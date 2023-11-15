<div class="content-wrapper">
   <section class="content-header">
      <h1>
         <i class="fa fa-database"></i> Master Data 
         <small>Master Grade</small>
      </h1>
      <ol class="breadcrumb">
         <li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
         <li><a href="<?php echo base_url('pages/master_induk_grade');?>"><i class="fa fa-list"></i> Master Induk Grade</a></li>
         <li class="active">Master Grade</li>
      </ol>
   </section>
   <section class="content">
      <div class="row">
         <div class="col-md-12">
            <div class="box box-success">
               <div class="box-header with-border">
                  <h3 class="box-title"><i class="fa fa-list"></i> Daftar Grade </h3>
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
                                    echo '<button class="btn btn-success" id="btn_tambah" type="button" data-toggle="collapse" data-target="#add" aria-expanded="false" aria-controls="import"><i class="fa fa-plus"></i> Tambah Grade</button>';
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
                                    <div class="form-group">
                                       <label>Kode Grade</label>
                                       <input type="hidden" name="induk_grade" value="<?php echo $this->codegenerator->decryptChar($this->uri->segment(3));?>">
                                       <input type="text" placeholder="Masukkan Kode Grade" id="data_kode_add" name="kode" class="form-control" required="required" value="" readonly="readonly">
                                    </div>
                                    <!-- <div class="form-group"> -->
                                       <!-- <label>Induk Grade</label> -->
                                       <!-- <select class="form-control select2" name="induk_grade" id="data_induk_add" style="width: 100%;"></select> -->
                                    <!-- </div> -->
                                    <div class="form-group">
                                       <label>Nama Grade</label>
                                       <input type="text" placeholder="Masukkan Nama Grade" id="data_name_add" name="nama" class="form-control field" required="required">
                                    </div>
                                    <div class="form-group">
                                       <label>Lokasi</label>
                                       <select class="form-control select2" name="loker" id="data_loker_add" style="width: 100%;"></select>
                                    </div>
                                    <div class="form-group">
                                       <label>Gaji Pokok</label>
                                       <input type="text" placeholder="Masukkan Gaji Pokok" id="data_gapok_add" name="gapok" class="form-control input-money" required="required">
                                    </div>
                                    <div class="form-group">
                                       <label>Uang Makan</label>
                                       <input type="text" placeholder="Masukkan Uang Makan" id="data_um_add" name="um" class="form-control input-money" required="required">
                                    </div>
                                    <!-- <div class="form-group">
                                       <label>Tahun</label>
                                       <select class="form-control select2" id="tahun" name="tahun" style="width: 100%;">
                                          <option></option>
                                          <?php
                                          // $year = $this->formatter->getYear();
                                          // foreach ($year as $yk => $yv) {
                                          //    echo '<option value="'.$yk.'" '.$select.'>'.$yv.'</option>'; } ?>
                                       </select>
                                    </div> -->
                                    <div class="form-group">
                                       <label>Dokumen</label>
                                       <select class="form-control select2" name="dokumen" id="data_dokumen_add" style="width: 100%;"></select>
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
                                 <th>Kode Grade</th>
                                 <th>Induk Grade</th>
                                 <th>Nama Grade</th>
                                 <th>Lokasi Grade</th>
                                 <th>Gaji Pokok</th>
                                 <th>Uang Makan</th>
                                 <th>Dokumen</th>
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
                     <label class="col-md-6 control-label">Kode Grade</label>
                     <div class="col-md-6" id="data_kode_view"></div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Nama Induk Grade</label>
                     <div class="col-md-6" id="data_induk_view"></div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Nama Grade</label>
                     <div class="col-md-6" id="data_name_view"></div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Lokasi Grade</label>
                     <div class="col-md-6" id="data_loker_view"></div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Gaji Pokok</label>
                     <div class="col-md-6" id="data_gapok_view"></div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Uang Makan</label>
                     <div class="col-md-6" id="data_um_view"></div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Dokumen</label>
                     <div class="col-md-6" id="data_dokumen_view"></div>
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
                  <label>Kode Grade</label>
                  <input type="text" placeholder="Masukkan Kode Grade" id="data_kode_edit" name="kode" value="" class="form-control" required="required" readonly="readonly">
               </div>
               <div class="form-group">
                  <label>Induk Grade</label>
                  <select class="form-control select2" name="induk_grade" id="data_induk_edit" style="width: 100%;"></select>
               </div>
               <div class="form-group">
                  <label>Nama Grade</label>
                  <input type="text" placeholder="Masukkan Nama Grade" id="data_name_edit" name="nama" value="" class="form-control" required="required">
               </div>
               <div class="form-group">
                  <label>Lokasi Grade</label>
                  <select class="form-control select2" name="loker" id="data_loker_edit" style="width: 100%;"></select>
               </div>
               <div class="form-group">
                  <label>Gaji Pokok</label>
                  <input type="text" placeholder="Masukkan Gaji Pokok" id="data_gapok_edit" name="gapok" value="" class="form-control input-money">
               </div>
               <div class="form-group">
                  <label>Uang Makan</label>
                  <input type="text" placeholder="Masukkan Uang Makan" id="data_um_edit" name="um" value="" class="form-control input-money">
               </div>
               <!-- <div class="form-group">
                  <label>Tahun</label>
                  <select class="form-control select2" id="data_tahun_edit" name="tahun" style="width: 100%;">
                     <option></option>
                     <?php
                     // $year = $this->formatter->getYear();
                     // foreach ($year as $yk => $yv) {
                     //    echo '<option value="'.$yk.'" '.$select.'>'.$yv.'</option>'; } ?>
                  </select>
               </div> -->
               <div class="form-group">
                  <label>Dokumen</label>
                  <select class="form-control select2" name="dokumen" id="data_dokumen_edit" style="width: 100%;"></select>
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
  //wajib diisi
  var table="master_grade";
  var column="id_grade";
  $(document).ready(function(){
    refreshCode();
    $('#table_data').DataTable( {
      ajax: {
        url: "<?php echo base_url('master/master_grade/view_all/'.$this->uri->segment(3))?>",
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
      {   targets: 1,
        width: '15%',
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
      {   targets: 10, 
        width: '5%',
        render: function ( data, type, full, meta ) {
          return '<center>'+data+'</center>';
        }
      },
      ]
    });
    $('#btn_tambah').click(function(){
      select_data('data_induk_add',url_select,'master_induk_grade','kode_induk_grade','nama');
      select_data('data_dokumen_add',url_select,'master_dokumen','kode_dokumen','nama');
      select_data('data_loker_add',url_select,'master_loker','kode_loker','nama');
    })
  });
  function refreshCode() {
    kode_generator("<?php echo base_url('master/master_grade/kode');?>",'data_kode_add');
  }
  function view_modal(id) {
    var data={id_grade:id};
    var callback=getAjaxData("<?php echo base_url('master/master_grade/view_one')?>",data);  
    $('#view').modal('show');
    $('.header_data').html(callback['nama']);
    $('#data_kode_view').html(callback['kode_grade']);
    $('#data_name_view').html(callback['nama']);
    $('#data_gapok_view').html(callback['gapok']);
    $('#data_um_view').html(callback['um']);
    $('#data_dokumen_view').html(callback['nama_dokumen']);
    $('#data_loker_view').html(callback['nama_loker']);
    $('#data_induk_view').html(callback['nama_induk_grade']);
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
    select_data('data_dokumen_edit',url_select,'master_dokumen','kode_dokumen','nama');
    select_data('data_loker_edit',url_select,'master_loker','kode_loker','nama');
    select_data('data_induk_edit',url_select,'master_induk_grade','kode_induk_grade','nama');
    var id = $('input[name="data_id_view"]').val();
    var data={id_grade:id};
    var callback=getAjaxData("<?php echo base_url('master/master_grade/view_one')?>",data); 
    $('#view').modal('toggle');
    setTimeout(function () {
       $('#edit').modal('show');
    },600); 
    $('.header_data').html(callback['nama']);
    $('#data_id_edit').val(callback['id']);
    $('#data_kode_edit_old').val(callback['kode_grade']);
    $('#data_kode_edit').val(callback['kode_grade']);
    $('#data_name_edit').val(callback['nama']);
    $('#data_gapok_edit').val(callback['gapok']);
    $('#data_um_edit').val(callback['um']);
    $('#data_dokumen_edit').val(callback['kode_dokumen']).trigger('change');
    $('#data_loker_edit').val(callback['kode_loker']).trigger('change');
    $('#data_induk_edit').val(callback['kode_induk_grade']).trigger('change');
    $('#data_tahun_edit').val(callback['e_tahun']).trigger('change');
  }
  function delete_modal(id) {
    var data={id_grade:id};
    var callback=getAjaxData("<?php echo base_url('master/master_grade/view_one')?>",data);
    var datax={table:table,column:column,id:id,nama:callback['nama']};
    loadModalAjax("<?php echo base_url('pages/load_modal_delete')?>",'modal_delete_partial',datax,'delete');
  }
  //doing db transaction
  function do_status(id,data) {
    var data_table={status:data};
    var where={id_grade:id};
    var datax={table:table,where:where,data:data_table};
    submitAjax("<?php echo base_url('global_control/change_status')?>",null,datax,null,null,'status');
    $('#table_data').DataTable().ajax.reload();
  }
  function do_edit(){
    if($("#form_edit")[0].checkValidity()) {
      submitAjax("<?php echo base_url('master/edt_grade')?>",'edit','form_edit',null,null);
      $('#table_data').DataTable().ajax.reload();
    }else{
      notValidParamx();
    } 
  }
  function do_add(){
    if($("#form_add")[0].checkValidity()) {
      submitAjax("<?php echo base_url('master/add_grade')?>",null,'form_add',null,null);
      $('#table_data').DataTable().ajax.reload(function (){
        Pace.restart();
      });
      $('#form_add')[0].reset();
      refreshCode();
    }else{
      notValidParamx();
    } 
  } 
</script>