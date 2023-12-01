<div class="content-wrapper">
  <section class="content-header">
    <h1>
      <i class="fa fa-database"></i> Master Data 
      <small>KPI</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
      <li class="active">Master Indikator</li>
    </ol>
  </section>
  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box box-success">
          <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-list"></i> Daftar KPI</h3>
            <div class="box-tools pull-right">
              <button class="btn btn-box-tool" onclick="reload_table('table_data')" data-toggle="tooltip" title="Refresh Table"><i class="fas fa-sync"></i></button>
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
                      if (in_array('ADD', $access['access'])) {
                        echo ' <button class="btn btn-success" type="button" data-toggle="collapse" data-target="#add" aria-expanded="false" id="btn_add_collapse" aria-controls="import"><i class="fa fa-plus"></i> Tambah KPI</button> ';
                      }
                      if (in_array('IMP', $access['access'])) {
                        echo '<input type="hidden" name="exkpi" value="ex">
                        <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#import" aria-expanded="false" aria-controls="import"><i class="fas fa-cloud-upload-alt"></i> Import</button> ';
                      }
                      if (in_array('EXP', $access['access'])) {
                        echo '<div class="btn-group">
                            <button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown"><i class="fas fa-file-excel-o"></i> Export
                            <span class="fa fa-caret-down"></span></button>
                            <ul class="dropdown-menu">
                              <li><a href="'.base_url('master/export_kpi').'">Export Template</a></li>
                              <li><a href="'.base_url('master/export_data_kpi').'">Export Data</a></li>
                            </ul>
                          </div>';
                      }
                      ?>
                      <?php
                      if(in_array('IMP', $access['access'])){?>
                          <div class="modal fade" id="import" role="dialog">
                            <div class="modal-dialog">
                              <div class="modal-content text-center">
                                <div class="modal-header">
                                  <button type="button" class="close all_btn_import" data-dismiss="modal">&times;</button>
                                  <h4 class="modal-title">Import Data Dari Excel</h4>
                                </div>
                              <form id="form_import" action="#">
                                <div class="modal-body">
                                  <p class="text-muted">File Data Template Master KPI harus tipe *.xls, *.xlsx, *.csv, *.ods dan *.ots</p>
                                  <input id="uploadFilex" placeholder="Nama File" readonly="readonly" class="form-control" required="required">
                                  <span class="input-group-btn">
                                    <div class="fileUpload btn btn-warning">
                                      <span><i class="fa fa-folder-open"></i> Pilih File</span>
                                      <input id="uploadBtnx" type="file" class="upload" name="file" onchange="checkFile('#uploadBtnx','#uploadFilex','#save')" />
                                    </div>
                                  </span>                              
                                </div> 
                                <div class="modal-footer">
                                  <div id="progress2" style="float: left;"></div>
                                  <button class="btn btn-primary all_btn_import" id="save" type="button" disabled style="margin-right: 4px;"><i class="fa fa-check-circle"></i> Upload</button>
                                  <button id="savex" type="submit" style="display: none;"></button>
                                  <button type="button" class="btn btn-default all_btn_import" data-dismiss="modal">Kembali</button>
                                </div>
                              </form>
                              </div>
                            </div>
                          </div>
                        <?php } ?>
                        </div>
                        <div class="pull-right" style="font-size: 8pt;">
                          <i class="fa fa-toggle-on stat scc"></i> Aktif<br>
                          <i class="fa fa-toggle-off stat err"></i> Tidak Aktif
                        </div>
                      </div>
                    </div>
                      <?php if(in_array('ADD', $access['access'])){?>
                        <div class="collapse" id="add">
                          <br>
                          <div class="col-md-2"></div>
                          <div class="col-md-8">
                            <form id="form_add">
                              <div class="form-group">
                                <label>Kode KPI</label>
                                <input type="text" placeholder="Masukkan Kode KPI" name="kode" class="form-control" id="data_kode_add" readonly="readonly">
                              </div>
                              <div class="form-group">
                                <label>KPI</label>
                                <textarea name="kpi" class="form-control" placeholder="Masukkan KPI" style="padding-top: 10px;"></textarea>
                              </div>
                              <!-- <div class="form-group">
                                <label>Perumusan</label>
                                <br><p class="text-muted">Jika Bobot Penalty Parameter[A]=<b style="background-color: yellow;color: #000;"> 5</b>% dan Parameter [B]=<b style="background-color: yellow;color: #000;"> 10</b>% maka isikan <b style="background-color: yellow;color: #000;"> 5;10 </b></p>
                                <p class="text-muted">Kosongkan Jika Tidak Berlaku Perumusan</p>
                                <input type="text" placeholder="Masukkan Rumus" name="rumus" class="form-control">
                              </div> -->
                              <div class="form-group">
                                <label>Unit / Satuan</label>
                                <input type="text" placeholder="Masukkan Unit / Satuan" name="unit" class="form-control" required="required">
                              </div>
                              <div class="form-group">
                                <label>Detail Rumus</label>
                                <textarea name="detail_rumus" class="form-control" placeholder="Masukkan Detail Rumus" style="padding-top: 10px;"></textarea>
                              </div>
                              <div class="form-group">
                                <label>Sumber Data</label>
                                <input type="text" placeholder="Masukkan Sumber Data" name="sumber_data" class="form-control" required="required">
                              </div>
                              <div class="form-group">
                                <label>Cara Menghitung</label>
                                <select class="form-control select2" style="width: 100%;" name="cara_menghitung" id="data_cara_menghitung_add">
                                </select>
                              </div>
                              <div class="form-group">
                                <label>Kaitan Nilai</label>
                                <?php
                                $sel = array(null);
                                $ex = array('class'=>'form-control select2','style'=>'width:100%;','required'=>'required','id'=>'data_kaitan_add');
                                echo form_dropdown('kaitan',$kaitan,$sel,$ex);
                                ?>
                              </div>
                              <div class="form-group">
                                <label>Jenis Satuan</label>
                                <?php
                                $sel1 = array(null);
                                $ex1 = array('class'=>'form-control select2','style'=>'width:100%;','required'=>'required','id'=>'data_jenis_satuan_add');
                                echo form_dropdown('jenis_satuan',$jenis_satuan,$sel1,$ex1);
                                ?>
                              </div>
                              <div class="form-group">
                                <label>Sifat</label>
                                <?php
                                $sel3 = array(null);
                                $ex3 = array('class'=>'form-control select2','style'=>'width:100%;','required'=>'required','id'=>'data_sifat_add');
                                echo form_dropdown('sifat',$sifat,$sel3,$ex3);
                                ?>
                              </div>
                              <div class="form-group">
                                <label>Untuk Bagian Jabatan</label>
                                <select class="form-control select2" style="width: 100%;" name="bagian" id="data_bagian_add">
                                </select>
                              </div>
                              <div id="show_min_max_add">
                                <div class="form-group">
                                  <label>Nilai Minimal</label>
                                  <input type="text" placeholder="Masukkan Minimal" name="min" class="form-control">
                                </div>
                                <div class="form-group">
                                  <label>Nilai Maksimal</label>
                                  <input type="text" placeholder="Masukkan Maksimal" name="max" class="form-control">
                                </div>
                              </div>
                              <div class="form-group">
                                <label>KPI Utama</label>
                                  <?php
                                  $yesno[null] = 'Pilih Data';
                                  $sel5 = array(null);
                                  $ex5 = array('class'=>'form-control select2','style'=>'width:100%;','required'=>'required','id'=>'kpi_utama_add');
                                  echo form_dropdown('kpi_utama',$yesno,$sel5,$ex5);
                                  ?>
                              </div>
                              <div class="panel panel-primary">
                                <div class="panel-heading">Batasan Poin KPI</div>
                                <div class="panel-body">
                                  <p class="text-muted" style="padding-left: 10px;">Kosongkan jika tidak ada poin dan satuan!</p>
                                  <div class="form-group">
                                    <label>Jenis Batasan Poin</label>
                                    <select class="form-control select2" style="width: 100%;" name="batasan_poin" id="data_batasan_poin_add">
                                    </select>
                                  </div>
                                  <div class="form-group">
                                    <label class="checkbox">Lebih Dari Batasan Maksimal Poin
                                      <input type="checkbox" name="lebih_max" id="data_lebih_max_add" value="1">
                                      <span class="checkmark"></span>
                                    </label>
                                  </div>
                                  <?php for ($i=1; $i <= $this->otherfunctions->poin_max_range(); $i++) { ?>
                                  <div class="col-md-4">
                                    <label>Poin <?php echo $i; ?></label>
                                    <input type="text" placeholder="Masukkan Poin <?php echo $i; ?>" name="poin_<?php echo $i; ?>" id="data_poin_<?php echo $i; ?>_add" class="form-control">
                                  </div>
                                  <div class="col-md-8">
                                    <label>Satuan <?php echo $i; ?></label>
                                    <input type="text" placeholder="Masukkan Satuan <?php echo $i; ?>" name="satuan_<?php echo $i; ?>" id="data_satuan_<?php echo $i; ?>_add" class="form-control">
                                  </div>
                                  <?php } ?>
                                </div>
                              </div>
                              <div class="form-group">
                                <button type="button" onclick="do_add()" id="btn_add" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
                              </div>
                            </form>
                          </div>
                        </div>
                      <?php } ?>
                      </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-md-12"> 
                        <div class="callout callout-warning">
                          <b><i class="fa fa-warning"></i> Peringatan</b>
                          <p>Jika Anda melakukan <b>EDIT</b> pada data Master KPI, maka <b>Agenda KPI </b><b class="err">(yang belum dilakukan Validasi)</b> maupun <b>Rancangan KPI</b> juga akan ikut terupdate sesuai dengan data yang Anda edit <b class="err">[TIDAK BERLAKU UNTUK PROSES IMPORT DATA]</b></p>
                        </div>
                        <table id="table_data" class="table table-bordered table-striped table-responsive" width="100%">
                          <thead>
                            <tr>
                              <th>No.</th>
                              <th>Kode KPI</th>
                              <th>KPI</th>
                              <th>Detail Rumus</th>
                              <th>Unit / Satuan</th>
                              <th>Jenis Batasan Poin</th>
                              <th>Sifat</th>
                              <th>KPI Utama</th>
                              <th>Status</th>
                              <th>Aksi</th>
                            </tr>
                          </thead>
                          <tbody> 
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </section>
        </div> 

        <!-- view -->
        <div id="view" class="modal fade" role="dialog">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h2 class="modal-title">Detail Data <b class="text-muted header_data"></b></h2> 
                <input type="hidden" name="data_id_view">
              </div>
              <div class="modal-body">
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group col-md-12">
                      <label class="col-md-6 control-label">Kode KPI</label>
                      <div class="col-md-6" id="data_kode_view"></div>
                    </div>
                    <div class="form-group col-md-12">
                      <label class="col-md-6 control-label">Nama KPI</label>
                      <div class="col-md-6" id="data_name_view"></div>
                    </div>
                    <!-- <div class="form-group col-md-12">
                      <label class="col-md-6 control-label">Rumus</label>
                      <div class="col-md-6" id="data_rumus_view"></div>
                    </div> -->
                    <div class="form-group col-md-12">
                      <label class="col-md-6 control-label">Unit / Satuan</label>
                      <div class="col-md-6" id="data_unit_view"></div>
                    </div>
                    <div class="form-group col-md-12">
                      <label class="col-md-6 control-label">Detail Rumus</label>
                      <div class="col-md-6" id="data_detail_rumus_view"></div>
                    </div>
                    <div class="form-group col-md-12">
                      <label class="col-md-6 control-label">Sumber Data</label>
                      <div class="col-md-6" id="data_sumber_data_view"></div>
                    </div>
                    <div class="form-group col-md-12">
                      <label class="col-md-6 control-label">Cara Menghitung</label>
                      <div class="col-md-6" id="data_cara_menghitung_view"></div>
                    </div>
                    <div class="form-group col-md-12">
                      <label class="col-md-6 control-label">Kaitan</label>
                      <div class="col-md-6" id="data_kaitan_view"></div>
                    </div>
                    <div class="form-group col-md-12">
                      <label class="col-md-6 control-label">Jenis Satuan</label>
                      <div class="col-md-6" id="data_jenis_satuan_view"></div>
                    </div>
                    <div class="form-group col-md-12">
                      <label class="col-md-6 control-label">Sifat</label>
                      <div class="col-md-6" id="data_sifat_view"></div>
                    </div>
                    <div class="form-group col-md-12">
                      <label class="col-md-6 control-label">Bagian</label>
                      <div class="col-md-6" id="data_kode_bagian_view"></div>
                    </div>
                    <div class="form-group col-md-12">
                      <label class="col-md-6 control-label">Nilai Minimal</label>
                      <div class="col-md-6" id="data_min_view"></div>
                    </div>
                    <div class="form-group col-md-12">
                      <label class="col-md-6 control-label">Nilai Maksimal</label>
                      <div class="col-md-6" id="data_max_view"></div>
                    </div>
                    <div class="form-group col-md-12">
                      <label class="col-md-6 control-label">Jenis Batasan Poin</label>
                      <div class="col-md-6" id="data_batasan_poin_view"></div>
                    </div>
                    <div class="form-group col-md-12">
                      <label class="col-md-6 control-label">Lebih Dari Maksimal Poin</label>
                      <div class="col-md-6" id="data_lebih_max_view"></div>
                    </div>
                    <div class="form-group col-md-12">
                      <label class="col-md-6 control-label">KPI Utama</label>
                      <div class="col-md-6" id="data_kpi_utama_view"></div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group col-md-12">
                      <label class="col-md-6 control-label">Status</label>
                      <div class="col-md-6" id="data_status_view"></div>
                    </div>
                    <div class="form-group col-md-12">
                      <label class="col-md-6 control-label">Dibuat Tanggal</label>
                      <div class="col-md-6" id="data_create_date_view"></div>
                    </div>
                    <div class="form-group col-md-12">
                      <label class="col-md-6 control-label">Diupdate Tanggal</label>
                      <div class="col-md-6" id="data_update_date_view"></div>
                    </div>
                    <div class="form-group col-md-12">
                      <label class="col-md-6 control-label">Dibuat Oleh</label>
                      <div class="col-md-6" id="data_create_by_view">
                      </div>
                    </div>
                    <div class="form-group col-md-12">
                      <label class="col-md-6 control-label">Diupdate Oleh</label>
                      <div class="col-md-6" id="data_update_by_view">
                      </div>
                    </div>
                  </div>
                </div>
                <hr>
                <div class="row">
                  <div class="col-md-3"></div>
                  <div class="col-md-6">
                    <h3 class="text-center">Rincian Point KPI</h3>
                    <table class="table table-hover table-striped">
                      <thead>
                        <tr class="bg-blue">
                          <th class="text-center">Point</th>
                          <th class="text-center">Satuan</th>
                        </tr>
                      </thead>
                      <tbody id="data_tr_view">
                      </tbody>
                    </table>
                    
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <?php if (in_array('EDT', $access['access'])) {
                  echo '<button type="submit" class="btn btn-info" onclick="edit_modal()"><i class="fa fa-edit"></i> Edit</button>';
                }?>
                <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
              </div>
            </div>
          </div>
        </div>

        <!-- edit -->
        <div id="edit" class="modal fade" role="dialog">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h2 class="modal-title">Edit Data <b class="text-muted header_data"></b></h2>
              </div>
              <div class="modal-body">
                <div class="callout callout-danger">
                  <b><i class="fa fa-warning"></i> Peringatan</b><br>
                  Edit data master KPI akan berpengaruh pada <b>Rancangan KPI</b> dan <b>Agenda KPI <b class="err">(yang belum dilakukan Validasi)</b></b>. Pastikan data diedit dengan benar!
                </div>
                <form id="form_edit">
                  <input type="hidden" id="data_id_edit" name="id" value="">
                  <input type="hidden" id="data_kode_edit_old" name="kode_old" value="">
                  <div class="form-group">
                    <label>Kode KPI</label>
                    <input type="text" placeholder="Masukkan Kode KPI" name="kode" id="data_kode_edit" class="form-control" readonly="readonly">
                  </div>
                  <div class="form-group">
                    <label>KPI</label>
                    <textarea name="kpi" id="data_name_edit" class="form-control" placeholder="Masukkan KPI" style="padding-top: 10px;"></textarea>
                  </div>
                  <!-- <div class="form-group">
                    <label>Perumusan</label>
                    <br><small class="text-muted">Jika Bobot Penalty Parameter[A]=<b style="background-color: yellow;color: #000;"> 5</b>% dan Parameter [B]=<b style="background-color: yellow;color: #000;"> 10</b>% maka isikan <b style="background-color: yellow;color: #000;"> 5;10 </b></small>
                    <p class="text-muted">Kosongkan Jika Tidak Berlaku Perumusan</p>
                    <input type="text" placeholder="Masukkan Rumus" name="rumus" id="data_rumus_edit" class="form-control">
                  </div> -->
                  <div class="form-group">
                    <label>Unit / Satuan</label>
                    <input type="text" placeholder="Masukkan Unit / Satuan" name="unit" id="data_unit_edit" class="form-control" required="required">
                  </div>
                  <div class="form-group">
                    <label>Detail Rumus</label>
                    <textarea name="detail_rumus" id="data_detail_rumus_edit" class="form-control" placeholder="Masukkan Detail Rumus" style="padding-top: 10px;"></textarea>
                  </div>
                  <div class="form-group">
                    <label>Sumber Data</label>
                    <input type="text" placeholder="Masukkan Sumber Data" name="sumber_data" class="form-control" id="data_sumber_data_edit" required="required">
                  </div>
                  <div class="form-group">
                    <label>Cara Menghitung</label>
                    <select class="form-control select2" style="width: 100%;" name="cara_menghitung" id="data_cara_menghitung_edit">
                    </select>
                  </div>
                  <div class="form-group">
                    <label>Kaitan Nilai</label>
                    <?php
                    $sel = array(null);
                    $ex = array('class'=>'form-control select2','style'=>'width:100%;','required'=>'required','id'=>'data_kaitan_edit');
                    echo form_dropdown('kaitan',$kaitan,$sel,$ex);
                    ?>
                  </div>
                  <div class="form-group">
                    <label>Jenis Satuan</label>
                    <?php
                    $sel1 = array(null);
                    $ex1 = array('class'=>'form-control select2','style'=>'width:100%;','required'=>'required','id'=>'data_jenis_satuan_edit');
                    echo form_dropdown('jenis_satuan',$jenis_satuan,$sel1,$ex1);
                    ?>
                  </div>
                  <div class="form-group">
                    <label>Sifat</label>
                    <?php
                    $sel3 = array(null);
                    $ex3 = array('class'=>'form-control select2','style'=>'width:100%;','required'=>'required','id'=>'data_sifat_edit');
                    echo form_dropdown('sifat',$sifat,$sel3,$ex3);
                    ?>
                  </div>
                  <div id="show_min_max_edit">
                    <div class="form-group">
                      <label>Nilai Minimal</label>
                      <input type="text" placeholder="Masukkan Minimal" name="min" id="data_min_edit" class="form-control">
                    </div>
                    <div class="form-group">
                      <label>Nilai Maksimal</label>
                      <input type="text" placeholder="Masukkan Maksimal" name="max" id="data_max_edit" class="form-control">
                    </div>
                  </div>
                  <div class="form-group">
                    <label>Untuk Bagian Jabatan</label>
                    <select class="form-control select2" style="width: 100%;" name="bagian" id="data_bagian_edit">
                    </select>
                  </div>
                  <div class="form-group">
                    <label>KPI Utama</label>
                      <?php
                      $yesno[null] = 'Pilih Data';
                      $sel6 = array(null);
                      $ex6 = array('class'=>'form-control select2','style'=>'width:100%;','required'=>'required','id'=>'kpi_utama_edit');
                      echo form_dropdown('kpi_utama',$yesno,$sel6,$ex6);
                      ?>
                  </div>
                  <div class="panel panel-primary">
                    <div class="panel-heading">Batasan Poin KPI</div>
                    <div class="panel-body">
                    <p class="text-muted" style="padding-left: 10px;">Kosongkan jika tidak ada poin dan satuan!</p>
                    <div class="form-group">
                      <label>Jenis Batasan Poin</label>
                      <select class="form-control select2" style="width: 100%;" name="batasan_poin" id="data_batasan_poin_edit">
                      </select>
                    </div>
                    <div class="form-group">
                      <label class="checkbox">Lebih Dari Batasan Maksimal Poin
                        <input type="checkbox" name="lebih_max" id="data_lebih_max_edit" value="1">
                        <span class="checkmark"></span>
                      </label>
                    </div>
                    <?php 
                    for ($i=1; $i <= $this->otherfunctions->poin_max_range(); $i++) { 
                    ?>
                    <div class="col-md-4">
                      <label>Poin <?php echo $i; ?></label>
                      <input type="text" placeholder="Masukkan Poin <?php echo $i; ?>" name="poin_<?php echo $i; ?>" id="data_poin_<?php echo $i; ?>_edit" class="form-control">
                    </div>
                    <div class="col-md-8">
                      <label>Satuan <?php echo $i; ?></label>
                      <input type="text" placeholder="Masukkan Satuan <?php echo $i; ?>" name="satuan_<?php echo $i; ?>" id="data_satuan_<?php echo $i; ?>_edit" class="form-control">
                    </div>
                    <?php } ?>
                    </div>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" onclick="do_edit()" id="btn_edit" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
                  <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
                </div>
              </form>
            </div>

          </div>
        </div>

