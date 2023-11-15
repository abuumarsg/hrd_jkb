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
        <a class="back" onclick="window.history.back()"><i class="fa fa-chevron-circle-left "></i></a> <i class="fa fa-flask"></i> Rancangan
        <small><?php echo $nama; ?></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
        <li><a href="<?php echo base_url('pages/concept');?>"><i class="fa fa-flask"></i> Rancangan Penilaian Output</a></li>
        <li><a href="<?php echo base_url('pages/view_concept/'.$kode);?>"><i class="fa fa-gear"></i> <?php echo $nama_st;?></a></li>
        <li class="active"><?php echo $nama; ?></li>
      </ol>
    </section>
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-list"></i> Daftar Indikator <?php echo $nama; ?></h3>
              <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
              <div class="row">
                <div class="col-md-12">
                  <div class="callout callout-info"><label><i class="fa fa-info-circle"></i> Bantuan</label><br>
                    <ul>
                      <?php 
                      if (isset($nama_sub)) {
                        echo '<li><p><b>[SUB JABATAN]</b> <label class="label label-primary">'.$nama_sub.'</label></p></li>';
                      }
                      ?>
                      <li><p><b>[KOLOM URUTAN]</b> Nomor Urutan <b style="background-color: red;color: #fff;"> TIDAK BOLEH SAMA </b>, urutkan sesuai urutan indikator yang benar</li>
                      <li><p><b>[KOLOM PERUMUSAN]</b> Jika Bobot Penalty Parameter[A]=<b style="background-color: yellow;color: #000;"> 5 </b>% dan Parameter [B]=<b style="background-color: yellow;color: #000;"> 10 </b>% maka isikan <b style="background-color: yellow;color: #000;"> 5;10 </b>, untuk pembatas antar Bobot Parameter Pinalty Gunakan <b style="background-color: yellow;color: #000;"> ; </b></p></li>
                      <li><p><b>[KOLOM TARGET]</b> Target Minimal = <b style="background-color: yellow;color: #000;"> 0 </b> dan Target Maksimal = <b style="background-color: yellow;color: #000;"> 100% </b> dan <b style="background-color: yellow;color: #000;"> 125% </b></li>
                      <li><p><b>[KOLOM BOBOT]</b> Jumlah Bobot Total Harus = <b style="background-color: yellow;color: #000;"> 100% </b></li>  
                      <li><p><b>[KOLOM PENILAI]</b> Kolom Isian Pilih User Hanya Berlaku Untuk Penilai = <b style="background-color: yellow;color: #000;"> Pilih User</b></li>  
                      <li><p><label class="label label-danger" style="font-size: 9pt"><i class="fa fa-warning"></i> WARNING</label> Jika Anda Melakukan <b style="background-color: red;color: #fff;">Salah Input dan Tidak Sesuai Ketentuan Diatas</b> Maka Data Tidak Akan Disimpan ke Database</li>  
                    </ul>
                    

                  </div>
                  <?php 
                    $kon=$this->db->get_where($tabel,array('id_jabatan'=>$idj,'konsolidasi'=>'1'))->num_rows();
                    if ($kon > 0) {
                      echo '<div class="callout callout-success"><label><i class="fa fa-check-circle"></i> Konsolidasi</label><br>Jabatan ini terkena konsolidasi</div>';
                    }
                  ?>
                </div>
              </div>
              <br>
              <div class="row">
                <div class="col-md-12">
                  <?php 
                  if (count($view_s) == 0) {
                    echo '<div class="callout callout-danger"><label><i class="fa fa-info-circle"></i> Data Kosong</label><br>Tidak ada data yang ditampilkan</div>';
                  }else{
                    echo form_open('setting/up_setting');
                  ?>
                  <div class="table-responsive">
                  <table class="table table-bordered table-striped table-hover">
                    <thead>
                      <tr class="bg-blue">
                        <th>Urutan</th>
                        <th>Indikator</th>
                        <th>Cara Mengukur</th>
                        <th>Sumber Data</th>
                        <th>Perumusan</th>
                        <th>Target</th>
                        <th>Bobot</th>
                        <th>Satuan</th>
                        <th>Sifat</th>
                        <th>Penilai</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                        $n=1;
                        foreach ($view_s as $v) {
                          $res[$v->kode_indikator]=$v->kode_indikator;
                        }
                        foreach ($res as $d) {
                          if (isset($id_sub)) {
                            $dt=$this->db->get_where($tabel,array('kode_indikator'=>$d,'id_jabatan'=>$idj,'id_sub'=>$id_sub))->row_array();
                          }else{
                            $dt=$this->db->get_where($tabel,array('kode_indikator'=>$d,'id_jabatan'=>$idj))->row_array();
                          }
                          echo '<tr>
                          <td width="4%">';
                          $jmn=count($res);
                          for ($z=1; $z <=$jmn ; $z++) { 
                            $opu[$z]=$z;
                          }
                          if ($dt['urutan'] == NULL) {
                            $selu = array(1);
                          }else{
                            $selu = array($dt['urutan']);
                          }
                          $exu = array('class'=>'form-control select2','placeholder'=>'Urutan','required'=>'required','style'=>'width:100%');
                          echo form_dropdown('urutan['.$d.']',$opu,$selu,$exu);
                          echo '</td>
                          <td>';
                          $s=$dt['indikator'];
                          $max_length = 50;

                          if (strlen($s) > $max_length)
                          {
                            $offset = ($max_length - 3) - strlen($s);
                            $s = substr($s, 0, strrpos($s, ' ', $offset)) . '..';
                            echo '<span class="collapsed">
                            '.$s.' <a href="javascript:void(0)"><i class="fa fa-chevron-circle-right" data-toggle="tooltip" title="Lebih Banyak"></i></a>
                            </span>

                            <span class="expanded">
                            '.$dt['indikator'].' <a href="javascript:void(0)"><i class="fa fa-chevron-circle-left" data-toggle="tooltip" title="Lebih Sedikit"></i></a>
                            </span>';
                          }else{
                            echo $dt['indikator'];
                          }
                          echo '</td>
                          <td>';
                          $s=$dt['cara_mengukur'];
                          $max_length = 50;

                          if (strlen($s) > $max_length)
                          {
                            $offset = ($max_length - 3) - strlen($s);
                            $s = substr($s, 0, strrpos($s, ' ', $offset)) . '..';
                            echo '<span class="collapsed">
                            '.$s.' <a href="javascript:void(0)"><i class="fa fa-chevron-circle-right" data-toggle="tooltip" title="Lebih Banyak"></i></a>
                            </span>

                            <span class="expanded">
                            '.$dt['cara_mengukur'].' <a href="javascript:void(0)"><i class="fa fa-chevron-circle-left" data-toggle="tooltip" title="Lebih Sedikit"></i></a>
                            </span>';
                          }else{
                            echo $dt['cara_mengukur'];
                          }
                          echo '</td>
                          <td>';
                          if ($dt['sumber'] != "") {
                            echo $dt['sumber'];
                          }else{
                            echo '<label class="label label-danger">Tidak Ada Sumber</label>';
                          }
                          echo '</td>
                          <td width="10%"><input style="width:100%" type="text" name="rumus['.$d.']" style="min-width:200px;" class="form-control" value="'.$dt['rumus'].'" placeholder="Masukkan Rumus">
                          <small class="text-danger">Kosongkan Jika Tidak Berlaku Perumusan</small></td>
                          <td width="10%"><input style="width:100%" type="number" name="target['.$d.']" style="min-width:200px;" min="0" max="125" class="form-control" placeholder="Masukkan Target" id="in" value="';
                          if ($dt['target'] != 0) {
                            echo $dt['target']; 
                          }else{
                            echo '100';
                          }
                          echo '" required="required"></td>
                          <td width="10%">';
                          if ($dt['bobot'] == 0) {
                            echo '<input style="width:100%" type="number" name="bobot['.$d.']" style="min-width:200px;" min="0" max="100" class="form-control" id="in'.$n.'" placeholder="Masukkan Bobot Penilaian" step="0.01" required="required">';
                          }else{
                            echo '<input style="width:100%" type="number" name="bobot['.$d.']" style="min-width:200px;" min="0" max="100" class="form-control" id="in'.$n.'" placeholder="Masukkan Bobot Penilaian" step="0.01" value="'.$dt['bobot'].'" required="required">';
                          }
                          $bbt[$n]=$dt['bobot'];
                          echo '</td>
                          <td>';
                          $op=array('%'=>'%');
                          $sel = array($dt['satuan']);
                          $ex = array('class'=>'form-control select2','placeholder'=>'Jenis Kelamin','required'=>'required','style'=>'width:100%');
                          echo form_dropdown('satuan['.$d.']',$op,$sel,$ex);
                          echo '</td> 
                          <td>';
                          $op1=array('1'=>'Individu','2'=>'Kolektif','3'=>'Individu & Kolektif',);
                          $sel1 = array($dt['sifat']);
                          $ex1 = array('class'=>'form-control select2','placeholder'=>'Jenis Kelamin','required'=>'required','style'=>'width:100%');
                          echo form_dropdown('sifat['.$d.']',$op1,$sel1,$ex1);
                          echo '</td>
                          <td>';
                          $jj=$this->db->get_where('master_jabatan',array('id_jabatan'=>$idj,'status'=>'aktif'))->row_array();
                          if ($jj['atasan'] == "TERTINGGI") {
                            $dta=$this->db->query("SELECT * FROM master_penilai WHERE kode_penilai != 'P1' AND kode_penilai != 'P2' AND status = 'aktif'")->result();
                          }else{
                            $dta=$this->db->get_where('master_penilai',array('status'=>'aktif'))->result();
                          }
                          $op2[NULL]="Pilih Penilai";
                          foreach ($dta as $a) {
                            $op2[$a->kode_penilai]=$a->penilai;
                          }
                          $sel2 = array($dt['kode_penilai']);
                          $ex2 = array('class'=>'form-control select2','placeholder'=>'Jenis Kelamin','required'=>'required','style'=>'width:100%','id'=>'pn'.$n,'onchange'=>'change('.$n.')');
                          echo form_dropdown('penilai['.$d.']',$op2,$sel2,$ex2);
                          if ($dt['kode_penilai'] == "P4") {
                            echo '<div id="shwx'.$n.'">';
                          }else{
                            echo '<div id="shwx'.$n.'" style="display:none;">';
                          }
                          echo '<br>
                          <p class="text-danger text-center">Pilih User</p>';
                          
                          $dtax[$n]=$this->db->query("SELECT id_karyawan,nama FROM karyawan")->result();
                          $opx2[$n]['ALL']="Semua Karyawan";
                          foreach ($dtax[$n] as $ax) {
                            $opx2[$n][$ax->id_karyawan]=$ax->nama;
                            
                          }
                          $sll[$n]=$dt['id_penilai'];
                          if ($sll[$n] != NULL) {
                            $var[$n]=explode(';', $sll[$n]);
                            if (count($var[$n]) == count($dtax[$n])) {
                              $selx2=array('ALL');
                            }else{
                              $selx2[$n]=array();
                              foreach ($var[$n] as $vx) {
                                array_push($selx2[$n], $vx);
                              }
                            }
                          }else{
                            $selx2[$n]=array(NULL);
                          }
                          $exx2[$n] = array('class'=>'form-control select2','data-placeholder'=>'Pilih Karyawan','multiple'=>'multiple','style'=>'width:100%;');
                          echo form_dropdown('penilai_ky['.$d.'][]',$opx2[$n],$selx2[$n],$exx2[$n]);
                          echo '</div>
                          </td>
                          </tr>';
                          $n++; 
                        }
                                                
                      ?>
                    </tbody>
                  </table>
                </div>
                <?php } ?>
                </div>
              </div>
            </div>
            <div class="box-footer">
              <div class="pull-left"> 

                <label style="font-size: 12pt;">Jumlah Bobot Harus 100%<input id="3" class="form-control input-md" disabled /></label>

              </div>
              <?php if (in_array($access['l_ac']['edt'], $access['access'])) {?>
              <div class="form-group pull-right">
                <input type="hidden" name="kode" value="<?php echo $kode;?>">
                <input type="hidden" name="tabel" value="<?php echo $tabel;?>">
                <input type="hidden" name="jabatan" value="<?php echo $idj;?>">
                <input type="hidden" name="sub" value="<?php echo $id_sub;?>">
                <button class="btn btn-danger" type="reset" onclick="function myFunction() {location.reload();}"><i class="fa fa-refresh"></i> Reset</button>
                <?php
                  if (isset($bbt)) {
                    if (array_sum($bbt) == 100) {
                      echo '<button class="btn btn-success" type="submit" id="save"><i class="fa fa-floppy-o"></i> Simpan</button>';
                    }else{
                      echo '<button class="btn btn-success" type="submit" id="save" disabled="disabled"><i class="fa fa-floppy-o"></i> Simpan</button>';
                    }
                  }else{
                    echo '<button class="btn btn-success" type="submit" id="save" disabled="disabled"><i class="fa fa-floppy-o"></i> Simpan</button>';
                  }
                ?>
                
              </div>
              <?php } echo form_close();?>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="box box-success">
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-users"></i> Daftar Karyawan</h3>
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
                      <?php if (in_array($access['l_ac']['add'], $access['access'])) {?>
                      <button class="btn btn-success btn-flat" type="button" data-toggle="collapse" data-target="#add" aria-expanded="false" aria-controls="import"><i class="fa fa-plus"></i> Tambah Karyawan</button>
                      <div class="row">
                        <div class="col-md-12">
                          <div class="collapse" id="add">
                            <br>
                            <div class="col-md-2"></div>
                            <div class="col-md-8">
                              <?php 
                              if (isset($kode_sub)) {
                                $kry=$this->db->get_where('karyawan',array('jabatan'=>$kdj,'kode_sub'=>$kode_sub))->result();
                              }else{
                                $kry=$this->db->get_where('karyawan',array('jabatan'=>$kdj))->result();
                              }
                              foreach ($view_s as $vx1) {
                                $resx1[$vx1->id_karyawan]=$vx1->id_karyawan;
                              }
                              $sa=array();
                              foreach ($kry as $k) {
                                if (!in_array($k->id_karyawan, $resx1)) {
                                  $op12[$k->id_karyawan]=$k->nama;
                                }else{
                                  array_push($sa, 1);
                                }
                              }
                              if (count($sa) == count($kry)) {
                                echo '<div class="callout callout-danger"><label><i class="fa fa-info-circle"></i> Data Kosong</label><br>Karyawan sudah dimasukkan semua</div>';
                              }else{
                                echo form_open('setting/add_emp_config');
                                echo '<div class="form-group">';
                                $sel1 = array(NULL);
                                $ex1 = array('class'=>'form-control select2','multiple'=>'multiple','data-placeholder'=>'Pilih Karyawan','required'=>'required','style'=>'width:100%;');
                                echo form_dropdown('karyawan[]',$op12,$sel1,$ex1);
                                echo ' 
                                </div>
                                <div class="form-group">
                                  <input type="hidden" name="sub" value="'.$id_sub.'">
                                  <input type="hidden" name="kode" value="'.$kode.'">
                                  <input type="hidden" name="tabel" value="'.$tabel.'">
                                  <input type="hidden" name="jabatan" value="'.$idj.'">
                                  <button type="submit" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
                                </div>
                                '.form_close().'';
                              }
                              ?>
                            </div>
                          </div>
                        </div>
                      </div><?php } ?>
                    </div>
                  </div>
                  <br>
                  <?php
                  if (count($view_s) == 0) {
                    echo '<div class="callout callout-danger"><label><i class="fa fa-info-circle"></i> Data Kosong</label><br>Tidak ada data yang ditampilkan</div>';
                  }else{
                  ?>
                  <table id="example2" class="table table-bordered table-striped table-hover">
                    <thead>
                      <tr>
                        <th>No.</th>
                        <th>Nomor Induk</th>
                        <th>Nama Karyawan</th>
                        <th>Lokasi Kerja</th>
                        <th>Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                        $n1=1;
                        foreach ($view_s as $v1) {
                          $res1[$v1->id_karyawan]=$v1->id_karyawan;
                        }
                        foreach ($res1 as $d1) {
                          $dt1=$this->db->get_where('karyawan',array('id_karyawan'=>$d1))->row_array();
                          $lok=$this->db->get_where('master_loker',array('kode_loker'=>$dt1['unit']))->row_array();
                          echo '<tr>
                          <td width="5%">'.$n1.'.</td>
                          <td>';
                          if ($dt1['nik'] == "") {
                            echo '<label class="label label-danger">Karyawan Dimutasi Atau Sudah Dihapus</label>';
                          }else{
                            echo $dt1['nik'];
                          }
                          echo '</td>
                          <td>';
                          if ($dt1['nama'] == "") {
                            echo '<label class="label label-danger">Karyawan Dimutasi Atau Sudah Dihapus</label>';
                          }else{
                            echo $dt1['nama'];
                          }
                          echo '</td>
                          <td class="text-center">'.$lok['nama'].'</td>
                          <td class="text-center">';
                            if (in_array($access['l_ac']['del'], $access['access'])) {
                              echo '<a href="#del'.$n1.'" data-toggle="modal" class="btn btn-danger"><i class="fa fa-trash" data-toggle="tooltip" title="Hapus Karyawan"></i></a>';
                            }else{
                              echo '<label class="label label-danger">Tidak Diizinkan</label>';
                            }
                          echo '</td>
                          </tr>';
                          if (in_array($access['l_ac']['del'], $access['access'])) {
                            echo '<div id="del'.$n1.'" class="modal fade" role="dialog">
                            <div class="modal-dialog modal-sm modal-danger">
                            <div class="modal-content">
                            <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title text-center">Konfirmasi Hapus</h4>
                            </div>
                            <div class="modal-body text-center">
                            <p>Apakah anda yakin karyawan dengan nama <b>';
                            if ($dt1['nama'] == "") {
                              echo '<label class="label label-primary">Karyawan Dimutasi Atau Sudah Dihapus</label>';
                            }else{
                              echo $dt1['nama'];
                            }
                            echo '</b> tidak diikutkan dalam penilaian kinerja ?</p>
                            </div>
                            <div class="modal-footer">
                            '.form_open('setting/del_employee_task').'
                            <input type="hidden" name="id" value="'.$d1.'">
                            <input type="hidden" name="tabel" value="'.$tabel.'">
                            <input type="hidden" name="idj" value="'.$idj.'">
                            <input type="hidden" name="kode" value="'.$kode.'">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-trash"></i> Hapus</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
                            '.form_close().'
                            </div>
                            </div>
                            </div>
                            </div>';
                          }
                          $n1++; 
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
