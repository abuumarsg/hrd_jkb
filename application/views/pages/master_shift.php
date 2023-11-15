<div class="content-wrapper">
   <section class="content-header">
      <h1>
         <i class="fa fa-database"></i> Master Data 
         <small>Master Shift</small>
      </h1>
      <ol class="breadcrumb">
         <li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
         <li class="active">Master Shift</li>
      </ol>
   </section>
   <section class="content">
      <div class="row">
         <div class="col-md-12">
            <div class="box box-success">
               <div class="box-header with-border">
                  <h3 class="box-title"><i class="fa fa-list"></i> Daftar Shift</h3>
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
                                    echo '<button class="btn btn-success" id="btn_tambah" type="button" data-toggle="collapse" data-target="#add" aria-expanded="false" aria-controls="import"><i class="fa fa-plus"></i> Tambah Shift</button>';
                                 }
                                 ?>
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
                                          <label>Kode Master Shift</label>
                                          <input type="text" placeholder="Masukkan Kode Shift" id="data_kode_add" name="kode" class="form-control" required="required" value="" readonly="readonly">
                                       </div>
                                    </div>
                                    <div class="col-md-12">
                                       <div class="form-group">
                                          <label>Nama Master Shift</label>
                                          <input type="text" placeholder="Masukkan Nama Shift" id="data_name_add" name="nama" class="form-control field" required="required">
                                       </div>
                                    </div>
                                    <div class="col-md-6">
                                       <div class="form-group">
                                          <label>Jam Mulai</label>
                                          <div class="input-group bootstrap-timepicker">
                                             <div class="input-group-addon">
                                                <i class="fa fa-clock-o"></i>
                                             </div>
                                             <input type="text" name="jam_mulai" id="data_mulai_add" class="time-picker form-control field" placeholder="Tetapkan Jam Mulai" required="required">
                                          </div>
                                       </div>
                                    </div>
                                    <div class="col-md-6">
                                       <div class="form-group">
                                          <label>Jam Selesai</label>
                                          <div class="input-group bootstrap-timepicker">
                                             <div class="input-group-addon">
                                                <i class="fa fa-clock-o"></i>
                                             </div>
                                             <input type="text" name="jam_selesai" id="data_selesai_add" class="time-picker form-control field" placeholder="Tetapkan Jam Selesai" required="required">
                                          </div>
                                       </div>
                                    </div>
                                    <div class="col-md-6">
                                       <div class="form-group">
                                          <label>Jam Istirahat Mulai</label>
                                          <div class="input-group bootstrap-timepicker">
                                             <div class="input-group-addon">
                                                <i class="fa fa-clock-o"></i>
                                             </div>
                                             <input type="text" name="jam_istirahat_mulai" id="data_mulai_add" class="time-picker form-control field" placeholder="Tetapkan Jam Mulai" required="required">
                                          </div>
                                       </div>
                                    </div>
                                    <div class="col-md-6">
                                       <div class="form-group">
                                          <label>Jam Istirahat Selesai</label>
                                          <div class="input-group bootstrap-timepicker">
                                             <div class="input-group-addon">
                                                <i class="fa fa-clock-o"></i>
                                             </div>
                                             <input type="text" name="jam_istirahat_selesai" id="data_selesai_add" class="time-picker form-control field" placeholder="Tetapkan Jam Selesai" required="required">
                                          </div>
                                       </div>
                                    </div>
                                    <div class="col-md-12">
                                       <div class="form-group">
                                          <label class="pull-left">Pilih Hari</label>
                                          <label class="pull-right checkbox-inline"><input type="checkbox" id="all_days"> Semua Hari</label>
                                          <select class="form-control select2" name="hari[]" style="width: 100%" multiple="multiple" id="for_hari">
                                             <?php
                                                echo '<option></option>';
                                                foreach ($hari as $k_adm=>$v_adm) {
                                                   echo '<option value="'.$k_adm.'">'.$v_adm.'</option>';
                                                }
                                             ?>
                                          </select>
                                       </div>
                                    </div>
                                    <div class="col-md-12">
                                       <div class="form-group">
                                          <label>Besar Potongan Gaji (%)</label>
                                          <input type="number" placeholder="Masukkan Berapa Persen Potongan Gaji" id="data_potongan_add" name="potongan" class="form-control field" required="required">
                                       </div>
                                    </div>
                                    <div class="col-md-12">
                                       <div class="form-group">
                                          <label>Shift</label>
                                          <?php
                                          $shift[null] = 'Pilih Data';
                                          $sel1 = array(null);
                                          $ex1 = array('class'=>'form-control select2','style'=>'width:100%;','required'=>'required','id'=>'data_shift_add');
                                          echo form_dropdown('shift',$shift,$sel1,$ex1);
                                          ?>
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
                        <table id="table_data" class="table table-bordered table-striped table-responsive" width="100%">
                           <thead>
                              <tr>
                                 <th>No.</th>
                                 <th>Kode Shift</th>
                                 <th>Nama Shift</th>
                                 <th>Jam Mulai</th>
                                 <th>Jam Selesai</th>
                                 <th>Hari</th>
                                 <th>Shift</th>
                                 <th>Potongan Gaji (%)</th>
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
                     <label class="col-md-6 control-label">Kode Master Shift</label>
                     <div class="col-md-6" id="data_kode_view"></div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Nama Master Shift</label>
                     <div class="col-md-6" id="data_name_view"></div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Jam Mulai</label>
                     <div class="col-md-6" id="data_mulai_view"></div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Jam Selesai</label>
                     <div class="col-md-6" id="data_selesai_view"></div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Jam Istirahat Mulai</label>
                     <div class="col-md-6" id="data_istirahat_mulai_view"></div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Jam Istirahat Selesai</label>
                     <div class="col-md-6" id="data_istirahat_selesai_view"></div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Hari</label>
                     <div class="col-md-6" id="data_hari_view"></div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Besar Potongan Gaji (%)</label>
                     <div class="col-md-6" id="data_potongan_view"></div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Shift</label>
                     <div class="col-md-6" id="data_shift_view"></div>
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
               echo '<button type="submit" id="btn_edit" class="btn btn-info" onclick="edit_modal()"><i class="fa fa-edit"></i> Edit</button>';
            }?>
            <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
         </div>
      </div>
   </div>
