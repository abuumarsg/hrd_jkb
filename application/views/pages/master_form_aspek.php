  <div class="content-wrapper">
    <section class="content-header">
      <h1>
        <i class="fa fa-database"></i> Master Data 
        <small>Form Aspek Sikap</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
        <li class="active">Master Form Aspek</li>
      </ol>
    </section>
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box box-warning">
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-list"></i> Daftar Form Aspek</h3>
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
                          echo '<button class="btn btn-success" type="button" data-toggle="collapse" id="add_button" data-target="#add" aria-expanded="false" aria-controls="import"><i class="fa fa-plus"></i> Tambah Form</button>';
                        }?>
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
                          <p class="text-muted">Semua data harus diisi!</p>
                          <div class="form-group">
                            <label>Kode Form Aspek</label>
                            <input type="text" placeholder="Masukkan Kode Form Aspek" id="data_kode_add" name="kode" class="form-control" required="required" readonly="readonly">
                          </div>
                          <div class="form-group">
                            <label>Nama Form</label>
                            <input type="text" placeholder="Masukkan Nama Form Aspek" name="nama" class="form-control" required="required">
                          </div>
                          <div class="form-group">
                            <label>Tipe Form Untuk Jabatan</label>
                            <?php
                            $sel = array(NULL);
                            $ex = array('class'=>'form-control select2','placeholder'=>'Tipe Jabatan','required'=>'required','style'=>'width:100%');
                            echo form_dropdown('tipe',$tipe_jabatan,$sel,$ex);
                            ?>
                          </div>
                          <?php 
                          if (count($asp) > 0) {
                            ?>
                            <div class="form-group">
                              <label>Bobot <p class="text-red">Kosongkan isian bobot jika tidak ingin memasukkan Aspek Sikap tersebut kedalam form</p></label>
                              <table class="table table-condensed">
                                <thead>
                                  <tr class="bg-blue">
                                    <th>Nama Aspek Sikap</th>
                                    <th>Bobot (%)</th>
                                  </tr>
                                </thead>
                                <tbody id="table_aspek_sikap_add">
                                  
                                </tbody>
                              </table>
                            </div>
                          <?php } ?>
                          <div class="form-group">
                            <button type="button" onclick="do_add()" id="btn_add" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
                          </div>
                        </form>
                      </div>
                      </div><?php }?>
                    </div>
                  </div>
                  <br>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="callout callout-info" id="bantuan">
                        <b><i class="fa fa-info-circle"></i> Bantuan</b><br>Klik Bobot Aspek Untuk Melihat Detail
                      </div>
                      <table id="table_data" class="table table-bordered table-striped table-responsive" width="100%">
                        <thead>
                          <tr>
                            <th>No.</th>
                            <th>Kode Form Aspek</th>
                            <th>Nama Form Aspek</th>
                            <th>Tipe Form</th>
                            <th>Bobot</th>
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
                    <label class="col-md-6 control-label">Kode Form</label>
                    <div class="col-md-6" id="data_kode_view"></div>
                  </div>
                  <div class="form-group col-md-12">
                    <label class="col-md-6 control-label">Nama Form</label>
                    <div class="col-md-6" id="data_name_view"></div>
                  </div>
                  <div class="form-group col-md-12">
                    <label class="col-md-6 control-label">Tipe Form</label>
                    <div class="col-md-6" id="data_kode_tipe_view"></div>
                  </div>
                  <div class="form-group col-md-12">
                    <label class="col-md-6 control-label">Total Bobot Aspek</label>
                    <div class="col-md-6" id="data_total_bobot_aspek_view"></div>
                  </div>
                  <div class="form-group col-md-12">
                    <label class="col-md-6 control-label">Bobot Aspek</label>
                    <div class="col-md-6" id="data_bobot_aspek_view"></div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group col-md-12">
                    <label class="col-md-6 control-label">Status</label>
                    <div class="col-md-6" id="data_status_view">
                      
                    </div>
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
              <form id="form_edit">
                <input type="hidden" id="data_id_edit" name="id" value="">
                <input type="hidden" id="data_kode_edit_old" name="kode_old" value="">
                <div class="form-group">
                  <label>Kode Form Aspek</label>
                  <input type="text" placeholder="Masukkan Kode Form Aspek" id="data_kode_edit" name="kode" value="" class="form-control" required="required" readonly="readonly">
                </div>
                <div class="form-group">
                  <label>Nama Form Aspek</label>
                  <input type="text" placeholder="Masukkan Nama Form Aspek" id="data_name_edit" name="nama" value="" class="form-control" required="required">
                </div>
                <div class="form-group">
                  <label>Tipe Form Untuk Jabatan</label>
                  <?php
                  $sel = array(NULL);
                  $ex = array('class'=>'form-control select2','placeholder'=>'Tipe Jabatan','required'=>'required','style'=>'width:100%');
                  echo form_dropdown('tipe',$tipe_jabatan,$sel,$ex);
                  ?>
                </div>
                <?php 
                if (count($asp) > 0) {
                  ?>
                  <div class="form-group">
                    <label>Bobot <p class="text-red">Kosongkan isian bobot jika tidak ingin memasukkan Aspek Sikap tersebut kedalam form</p></label>
                    <div class="callout callout-danger">
                      <b><i class="fa fa-warning"></i> Peringatan</b><br>
                      Edit data Bobot Aspek Sikap akan berpengaruh pada <b>Agenda Sikap <b class="text-red">(yang belum dilakukan Validasi)</b></b>. Pastikan data diedit dengan benar!
                    </div>
                    <table class="table table-condensed">
                      <thead>
                        <tr class="bg-blue">
                          <th>Nama Aspek Sikap</th>
                          <th>Bobot (%)</th>
                        </tr>
                      </thead>
                      <tbody id="table_aspek_sikap_edit">
                      </tbody>
                    </table>
                    
                  </div>
                <?php } ?>
              </div>
              <div class="modal-footer">
                <button type="button" onclick="do_edit()" id="btn_edit" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
              </div>
            </form>
          </div>

        </div>
      </div>
