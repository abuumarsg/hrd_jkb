<div class="content-wrapper">
   <section class="content-header">
      <h1>
         <i class="fa fa-users"></i> Data
         <small>Karyawan</small>
      </h1>
      <ol class="breadcrumb">
         <li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
         <li class="active"><i class="fa fa-users"></i> Data Karyawan</li>
      </ol>
   </section>
   <section class="content">
      <div class="row">
         <div class="col-md-12">
            <div class="box box-primary">
               <div class="box-header with-border">
                  <h3 class="box-title"><i class="fa fa-search"></i> Advance Filter Data</h3>
                  <div class="box-tools pull-right">
                     <button class="btn btn-box-tool" onclick="refreshData()" data-toggle="tooltip" title="Refresh Data"><i class="fas fa-sync"></i></button>
                     <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                     <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i class="fa fa-times"></i></button>
                  </div>
               </div>
               <div style="padding-top: 10px;">
                  <form id="form_filter">
                     <input type="hidden" name="param" value="all">
                     <input type="hidden" name="mode" value="data">
                     <div class="box-body">
                        <div class="col-md-6">
                           <div class="form-group">
                              <label>Pilih Bagian</label>
                              <select class="form-control select2" id="bagian_export" name="bagian_export" style="width: 100%;"></select>
                           </div>
                           <div class="form-group">
                              <label>Pilih Lokasi Kerja</label>
                              <select class="form-control select2" id="unit_export" name="unit_export" style="width: 100%;"></select>
                           </div>
                        </div>
                        <div class="col-md-6">
                           <div class="form-group">
                              <label>Bulan Masuk</label>
                              <select class="form-control select2" id="bulan_export" name="bulan_export" style="width: 100%;">
                                 <option></option>
                                 <?php
                                 for ($i=1; $i <= 12; $i++) { 
                                    echo '<option value="'.$this->formatter->zeroPadding($i).'" '.$select.'>'.$this->formatter->getNameOfMonth($i).'</option>'; } ?>
                              </select>
                           </div>
                           <div class="form-group">
                              <label>Tahun Masuk</label>
                              <select class="form-control select2" id="tahun_export" name="tahun_export" style="width: 100%;">
                                 <option></option>
                                 <?php
                                 $year = $this->formatter->getYearAll();
                                 foreach ($year as $yk => $yv) {
                                    echo '<option value="'.$yk.'" '.$select.'>'.$yv.'</option>';
                                 }
                                 ?>
                              </select>
                           </div>
                        </div>
                     </div>
                     <div class="box-footer">
                        <div class="col-md-12">
                           <div class="pull-right">
                              <button type="button" onclick="tableData('search')" class="btn btn-success"><i class="fa fa-eye"></i> Lihat Data</button>
                           </div>
                        </div>
                     </div>
                  </form>
               </div>
            </div>
         </div>
      </div>
      <div class="row">
         <div class="col-md-12">
            <div class="box box-info">
               <div class="box-header with-border">
                  <h3 class="box-title"><i class="fa fa-users"></i> Data Seluruh Karyawan</h3>
                  <div class="box-tools pull-right">
                     <button class="btn btn-box-tool" onclick="reload_table('table_data')" data-toggle="tooltip" title="Refresh Table"><i class="fas fa-sync"></i></button>
                     <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                     <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i class="fa fa-times"></i></button>
                  </div>
               </div>
               <div class="box-body">
                  <div class="row">
                     <div class="col-md-12">
                        <?php
                        if (in_array($access['l_ac']['add'], $access['access'])) {
                           echo '<a href="'.base_url('pages/add_employee').'" class="btn btn-success btn-flat" ><i class="fa fa-user"></i> Tambah Karyawan</a>  ';
                        }
                        if (in_array($access['l_ac']['imp'], $access['access'])) {
                           echo '<button class="btn btn-primary" type="button" data-toggle="modal" data-target="#import" aria-expanded="false" aria-controls="import" style="margin-right: 2px;"><i class="fas fa-cloud-upload-alt"></i> Import</button>';
                        } ?>
                        <?php if (in_array($access['l_ac']['rkp'], $access['access'])) { ?>
                        <div class="btn-group">
                           <button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown"><i class="fas fa-file-excel-o"></i> Export
                           <span class="fa fa-caret-down"></span></button>
                           <ul class="dropdown-menu">
                              <li><a href="<?php echo base_url('rekap/export_template_karyawan');?>">Export Template</a></li>
                              <li><a onclick="rekap()">Export Data Karyawan</a></li>
                              <li><a onclick="rekap_thr()">Export Karyawan untuk THR</a></li>
                           </ul>
                        </div>
                        <?php } ?>
                        <?php if (in_array($access['l_ac']['rkp'], $access['access'])) { ?>
                        <div class="btn-group">
                           <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown"><i class="fas fa-file-export"></i> Export Import Gaji
                           <span class="fa fa-caret-down"></span></button>
                           <ul class="dropdown-menu">
                              <li><a href="<?php echo base_url('rekap/exportTemplateGajiNonMatrix');?>"><i class="fa fa-download"></i> Export Data Gaji Non Matrix</a></li>
                              <li><a  data-toggle="modal" data-target="#importNonMatrix"><i class="fa fa-upload"></i> Import Data Gaji Non Matrix</a></li>
                              <hr>
                              <li><a href="<?php echo base_url('rekap/exportTemplateGajibpjs');?>"><i class="fa fa-download"></i> Export Data Gaji Perhitungan BPJS</a></li>
                              <li><a  data-toggle="modal" data-target="#importGajibpjs"><i class="fa fa-upload"></i> Import Data Gaji Perhitungan BPJS</a></li>
                           </ul>
                        </div>
                        <?php }
                        if (in_array($access['l_ac']['imp'], $access['access'])) {
                        ?>
                           <div class="row">
                              <div class="col-md-2"></div>
                              <div class="col-md-8">
                                 <div class="modal fade" id="import" role="dialog">
                                    <div class="modal-dialog">
                                       <div class="modal-content text-center">
                                          <div class="modal-header">
                                             <button type="button" class="close all_btn_import" data-dismiss="modal">&times;</button>
                                             <h4 class="modal-title">Import Data Dari Excel</h4>
                                          </div>
                                          <form id="form_import" action="#">
                                             <div class="modal-body">
                                                <p style="color:red;">File Data Template Master Indikator harus tipe *.xls, *.xlsx, *.csv, *.ods dan *.ots</p>
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
                                                <button class="btn btn-flat btn-primary all_btn_import" id="save" type="button" disabled><i class="fa fa-check-circle"></i> Upload</button>
                                                <button id="savex" type="submit" style="display: none;"></button>
                                                <button type="button" class="btn btn-default all_btn_import" data-dismiss="modal">Kembali</button>
                                             </div>
                                          </form>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        <?php } ?>
                     </div>
                  </div>
                  <br>
                  <div class="row">
                     <div class="col-md-12">
                        <div class="callout callout-info"><label><i class="fa fa-info-circle"></i> Bantuan</label><br>Pilih <b>Nomor Induk</b> untuk melakukan update data karyawan dan pilih button <button type="button" class="btn btn-info btn-sm" disabled><i class="fa fa-info-circle" data-toggle="tooltip" title="Detail Data"></i></button> untuk melihat data karyawan secara umum</div>
                        <table id="table_data" class="table table-bordered table-striped" width="100%">
                           <thead>
                              <tr>
                                 <th>No.</th>
                                 <th>Nomor Induk</th>
                                 <th>Nama Karyawan</th>
                                 <th>Jabatan</th>
                                 <th>Level Jabatan</th>
                                 <th>Bagian</th>
                                 <th>Departemen</th>
                                 <?php
                                    // foreach ($d_level as $d){
                                    //    echo '<th>'.$d->nama.'</th>';
                                    // }
                                 ?>
                                 <th>Lokasi Kerja</th>
                                 <th>Grade</th>
                                 <th>ID Finger</th>
                                 <th>Tanggal Masuk</th>
                                 <th>Masa Kerja</th>
                                 <th>Tanggal Lahir</th>
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
               <div class="col-md-12">
                  <div id="data_foto_view"></div>
               </div>
            </div>
            <br>
            <hr>
            <div class="row">
               <div class="col-md-6">
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Nomer Induk</label>
                     <div class="col-md-6" id="data_nik_view"></div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Nama Karyawan</label>
                     <div class="col-md-6" id="data_nama_view"></div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Jabatan</label>
                     <div class="col-md-6" id="data_jabatan_view"></div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Lokasi Kerja</label>
                     <div class="col-md-6" id="data_loker_view"></div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Grade</label>
                     <div class="col-md-6" id="data_grade_view"></div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Tanggal Masuk</label>
                     <div class="col-md-6" id="data_tglmasuk_view"></div>
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
            <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
         </div>
      </div>
   </div>
