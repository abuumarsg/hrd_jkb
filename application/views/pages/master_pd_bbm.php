<div class="content-wrapper">
   <section class="content-header">
      <h1>
         <a class="back" onclick="window.history.back()"><i class="fa fa-chevron-circle-left "></i></a>
         <i class="fa fa-database"></i> Master Data 
         <small>Daftar Intensif Perjalanan DInas</small>
      </h1>
      <ol class="breadcrumb">
         <li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
         <li><a href="<?php echo base_url('pages/master_perjalanan_dinas');?>"><i class="fas fa-car"></i> Master Perjalanan Dinas</a></li>
         <li class="active">Daftar Intensif Perjalanan Dinas</li>
      </ol>
   </section>
   <section class="content">
      <div class="row">
         <div class="col-md-12">
            <div class="box box-success">
               <div class="box-header with-border">
                  <h3 class="box-title"><i class="fas fa-gas-pump"></i> Daftar Intensif Perjalanan Dinas </h3>
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
                                       <label>Kode Kendaraan</label>
                                       <input type="text" placeholder="Masukkan Kode Kendaraan" id="kode_bbm_add" name="kode_bbm" class="form-control" required="required" value="" readonly="readonly">
                                    </div>
                                    <input type="hidden" name="kode_kendaraan" id="data_kendaraan_add" value="<?php echo $this->codegenerator->decryptChar($this->uri->segment(3));?>">
                                    <div class="form-group" id="kendaraan_umum_add" style="display: none;">
                                       <label>Pilih Kendaraan Umum</label>
                                       <?php
                                       $kendaraan_umum[null] = 'Pilih Data';
                                       $sel = [null];
                                       $exsel = array('class'=>'form-control select2','placeholder'=>'Kendaraan Umum','id'=>'data_kendaraan_umum_add','required'=>'required','style'=>'width:100%');
                                       echo form_dropdown('kendaraan_umum',$kendaraan_umum,$sel,$exsel);
                                       ?>
                                    </div>
                                    <div class="form-group">
                                       <label>Dari Jarak (km)</label>
                                       <input type="number" min="0" name="jarak_min" class="form-control" placeholder="Jarak Minimal" required="required">
                                    </div>
                                    <div class="form-group">
                                       <label>Sampai Jarak (km)</label>
                                       <input type="number" min="0" name="jarak_mak" class="form-control" placeholder="Jarak Maksimal" required="required">
                                    </div>
                                    <div class="form-group">
                                       <label>Nominal</label>
                                       <input type="text" name="nominal" class="form-control input-money" placeholder="Besaran Intensif BBM" required="required">
                                    </div>
                                    <div class="form-group">
                                       <label>Keterangan</label>
                                       <textarea name="keterangan" class="form-control" placeholder="Keterangan"></textarea>
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
                                 <th>Kode</th>
                                 <th>Nama Kendaraan</th>
                                 <th>Dari Jarak</th>
                                 <th>Sampai Jarak</th>
                                 <th>Nominal</th>
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
                     <label class="col-md-6 control-label">Nama Kendaraan</label>
                     <div class="col-md-6" id="data_nama_view"></div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Jarak (km)</label>
                     <div class="col-md-6" id="data_jarak_view"></div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Nominal</label>
                     <div class="col-md-6" id="data_nominal_view"></div>
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
<?php if (in_array($access['l_ac']['edt'], $access['access'])) { ?>
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
                  <input type="text" placeholder="Masukkan Kode Grade" id="data_kode_edit" name="kode" value="" class="form-control" required="required" readonly="readonly">
               </div>
               <div class="form-group">
                  <label>Pilih Kendaraan</label>
                  <select class="form-control select2" name="kode_kendaraan" id="data_kendaraan_edit" onchange="kendaraanPD(this.value)" style="width: 100%;"></select>
               </div>
               <div class="form-group" id="nama_kendaraan_umum">
                  <label>Pilih Kendaraan Umum</label>
                  <?php
                  $kendaraan_umum[null] = 'Pilih Data';
                  $sel2 = [null];
                  $exsel2 = array('class'=>'form-control select2','placeholder'=>'Kendaraan Umum','id'=>'kendaraan_umum_edit','required'=>'required','style'=>'width:100%');
                  echo form_dropdown('kendaraan_umum',$kendaraan_umum,$sel2,$exsel2);
                  ?>
               </div>
               <input type="hidden" name="kode_kendaraan_old" id="kode_kendaraan_old" value="">
               <input type="hidden" name="kendaraan_umum_old" id="kendaraan_umum_old" value="">
               <input type="hidden" name="jarak_min_old" id="jarak_min_old" value="">
               <input type="hidden" name="jarak_mak_old" id="jarak_mak_old" value="">
               <div class="form-group">
                  <label>Dari Jarak (km)</label>
                  <input type="number" min="0" name="jarak_min" id="data_dari_edit" class="form-control" placeholder="Jarak Minimal" required="required">
               </div>
               <div class="form-group">
                  <label>Sampai Jarak (km)</label>
                  <input type="number" min="0" name="jarak_mak" id="data_sampai_edit" class="form-control" placeholder="Jarak Maksimal" required="required">
               </div>
               <div class="form-group">
                  <label>Nominal</label>
                  <input type="text" placeholder="Masukkan Nominal" id="data_nominal_edit" name="nominal" value="" class="form-control input-money" required="required">
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
<?php } ?>
<div id="modal_delete_partial"></div>
<script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
<script type="text/javascript">
   var url_select="<?php echo base_url('global_control/select2_global');?>";
   var table="master_pd_bbm";
   var column="id_pd_bbm";
   $(document).ready(function(){
      refreshCode();
      $('#table_data').DataTable( {
         ajax: {
            url: "<?php echo base_url('master/master_bbm_kendaraan/view_all/'.$this->uri->segment(3))?>",
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
         {   targets: 7,
            width: '7%',
            render: function ( data, type, full, meta ) {
               return '<center>'+data+'</center>';
            }
         },
         {   targets: 8, 
            width: '10%',
            render: function ( data, type, full, meta ) {
               return '<center>'+data+'</center>';
            }
         },
         ]
      });
      var name = $('#data_kendaraan_add').val();
      if(name == 'KPD0001') {
         $('#kendaraan_umum_add').show();
         $('#data_kendaraan_umum_add').attr('required','required');
      }else {
         $('#kendaraan_umum_add').hide();
         $('#data_kendaraan_umum_add').removeAttr('required');
      }
   });
   function refreshCode() {
      kode_generator("<?php echo base_url('master/master_bbm_kendaraan/kode');?>",'kode_bbm_add');
   }
   function kendaraanPD(f) {
      setTimeout(function () {
         var name = $('#data_kendaraan_edit').val();
         if(name == 'KPD0001') {
            $('#nama_kendaraan_umum').show();
            $('#kendaraan_umum_edit').attr('required','required');
         }else {
            $('#nama_kendaraan_umum').hide();
            $('#kendaraan_umum_edit').removeAttr('required');
         }
      },100);
   }
   function view_modal(id) {
      var data={id_pd_bbm:id};
      var callback=getAjaxData("<?php echo base_url('master/master_bbm_kendaraan/view_one')?>",data);  
      $('#view').modal('show');
      $('.header_data').html(callback['kode']);
      $('#data_kode_view').html(callback['kode']);
      $('#data_nama_view').html(callback['nama']);
      $('#data_jarak_view').html(callback['jarak']);
      $('#data_nominal_view').html(callback['nominal']);
      $('#data_keterangan_view').html(callback['v_keterangan']);
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
      select_data('data_kendaraan_edit',url_select,'master_pd_kendaraan','kode','nama');
      var id = $('input[name="data_id_view"]').val();
      var data={id_pd_bbm:id};
      var callback=getAjaxData("<?php echo base_url('master/master_bbm_kendaraan/view_one')?>",data); 
      $('#view').modal('toggle');
      setTimeout(function () {
         $('#edit').modal('show');
      },600); 
      $('.header_data').html(callback['nama']);
      $('#data_id_edit').val(callback['id']);
      $('#data_kode_edit_old').val(callback['kode']);
      $('#data_kode_edit').val(callback['kode']);
      $('#data_dari_edit').val(callback['dari']);
      $('#data_sampai_edit').val(callback['sampai']);
      $('#data_nominal_edit').val(callback['nmnl']);
      $('#data_keterangan_edit').val(callback['keterangan']);
      $('#data_kendaraan_edit').val(callback['kode_k']).trigger('change');
      $('#kendaraan_umum_edit').val(callback['e_kendaraan_umum']).trigger('change');
      $('#kode_kendaraan_old').val(callback['kode_k']);
      $('#kendaraan_umum_old').val(callback['e_kendaraan_umum']);
      $('#jarak_min_old').val(callback['dari']);
      $('#jarak_mak_old').val(callback['sampai']);
   }
   function delete_modal(id) {
      var data={id_pd_bbm:id};
      var callback=getAjaxData("<?php echo base_url('master/master_bbm_kendaraan/view_one')?>",data);
      var datax={table:table,column:column,id:id,nama:callback['nama']};
      loadModalAjax("<?php echo base_url('pages/load_modal_delete')?>",'modal_delete_partial',datax,'delete');
   }
   function do_status(id,data) {
      var data_table={status:data};
      var where={id_pd_bbm:id};
      var datax={table:table,where:where,data:data_table};
      submitAjax("<?php echo base_url('global_control/change_status')?>",null,datax,null,null,'status');
      $('#table_data').DataTable().ajax.reload();
   }
   function do_edit(){
      if($("#form_edit")[0].checkValidity()) {
         submitAjax("<?php echo base_url('master/edit_master_bbm_kendaraan')?>",'edit','form_edit',null,null);
         $('#table_data').DataTable().ajax.reload();
      }else{
         notValidParamx();
      } 
   }
   function do_add(){
      if($("#form_add")[0].checkValidity()) {
         submitAjax("<?php echo base_url('master/add_master_bbm_kendaraan')?>",null,'form_add',"<?php echo base_url('master/master_pd_kendaraan/kode');?>",'kode_bbm_add');
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