</div>

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
                  <label>Kode Master</label>
                  <input type="text" placeholder="Masukkan Kode Master Shift" id="data_kode_edit" name="kode" value="" class="form-control" required="required" readonly="readonly">
               </div>
               <div class="form-group">
                  <label>Nama Master</label>
                  <input type="text" placeholder="Masukkan Nama Master Shift" id="data_name_edit" name="nama" value="" class="form-control" required="required">
               </div>
               <div class="row">
                  <div class="col-md-6">
                     <div class="form-group">
                        <label>Jam Mulai</label>
                        <div class="input-group bootstrap-timepicker">
                           <div class="input-group-addon">
                              <i class="fa fa-clock-o"></i>
                           </div>
                           <input type="text" name="jam_mulai" id="data_mulai_edit" class="time-picker form-control field" placeholder="Tetapkan Jam Mulai" required="required">
                        </div>
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="form-group">
                        <label>Jam Selesai</label>
                        <div class="input-group bootstrap-timepicker">
                           <div class="input-group-addon">
                              <i class="fa fa-clock-o"></i>
                           </div>
                           <input type="text" name="jam_selesai" id="data_selesai_edit" class="time-picker form-control field" placeholder="Tetapkan Jam Selesai" required="required">
                        </div>
                     </div>
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="form-group">
                     <label>Jam Istirahat Mulai</label>
                     <div class="input-group bootstrap-timepicker">
                        <div class="input-group-addon">
                           <i class="fa fa-clock-o"></i>
                        </div>
                        <input type="text" name="jam_istirahat_mulai" id="istirahat_mulai_edit" class="time-picker form-control field" placeholder="Tetapkan Jam Mulai" required="required">
                     </div>
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="form-group">
                     <label>Jam Istirahat Selesai</label>
                     <div class="input-group bootstrap-timepicker">
                        <div class="input-group-addon">
                           <i class="fa fa-clock-o"></i>
                        </div>
                        <input type="text" name="jam_istirahat_selesai" id="istirahat_selesai_edit" class="time-picker form-control field" placeholder="Tetapkan Jam Selesai" required="required">
                     </div>
                  </div>
               </div>
               <div class="form-group">
                  <label class="pull-left">Pilih Hari</label>
                  <label class="pull-right checkbox-inline"><input type="checkbox" id="all_days_edit"> Semua Hari</label>
                  <select class="form-control select2" name="hari[]" style="width: 100%" multiple="multiple" id="data_hari_edit">
                     <?php
                     foreach ($hari as $k_adm=>$v_adm) {
                        echo '<option value="'.$k_adm.'">'.$v_adm.'</option>';
                     }
                     ?>
                  </select>
               </div>
               <div class="form-group">
                  <label>Besar Potongan Gaji (%)</label>
                  <input type="number" placeholder="Masukkan Berapa Persen Potongan Gaji" id="data_potongan_edit" name="potongan" class="form-control field" required="required">
               </div>
               <div class="form-group">
                  <label>Shift</label>
                  <?php
                  $sel2 = array(null);
                  $ex2 = array('class'=>'form-control select2','style'=>'width:100%;','required'=>'required','id'=>'data_shift_edit');
                  echo form_dropdown('shift',$shift,$sel2,$ex2);
                  ?>
               </div>
            </div>
            <div class="modal-footer">
               <button type="button" onclick="do_edit()" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
               <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
            </div>
         </form>
      </div>
   </div>
