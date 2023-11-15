<div class="content-wrapper">
  <section class="content-header">
    <h1>
      <i class="fa fa-gears"></i> Setting Aplikasi
      <small>Setting Bobot Sikap</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
      <li class="active">Setting Bobot Sikap</li>
    </ol>
  </section>
  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box box-danger">
          <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-balance-scale"></i> Bobot Sikap</h3>
            <div class="box-tools pull-right">
              <button class="btn btn-box-tool" onclick="reload_table('table_data')" data-toggle="tooltip" title="Refresh Table"><i class="fas fa-sync"></i></button>
              <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
              <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i class="fa fa-times"></i></button>
            </div>
          </div>
          <div class="box-body">
            <table id="table_data" class="table table-bordered table-striped table-responsive" width="100%">
              <thead>
                <tr>
                  <th>No.</th>
                  <th>Nama</th>
                  <th>Atasan</th>
                  <th>Bawahan</th>
                  <th>Rekan</th>
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
              <label class="col-md-6 control-label">Nama Kategori</label>
              <div class="col-md-6" id="data_name_view"></div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Bobot Atasan</label>
              <div class="col-md-6" id="data_atasan_view"></div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Bobot Bawahan</label>
              <div class="col-md-6" id="data_bawahan_view"></div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Bobot Rekan</label>
              <div class="col-md-6" id="data_rekan_view"></div>
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
          <div class="form-group">
            <label>Nama Kategori</label>
            <input type="text" placeholder="Masukkan Nama Lokasi" id="data_name_edit" name="nama" value="" class="form-control" required="required">
          </div>
          <div class="form-group card-trans" style="padding: 10px;margin-right: 0px;">
            <label>Bobot Penilaian</label>
            <label class="text-danger"> Jumlah Bobot Harus 100%</label>
            <div class="row">
              <div class="col-md-12">
                <label>Bobot Atasan</label>
                <div class="has-feedback">
                  <input type="number" max="100" min="0" id="bobot_edit[]" placeholder="Bobot Atasan" name="bobot_atasan" class="form-control bobot_edit">
                  <span class="form-control-feedback">%</span>
                </div>
              </div>
              <div class="col-md-12">
                <label>Bobot Bawahan</label>
                <div class="has-feedback">
                  <input type="number" max="100" min="0" id="bobot_edit[]" placeholder="Bobot Bawahan" name="bobot_bawahan" class="form-control bobot_edit">
                  <span class="form-control-feedback">%</span>
                </div>
              </div>
              <div class="col-md-12">
                <label>Bobot Rekan</label>
                <div class="has-feedback">
                  <input type="number" max="100" min="0" id="bobot_edit[]" placeholder="Bobot Rekan" name="bobot_rekan" class="form-control bobot_edit">
                  <span class="form-control-feedback">%</span>
                </div>
              </div>
              <div class="col-md-12">
                <label>Bobot Total</label>
                <div class="has-feedback">
                  <input type="number" class="form-control" id="data_hasilbobot_edit" required="required" readonly="readonly">
                  <span class="form-control-feedback">%</span>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" onclick="do_edit()" id="btn_edit"  class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
        </div>
      </form>
    </div>

  </div>
</div>

<script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
<script type="text/javascript">
  $(document).ready(function(){
    
    $('#table_data').DataTable( {
      ajax: {
        url: "<?php echo base_url('master/master_bobot_sikap/view_all/')?>",
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
        width: '10%',
        render: function ( data, type, full, meta ) {
          return data;
        }
      },
      {   targets: 2,
        width: '10%',
        render: function ( data, type, full, meta ) {
          return '<center>'+data+'</center>';
        }
      },
      {   targets: 3,
        width: '10%',
        render: function ( data, type, full, meta ) {
          return '<center>'+data+'</center>';
        }
      },
      {   targets: 4,
        width: '10%',
        render: function ( data, type, full, meta ) {
          return '<center>'+data+'</center>';
        }
      },
      {   targets: 5,
        width: '10%',
        render: function ( data, type, full, meta ) {
          return '<center>'+data+'</center>';
        }
      },
      //aksi
      {   targets: 6, 
        width: '7%',
        render: function ( data, type, full, meta ) {
          return '<center>'+data+'</center>';
        }
      },
      ]
    });
    $('.bobot_edit').keyup(function(){
      var users = $('input[id="bobot_edit[]"]').map(function(){ 
        return this.value; 
      }).get()
      getAjaxCount(null,users,"data_hasilbobot_edit");
    })
  });
  function view_modal(id) {
    var data={id_bobot:id};
    var callback=getAjaxData("<?php echo base_url('master/master_bobot_sikap/view_one')?>",data);  
    $('#view').modal('show');
    $('.header_data').html(callback['nama']);
    $('#data_name_view').html(callback['nama']);
    $('#data_atasan_view').html(callback['atasan']);
    $('#data_bawahan_view').html(callback['bawahan']);
    $('#data_rekan_view').html(callback['rekan']);
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
    var data={id_bobot:id};
    var callback=getAjaxData("<?php echo base_url('master/master_bobot_sikap/view_one')?>",data); 
    $('#view').modal('toggle');
    setTimeout(function () {
     $('#edit').modal('show');
   },500); 
    $('.header_data').html(callback['nama']);
    $('#data_id_edit').val(callback['id']);
    $('#data_name_edit').val(callback['nama']);
    $('input[name="bobot_atasan"]').val(callback['val_atasan']);
    $('input[name="bobot_bawahan"]').val(callback['val_bawahan']);
    $('input[name="bobot_rekan"]').val(callback['val_rekan']);
    $('#data_hasilbobot_edit').val(callback['sumbobot']);
  }
  //doing db transaction
  function do_edit(){
    if($("#form_edit")[0].checkValidity()) {
      if($('#data_hasilbobot_edit').val()!=100){
        notValidParamx();
      }else{
        submitAjax("<?php echo base_url('master/edt_bobot_sikap')?>",'edit','form_edit',null,null);
        $('#table_data').DataTable().ajax.reload(function (){
          Pace.restart();
        });
      }
    }else{
      notValidParamx();
    } 
  }
</script>