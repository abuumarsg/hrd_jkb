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
        <a class="back" onclick="window.history.back()"><i class="fa fa-chevron-circle-left "></i></a> <i class="fa fa-balance-scale"></i> Data Anggaran
        <small><?php echo $nama; ?></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
        <li><a href="<?php echo base_url('pages/anggaran');?>"><i class="fa fa-calendar-check-o"></i> Daftar Data Anggaran</a></li>
        <li class="active"><?php echo $nama; ?></li>
      </ol>
    </section>
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-balance-scale"></i> <?php echo $nama; 
              if ($smt == 1) {
                echo ' <label class="label label-info">Semester 1</label>';
              }else{
                echo ' <label class="label label-success">Semester 2</label>';
              }
              ?></h3>
              <div class="box-tools pull-right">
                <?php
                  if ($edit == 0) {
                    echo ' <label class="label label-danger">Update Data Dikunci</label>';
                  }else{
                    echo ' <label class="label label-success">Update Data Dibuka</label>';
                  }
                ?>
                <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
              <div class="row">
                <div class="col-md-12">
                  <?php 
                  if (count($agenda) != 0) {
                  ?>
                  <div class="row">
                    <div class="col-md-12">
                      <?php 
                      if (in_array($access['l_ac']['imp'], $access['access'])) {
                        echo '<button class="btn btn-primary btn-flat" type="button" data-toggle="modal" data-target="#import" aria-expanded="false" aria-controls="import"><i class="fa fa-cloud-upload"></i> Import</button> 
                        <div class="modal fade" id="import" role="dialog">
                        <div class="modal-dialog">
                          <div class="modal-content text-center">
                            <div class="modal-header">
                              <button type="button" class="close" data-dismiss="modal">&times;</button>
                              <h4 class="modal-title">Import Data Dari Excel</h4>
                            </div>
                            <div class="modal-body">
                              '.form_open_multipart('anggaran/import').'
                              <p style="color:red;">File Data Aanggaran harus tipe *.xls, *.xlsx, *.csv, *.ods dan *.ots</p>
                              <input id="uploadFile" placeholder="Nama File" disabled="disabled" class="form-control" required="required" >
                              <span class="input-group-btn">
                                <div class="fileUpload btn btn-warning btn-flat">
                                  <span><i class="fa fa-folder-open"></i> Pilih File</span>
                                  <input id="uploadBtn" type="file" class="upload" name="file"/>
                                </div>
                              </span>
                            </div>
                            <div class="modal-footer">
                              <input type="hidden" name="kode" value="<?php echo $kode;?>">
                              <input type="hidden" name="kode_agd" value="'.$agenda['kode_agenda'].'">
                              <input type="hidden" name="tabel_agd" value="'.$agenda['tabel_agenda'].'">
                              <button class="btn btn-flat btn-primary" id="save" type="submit" disabled><i class="fa fa-check-circle"></i> Upload</button>
                              <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
                              <?php echo form_close();?>
                            </div>
                          </div>
                        </div>
                      </div>';
                      }if (in_array($access['l_ac']['exp'], $access['access'])) {
                        echo '<button class="btn btn-success btn-flat" type="button" data-toggle="modal" data-target="#export" aria-expanded="false" aria-controls="export"><i class="fa fa-cloud-download"></i> Export</button>';
                      
                      ?>
                      
                      <div class="modal fade" id="export" role="dialog">
                        <div class="modal-dialog">
                          <div class="modal-content text-center">
                            <div class="modal-header">
                              <button type="button" class="close" data-dismiss="modal">&times;</button>
                              <h4 class="modal-title">Export Data Ke Excel</h4>
                            </div>
                            <div class="modal-body">
                              <?php 
                                $tbagd=$agenda['tabel_agenda'];
                                $ts=$this->db->query("SELECT kode_indikator FROM $tbagd WHERE kaitan = '1'")->result();
                                foreach ($ts as $t) {
                                  $ind[$t->kode_indikator]=$t->kode_indikator;
                                }
                                if (isset($ind)) {
                                  
                                
                              ?>
                              <p>Pilih Indikator Anggaran</p>
                              <?php echo form_open('anggaran/export');
                              
                              foreach ($ind as $a) {
                                $indi=$this->db->get_where('master_indikator',array('kode_indikator'=>$a))->row_array();
                                $s=$indi['kpi'];
                                $max_length = 50;
                                if (strlen($s) > $max_length)
                                {
                                  $offset = ($max_length - 3) - strlen($s);
                                  $s = substr($s, 0, strrpos($s, ' ', $offset)) . '..';
                                  $word=$s;
                                }else{
                                  $word=$indi['kpi'];
                                }
                                $op12[$a]=$word;
                              }
                              $sel1 = array(NULL);
                              $ex1 = array('class'=>'form-control select2','multiple'=>'multiple','data-placeholder'=>'Pilih Indikator Anggaran','required'=>'required','style'=>'width:100%;');
                              echo form_dropdown('indi[]',$op12,$sel1,$ex1);
                              }else{
                                echo '<div class="callout callout-danger text-left"><label><i class="fa fa-info-circle"></i> Data Kosong</label><br> Tidak ada data yang ditampilkan</div>';
                              }

                              ?>

                            </div>
                            <div class="modal-footer">
                              <?php 
                              if (isset($ind)) {
                                
                              ?>
                              <input type="hidden" name="kode" value="<?php echo $kode;?>">
                              <input type="hidden" name="kode_agd" value="<?php echo $agenda['kode_agenda'];?>">
                              <input type="hidden" name="tabel_agd" value="<?php echo $agenda['tabel_agenda'];?>">
                              <button class="btn btn-success btn-flat" type="submit"><i class="fa fa-check-circle"></i> Download</button><?php } ?>
                              <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
                              <?php echo form_close();?>
                            </div> 
                          </div>
                        </div>
                      </div><?php } ?>
                    </div>
                  </div>
                  <br>
                  <?php 
                  }
                  if (count($view) == 0) {
                    echo '<div class="callout callout-danger"><label><i class="fa fa-info-circle"></i> Data Kosong</label><br>Tidak ada data yang ditampilkan</div>';
                  }else{
                  ?>
                  <table class="table table-bordered table-hover table-striped" id="example1">
                    <thead>
                    <tr>
                      <th>No.</th>
                      <th>Nomor Induk</th>
                      <th>Nama</th>
                      <th>Indikator</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                      foreach ($view as $l) {
                        $idk[$l->id_karyawan]=$l->id_karyawan;
                      }
                      $n=1;
                      foreach ($idk as $i) {
                        $ky=$this->db->query("SELECT nik,nama FROM karyawan WHERE id_karyawan = '$i'")->row_array();
                        $cind=explode(',', $kind);
                            echo '<tr>
                                <td>'.$n.'.</td>
                                <td>'.$ky['nik'].'</td>
                                <td>'.$ky['nama'].'</td>
                                <td width="70%"><a href="#dt'.$n.'" data-toggle="collapse">'.count($cind).' Indikator</a>
                                <div id="dt'.$n.'" class="collapse">
                                  <table class="table table-hover">
                                    <thead>
                                      <tr class="bg-green">
                                        <th>Indikator</th>
                                        <th>Anggaran</th>
                                        <th>Riil</th>
                                        <th>Pencapaian</th>
                                      </tr>
                                    </thead>
                                    <tbody>';
                                  foreach ($cind as $inx) {
                                    $dtv=$this->db->get_where($tabel,array('id_karyawan'=>$i,'kode_indikator'=>$inx))->row_array();
                                    $kpi=$this->db->get_where('master_indikator',array('kode_indikator'=>$inx))->row_array();
                                    echo '<tr>
                                      <td>'.$kpi['kpi'].'</td>
                                      <td>'.number_format($dtv['n1'],0,',','.').'</td>
                                      <td>'.number_format($dtv['n2'],0,',','.').'</td>
                                      <td>'.number_format($dtv['na'],2).'</td>
                                    </tr>';
                                  }
                                  echo '</tbody>
                                  </table>
                                </div>
                                </td>
                              </tr>';
                          $n++; 
                      }

                    ?>  
                    </tbody>
                  </table>
                  <?php 
                  } 
                  ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div> 