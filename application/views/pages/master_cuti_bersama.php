  <div class="content-wrapper">
    <section class="content-header">
      <h1>
        <i class="fas fa-wrench"></i> Master Data 
        <small>Master Cuti Bersama</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
        <li class="active"><i class="fas fa-wrench"></i> Master Cuti Bersama</li>
      </ol>
    </section>
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box box-success">
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fas fa-wrench"></i> Daftar Cuti Bersama</h3>
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
                    <div class="col-md-4">
								      <div class="callout callout-info">
                        <label><i class="fa fa-info-circle"></i> Bantuan</label><br>
                          Cuti Karyawan akan di-Reset per <b style="color:green;">01 Januari</b>, Sisa Cuti pada tahun sebelumnya masih bisa digunakan sampai <b style="color:green;">30 Juni</b>, Pastikan anda men-Setting tanggal cuti bersama Sebelum tanggal tersebut <b style="color:green;">01 Januari</b>, Jika anda menambahkan atau merubah data <b>Cuti Bersama</b> setelah tanggal <b style="color:green;">01 Januari</b> maka anda harus me-Sinkron dengan klik tombol dibawah.
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="row form-box-group">
                        <span class="form-box-group-title">Jumlah Cuti Bersama Dalam 1 Tahun</span>
                        <div class="col-md-12">
                          <p id="keterangan_cuti_per_tahun"></p>
                          <span id="keterangan_update"></span>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="row form-box-group">
                        <span class="form-box-group-title">Filter Pencarian</span>
                        <div class="col-md-7">
                          <div class="form-group">
                            <label>Tahun</label>
                            <?php
                              $tahun_ser = $this->formatter->getYear();
                              $sels = array(date('Y'));
                              $exs = array('class'=>'form-control select2', 'id'=>'tahun_filter', 'style'=>'width:100%;','required'=>'required');
                              echo form_dropdown('tahun',$tahun_ser,$sels,$exs);
                            ?>
                          </div>
                        </div>
                        <div class="col-md-5">
                          <div class="form-group">
                            <div class="pull-left" style="padding-top:26px">
                              <button type="button" onclick="searchCutiBersama('search')" class="btn btn-success"><i class="fa fa-eye"></i> Lihat Data</button>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <hr>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="pull-left">
                        <?php if (in_array($access['l_ac']['add'], $access['access'])) {
                          echo '<button class="btn btn-success pull-left" id="btn_tambah" type="button" data-toggle="collapse" data-target="#add" aria-expanded="false" aria-controls="import"><i class="fa fa-plus"></i> Tambah Cuti Bersama</button>';
                        }?>
                        <button class="btn btn-danger" onclick="confirm_sync()" style="margin-left: 5px;float: left;"><i class="fas fa-refresh"></i> Sinkron Data</button>
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
                      <div class="col-md-2"></div>
                      <div class="col-md-8">
                        <form id="form_add">
                          <div class="form-group">
                            <label>Kode Cuti Bersama</label>
                            <input type="text" placeholder="Masukkan Kode Cuti Bersama" id="data_kode_add" name="kode" class="form-control" required="required" value="" readonly="readonly">
                          </div>
                          <div class="form-group">
                            <label>Tanggal Libur</label>
                              <div class="input-group">
                                <div class="input-group-addon">
                                  <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" name="tanggal" id="date_tanggal_add" class="date-picker form-control" placeholder="Tanggal Libur" required="required">
                              </div>
                          </div>
                          <div class="form-group">
                            <label>Keterangan Cuti Bersama</label>
                            <input type="text" placeholder="Masukkan Keterangan Cuti Bersama" id="data_name_add" name="nama" class="form-control field" required="required">
                          </div>
                          <div class="form-group">
                            <button type="button" onclick="do_add()" id="btn_add" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
                          </div>
                        </form>
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
                        <th>Kode Cuti Bersama</th>
                        <th>Tanggal Libur</th>
                        <th>Keterangan</th>
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
              <label class="col-md-6 control-label">Kode Cuti Bersama</label>
              <div class="col-md-6" id="data_kode_view"></div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Tanggal Libur</label>
              <div class="col-md-6" id="data_tanggal_view"></div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Keterangan</label>
              <div class="col-md-6" id="data_name_view"></div>
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
  <div class="modal-dialog modal-sm">
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
            <label>Kode Cuti Bersama</label>
            <input type="text" placeholder="Masukkan Kode Cuti Bersama" id="data_kode_edit" name="kode" value="" class="form-control" required="required" readonly="readonly">
          </div>
          <div class="form-group">
            <label>Tanggal Libur</label>
              <div class="input-group">
                <div class="input-group-addon">
                  <i class="fa fa-calendar"></i>
                </div>
                <input type="text" name="tanggal_e" value="" id="data_tanggal_edit" class="date-picker form-control" placeholder="Tanggal Libur" required="required">
              </div>
          </div>
          <div class="form-group">
            <label>Nama Cuti Bersama</label>
            <input type="text" placeholder="Masukkan Nama Cuti Bersama" id="data_name_edit" name="nama" value="" class="form-control" required="required">
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

