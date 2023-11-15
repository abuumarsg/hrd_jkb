<style type="text/css">
.data_detail{
   display: none;
   border-style: solid;
   border-width: 1px;
   border-radius: 3px;
   padding: 8px;
   border-color: #7F7F7F;
}
</style>
<div class="content-wrapper">
   <section class="content-header">
      <h1>
         <i class="fa fa-gears"></i> Setting Aplikasi
         <small>Setting Manajemen Notifikasi</small>
      </h1>
      <ol class="breadcrumb">
         <li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
         <li class="active">Setting Manajemen Notifikasi</li>
      </ol>
   </section>
   <section class="content">
      <div class="row">
         <div class="col-md-12">
            <div class="box box-default">
               <div class="box-header with-border">
                  <h3 class="box-title" id="coba_notif"><i class="fa fa-bell"></i> Manajemen Notifikasi</h3>
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
                                 <button class="btn btn-success btn-flat" type="button" data-toggle="collapse" data-target="#add" id="add_button"><i class="fa fa-plus"></i> Tambah Notifikasi</button>
                              </div>
                              <div class="pull-right" style="font-size: 8pt;">
                                 <i class="fa fa-toggle-on stat scc"></i> Aktif<br>
                                 <i class="fa fa-toggle-off stat err"></i> Tidak Aktif
                              </div>
                           </div>
                        </div>
                        <div class="collapse" id="add">
                           <br>
                           <div class="col-md-2"></div>
                           <div class="col-md-8">

                              <form id="form_add" action="#">
                                 <p class="text-danger">Semua data harus diisi!</p>
                                 <div class="form-group">
                                    <label>Kode Notifikasi</label>
                                    <input type="text" placeholder="Masukkan Kode Notifikasi" name="kode" class="form-control" id="data_kode_add" readonly="readonly">
                                 </div>
                                 <div class="form-group">
                                    <label>Judul</label>
                                    <input type="text" placeholder="Masukkan Judul Notifikasi" name="judul" class="form-control">
                                 </div>
                                 <div class="form-group">
                                    <label>Isi Notifikasi</label>
                                    <textarea class="form-control textarea" name="isi_notif" placeholder="Isi Notifikasi Disini ..." width="100%"></textarea>
                                 </div>
                                 <div class="form-group">
                                    <label>Tanggal Berlaku (Mulai -  Selesai)</label>
                                    <input type="text" placeholder="Masukkan Judul Notifikasi" name="date" class="form-control date-range">
                                 </div>
                                 <div class="form-group">
                                    <label>Notifikasi Untuk</label>
                                    <?php
                                    $untuk[null] = 'Pilih Data';
                                    $sel1 = [null];
                                    $exsel1 = array('class'=>'form-control select2','id'=>'notif_for','required'=>'required','style'=>'width:100%','onchange'=>'notif_forx(this.value)');
                                    echo form_dropdown('untuk',$untuk,$sel1,$exsel1);
                                    ?>
                                 </div>
                                 <div class="form-group">
                                    <label>Tipe Notifikasi</label>
                                    <?php
                                    $tipe[null] = 'Pilih Data';
                                    $sel2 = [null];
                                    $exsel2 = array('class'=>'form-control select2','id'=>'data_tipe_add','required'=>'required','style'=>'width:100%');
                                    echo form_dropdown('tipe',$tipe,$sel2,$exsel2);
                                    ?>
                                 </div>
                                 <div class="form-group">
                                    <label>Sifat</label>
                                    <?php
                                    $sifat[null] = 'Pilih Data';
                                    $sel3 = [null];
                                    $exsel3 = array('class'=>'form-control select2','id'=>'data_sifat_add','required'=>'required','style'=>'width:100%');
                                    echo form_dropdown('sifat',$sifat,$sel3,$exsel3);
                                    ?>
                                 </div>
                                 <div class="form-group" id="empx0" style="display: none;">
                                    <label>Masukkan Daftar Karyawan</label>
                                    <select class="form-control select2" name="emp[]" style="width: 100%" multiple="multiple" id="for_kar">
                                       <option value="">Pilih Data</option>
                                       <option value="ALL">Semua Karyawan</option>
                                       <?php
                                       foreach ($list_emp as $k_emp=>$v_emp) {
                                          echo '<option value="'.$k_emp.'">'.$v_emp.'</option>';
                                       }
                                       ?>
                                    </select>
                                 </div>
                                 <div class="form-group" id="admx0" style="display: none;">
                                    <label>Masukkan Daftar Admin</label>
                                    <select class="form-control select2" name="adm[]" style="width: 100%" multiple="multiple" id="for_adm">
                                       <option value="ALL">Semua Admin</option>
                                       <?php
                                       foreach ($list_admin as $k_adm=>$v_adm) {
                                          echo '<option value="'.$k_adm.'">'.$v_adm.'</option>';
                                       }
                                       ?>
                                    </select>
                                 </div>
                                 <p class="text-muted">File harus bertipe *.pdf</p>
                                 <div class="form-group">
                                    <div class="input-group">
                                       <label class="input-group-btn">
                                          <span class="btn btn-primary"> 
                                             <i class="fas fa-folder-open"></i> Pilih File <input type="file" id="BSbtnsuccess" name="file" style="display: none;">
                                          </span>
                                       </label>
                                       <input type="text" class="form-control" readonly>
                                    </div>
                                 </div>
                                 <div class="form-group">
                                    <button type="submit" id="btn_add" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
                                 </div>
                              </form>
                           </div>
                        </div>
                     </div>
                  </div>
                  <br>
                  <div class="row">
                     <div class="col-md-12">
                        <div class="callout callout-info">
                           <label><i class="fa fa-info-circle"></i> Bantuan</label><br>
                           <ul>
                              <li><b>[DETAIL ISI]</b> Klik Pada Button <button class="btn btn-xs btn-info"><i class="fas fa-info-circle"></i></button> Pada Table  untuk melihat detail dari Isi Notifikasi</li>
                              <li><b>[UNTUK]</b> Klik Pada kolom <b>Untuk</b> untuk melihat Siapa Saja yang mendapatkan Notifikasi, Jika kolom <b>Untuk</b> TIDAK berisi <label class="label label-primary">Semua Karyawan</label> atau <label class="label label-primary">Semua Admin</label></li>
                           </ul>
                        </div>
                        <table id="table_data" class="table table-bordered table-striped" width="100%">
                           <thead>
                              <tr>
                                 <th>No.</th>
                                 <th>Kode</th>
                                 <th>Judul</th>
                                 <th>Isi</th>
                                 <th>Tanggal Notif</th>
                                 <th>Sifat</th>
                                 <th>Tipe</th>
                                 <th>Untuk</th>
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
                     <label class="col-md-6 control-label">Kode Notifikasi</label>
                     <div class="col-md-6" id="data_kode_view"></div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Judul</label>
                     <div class="col-md-6" id="data_judul_view"></div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Isi</label>
                     <div class="col-md-6" id="data_isi_view"></div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Tanggal Notif</label>
                     <div class="col-md-6" id="data_tanggal_notif_view" style="padding-left: 0px;"></div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Sifat Notifikasi</label>
                     <div class="col-md-6" id="data_sifat_view"></div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Tipe Notifikasi</label>
                     <div class="col-md-6" id="data_tipe_view"></div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Notifikasi Untuk</label>
                     <div class="col-md-6" id="data_untuk_view"></div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">File</label>
                     <div class="col-md-6" id="data_file_view"></div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Dibaca Oleh</label>
                     <div class="col-md-6" id="data_read_view"></div>
                     <div class="col-md-6 data_detail" id="data_read_detail"></div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Dihapus Oleh</label>
                     <div class="col-md-6" id="data_del_view"></div>
                     <div class="col-md-6 data_detail" id="data_delete_detail"></div>
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
<div id="edit" class="modal fade" role="dialog">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h2 class="modal-title">Edit Data <b class="text-muted header_data"></b></h2>
         </div>
         <form id="form_edit" action="#">
            <div class="modal-body">
               <input type="hidden" id="data_id_edit" name="id" value="">
               <div class="form-group">
                  <label>Kode Notifikasi</label>
                  <input type="text" placeholder="Masukkan Kode Notifikasi" name="kode" class="form-control" id="data_kode_edit" readonly="readonly">
               </div>
               <div class="form-group">
                  <label>Judul</label>
                  <input type="text" placeholder="Masukkan Judul Notifikasi" name="judul" id="data_judul_edit" class="form-control">
               </div>
               <div class="form-group">
                  <label>Isi Notifikasi</label>
                  <textarea class="form-control textarea" name="isi" placeholder="Isi Notifikasi Disini ..." id="data_isi_edit"></textarea>
               </div>
               <div class="form-group">
                  <label>Tanggal Berlaku (Mulai -  Selesai)</label>
                  <input type="text" placeholder="Tanggal" id="data_date_edit" name="date" value="" class="form-control date-range" required="required">
               </div>
               <div class="form-group">
                  <label>Notifikasi Untuk</label>
                  <?php
                  $untuk[null] = 'Pilih Data';
                  $sel4 = [null];
                  $exsel4 = array('class'=>'form-control select2','id'=>'notif_for_edit','required'=>'required','style'=>'width:100%','onchange'=>'notif_forx_edit(this.value)');
                  echo form_dropdown('untuk_edit',$untuk,$sel4,$exsel4);
                  ?>
               </div>
               <div class="form-group">
                  <label>Tipe Notifikasi</label>
                  <?php
                  $tipe[null] = 'Pilih Data';
                  $sel5 = [null];
                  $exsel5 = array('class'=>'form-control select2','id'=>'data_tipe_edit','required'=>'required','style'=>'width:100%');
                  echo form_dropdown('tipe_edit',$tipe,$sel5,$exsel5);
                  ?>
               </div>
               <div class="form-group">
                  <label>Sifat</label>
                  <?php
                  $sifat[null] = 'Pilih Data';
                  $sel6 = [null];
                  $exsel6 = array('class'=>'form-control select2','id'=>'data_sifat_edit','required'=>'required','style'=>'width:100%');
                  echo form_dropdown('sifat_edit',$sifat,$sel6,$exsel6);
                  ?>
               </div>
               <div class="form-group" id="empe0" style="display: none;">
                  <label>Masukkan Daftar Karyawan</label>
                  <select class="form-control select2" name="emp[]" style="width: 100%" multiple="multiple" id="data_karyawan_edit">
                     <option value="ALL">Semua Karyawan</option>
                     <?php
                     foreach ($list_emp as $k_emp=>$v_emp) {
                        echo '<option value="'.$k_emp.'">'.$v_emp.'</option>';
                     }
                     ?>
                  </select>
               </div>
               <div class="form-group" id="adme0" style="display: none;">
                  <label>Masukkan Daftar Admin</label>
                  <select class="form-control select2" name="adm[]" style="width: 100%" multiple="multiple" id="data_admin_edit">
                     <option value="ALL">Semua Admin</option>
                     <?php
                     foreach ($list_admin as $k_adm=>$v_adm) {
                        echo '<option value="'.$k_adm.'">'.$v_adm.'</option>';
                     }
                     ?>
                  </select>
               </div>
               <p class="text-muted">File harus bertipe *.pdf</p>
               <div class="form-group">
                  <div class="input-group">
                     <label class="input-group-btn">
                        <span class="btn btn-primary"> 
                           <i class="fas fa-folder-open"></i> Pilih File <input type="file" id="BSbtnsuccess" name="file" style="display: none;">
                        </span>
                     </label>
                     <input type="text" class="form-control" id="file_edit" readonly>
                  </div>
               </div>
            </div>
            <div class="modal-footer">
               <button type="submit" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
               <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
            </div>
         </form>
      </div>
   </div>