</div>
<div class="modal fade" id="importNonMatrix" role="dialog">
   <div class="modal-dialog">
      <div class="modal-content text-center">
         <div class="modal-header">
            <button type="button" class="close all_btn_import" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Import Data Dari Excel</h4>
         </div>
         <form id="form_import_non_matrix" action="#">
            <div class="modal-body">
               <div class="callout callout-info text-left">
                  <b><i class="fa fa-bullhorn"></i> Bantuan</b><br>
                  <ul>
                     <li>Pastikan cell pada file Excel tidak ada yang di <b>MERGE</b>, jika ada yang di <b>MERGE</b> harap melakukan <b style="color: red">UNMERGE</b> terlebih dahulu!</li>
                     <li>Gunakan File Template Import Gaji Non Matrix yang telah anda Download dari <b>"Export Data Gaji Non Matrix"</b></li>
                  </ul>
               </div>
               <p class="text-muted">File harus tipe *.xls, *.xlsx, *.csv, *.ods dan *.ots</p>
               <input id="uploadFilexNonMatrix" placeholder="Nama File" readonly="readonly" class="form-control" required="required">
               <span class="input-group-btn">
                  <div class="fileUpload btn btn-warning btn-flat">
                     <span><i class="fa fa-folder-open"></i> Pilih File</span>
                     <input id="uploadBtnxNonMatrix" type="file" class="upload" name="file" onchange="checkFile('#uploadBtnxNonMatrix','#uploadFilexNonMatrix','#saveNonMatrix')" />
                  </div>
               </span>
            </div> 
            <div class="modal-footer">
               <div id="progressNonMatrix" style="float: left;"></div>
               <button class="btn btn-primary all_btn_import" id="saveNonMatrix" type="button" disabled style="margin-right: 4px;"><i class="fa fa-check-circle"></i> Upload</button>
               <button id="savexNonMatrix" type="submit" style="display: none;"></button>
               <button type="button" class="btn btn-default all_btn_import" data-dismiss="modal">Kembali</button>
            </div>
         </form>
      </div>
   </div>
