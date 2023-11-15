<meta http-equiv="cache-control" content="no-cache" />
<meta http-equiv="pragma" content="no-cache" />
<?php 
if (in_array($access['l_ac']['add'], $access['access'])) { ?>
<button type="button" class="btn btn-success btn-sm" onclick="add_modal_anak()">
<i class ="fa fa-plus"></i> Tambah Data Anak
</button>
<div id="add_anak" class="modal modal-default fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span></button>
        <h2 class="modal-title">Tambah Data</h2>
      </div>
      <form id="form_anak_add">
        <div class="modal-body">
          <input type="hidden" name="nik" value="<?php echo $profile['nik'];?>">
          <div class="form-group clearfix">
            <label class="col-sm-3 control-label">Nama Anak</label>
            <div class="col-sm-9">
              <input type="text" class="form-control keyup" name="nama_anak" placeholder="Nama Anak" required>
            </div>
          </div>
          <div class="form-group clearfix">
            <label for="nama" class="col-sm-3 control-label">Tempat Tanggal Lahir</label>
            <div class="col-sm-9">
            <input type="text" class="form-control keyup" name="tempat_lahir_anak" placeholder="Tempat Lahir">
            <div class="has-feedback">
              <span class="fa fa-calendar form-control-feedback"></span>
            <input type="text" name="tanggal_lahir_anak" class="form-control pull-right date-picker" placeholder="Tanggal Lahir Anak" readonly="readonly">
            </div>
            </div>
          </div>
          <div class="form-group clearfix">
            <label for="nama" class="col-sm-3 control-label">Jenis Kelamin</label>
            <div class="col-sm-9">
              <?php
                $sel2 = array(null);
                $ex2 = array('class'=>'form-control select2','style'=>'width:100%;','required'=>'required');
                echo form_dropdown('kelamin_anak',$kelamin,$sel2,$ex2);
              ?>
            </div>
          </div>
          <div class="form-group clearfix">
            <label for="nama" class="col-sm-3 control-label">Pendidikan Terakhir</label>
            <div class="col-sm-9">
                <?php
                  $pendidikan[null] = 'Pilih Data';
                  $sel3 = array(null);
                  $ex3 = array('class'=>'form-control select2','style'=>'width:100%;');
                  echo form_dropdown('pendidikan_anak',$pendidikan,$sel3,$ex3);
                ?>
            </div>
          </div>
          <div class="form-group clearfix">
            <label class="col-sm-3 control-label">No HP</label>
            <div class="col-sm-9">
            <input type="text" class="form-control" name="no_telp" placeholder="Nomor Handphone">
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" onclick="add_anak()"><i class="fa fa-floppy-o"></i> Simpan</button>
          <button type="button" class="btn btn-default pull-right" data-dismiss="modal"> Kembali</button>
        </div>
      </form>
    </div>
  </div>
</div>
<?php } ?>
<br>
<br>
<div class="row">
  <div class="col-md-12">
    <table id="table_data_anak" class="table table-bordered table-striped table-responsive" width="100%">
      <thead>
        <tr>
          <th>No</th>
          <th>Nama</th>
          <th>TTL</th>
          <th>L/P</th>
          <th>Pendidikan</th>
          <th>No.HP</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
      </tbody>
    </table>
  </div>
</div>

<!-- view -->
<div id="view_anak" class="modal fade" role="dialog">
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
              <label class="col-md-6 control-label">Nama Anak</label>
              <div class="col-md-6" id="data_nama_view"></div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Jenis Kelamin</label>
              <div class="col-md-6" id="data_jk_view"></div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Tempat, Tanggal Lahir</label>
              <div class="col-md-6" id="data_ttl_view"></div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Pendidikan</label>
              <div class="col-md-6" id="data_ptnd_view"></div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">No. telp</label>
              <div class="col-md-6" id="data_telp_view"></div>
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
          echo '<button type="submit" class="btn btn-info" onclick="edit_modal_anak()"><i class="fa fa-edit"></i> Edit</button>';
        }?>
        <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
      </div>
    </div>
  </div>
