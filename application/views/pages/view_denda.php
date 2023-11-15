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
        <a class="back" onclick="window.history.back()"><i class="fa fa-chevron-circle-left "></i></a> <i class="fa fa-money"></i> Data Denda
        <small><?php echo $nama; ?></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
        <li><a href="<?php echo base_url('pages/denda');?>"><i class="fa fa-money"></i> Daftar Data Denda</a></li>
        <li class="active"><?php echo $nama; ?></li>
      </ol>
    </section>
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-list"></i> <?php echo $nama; 
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
            <div class="box-body" style="overflow: scroll;">
              <div class="row">
                <div class="col-md-12">
                  <?php if ($edit == 1) { ?>
                  <div class="row">
                    <div class="col-md-12">
                      <?php echo form_open_multipart('denda/export');
                      if (in_array($access['l_ac']['imp'], $access['access'])) {
                      ?>
                      <button class="btn btn-primary btn-flat" type="button" data-toggle="modal" data-target="#import" aria-expanded="false" aria-controls="import"><i class="fa fa-cloud-upload"></i> Import</button><?php 
                      }if (in_array($access['l_ac']['exp'], $access['access'])) {
                        echo '<input type="hidden" name="kode" value="'.$kode.'">
                        <input type="hidden" name="tabel" value="'.$tabel.'">
                        <button class="btn btn-success btn-flat" type="submit" aria-expanded="false" aria-controls="export"><i class="fa fa-cloud-download"></i> Export</button>'; 
                      }
                      ?>
                      <?php echo form_close();
                      if (in_array($access['l_ac']['imp'], $access['access'])) {?>
                      <div class="modal fade" id="import" role="dialog">
                        <div class="modal-dialog">
                          <div class="modal-content text-center">
                            <div class="modal-header">
                              <button type="button" class="close" data-dismiss="modal">&times;</button>
                              <h4 class="modal-title">Import Data Dari Excel</h4>
                            </div>
                            <div class="modal-body">
                              <?php echo form_open_multipart('denda/import');?>
                              <p style="color:red;">File Data Denda harus tipe *.xls, *.xlsx, *.csv, *.ods dan *.ots</p>
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
                      </div></div>
                    </div><?php } ?>
                  <br>
                  <?php
                  } 
                  if (count($view) == 0) {
                    echo '<div class="callout callout-danger"><label><i class="fa fa-info-circle"></i> Data Kosong</label><br>Tidak ada data yang ditampilkan</div>';
                  }else{
                    echo form_open('denda/add_value');
                  ?>
                  <table class="table table-bordered table-hover table-striped" id="example1">
                    <thead>
                    <tr class="bg-green">
                      <th>No.</th>
                      <th>Nama Kantor</th>
                      <th>Keterangan</th>
                      <?php
                          if ($smt == 1) {
                            echo '<th>Januari '.$th.'</th>
                            <th>Februari '.$th.'</th>
                            <th>Maret '.$th.'</th>
                            <th>April '.$th.'</th>
                            <th>Mei '.$th.'</th>
                            <th>Juni '.$th.'</th>';
                          }else{
                            echo '<th>Juli '.$th.'</th>
                            <th>Agustus '.$th.'</th>
                            <th>September '.$th.'</th>
                            <th>Oktober '.$th.'</th>
                            <th>November '.$th.'</th>
                            <th>Desember '.$th.'</th>
                            ';
                          }
                        ?>
                        <th>Rata - Rata </th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                      $n=1;
                      foreach ($view as $l) {
                        if ($edit == 1) {
                          if ($l->id_denda == 1) {
                            echo '<tr>
                                <td rowspan="4">'.$n.'.</td>
                                <td rowspan="4">'.$l->nama.'</td>
                                <td>PYD</td>
                                <td>'.number_format($l->pyd1,0,',','.').'</td>
                                <td>'.number_format($l->pyd2,0,',','.').'</td>
                                <td>'.number_format($l->pyd3,0,',','.').'</td>
                                <td>'.number_format($l->pyd4,0,',','.').'</td>
                                <td>'.number_format($l->pyd5,0,',','.').'</td>
                                <td>'.number_format($l->pyd6,0,',','.').'</td>
                                <td>'.number_format($l->ratapyd,0,',','.').'</td>
                              </tr>
                              <tr>
                                <td>Pendapatan Denda</td>
                                <td>'.number_format($l->pd1,0,',','.').'</td>
                                <td>'.number_format($l->pd2,0,',','.').'</td>
                                <td>'.number_format($l->pd3,0,',','.').'</td>
                                <td>'.number_format($l->pd4,0,',','.').'</td>
                                <td>'.number_format($l->pd5,0,',','.').'</td>
                                <td>'.number_format($l->pd6,0,',','.').'</td>
                                <td>'.number_format($l->ratapd,0,',','.').'</td>
                              </tr>';
                          }else{
                            echo '<tr>
                                <td rowspan="4">'.$n.'.</td>
                                <td rowspan="4">'.$l->nama.'</td>
                                <td>PYD</td>
                                <td><input type="number" name="pyd1['.$l->id_denda.']" value="'.$l->pyd1.'" min="0" class="form-control" required="required"></td>
                                <td><input type="number" name="pyd2['.$l->id_denda.']" value="'.$l->pyd2.'" min="0"  class="form-control" required="required"></td>
                                <td><input type="number" name="pyd3['.$l->id_denda.']" value="'.$l->pyd3.'" min="0"  class="form-control" required="required"></td>
                                <td><input type="number" name="pyd4['.$l->id_denda.']" value="'.$l->pyd4.'" min="0"  class="form-control" required="required"></td>
                                <td><input type="number" name="pyd5['.$l->id_denda.']" value="'.$l->pyd5.'" min="0"  class="form-control" required="required"></td>
                                <td><input type="number" name="pyd6['.$l->id_denda.']" value="'.$l->pyd6.'" min="0"  class="form-control" required="required"></td>
                                <td>'.number_format($l->ratapyd,0,',','.').'</td>
                              </tr>
                              <tr>
                                <td>Pendapatan Denda</td>
                                <td><input type="number" name="pd1['.$l->id_denda.']" value="'.$l->pd1.'" min="0"  class="form-control" required="required"></td>
                                <td><input type="number" name="pd2['.$l->id_denda.']" value="'.$l->pd2.'" min="0"  class="form-control" required="required"></td>
                                <td><input type="number" name="pd3['.$l->id_denda.']" value="'.$l->pd3.'" min="0"  class="form-control" required="required"></td>
                                <td><input type="number" name="pd4['.$l->id_denda.']" value="'.$l->pd4.'" min="0"  class="form-control" required="required"></td>
                                <td><input type="number" name="pd5['.$l->id_denda.']" value="'.$l->pd5.'" min="0"  class="form-control" required="required"></td>
                                <td><input type="number" name="pd6['.$l->id_denda.']" value="'.$l->pd6.'" min="0"  class="form-control" required="required"></td>
                                <td>'.number_format($l->ratapd,0,',','.').'</td>
                              </tr>';
                          }
                          echo '<tr>
                                <td>Target (Acuan 0,1%)</td>
                                <td>'.number_format($l->tgt1,0,',','.').'</td>
                                <td>'.number_format($l->tgt2,0,',','.').'</td>
                                <td>'.number_format($l->tgt3,0,',','.').'</td>
                                <td>'.number_format($l->tgt4,0,',','.').'</td>
                                <td>'.number_format($l->tgt5,0,',','.').'</td>
                                <td>'.number_format($l->tgt6,0,',','.').'</td>
                                <td>'.number_format($l->ratatgt,0,',','.').'</td>
                              </tr>
                              <tr>
                                <td class="bg-primary">PA</td>
                                <td class="bg-primary">'.number_format($l->pa1,2).'</td>
                                <td class="bg-primary">'.number_format($l->pa2,2).'</td>
                                <td class="bg-primary">'.number_format($l->pa3,2).'</td>
                                <td class="bg-primary">'.number_format($l->pa4,2).'</td>
                                <td class="bg-primary">'.number_format($l->pa5,2).'</td>
                                <td class="bg-primary">'.number_format($l->pa6,2).'</td>
                                <td class="bg-yellow">'.number_format($l->ratapa,2).'</td>
                              </tr>';
                          
                        }else{
                          echo '<tr>
                                <td rowspan="4">'.$n.'.</td>
                                <td rowspan="4">'.$l->nama.'</td>
                                <td>PYD</td>
                                <td>'.number_format($l->pyd1,0,',','.').'</td>
                                <td>'.number_format($l->pyd2,0,',','.').'</td>
                                <td>'.number_format($l->pyd3,0,',','.').'</td>
                                <td>'.number_format($l->pyd4,0,',','.').'</td>
                                <td>'.number_format($l->pyd5,0,',','.').'</td>
                                <td>'.number_format($l->pyd6,0,',','.').'</td>
                                <td>'.number_format($l->ratapyd,0,',','.').'</td>
                              </tr>
                              <tr>
                                <td>Pendapatan Denda</td>
                                <td>'.number_format($l->pd1,0,',','.').'</td>
                                <td>'.number_format($l->pd2,0,',','.').'</td>
                                <td>'.number_format($l->pd3,0,',','.').'</td>
                                <td>'.number_format($l->pd4,0,',','.').'</td>
                                <td>'.number_format($l->pd5,0,',','.').'</td>
                                <td>'.number_format($l->pd6,0,',','.').'</td>
                                <td>'.number_format($l->ratapd,0,',','.').'</td>
                              </tr>
                              <tr>
                                <td>Target (Acuan 0,1%)</td>
                                <td>'.number_format($l->tgt1,0,',','.').'</td>
                                <td>'.number_format($l->tgt2,0,',','.').'</td>
                                <td>'.number_format($l->tgt3,0,',','.').'</td>
                                <td>'.number_format($l->tgt4,0,',','.').'</td>
                                <td>'.number_format($l->tgt5,0,',','.').'</td>
                                <td>'.number_format($l->tgt6,0,',','.').'</td>
                                <td>'.number_format($l->ratatgt,0,',','.').'</td>
                              </tr>
                              <tr>
                                <td class="bg-primary">PA</td>
                                <td class="bg-primary">'.number_format($l->pa1,2).'</td>
                                <td class="bg-primary">'.number_format($l->pa2,2).'</td>
                                <td class="bg-primary">'.number_format($l->pa3,2).'</td>
                                <td class="bg-primary">'.number_format($l->pa4,2).'</td>
                                <td class="bg-primary">'.number_format($l->pa5,2).'</td>
                                <td class="bg-primary">'.number_format($l->pa6,2).'</td>
                                <td class="bg-yellow">'.number_format($l->ratapa,2).'</td>
                              </tr>';
                        }
                        $n++;  
                      }

                    ?>  
                    </tbody>
                  </table>
                  <?php if ($edit == 1) { 
                    if (in_array($access['l_ac']['edt'], $access['access'])) {
                    ?>
                  <div class="form-group pull-right">
                    <input type="hidden" name="tabel" value="<?php echo $tabel;?>">
                    <input type="hidden" name="smt" value="<?php echo $smt;?>">
                    <input type="hidden" name="kode" value="<?php echo $kode;?>">
                    <button class="btn btn-danger" type="reset"><i class="fa fa-refresh"></i> Reset</button>
                    <button class="btn btn-success" type="submit" id="save1"><i class="fa fa-floppy-o"></i> Simpan</button>
                  </div>
                  <?php }
                  }
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