</div>
<div id="view_for" class="modal fade" role="dialog">
   <div class="modal-dialog modal-md modal-default">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h2 class="modal-title text-center"><b class="text-muted header_data"></b></h2>
         </div>
         <div id="daftar_for"></div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
         </div>
      </div>
   </div>
</div>
<div id="modal_send" class="modal fade" role="dialog">
   <div class="modal-dialog modal-md modal-default">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h2 class="modal-title"><b class="text-muted">Kirim Email</b></h2>
         </div>
         <div class="modal-body">
            <form id="form_send">
               <input type="hidden" id="data_id_send" name="id_notifikasi" value="">
               <div class="form-group">
                  <label>Pilih Bagian</label>
                  <select class="form-control select2" name="bagian" id="bagian_send" style="width: 100%;" onchange="get_selet_emp(this.value)"></select>
               </div>
               <div class="form-group">
                  <label>Pilih Karyawan</label>
                  <select class="form-control select2" name="karyawan[]" id="karyawan_send" required="required" style="width: 100%;" multiple="multiple"></select>
               </div>
               <div class="form-group">
                  <label>Subject</label>
                  <input type="text" placeholder="Masukkan Subject 'Notifikasi Pengingat Input PA'" name="subject" class="form-control">
               </div>
               <div class="form-group">
                  <label>Isi Email</label>
                  <textarea class="form-control textarea" name="isi_email" placeholder="Isi Email Disini ..." width="100%"></textarea>
               </div>
            </form>
         </div>
         <div class="modal-footer">
            <button type="button" onclick="send_email()" class="btn btn-success"><i class="fa fa-send"></i> Kirim</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
         </div>
      </div>
   </div>