<!-- delete -->
<div id="modal_delete_partial"></div>
<script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
<script type="text/javascript">
  var url_select="<?php echo base_url('global_control/select2_global');?>";
  var table="master_form_aspek";
  var column="id_form";
  $(document).ready(function(){
    $('#add_button').click(function () {
      tableData('add','no');
    });refreshCode();
    $('#table_data').DataTable( {
      ajax: {
        url: "<?php echo base_url('master/master_form_aspek/view_all/')?>",
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
        {   targets: 4,
            width: '15%',
            render: function ( data, type, full, meta ) {
              return '<a style="cursor: pointer;color: #00C1FF;" onclick="show_bobot('+full[0]+')"">'+full[8]+'% </a>'+
                '<div id="sh_'+full[0]+'" style="margin-top:5px;display:none;">'+data+'</div>';
            }
        },
        {   targets: 6,
            width: '7%',
            render: function ( data, type, full, meta ) {
              return '<center>'+data+'</center>';
            }
        },
        //aksi
        {   targets: 7, 
            width: '10%',
            render: function ( data, type, full, meta ) {
              return '<center>'+data+'</center>';
            }
        },
      ]
    });
  });
  function show_bobot(id) {
    $('#sh_'+id).slideToggle('slow');
  }
  function hitungbobot(kode) {
    var users = $('input[id="bobot_'+kode+'[]"]').map(function(){ 
      return this.value; 
    }).get();
    getAjaxCount(null,users,"data_hasilbobot_"+kode+"");
  }

  function refreshCode() {
    kode_generator("<?php echo base_url('master/master_form_aspek/kode');?>",'data_kode_add');
  }
  function view_modal(id) {
    var data={id_form:id};
    var callback=getAjaxData("<?php echo base_url('master/master_form_aspek/view_one')?>",data);  
    $('#view').modal('show');
    $('.header_data').html(callback['nama']);
    $('#data_kode_view').html(callback['kode_form']);
    $('#data_name_view').html(callback['nama']);
    $('#data_kode_tipe_view').html(callback['tipe']);
    $('#data_bobot_aspek_view').html(callback['bobot_aspek']);
    $('#data_total_bobot_aspek_view').html(callback['total_bobot_aspek']);
    var status = callback['status'];
    if(status==1){
      var statusval = '<b class="text-success">Aktif</b>';
    }else{
      var statusval = '<b class="text-danger">Tidak Aktif</b>';
    }
    $('#data_status_view').html(statusval);
    $('#data_create_date_view').html(callback['create_date']+' WIB');
    $('#data_update_date_view').html(callback['update_date']+' WIB');
    $('input[name="data_id_view"]').val(callback['id']);
    $('#data_create_by_view').html(callback['nama_buat']);
    $('#data_update_by_view').html(callback['nama_update']);
  }
  function edit_modal() {
    var id = $('input[name="data_id_view"]').val();
    tableData('edit',id);
    var data={id_form:id};
    var callback=getAjaxData("<?php echo base_url('master/master_form_aspek/view_one')?>",data); 
    $('#view').modal('toggle');
    setTimeout(function () {
      $('#edit').modal('show');
    },500); 
    $('.header_data').html(callback['nama']);
    $('#data_id_edit').val(callback['id']);
    $('#data_kode_edit_old').val(callback['kode_form']);
    $('#data_kode_edit').val(callback['kode_form']);
    $('#data_name_edit').val(callback['nama']);
    $('#data_kode_tipe_edit').val(callback['kode_tipe']).trigger('change');
    $('#data_bobot_aspek_edit').val(callback['bobot_aspek']);
    $('#data_hasilbobot_edit').val(callback['sumbobot']);
  }
  function delete_modal(id) {
    var data={id_form:id};
    var callback=getAjaxData("<?php echo base_url('master/master_form_aspek/view_one')?>",data);
    var datax={table:table,column:column,id:id,nama:callback['nama']};
    loadModalAjax("<?php echo base_url('pages/load_modal_delete')?>",'modal_delete_partial',datax,'delete');
  }
  //doing db transaction
  function do_status(id,data) {
    var data_table={status:data};
    var where={id_form:id};
    var datax={table:table,where:where,data:data_table};
    submitAjax("<?php echo base_url('global_control/change_status')?>",null,datax,null,null,'status');
    $('#table_data').DataTable().ajax.reload(function (){
      Pace.restart();
    });
  }
  function do_delete(){
    submitAjax("<?php echo base_url('master/del_form_aspek')?>",'delete','form_delete',null,null);
    $('#table_data').DataTable().ajax.reload(function (){
      Pace.restart();
    });
  }
  function do_edit(){
    if($("#form_edit")[0].checkValidity()) {
      var keyx = parseInt($('#data_hasilbobot_edit').val());
      if(keyx<100 || keyx>100){
        notValidCustom('Bobot Harus Sama Dengan 100');
      }else{
        submitAjax("<?php echo base_url('master/edt_form_aspek')?>",'edit','form_edit',null,null);
        $('#table_data').DataTable().ajax.reload(function (){Pace.restart();},false);
      }
    }else{
      notValidParamx();
    } 

  }
  function do_add(){
    if($("#form_add")[0].checkValidity()) {
      var keyx = parseInt($('#data_hasilbobot_add').val());
      if(keyx<100 || keyx>100){
        notValidCustom('Bobot Harus Sama Dengan 100');
      }else{
        submitAjax("<?php echo base_url('master/add_form_aspek')?>",null,'form_add',null,null);
        $('#table_data').DataTable().ajax.reload(function (){Pace.restart();},false);
        $('#form_add')[0].reset();
        refreshCode();
      }
    }else{
      notValidParamx();
    }
  }
  function tableData(kode,id_f) {
    var data={id:id_f,kode:kode};
    var callback=getAjaxData("<?php echo base_url('master/master_aspek_actv')?>",data); 
    $('#table_aspek_sikap_'+kode+'').html(callback);
    $('.bobot_'+kode+'').keyup(function(){ hitungbobot(kode); });
    $('.bobot_'+kode+'').blur(function(){ hitungbobot(kode); });
  }
  </script>
