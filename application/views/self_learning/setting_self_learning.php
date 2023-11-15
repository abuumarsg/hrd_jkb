<div class="content-wrapper">
   <section class="content-header">
      <h1>
         <i class="fas fa-chalkboard-teacher"></i> Materi Learning
      </h1>
      <ol class="breadcrumb">
         <li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
         <li class="active">Materi Learning</li>
      </ol>
   </section>
   <section class="content">
      <div class="row">
         <div class="col-md-12">
            <div class="box box-success">
               <div class="box-header with-border">
                  <h3 class="box-title"><i class="fas fa-chalkboard-teacher"></i> Materi Learning</h3>
                  <div class="box-tools pull-right">
                     <button class="btn btn-box-tool" onclick="reload_table('table_data')" data-toggle="tooltip" title="Refresh Table"><i class="fas fa-sync"></i></button>
                     <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                     <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i class="fa fa-times"></i></button>
                  </div>
               </div>
               <div class="box-body">
                  <div class="row">
                     <div class="col-md-12">
                        <div id="accordion">
                           <div class="panel">
                              <div class="row">
                                 <div class="col-md-12">
                                    <div class="pull-left">
                                       <?php if (in_array($access['l_ac']['add'], $access['access'])) {
                                          echo '<a href="#add" data-toggle="collapse"  data-parent="#accordion" class="btn btn-success"><i class="fa fa-plus"></i> Tambah Materi</a>';
                                          // echo '<a href="#add_master" data-toggle="collapse" data-parent="#accordion" class="btn btn-info"><i class="fa fa-plus"></i> Tambah Master Grade</a>';
                                       }?>
                                      <!-- <div class="btn-group">
                                        <button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown"><i class="fas fa-file-excel-o"></i> Export & Import
                                        <span class="fa fa-caret-down"></span></button>
                                        <ul class="dropdown-menu">
                                          <li><a href="<?php echo base_url('rekap/export_template_grade');?>">Export Template Grade</a></li>
                                          <li><a data-toggle="modal" data-target="#import">Import Master Grade</a></li>
                                        </ul>
                                      </div> -->
                                    </div>
                                    <div class="pull-right" style="font-size: 8pt;">
                                       <i class="fa fa-toggle-on stat scc"></i> Aktif<br>
                                       <i class="fa fa-toggle-off stat err"></i> Tidak Aktif
                                    </div>
                                 </div>
                              </div>
                              <?php if(in_array($access['l_ac']['add'], $access['access'])){?>
                                 <div class="collapse" id="add">
                                    <div class="col-md-2"></div>
                                    <div class="col-md-8">
                                       <form id="form_add">
                                          <div class="form-group">
                                             <label>Kode Materi</label>
                                             <input type="text" placeholder="Masukkan Kode Grade" id="data_kode_add" name="kode" class="form-control" required="required" value="" readonly="readonly">
                                          </div>
                                          <div class="form-group">
                                             <label>Nama Materi</label>
                                             <input type="text" placeholder="Masukkan Nama Materi" id="data_name_add" name="nama" class="form-control field" required="required">
                                          </div>
                                          <div class="form-group">
                                             <label>Pilih Bagian</label>
                                             <select class="form-control select2" id="bagian_add" name="bagian[]" style="width: 100%;" multiple="multiple"></select>
                                          </div>
                                          <div class="form-group">
                                             <label>Waktu Mengerjakan Materi (menit) </label>
                                             <input type="text" placeholder="Masukkan Waktu Mengerjakan Materi" name="time" class="form-control field" required="required">
                                          </div>
                                          <div class="form-group">
                                             <button type="button" onclick="do_add()" id="btn_add" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
                                          </div>
                                       </form>
                                    </div>
                                 </div>
                                 <div class="collapse" id="add_master">
                                    <div class="col-md-2"></div>
                                    <div class="col-md-8">
                                       <form id="form_add_master">
                                          <div class="form-group">
                                             <label>Kode Grade</label>
                                             <input type="text" placeholder="Masukkan Kode Grade" id="data_kode_master_add" name="kode" class="form-control" required="required" value="" readonly="readonly">
                                          </div>
                                          <div class="form-group">
                                             <label>Materi</label>
                                             <select class="form-control select2" name="induk_grade" id="data_induk_add" style="width: 100%;" required="required"></select>
                                          </div>
                                          <div class="form-group">
                                             <label>Nama Grade</label>
                                             <input type="text" placeholder="Masukkan Nama Grade" id="data_name_master_add" name="nama" class="form-control field" required="required">
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
                                             <label>Tahun</label>
                                             <select class="form-control select2" id="tahun" name="tahun" style="width: 100%;">
                                                <option></option>
                                                <?php
                                                $year = $this->formatter->getYear();
                                                foreach ($year as $yk => $yv) {
                                                   echo '<option value="'.$yk.'" '.$select.'>'.$yv.'</option>'; } ?>
                                             </select>
                                          </div>
                                          <div class="form-group">
                                             <label>Dokumen</label>
                                             <select class="form-control select2" name="dokumen" id="data_dokumen_add" style="width: 100%;"></select>
                                          </div>
                                          <div class="form-group">
                                             <button type="button" onclick="do_add_master()" id="btn_add_master" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
                                          </div>
                                       </form>
                                    </div>
                                 </div>
                              <?php } ?>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="row">
                     <div class="col-md-12">
                        <div class="callout callout-info"><label><i class="fa fa-info-circle"></i> Bantuan</label><br>
                        <ul>
                           <li>Klik pada kolom <b>Kode Materi</b> untuk menambah dan mengupdate soal-soal pelatihan</li>
                           <li>Klik pada kolom <b>Jumlah Soal</b> untuk melihat preview soal pelatihan</li>
                           <li>Klik pada kolom <b>Jumlah Bagian</b> untuk melihat preview bagian mana saja yang dapat pelatihan tersebut</li>
                        </ul>
                        </div>
                        <table id="table_data" class="table table-bordered table-striped table-responsive" width="100%">
                           <thead>
                              <tr>
                                 <th>No.</th>
                                 <th>Kode Materi</th>
                                 <th>Nama Materi</th>
                                 <th>Jumlah Soal</th>
                                 <th>Jumlah File Materi</th>
                                 <th>Jumlah Bagian</th>
                                 <th>Waktu</th>
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
<!-- <div class="modal fade" id="import" role="dialog">
   <div class="modal-dialog">
      <div class="modal-content text-center">
         <div class="modal-header">
            <button type="button" class="close all_btn_import" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Import Data Dari Excel</h4>
         </div>
         <form id="form_import" action="#">
            <div class="modal-body">
               <div class="callout callout-info text-left">
                  <b><i class="fa fa-bullhorn"></i> Bantuan</b><br>
                  <ul>
                     <li>Pastikan cell pada file Excel tidak ada yang di <b>MERGE</b>, jika ada yang di <b>MERGE</b> harap melakukan <b style="color: red">UNMERGE</b> terlebih dahulu!</li>
                     <li>Gunakan File Template Excel Master Grade yang telah anda Download dari <b>"Export Template Grade"</b></li>
                     <li>Jika Menambah Data Grade Melalui Template, Pastikan kolom <b>No, Kode Induk Grade</b> dan <b>Nama Grade</b> Pada Template Terisi</li>
                  </ul>
               </div>
               <p class="text-muted">File Data Presensi harus tipe *.xls, *.xlsx, *.csv, *.ods dan *.ots</p>
               <input id="uploadFilex" placeholder="Nama File" readonly="readonly" class="form-control" required="required">
               <span class="input-group-btn">
                  <div class="fileUpload btn btn-warning btn-flat">
                     <span><i class="fa fa-folder-open"></i> Pilih File</span>
                     <input id="uploadBtnx" type="file" class="upload" name="file" onchange="checkFile('#uploadBtnx','#uploadFilex','#save')" />
                  </div>
               </span>
            </div> 
            <div class="modal-footer">
               <div id="progress2" style="float: left;"></div>
               <button class="btn btn-primary all_btn_import" id="save" type="button" disabled style="margin-right: 4px;"><i class="fa fa-check-circle"></i> Upload</button>
               <button id="savex" type="submit" style="display: none;"></button>
               <button type="button" class="btn btn-default all_btn_import" data-dismiss="modal">Kembali</button>
            </div>
         </form>
      </div>
   </div>
