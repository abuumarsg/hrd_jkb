  <div class="content-wrapper">
    <section class="content-header">
      <h1>
        <i class="fa fa-calendar"></i> Agenda 
        <small>Aspek Sikap</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
        <li class="active">Agenda Aspek Sikap</li>
      </ol>
    </section>
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box box-warning">
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-list"></i> Daftar Agenda Aspek Sikap</h3>
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
                        <?php if(in_array('ADD',$access['access'])){?>
                        <button class="btn btn-success" type="button" data-toggle="collapse" id="btn_add_collapse" data-target="#add"><i class="fa fa-plus"></i> Tambah Agenda Baru</button>
                        <?php } ?>
                      </div>
                      <div class="pull-right" style="font-size: 8pt;">
                        <i class="fa fa-toggle-on stat scc"></i> Aktif<br>
                        <i class="fa fa-toggle-off stat err"></i> Tidak Aktif
                      </div>
                    </div>
                  </div>
                    <?php if (in_array('ADD', $access['access'])) {?>
                    <div class="collapse" id="add">
                      <br>
                      <div class="col-md-2"></div>
                      <div class="col-md-8">
                        <form id="form_add">
                          <div class="form-group">
                            <label>Kode Agenda Sikap</label>
                            <input type="text" placeholder="Masukkan Kode Agenda Sikap" name="kode" id="data_kode_add" class="form-control" value="" required="required" readonly="readonly">
                          </div>
                          <div class="form-group"> 
                            <label>Nama Agenda Sikap</label>
                            <input type="text" placeholder="Masukkan Nama Agenda Sikap" name="nama" id="data_name_add" class="form-control" required="required">
                          </div>
                          <div class="form-group"> 
                            <label>Pilih Rancangan</label>
                            <select class="form-control select2" name="konsep" id="data_konsep_add" style="width: 100%;"></select>
                          </div>
                          <div class="form-group">
                            <label>Tanggal</label>
                            <div class="has-feedback">
                              <span class="fa fa-calendar form-control-feedback"></span>
                              <input type="text" placeholder="Tanggal" id="data_date_add" name="tanggal" value="" class="form-control date-range" required="required">
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-md-6">
                              <div class="form-group">
                                <label>Pilih Periode</label>
                                <select class="form-control select2" name="periode" id="data_periode_add" style="width: 100%;"></select>
                              </div>
                            </div>
                            <div class="col-md-6">
                              <div class="form-group">
                                <label>Pilih Tahun</label>
                                <?php
                                $sel1 = array(date('Y'));
                                $ex1 = array('class'=>'form-control select2','style'=>'width:100%;','required'=>'required','id'=>'data_tahun_add');
                                echo form_dropdown('tahun',$tahun,$sel1,$ex1);
                                ?>
                              </div>
                            </div>
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
                  <div class="callout callout-info"><b><i class="fa fa-bullhorn"></i> Petunjuk</b>
                    <ul>
                      <li>Setiap Agenda Penilaian <b>HARUS</b> dilakukan <b>Validasi</b> sehingga poin pada karyawan akan terupdate sesuai Agenda yang divalidasi</li>
                      <li>Anda <b>tidak bisa</b> melakukan Validasi jika Agenda dalam keadaan <b>Tidak Aktif</b> atau <b>belum melewati</b> tanggal akhir Agenda</li>
                      <li>Anda hanya bisa membuat <b>1 (SATU)</b> Agenda dalam satu periode penilaian</li>
                    </ul>
                  </div>
                  <table id="table_data" class="table table-bordered table-striped table-responsive" width="100%">
                    <thead>
                      <tr>
                        <th>No.</th>
                        <th>Kode Agenda</th>
                        <th>Nama Agenda Sikap</th>
                        <th>Progress</th>
                        <th>Nama Rancangan</th>
                        <th>Periode Penilaian</th>
                        <th>Tanggal Agenda</th>
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
                <label class="col-md-6 control-label">Kode Agenda</label>
                <div class="col-md-6" id="data_kode_view"></div>
              </div>
              <div class="form-group col-md-12">
                <label class="col-md-6 control-label">Nama Agenda</label>
                <div class="col-md-6" id="data_name_view"></div>
              </div>
              <div class="form-group col-md-12">
                <label class="col-md-6 control-label">Periode</label>
                <div class="col-md-6" id="data_periode_view"></div>
              </div>
              <div class="form-group col-md-12">
                <label class="col-md-6 control-label">Tahun</label>
                <div class="col-md-6" id="data_tahun_view"></div>
              </div>
              <div class="form-group col-md-12">
                <label class="col-md-6 control-label">Tanggal Agenda</label>
                <div class="col-md-6" id="data_tanggal_view"></div>
              </div>
              <div class="form-group col-md-12">
                <label class="col-md-6 control-label">Nama Rancangan</label>
                <div class="col-md-6" id="data_konsep_view"></div>
              </div>
              <div class="form-group col-md-12">
                <label class="col-md-6 control-label">Tabel Agenda</label>
                <div class="col-md-6" id="data_name_table_view"></div>
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
            <input type="hidden" id="data_periode_edit_old" name="periode_old" value="">
            <input type="hidden" id="data_tahun_edit_old" name="tahun_old" value="">
            <div class="form-group">
              <label>Kode Agenda Sikap</label>
              <input type="text" placeholder="Masukkan Kode Agenda Sikap" id="data_kode_edit" name="kode" value="" class="form-control" required="required" readonly="readonly">
            </div>
            <div class="form-group">
              <label>Nama Agenda</label>
              <input type="text" placeholder="Masukkan Nama Agenda Sikap" id="data_name_edit" name="nama" value="" class="form-control" required="required">
            </div>
            <div class="form-group">
              <label>Tanggal</label>
              <div class="has-feedback">
                <span class="fa fa-calendar form-control-feedback"></span>
                <input type="text" placeholder="Tanggal" id="data_date_edit" name="tanggal" value="" class="form-control date-range" required="required">
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Pilih Periode</label>
                  <select class="form-control select2" name="periode" id="data_periode_edit" style="width: 100%;"></select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>Pilih Tahun</label>
                  <?php
                  $sel1 = array(date('Y'));
                  $ex1 = array('class'=>'form-control select2','style'=>'width:100%;','required'=>'required','id'=>'data_tahun_edit');
                  echo form_dropdown('tahun',$tahun,$sel1,$ex1);
                  ?>
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

  <!-- delete -->
  <div id="modal_delete_partial"></div>
  <!-- validate -->
  <div id="validate" class="modal fade" role="dialog">
    <div class="modal-dialog modal-sm" id="box_color">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title text-center" id="header_data"></h4>
        </div>
        <form id="form_validate">
          <div class="modal-body text-center">
            <input type="hidden" id="data_kode" name="kode">
            <input type="hidden" id="data_status" name="status">
            <p id="data_confirm"></p>
          </div>
        </form>
        <div class="modal-footer">
          <button type="button" onclick="do_validate()" class="btn btn-primary" id="btn_validate"></button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
        </div>
      </div>
    </div>
  </div>
  <script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
  <script type="text/javascript">
  var url_select="<?php echo base_url('global_control/select2_global');?>";
  //wajib diisi
  var table="agenda_sikap";
  var column="id_a_sikap";
  $(document).ready(function(){ 
    refreshCode();
    $('#table_data').DataTable( {
      ajax: {
        url: "<?php echo base_url('agenda/agenda_sikap/view_all')?>",
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
      {   targets: [1,2],
        width: '10%',
        render: function ( data, type, full, meta ) {
          return data;
        }
      },
      {   targets: 3,
        width: '25%',
        render: function ( data, type, full, meta ) {
          return data;
        }
      },
      {   targets: [6,7,8],
        width: '5%',
        render: function ( data, type, full, meta ) {
          return '<center>'+data+'</center>';
        }
      },
      {   targets: 9, 
        width: '10%',
        render: function ( data, type, full, meta ) {
          return '<center>'+data+'</center>';
        }
      },
      ],
      drawCallback: function() {
        $('[data-toggle="tooltip"]').tooltip();
      }
    });
    $('#btn_add_collapse').click(function(){
      select_data('data_konsep_add',url_select,'concept_sikap','kode_c_sikap','nama');
      getSelect2('<?php echo base_url('global_control/select2_custom/master_periode_penilaian');?>','data_periode_add',{table:'<?php echo $this->codegenerator->encryptChar("master_periode_penilaian");?>',});
    })
  });
  function refreshCode() {
    kode_generator("<?php echo base_url('agenda/agenda_sikap/kode');?>",'data_kode_add');
  }
  function view_modal(id) {
    var data={id_a_sikap:id};
    var callback=getAjaxData("<?php echo base_url('agenda/agenda_sikap/view_one')?>",data);  
    $('#view').modal('show');
    $('.header_data').html(callback['nama']);
    $('#data_kode_view').html(callback['kode_a_sikap']);
    $('#data_name_view').html(callback['nama_detail']);
    $('#data_name_table_view').html(callback['nama_tabel']);
    $('#data_konsep_view').html(callback['nama_konsep']);
    $('#data_tanggal_view').html(callback['tanggal']);
    $('#data_periode_view').html(callback['periode_view']);
    $('#data_tahun_view').html(callback['tahun']);
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
    var data={id_a_sikap:id};
    var callback=getAjaxData("<?php echo base_url('agenda/agenda_sikap/view_one')?>",data);
    getSelect2('<?php echo base_url('global_control/select2_custom/master_periode_penilaian');?>','data_periode_edit',{table:'<?php echo $this->codegenerator->encryptChar("master_periode_penilaian");?>',});
    $('#view').modal('toggle');
    setTimeout(function () {
     $('#edit').modal('show');
    },600); 
    $('.header_data').html(callback['nama']);
    $('#data_id_edit').val(callback['id']);
    $('#data_kode_edit_old').val(callback['kode_a_sikap']);
    $('#data_periode_edit_old').val(callback['periode']);
    $('#data_tahun_edit_old').val(callback['tahun']);
    $('#data_kode_edit').val(callback['kode_a_sikap']);
    $('#data_name_edit').val(callback['nama']);
    $("#data_date_edit").data('daterangepicker').setStartDate(callback['tgl_mulai_val']);
    $("#data_date_edit").data('daterangepicker').setEndDate(callback['tgl_selesai_val']);
    $('#data_periode_edit').val(callback['periode']).trigger('change');
    $('#data_tahun_edit').val(callback['tahun']).trigger('change');
  }
  function delete_modal(id) {
    var data={id_a_sikap:id};
    var callback=getAjaxData("<?php echo base_url('agenda/agenda_sikap/view_one')?>",data);
    var datax={table:table,column:column,id:id,nama:callback['nama']};
    loadModalAjax("<?php echo base_url('pages/load_modal_delete')?>",'modal_delete_partial',datax,'delete');
  }
  function validate_modal(id,st) {
    var data={id_a_sikap:id};
    var callback=getAjaxData("<?php echo base_url('agenda/agenda_sikap/view_one')?>",data);
    $('#validate').modal('show');
    $('#data_kode').val(callback['kode_a_sikap']);
    $('#data_status').val(st);
    if (st == 0) {
      $('#box_color').removeClass( "modal-success" ).addClass( "modal-warning" );
      $('#header_data').html('Konfirmasi Batal Validasi');
      $('#data_confirm').html('Apakah Anda yakin akan membatalkan validasi pada Agenda <b>'+callback['nama']+'</b>?');
      $('#btn_validate').html('<i class="fa fa-times"></i> Batalkan Validasi');
    }else{
      $('#box_color').removeClass( "modal-warning" ).addClass( "modal-success" );
      $('#header_data').html('Konfirmasi Validasi');
      $('#data_confirm').html('Apakah Anda yakin akan melakukan validasi pada Agenda <b>'+callback['nama']+'</b>?');
      $('#btn_validate').html('<i class="fa fa-check"></i> Validasi');
    }
  }
  //doing db transaction
  function do_status(id,data) {
    var data_table={status:data};
    var where={id_a_sikap:id};
    var datax={table:table,where:where,data:data_table};
    submitAjax("<?php echo base_url('agenda/stt_agenda_sikap')?>",null,datax,null,null,'status');
    $('#table_data').DataTable().ajax.reload(function (){
      Pace.restart();
    });
  }
  function do_validate(){
    if($("#form_validate")[0].checkValidity()) {
      submitAjax("<?php echo base_url('agenda/val_agenda_sikap')?>",'validate','form_validate',null,null);
      $('#table_data').DataTable().ajax.reload(function (){Pace.restart();},false);
    }else{
      notValidParamx();
    } 
  }
  function do_edit(){
    if($("#form_edit")[0].checkValidity()) {
      submitAjax("<?php echo base_url('agenda/edt_agenda_sikap')?>",'edit','form_edit',null,null);
      $('#table_data').DataTable().ajax.reload(function (){Pace.restart();},false);
    }else{
      notValidParamx();
    } 
  }
  function do_add(){
    if($("#form_add")[0].checkValidity()) {
      Pace.restart();
      show_loader();
      submitAjax("<?php echo base_url('agenda/add_agenda_sikap')?>",null,'form_add',null,null,'wload');
      $('#table_data').DataTable().ajax.reload(function (){Pace.restart();},false);
      $('#form_add')[0].reset();
      $('#data_periode_add').val([]).trigger('change');
      $('#data_konsep_add').val([]).trigger('change');
      $('#data_tahun_add').val([]).trigger('change');
      refreshCode();
    }else{
      notValidParamx();
    } 
  }
</script>