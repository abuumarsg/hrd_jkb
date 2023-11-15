<div class="content-wrapper">
   <section class="content-header">
      <h1>
         <i class="fas fa-clock"></i> Master Kuota Lembur 
         <small><b><?=$nama?></b></small>
      </h1>
      <ol class="breadcrumb">
         <li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
         <li><a href="<?php echo base_url('pages/master_kuota_lembur');?>"><i class="fas fa-clock"></i> Master Kuota Lembur</a></li>
         <li class="active"><?=$nama?></li>
      </ol>
   </section>
   <section class="content">
      <div class="row">
         <div class="col-md-12">
            <div class="box box-success">
               <div class="box-header with-border">
                  <h3 class="box-title"><i class="fas fa-clock"></i> Kuota Lembur <?=$nama?></h3>
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
                                    echo '<button class="btn btn-success" id="btn_tambah" type="button" data-toggle="collapse" data-target="#add" aria-expanded="false" aria-controls="import"><i class="fa fa-plus"></i> Tambah Data</button>';
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
                                       <label>Kode Kuota Lembur</label>
                                       <input type="text" name="kode_kuota_lembur" value="<?php echo $this->codegenerator->decryptChar($this->uri->segment(3));?>" class="form-control" required="required" readonly="readonly">
                                    </div>
                                    <div class="form-group">
                                       <label>Bagian</label>
                                       <select class="form-control select2" name="bagian" id="data_bagian_add" style="width: 100%;"></select>
                                    </div>
                                    <div class="form-group">
                                       <label>Kuota Lembur (Jam)</label>
                                       <input type="number" placeholder="Masukkan Kuota Lembur" name="kuota" class="form-control" required="required">
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
                                 <th>Bagian</th>
                                 <th>Lokasi Kerja</th>
                                 <th>Persen</th>
                                 <th>Kuota (Jam)</th>
                                 <th>Sisa Kuota (Jam)</th>
                                 <th>Tanggal</th>
                                 <th>Status</th> 
                                 <th>Aksi</th>
                              </tr>
                           </thead>
                           <tbody>
                           </tbody>
                           <tfoot>
                              <tr>
                                 <th colspan="3" style="text-align:right:width:10%">Total:</th>
                                 <th width="5%"></th>
                                 <th width="5%"></th>
                                 <th width="5%"></th>
                                 <th width="5%"></th>
                                 <th width="5%"></th>
                                 <th width="5%"></th>
                                 <!-- <th width="25%"></th>
                                 <th width="10%"></th>
                                 <th width="10%"></th>
                                 <th width="8%"></th>
                                 <th width="5%"></th>
                                 <th width="7%"></th> -->
                              </tr>
                              <!-- <tr>
                                 <th>No.</th>
                                 <th>Bagian</th>
                                 <th>Lokasi Kerja</th>
                                 <th>Persen</th>
                                 <th>Kuota (Jam)</th>
                                 <th>Sisa Kuota (Jam)</th>
                                 <th>Tanggal</th>
                                 <th>Status</th> 
                                 <th>Aksi</th>
                              </tr> -->
                           </tfoot>
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
                     <label class="col-md-6 control-label">Kode</label>
                     <div class="col-md-6" id="data_kode_view"></div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Nama Bagian</label>
                     <div class="col-md-6" id="data_nama_bagian_view"></div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Nama Lokasi</label>
                     <div class="col-md-6" id="data_nama_lokasi_view"></div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Persentase (%)</label>
                     <div class="col-md-6" id="data_persen_view"></div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Kuota (Jam)</label>
                     <div class="col-md-6" id="data_kuota_view"></div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Sisa Kuota (Jam)</label>
                     <div class="col-md-6" id="data_sisa_kuota_view"></div>
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
                  <label>Kode</label>
                  <input type="text" placeholder="Masukkan Kode" id="data_kode_edit" name="kode" class="form-control" readonly="readonly">
               </div>
               <div class="form-group">
                  <label>Nama Bagian</label>
                  <select class="form-control select2" name="bagian" id="data_kode_bagian_edit" style="width: 100%;"></select>
               </div>
               <div class="form-group">
                  <label>Persentase Kuota</label>
                  <input type="number" placeholder="Masukkan Persentase Kuota" id="data_persen_edit" name="persen" class="form-control" required="required">
               </div>
               <div class="form-group">
                  <label>Kuota Lembur</label>
                  <input type="number" placeholder="Masukkan Kuota Lembur" id="data_kuota_edit" name="kuota" class="form-control" required="required">
               </div>
               <div class="form-group">
                  <label>Sisa Kuota Lembur</label>
                  <input type="number" placeholder="Masukkan Sisa Kuota Lembur" id="data_sisa_kuota_edit" name="sisa_kuota" class="form-control" required="required">
               </div>
            </div>
         </form>
            <div class="modal-footer">
               <button type="button" onclick="do_edit()" id="btn_edit" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
               <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
            </div>
      </div>

   </div>
</div>

