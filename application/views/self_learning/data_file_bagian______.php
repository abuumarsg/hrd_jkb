<div class="content-wrapper">
   <section class="content-header">
      <h1>
         <i class="fas fa-sliders-h"></i> Data File Materi Self Learning Bagian
      </h1>
      <ol class="breadcrumb">
         <li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
         <li class="active">Data File Materi Self Learning</li>
      </ol>
   </section>
   <section class="content">
      <div class="row">
         <div class="col-md-12">
            <div class="box box-success">
               <div class="box-header with-border">
                  <h3 class="box-title"><i class="fas fa-sliders-h"></i> Data File Materi Self Learning <small id="name_materi_header"></small></h3>
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
                                          echo '<a href="#add" data-toggle="collapse"  data-parent="#accordion" class="btn btn-success"><i class="fa fa-plus"></i> Tambah File</a>';
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
                                    <div class="col-md-2"></div>
                                    <div class="col-md-8">
                                       <form id="form_add">
                                          <input type="hidden" name="kode_materi" id="kode_materi_add">
                                          <div class="form-group">
                                             <label>Kode File</label>
                                             <input type="text" placeholder="Masukkan Kode File" id="data_kode_file_add" name="kode_file" class="form-control" required="required" readonly="readonly">
                                          </div>
                                          <div class="form-group">
                                             <label>Materi</label>
                                             <input type="text" placeholder="Masukkan Kode Materi" id="data_kode_addx" name="nama_materi" class="form-control" required="required" readonly="readonly">
                                          </div>
                                          <p class="text-muted"><i>File harus bertipe *.mp4, *.pptx, *.pdf, *.jpg, *.jpeg, *.png, *.xlsx</i></p>
                                          <div class="form-group">
                                             <div class="input-group">
                                                   <label class="input-group-btn">
                                                      <span class="btn btn-primary"> 
                                                         <i class="fas fa-folder-open"></i> Pilih File <input type="file" id="BSbtnsuccess" name="file" style="display: none;" onchange="checkFile('#BSbtnsuccess',null,null,event)">
                                                      </span>
                                                   </label>
                                                   <input type="text" class="form-control" readonly>
                                             </div>
                                          </div>
                                          <div class="form-group">
                                             <label>Nama </label>
                                             <input type="text" placeholder="Masukkan Pilihan A" name="nama" class="form-control field" required="required">
                                          </div>
                                          <div class="form-group">
                                             <label>Keterangan</label>
                                             <textarea name="keterangan" id="keterangan_add" class="form-control" required="required" cols="30" rows="10" placeholder="Masukkan Soal"></textarea>
                                          </div>
                                          <div class="form-group">
                                             <button type="button" id="btn_add" onclick="do_add()" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
                                             <button type="submit" id="btn_addx" style="display: none;"></button>
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
                        <table id="table_data" class="table table-bordered table-striped table-responsive" width="100%">
                           <thead>
                              <tr>
                                 <th>No.</th>
                                 <th>Kode</th>
                                 <th>Nama</th>
                                 <th>File</th>
                                 <th>Keterangan</th>
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
                     <label class="col-md-6 control-label">Kode File</label>
                     <div class="col-md-6" id="data_kode_view"></div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Nama Materi</label>
                     <div class="col-md-6" id="data_name_view"></div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">File</label>
                     <div class="col-md-6" id="data_file_view"></div>
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
            <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
         </div>
      </div>
   </div>
