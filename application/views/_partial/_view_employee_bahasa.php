<meta http-equiv="cache-control" content="no-cache" />
<meta http-equiv="pragma" content="no-cache" />
<?php if (in_array($access['l_ac']['add'], $access['access'])) { ?>
   <button type="button" class="btn btn-success btn-sm" onclick="add_modal_bahasa()">
      <i class ="fa fa-plus"></i> Tambah Bahasa
   </button>
   <div class="modal modal-default fade" id="add_bahasa">
      <div class="modal-dialog modal-lg">
         <div class="modal-content">
            <div class="modal-header">
               <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                  <h2 class="modal-title">Tambah Data</h2>
               </div>
               <form id="form_bahasa_add">
                  <div class="modal-body">
                     <input type="hidden" name="nik" value="<?php echo $profile['nik'];?>">
                     <div class="box-body">
                        <div class="form-group clearfix">
                           <label for="pengusaanbahasa" class="col-sm-3 control-label">Penguasaan Bahasa</label>
                           <div class="col-sm-9">
                              <?php
                              $sel = array(null);
                              $ex = array('class'=>'form-control select2','style'=>'width:100%;','required'=>'required');
                              echo form_dropdown('bahasa',$bahasa,$sel,$ex);
                              ?>
                           </div>
                        </div>
                        <div class="form-group clearfix">
                           <label for="membaca" class="col-sm-3 control-label">Membaca</label>
                           <div class="col-sm-9">
                              <?php
                              foreach ($radio as $rad => $r) {
                                 echo '<input type="radio" name="membaca" value="'.$rad.'">&nbsp;'.$r.'&nbsp;&nbsp;&nbsp;';
                              } ?>
                           </div>
                        </div>
                        <div class="form-group clearfix">
                           <label for="menulis" class="col-sm-3 control-label">Menulis</label>
                           <div class="col-sm-9">
                              <?php
                              foreach ($radio as $rad => $r) {
                                 echo '<input type="radio" name="menulis" value="'.$rad.'">&nbsp;'.$r.'&nbsp;&nbsp;&nbsp;';
                              } ?>
                           </div>
                        </div>
                        <div class="form-group clearfix">
                           <label for="berbicara" class="col-sm-3 control-label">Berbicara</label>
                           <div class="col-sm-9">
                              <?php
                              foreach ($radio as $rad => $r) {
                                 echo '<input type="radio" name="berbicara" value="'.$rad.'">&nbsp;'.$r.'&nbsp;&nbsp;&nbsp;';
                              }
                              ?>
                           </div>
                        </div>
                        <div class="form-group clearfix">
                           <label for="mendengar" class="col-sm-3 control-label">Mendengar</label>
                           <div class="col-sm-9">
                              <?php
                              foreach ($radio as $rad => $r) {
                                 echo '<input type="radio" name="mendengar" value="'.$rad.'">&nbsp;'.$r.'&nbsp;&nbsp;&nbsp;';
                              } ?>
                           </div>
                        </div>
                     </div>
                  </div>
               </form>
               <div class="modal-footer">
                  <button type="button" class="btn btn-primary" onclick="do_add_bahasa()"><i class="fa fa-floppy-o"></i> Simpan</button>
                  <button type="button" class="btn btn-default pull-right" data-dismiss="modal"> Kembali</button>
               </div>
            </div>
         </div>
      </div>
   <?php } ?>
   <br>
   <div class="row">
      <div class="col-md-12" style="padding-top: 10px;">
         <table id="table_data_bahasa" class="table table-bordered table-striped table-responsive" width="100%">
            <thead>
               <tr>
                  <th>No</th>
                  <th>Bahasa</th>
                  <th>Membaca</th>
                  <th>Menulis</th>
                  <th>Berbicara</th>
                  <th>Mendengar</th>
                  <th>Aksi</th>
               </tr>
            </thead>
            <tbody>
            </tbody>
         </table>
      </div>
   </div>

   <div id="view_bahasa" class="modal fade" role="dialog">
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
                        <label class="col-md-6 control-label">Bahasa</label>
                        <div class="col-md-6" id="bahasa"></div>
                     </div>
                     <div class="form-group col-md-12">
                        <label class="col-md-6 control-label">Membaca</label>
                        <div class="col-md-6" id="membaca"></div>
                     </div>
                     <div class="form-group col-md-12">
                        <label class="col-md-6 control-label">Menulis</label>
                        <div class="col-md-6" id="menulis"></div>
                     </div>
                     <div class="form-group col-md-12">
                        <label class="col-md-6 control-label">Berbicara</label>
                        <div class="col-md-6" id="berbicara"></div>
                     </div>
                     <div class="form-group col-md-12">
                        <label class="col-md-6 control-label">Mendengar</label>
                        <div class="col-md-6" id="mendengar"></div>
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
                  echo '<button type="submit" class="btn btn-info" onclick="edit_modal_bahasa()"><i class="fa fa-edit"></i> Edit</button>';
               }?>
               <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
            </div>
         </div>
      </div>
   </div>
   <?php if (in_array($access['l_ac']['edt'], $access['access'])) {?>
   <div class="modal modal-default fade" id="edit_bahasa">
      <div class="modal-dialog modal-lg">
         <div class="modal-content">
            <div class="modal-header">
               <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span></button>
               <h2 class="modal-title">Edit Data <b class="text-muted header_data"></b></h2>
            </div>
            <form id="form_bahasa_edit">
               <div class="modal-body">
                  <input type="hidden" name="id_k_bahasa" value="">
                  <input type="hidden" name="nik" value="<?php echo $profile['nik'];?>">
                  <div class="box-body">
                     <div class="form-group clearfix">
                        <label for="pengusaanbahasa" class="col-sm-3 control-label">Penguasaan Bahasa</label>
                        <div class="col-sm-9">
                           <?php
                           $sel1 = array(null);
                           $ex1 = array('class'=>'form-control select2','style'=>'width:100%;','required'=>'required','readonly'=>'readonly');
                           echo form_dropdown('bahasa2',$bahasa,$sel1,$ex1);
                           ?>
                        </div>
                     </div>
                     <div class="form-group clearfix">
                        <label for="membaca" class="col-sm-3 control-label">Membaca</label>
                        <div class="col-sm-9">
                           <?php
                           foreach ($radio as $rad => $r) {
                              echo '<input type="radio" name="membaca2" value="'.$rad.'">&nbsp;'.$r.'&nbsp;&nbsp;&nbsp;';
                           }
                           ?>
                        </div>
                     </div>
                     <div class="form-group clearfix">
                        <label for="menulis" class="col-sm-3 control-label">Menulis</label>
                        <div class="col-sm-9">
                           <?php
                           foreach ($radio as $rad => $r) {
                              echo '<input type="radio" name="menulis2" value="'.$rad.'">&nbsp;'.$r.'&nbsp;&nbsp;&nbsp;';
                           } ?>
                        </div>
                     </div>
                     <div class="form-group clearfix">
                        <label for="berbicara" class="col-sm-3 control-label">Berbicara</label>
                        <div class="col-sm-9">
                           <?php
                           foreach ($radio as $rad => $r) {
                              echo '<input type="radio" name="berbicara2" value="'.$rad.'">&nbsp;'.$r.'&nbsp;&nbsp;&nbsp;';
                           } ?>
                        </div>
                     </div>
                     <div class="form-group clearfix">
                        <label class="col-sm-3 control-label">Mendengar</label>
                        <div class="col-sm-9">
                           <?php
                           foreach ($radio as $rad => $r) {
                              echo '<input type="radio" name="mendengar2" value="'.$rad.'">&nbsp;'.$r.'&nbsp;&nbsp;&nbsp;';
                           } ?>
                        </div>
                     </div>
                  </div>
               </div>
            </form>
            <div class="modal-footer">
               <button type="button" class="btn btn-primary" onclick="do_edit_bahasa()"><i class="fa fa-floppy-o"></i> Simpan</button>
               <button type="button" class="btn btn-default pull-right" data-dismiss="modal"> Kembali</button>
            </div>
         </div>
      </div>
   </div>
   <?php } ?>
   <div class="modal fade" id="delete_bahasa">
      <div class="modal-dialog modal-danger modal-sm">
         <div class="modal-content">
            <div class="modal-header">
               <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span></button>
               <h4 class="modal-title">Menghapus Data</h4>
            </div>
            <form id="form_delete_bahasa">
               <div class="modal-body text-center">
                  <input type="hidden" id="data_column_delete" name="column" value="id_k_bahasa">
                  <input type="hidden" id="data_id_delete" name="id">
                  <input type="hidden" id="data_table_delete" name="table" value="karyawan_bahasa">
                  <p>Apakah anda yakin akan menghapus data <b id="data_name_delete" class="header_data"></b> ?</p>
               </div>
            </form>
            <div class="modal-footer">
               <button type="button" onclick="do_delete_bahasa()" class="btn btn-primary"><i class="fa fa-trash"></i> Hapus</button>
               <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
            </div>
         </div>
      </div>
   </div>