<div id="modal_delete_partial"></div>
<script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
<script type="text/javascript">
  var url_select="<?php echo base_url('global_control/select2_global');?>";
  var table="master_kpi";
  var column="id_kpi";
  $(document).ready(function(){
    $('#btn_add_collapse').click(function(){
      select_data('data_bagian_add',url_select,'master_bagian','kode_bagian','nama');
      select_data('data_cara_menghitung_add',url_select,'master_rumus','function','nama');
      select_data('data_batasan_poin_add',url_select,'master_jenis_batasan_poin','id_batasan_poin','nama');
    })
    $('#data_batasan_poin_add').change(function () {
      var kode=this.value;
      var callback=getAjaxData("<?php echo base_url('master/master_jenis_batasan_poin/view_one')?>",{id_batasan_poin:kode}); 
      var i;
      var x = 1;
      for (i = 0; i < '<?=$this->otherfunctions->poin_max_range()?>'; i++) {
        $('#data_poin_'+x+'_add').val(callback['poin_'+x]);
        $('#data_satuan_'+x+'_add').val(callback['satuan_'+x]);
        x++;
      }
      if (callback['lebih_max_val']) {
        $('#data_lebih_max_add').prop('checked', true);
      }else{
        $('#data_lebih_max_add').prop('checked', false);
      }
    });
    
    $('#import').modal({
      show: false,
      backdrop: 'static',
      keyboard: false
    }) 
    $('#save').click(function(){
      $('.all_btn_import').attr('disabled','disabled');
      $('#progress2').html('<i class="fa fa-spinner fa-pulse fa-fw"></i> Mohon Menunggu, data sedang di upload....')
      setTimeout(function () {
          $('#savex').click();
      },1000);
    })
    $('#form_import').submit(function(e){
      e.preventDefault();
      var data_add = new FormData(this);
      var urladd = "<?php echo base_url('master/import_kpi'); ?>";
      submitAjaxFile(urladd,data_add,'#import','#progress2','.all_btn_import');
    });
    $('#show_min_max_add').hide();
    $('#data_sifat_add').change(function () {
      var val=this.value;
      if (val == 'MIN') {
        $('#show_min_max_add').show();
      }else{
        $('#show_min_max_add').hide();
      }
    });
    refreshCode();
    $('#table_data').DataTable( {
      ajax: {
        url: "<?php echo base_url('master/master_kpi/view_all/')?>",
        type: 'POST',
        data:{access:"<?php echo $this->codegenerator->encryptChar($access);?>"}
      },
      scrollX: true,
      deferRender: true,
      columnDefs: [
      {   targets: 0, 
        width: '2%',
        render: function ( data, type, full, meta ) {
          return '<center>'+(meta.row+1)+'.</center>';
        }
      },
      {   targets: 2,
        width: '25%',
        render: function ( data, type, full, meta ) {
          return data;
        }
      },
      {   targets: 3,
        width: '45%',
        render: function ( data, type, full, meta ) {
          return data;
        }
      },
      //aksi
      {   targets: [7,8], 
        width: '7%',
        render: function ( data, type, full, meta ) {
          return '<center>'+data+'</center>';
        }
      },
      ]
    });
  })
  function checkFile(idf,idt,btnx) {
    var fext = ['xls', 'xlsx', 'csv', 'ods', 'ots'];
    pathFile(idf,idt,fext,btnx);
  }
  function view_modal(id) {
    var data={id_kpi:id};
    var callback=getAjaxData("<?php echo base_url('master/master_kpi/view_one')?>",data);  
    $('#view').modal('show');
    $('.header_data').html(callback['nama']);
    $('input[name="data_id_view"]').val(callback['id']);
    $('#data_kode_view').html(callback['kode_kpi']);
    $('#data_name_view').html(callback['nama']);
    $('#data_rumus_view').html(callback['rumus_view']);
    $('#data_unit_view').html(callback['unit']);
    $('#data_detail_rumus_view').html(callback['detail_rumus']);
    $('#data_sumber_data_view').html(callback['sumber_data']);
    $('#data_kaitan_view').html(callback['kaitan_view']);
    $('#data_cara_menghitung_view').html(callback['cara_menghitung_view']);
    $('#data_jenis_satuan_view').html(callback['jenis_satuan_view']);
    $('#data_jenis_view').html(callback['jenis_view']);
    $('#data_sifat_view').html(callback['sifat_view']);
    $('#data_kode_bagian_view').html(callback['bagian']);
    $('#data_min_view').html(callback['min']);    
    $('#data_max_view').html(callback['max']);    
    $('#data_batasan_poin_view').html(callback['nama_batasan_poin']); 
    $('#data_lebih_max_view').html(callback['lebih_max']);
    $('#data_kpi_utama_view').html(callback['kpi_utama']);
    $('#data_tr_view').html(callback['tr_table']);
    var status = callback['status'];
    if(status==1){
      var statusval = '<b class="text-success">Aktif</b>';
    }else{
      var statusval = '<b class="text-danger">Tidak Aktif</b>';
    }
    $('#data_status_view').html(statusval);
    $('#data_create_date_view').html(callback['create_date']+' WIB');
    $('#data_update_date_view').html(callback['update_date']+' WIB');
    $('#data_create_by_view').html(callback['nama_buat']);
    $('#data_update_by_view').html(callback['nama_update']);
  }
  function edit_modal() {
    select_data('data_batasan_poin_edit',url_select,'master_jenis_batasan_poin','id_batasan_poin','nama');
    var id = $('input[name="data_id_view"]').val();
    var data={id_kpi:id};
    var callback=getAjaxData("<?php echo base_url('master/master_kpi/view_one')?>",data); 
    $('#view').modal('toggle');
    setTimeout(function () {
      $('#edit').modal('show');
    },1000);
    var kode = callback['kode_kpi'];
    select_data('data_bagian_edit',url_select,'master_bagian','kode_bagian','nama','0');
    select_data('data_cara_menghitung_edit',url_select,'master_rumus','function','nama');
    if (callback['sifat'] == 'MIN') {
      $('#show_min_max_edit').show();
    }else{
      $('#show_min_max_edit').hide();
    }    
    $('#data_sifat_edit').change(function () {
      var val=this.value;
      if (val == 'MIN') {
        $('#show_min_max_edit').show();
      }else{
        $('#show_min_max_edit').hide();
      }
    });
    $('.header_data').html(callback['nama']);
    $('#data_id_edit').val(callback['id']);
    $('#data_kode_edit_old').val(callback['kode_kpi']);
    $('#data_kode_edit').val(callback['kode_kpi']);
    $('#data_name_edit').val(callback['nama']);
    $('#data_rumus_edit').val(callback['rumus']);
    $('#data_unit_edit').val(callback['unit']);
    $('#data_sumber_edit').val(callback['sumber']);
    $('#data_detail_rumus_edit').val(callback['detail_rumus']);
    $('#data_sumber_data_edit').val(callback['sumber_data']);
    $('#data_min_edit').val(callback['min']);
    $('#data_max_edit').val(callback['max']);
    $('#data_batasan_poin_edit').val(callback['batasan_poin']).trigger('change');
    $('#data_kaitan_edit').val(callback['kaitan']).trigger('change');
    $('#data_cara_menghitung_edit').val(callback['cara_menghitung']).trigger('change');
    $('#data_jenis_satuan_edit').val(callback['jenis_satuan']).trigger('change');
    $('#data_jenis_edit').val(callback['jenis']).trigger('change');
    $('#data_sifat_edit').val(callback['sifat']).trigger('change');
    $('#data_bagian_edit').val(callback['kode_bagian']).trigger('change');
    $('#kpi_utama_edit').val(callback['e_kpi_utama']).trigger('change');
    var i;
    var x = 1;
    for (i = 0; i < '<?=$this->otherfunctions->poin_max_range()?>'; i++) {
      $('#data_poin_'+x+'_edit').val(callback['poin_'+x]);
      $('#data_satuan_'+x+'_edit').val(callback['satuan_'+x]);
      x++;
    }
    if (callback['lebih_max_val']) {
      $('#data_lebih_max_edit').prop('checked', true);
    }else{
      $('#data_lebih_max_edit').prop('checked', false);
    }
    $('#data_batasan_poin_edit').change(function () {
      var kode=this.value;
      var callback=getAjaxData("<?php echo base_url('master/master_jenis_batasan_poin/view_one')?>",{id_batasan_poin:kode}); 
      var i;
      var x = 1;
      for (i = 0; i < '<?=$this->otherfunctions->poin_max_range()?>'; i++) {
        $('#data_poin_'+x+'_edit').val(callback['poin_'+x]);
        $('#data_satuan_'+x+'_edit').val(callback['satuan_'+x]);
        x++;
      }
      if (callback['lebih_max_val']) {
        $('#data_lebih_max_edit').prop('checked', true);
      }else{
        $('#data_lebih_max_edit').prop('checked', false);
      }
    });
  }
  function refreshCode() {
    kode_generator("<?php echo base_url('master/master_kpi/kode');?>",'data_kode_add');
  }
  function delete_modal(id) {
    var data={id_kpi:id};
    var callback=getAjaxData("<?php echo base_url('master/master_kpi/view_one')?>",data);
    var datax={table:table,column:column,id:id,nama:callback['nama']};
    loadModalAjax("<?php echo base_url('pages/load_modal_delete')?>",'modal_delete_partial',datax,'delete');
  }
  //doing db transaction
  function do_status(id,data) {
    var data_table={status:data};
    var where={id_kpi:id};
    var datax={table:table,where:where,data:data_table};
    submitAjax("<?php echo base_url('global_control/change_status')?>",null,datax,null,null,'status');
    $('#table_data').DataTable().ajax.reload(function (){
      Pace.restart();
    });
  }
  function do_edit(){
    if($("#form_edit")[0].checkValidity()) {
      submitAjax("<?php echo base_url('master/edt_kpi')?>",'edit','form_edit',null,null);
      $('#table_data').DataTable().ajax.reload(function (){Pace.restart();},false);
    }else{
      notValidParamx();
    } 
  }
  function do_add(){
    if($("#form_add")[0].checkValidity()) {
      submitAjax("<?php echo base_url('master/add_kpi')?>",null,'form_add',null,null);
      $('#table_data').DataTable().ajax.reload(function (){Pace.restart();},false);
      $('#form_add')[0].reset();
      refreshCode();
    }else{
      notValidParamx();
    }
  }
</script>