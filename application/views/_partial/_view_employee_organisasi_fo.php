<meta http-equiv="cache-control" content="no-cache" />
<meta http-equiv="pragma" content="no-cache" />
  <button type="button" id="btn_org" class="btn btn-success btn-sm" onclick="add_modal_org()">
    <i class ="fa fa-plus"></i> Tambah Organisasi
  </button> 
  <div class="modal modal-default fade" id="add_org">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Tambah Data Organisasi</h4>
        </div>
        <form id="form_org_add">
          <div class="modal-body">
            <input type="hidden" name="nik" value="<?php echo $profile['nik']?>">
            <div class="form-group clearfix">
              <label class="col-sm-4 control-label">Nama Organisasi</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" name="nama_organisasi" placeholder="Nama Organisasi" required>
              </div>
            </div>
            <div class="form-group clearfix">
              <label for="nama" class="col-sm-4 control-label">Tanggal Masuk</label>
              <div class="col-sm-8">
                <div class="has-feedback">
                  <span class="fa fa-calendar form-control-feedback"></span>
                  <input type="text" name="tahun_masuk" class="form-control pull-right date-picker" placeholder="Tanggal Masuk" readonly="readonly">
                </div>
              </div>
            </div>
            <div class="form-group clearfix">
              <label for="nama" class="col-sm-4 control-label">Tanggal Keluar</label>
              <div class="col-sm-8">
                <div class="has-feedback">
                  <span class="fa fa-calendar form-control-feedback"></span>
                  <input type="text" name="tahun_keluar" class="form-control pull-right date-picker" placeholder="Tanggal Masuk" readonly="readonly">
                </div>
              </div>
            </div>
            <div class="form-group clearfix">
              <label class="col-sm-4 control-label">Jabatan</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" name="jabatan_org" placeholder="Jabatan">
              </div>
            </div>
          </div>
        </form>
        <div class="modal-footer">
          <button type="button" class="btn btn-success" onclick="do_add_org()"><i class="fa fa-floppy-o"></i> Simpan</button>
          <button type="button" class="btn btn-default pull-right" data-dismiss="modal"> Kembali</button>
        </div>
      </div>
    </div>
  </div>
<br>
<br>
<div class="row">
  <div class="col-md-12">
    <table id="table_data_org" class="table table-bordered table-striped table-responsive" width="100%">
      <thead>
        <tr>
          <th>No</th>
          <th>Nama Organisasi</th>
          <th>Tanggal Masuk</th>
          <th>Tanggal Keluar</th>
          <th>Jabatan</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
      </tbody>
    </table>
  </div>
</div>

<!-- view -->
<div id="modal_org_view" class="modal fade" role="dialog">
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
              <label class="col-md-6 control-label">Nama Organisasi</label>
              <div class="col-md-6" id="nama_org"></div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Tanggal Masuk</label>
              <div class="col-md-6" id="tanggal_masuk_org"></div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Tangga Keluar</label>
              <div class="col-md-6" id="tanggal_keluar_org"></div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Jabatan</label>
              <div class="col-md-6" id="jabatan"></div>
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
        <button type="submit" class="btn btn-info" onclick="edit_modal_org()"><i class="fa fa-edit"></i> Edit</button> 
        <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
      </div>
    </div>
  </div>
</div>

<!-- edit -->
<div class="modal modal-default fade" id="modal_org_edit">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span></button>
        <h2 class="modal-title">Edit Data <b class="text-muted header_data"></b></h2>
      </div>
      <form id="form_org_edit">
        <div class="modal-body"> 
          <input type="hidden" name="idorg">
          <input type="hidden" name="nik" value="<?php echo $profile['nik'];?>">
          <div class="form-group clearfix">
            <label class="col-sm-3 control-label">Nama Organisasi</label>
            <div class="col-sm-9">
            <input type="text" class="form-control" name="nama_organisasi" placeholder="Nama Organisasi" required>
            </div>
          </div>
          <div class="form-group clearfix">
            <label for="nama" class="col-sm-3 control-label">Tanggal Masuk</label>
            <div class="col-sm-9">
              <div class="has-feedback">
                <span class="fa fa-calendar form-control-feedback"></span>
              <input type="text" name="tahun_masuk" class="form-control pull-right date-picker" placeholder="Tanggal Masuk" readonly="readonly">
              </div>
            </div>
          </div>
          <div class="form-group clearfix">
            <label for="nama" class="col-sm-3 control-label">Tanggal Keluar</label>
            <div class="col-sm-9">
              <div class="has-feedback">
                <span class="fa fa-calendar form-control-feedback"></span>
                <input type="text" name="tahun_keluar" class="form-control pull-right date-picker" placeholder="Tanggal Masuk" readonly="readonly">
            </div>
            </div>
          </div>
          <div class="form-group clearfix">
            <label class="col-sm-3 control-label">Jabatan</label>
            <div class="col-sm-9">
            <input type="text" class="form-control" name="jabatan_org" placeholder="Jabatan">
            </div>
          </div>
        </div>
      </form>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" onclick="do_edit_org()"><i class="fa fa-floppy-o"></i> Simpan</button>
        <button type="button" class="btn btn-default pull-right" data-dismiss="modal"> Kembali</button>
      </div>
    </div>
  </div>
</div>

