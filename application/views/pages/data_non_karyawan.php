  <div class="content-wrapper">
    <section class="content-header">
      <h1>
        <i class="fas fa-user-circle"></i> Data Non Karyawan
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
        <li class="active">Data Non Karyawan</li>
      </ol>
    </section>
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box box-success">
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fas fa-user-circle"></i> Data Non Karyawan</h3>
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
                          echo '<button class="btn btn-success" id="btn_tambah" type="button" data-toggle="collapse" data-target="#add" aria-expanded="false" aria-controls="import"><i class="fa fa-plus"></i> Data Non Karyawan</button>';
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
                      <form id="form_add">
                        <div class="col-md-1"></div>
                        <div class="col-md-5">
                          <div class="col-md-12">
                            <div class="form-group">
                              <label>Nama Instansi / Non Karyawan</label>
                              <input type="text" placeholder="Nama Instansi / Non Karyawan" name="nama" class="form-control field" required="required">
                            </div>
                          </div>
                          <div class="col-md-12">
                            <div class="form-group">
                              <label>NIK</label>
                              <input type="text" placeholder="Masukkan NIK" name="nik" class="form-control field" required="required">
                            </div>
                          </div>
                          <div class="col-md-12">
                            <div class="form-group">
                              <label>No Telpon</label>
                              <input type="text" placeholder="Masukkan No Telpon" name="no_telp" class="form-control field" required="required">
                            </div>
                          </div>
                          <div class="col-md-12">
                            <div class="form-group">
                              <label>Jenis</label>
                                <select class="form-control select2" name="jenis" id="data_jenis_add" style="width: 100%;">
                                  <?php
                                    echo '<option></option>';
                                    foreach ($jenis as $p) {
                                      echo '<option value="'.$p->kode_pajak.'">'.$p->nama.'</option>';
                                    }
                                  ?>
                                </select>
                            </div>
                          </div>
                          <div class="col-md-12">
                            <div class="form-group">
                              <label>Status Pajak</label>
                                <select class="form-control select2" name="status_pajak" id="status_pajak_add" style="width: 100%;">
                                  <?php
                                    $status_pajak = $this->otherfunctions->getStatusPajakList();
                                    echo '<option></option>';
                                    foreach ($status_pajak as $kk => $vv) {
                                      echo '<option value="'.$kk.'">'.$vv.'</option>';
                                    }
                                  ?>
                                </select>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-5">
                          <div class="col-md-12">
                            <div class="form-group">
                              <label>Perhitungan Pajak</label>
                                <select class="form-control select2" name="perhitungan_pajak" id="perhitungan_pajak_add" style="width: 100%;">
                                  <?php
                                    $perhitungan_pajak = $this->otherfunctions->getJenisPerhitunganPajak();
                                    echo '<option></option>';
                                    foreach ($perhitungan_pajak as $kkx => $vvx) {
                                      echo '<option value="'.$kkx.'">'.$vvx.'</option>';
                                    }
                                  ?>
                                </select>
                            </div>
                          </div>
                          <div class="col-md-12">
                            <div class="form-group">
                              <label>No NPWP</label>
                              <input type="text" placeholder="Masukkan No NPWP" name="npwp" class="form-control field" required="required">
                            </div>
                          </div>
                          <div class="col-md-12">
                            <div class="form-group">
                              <label>Alamat</label>
                                <textarea name="alamat" class="form-control" placeholder="Alamat"></textarea>
                            </div>
                          </div>
                          <div class="col-md-12">
                            <div class="form-group">
                              <label>Keterangan</label>
                                <textarea name="keterangan" class="form-control" placeholder="Keterangan"></textarea>
                            </div>
                          </div>
                          <div class="col-md-12">
                            <div class="form-group">
                              <button type="button" onclick="do_add()" id="btn_add" class="btn btn-success pull-right"><i class="fa fa-floppy-o"></i> Simpan</button>
                            </div>
                          </div>
                        </div>
                      </form>
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
                        <th>NIK</th>
                        <th>Nama Intansi / Non Karyawan</th>
                        <th>No Telpon</th>
                        <th>Jenis</th>
                        <th>Alamat</th>
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
              <label class="col-md-6 control-label">Nama</label>
              <div class="col-md-6" id="data_nama_view"></div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">NIK</label>
              <div class="col-md-6" id="data_nik_view"></div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">No Telpon</label>
              <div class="col-md-6" id="data_no_telp_view"></div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Jenis</label>
              <div class="col-md-6" id="data_jenis_view"></div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">No NPWP</label>
              <div class="col-md-6" id="data_npwp_view"></div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Status Pajak</label>
              <div class="col-md-6" id="data_status_pajak_view"></div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Perhitungan Pajak</label>
              <div class="col-md-6" id="data_perhitungan_pajak_view"></div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Alamat</label>
              <div class="col-md-6" id="data_alamat_view"></div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Keterangan</label>
              <div class="col-md-6" id="data_keterangan_view"></div>
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
            <label>Nama Instansi / Non Karyawan</label>
            <input type="text" placeholder="Nama Instansi / Non Karyawan" id="data_nama_edit" name="nama" value="" class="form-control" required="required">
          </div>
          <div class="form-group">
            <label>NIK</label>
            <input type="text" placeholder="Masukkan NIK" id="data_nik_edit" name="nik" value="" class="form-control" required="required">
          </div>
          <div class="form-group">
            <label>Nomor Telpon</label>
            <input type="text" placeholder="Masukkan Nomor Telpon" id="data_no_telp_edit" name="no_telp" value="" class="form-control" required="required">
          </div>
          <div class="form-group">
            <label>Jenis</label>
              <select class="form-control select2" name="jenis" id="data_jenis_edit" style="width: 100%;">
                <?php
                  echo '<option></option>';
                  foreach ($jenis as $p) {
                    echo '<option value="'.$p->kode_pajak.'">'.$p->nama.'</option>';
                  }
                ?>
              </select>
          </div>
          <div class="form-group">
            <label>Status Pajak</label>
              <select class="form-control select2" name="status_pajak" id="status_pajak_edit" style="width: 100%;">
                <?php
                  $status_pajak = $this->otherfunctions->getStatusPajakList();
                  echo '<option></option>';
                  foreach ($status_pajak as $kk_e => $vv_e) {
                    echo '<option value="'.$kk_e.'">'.$vv_e.'</option>';
                  }
                ?>
              </select>
          </div>
          <div class="form-group">
            <label>Perhitungan Pajak</label>
              <select class="form-control select2" name="perhitungan_pajak" id="perhitungan_pajak_edit" style="width: 100%;">
                <?php
                  $perhitungan_pajak = $this->otherfunctions->getJenisPerhitunganPajak();
                  echo '<option></option>';
                  foreach ($perhitungan_pajak as $kkx => $vvx) {
                    echo '<option value="'.$kkx.'">'.$vvx.'</option>';
                  }
                ?>
              </select>
          </div>
          <div class="form-group">
            <label>No NPWP</label>
            <input type="text" placeholder="Masukkan No NPWP" name="npwp" id="data_npwp_edit" class="form-control field">
          </div>
          <div class="form-group">
            <label>Alamat</label>
              <textarea name="alamat" class="form-control" id="data_alamat_edit" placeholder="Alamat"></textarea>
          </div>
          <div class="form-group">
            <label>Keterangan</label>
              <textarea name="keterangan" class="form-control" id="data_keterangan_edit" placeholder="Keterangan"></textarea>
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
  var url_select="<?php echo base_url('global_control/select2_global');?>";
  var table="data_non_karyawan";
  var column="id_non";
  $(document).ready(function(){
    $('#table_data').DataTable( {
      ajax: {
        url: "<?php echo base_url('employee/data_non_karyawan/view_all/')?>",
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
      {   targets: 7,
        width: '10%',
        render: function ( data, type, full, meta ) {
          return '<center>'+data+'</center>';
        }
      },
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
    var data={id_non:id};
    var callback=getAjaxData("<?php echo base_url('employee/data_non_karyawan/view_one')?>",data);  
    $('#view').modal('show');
    $('.header_data').html(callback['nama']);
    $('#data_nama_view').html(callback['nama']);
    $('#data_nik_view').html(callback['nik']);
    $('#data_no_telp_view').html(callback['view_no_telp']);
    $('#data_alamat_view').html(callback['view_alamat']);
    $('#data_jenis_view').html(callback['nama_jenis']);
    $('#data_keterangan_view').html(callback['view_keterangan']);
    $('#data_npwp_view').html(callback['npwp']);
    $('#data_status_pajak_view').html(callback['status_pajak']);
    $('#data_perhitungan_pajak_view').html(callback['perhitungan_pajak']);
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
    var data={id_non:id};
    var callback=getAjaxData("<?php echo base_url('employee/data_non_karyawan/view_one')?>",data); 
    $('#view').modal('toggle');
    setTimeout(function () {
       $('#edit').modal('show');
    },600); 
    $('.header_data').html(callback['nama']);
    $('#data_id_edit').val(callback['id']);
    $('#data_nama_edit').val(callback['nama']);
    $('#data_nik_edit').val(callback['nik']);
    $('#data_no_telp_edit').val(callback['no_telp']);
    $('#data_alamat_edit').val(callback['alamat']);
    $('#data_keterangan_edit').val(callback['keterangan']);
    $('#data_jenis_edit').val(callback['jenis']).trigger('change');
    $('#data_npwp_edit').val(callback['npwp']);
    $('#status_pajak_edit').val(callback['status_pajak_e']).trigger('change');
    $('#perhitungan_pajak_edit').val(callback['perhitungan_pajak_e']).trigger('change');
  }
  function delete_modal(id) {
    var data={id_non:id};
    var callback=getAjaxData("<?php echo base_url('employee/data_non_karyawan/view_one')?>",data);
    var datax={table:table,column:column,id:id,nama:callback['nama']};
    loadModalAjax("<?php echo base_url('pages/load_modal_delete')?>",'modal_delete_partial',datax,'delete');
  }
  //doing db transaction
  function do_status(id,data) {
    var data_table={status:data};
    var where={id_non:id};
    var datax={table:table,where:where,data:data_table};
    submitAjax("<?php echo base_url('global_control/change_status')?>",null,datax,null,null,'status');
    $('#table_data').DataTable().ajax.reload();
  }
  function do_edit(){
    if($("#form_edit")[0].checkValidity()) {
      submitAjax("<?php echo base_url('employee/edit_non_karyawan')?>",'edit','form_edit',null,null);
      $('#table_data').DataTable().ajax.reload();
    }else{
      notValidParamx();
    } 
  }
  function do_add(){
    if($("#form_add")[0].checkValidity()) {
      submitAjax("<?php echo base_url('employee/add_non_karyawan')?>",null,'form_add',null,null);
      $('#table_data').DataTable().ajax.reload(function(){
        Pace.restart();
      });
      $('#form_add')[0].reset();
        refreshCode();
        refreshAdd();
    }else{
      notValidParamx();
    } 
  }
</script>