</div>
<div id="modal_delete_partial"></div>
<script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
<script type="text/javascript">
   var url_select="<?php echo base_url('global_control/select2_global');?>";
   var table="master_shift";
   var column="id_master_shift";
   $(document).ready(function(){
      refreshCode();
      $('#table_data').DataTable( {
         ajax: {
            url: "<?php echo base_url('master/master_shift/view_all/')?>",
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
         {   targets: [9,10],
            width: '5%',
            render: function ( data, type, full, meta ) {
               return '<center>'+data+'</center>';
            }
         },
         ]
      });
      $("#for_hari").change(function(){
         var length = $('#for_hari :selected').length;
         if (length < 7) {
            $("#all_days").iCheck('uncheck');
         }
      });
      $('#all_days').change(function(){
      // $("#all_days").on('ifChanged', function () {
         var callback=null;
         if ($('#all_days:checked').length == $('#all_days').length) {
            callback=[1,2,3,4,5,6,7];
            $('#for_hari').val(callback).trigger('change');
         }
      });
   });
   function refreshCode() {
      kode_generator("<?php echo base_url('master/master_shift/kode');?>",'data_kode_add');
   }
   function view_modal(id) {
      var data={id_master_shift:id};
      var callback=getAjaxData("<?php echo base_url('master/master_shift/view_one')?>",data);  
      $('#view').modal('show');
      $('.header_data').html(callback['nama']);
      $('#data_kode_view').html(callback['kode_master_shift']);
      $('#data_name_view').html(callback['nama']);
      $('#data_mulai_view').html(callback['jam_mulai']);
      $('#data_selesai_view').html(callback['jam_selesai']);
      $('#data_istirahat_mulai_view').html(callback['jam_istirahat_mulai']);
      $('#data_istirahat_selesai_view').html(callback['jam_istirahat_selesai']);
      $('#data_shift_view').html(callback['shift']);
      $('#data_potongan_view').html(callback['potongan_view']);
      var status = callback['status'];
      if(status==1){
         var statusval = '<b class="text-success">Aktif</b>';
      }else{
         var statusval = '<b class="text-danger">Tidak Aktif</b>';
      }
      $('#data_status_view').html(statusval);
      $('#data_hari_view').html(callback['hari']);
      $('#data_create_date_view').html(callback['create_date']+' WIB');
      $('#data_update_date_view').html(callback['update_date']+' WIB');
      $('input[name="data_id_view"]').val(callback['id']);
      $('#data_create_by_view').html(callback['nama_buat']);
      $('#data_update_by_view').html(callback['nama_update']);
      var cstm = callback['kode_master_shift'];
      if(cstm=='CSTM'){
         $('#btn_edit').hide();
      }else{
         $('#btn_edit').show();
      }
   }
   function edit_modal() {
      var id = $('input[name="data_id_view"]').val();
      var data={id_master_shift:id};
      var callback=getAjaxData("<?php echo base_url('master/master_shift/view_one')?>",data); 
      $('#view').modal('toggle');
      setTimeout(function () {
         $('#edit').modal('show');
      },600); 
      $('.header_data').html(callback['nama']);
      $('#data_id_edit').val(callback['id']);
      $('#data_kode_edit_old').val(callback['kode_master_shift']);
      $('#data_kode_edit').val(callback['kode_master_shift']);
      $('#data_name_edit').val(callback['nama']);
      $('#data_mulai_edit').val(callback['jam_mulai']);
      $('#data_selesai_edit').val(callback['jam_selesai']);
      $('#istirahat_mulai_edit').val(callback['jam_istirahat_mulai']);
      $('#istirahat_selesai_edit').val(callback['jam_istirahat_selesai']);
      $('#data_potongan_edit').val(callback['potongan']);
      $('#data_shift_edit').val(callback['kode_shift']).trigger('change');
      $('#data_hari_edit').val(callback['hari_e']).trigger('change');
      $("#data_hari_edit").change(function(){
         var length = $('#data_hari_edit :selected').length;
         if (length < 7) {
            $("#all_days_edit").iCheck('uncheck');
         }
      });
      // $("#all_days_edit").on('ifChecked', function () {
      $('#all_days_edit').change(function(){
         var callback=null;
         if ($('#all_days_edit:checked').length == $('#all_days_edit').length) {
               callback=[1,2,3,4,5,6,7];
               $('#data_hari_edit').val(callback).trigger('change');
         }else{
               $('#data_hari_edit').val(callback).trigger('change');             
         }
      });
   }
   function delete_modal(id) {
      var data={id_master_shift:id};
      var callback=getAjaxData("<?php echo base_url('master/master_shift/view_one')?>",data);
      var datax={table:table,column:column,id:id,nama:callback['nama']};
      loadModalAjax("<?php echo base_url('pages/load_modal_delete')?>",'modal_delete_partial',datax,'delete');
   }
   function do_status(id,data) {
      var data_table={status:data};
      var where={id_master_shift:id};
      var datax={table:table,where:where,data:data_table};
      submitAjax("<?php echo base_url('global_control/change_status')?>",null,datax,null,null,'status');
      $('#table_data').DataTable().ajax.reload();
   }
   function do_edit(){
      if($("#form_edit")[0].checkValidity()) {
         submitAjax("<?php echo base_url('master/edt_master_shift')?>",'edit','form_edit',null,null);
         $('#table_data').DataTable().ajax.reload();
      }else{
         notValidParamx();
      } 
   }
   function do_add(){
      if($("#form_add")[0].checkValidity()) {
         submitAjax("<?php echo base_url('master/add_master_shift')?>",null,'form_add',"<?php echo base_url('master/master_shift/kode');?>",'data_kode_add');
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