</div>
<div class="modal fade" id="importGajibpjs" role="dialog">
   <div class="modal-dialog">
      <div class="modal-content text-center">
         <div class="modal-header">
            <button type="button" class="close all_btn_import" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Import Data Dari Excel</h4>
         </div>
         <form id="form_import_bpjs" action="#">
            <div class="modal-body">
               <div class="callout callout-info text-left">
                  <b><i class="fa fa-bullhorn"></i> Bantuan</b><br>
                  <ul>
                     <li>Pastikan cell pada file Excel tidak ada yang di <b>MERGE</b>, jika ada yang di <b>MERGE</b> harap melakukan <b style="color: red">UNMERGE</b> terlebih dahulu!</li>
                     <li>Gunakan File Template Import Gaji Perhitungan BPJS yang telah anda Download dari <b>"Export Data Gaji Perhitungan BPJS"</b></li>
                  </ul>
               </div>
               <p class="text-muted">File harus tipe *.xls, *.xlsx, *.csv, *.ods dan *.ots</p>
               <input id="uploadFilexbpjs" placeholder="Nama File" readonly="readonly" class="form-control" required="required">
               <span class="input-group-btn">
                  <div class="fileUpload btn btn-warning btn-flat">
                     <span><i class="fa fa-folder-open"></i> Pilih File</span>
                     <input id="uploadBtnxbpjs" type="file" class="upload" name="file" onchange="checkFile('#uploadBtnxbpjs','#uploadFilexbpjs','#savebpjs')" />
                  </div>
               </span>
            </div> 
            <div class="modal-footer">
               <div id="progressbpjs" style="float: left;"></div>
               <button class="btn btn-primary all_btn_import" id="savebpjs" type="button" disabled style="margin-right: 4px;"><i class="fa fa-check-circle"></i> Upload</button>
               <button id="savexbpjs" type="submit" style="display: none;"></button>
               <button type="button" class="btn btn-default all_btn_import" data-dismiss="modal">Kembali</button>
            </div>
         </form>
      </div>
   </div>