</div>

<!-- edit -->
<div class="modal modal-default fade" id="edit_anak">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h2 class="modal-title">Edit Data <b class="text-muted header_data"></b></h2>
      </div>
      <form id="form_edit_anak">
        <div class="modal-body">
          <input type="hidden" name="id_anak" value="">
          <input type="hidden" name="nik" value="">
          <div class="form-group clearfix">
            <label class="col-sm-3 control-label">Nama Anak</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" name="nama_anak" placeholder="Nama Anak" value="" required>
            </div>
          </div>
          <div class="form-group clearfix">
            <label for="nama" class="col-sm-3 control-label">Tempat Tanggal Lahir</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" name="tempat_lahir_anak" placeholder="Tempat Lahir" value="">
              <div class="has-feedback">
                <span class="fa fa-calendar form-control-feedback"></span>
                <input type="text" name="tanggal_lahir_anak" class="form-control pull-right date-picker" placeholder="Tanggal Lahir Anak" value="" readonly="readonly">
              </div>
            </div>
          </div>
          <div class="form-group clearfix">
            <label for="nama" class="col-sm-3 control-label">Jenis Kelamin</label>
            <div class="col-sm-9" align="left">
              <?php
                $sel4 = array(null);
                $ex4 = array('class'=>'form-control select2','style'=>'width:100%;','required'=>'required');
                echo form_dropdown('kelamin_anak',$kelamin,$sel4,$ex4);
              ?>
            </div>
          </div>
          <div class="form-group clearfix">
            <label for="nama" class="col-sm-3 control-label">Pendidikan Terakhir</label>
            <div class="col-sm-9" align="left">
              <?php
                $sel5 = array(null);
                $ex5 = array('class'=>'form-control select2','style'=>'width:100%;','required'=>'required');
                echo form_dropdown('pendidikan_anak',$pendidikan,$sel5,$ex5);
              ?>
            </div>
          </div>
          <div class="form-group clearfix">
            <label class="col-sm-3 control-label">No HP</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" name="no_telp" placeholder="No. HP" value="">
            </div>
          </div>
        </div>
      </form>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" onclick="edit_anak()"><i class="fa fa-floppy-o"></i> Simpan</button>
        <button type="button" class="btn btn-default pull-right" data-dismiss="modal"> Kembali</button>
      </div>
    </div>
  </div>
</div>

<!-- delete -->
<div id="delete_anak" class="modal fade" role="dialog">
  <div class="modal-dialog modal-sm modal-danger">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title text-center">Konfirmasi Hapus</h4>
      </div>
      <form id="form_delete_anak">
        <div class="modal-body text-center">
          <input type="hidden" id="data_column_delete" name="column" value="id_anak">
          <input type="hidden" id="data_id_delete" name="id">
          <input type="hidden" id="data_table_delete" name="table" value="karyawan_anak">
          <p>Apakah anda yakin akan menghapus data dengan nama <b id="data_name_delete" class="header_data"></b> ?</p>
        </div>
      </form>
      <div class="modal-footer">
        <button type="button" onclick="do_delete_anak()" class="btn btn-primary"><i class="fa fa-trash"></i> Hapus</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
      </div>
    </div>
  </div>