<!-- delete -->
<div id="modal_org_delete" class="modal fade" role="dialog">
  <div class="modal-dialog modal-sm modal-danger">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title text-center">Konfirmasi Hapus</h4>
      </div>
      <form id="form_delete_org">
        <div class="modal-body text-center">
          <input type="hidden" id="data_column_delete" name="column">
          <input type="hidden" id="data_id_delete" name="id">
          <input type="hidden" id="data_table_delete" name="table">
          <p>Apakah anda yakin akan menghapus data dengan nama <b id="data_name_delete" class="header_data"></b> ?</p>
        </div>
      </form>
      <div class="modal-footer">
        <button type="button" onclick="do_delete_org()" class="btn btn-primary"><i class="fa fa-trash"></i> Hapus</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
      </div>
    </div>
  </div>
</div>
<script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
<script type="text/javascript">
  $(document).ready(function(){
    form_property();all_property();//set_interval();
  })
  function data_organisasi(){
    $('#table_data_org').DataTable().destroy();
    $('#table_data_org').DataTable( {
      ajax: {
        url: "<?php echo base_url().'kemp/emp_org/view_all/'.$profile['nik']; ?>",
        type: 'POST',
        data:{}
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
      {   targets: 5, 
        width: '10%',
        render: function ( data, type, full, meta ) {
          return '<center>'+data+'</center>';
        }
      },
      ]
    });
  }
  function add_modal_org() {
    $('#add_org').modal('toggle');
    $('#add_org .header_data').html('Tambah Data Organisasi');
  }
  function do_add_org() {
    submitAjax("<?php echo base_url('kemp/add_org')?>",'add_org','form_org_add',null,null);
    $('#table_data_org').DataTable().ajax.reload(function (){
      Pace.restart();
      $('#form_org_add')[0].reset();
    });
  }
  function view_org(id) {
    var data={id_k_organisasi:id};
    var callback=getAjaxData("<?php echo base_url().'kemp/emp_org/view_one/'.$profile['nik']; ?>",data);  
    $('#modal_org_view').modal('toggle');
    $('#modal_org_view input[name="data_id_view"]').val(callback['id']);
    $('#modal_org_view .header_data').html(callback['nama_organisasi']);
    $('#modal_org_view #nama_org').html(callback['nama_organisasi']);
    $('#modal_org_view #tanggal_masuk_org').html(callback['getvtahun_masuk']);
    $('#modal_org_view #tanggal_keluar_org').html(callback['getvtahun_keluar']);
    $('#modal_org_view #jabatan').html(callback['jabatan_org']);
    var status = callback['status'];
    if(status==1){
      var statusval = '<b class="text-success">Aktif</b>';
    }else{
      var statusval = '<b class="text-danger">Tidak Aktif</b>';
    }
    $('#modal_org_view #data_status_view').html(statusval);
    $('#modal_org_view #data_create_date_view').html(callback['create_date']+' WIB');
    $('#modal_org_view #data_update_date_view').html(callback['update_date']+' WIB');
    $('#modal_org_view #data_create_by_view').html(callback['nama_buat']);
    $('#modal_org_view #data_update_by_view').html(callback['nama_update']);
  }
  function edit_modal_org() {
    var id = $('#modal_org_view input[name="data_id_view"]').val();
    var data={id_k_organisasi:id};
    var callback=getAjaxData("<?php echo base_url().'kemp/emp_org/view_one/'.$profile['nik']; ?>",data); 
    $('#modal_org_view').modal('toggle');
    setTimeout(function () {
     $('#modal_org_edit').modal('show');
   },600); 
    $('#modal_org_edit .header_data').html(callback['nama_organisasi']);
    $('#modal_org_edit input[name="idorg"]').val(callback['id']);
    $('#modal_org_edit input[name="nik"]').val(callback['nik']);
    $('#modal_org_edit input[name="nama_organisasi"]').val(callback['nama_organisasi']);
    $('#modal_org_edit input[name="tahun_masuk"]').val(callback['gettahun_masuk']);
    $('#modal_org_edit input[name="tahun_keluar"]').val(callback['gettahun_keluar']);
    $('#modal_org_edit input[name="jabatan_org"]').val(callback['jabatan_org']);
  }
  function do_edit_org() {
    submitAjax("<?php echo base_url('kemp/edit_org')?>",'modal_org_edit','form_org_edit',null,null);
    $('#table_data_org').DataTable().ajax.reload(function (){
      Pace.restart();
    });
  }
  function delete_org(id) {
    var data={id_k_organisasi:id};
    $('#modal_org_delete').modal('toggle');
    var callback=getAjaxData("<?php echo base_url().'kemp/emp_org/view_one/'.$profile['nik']; ?>",data);
    $('#modal_org_delete #data_id_delete').val(callback['id']);
    $('#modal_org_delete .header_data').html(callback['nama_organisasi']);
    $('#modal_org_delete #data_column_delete').val('id_k_organisasi');
    $('#modal_org_delete #data_table_delete').val('karyawan_organisasi');
  }
  function do_delete_org(){
    submitAjax("<?php echo base_url('global_control/delete')?>",'modal_org_delete','form_delete_org',null,null);
    $('#table_data_org').DataTable().ajax.reload(function (){
      Pace.restart();
    });
  }
</script>