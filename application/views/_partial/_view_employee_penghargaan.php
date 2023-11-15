<meta http-equiv="cache-control" content="no-cache" />
<meta http-equiv="pragma" content="no-cache" />
 <?php if (in_array($access['l_ac']['add'], $access['access'])) { ?>
<button type="button" class="btn btn-success btn-sm" onclick="add_modal_hrg()">
  <i class ="fa fa-plus"></i> Tambah Penghargaan
</button>
<div class="modal modal-default fade" id="add_hrg">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span></button>
        <h2 class="modal-title">Tambah Data</h2>
      </div>
      <form id="form_hrg_add">
        <div class="modal-body">
          <input type="hidden" name="nik" value="<?php echo $profile['nik'];?>">
          <div class="form-group clearfix">
            <label class="col-sm-3 control-label">Nama Penghargaan</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" name="nama_penghargaan" placeholder="Nama Penghargaan" required>
            </div>
          </div>
          <div class="form-group clearfix">
            <label class="col-sm-3 control-label">Tanggal</label>
            <div class="col-sm-9">
              <div class="has-feedback">
                <span class="fa fa-calendar form-control-feedback"></span>
                <input type="text" name="tanggal" class="form-control pull-right date-picker" placeholder="Tanggal" readonly="readonly">
              </div>
            </div>
          </div>
          <div class="form-group clearfix">
            <label class="col-sm-3 control-label">Peringkat</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" name="peringkat" placeholder="Peringkat">
            </div>
          </div>
          <div class="form-group clearfix">
            <label class="col-sm-3 control-label">Yang Menetapkan</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" name="yg_menetapkan" placeholder="Yang Menetapkan">
            </div>
          </div>
          <div class="form-group clearfix">
            <label class="col-sm-3 control-label">Penyelenggara</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" name="penyelenggara" placeholder="Penyelenggara">
            </div>
          </div>
          <div class="form-group clearfix">
            <label class="col-sm-3 control-label">Keterangan</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" name="keterangan" placeholder="Keterangan">
            </div>
          </div>
        </div>
      </form>
      <div class="modal-footer">
          <button type="button" class="btn btn-primary" onclick="do_add_hrg()"><i class="fa fa-floppy-o"></i> Simpan</button>
        <button type="button" class="btn btn-default pull-right" data-dismiss="modal"> Kembali</button>
      </div>
    </div>
  </div>
</div>
<?php } ?>
<br>
<div class="row">
  <div class="col-md-12" style="padding-top: 10px;">
    <table id="table_data_hrg" class="table table-bordered table-striped table-responsive" width="100%">
      <thead>
        <tr>
          <th>No</th>
          <th>Nama Penghargaan</th>
          <th>Tanggal</th>
          <th>Peringkat</th>
          <th>Penyelenggara</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
       
      </tbody>
    </table>
  </div>
</div>

<!-- view -->
<div id="modal_hrg_view" class="modal fade" role="dialog">
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
              <label class="col-md-6 control-label">Nama Penghargaan</label>
              <div class="col-md-6" id="nama_penghargaan"></div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Tanggal</label>
              <div class="col-md-6" id="tanggal"></div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Peringkat</label>
              <div class="col-md-6" id="peringkat"></div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Yang Menetapkan</label>
              <div class="col-md-6" id="yg_menetapkan"></div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Penyelenggara</label>
              <div class="col-md-6" id="penyelenggara"></div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Keterangan</label>
              <div class="col-md-6" id="keterangan"></div>
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
        <?php if (in_array($access['l_ac']['edt'], $access['access'])) {
          echo '<button type="submit" class="btn btn-info" onclick="edit_modal_hrg()"><i class="fa fa-edit"></i> Edit</button>';
        }?>
        <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
      </div>
    </div>
  </div>
</div>

