  <div class="content-wrapper">
    <section class="content-header">
      <h1>
        <i class="fa fa-calendar"></i> Log Agenda 
        <small>Kpi</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
        <li class="active">Log Agenda Kpi</li>
      </ol>
    </section>
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box box-warning">
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-list"></i> Daftar Log Agenda KPI</h3>
              <div class="box-tools pull-right">
                <button class="btn btn-box-tool" onclick="reload_table('table_data')" data-toggle="tooltip" title="Refresh Table"><i class="fas fa-sync"></i></button>
                <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
              <div class="row">
                <div class="col-md-12">
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
                <label class="col-md-6 control-label">Kode Log Agenda</label>
                <div class="col-md-6" id="data_kode_view"></div>
              </div>
              <div class="form-group col-md-12">
                <label class="col-md-6 control-label">Kode Agenda</label>
                <div class="col-md-6" id="data_kode_a_view"></div>
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
          <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
        </div>
      </div>
    </div>
  </div>
  <!-- delete -->
  <div id="modal_delete_partial"></div>
  <script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
  <script type="text/javascript">
  var url_select="<?php echo base_url('global_control/select2_global');?>";
  //wajib diisi
  var table="log_agenda_kpi";
  var column="id_l_a_kpi";
  $(document).ready(function(){ 
    $('#table_data').DataTable( {
      ajax: {
        url: "<?php echo base_url('agenda/log_agenda_kpi/view_all')?>",
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
      {   targets: 6,
        width: '10%',
        render: function ( data, type, full, meta ) {
          return '<center>'+data+'</center>';
        }
      },
      {   targets: 7,
        width: '10%',
        render: function ( data, type, full, meta ) {
          return '<center>'+data+'</center>';
        }
      },
      //aksi
      {   targets: 8, 
        width: '5%',
        render: function ( data, type, full, meta ) {
          return '<center>'+data+'</center>';
        }
      },
      ]
    });
  });
  function view_modal(id) {
    var data={id_l_a_kpi:id};
    var callback=getAjaxData("<?php echo base_url('agenda/log_agenda_kpi/view_one')?>",data);  
    $('#view').modal('show');
    $('.header_data').html(callback['nama']);
    $('#data_kode_view').html(callback['kode_l_a_kpi']);
    $('#data_kode_a_view').html(callback['kode_a_kpi']);
    $('#data_name_view').html(callback['nama']);
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
    var data={id_l_a_kpi:id};
    var callback=getAjaxData("<?php echo base_url('agenda/log_agenda_kpi/view_one')?>",data);
    getSelect2('<?php echo base_url('global_control/select2_custom/master_periode_penilaian');?>','data_periode_edit',{table:'<?php echo $this->codegenerator->encryptChar("master_periode_penilaian");?>',});
    $('#view').modal('toggle');
    setTimeout(function () {
     $('#edit').modal('show');
    },600); 
    $('.header_data').html(callback['nama']);
    $('#data_id_edit').val(callback['id']);
    $('#data_kode_edit_old').val(callback['kode_a_kpi']);
    $('#data_kode_edit').val(callback['kode_a_kpi']);
    $('#data_name_edit').val(callback['nama']);
    $("#data_date_edit").data('daterangepicker').setStartDate(callback['tgl_mulai_val']);
    $("#data_date_edit").data('daterangepicker').setEndDate(callback['tgl_selesai_val']);
    $('#data_periode_edit').val(callback['periode']).trigger('change');
    $('#data_tahun_edit').val(callback['tahun']).trigger('change');
  }
  function delete_modal(id) {
    var data={id_l_a_kpi:id};
    var callback=getAjaxData("<?php echo base_url('agenda/log_agenda_kpi/view_one')?>",data);
    var datax={table:table,column:column,id:id,nama:callback['nama'],nama_tabel:callback['nama_tabel'],del_link_tb:'agenda_kpi',del_link_col:'kode_a_kpi',del_link_data_col:callback['kode_a_kpi']};
    loadModalAjax("<?php echo base_url('pages/load_modal_delete')?>",'modal_delete_partial',datax,'delete');
  }
  //doing db transaction
  function do_status(id,data) {
    var data_table={status:data};
    var where={id_l_a_kpi:id};
    var datax={table:table,where:where,data:data_table};
    submitAjax("<?php echo base_url('global_control/change_status')?>",null,datax,null,null,'status');
    $('#table_data').DataTable().ajax.reload(function (){
      Pace.restart();
    });
  }
  function do_edit(){
    if($("#form_edit")[0].checkValidity()) {
      submitAjax("<?php echo base_url('agenda/edt_log_agenda_kpi')?>",'edit','form_edit',null,null);
      $('#table_data').DataTable().ajax.reload(function (){Pace.restart();},false);
    }else{
      notValidParamx();
    } 
  }
</script>