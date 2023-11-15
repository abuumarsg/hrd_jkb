<div class="content-wrapper">
  <section class="content-header">
    <h1>
      <i class="fas fa-tasks"></i> Data 
      <small>Presensi</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
      <li class="active">Data Presensi</li>
    </ol>
  </section>
  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box box-success">
          <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-calendar-check"></i> Daftar Presensi</h3>
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
                        echo ' <button class="btn btn-success" type="button" data-toggle="collapse" data-target="#add" aria-expanded="false" id="btn_add_collapse" aria-controls="import"><i class="fa fa-plus"></i> Tambah Presensi</button> ';
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
                              <li><a href="'.base_url('presensi/export_presensi_pa').'">Export Template</a></li>
                              <li><a onclick="rekap_data()">Export Data</a></li>
                            </ul>
                          </div>';
                      }
                      if (in_array('SNC', $access['access'])) {
                        echo ' <button class="btn btn-info" data-toggle="modal" data-target="#sync" aria-expanded="false" aria-controls="sync"><i class="fas fa-refresh"></i> Sync Data</button>';
                      }
                      ?>
                      <?php 
                      if(in_array('IMP', $access['access'])){?>
                          <div class="modal fade" id="import" role="dialog">
                            <div class="modal-dialog">
                              <div class="modal-content text-center">
                                <div class="modal-header">
                                  <button type="button" class="close all_btn_import" data-dismiss="modal">&times;</button>
                                  <h2 class="modal-title">Import Data <b class="text-muted header_data">Excel</b></h2>
                                </div>
                              <form id="form_import" action="#">
                                <div class="modal-body">
                                  <p class="text-muted">File Data Template Presensi harus tipe *.xls, *.xlsx, *.csv, *.ods dan *.ots</p>
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
                        <?php  if(in_array('SNC', $access['access'])){?>
                          <div class="modal fade" id="sync" role="dialog">
                            <div class="modal-dialog">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <button type="button" class="close all_btn_import" data-dismiss="modal">&times;</button>
                                  <h2 class="modal-title">Syncronize Data <b class="text-muted header_data">Presensi</b></h2>
                                </div>
                              <form id="form_sync">
                                <div class="modal-body">
                                  <div class="row">
                                    <div class="col-md-12">
                                      <div class="form-group">
                                        <div class="col-md-6">
                                          <label>Pilih Karyawan</label>
                                        </div>
                                        <div class="col-md-6">
                                          <span id="kary_off_edit" style="font-size: 20px;"><i class="far fa-square" aria-hidden="true"></i></span>
                                          <span id="kary_on_edit" style="display: none; font-size: 20px;"><i class="far fa-check-square" aria-hidden="true"></i></span>&nbsp;&nbsp;
                                          <span style="padding-bottom: 9px;vertical-align: middle;"><b>Pilih Semua Karyawan</b></span>
                                          <input type="hidden" name="all_kary" id="kary_edit">
                                        </div>
                                        <div class="col-md-12" id="div_kar_edit">
                                          <select class="form-control select2" name="karyawan[]" multiple="multiple" id="data_karyawan_edit" style="width: 100%;"></select>
                                        </div><br><br><br><br>
                                      </div>
                                      <div class="col-md-12">
                                        <div class="form-group">
                                          <label>Pilih Bulan</label>
                                          <select class="form-control select2" name="bulan_for" id="data_bulan_for" style="width: 100%;" required="required">
                                            <?php
                                              $bulan_for = $this->formatter->getMonth();
                                              foreach ($bulan_for as $buf => $valf) {
                                                echo '<option value="'.$buf.'">'.$valf.'</option>';
                                              }
                                            ?>
                                          </select>
                                        </div>
                                      </div>
                                      <div class="col-md-12">
                                        <div class="form-group">
                                          <label>Pilih Tahun</label>
                                          <?php
                                          $sels = array(date('Y'));
                                          $exs = array('class'=>'form-control select2','style'=>'width:100%;','required'=>'required','id'=>'data_tahun_sync');
                                          echo form_dropdown('tahun',$tahun,$sels,$exs);
                                          ?>
                                        </div>
                                      </div>
                                    </div>
                                  </div>                            
                                </div> 
                                <div class="modal-footer">
                                  <div id="progress2sync" style="float: left;"></div>
                                  <div class="pull-right">
                                    <button type="button" onclick="do_sync_data()" class="btn btn-success"><i class="fas fa-refresh"></i> Sync Data</button>
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
                                  </div>
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
                        <div class="col-md-3"></div>
                          <div class="col-md-6">
                            <form id="form_add">
                              <div class="form-group">
                                <label>Pilih Karyawan</label>
                                <select class="form-control select2" name="karyawan[]" id="data_karyawan_add" multiple="multiple" style="width: 100%;"></select>
                              </div>
                              <div class="form-group">
                                <label>Pilih Bulan</label>
                                <?php
                                $sel_b = array(date('m'));
                                $ex_b = array('class'=>'form-control select2','style'=>'width:100%;','required'=>'required','id'=>'data_bulan_add');
                                echo form_dropdown('bulan',$bulan,$sel_b,$ex_b);
                                ?>
                              </div>
                              <div class="form-group">
                                <label>Pilih Tahun</label>
                                <?php
                                $sel1 = array(date('Y'));
                                $ex1 = array('class'=>'form-control select2','style'=>'width:100%;','required'=>'required','id'=>'data_tahun_add');
                                echo form_dropdown('tahun',$tahun,$sel1,$ex1);
                                ?>
                              </div>
                              <div class="form-group clearfix">
                                <label>Ijin</label>
                                <input type="number" placeholder="Masukkan Jumlah Ijin" name="ijin" class="form-control" id="data_ijin_add">
                              </div>
                              <div class="form-group clearfix">
                                <label>Terlambat</label>
                                <input type="number" placeholder="Masukkan Jumlah Terlambat" name="telat" class="form-control" id="data_telat_add">
                              </div>
                              <div class="form-group clearfix">
                                <label>Bolos</label>
                                <input type="number" placeholder="Masukkan Jumlah Bolos" name="mangkir" class="form-control" id="data_mangkir_add">
                              </div>
                              <div class="form-group clearfix">
                                <label>SP</label>
                                <input type="number" placeholder="Masukkan Jumlah SP" name="sp" class="form-control" id="data_sp_add">
                              </div> 
                              <div class="form-group clearfix">
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
                              <th>Bulan dan Tahun</th>
                              <th>NIK</th>
                              <th>Nama Karyawan</th>
                              <th>Jabatan</th>
                              <th>Bagian</th>
                              <th>Lokasi Kerja</th>                              
                              <th>Ijin</th>
                              <th>Terlambat</th>
                              <th>Bolos</th>
                              <th>SP</th>
                              <th>Tanggal</th>
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
                      <label class="col-md-6 control-label">NIK</label>
                      <div class="col-md-6" id="data_nik_view"></div>
                    </div>
                    <div class="form-group col-md-12">
                      <label class="col-md-6 control-label">Nama Karyawan</label>
                      <div class="col-md-6" id="data_name_view"></div>
                    </div>
                    <div class="form-group col-md-12">
                      <label class="col-md-6 control-label">Jabatan</label>
                      <div class="col-md-6" id="data_nama_jabatan_view"></div>
                    </div>
                    <div class="form-group col-md-12">
                      <label class="col-md-6 control-label">Lokasi Kerja</label>
                      <div class="col-md-6" id="data_nama_loker_view"></div>
                    </div>
                    <div class="form-group col-md-12">
                      <label class="col-md-6 control-label">Bagian</label>
                      <div class="col-md-6" id="data_nama_bagian_view"></div>
                    </div>
                    <div class="form-group col-md-12">
                      <label class="col-md-6 control-label">Bulan dan Tahun</label>
                      <div class="col-md-6" id="data_bulan_tahun_view"></div>
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
                    <h3 class="text-center">Rincian Presensi</h3>
                    <table class="table table-hover table-striped">
                      <thead>
                        <tr class="bg-blue">
                          <th class="text-center">Jenis Presensi</th>
                          <th class="text-center">Jumlah</th>
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
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h2 class="modal-title">Edit Data <b class="text-muted header_data"></b></h2>
      </div>
      <div class="modal-body">
        <div class="row">
          <form id="form_edit">
          <input type="hidden" id="data_id_edit" name="id" value="">
          <input type="hidden" id="data_bulan_old_edit" name="bulan_old" value="">
          <input type="hidden" id="data_tahun_old_edit" name="tahun_old" value="">
          <div class="col-md-12">
            <div class="form-group">
              <label>Pilih Bulan</label>
              <?php
              $sel_b = array(date('m'));
              $ex_b = array('class'=>'form-control select2','style'=>'width:100%;','required'=>'required','id'=>'data_bulan_edit');
              echo form_dropdown('bulan',$bulan,$sel_b,$ex_b);
              ?>
            </div>
            <div class="form-group">
              <label>Pilih Tahun</label>
              <?php
              $sel1 = array(date('Y'));
              $ex1 = array('class'=>'form-control select2','style'=>'width:100%;','required'=>'required','id'=>'data_tahun_edit');
              echo form_dropdown('tahun',$tahun,$sel1,$ex1);
              ?>
            </div>
            <div class="form-group">
              <label>Ijin</label>
              <input type="number" placeholder="Masukkan Jumlah Ijin" name="ijin" class="form-control" id="data_ijin_edit">
            </div>
            <div class="form-group">
              <label>Terlambat</label>
              <input type="number" placeholder="Masukkan Jumlah Terlambat" name="telat" class="form-control" id="data_telat_edit">
            </div>
            <div class="form-group">
              <label>Bolos</label>
              <input type="number" placeholder="Masukkan Jumlah Bolos" name="mangkir" class="form-control" id="data_mangkir_edit">
            </div>
            <div class="form-group">
              <label>SP</label>
              <input type="number" placeholder="Masukkan Jumlah SP" name="sp" class="form-control" id="data_sp_edit">
            </div> 
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
  var table="data_presensi_pa";
  var column="id_presensi";
  $(document).ready(function(){
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
      var urladd = "<?php echo base_url('presensi/import_data_presensi_pa'); ?>";
      submitAjaxFile(urladd,data_add,'#import','#progress2','.all_btn_import');
    });
    $('#table_data').DataTable( {
      ajax: {
        url: "<?php echo base_url('presensi/data_presensi_pa/view_all/')?>",
        type: 'POST',
        data:{access:"<?php echo $this->codegenerator->encryptChar($access);?>"}
      },
      scrollX: true,
      columnDefs: [
      {   targets: 0, 
        width: '5%',
        render: function ( data, type, full, meta ) {
          return '<center>'+(meta.row+1)+'.</center>';
        }
      },
      //aksi
      {   targets: [7,8,9,10,11,12,13], 
        width: '7%',
        render: function ( data, type, full, meta ) {
          return '<center>'+data+'</center>';
        }
      },
      ]
    });
    $('#btn_add_collapse').click(function(){
      getSelect2('<?php echo base_url('employee/mutasi_jabatan/employee');?>','data_karyawan_add');              
      getSelect2('<?php echo base_url('global_control/select2_custom/master_periode_penilaian');?>','data_periode_add',{table:'<?php echo $this->codegenerator->encryptChar("master_periode_penilaian");?>',});
    });
		getSelect2("<?php echo base_url('employee/view_mutasi_jabatan/employee')?>",'data_karyawan_edit');
		$('#kary_off_edit').click(function(){
			$('#kary_off_edit').hide();
			$('#kary_on_edit').show();
			$('input[name="all_kary"]').val('1');
			$('#data_karyawan_edit').removeAttr('required');
			$('#div_kar_edit').hide();
			$('#data_karyawan_edit').val('').trigger('change');
		});
		$('#kary_on_edit').click(function(){
			$('#kary_off_edit').show();
			$('#kary_on_edit').hide();
			$('input[name="all_kary"]').val('0');
			$('#data_karyawan_edit').attr('required','required');
			$('#div_kar_edit').show();
		});
  });
  function checkFile(idf,idt,btnx) {
    var fext = ['xls', 'xlsx', 'csv', 'ods', 'ots'];
    pathFile(idf,idt,fext,btnx);
  }
  function view_modal(id) {
    var data={id_presensi:id};
    var callback=getAjaxData("<?php echo base_url('presensi/data_presensi_pa/view_one')?>",data);  
    $('#view').modal('show');
    $('.header_data').html(callback['nama']);
    $('input[name="data_id_view"]').val(callback['id']);
    $('#data_nik_view').html(callback['nik']);
    $('#data_name_view').html(callback['nama']);
    $('#data_nama_jabatan_view').html(callback['nama_jabatan']);
    $('#data_nama_loker_view').html(callback['nama_loker']);
    $('#data_nama_bagian_view').html(callback['nama_bagian']);
    $('#data_sp_view').html(callback['sp']);
    $('#data_ijin_view').html(callback['ijin']);
    $('#data_mangkir_view').html(callback['mangkir']);
    $('#data_telat_view').html(callback['telat']);
    $('#data_bulan_tahun_view').html(callback['bulan_tahun']);
    $('#data_tr_view').html(callback['data_tr_view']);
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
    var data={id_presensi:id};
    var callback=getAjaxData("<?php echo base_url('presensi/data_presensi_pa/view_one')?>",data); 
    $('#view').modal('toggle');
    setTimeout(function () {
      $('#edit').modal('show');
    },1000);
    getSelect2('<?php echo base_url('global_control/select2_custom/master_periode_penilaian');?>','data_periode_edit',{table:'<?php echo $this->codegenerator->encryptChar("master_periode_penilaian");?>',});
    $('.header_data').html(callback['nama']);
    $('#data_id_edit').val(callback['id']);
    $('#data_bulan_old_edit').val(callback['bulan_val']);
    $('#data_tahun_old_edit').val(callback['tahun_val']);
    $('#data_sp_edit').val(callback['sp_val']);
    $('#data_ijin_edit').val(callback['ijin_val']);
    $('#data_mangkir_edit').val(callback['mangkir_val']);
    $('#data_telat_edit').val(callback['telat_val']);
    $('#data_bulan_edit').val(callback['bulan_val']).trigger('change');
    $('#data_tahun_edit').val(callback['tahun_val']).trigger('change');
  }
  function delete_modal(id) {
    var data={id_presensi:id};
    var callback=getAjaxData("<?php echo base_url('presensi/data_presensi_pa/view_one')?>",data);
    var datax={table:table,column:column,id:id,nama:callback['nama']};
    loadModalAjax("<?php echo base_url('pages/load_modal_delete')?>",'modal_delete_partial',datax,'delete');
  }
  //doing db transaction
  function do_status(id,data) {
    var data_table={status:data};
    var where={id_presensi:id};
    var datax={table:table,where:where,data:data_table};
    submitAjax("<?php echo base_url('global_control/change_status')?>",null,datax,null,null,'status');
    $('#table_data').DataTable().ajax.reload(function (){
      Pace.restart();
    });
  }
  function do_edit(){
    if($("#form_edit")[0].checkValidity()) {
      submitAjax("<?php echo base_url('presensi/edt_data_presensi_pa')?>",'edit','form_edit',null,null);
      $('#table_data').DataTable().ajax.reload(function (){Pace.restart();},false);
    }else{
      notValidParamx();
    } 
  }
  function do_add(){
    if($("#form_add")[0].checkValidity()) {
      submitAjax("<?php echo base_url('presensi/add_data_presensi_pa')?>",null,'form_add',null,null);
      $('#table_data').DataTable().ajax.reload(function (){Pace.restart();},false);
      $('#form_add')[0].reset();
      $('#data_karyawan_add,#data_bulan_add,#data_tahun_add').val([]).trigger('change');
    }else{
      notValidParamx();
    }
  }
  function rekap_data() {
    window.open("<?php echo base_url('rekap/export_data_presensi_pa'); ?>", "_blank");
  }
	function do_sync_data() {
		show_loader();
		$('#progress2sync').html('<i class="fa fa-spinner fa-pulse fa-fw"></i> Mohon Tunggu, Sedang men-Syncronize Data...');
		$('#progress2sync').show();
		submitAjax("<?php echo base_url('presensi/sync_data_presensi_pa')?>", 'sync', 'form_sync', null, null);
    $('#table_data').DataTable().ajax.reload(function (){Pace.restart();},false);
		$('#data_periode_sync').val('').trigger('change');
		$('#sync_bagian').val('').trigger('change');
		$('#sync_karyawan').val('').trigger('change');
		$('#progress2sync').hide();
	}
</script>