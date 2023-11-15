<meta http-equiv="cache-control" content="no-cache" />
<meta http-equiv="pragma" content="no-cache" />
    <?php 
    if (in_array($access['l_ac']['add'], $access['access'])) { 
    ?>
      <button type="button" class="btn btn-success btn-sm" onclick="add_modal_saudara()">
        <i class ="fa fa-plus"></i> Tambah Data Saudara
      </button><br> 
      <div class="modal modal-default fade" id="add_saudara">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
              <h2 class="modal-title">Tambah Data</h2>
            </div>
            <form id="form_saudara_add">
              <div class="modal-body">
                <input type="hidden" name="nik" value="<?php echo $profile['nik'];?>">
                <div class="form-group clearfix">
                  <label class="col-sm-3 control-label">Nama Saudara</label>
                  <div class="col-sm-9">
                  <input type="text" class="form-control" name="nama_saudara" placeholder="Nama Saudara" required>
                  </div>
                </div>
                <div class="form-group clearfix">
                  <label for="nama" class="col-sm-3 control-label">Tempat Tanggal Lahir</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" name="tempat_lahir_saudara" placeholder="Tempat Lahir Saudara">
                    <div class="has-feedback">
                      <span class="fa fa-calendar form-control-feedback"></span>
                    <input type="text" name="tanggal_lahir_saudara" class="form-control pull-right date-picker" placeholder="Tanggal Lahir Saudara" readonly="readonly">
                    </div>
                  </div>
                </div>
                <div class="form-group clearfix">
                  <label for="nama" class="col-sm-3 control-label">Jenis Kelamin</label>
                  <div class="col-sm-9">
                    <?php
                      $sel1 = array(null);
                      $ex1 = array('class'=>'form-control select2','style'=>'width:100%;','required'=>'required');
                      echo form_dropdown('jenis_kelamin_saudara',$kelamin,$sel1,$ex1);
                    ?>
                  </div>
                </div>
                <div class="form-group clearfix">
                  <label for="nama" class="col-sm-3 control-label">Pendidikan Terakhir</label>
                  <div class="col-sm-9">
                    <?php
                      $sel2 = array(null);
                      $ex2 = array('class'=>'form-control select2','style'=>'width:100%;','required'=>'required');
                      echo form_dropdown('pendidikan_saudara',$pendidikan,$sel2,$ex2);
                    ?>
                  </div>
                </div>
                <div class="form-group clearfix">
                  <label class="col-sm-3 control-label">No HP</label>
                  <div class="col-sm-9">
                  <input type="text" class="form-control" name="no_telp_saudara" placeholder="Nomor Handphone">
                  </div>
                </div>
              </div>
            </form>
            <div class="modal-footer">
               <button type="button" class="btn btn-primary" onclick="add_saudara()"><i class="fa fa-floppy-o"></i> Simpan</button>
              <!-- <input type="submit" class="btn btn-success" name="submit" value="Simpan Data"> -->
              <button type="button" class="btn btn-default pull-right" data-dismiss="modal"> Kembali</button>
            </div>
          </div>
        </div>
      </div>
<?php } ?>
<br>
  <div class="row">
    <div class="col-md-12">
      <table id="table_data_saudara" class="table table-bordered table-striped table-responsive" width="100%">
        <thead>
          <tr">
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
<div id="view_saudara" class="modal fade" role="dialog">
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
              <label class="col-md-6 control-label">Nama saudara</label>
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
          echo '<button type="submit" class="btn btn-info" onclick="edit_modal_saudara()"><i class="fa fa-edit"></i> Edit</button>';
        }?>
        <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
      </div>
    </div>
  </div>
</div>

<!-- edit -->
<div class="modal modal-default fade" id="edit_saudara">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h2 class="modal-title">Edit Data <b class="text-muted header_data"></b></h2>
      </div>
      <form id="form_edit_saudara">
        <div class="modal-body">
          <input type="hidden" name="id_saudara" value="">
          <input type="hidden" name="nik" value="">
          <div class="form-group clearfix">
            <label class="col-sm-3 control-label">Nama saudara</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" name="nama_saudara" placeholder="Nama saudara" value="" required>
            </div>
          </div>
          <div class="form-group clearfix">
            <label for="nama" class="col-sm-3 control-label">Tempat Tanggal Lahir</label>
            <div class="col-sm-9">
              <input type="text" class="form-control" name="tempat_lahir_saudara" placeholder="Tempat Lahir" value="">
              <div class="has-feedback">
                <span class="fa fa-calendar form-control-feedback"></span>
                <input type="text" name="tanggal_lahir_saudara" class="form-control pull-right date-picker" placeholder="Tanggal Lahir saudara" value="" readonly="readonly">
              </div>
            </div>
          </div>
          <div class="form-group clearfix">
            <label for="nama" class="col-sm-3 control-label">Jenis Kelamin</label>
            <div class="col-sm-9" align="left">
              <?php
                $sel3 = array(null);
                $ex3 = array('class'=>'form-control select2','style'=>'width:100%;','required'=>'required','id'=>'kelamin_saudara_edit');
                echo form_dropdown('kelamin_saudara',$kelamin,$sel3,$ex3);
              ?>
            </div>
          </div>
          <div class="form-group clearfix">
            <label for="nama" class="col-sm-3 control-label">Pendidikan Terakhir</label>
            <div class="col-sm-9" align="left">
              <?php
                $sel4 = array(null);
                $ex4 = array('class'=>'form-control select2','style'=>'width:100%;','required'=>'required','id'=>'pendidikan_saudara_edit');
                echo form_dropdown('pendidikan_saudara',$pendidikan,$sel4,$ex4);
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
        <button type="button" class="btn btn-success" onclick="edit_saudara()"><i class="fa fa-floppy-o"></i> Simpan</button>
        <button type="button" class="btn btn-default pull-right" data-dismiss="modal"> Kembali</button>
      </div>
    </div>
  </div>