<!-- edit -->
<div class="modal modal-default fade" id="modal_hrg_edit">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span></button>
        <h2 class="modal-title">Edit Data <b class="text-muted header_data"></b></h2>
      </div>
      <form id="form_hrg_edit">
        <div class="modal-body"> 
          <input type="hidden" name="idhrg">
          <input type="hidden" name="nik" value="<?php echo $profile['nik'];?>">
          <div class="form-group clearfix">
            <label class="col-sm-3 control-label">Nama Penghargaan</label>
            <div class="col-sm-9">
            <input type="text" class="form-control" name="nama_penghargaan" placeholder="Nama penghargaan" required>
            </div>
          </div>
          <div class="form-group clearfix">
            <label for="nama" class="col-sm-3 control-label">Tanggal</label>
            <div class="col-sm-9">
              <div class="has-feedback">
                <span class="fa fa-calendar form-control-feedback"></span>
                <input type="text" name="tanggal" class="form-control pull-right date-picker" placeholder="Tanggal" readonly="readonly">
              </div>
            </div>
          </div>
          <div class="form-group clearfix">
            <label class="col-sm-3 control-label">Peringkat</label>
            <div class="col-sm-9">
            <input type="text" class="form-control" name="peringkat" placeholder="Peringkat">
            </div>
          </div>
          <div class="form-group clearfix">
            <label class="col-sm-3 control-label">Yang Menetapkan</label>
            <div class="col-sm-9">
            <input type="text" class="form-control" name="yg_menetapkan" placeholder="Yang Menetapkan">
            </div>
          </div>
          <div class="form-group clearfix">
            <label class="col-sm-3 control-label">Penyelenggara</label>
            <div class="col-sm-9 text-left">
            <input type="text" class="form-control" name="penyelenggara" placeholder="Penyelenggara">
            </div>
          </div>
          <div class="form-group clearfix">
            <label class="col-sm-3 control-label">Keterangan</label>
            <div class="col-sm-9">
            <input type="text" class="form-control" name="keterangan" placeholder="Keterangan">
            </div>
          </div>
        </div>
      </form>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" onclick="do_edit_hrg()"><i class="fa fa-floppy-o"></i> Simpan</button>
        <button type="button" class="btn btn-default pull-right" data-dismiss="modal"> Kembali</button>
      </div>
    </div>
  </div>
</div>

<!-- delete -->
<div id="modal_hrg_delete" class="modal fade" role="dialog">
  <div class="modal-dialog modal-sm modal-danger">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title text-center">Konfirmasi Hapus</h4>
      </div>
      <form id="form_delete_hrg">
        <div class="modal-body text-center">
          <input type="hidden" id="data_column_delete" name="column">
          <input type="hidden" id="data_id_delete" name="id">
          <input type="hidden" id="data_table_delete" name="table">
          <p>Apakah anda yakin akan menghapus data dengan nama <b id="data_name_delete" class="header_data"></b> ?</p>
        </div>
      </form>
      <div class="modal-footer">
        <button type="button" onclick="do_delete_hrg()" class="btn btn-primary"><i class="fa fa-trash"></i> Hapus</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
      </div>
    </div>
  </div>