<div id="confirm_sync" class="modal fade" role="dialog">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title text-center">Sinkron Data Cuti Bersama</h4>
			</div>
			<div class="modal-body text-center">
				<form id="form_sync">
          <p>Dengan mensinkron Data, Sisa Cuti karyawan akan disesuaikan berdasarkan jumlah data cuti bersama.<br>Apakah anda yakin ingin mensinkron ??
					</p>
          <hr>
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label>Pilih Tahun</label>
                <?php
                  $tahun_sync = $this->formatter->getYear();
                  $sels2 = array(date('Y'));
                  $exs2 = array('class'=>'form-control select2', 'id'=>'tahun_sinkron', 'style'=>'width:100%;','required'=>'required');
                  echo form_dropdown('tahun',$tahun_sync,$sels2,$exs2);
                ?>
              </div>
            </div>
          </div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" onclick="do_sync()" class="btn btn-danger"><i class="fas fa-refresh"></i>
					Sinkron Data</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
			</div>
		</div>
	</div>
</div>
<!-- delete -->
<div id="modal_delete_partial"></div>
<script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
<script type="text/javascript">
  //wajib diisi
  var table="master_cuti_bersama";
  var column="id";
  $(document).ready(function(){
    refreshCode();
    searchCutiBersama();
  });
  function searchCutiBersama() {
    var tahun = $('#tahun_filter').val();
    var cbx=getAjaxData("<?php echo base_url('master/master_cuti_bersama/view_jumlah')?>",{tahun:tahun}); 
    $('#keterangan_cuti_per_tahun').html(cbx['keterangan']);
    $('#keterangan_update').html(cbx['keterangan_update']);
		$('#table_data').DataTable().destroy();
    $('#table_data').DataTable( {
      ajax: {
        url: "<?php echo base_url('master/master_cuti_bersama/view_all/')?>",
        type: 'POST',
        data:{access:"<?php echo base64_encode(serialize($access));?>",tahun:tahun}
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
      //aksi
      {   targets: 6, 
        width: '5%',
        render: function ( data, type, full, meta ) {
          return '<center>'+data+'</center>';
        }
      },
      ]
    });
  }
  function refreshCode() {
    kode_generator("<?php echo base_url('master/master_cuti_bersama/kode');?>",'data_kode_add');
  }
  function view_modal(id) {
    var data={id:id};
    var callback=getAjaxData("<?php echo base_url('master/master_cuti_bersama/view_one')?>",data);  
    $('#view').modal('show');
    $('.header_data').html(callback['nama']);
    $('#data_kode_view').html(callback['kode']);
    $('#data_name_view').html(callback['nama']);
    $('#data_tanggal_view').html(callback['tanggal']);
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
    var data={id:id};
    var callback=getAjaxData("<?php echo base_url('master/master_cuti_bersama/view_one')?>",data); 
    $('#view').modal('toggle');
    setTimeout(function () {
       $('#edit').modal('show');
    },600); 
    $('.header_data').html(callback['nama']);
    $('#data_id_edit').val(callback['id']);
    $('#data_kode_edit_old').val(callback['kode']);
    $('#data_kode_edit').val(callback['kode']);
    $('#data_name_edit').val(callback['nama']);
    $('#data_tanggal_edit').val(callback['tanggal_e']);
  }
  function delete_modal(id) {
    var data={id:id};
    var callback=getAjaxData("<?php echo base_url('master/master_cuti_bersama/view_one')?>",data);
    var datax={table:table,column:column,id:id,nama:callback['nama']};
    loadModalAjax("<?php echo base_url('pages/load_modal_delete')?>",'modal_delete_partial',datax,'delete');
    searchCutiBersama();
  }
  //doing db transaction
  function do_status(id,data) {
    var tahun = $('#tahun_filter').val();
    var data_table={status:data};
    var where={id:id};
    var datax={table:table,where:where,data:data_table,tahun:tahun};
    submitAjax("<?php echo base_url('global_control/change_status2')?>",null,datax,null,null,'status');
    $('#table_data').DataTable().ajax.reload();
    searchCutiBersama();
  }
  function do_edit(){
    if($("#form_edit")[0].checkValidity()) {
      submitAjax("<?php echo base_url('master/edt_cuti_bersama')?>",'edit','form_edit',null,null);
      $('#table_data').DataTable().ajax.reload();
      searchCutiBersama();
    }else{
      notValidParamx();
    } 
  }
  function do_add(){
    if($("#form_add")[0].checkValidity()) {
      submitAjax("<?php echo base_url('master/add_cuti_bersama')?>",null,'form_add',"<?php echo base_url('master/master_cuti_bersama/kode');?>",'data_kode_add');
      $('#table_data').DataTable().ajax.reload(function(){
        Pace.restart();
      });
      $('#form_add')[0].reset();
        refreshCode();
        searchCutiBersama();
    }else{
      notValidParamx();
    } 
  }
	function confirm_sync() {
		$('#confirm_sync').modal('show');
	}
	function do_sync() {
    var tahun = $('#tahun_sinkron').val();
    var datax={tahun:tahun};
    submitAjax("<?php echo base_url('master/master_cuti_bersama/syncronize')?>",null,datax,null,null,'status');
    $('#table_data').DataTable().ajax.reload();
    searchCutiBersama();
	}
</script>