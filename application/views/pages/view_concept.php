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
        <li class="active"><?php echo $nama; ?></li>
      </ol>
    </section>
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box box-primary">
            <div class="box-header with-border"> 
              <h3 class="box-title"><i class="fa fa-gear"></i> <?php echo $nama; ?></h3>
              <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body" style="overflow-y: scroll; height: 900px">
              <div class="row">
                <div class="col-md-12"> 
                  <div class="callout callout-info"><label><i class="fa fa-info-circle"></i> Bantuan</label><br>Pilih Jabatan dan Karyawan yang akan diberikan indikator</div>
                </div>
              </div>
              <br>
              <div class="row">
                <div class="col-md-12">
                  <?php 
                  if (count($jabatan) == 0) {
                    echo '<div class="callout callout-danger"><label><i class="fa fa-info-circle"></i> Data Kosong</label><br>Tidak ada data yang ditampilkan</div>';
                  }else{
                    echo form_open('setting/add_config');
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
                        
                        foreach ($jabatan as $l) {
                          $nmj=$l->kode_jabatan;
                          $sub=$this->db->get_where('master_sub_jabatan',array('kode_jabatan'=>$nmj,'status'=>'aktif'))->result();
                          if (count($sub) != 0) {
                            foreach ($sub as $sb) {
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
                      ?>
                    </tbody>
                  </table>
                  
                </div>
              </div>
            </div><?php if (in_array($access['l_ac']['add'], $access['access'])) {?>
            <div class="box-footer">
              <div class="form-group pull-right">
                    <input type="hidden" name="kode" value="<?php echo $kode;?>">
                    <button class="btn btn-danger" type="reset" onclick="function myFunction() {location.reload();}"><i class="fa fa-refresh"></i> Reset</button>
                    <button class="btn btn-success" type="submit" id="save"><i class="fa fa-floppy-o"></i> Simpan</button>
                  </div>
                  <?php }
                echo form_close();
               }?>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div> 