</div> -->
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
            <?php if (in_array($access['l_ac']['edt'], $access['access'])) {
               echo '<button type="submit" class="btn btn-info" onclick="edit_modal()"><i class="fa fa-edit"></i> Edit</button>';
            }?>
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
                  <label>Kode Materi</label>
                  <input type="text" placeholder="Masukkan Kode Materi" id="data_kode_edit" name="kode" value="" class="form-control" required="required" readonly="readonly">
               </div>
               <div class="form-group">
                  <label>Nama Materi</label>
                  <input type="text" placeholder="Masukkan Nama Materi" id="data_name_edit" name="nama" value="" class="form-control" required="required">
               </div>
               <div class="form-group">
                  <label>Pilih Bagian</label>
                  <select class="form-control select2" id="bagian_edit" name="bagian[]" style="width: 100%;" multiple="multiple"></select>
               </div>
               <div class="form-group">
                  <label>Waktu Mengerjakan Materi (menit) </label>
                  <input type="text" placeholder="Masukkan Waktu Mengerjakan Materi" id="waktu_edit" name="time" class="form-control field" required="required">
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
<div id="modal_delete_partial"></div>
<script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
<script type="text/javascript">
  var url_select="<?php echo base_url('global_control/select2_global');?>";
   var table="learn_materi";
   var column="id";
   $(document).ready(function(){
      refreshCode();
		getSelect2("<?php echo base_url('master/master_bagian/get_select2')?>",'bagian_add');
      $('#table_data').DataTable( {
         ajax: {
            url: "<?php echo base_url('learning/getMateriLearning/view_all/')?>",
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
            width: '10%',
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
      $('#save').click(function(){
           $('.all_btn_import').attr('disabled','disabled');
           $('#progress2').html('<i class="fa fa-spinner fa-pulse fa-fw"></i> Mohon Tunggu, Data sedang di upload....')
           setTimeout(function () {
            $('#savex').click();
           },1000);
      })
      $('#form_import').submit(function(e){
         e.preventDefault();
         var data_add = new FormData(this);
         var urladd = "<?php echo base_url('master/import_master_grade'); ?>";
         submitAjaxFile(urladd,data_add,'#import','#progress2','.all_btn_import');
         $('#table_data').DataTable().ajax.reload(function (){
            Pace.restart();
         });
      });
   });
   function refreshCode() {
      kode_generator("<?php echo base_url('learning/getMateriLearning/kode');?>",'data_kode_add');
   }
   function view_modal(id) {
      var data={id:id};
      var callback=getAjaxData("<?php echo base_url('learning/getMateriLearning/view_one')?>",data);  
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
   function edit_modal() {
      // kode_generator("<?php echo base_url('learning/getMateriLearning/kode');?>",'bagian_edit');
		getSelect2("<?php echo base_url('master/master_bagian/get_select2')?>",'bagian_edit');
      var id = $('input[name="data_id_view"]').val();
      var data={id:id};
      var callback=getAjaxData("<?php echo base_url('learning/getMateriLearning/view_one')?>",data); 
      $('#view').modal('toggle');
      setTimeout(function () {
         $('#edit').modal('show');
      },600); 
      $('.header_data').html(callback['nama']);
      $('#data_id_edit').val(callback['id']);
      $('#data_kode_edit_old').val(callback['kode']);
      $('#data_kode_edit').val(callback['kode']);
      $('#data_name_edit').val(callback['nama']);
      $('#bagian_edit').val(callback['bagian_edit']).trigger('change');
      $('#waktu_edit').val(callback['waktu']);
   }
   function delete_modal(id) {
      var data={id:id};
      var callback=getAjaxData("<?php echo base_url('learning/getMateriLearning/view_one')?>",data);
      var datax={table:table,column:column,id:id,nama:callback['nama']};
      loadModalAjax("<?php echo base_url('pages/load_modal_delete')?>",'modal_delete_partial',datax,'delete');
   }
   function do_status(id,data) {
      var data_table={status:data};
      var where={id:id};
      var datax={table:table,where:where,data:data_table};
      submitAjax("<?php echo base_url('global_control/change_status')?>",null,datax,null,null,'status');
      $('#table_data').DataTable().ajax.reload();
   }
   function do_edit(){
      if($("#form_edit")[0].checkValidity()) {
         submitAjax("<?php echo base_url('learning/edit_setting_materi')?>",'edit','form_edit',null,null);
         $('#table_data').DataTable().ajax.reload();
      }else{
         notValidParamx();
      } 
   }
   function do_add(){
      if($("#form_add")[0].checkValidity()) {
         submitAjax("<?php echo base_url('learning/add_setting_materi')?>",null,'form_add',null,null);
         $('#table_data').DataTable().ajax.reload(function (){
            Pace.restart();
         });
         $('#form_add')[0].reset();
         refreshCode();
         $('#bagian_add').val(null).trigger('change');
      }else{
         notValidParamx();
      } 
   } 
   // function do_add_master(){
   //    if($("#form_add_master")[0].checkValidity()) {
   //       submitAjax("<?php echo base_url('master/add_grade')?>",null,'form_add_master',null,null);
   //       $('#table_data').DataTable().ajax.reload(function (){
   //          Pace.restart();
   //       });
   //       $('#form_add_master')[0].reset();
   //       refreshCode();
   //    }else{
   //       notValidParamx();
   //    } 
   // } 
   function checkFile(idf,idt,btnx) {
      var fext = ['xls', 'xlsx', 'csv', 'ods', 'ots'];
      pathFile(idf,idt,fext,btnx);
   }
</script>