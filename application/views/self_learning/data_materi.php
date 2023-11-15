<div class="content-wrapper">
   <section class="content-header">
      <h1>
         <i class="fas fa-chalkboard-teacher"></i> Materi Self Learning
      </h1>
      <ol class="breadcrumb">
         <li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
         <li class="active">Materi Self Learning</li>
      </ol>
   </section>
   <section class="content">
      <div class="row">
         <div class="col-md-12">
            <div class="box box-success">
               <div class="box-header with-border">
                  <h3 class="box-title"><i class="fas fa-chalkboard-teacher"></i> Materi Self Learning</h3>
                  <div class="box-tools pull-right">
                     <button class="btn btn-box-tool" onclick="reload_table('table_data')" data-toggle="tooltip" title="Refresh Table"><i class="fas fa-sync"></i></button>
                     <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                     <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i class="fa fa-times"></i></button>
                  </div>
               </div>
               <div class="box-body">
                  <div class="row">
                     <div class="col-md-12">
                        <div class="callout callout-info"><label><i class="fa fa-info-circle"></i> Bantuan</label><br>
                        <ul>
                           <li>Klik pada kolom <b>Aksi</b> untuk melihat preview soal pelatihan</li>
                           <li>Klik pada kolom <b>Jumlah File Materi</b> untuk melihat preview Materi pada pelatihan tersebut</li>
                           <li>Klik pada kolom <b>Karyawan</b> untuk mengatur siapa saja karyawan yg dapat pelatihan tersebut</li>
                        </ul>
                        </div>
                        <table id="table_data" class="table table-bordered table-striped table-responsive" width="100%">
                           <thead>
                              <tr>
                                 <th>No.</th>
                                 <th>Nama Materi</th>
                                 <th>Jumlah Soal</th>
                                 <th>Jumlah File Materi</th>
                                 <!-- <th>Karyawan</th> -->
                                 <th>Waktu</th>
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
                     <label class="col-md-6 control-label">Kode Materi</label>
                     <div class="col-md-6" id="data_kode_view"></div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Nama Materi</label>
                     <div class="col-md-6" id="data_name_view"></div>
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
            <div class="row">
               <div class="col-md-12">
                  <div id="data_tabel_view"></div>
               </div>
            </div>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
         </div>
      </div>
   </div>
</div>
<div id="view_modal_bagian" class="modal fade" role="dialog">
   <div class="modal-dialog modal-md">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h2 class="modal-title">Detail Bagian <b class="text-muted header_data"></b></h2>
            <input type="hidden" name="data_id_view">
         </div>
         <div class="modal-body">
            <div class="row">
               <div class="col-md-12">
                  <div id="data_tabel_bagian_view"></div>
               </div>
            </div>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
         </div>
      </div>
   </div>
</div>
<div id="view_modal_file" class="modal fade" role="dialog">
   <div class="modal-dialog modal-md">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h2 class="modal-title">Detail Data <b class="text-muted header_data"></b></h2>
            <input type="hidden" name="data_id_view">
         </div>
         <div class="modal-body">
            <div class="row">
               <div class="col-md-12">
                  <div id="data_tabel_file_view"></div>
               </div>
            </div>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
         </div>
      </div>
   </div>
</div>
<div id="modal_delete_partial"></div>
<script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
<script type="text/javascript">
  var url_select="<?php echo base_url('global_control/select2_global');?>";
   var table="learn_materi";
   var column="id";
   $(document).ready(function(){
      $('#table_data').DataTable( {
         ajax: {
            url: "<?php echo base_url('learning/getMateriLearningBagian/view_all/')?>",
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
            width: '35%',
            render: function ( data, type, full, meta ) {
               return data;
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
         ]
      });
   });
   function view_modal(id) {
      var data={id:id};
      var callback=getAjaxData("<?php echo base_url('learning/getMateriLearningBagian/view_one')?>",data);  
      $('#view').modal('show');
      $('.header_data').html(callback['nama']);
      $('#data_kode_view').html(callback['kode']);
      $('#data_name_view').html(callback['nama']);
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
      $('#data_tabel_view').html(callback['tabel']);
   }
   function view_modal_bagian(id) {
      var data={id:id};
      var callback=getAjaxData("<?php echo base_url('learning/getMateriLearning/view_one_bagian')?>",data);  
      $('#view_modal_bagian').modal('show');
      $('.header_data').html(callback['nama']);
      $('#data_tabel_bagian_view').html(callback['tabel_bagian']);
   }
   function view_modal_file(kode) {
      var data={kode:kode};
      var callback=getAjaxData("<?php echo base_url('learning/getMateriLearningBagian/view_one_file')?>",data);  
      $('#view_modal_file').modal('show');
      $('.header_data').html(callback['nama']);
      $('#data_tabel_file_view').html(callback['tabel_file']);
   }
</script>