</div>
<div id="modal_delete_partial"></div>
<script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
<script type="text/javascript">
   var url_select="<?php echo base_url('global_control/select2_global');?>";
   var table="karyawan";
   var column="id_karyawan";
   $(document).ready(function(){ 
      refreshData();
      tableData('all');
      $('#btn_export').click(function(){
      	getSelect2("<?php echo base_url('master/master_bagian/get_select2')?>",'bagian_export');
         select_data('unit_export',url_select,'master_loker','kode_loker','nama');
      })
      $('#import').modal({
         show: false,
         backdrop: 'static',
         keyboard: false
      }) 
      $('#save').click(function(){
         $('.all_btn_import').attr('disabled','disabled');
         $('#progress2').html('<i class="fa fa-spinner fa-pulse fa-fw"></i> Mohon Menunggu, data sedang di upload....')
         setTimeout(function () {
            $('#savex').click();
         },1000);
      })
      $('#form_import').submit(function(e){
         e.preventDefault();
         var data_add = new FormData(this);
         var urladd = "<?php echo base_url(); ?>rekap/import_data_karyawan";
         submitAjaxFile(urladd,data_add,'#import','#progress2','.all_btn_import');
      });
      $('#saveNonMatrix').click(function(){
           $('.all_btn_import').attr('disabled','disabled');
           $('#progressNonMatrix').html('<i class="fa fa-spinner fa-pulse fa-fw"></i> Mohon Tunggu, Data sedang di upload....')
           setTimeout(function () {
            $('#savexNonMatrix').click();
           },1000);
      })
      $('#form_import_non_matrix').submit(function(e){
         e.preventDefault();
         var data_add = new FormData(this);
         var urladd = "<?php echo base_url('rekap/importGajiNonMatrix'); ?>";
         submitAjaxFile(urladd,data_add,'#importNonMatrix','#progressNonMatrix','.all_btn_import');
		   $('#importNonMatrix').modal('toggle');
         $('#table_data').DataTable().ajax.reload(function (){
            Pace.restart();
         });
      });
      $('#savebpjs').click(function(){
           $('.all_btn_import').attr('disabled','disabled');
           $('#progressbpjs').html('<i class="fa fa-spinner fa-pulse fa-fw"></i> Mohon Tunggu, Data sedang di upload....')
           setTimeout(function () {
            $('#savexbpjs').click();
           },1000);
      })
      $('#form_import_bpjs').submit(function(e){
         e.preventDefault();
         var data_add = new FormData(this);
         var urladd = "<?php echo base_url('rekap/importGajibpjs'); ?>";
         submitAjaxFile(urladd,data_add,'#importbpjs','#progressbpjs','.all_btn_import');
         $('#importGajibpjs').modal('toggle');
         $('#table_data').DataTable().ajax.reload(function (){
            Pace.restart();
         });
      });
   });
   function tableData(kode) {
      $('input[name="param"').val(kode);
      $('#table_data').DataTable().destroy();
      if(kode=='all'){
         var datax = {param:'all',access:"<?php echo $this->codegenerator->encryptChar($access);?>"};
      }else{
         var bagian = $('#bagian_export').val();
         var unit = $('#unit_export').val();
         var bulan = $('#bulan_export').val();
         var tahun = $('#tahun_export').val();
         var datax = {param:'search',bagian:bagian,unit:unit,bulan:bulan,tahun:tahun,access:"<?php echo $this->codegenerator->encryptChar($access);?>"};
      }
      var max_col=<?=count($d_level)?>;
      $('#table_data').DataTable( {
         ajax: {
            url: "<?php echo base_url('employee/employee/view_all/')?>",
            type: 'POST',
            data:datax
         },
         scrollX: true,
         columnDefs: [
         {   targets: 0, 
            width: '5%',
            render: function ( data, type, full, meta ) {
               return '<center>'+(meta.row+1)+'.</center>';
            }
         },
         {   targets: [12,13,14,15], 
         // {   targets: [(7+max_col),(8+max_col),(9+max_col)], 
            width: '6%',
            render: function ( data, type, full, meta ) {
               return '<center>'+data+'</center>';
            }
         },
         ]
      });
   }
   function refreshData() {
      getSelect2("<?php echo base_url('master/master_bagian/get_select2')?>",'bagian_export');
      select_data('unit_export',url_select,'master_loker','kode_loker','nama','placeholder');
      unsetoption('bagian_export',['BAG001','BAG002']);
   }
   function view_modal(id) {
      var data={id_karyawan:id};
      var callback=getAjaxData("<?php echo base_url('employee/employee/view_one')?>",data);  
      $('#view').modal('show');
      $('.header_data').html(callback['nama']);
      $('#data_foto_view').html('<img class="profile-user-img img-responsive img-circle view_photo" data-source-photo="'+callback['foto']+'" src="'+callback['foto']+'" alt="User profile picture">');
      $('#data_nik_view').html(callback['nik']);
      $('#data_nama_view').html(callback['nama']);
      $('#data_jabatan_view').html(callback['nama_jabatan']);
      $('#data_loker_view').html(callback['nama_loker']);
      $('#data_grade_view').html(callback['grade']);
      $('#data_tglmasuk_view').html(callback['gettgl_masuk']);
      var status = callback['status'];
      if(status==1){
         var statusval = '<b class="text-success">Online</b>';
      }else{
         var statusval = '<b class="text-danger">Offline</b>';
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
      var data={id_karyawan:id};
      var callback=getAjaxData("<?php echo base_url('employee/master_bidang/view_one')?>",data); 
      $('#view').modal('toggle');
      setTimeout(function () {
         $('#edit').modal('show');
      },600); 
      $('.header_data').html(callback['nama']);
      $('#data_id_edit').val(callback['id']);
      $('#data_kode_edit_old').val(callback['kode_bidang']);
      $('#data_kode_edit').val(callback['kode_bidang']);
      $('#data_name_edit').val(callback['nama']);
   }
   function delete_modal(id) {
      var data={id_karyawan:id};
      var callback=getAjaxData("<?php echo base_url('employee/employee/view_one')?>",data);
      var datax={table:table,column:column,id:id,nama:callback['nama']};
      loadModalAjax("<?php echo base_url('pages/load_modal_delete')?>",'modal_delete_partial',datax,'delete');
   }
   function checkFile(idf,idt,btnx) {
      var fext = ['xls', 'xlsx', 'csv', 'ods', 'ots'];
      pathFile(idf,idt,fext,btnx);
   }
   function do_status(id,data) {
      var data_table={status:data};
      var where={id_karyawan:id};
      var datax={table:table,where:where,data:data_table};
      submitAjax("<?php echo base_url('global_control/change_status')?>",null,datax,null,null,'status');
      $('#table_data').DataTable().ajax.reload(function (){
         Pace.restart();
      });
   }
   function do_edit(){
      if($("#form_edit")[0].checkValidity()) {
         submitAjax("<?php echo base_url('master/edt_bidang')?>",'edit','form_edit',null,null);
         $('#table_data').DataTable().ajax.reload(function (){
            Pace.restart();
         });
      }else{
         notValidParamx();
      } 
   }
   function do_add(){
      if($("#form_add")[0].checkValidity()) {
         submitAjax("<?php echo base_url('master/add_bidang')?>",null,'form_add',null,null);
         $('#table_data').DataTable().ajax.reload(function (){
            Pace.restart();
         });
         $('#form_add')[0].reset();
      }else{
         notValidParamx();
      } 
   }
   function rekap() {
      var data=$('#form_filter').serialize();
      window.location.href = "<?php echo base_url('rekap/export_data_karyawan')?>?"+data;
   }
   function rekap_thr() {
      var data=$('#form_filter').serialize();
      window.location.href = "<?php echo base_url('rekap/export_data_karyawan_thr')?>?"+data;
   }
</script>