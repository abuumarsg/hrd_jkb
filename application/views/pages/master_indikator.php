<div class="content-wrapper">
  <section class="content-header">
    <h1>
      <i class="fa fa-database"></i> Master Data 
      <small>Indikator</small>
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
            <h3 class="box-title"><i class="fa fa-list"></i> Daftar Indikator</h3>
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
                      <?php echo form_open_multipart('master/export_indikator');
                      if (in_array($access['l_ac']['add'], $access['access'])) {
                        echo ' <button class="btn btn-success btn-flat" type="button" data-toggle="collapse" data-target="#add" aria-expanded="false" aria-controls="import"><i class="fa fa-plus"></i> Tambah Indikator</button> ';
                      }
                      if (in_array($access['l_ac']['imp'], $access['access'])) {
                        echo '<input type="hidden" name="exindikator" value="ex">
                        <button class="btn btn-primary btn-flat" type="button" data-toggle="modal" data-target="#import" aria-expanded="false" aria-controls="import"><i class="fas fa-cloud-upload-alt"></i> Import</button> ';
                      }
                      if (in_array($access['l_ac']['exp'], $access['access'])) {
                        echo '<button class="btn btn-warning btn-flat" type="submit" aria-expanded="false" aria-controls="export"><i class="fas fa-cloud-download-alt"></i> Export Template</button>';
                      }
                      ?>
                      <?php echo form_close();
                      if(in_array($access['l_ac']['imp'], $access['access'])){?>
                        <div class="modal fade" id="import" role="dialog">
                          <div class="modal-dialog">
                            <div class="modal-content text-center">
                              <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Import Data Dari Excel</h4>
                              </div>
                              <div class="modal-body">
                                <?php echo form_open_multipart('master/import_indikator');?>
                                <p style="color:red;">File Data Template Master Indikator harus tipe *.xls, *.xlsx, *.csv, *.ods dan *.ots</p>
                                <input id="uploadFilex" placeholder="Nama File" readonly="readonly" class="form-control" required="required">
                                <span class="input-group-btn">
                                  <div class="fileUpload btn btn-warning btn-flat">
                                    <span><i class="fa fa-folder-open"></i> Pilih File</span>
                                    <input id="uploadBtnx" type="file" class="upload" name="file" onchange="checkFile('#uploadBtnx','#uploadFilex','#save')" />
                                  </div>
                                </span>                              
                              </div>
                              <div class="modal-footer">
                                <button class="btn btn-flat btn-primary" id="save" type="submit" disabled><i class="fa fa-check-circle"></i> Upload</button>
                                <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
                                <?php echo form_close();?>
                              </div>
                            </div>
                          </div>
                          </div><?php } ?>
                        </div>
                        <div class="pull-right" style="font-size: 8pt;">
                          <i class="fa fa-toggle-on stat scc"></i> Aktif<br>
                          <i class="fa fa-toggle-off stat err"></i> Tidak Aktif
                        </div>
                      </div>
                    </div>
                    <?php if(in_array($access['l_ac']['add'], $access['access'])){?>
                      <div class="collapse" id="add">
                        <br>
                        <div class="col-md-2"></div>
                        <div class="col-md-8">
                          <form id="form_add">
                            <div class="form-group">
                              <label>Kode Indikator</label>
                              <input type="text" placeholder="Masukkan Kode Indikator" name="kode" class="form-control" id="data_kode_add" readonly="readonly">
                            </div>
                            <div class="form-group">
                              <label>Indikator</label>
                              <textarea name="indikator" class="form-control" placeholder="Masukkan Indikator" style="padding-top: 10px;"></textarea>
                            </div>
                            <div class="form-group">
                              <label>Cara Pengukuran</label>
                              <textarea name="pengukuran" class="form-control" placeholder="Masukkan Cara Pengukuran" style="padding-top: 10px;"></textarea>
                            </div>
                            <div class="form-group">
                              <label>Perumusan</label>
                              <br><small class="text-danger">Jika Bobot Penalty Parameter[A]=<b style="background-color: yellow;color: #000;"> 5</b>% dan Parameter [B]=<b style="background-color: yellow;color: #000;"> 10</b>% maka isikan <b style="background-color: yellow;color: #000;"> 5;10 </b></small>
                              <p class="text-danger">Kosongkan Jika Tidak Berlaku Perumusan</p>
                              <input type="text" placeholder="Masukkan Rumus" name="rumus" class="form-control">
                            </div>
                            <div class="form-group">
                              <label>Sumber Data</label>
                              <input type="text" placeholder="Masukkan Sumber Data" name="sumber" class="form-control" required="required">
                            </div>
                            <div class="form-group">
                              <label>Pelaporan</label>
                              <select class="form-control select2" style="width: 100%;" name="pelaporan" id="data_pelaporan_add">
                                <option></option>
                                <?php
                                foreach ($periode as $kper => $vper) {
                                  echo '<option value="'.$kper.'">'.$vper.'</option>';
                                }
                                ?>
                              </select>
                            </div>
                            <div class="form-group">
                              <label>Polarisasi</label>
                              <select class="form-control select2" style="width: 100%;" name="polarisasi" id="data_polarisasi_add">
                                <option></option>
                                <option value="Minimal">Minimal</option>
                                <option value="Maksimal">Maksimal</option>
                              </select>
                            </div>
                            <div class="form-group">
                              <label>Untuk Bagian Jabatan</label>
                              <select class="form-control select2" style="width: 100%;" name="bagian" id="data_bagian_add">
                                <option></option>
                                <?php
                                foreach ($bagian as $kb => $bag) {
                                  echo '
                                  <option value="'.$kb.'">'.$bag.'</option>';
                                }
                                ?>
                              </select>
                            </div>
                            <div class="form-group">
                              <label>Kaitan Nilai</label>
                              <select class="form-control select2" style="width: 100%;" name="kaitan" id="data_kaitan_add">
                                <option></option>
                                <option value="0">Tidak Berkaitan</option>
                                <option value="1">Berkaitan</option>
                              </select>
                            </div>
                            <div class="form-group">
                              <button type="button" onclick="do_add()" id="btn_add" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
                            </div>
                          </form>
                        </div>
                        </div><?php } ?>
                      </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-md-12">                  
                        <table id="table_data" class="table table-bordered table-striped table-responsive" width="100%">
                          <thead>
                            <tr>
                              <th>No.</th>
                              <th>Kode Indikator</th>
                              <th>Indikator</th>
                              <th>Cara Pengukuran</th>
                              <th>Sumber Data</th>
                              <th>Pelaporan</th>
                              <th>Polarisasi</th>
                              <th>Bagian</th>
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
                      <label class="col-md-6 control-label">Kode Indikator</label>
                      <div class="col-md-6" id="data_kode_view"></div>
                    </div>
                    <div class="form-group col-md-12">
                      <label class="col-md-6 control-label">Nama Indikator</label>
                      <div class="col-md-6" id="data_name_view"></div>
                    </div>
                    <div class="form-group col-md-12">
                      <label class="col-md-6 control-label">Pengukuran</label>
                      <div class="col-md-6" id="data_pengukuran_view"></div>
                    </div>
                    <div class="form-group col-md-12">
                      <label class="col-md-6 control-label">Sumber Data</label>
                      <div class="col-md-6" id="data_sumber_data_view"></div>
                    </div>
                    <div class="form-group col-md-12">
                      <label class="col-md-6 control-label">Pelaporan</label>
                      <div class="col-md-6" id="data_pelaporan_view"></div>
                    </div>
                    <div class="form-group col-md-12">
                      <label class="col-md-6 control-label">Polarisasi</label>
                      <div class="col-md-6" id="data_polarisasi_view"></div>
                    </div>
                    <div class="form-group col-md-12">
                      <label class="col-md-6 control-label">Bagian</label>
                      <div class="col-md-6" id="data_bagian_view"></div>
                    </div>
                    <div class="form-group col-md-12">
                      <label class="col-md-6 control-label">Kaitan</label>
                      <div class="col-md-6" id="data_kaitan_view"></div>
                    </div>
                    <div class="form-group col-md-12">
                      <label class="col-md-6 control-label">Rumus</label>
                      <div class="col-md-6" id="data_rumus_view"></div>
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
              </div>
              <div class="modal-footer">
                <?php if (in_array($access['l_ac']['edt'], $access['access'])) {
                  echo '<button type="submit" class="btn btn-info" onclick="edit_modal()"><i class="fa fa-edit"></i> Edit</button>';
                }?>
                <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
              </div>
            </div>
          </div>
        </div>

        <!-- edit -->
        <div id="edit" class="modal fade" role="dialog">
          <div class="modal-dialog modal-md">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h2 class="modal-title">Edit Data <b class="text-muted header_data"></b></h2>
              </div>
              <div class="modal-body">
                <form id="form_edit">
                  <input type="hidden" id="data_id_edit" name="id" value="">
                  <input type="hidden" id="data_kode_edit_old" name="kode_old" value="">
                  <div class="form-group">
                    <label>Kode jabatan</label>
                    <input type="text" placeholder="Masukkan Kode Indikator" id="data_kode_edit" name="kode" value="" class="form-control" required="required" readonly="readonly">
                  </div>
                  <div class="form-group">
                    <label>Indikator</label>
                    <textarea name="indikator" class="form-control" id="data_name_edit" placeholder="Masukkan Indikator"></textarea>
                  </div>
                  <div class="form-group">
                    <label>Cara Pengukuran</label>
                    <textarea name="pengukuran" class="form-control" id="data_pengukuran_edit" placeholder="Masukkan Cara Pengukuran"></textarea>
                  </div>
                  <div class="form-group">
                    <label>Perumusan</label>
                    <br><small class="text-danger">Jika Bobot Penalty Parameter[A]=<b style="background-color: yellow;color: #000;"> 5</b>% dan Parameter [B]=<b style="background-color: yellow;color: #000;"> 10</b>% maka isikan <b style="background-color: yellow;color: #000;"> 5;10 </b></small>
                    <p class="text-danger">Kosongkan Jika Tidak Berlaku Perumusan</p>
                    <input type="text" placeholder="Masukkan Rumus" id="data_rumus_edit" name="rumus" class="form-control">
                  </div>
                  <div class="form-group">
                    <label>Sumber Data</label>
                    <input type="text" placeholder="Masukkan Sumber Data" id="data_sumber_edit" name="sumber" class="form-control" required="required">
                  </div>
                  <div class="form-group">
                    <label>Pelaporan</label>
                    <select class="form-control select2" style="width: 100%;" name="pelaporan" id="data_pelaporan_edit">
                      <option></option>
                      <?php
                      foreach ($periode as $kper => $vper) {
                        echo '<option value="'.$kper.'">'.$vper.'</option>';
                      }
                      ?>
                    </select>
                  </div>
                  <div class="form-group">
                    <label>Polarisasi</label>
                    <select class="form-control select2" style="width: 100%;" name="polarisasi" id="data_polarisasi_edit">
                      <option></option>
                      <option value="Minimal">Minimal</option>
                      <option value="Maksimal">Maksimal</option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label>Untuk Bagian Jabatan</label>
                    <?php
                    $o1[NULL]='Belum Ada Bagian';
                    foreach ($bagian as $kb => $bag) {
                      $o1[$kb]=$bag;
                    }
                    $s1=array(NULL);
                    $e1 = array('class'=>'form-control select2','placeholder'=>'Loker','style'=>'width:100%;','id'=>'data_bagian_edit');
                    echo form_dropdown('bagian',$o1,$s1,$e1);
                    ?>
                  </div>
                  <div class="form-group">
                    <label>Kaitan Nilai</label>
                    <select class="form-control select2" style="width: 100%;" name="kaitan" id="data_kaitan_edit">
                      <option value="0">Tidak Berkaitan</option>
                      <option value="1">Berkaitan</option>
                    </select>
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

        <?php if(in_array($access['l_ac']['imp'], $access['access'])){?>
          <div class="modal fade" id="import" role="dialog">
            <div class="modal-dialog">
              <div class="modal-content text-center">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">Import Data Dari Excel</h4>
                </div>
                <div class="modal-body">
                  <?php echo form_open_multipart('master/import_indikator');?>
                  <p style="color:red;">File Data Template Master Indikator harus tipe *.xls, *.xlsx, *.csv, *.ods dan *.ots</p>
                  <input id="uploadFile" placeholder="Nama File" disabled="disabled" class="form-control" required="required">
                  <span class="input-group-btn">
                    <div class="fileUpload btn btn-warning btn-flat">
                      <span><i class="fa fa-folder-open"></i> Pilih File</span>
                      <input id="uploadBtn" type="file" class="upload" name="file"/>
                    </div>
                  </span>                              
                </div>
                <div class="modal-footer">
                  <button class="btn btn-flat btn-primary" id="save" type="submit" disabled><i class="fa fa-check-circle"></i> Upload</button>
                  <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
                  <?php echo form_close();?>
                </div>
              </div>
            </div>
          </div>
        <?php } ?>

        <div id="modal_delete_partial"></div>
        <script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
        <script type="text/javascript">
          var url_select="<?php echo base_url('global_control/select2_global');?>";
          var table="master_kpi";
          var column="id_kpi";
          $(document).ready(function(){
            
            
            
            refreshCode();
            select2_setting('#data_pelaporan_add','Pilih Pelaporan');
            select2_setting('#data_polarisasi_add','Pilih Polarisasi');
            select2_setting('#data_bagian_add','Pilih Bagian');
            select2_setting('#data_kaitan_add','Pilih kaitan');
            select2_setting('#data_pelaporan_edit','Pilih Pelaporan');
            select2_setting('#data_polarisasi_edit','Pilih Polarisasi');
            $('#table_data').DataTable( {
              ajax: {
                url: "<?php echo base_url('master/master_indikator/view_all/')?>",
                type: 'POST',
                data:{access:"<?php echo base64_encode(serialize($access));?>"}
              },
              scrollX: true,
              columnDefs: [
              {   targets: 0, 
                width: '5%',
                render: function ( data, type, full, meta ) {
                  return '<center>'+(meta.row+1)+'.</center>';
                }
              },
              {   targets: 1,
                width: '12%',
                render: function ( data, type, full, meta ) {
                  return data;
                }
              },
              {   targets: 2,
                width: '15%',
                render: function ( data, type, full, meta ) {
                  return data;
                }
              },
              {   targets: 3,
                width: '10%',
                render: function ( data, type, full, meta ) {
                  return data;
                }
              },
              {   targets: 4,
                width: '10%',
                render: function ( data, type, full, meta ) {
                  return data;
                }
              },
              {   targets: 5,
                width: '10%',
                render: function ( data, type, full, meta ) {
                  return data;
                }
              },
              {   targets: 6,
                width: '10%',
                render: function ( data, type, full, meta ) {
                  return data;
                }
              },
              {   targets: 7,
                width: '10%',
                render: function ( data, type, full, meta ) {
                  return data;
                }
              },
              {   targets: 8,
                width: '7%',
                render: function ( data, type, full, meta ) {
                  return '<center>'+data+'</center>';
                }
              },
        //aksi
        {   targets: 9, 
          width: '10%',
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
            var callback=getAjaxData("<?php echo base_url('master/master_indikator/view_one')?>",data);  
            $('#view').modal('show');
            $('.header_data').html(callback['nama']);
            $('input[name="data_id_view"]').val(callback['id']);
            $('#data_kode_view').html(callback['kode_kpi']);
            $('#data_name_view').html(callback['nama']);
            $('#data_pengukuran_view').html(callback['cara_mengukur']);
            $('#data_sumber_data_view').html(callback['sumber']);
            $('#data_pelaporan_view').html(callback['periode_pelaporan']);
            $('#data_polarisasi_view').html(callback['polarisasi']);
            $('#data_bagian_view').html(callback['nama_bagian']);
            $('#data_kaitan_view').html(callback['kaitan']);
            $('#data_rumus_view').html(callback['rumus']);
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
            var id = $('input[name="data_id_view"]').val();
            var data={id_kpi:id};
            var callback=getAjaxData("<?php echo base_url('master/master_indikator/view_one')?>",data); 
            $('#view').modal('toggle');
            setTimeout(function () {
             $('#edit').modal('show');
           },1000);
            var kode = callback['kode_kpi'];
            select_data('data_bagian_edit',url_select,'master_bagian','kode_bagian','nama','0');
            $('.header_data').html(callback['nama']);
            $('#data_id_edit').val(callback['id']);
            $('#data_kode_edit_old').val(callback['kode_kpi']);
            $('#data_kode_edit').val(callback['kode_kpi']);
            $('#data_name_edit').val(callback['nama']);
            $('#data_pengukuran_edit').val(callback['cara_mengukur']);
            $('#data_rumus_edit').val(callback['rumus']);
            $('#data_sumber_edit').val(callback['sumber']);
            $('#data_pelaporan_edit').val(callback['kode_periode_pelaporan']).trigger('change');
            $('#data_polarisasi_edit').val(callback['polarisasi']).trigger('change');
            $('#data_bagian_edit').val(callback['kode_bagian']).trigger('change');
            $('#data_kaitan_edit').val(callback['kode_kaitan']).trigger('change');

          }
          function refreshCode() {
            kode_generator("<?php echo base_url('master/master_indikator/kode');?>",'data_kode_add');
          }
          function delete_modal(id) {
            var data={id_kpi:id};
            var callback=getAjaxData("<?php echo base_url('master/master_indikator/view_one')?>",data);
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
        submitAjax("<?php echo base_url('master/edt_indikator')?>",'edit','form_edit',null,null);
        $('#table_data').DataTable().ajax.reload(function (){
          Pace.restart();
        });
      }else{
        notValidParamx();
      } 
    }
    function do_add(){
      if($("#form_add")[0].checkValidity()) {
        submitAjax("<?php echo base_url('master/add_indikator')?>",null,'form_add',null,null);
        $('#table_data').DataTable().ajax.reload(function (){
          Pace.restart();
        });
        $('#form_add')[0].reset();
        refreshCode();
      }else{
        notValidParamx();
      }
    }
  </script>