</div>
<div id="view_modal_file" class="modal fade" role="dialog">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h2 class="modal-title">Detail File Data <b class="text-muted header_data"></b></h2>
            <input type="hidden" name="data_id_view">
         </div>
         <div class="modal-body">
            <div class="row">
               <div class="col-md-12">
                  <div id="fileView"></div>
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
                  <label>Nama Soal</label>
                  <textarea name="soal" id="soal_edit" class="form-control" required="required" cols="30" rows="10" placeholder="Masukkan Soal"></textarea>
               </div>
               <div class="form-group">
                  <label>Pilihan A </label>
                  <input type="text" placeholder="Masukkan Pilihan A" name="choice_a" id="choice_a_edit" class="form-control field" required="required">
               </div>
               <div class="form-group">
                  <label>Pilihan B </label>
                  <input type="text" placeholder="Masukkan Pilihan B" name="choice_b" id="choice_b_edit" class="form-control field" required="required">
               </div>
               <div class="form-group">
                  <label>Pilihan C </label>
                  <input type="text" placeholder="Masukkan Pilihan C" name="choice_c" id="choice_c_edit" class="form-control field" required="required">
               </div>
               <div class="form-group">
                  <label>Pilihan D </label>
                  <input type="text" placeholder="Masukkan Pilihan D" name="choice_d" id="choice_d_edit" class="form-control field" required="required">
               </div>
               <div class="form-group">
                  <label>Pilihan E </label>
                  <input type="text" placeholder="Masukkan Pilihan E" name="choice_e" id="choice_e_edit" class="form-control field" required="required">
               </div>
               <div class="form-group">
                  <label>Jawaban Benar</label>
                  <select class="form-control select2" id="correct_answer_edit" name="correct_answer" style="width: 100%;"></select>
               </div>
               <div class="form-group">
                  <label>Jenis Soal</label>
                  <select class="form-control select2" id="jenis_soal_edit" name="jenis_soal" style="width: 100%;"></select>
               </div>
               <div class="form-group">
                  <label>Pilih Materi Soal</label>
                  <select class="form-control select2" id="select_materi_edit" name="select_materi" style="width: 100%;"></select>
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
   var table="learn_file_materi";
   var column="id";
   $(document).ready(function(){
		getSelect2("<?php echo base_url('learning/getSoal/select_correct_answer')?>",'correct_answer_add');
		getSelect2("<?php echo base_url('learning/getSoal/select_jenis_materi')?>",'jenis_soal_add');
      var dataxx={ kode:"<?=$this->codegenerator->decryptChar($this->uri->segment(3));?>"};
      var callback=getAjaxData("<?php echo base_url('learning/getMateriLearning/view_one')?>",dataxx);  
      $('#kode_materi_add').val(callback['kode']);
      $('#data_kode_addx').val(callback['nama']);
      $('#name_materi_header').html(callback['nama']);
      refreshCode();
      $('#table_data').DataTable( {
         ajax: {
               url: "<?php echo base_url('learning/getFileMateriLearning/view_all/')?>",
               type: 'POST',
               data: {
                  access:"<?php echo $this->codegenerator->encryptChar($access);?>",
                  kode:"<?php echo $this->codegenerator->decryptChar($this->uri->segment(3));?>",
               },
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
         {   targets: 3,
               width: '20%',
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
         {   targets: 6, 
               width: '10%',
               render: function ( data, type, full, meta ) {
               return '<center>'+data+'</center>';
               }
         },
         ]
      });
      $('#form_add').submit(function(e){
         e.preventDefault();
         var data = new FormData(this);
         var urlx = "<?php echo base_url('learning/add_FileMateriLearning'); ?>";
         submitAjaxFile(urlx,data,null,null,null);
      });
   });
  function do_add(){
    if($("#form_add")[0].checkValidity()) {
      $('#btn_addx').click();
      $('#form_add')[0].reset();
      refreshCode();
    }else{
      notValidParamx();
    }
  } 
   function refreshCode() {
      var dataxx={ kode:"<?=$this->codegenerator->decryptChar($this->uri->segment(3));?>"};
      var callback=getAjaxData("<?php echo base_url('learning/getMateriLearning/view_one')?>",dataxx);  
      $('#kode_materi_add').val(callback['kode']);
      $('#data_kode_addx').val(callback['nama']);
      kode_generator("<?php echo base_url('learning/getFileMateriLearning/kode');?>",'data_kode_file_add');
   }
   function view_modal_file(id, ext) {
      var data = {id:id, ext:ext};
      var callback=getAjaxData("<?php echo base_url('learning/getFileMateriLearning/view_one')?>",data);  
      $('#view_modal_file').modal('show');
      $('.header_data').html(callback['nama']);
      $('#data_tabel_view').html(callback['tabel']);
      $('#fileView').html(callback['fileView']);
   }
   function view_modal(id) {
      var data={id:id};
      var callback=getAjaxData("<?php echo base_url('learning/getFileMateriLearning/view_one')?>",data);  
      $('#view').modal('show');
      $('.header_data').html(callback['nama']);
      $('#data_kode_view').html(callback['kode']);
      $('#data_name_view').html(callback['nama']);
      $('#data_file_view').html(callback['file']);
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
      $('#data_tabel_view').html(callback['tabel']);
   }
   function edit_modal() {
		getSelect2("<?php echo base_url('learning/getSoal/select_correct_answer')?>",'correct_answer_edit');
		getSelect2("<?php echo base_url('learning/getSoal/select_jenis_materi')?>",'jenis_soal_edit');
		getSelect2("<?php echo base_url('learning/getSoal/select_materi')?>",'select_materi_edit');
      var id = $('input[name="data_id_view"]').val();
      var data={id:id};
      var callback=getAjaxData("<?php echo base_url('learning/getSoal/view_one')?>",data); 
      $('#view').modal('toggle');
      setTimeout(function () {
         $('#edit').modal('show');
      },600); 
      $('.header_data').html(callback['nama']);
      $('#data_id_edit').val(callback['id']);
      $('#data_kode_edit_old').val(callback['kode']);
      $('#data_kode_edit').val(callback['kode']);
      $('#soal_edit').val(callback['soal']);
      $('#choice_a_edit').val(callback['choice_A']);
      $('#choice_b_edit').val(callback['choice_B']);
      $('#choice_c_edit').val(callback['choice_C']);
      $('#choice_d_edit').val(callback['choice_D']);
      $('#choice_e_edit').val(callback['choice_E']);
      $('#correct_answer_edit').val(callback['correct_answer']).trigger('change');
      $('#jenis_soal_edit').val(callback['jenis_soal']).trigger('change');
      $('#select_materi_edit').val(callback['kode_materi']).trigger('change');
   }
   function delete_modal(id) {
      var data={id:id};
      var callback=getAjaxData("<?php echo base_url('learning/getFileMateriLearning/view_one')?>",data);
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
         submitAjax("<?php echo base_url('learning/edit_soal')?>",'edit','form_edit',null,null);
         $('#table_data').DataTable().ajax.reload();
      }else{
         notValidParamx();
      } 
   }
   function checkFile(idf,idt,btnx) {
      var fext = ['mp4', 'pptx', 'pdf', 'jpg', 'jpeg', 'png', 'xlsx'];
      pathFile(idf,idt,fext,btnx);
   }
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
</script>