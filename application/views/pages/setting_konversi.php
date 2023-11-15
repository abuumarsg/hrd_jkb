<div class="content-wrapper">
  <section class="content-header">
    <h1>
      <i class="fa fa-gears"></i> Setting Aplikasi
      <small>Setting Konversi Nilai</small> 
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
      <li class="active">Setting Konversi Nilai</li>
    </ol>
  </section>
  <section class="content">
    <div class="row">
      <div class="col-md-12"> 
        <div class="box box-success">
          <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-filter"></i> Master Konversi Nilai</h3>
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
                      <?php if (in_array($access['l_ac']['add'], $access['access'])) {
                        echo '<button class="btn btn-success btn-flat" type="button" data-toggle="collapse" data-target="#add"><i class="fa fa-plus"></i> Tambah Konversi</button>';
                      }?>
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
                    <div class="col-md-4"></div>
                    <div class="col-md-4">
                      <form id="form_add">
                        <p class="text-danger">Semua data harus diisi!</p>
                        <div class="form-group">
                          <label>Nama Kategori</label>
                          <input type="text" placeholder="Masukkan Nama Kategori" name="nama" class="form-control" required="required" >
                          <label>Batas Awal</label>
                          <input type="number" max="100" min="0" step="0.001" placeholder="Masukkan Batas Awal" name="awal" class="form-control" required="required" >
                          <label>Batas Akhir</label>
                          <input type="number" max="100" min="0" step="0.001" placeholder="Masukkan Batas Akhir" name="akhir" class="form-control" required="required" >
                          <label>Huruf</label>
                          <input type="text" placeholder="Masukkan Huruf" name="huruf" class="form-control" required="required" >
                          <label>Warna</label>
                          <input type="text" placeholder="Pilih Warna" name="warna" class="form-control color-picker">
                          <br>
                          <div class="form-group">
                            <button type="button" onclick="do_add()" id="btn_add" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>
                <?php } ?>
              </div>
            </div>
            <br>
            <div class="row">
              <div class="col-md-12">
                <table id="table_data" class="table table-bordered table-striped table-responsive" width="100%">
                  <thead>
                    <tr>
                      <th>No.</th>
                      <th>Kategori</th>
                      <th>Batas Awal</th>
                      <th>Batas Akhir</th>
                      <th>Huruf</th>
                      <th>Warna</th>
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
              <label class="col-md-6 control-label">Nama Kategori</label>
              <div class="col-md-6" id="data_name_view"></div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Batas Awal</label>
              <div class="col-md-6" id="data_awal_view"></div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Batas Akhir</label>
              <div class="col-md-6" id="data_akhir_view"></div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Huruf</label>
              <div class="col-md-6" id="data_huruf_view"></div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Warna</label>
              <div class="col-md-6" id="data_warna_view"></div>
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
            <label>Nama Katogori</label>
            <input type="text" placeholder="Masukkan Nama Kategori" id="data_name_edit" name="nama" value="" class="form-control" required="required">
          </div>
          <div class="form-group">
            <label>Batas Awal</label>
            <input type="number" min="0" max="100" step="0.001" placeholder="Masukkan Batas Awal" id="data_awal_edit" name="awal" value="" class="form-control" required="required">
          </div>
          <div class="form-group">
            <label>Batas Akhir</label>
            <input type="number" min="0" max="100" step="0.001" placeholder="Masukkan Batas Akhir" id="data_akhir_edit" name="akhir" value="" class="form-control" required="required">
          </div>
          <div class="form-group">
            <label>Huruf</label>
            <input type="text" placeholder="Masukkan Huruf" id="data_huruf_edit" name="huruf" value="" class="form-control" required="required">
          </div>
          <div class="form-group">
            <label>Warna</label>
            <input type="text" placeholder="Pilih Warna" id="data_warna_edit" name="warna" value="" class="form-control color-picker" required="required">
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
<script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
<script type="text/javascript">
  //wajib diisi
  var table="master_konversi_nilai";
  var column="id_konversi";
  $(document).ready(function(){ 
    
    
    $('#table_data').DataTable( {
      ajax: {
        url: "<?php echo base_url('master/master_konversi_nilai/view_all/')?>",
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
        width: '15%',
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
          return '<center>'+data+'</center>';
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
        width: '7%',
        render: function ( data, type, full, meta ) {
          return '<center>'+data+'</center>';
        }
      },
      ]
    });
  });
  function view_modal(id) {
    var data={id_konversi:id};
    var callback=getAjaxData("<?php echo base_url('master/master_konversi_nilai/view_one')?>",data);  
    $('#view').modal('show');
    $('.header_data').html(callback['nama']);
    $('#data_name_view').html(callback['nama']);
    $('#data_awal_view').html(callback['awal']);
    $('#data_akhir_view').html(callback['akhir']);
    $('#data_huruf_view').html(callback['huruf']);
    $('#data_warna_view').html(callback['warna']);
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
    var data={id_konversi:id};
    var callback=getAjaxData("<?php echo base_url('master/master_konversi_nilai/view_one')?>",data); 
    $('#view').modal('toggle');
    setTimeout(function () {
     $('#edit').modal('show');
   },600); 
    $('.header_data').html(callback['nama']);
    $('#data_id_edit').val(callback['id']);
    $('#data_name_edit').val(callback['nama']);
    $('#data_awal_edit').val(callback['awal']);
    $('#data_akhir_edit').val(callback['akhir']);
    $('#data_huruf_edit').val(callback['huruf']);
    $('#data_warna_edit').val(callback['warna_val']);
  }
  function delete_modal(id) {
    var data={id_konversi:id};
    var callback=getAjaxData("<?php echo base_url('master/master_konversi_nilai/view_one')?>",data);
    var datax={table:table,column:column,id:id,nama:callback['nama']};
    loadModalAjax("<?php echo base_url('pages/load_modal_delete')?>",'modal_delete_partial',datax,'delete');
  }
  //doing db transaction
  function do_status(id,data) {
    var data_table={status:data};
    var where={id_konversi:id};
    var datax={table:table,where:where,data:data_table};
    submitAjax("<?php echo base_url('global_control/change_status')?>",null,datax,null,null,'status');
    $('#table_data').DataTable().ajax.reload(function (){
      Pace.restart();
    });
  }
  function do_edit(){
    if($("#form_edit")[0].checkValidity()) {
      submitAjax("<?php echo base_url('master/edt_konversi')?>",'edit','form_edit',null,null);
      $('#table_data').DataTable().ajax.reload(function (){
        Pace.restart();
      });
    }else{
      notValidParamx();
    } 
  }
  function do_add(){
    if($("#form_add")[0].checkValidity()) {
      submitAjax("<?php echo base_url('master/add_konversi')?>",null,'form_add',null,null);
      $('#table_data').DataTable().ajax.reload(function (){
        Pace.restart();
      });
      $('#form_add')[0].reset();

    }else{
      notValidParamx();
    } 
  }
</script>