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
        <a class="back" onclick="window.history.back()"><i class="fa fa-chevron-circle-left "></i></a> <i class="fa fa-crosshairs"></i> Target Corporate
        <small><?php echo $nama; ?></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
        <li><a href="<?php echo base_url('pages/target_corporate');?>"><i class="fa fa-crosshairs"></i> Target Corporate</a></li>
        <li class="active"><?php echo $nama; ?></li> 
      </ol>
    </section>
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-list"></i> <?php echo $nama; 
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
                  <?php if ($edit == 1) { ?>
                  <div class="row">
                    <div class="col-md-12">
                      <?php echo form_open('target/export');?>
                      <input type="hidden" name="kode" value="<?php echo $kode;?>">
                      <input type="hidden" name="tabel" value="<?php echo $tabel;?>">
                      <?php if (in_array($access['l_ac']['imp'], $access['access'])) {
                        echo '<button class="btn btn-primary btn-flat" type="button" data-toggle="modal" data-target="#import" aria-expanded="false" aria-controls="import"><i class="fa fa-cloud-upload"></i> Import</button> ';
                      }if (in_array($access['l_ac']['exp'], $access['access'])) {
                        echo '<button class="btn btn-success btn-flat" type="submit" aria-expanded="false" aria-controls="export"><i class="fa fa-cloud-download"></i> Export</button>';
                      }
                      echo form_close();
                      if (in_array($access['l_ac']['imp'], $access['access'])) {
                      ?>
                      <div class="modal fade" id="import" role="dialog">
                        <div class="modal-dialog">
                          <div class="modal-content text-center">
                            <div class="modal-header">
                              <button type="button" class="close" data-dismiss="modal">&times;</button>
                              <h4 class="modal-title">Import Data Dari Excel</h4>
                            </div>
                            <div class="modal-body">
                              <?php echo form_open_multipart('target/import');?>
                              <p style="color:red;">File Data Target Corporate harus tipe *.xls, *.xlsx, *.csv, *.ods dan *.ots</p>
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
                              <input type="hidden" name="tabel" value="<?php echo $tabel;?>">
                              <button class="btn btn-flat btn-primary" id="save" type="submit" disabled><i class="fa fa-check-circle"></i> Upload</button>
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
                    echo form_open('target/add_value');
                  ?>
                  <table id="example1" class="table table-bordered table-striped table-hover">
                    <thead>
                      <tr>
                        <th>No.</th>
                        <th>Nama</th>
                        <?php
                          if ($smt == "1") {
                            echo '<th>Anggaran Juni '.$th.'</th>
                            <th>OS Desember '.date("Y",strtotime("-1 Years",strtotime($th))).'</th>
                            <th>Target Growth</th>
                            <th>OS Juni '.$th.'</th>';
                          }else{
                            echo '<th>Anggaran Desember '.$th.'</th>
                            <th>OS Juni '.date("Y",strtotime("-1 Years",strtotime($th))).'</th>
                            <th>Target Growth</th>
                            <th>OS Desember '.$th.'</th>';
                          }
                        ?>
                        <th>Growth</th>
                        <th>Pencapaian</th>
                        <th>Nilai</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                        $n=1;
                        
                        foreach ($view as $l) {
                          if ($l->id_target == 1) {
                            echo '<tr>
                                  <td width="3%" class="bg-warning">'.$n.'.</td>
                                  <td class="bg-warning">'.$l->nama.'</td>';
                                  if ($smt == "1") {
                                    echo '<td class="bg-warning">'.number_format($l->anggaran_juni,0,',','.').'</td>
                                    <td class="bg-warning">'.number_format($l->os_desember,0,',','.').'</td>
                                    <td class="bg-primary">'.number_format($l->target_growth,0,',','.').'</td>
                                    <td class="bg-warning">'.number_format($l->os_juni,0,',','.').'</td>';
                                  }else{
                                    echo '<td>'.number_format($l->anggaran_desember,0,',','.').'</td>
                                    <td class="bg-warning">'.number_format($l->os_juni,0,',','.').'</td>
                                    <td class="bg-primary">'.number_format($l->target_growth,0,',','.').'</td>
                                    <td class="bg-warning">'.number_format($l->os_desember,0,',','.').'</td>';
                                  }

                                  echo '<td class="bg-primary">'.number_format($l->growth,0,',','.').'</td>
                                  <td class="bg-primary">'.$l->pencapaian.'</td>
                                  <td class="bg-primary">'.$l->nilai.'</td>
                                </tr>';
                          }else{
                            echo '<tr>
                                  <td width="3%">'.$n.'.</td>
                                  <td>'.$l->nama.'</td>';
                                  if ($smt == "1") {
                                    if ($edit == 0) {
                                      echo '<td>'.number_format($l->anggaran_juni,0,',','.').'</td>
                                      <td>'.number_format($l->os_desember,0,',','.').'</td>
                                      <td class="bg-primary">'.number_format($l->target_growth,0,',','.').'</td>
                                      <td>'.number_format($l->os_juni,0,',','.').'</td>';
                                    }else{
                                      echo '<td width="15%"><input type="number" style="width:100%;" name="agg['.$l->id_target.']" class="form-control" value="'.$l->anggaran_juni.'" required="required"></td>
                                      <td width="15%"><input type="number" style="width:100%;"  name="os1['.$l->id_target.']" class="form-control" value="'.$l->os_desember.'" required="required"></td>
                                      <td class="bg-primary">'.number_format($l->target_growth,0,',','.').'</td>
                                      <td width="15%"><input type="number" style="width:100%;"  name="os2['.$l->id_target.']" class="form-control" value="'.$l->os_juni.'" required="required"></td>';
                                    }
                                    
                                  }else{
                                    if ($edit == 0) {
                                      echo '<td>'.number_format($l->anggaran_desember,0,',','.').'</td>
                                      <td>'.number_format($l->os_juni,0,',','.').'</td>
                                      <td class="bg-primary">'.number_format($l->target_growth,0,',','.').'</td>
                                      <td>'.number_format($l->os_desember,0,',','.').'</td>';
                                    }else{
                                      echo '<td width="15%"><input type="number" style="width:100%;"  name="agg['.$l->id_target.']" class="form-control" value="'.$l->anggaran_desember.'" required="required"></td>
                                      <td width="15%"><input type="number" style="width:100%;"  name="os1['.$l->id_target.']" class="form-control" value="'.$l->os_juni.'" required="required"></td>
                                      <td class="bg-primary">'.number_format($l->target_growth,0,',','.').'</td>
                                      <td width="15%"><input type="number" style="width:100%;"  name="os2['.$l->id_target.']" class="form-control" value="'.$l->os_desember.'" required="required"></td>';
                                    }
                                  }

                                  echo '<td class="bg-primary">'.number_format($l->growth,0,',','.').'</td>
                                  <td class="bg-primary">'.$l->pencapaian.'</td>
                                  <td class="bg-primary">'.$l->nilai.'</td>
                                </tr>';
                          }
                          
                          $n++;      
                        }
                      ?>
                    </tbody>
                  </table>
                  <?php if ($edit == 1 && in_array($access['l_ac']['edt'], $access['access'])) { ?>
                  <div class="form-group pull-right">
                    <input type="hidden" name="tabel" value="<?php echo $tabel;?>">
                    <input type="hidden" name="smt" value="<?php echo $smt;?>">
                    <input type="hidden" name="kode" value="<?php echo $kode;?>">
                    <button class="btn btn-danger" type="reset"><i class="fa fa-refresh"></i> Reset</button>
                    <button class="btn btn-success" type="submit" id="save1"><i class="fa fa-floppy-o"></i> Simpan</button>
                  </div>
                  <?php }
                  } 
                  echo form_close();
                  ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div> 