</div>
<script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
<script type="text/javascript">
  $(document).ready(function(){
   form_property();all_property();
  })
  function data_penghargaan(){
    $('#table_data_hrg').DataTable().destroy();
    $('#table_data_hrg').DataTable( {
      ajax: {
        url: "<?php echo base_url().'employee/emp_hrg/view_all/'.$profile['nik']; ?>",
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
      {   targets: 5, 
        width: '10%',
        render: function ( data, type, full, meta ) {
          return '<center>'+data+'</center>';
        }
      },
      ]
    });
  }
  function add_modal_hrg() {
    $('#add_hrg').modal('toggle');
    $('#add_hrg .header_data').html('Tambah Data Penghargaan');
  }
  function do_add_hrg() {
    submitAjax("<?php echo base_url('employee/add_hrg')?>",'add_hrg','form_hrg_add',null,null);
    $('#table_data_hrg').DataTable().ajax.reload(function (){
      Pace.restart();
      $('#form_hrg_add')[0].reset();
    });
  }
  function view_hrg(id) {
    var data={id_k_penghargaan:id};
    var callback=getAjaxData("<?php echo base_url().'employee/emp_hrg/view_one/'.$profile['nik']; ?>",data);  
    $('#modal_hrg_view').modal('toggle');
    $('#modal_hrg_view input[name="data_id_view"]').val(callback['id']);
    $('#modal_hrg_view .header_data').html(callback['nama_penghargaan']);
    $('#modal_hrg_view #nama_penghargaan').html(callback['nama_penghargaan']);
    $('#modal_hrg_view #tanggal').html(callback['tanggalv']);
    $('#modal_hrg_view #peringkat').html(callback['peringkat']);
    $('#modal_hrg_view #yg_menetapkan').html(callback['yg_menetapkan']);
    $('#modal_hrg_view #penyelenggara').html(callback['penyelenggara']);
    $('#modal_hrg_view #keterangan').html(callback['keterangan']);
    var status = callback['status'];
    if(status==1){
      var statusval = '<b class="text-success">Aktif</b>';
    }else{
      var statusval = '<b class="text-danger">Tidak Aktif</b>';
    }
    $('#modal_hrg_view #data_status_view').html(statusval);
    $('#modal_hrg_view #data_create_date_view').html(callback['create_date']+' WIB');
    $('#modal_hrg_view #data_update_date_view').html(callback['update_date']+' WIB');
    $('#modal_hrg_view #data_create_by_view').html(callback['nama_buat']);
    $('#modal_hrg_view #data_update_by_view').html(callback['nama_update']);
  }
  function edit_modal_hrg() {
    var id = $('#modal_hrg_view input[name="data_id_view"]').val();
    var data={id_k_penghargaan:id};
    var callback=getAjaxData("<?php echo base_url().'employee/emp_hrg/view_one/'.$profile['nik']; ?>",data); 
    $('#modal_hrg_view').modal('toggle');
    setTimeout(function () {
     $('#modal_hrg_edit').modal('show');
   },600); 
    $('#modal_hrg_edit .header_data').html(callback['nama_penghargaan']);
    $('#modal_hrg_edit input[name="idhrg"]').val(callback['id']);
    $('#modal_hrg_edit input[name="nik"]').val(callback['nik']);
    $('#modal_hrg_edit input[name="nama_penghargaan"]').val(callback['nama_penghargaan']);
    $('#modal_hrg_edit input[name="tanggal"]').val(callback['tanggal']);
    $('#modal_hrg_edit input[name="peringkat"]').val(callback['peringkat']);
    $('#modal_hrg_edit input[name="yg_menetapkan"]').val(callback['yg_menetapkan']);
    $('#modal_hrg_edit input[name="penyelenggara"]').val(callback['penyelenggara']);
    $('#modal_hrg_edit input[name="keterangan"]').val(callback['keterangan']);
  }
  function do_edit_hrg() {
    submitAjax("<?php echo base_url('employee/edit_hrg')?>",'modal_hrg_edit','form_hrg_edit',null,null);
    $('#table_data_hrg').DataTable().ajax.reload(function (){
      Pace.restart();
    });
  }
  function delete_hrg(id) {
    var data={id_k_penghargaan:id};
    $('#modal_hrg_delete').modal('toggle');
    var callback=getAjaxData("<?php echo base_url().'employee/emp_hrg/view_one/'.$profile['nik']; ?>",data);
    $('#modal_hrg_delete #data_id_delete').val(callback['id']);
    $('#modal_hrg_delete .header_data').html(callback['nama_penghargaan']);
    $('#modal_hrg_delete #data_column_delete').val('id_k_penghargaan');
    $('#modal_hrg_delete #data_table_delete').val('karyawan_penghargaan');
  }
  function do_delete_hrg(){
    submitAjax("<?php echo base_url('global_control/delete')?>",'modal_hrg_delete','form_delete_hrg',null,null);
    $('#table_data_hrg').DataTable().ajax.reload(function (){
      Pace.restart();
    });
  }
</script>