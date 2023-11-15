  <div class="content-wrapper">
    <section class="content-header">
      <h1>
        <i class="fa fa-database"></i> Master Data 
        <small>Master Tarif Lembur</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
        <li class="active">Master Tarif Lembur</li>
      </ol>
    </section>
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box box-success">
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-list"></i> Daftar Tarif Lembur</h3>
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
                          echo '<button class="btn btn-success " id="btn_tambah" type="button" data-toggle="collapse" data-target="#add" aria-expanded="false" aria-controls="import"><i class="fa fa-plus"></i> Tambah Tarif Lembur</button>';
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
                      <div class="col-md-2"></div>
                      <div class="col-md-8">
                        <form id="form_add">
                          <div class="col-md-12">
                            <div class="form-group">
                              <label>Kode Tarif</label>
                              <input type="text" placeholder="Masukkan Kode Tarif Lembur" id="data_kode_add" name="kode" class="form-control" required="required" value="" readonly="readonly">
                            </div>
                          </div>
                          <div class="col-md-12">
                            <div class="form-group">
                              <label>Nama Tarif</label>
                              <input type="text" placeholder="Masukkan Nama Tarif Lembur" id="data_name_add" name="nama" class="form-control field" required="required">
                            </div>
                          </div>
                          <div class="col-md-12">
                            <div class="form-group">
                              <label>Jenis Lembur</label>
                                <?php
                                $jenisLembur[null] = 'Pilih Data';
                                $sel = [null];
                                $exsel = array('class'=>'form-control select2','placeholder'=>'Jenis Lembur','required'=>'required','style'=>'width:100%');
                                echo form_dropdown('jenis_lembur',$jenisLembur,$sel,$exsel);
                                ?>
                            </div>
                          </div>
                          <div class="col-md-12">
                            <div class="form-group">
                              <label>Potong Jam Lembur (Jam)</label>
                              <input type="number" placeholder="Potong Jam Lembur (Jam)" step="0.1" name="jam_potong" class="form-control field" required="required">
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group">
                              <label>Jam Ke</label>
                              <input type="number" placeholder="Jam Ke" name="jam_ke" step="0.5" class="form-control field" required="required">
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group">
                              <label>Ekuivalen</label>
                              <input type="number" placeholder="Ekuivalen" name="faktor_kali" step="0.5" class="form-control field" required="required">
                            </div>
                          </div>
                          <div class="col-md-12">
                            <!-- <div class="form-group">
                              <label>Tarif Lembur</label>
                              <input type="text" placeholder="Masukkan Nilai Upah Pokok" id="data_tarif_add" name="tarif" class="form-control input-money" required="required">
                            </div> -->
                            <div class="form-group">
                              <label>Keterangan</label>
                                <textarea name="keterangan" class="form-control" placeholder="Keterangan"></textarea>
                            </div>
                          </div>
                          <div class="col-md-12">
                            <div class="form-group">
                              <button type="button" onclick="do_add()" id="btn_add" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
                            </div>
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
                  <!-- Data Begin Here -->
								<div class="callout callout-info"><label><i class="fa fa-info-circle"></i> Info</label><br>Besar Nominal Lembur adalah Besar <b>Gaji Pokok</b> Karyawan di bagi jumlah jam kerja karyawan dalam sebulan <b>(<?=$this->model_master->getGeneralSetting('JKSB')['value_int'];?> Jam)</b><br>
                Setiap Jenis Lembur harus lengkap data mulai jam ke 1 lembur sampai maksimal berapa jam diperbolehkan lembur dalam sehari</div>
                  <table id="table_data" class="table table-bordered table-striped table-responsive" width="100%">
                    <thead>
                      <tr>
                        <th>No.</th>
                        <th>Kode Tarif</th>
                        <th>Nama Tarif</th>
                        <th>Jenis Lembur</th>
                        <th>Jam Ke</th>
                        <th>Ekuivalen</th>
                        <th>Potong Jam Lembur</th>
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
              <label class="col-md-6 control-label">Kode Tarif</label>
              <div class="col-md-6" id="data_kode_view"></div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Nama Tarif</label>
              <div class="col-md-6" id="data_name_view"></div>
            </div>
            <!-- <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Tarif Lembur</label>
              <div class="col-md-6" id="data_tarif_view"></div>
            </div> -->
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Jenis Lembur</label>
              <div class="col-md-6" id="data_jenis_lembur_view"></div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Jam Ke</label>
              <div class="col-md-6" id="data_jam_ke_view"></div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Ekuivalen</label>
              <div class="col-md-6" id="data_faktor_kali_view"></div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Potong Jam Lembur (Jam)</label>
              <div class="col-md-6" id="data_jam_potong_view"></div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Keterangan</label>
              <div class="col-md-6" id="data_keterangan_view"></div>
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
            <label>Kode Tarif</label>
            <input type="text" placeholder="Masukkan Kode Tarif Lembur" id="data_kode_edit" name="kode" value="" class="form-control" required="required" readonly="readonly">
          </div>
          <div class="form-group">
            <label>Nama Tarif</label>
            <input type="text" placeholder="Masukkan Nama Tarif Lembur" id="data_name_edit" name="nama" value="" class="form-control" required="required">
          </div>
          <!-- <div class="form-group">
            <label>Tarif Lembur</label>
            <input type="text" placeholder="Masukkan Nilai Upah Pokok" id="data_tarif_edit" name="tarif" value="" class="form-control input-money" required="required">
          </div> -->
          <div class="form-group">
            <label>Jenis Lembur</label>
                <?php
                $jenisLembur[null] = 'Pilih Data';
                $selw = [null];
                $exselw = array('class'=>'form-control select2','placeholder'=>'Jenis Lembur','required'=>'required','id'=>'data_jenis_lembur_edit','style'=>'width:100%');
                echo form_dropdown('jenis_lembur',$jenisLembur,$selw,$exselw);
                ?>
          </div>
          <div class="form-group">
            <label>Jam Ke</label>
              <input type="number" placeholder="Jam Ke" name="jam_ke" step="0.5" class="form-control field" id="data_jam_ke_edit" required="required">
          </div>
          <div class="form-group">
            <label>Ekuivalen</label>
              <input type="number" placeholder="Ekuivalen" name="faktor_kali" step="0.5" class="form-control field" id="data_faktor_kali_edit" required="required">
          </div>
          <div class="form-group">
            <label>Potong Jam Lembur (Jam)</label>
              <input type="number" placeholder="Potong Jam Lembur (Jam)" step="0.1" name="jam_potong" id="data_jam_potong_edit" class="form-control field" required="required">
          </div>
          <div class="form-group">
            <label>Keterangan</label>
              <textarea name="keterangan" class="form-control" placeholder="Keterangan" id="data_keterangan_edit"></textarea>
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
  var table="master_tarif_lembur";
  var column="id_tarif_lembur";
  $(document).ready(function(){
    refreshCode();
    $('#table_data').DataTable( {
      ajax: {
        url: "<?php echo base_url('master/master_tarif_lembur/view_all/')?>",
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
      {   targets: 7,
        width: '10%',
        render: function ( data, type, full, meta ) {
          return data;
        }
      },
      {   targets: 8,
        width: '10%',
        render: function ( data, type, full, meta ) {
          return '<center>'+data+'</center>';
        }
      },
      //aksi
      {   targets: 9, 
        width: '5%',
        render: function ( data, type, full, meta ) {
          return '<center>'+data+'</center>';
        }
      },
      ]
    });
    $('#btn_tambah').click(function(){
    })
  });
  function refreshCode() {
    kode_generator("<?php echo base_url('master/master_tarif_lembur/kode');?>",'data_kode_add');
  }
  function view_modal(id) {
    var data={id_tarif_lembur:id};
    var callback=getAjaxData("<?php echo base_url('master/master_tarif_lembur/view_one')?>",data);  
    $('#view').modal('show');
    $('.header_data').html(callback['nama']);
    $('#data_kode_view').html(callback['kode_tarif_lembur']);
    $('#data_name_view').html(callback['nama']);
    $('#data_tarif_view').html(callback['tarif']);
    $('#data_jenis_lembur_view').html(callback['jenis_lembur']);
    $('#data_jam_ke_view').html(callback['jam_ke']);
    $('#data_faktor_kali_view').html(callback['faktor_kali']);
    $('#data_jam_potong_view').html(callback['jam_potong']);
    $('#data_keterangan_view').html(callback['keterangan']);
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
    var data={id_tarif_lembur:id};
    var callback=getAjaxData("<?php echo base_url('master/master_tarif_lembur/view_one')?>",data); 
    $('#view').modal('toggle');
    setTimeout(function () {
       $('#edit').modal('show');
    },600); 
    $('.header_data').html(callback['nama']);
    $('#data_id_edit').val(callback['id']);
    $('#data_kode_edit_old').val(callback['kode_tarif_lembur']);
    $('#data_kode_edit').val(callback['kode_tarif_lembur']);
    $('#data_name_edit').val(callback['nama']);
    // $('#data_tarif_edit').val(callback['tarif']);
    $('#data_jenis_lembur_edit').val(callback['e_jenis_lembur']).trigger('change');
    $('#data_jam_potong_edit').val(callback['e_jam_potong']);
    $('#data_jam_ke_edit').val(callback['jam_ke']);
    $('#data_faktor_kali_edit').val(callback['e_faktor_kali']);
    $('#data_keterangan_edit').val(callback['keterangan']);
  }
  function delete_modal(id) {
    var data={id_tarif_lembur:id};
    var callback=getAjaxData("<?php echo base_url('master/master_tarif_lembur/view_one')?>",data);
    var datax={table:table,column:column,id:id,nama:callback['nama']};
    loadModalAjax("<?php echo base_url('pages/load_modal_delete')?>",'modal_delete_partial',datax,'delete');
  }
  //doing db transaction
  function do_status(id,data) {
    var data_table={status:data};
    var where={id_tarif_lembur:id};
    var datax={table:table,where:where,data:data_table};
    submitAjax("<?php echo base_url('global_control/change_status')?>",null,datax,null,null,'status');
    $('#table_data').DataTable().ajax.reload();
  }
  function do_edit(){
    if($("#form_edit")[0].checkValidity()) {
      submitAjax("<?php echo base_url('master/edt_tarif_lembur')?>",'edit','form_edit',null,null);
      $('#table_data').DataTable().ajax.reload();
    }else{
      notValidParamx();
    } 
  }
  function do_add(){
    if($("#form_add")[0].checkValidity()) {
      submitAjax("<?php echo base_url('master/add_tarif_lembur')?>",null,'form_add',"<?php echo base_url('master/master_tarif_lembur/kode');?>",'data_kode_add');
      $('#table_data').DataTable().ajax.reload(function(){
        Pace.restart();
      });
      $('#form_add')[0].reset();
        refreshCode();
    }else{
      notValidParamx();
    } 
  }
</script>