</div>

<!-- delete -->
<div id="delete_saudara" class="modal fade" role="dialog">
  <div class="modal-dialog modal-sm modal-danger">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title text-center">Konfirmasi Hapus</h4>
      </div>
      <form id="form_delete_saudara">
        <div class="modal-body text-center">
          <input type="hidden" id="data_column_delete" name="column" value="id_saudara">
          <input type="hidden" id="data_id_delete" name="id">
          <input type="hidden" id="data_table_delete" name="table" value="karyawan_saudara">
          <p>Apakah anda yakin akan menghapus data dengan nama <b id="data_name_delete" class="header_data"></b> ?</p>
        </div>
      </form>
      <div class="modal-footer">
        <button type="button" onclick="do_delete_saudara()" class="btn btn-primary"><i class="fa fa-trash"></i> Hapus</button>
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
  function data_saudara(){
    $('#table_data_saudara').DataTable().destroy();
    $('#table_data_saudara').DataTable( {
      ajax: {
        url: "<?php echo base_url().'employee/empsaudara/view_all/'.$profile['nik'];'/'?>",
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
  function add_modal_saudara() {
    $('#add_saudara').modal('toggle');
    $('#add_saudara .header_data').html('Tambah Data Saudara');
  }
  function view_modal_saudara(id) {
    var data={id_saudara:id};
    var callback=getAjaxData("<?php echo base_url().'employee/empsaudara/view_one/'.$profile['nik']; ?>",data);  
    $('#view_saudara').modal('toggle');
    $('#view_saudara input[name="data_id_view"]').val(callback['id']);
    $('#view_saudara .header_data').html(callback['nama_saudara']);
    $('#view_saudara #data_nama_view').html(callback['nama_saudara']);
    $('#view_saudara #data_jk_view').html(callback['getkelamin_saudara']);
    $('#view_saudara #data_ttl_view').html(callback['getTTL']);
    $('#view_saudara #data_ptnd_view').html(callback['getPendidikan']);
    $('#view_saudara #data_telp_view').html(callback['no_telp_saudara']);
    var status = callback['status'];
    if(status==1){
      var statusval = '<b class="text-success">Aktif</b>';
    }else{
      var statusval = '<b class="text-danger">Tidak Aktif</b>';
    }
    $('#view_saudara #data_status_view').html(statusval);
    $('#view_saudara #data_create_date_view').html(callback['create_date']+' WIB');
    $('#view_saudara #data_update_date_view').html(callback['update_date']+' WIB');
    $('#view_saudara #data_create_by_view').html(callback['nama_buat']);
    $('#view_saudara #data_update_by_view').html(callback['nama_update']);
  }
  function edit_modal_saudara() {
    var id = $('#view_saudara input[name="data_id_view"]').val();
    var data={id_saudara:id};
    var callback=getAjaxData("<?php echo base_url().'employee/empsaudara/view_one/'.$profile['nik']; ?>",data); 
    $('#view_saudara').modal('toggle');
    setTimeout(function () {
     $('#edit_saudara').modal('show');
   },600); 
    $('#edit_saudara .header_data').html(callback['nama_saudara']);
    $('#edit_saudara input[name="id_saudara"]').val(callback['id']);
    $('#edit_saudara input[name="nik"]').val(callback['nik']);
    $('#edit_saudara input[name="nama_saudara"]').val(callback['nama_saudara']);
    $('#edit_saudara input[name="tempat_lahir_saudara"]').val(callback['tempat_lahir_saudara']);
    $('#edit_saudara input[name="tanggal_lahir_saudara"]').val(callback['tanggal_lahir_saudara']);
    $('#edit_saudara input[name="no_telp"]').val(callback['no_telp_saudara']);
    $('#edit_saudara select[name="kelamin_saudara"]').val(callback['jenis_kelamin_saudara']).trigger('change');
    $('#edit_saudara select[name="pendidikan_saudara"]').val(callback['pendidikan_saudara']).trigger('change');
  }
  function edit_saudara() {
      submitAjax("<?php echo base_url('employee/edit_saudara')?>",'edit_saudara','form_edit_saudara',null,null);
      $('#table_data_saudara').DataTable().ajax.reload(function (){
        Pace.restart();
      });
  }
  function add_saudara() {
    submitAjax("<?php echo base_url('employee/add_saudara')?>",'add_saudara','form_saudara_add',null,null);
    $('#table_data_saudara').DataTable().ajax.reload(function (){
      Pace.restart();
    });
  }
  function delete_saudara(id) {
    var table="karyawan_saudara";
    var column="id_saudara";
    var data={id_saudara:id};
    $('#delete_saudara').modal('toggle');
    var callback=getAjaxData("<?php echo base_url().'employee/empsaudara/view_one/'.$profile['nik']; ?>",data);
    $('#delete_saudara #data_id_delete').val(callback['id']);
    $('#delete_saudara .header_data').html(callback['nama_saudara']);
  }
  function do_delete_saudara(){
    submitAjax("<?php echo base_url('global_control/delete')?>",'delete_saudara','form_delete_saudara',null,null);
    $('#table_data_saudara').DataTable().ajax.reload(function (){
      Pace.restart();
    });
  }
</script>