</div>
<script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
<script type="text/javascript">
  $(document).ready(function(){
    form_property();all_property();set_interval();reset_interval();
  })
  function data_anak(){
    $('#table_data_anak').DataTable().destroy();
    $('#table_data_anak').DataTable( {
      ajax: {
        url: "<?php echo base_url().'employee/emp_anak/view_all/'.$profile['nik'];'/' ?>",
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
        width: '15%',
        render: function ( data, type, full, meta ) {
          return '<center>'+data+'</center>';
        }
      },
      //aksi
      {   targets: 6, 
        width: '10%',
        render: function ( data, type, full, meta ) {
          return '<center>'+data+'</center>';
        }
      },
      ]
    });
  }
  function add_modal_anak() {
    $('#add_anak').modal('toggle');
    $('#add_anak .header_data').html('Tambah Data Anak');
  }
  function view_modal_anak(id) {
    var data={id_anak:id};
    var callback=getAjaxData("<?php echo base_url().'employee/emp_anak/view_one/'.$profile['nik']; ?>",data);  
    $('#view_anak').modal('toggle');
    $('#view_anak input[name="data_id_view"]').val(callback['id']);
    $('#view_anak .header_data').html(callback['nama_anak']);
    $('#view_anak #data_nama_view').html(callback['nama_anak']);
    $('#view_anak #data_jk_view').html(callback['getkelamin_anak']);
    $('#view_anak #data_ttl_view').html(callback['getTTL']);
    $('#view_anak #data_ptnd_view').html(callback['getPendidikan']);
    $('#view_anak #data_telp_view').html(callback['no_telp']);
    var status = callback['status'];
    if(status==1){
      var statusval = '<b class="text-success">Aktif</b>';
    }else{
      var statusval = '<b class="text-danger">Tidak Aktif</b>';
    }
    $('#view_anak #data_status_view').html(statusval);
    $('#view_anak #data_create_date_view').html(callback['create_date']+' WIB');
    $('#view_anak #data_update_date_view').html(callback['update_date']+' WIB');
    $('#view_anak #data_create_by_view').html(callback['nama_buat']);
    $('#view_anak #data_update_by_view').html(callback['nama_update']);
  }
  function edit_modal_anak() {
    var id = $('#view_anak input[name="data_id_view"]').val();
    var data={id_anak:id};
    var callback=getAjaxData("<?php echo base_url().'employee/emp_anak/view_one/'.$profile['nik']; ?>",data); 
    $('#view_anak').modal('toggle');
    setTimeout(function () {
     $('#edit_anak').modal('show');
   },600); 
    $('#edit_anak .header_data').html(callback['nama_anak']);
    $('#edit_anak input[name="id_anak"]').val(callback['id']);
    $('#edit_anak input[name="nik"]').val(callback['nik']);
    $('#edit_anak input[name="nama_anak"]').val(callback['nama_anak']);
    $('#edit_anak input[name="tempat_lahir_anak"]').val(callback['tempat_lahir_anak']);
    $('#edit_anak input[name="tanggal_lahir_anak"]').val(callback['tanggal_lahir_anak']);
    $('#edit_anak input[name="no_telp"]').val(callback['no_telp']);
    $('#edit_anak select[name="kelamin_anak"]').val(callback['kelamin_anak']).trigger('change');
    $('#edit_anak select[name="pendidikan_anak"]').val(callback['pendidikan_anak']).trigger('change');
  }
  function edit_anak() {
      submitAjax("<?php echo base_url('employee/edit_anak')?>",'edit_anak','form_edit_anak',null,null);
      $('#table_data_anak').DataTable().ajax.reload(function (){
        Pace.restart();
      });
  }
  function add_anak() {
    submitAjax("<?php echo base_url('employee/add_anak')?>",'add_anak','form_anak_add',null,null);
    var nik = '<?php echo $profile['nik']; ?>';
    var data={nik:nik};
    var callback=getAjaxData("<?php echo base_url('employee/emppribadi')?>",data);
    $('#view_jmlanak').html(callback['jml_anak']);
    $('#table_data_anak').DataTable().ajax.reload(function (){
      Pace.restart();
    });
  }
  function delete_anak(id) {
    var table="karyawan_anak";
    var column="id_anak";
    var data={id_anak:id};
    $('#delete_anak').modal('toggle');
    var callback=getAjaxData("<?php echo base_url().'employee/emp_anak/view_one/'.$profile['nik']; ?>",data);
    $('#delete_anak #data_id_delete').val(callback['id']);
    $('#delete_anak .header_data').html(callback['nama_anak']);
  }
  function do_delete_anak(){
    submitAjax("<?php echo base_url('global_control/delete')?>",'delete_anak','form_delete_anak',null,null);
    $('#table_data_anak').DataTable().ajax.reload(function (){
      Pace.restart();
    });
  }
</script>