</div>
<div id="deletex" class="modal fade" role="dialog">
   <div class="modal-dialog modal-sm modal-danger">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title text-center">Konfirmasi Hapus</h4>
         </div>
         <form id="form_deletex">
            <div class="modal-body text-center">
               <input type="hidden" id="data_kode_delete" name="kode">
               <input type="hidden" id="data_id_delete" name="id">
               <input type="hidden" id="data_berkas_delete" name="berkas">
               <p>Apakah anda yakin akan menghapus data dengan nama <b id="data_name_delete" class="header_data"></b> ?</p>
            </div>
         </form>
         <div class="modal-footer">
            <button type="button" onclick="dox_delete()" class="btn btn-primary"><i class="fa fa-trash"></i> Hapus</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
         </div>
      </div>
   </div>
</div>
<script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
<script type="text/javascript">
   var url_select="<?php echo base_url('global_control/select2_global');?>";
   var table="notification";
   var column="id_notif";
   $(document).ready(function(){
      form_key("form_add","btn_add");
      form_key("form_edit","btn_edit");
      refreshCode();
      $('#table_data').DataTable({
         ajax: {
            url: "<?php echo base_url('master/master_notifikasi/view_all/')?>",
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
         // {   targets: 3,
         //    width: '10%',
         //    render: function ( data, type, full, meta ) {
         //       return '<div style="white-space:normal;word-wrap: break-word;"><span id="read_partian_'+full[0]+'" title="'+full[11]+'">'+data+'... <a onclick="readmore('+full[0]+')" style="cursor: pointer;color:#4A89BF;"><i class="fa fa-chevron-circle-right fa-fw"></i></a></span><span id="read_full_'+full[0]+'" style="display:none;">'+full[11]+'  <a onclick="hidemore('+full[0]+')" style="cursor: pointer;color:#4A89BF;"><i class="fa fa-chevron-circle-left fa-fw"></i></a></span></div>';
         //    }
         // },
         {   targets: 5,
            width: '10%',
            render: function ( data, type, full, meta ) {
               return '<center>'+data+'</center>';
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
         {   targets: 9, 
            width: '7%',
            render: function ( data, type, full, meta ) {
               return '<center>'+data+'</center>';
            }
         },
         ]
      });
      $('#form_add').submit(function(e){
         e.preventDefault();
         var data = new FormData(this);
         var urlx = "<?php echo base_url('master/add_notif'); ?>";
         submitAjaxFile(urlx,data,null,null,null);
         $('#form_add')[0].reset();
         refreshCode();
      });
      $('#form_edit').submit(function(e){
         e.preventDefault();
         var data = new FormData(this);
         var urlx = "<?php echo base_url('master/edt_notif'); ?>";
         submitAjaxFile(urlx,data,'#edit' ,null,null);
      });
   });
   $(function() {
      $(document).on('change', ':file', function() {
         var input = $(this),
         numFiles = input.get(0).files ? input.get(0).files.length : 1,
         label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
         input.trigger('fileselect', [numFiles, label]);
      });
      $(document).ready( function() {
         $(':file').on('fileselect', function(event, numFiles, label) {
            var input = $(this).parents('.input-group').find(':text'),
            log = numFiles > 1 ? numFiles + ' files selected' : label;
            if( input.length ) {
               input.val(log);
            } else {
               if( log ) alert(log);
            }
         });
      });
   });
   function notif_forx(notif_for) {
      setTimeout(function () {
         var utk = $('#notif_for').val()
         if(utk=='ADM'){
            $('#admx0').css('display','block');
            $('#empx0').css('display','none');
         }else if(utk=='FO'){
            $('#admx0').css('display','none');
            $('#empx0').css('display','block');
         }else{
            $('#admx0').css('display','none');
            $('#empx0').css('display','none');
         }
      },100);
   }
   function notif_forx_edit(notif_for_edit) {
      setTimeout(function () {
         var utk = $('#notif_for_edit').val()
         if(utk=='ADM'){
            $('#adme0').css('display','block');
            $('#empe0').css('display','none');
         }else if(utk=='FO'){
            $('#adme0').css('display','none');
            $('#empe0').css('display','block');
         }else{
            $('#adme0').css('display','none');
            $('#empe0').css('display','none');
         }
      },100);
   }
   function dox_delete(){
      submitAjax("<?php echo base_url('master/del_berkas')?>",'deletex','form_deletex',null,null);
      $('#table_data').DataTable().ajax.reload(function (){
         Pace.restart();
      });
   }
   function checkFile(idf,idt,btnx) {
      var fext = ['jpg', 'png', 'xls', 'xlsx', 'docx', 'pdf'];
      pathFile(idf,idt,fext,btnx);
   }
   function refreshCode() {
      kode_generator("<?php echo base_url('master/master_notifikasi/kode');?>",'data_kode_add');
   }
   function data_menu_detail() {
      $('#data_read_detail').slideToggle('slow');
   }
   function data_del_detail() {
      $('#data_delete_detail').slideToggle('slow');
   }
   function view_modal(id) {
      var data={id_notif:id};
      var callback=getAjaxData("<?php echo base_url('master/master_notifikasi/view_one')?>",data);  
      $('#view').modal('show');
      $('.header_data').html(callback['judul']);
      $('#data_kode_view').html(callback['kode_notif']);
      $('#data_judul_view').html(callback['judul']);
      $('#data_isi_view').html(callback['isi']);
      $('#data_tanggal_notif_view').html(callback['tgl_notif']);
      $('#data_sifat_view').html(callback['nama_sifat']);
      $('#data_tipe_view').html(callback['nama_tipe']);
      $('#data_untuk_view').html(callback['nama_untuk']);
      $('#data_file_view').html(callback['file']);
      var jml = callback['jml_bc'];
      if(jml>0){
         $('#data_read_view').html('<a style="cursor: pointer;color: #0084FC;" onclick="data_menu_detail()"><i class="fa fa-eye"></i> '+callback['jumlah_baca']+'</a>');
      }else{
         $('#data_read_view').html(callback['jumlah_baca']);
      }
      $('#data_read_detail').html(callback['id_read']);
      var jml_d = callback['jml_del'];
      if(jml_d>0){
         $('#data_del_view').html('<a style="cursor: pointer;color: #0084FC;" onclick="data_del_detail()"><i class="fa fa-eye"></i> '+callback['jumlah_delete']+'</a>');
      }else{
         $('#data_del_view').html(callback['jumlah_delete']);
      }
      $('#data_delete_detail').html(callback['id_del']);
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
   function view_modal_for(id) {
      var data={id_notif:id};
      var callback=getAjaxData("<?php echo base_url('master/master_notifikasi/view_daftar')?>",data); 
      $('#view_for').modal('show');
      $('.header_data').html(callback['untuk']);
      $('#daftar_for').html(callback['daftar']);
   }
   function modal_send(id) {
      $('#data_id_send').val(id);
      $('#modal_send').modal('show');
		getSelect2("<?php echo base_url('master/master_bagian/get_select2')?>",'bagian_send');
		unsetoption('bagian_send',['BAG001','BAG002']);
		getSelect2("<?php echo base_url('presensi/data_presensi/employee')?>",'karyawan_send');
   }
	function get_selet_emp(kode) {
		var data={kode_bagian:kode};
		var callback=getAjaxData("<?php echo base_url('presensi/data_lembur_trans/view_select')?>",data);
		var text = "";
		var i;
		var selectedValues = new Array();
		for (i = 0; i < callback.length; i++) {
			selectedValues[i] = callback[i];
		} 
		$('#karyawan_send').val(selectedValues).trigger('change');
	}
   function send_email(){
		if($("#form_send")[0].checkValidity()) {
			submitAjax("<?php echo base_url('master/send_email_notifikasi')?>",null,'form_send',null,null);
			$('#table_data').DataTable().ajax.reload(function(){
				Pace.restart();
			});
			$('#form_send')[0].reset();
		}else{
			notValidParamx();
		} 
   }
   function edit_modal() {
      var id = $('input[name="data_id_view"]').val();
      var data={id_notif:id};
      var callback=getAjaxData("<?php echo base_url('master/master_notifikasi/view_one')?>",data); 
      $('#view').modal('toggle');
      setTimeout(function () {
         $('#edit').modal('show');
      },600);
      $('.header_data').html(callback['judul']);
      $('#data_id_edit').val(callback['id']);
      $('#data_kode_edit').val(callback['kode_notif']);
      $("#data_date_edit").data('daterangepicker').setStartDate(callback['tgl_mulai_val']);
      $("#data_date_edit").data('daterangepicker').setEndDate(callback['tgl_selesai_val']);
      $('#data_judul_edit').val(callback['judul']);
      addValueEditor('data_isi_edit',callback['eisi']);
      $('#file_edit').val(callback['nama_file']);
      $('#notif_for_edit').val(callback['untuk_edit']).trigger('change');
      $('#data_tipe_edit').val(callback['tipe_edit']).trigger('change');
      $('#data_sifat_edit').val(callback['sifat_edit']).trigger('change');
      if(callback['untuk_edit']=='ADM'){
         $('#data_admin_edit').select2('val', [callback['id_for']]);
      }else if(callback['untuk_edit']=='FO'){
         $('#data_karyawan_edit').select2('val', [callback['id_for']]);
      }
   }
   function delete_modal(id) {
      var data={id_notif:id};
      var callback=getAjaxData("<?php echo base_url('master/master_notifikasi/view_one')?>",data);
      var datax={table:table,column:column,id:id,nama:callback['judul']};
      $('#data_kode_delete').val(callback['kode_notif']);
      $('#data_name_delete').html(callback['judul']);
      $('#data_id_delete').val(callback['id']);
      $('#data_berkas_delete').val(callback['link_file']);
      $('#deletex').modal('toggle');
   }
   function do_status(id,data) {
      var data_table={status:data};
      var where={id_notif:id};
      var datax={table:table,where:where,data:data_table};
      submitAjax("<?php echo base_url('global_control/change_status')?>",null,datax,null,null,'status');
      $('#table_data').DataTable().ajax.reload(function (){
         Pace.restart();
      });
   }
</script>