<script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
<script type="text/javascript">
   $(document).ready(function(){
      form_property();all_property();
   });
   function data_bahasa(){
      $('#table_data_bahasa').DataTable().destroy();
      $('#table_data_bahasa').DataTable( {
         ajax: {
            url: "<?php echo base_url().'employee/empbahasa/view_all/'.$profile['nik']; ?>",
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
         {   targets: 6, 
            width: '10%',
            render: function ( data, type, full, meta ) {
               return '<center>'+data+'</center>';
            }
         },
         ]
      });
   }
   function add_modal_bahasa() {
      $('#add_bahasa').modal('toggle');
      $('#add_bahasa .header_data').html('Tambah Data Bahasa');
   }
   function view_bahasa(id) {
      var data={id_k_bahasa:id};
      var callback=getAjaxData("<?php echo base_url().'employee/empbahasa/view_one/'.$profile['nik']; ?>",data);  
      $('#view_bahasa').modal('toggle');
      $('#view_bahasa input[name="data_id_view"]').val(callback['id']);
      $('#view_bahasa .header_data').html(callback['bahasav']);
      $('#view_bahasa #bahasa').html(callback['bahasav']);
      $('#view_bahasa #membaca').html(callback['membacav']);
      $('#view_bahasa #menulis').html(callback['menulisv']);
      $('#view_bahasa #berbicara').html(callback['berbicarav']);
      $('#view_bahasa #mendengar').html(callback['mendengarv']);
      var status = callback['status'];
      if(status==1){
         var statusval = '<b class="text-success">Aktif</b>';
      }else{
         var statusval = '<b class="text-danger">Tidak Aktif</b>';
      }
      $('#view_bahasa #data_status_view').html(statusval);
      $('#view_bahasa #data_create_date_view').html(callback['create_date']+' WIB');
      $('#view_bahasa #data_update_date_view').html(callback['update_date']+' WIB');
      $('#view_bahasa #data_create_by_view').html(callback['nama_buat']);
      $('#view_bahasa #data_update_by_view').html(callback['nama_update']);
   }
   function edit_modal_bahasa() {
      var id = $('#view_bahasa input[name="data_id_view"]').val();
      var data={id_k_bahasa:id};
      var callback=getAjaxData("<?php echo base_url().'employee/empbahasa/view_one/'.$profile['nik']; ?>",data); 
      $('#view_bahasa').modal('toggle');
      setTimeout(function () {
         $('#edit_bahasa').modal('show');
      },600); 
      $('#edit_bahasa .header_data').html(callback['bahasav']);
      $('#edit_bahasa input[name="id_k_bahasa"]').val(callback['id']);
      $('#edit_bahasa input[name="nik"]').val(callback['nik']);
      $('#edit_bahasa select[name="bahasa2"]').val(callback['bahasa']).trigger('change');
      var baca=callback['membaca'];
      $('input[name=membaca2][value='+baca+']').iCheck('check');
      var nulis=callback['menulis'];
      $('input[name=menulis2][value='+nulis+']').iCheck('check');
      var bicara=callback['berbicara'];
      $('input[name=berbicara2][value='+bicara+']').iCheck('check');
      var dengar=callback['mendengar'];
      $('input[name=mendengar2][value='+dengar+']').iCheck('check');
   }
   function do_add_bahasa() {
      submitAjax("<?php echo base_url('employee/add_bahasa')?>",'add_bahasa','form_bahasa_add',null,null);
      $('#table_data_bahasa').DataTable().ajax.reload(function (){
         Pace.restart();
      });
   }
   function do_edit_bahasa() {
      submitAjax("<?php echo base_url('employee/edit_bahasa')?>",'edit_bahasa','form_bahasa_edit',null,null);
      $('#table_data_bahasa').DataTable().ajax.reload(function (){
         Pace.restart();
      });
   }
   function delete_bahasa(id) {
      var table="karyawan_bahasa";
      var column="id_k_bahasa";
      var data={id_k_bahasa:id};
      $('#delete_bahasa').modal('toggle');
      var callback=getAjaxData("<?php echo base_url().'employee/empbahasa/view_one/'.$profile['nik']; ?>",data);
      $('#delete_bahasa #data_id_delete').val(callback['id']);
      $('#delete_bahasa .header_data').html(callback['bahasa']);
   }
   function do_delete_bahasa(){
      submitAjax("<?php echo base_url('global_control/delete')?>",'delete_bahasa','form_delete_bahasa',null,null);
      $('#table_data_bahasa').DataTable().ajax.reload(function (){
         Pace.restart();
      });
   }
</script>