<!-- delete -->
<div id="modal_delete_partial"></div>
<script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
<script type="text/javascript">
  var url_select="<?php echo base_url('global_control/select2_global');?>";
  //wajib diisi
  var table="detail_kuota_lembur";
  var column="id";
  $(document).ready(function(){
    refreshCode();
    $('#table_data').DataTable( {
      ajax: {
        url: "<?php echo base_url('master/detail_kuota_lembur/view_all/'.$this->uri->segment(3))?>",
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
         width: '25%',
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
         {   targets: 6,
         width: '8%',
         render: function ( data, type, full, meta ) {
            return '<center>'+data+'</center>';
         }
         },
         {   targets: 7,
         width: '5%',
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
      ],
      footerCallback: function (row, data, start, end, display) {
         var api = this.api();
         var intVal = function (i) {
               return typeof i === 'string' ? i.replace(/[^\d.-]/g, '') * 1 : typeof i === 'number' ? i : 0;
         }; 
         persen = api
               .column(3)
               .data()
               .reduce(function (a, b) {
                  return intVal(a) + intVal(b);
               }, 0); 
         pagePersen = api
               .column(3, { page: 'current' })
               .data()
               .reduce(function (a, b) {
                  return intVal(a) + intVal(b);
               }, 0); 
         $(api.column(3).footer()).html('' + pagePersen + '% dari ' + persen + '%');
         //
         total = api
               .column(4)
               .data()
               .reduce(function (a, b) {
                  return intVal(a) + intVal(b);
               }, 0); 
         pageTotal = api
               .column(4, { page: 'current' })
               .data()
               .reduce(function (a, b) {
                  return intVal(a) + intVal(b);
               }, 0); 
         // Update footer
         $(api.column(4).footer()).html('' + pageTotal + ' jam dari ' + total + ' total jam');
         //
         sisa = api
               .column(5)
               .data()
               .reduce(function (a, b) {
                  return intVal(a) + intVal(b);
               }, 0); 
         pageSisa = api
               .column(5, { page: 'current' })
               .data()
               .reduce(function (a, b) {
                  return intVal(a) + intVal(b);
               }, 0); 
         // Update footer
         $(api.column(5).footer()).html('' + pageSisa + ' jam dari ' + sisa + ' total jam');
      },
    });
    $('#btn_tambah').click(function(){
      select_data('data_induk_add',url_select,'master_induk_grade','kode_induk_grade','nama');
      select_data('data_dokumen_add',url_select,'master_dokumen','kode_dokumen','nama');
      select_data('data_loker_add',url_select,'master_loker','kode_loker','nama');
    });
    getSelect2("<?php echo base_url('master/master_kuota_lembur/get_select2')?>",'data_bagian_add');
  });
  function refreshCode() {
    kode_generator("<?php echo base_url('master/master_grade/kode');?>",'data_kode_add');
  }
  function view_modal(id) {
    var data={id:id};
    var callback=getAjaxData("<?php echo base_url('master/detail_kuota_lembur/view_one')?>",data);  
    $('#view').modal('show');
    $('.header_data').html(callback['nama']);
    $('#data_kode_view').html(callback['kode']);
    $('#data_nama_bagian_view').html(callback['nama']);
    $('#data_nama_lokasi_view').html(callback['nama_lokasi']);
    $('#data_persen_view').html(callback['persen_v']);
    $('#data_kuota_view').html(callback['kuota']);
    $('#data_sisa_kuota_view').html(callback['sisa_kuota']);
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
    getSelect2("<?php echo base_url('master/master_kuota_lembur/get_select2')?>",'data_kode_bagian_edit');
    var id = $('input[name="data_id_view"]').val();
    var data={id:id};
    var callback=getAjaxData("<?php echo base_url('master/detail_kuota_lembur/view_one')?>",data); 
    $('#view').modal('toggle');
    setTimeout(function () {
       $('#edit').modal('show');
    },600); 
    $('.header_data').html(callback['nama']);
    $('#data_id_edit').val(callback['id']);
    $('#data_kode_edit').val(callback['kode']);
    $('#data_kode_bagian_edit').val(callback['kode_bagian']).trigger('change');
    $('#data_persen_edit').val(callback['persen']);
    $('#data_kuota_edit').val(callback['kuota']);
    $('#data_sisa_kuota_edit').val(callback['sisa_kuota']);
  }
  function delete_modal(id) {
    var data={id:id};
    var callback=getAjaxData("<?php echo base_url('master/detail_kuota_lembur/view_one')?>",data);
    var datax={table:table,column:column,id:id,nama:callback['nama']};
    loadModalAjax("<?php echo base_url('pages/load_modal_delete')?>",'modal_delete_partial',datax,'delete');
  }
  //doing db transaction
  function do_status(id,data) {
    var data_table={status:data};
    var where={id:id};
    var datax={table:table,where:where,data:data_table};
    submitAjax("<?php echo base_url('global_control/change_status')?>",null,datax,null,null,'status');
    $('#table_data').DataTable().ajax.reload();
  }
  function do_edit(){
   //  if($("#form_edit")[0].checkValidity()) {
      submitAjax("<?php echo base_url('master/edit_detail_kuota_lembur')?>",'edit','form_edit',null,null);
      $('#table_data').DataTable().ajax.reload();
   //  }else{
   //    notValidParamx();
   //  } 
  }
  function do_add(){
    if($("#form_add")[0].checkValidity()) {
      submitAjax("<?php echo base_url('master/add_detail_kuota_lembur')?>",null,'form_add',null,null);
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