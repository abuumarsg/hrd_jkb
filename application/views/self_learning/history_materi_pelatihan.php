<div class="content-wrapper">
   <section class="content-header">
      <h1>
         <i class="fas fa-history"></i> Riwayat Penilaian
      </h1>
      <ol class="breadcrumb">
         <li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
         <li class="active">Riwayat Penilaian</li>
      </ol>
   </section>
   <section class="content">
      <div class="row">
         <div class="col-md-12">
            <div class="box box-success">
               <div class="box-header with-border">
                  <h3 class="box-title"><i class="fas fa-history"></i> Riwayat Penilaian</h3>
                  <div class="box-tools pull-right">
                     <button class="btn btn-box-tool" onclick="tableData('seach')" data-toggle="tooltip" title="Refresh Table"><i class="fas fa-sync"></i></button>
                     <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                     <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i class="fa fa-times"></i></button>
                  </div>
               </div>
               <div class="box-body">
                  <div class="row">
                     <div class="col-md-12">
                        <div class="col-md-2"></div>
                        <form id="form_search">
                           <div class="col-md-4">
                              <div class="form-group">
                                 <label>Pilih Materi</label>
                                 <select class="form-control select2" id="materi_search" name="materi" style="width: 100%;"></select>
                              </div>
                           </div>
                           <div class="col-md-4">
                              <div class="form-group">
                                 <label>Pilih Karyawan</label>
                                 <select class="form-control select2" id="karyawan_search" name="karyawan" style="width: 100%;"></select>
                              </div>
                           </div>
                        </form>
                        <div class="col-md-2">
                           <div class="form-group" style="padding-top:27px;">
                              <button type="button" onclick="tableData('seach')" class="btn btn-success"><i class="fas fa-search"></i> Cari</button>&nbsp;
                              <button type="button" onclick="do_cetak()" class="btn btn-danger"><i class="fas fa-file-excel"></i> Cetak</button>
                           </div>
                        </div>
                     </div>
                  </div><hr>
                  <div class="row">
                     <div class="col-md-12">
                        <div id="accordion">
                           <div class="panel">
                              <div class="row">
                                 <div class="col-md-12">
                                    <div class="pull-left">
                                       <?php if (in_array($access['l_ac']['add'], $access['access'])) {
                                          echo '<a href="#add" data-toggle="collapse"  data-parent="#accordion" class="btn btn-success"><i class="fa fa-plus"></i> Tambah Learning Eksternal</a>';
                                       }?>
                                    </div>
                                    <!-- <div class="pull-right" style="font-size: 8pt;">
                                       <i class="fa fa-toggle-on stat scc"></i> Aktif<br>
                                       <i class="fa fa-toggle-off stat err"></i> Tidak Aktif
                                    </div> -->
                                 </div>
                              </div>
                              <?php if(in_array($access['l_ac']['add'], $access['access'])){?>
                                 <div class="collapse" id="add"><br><br>
                                    <div class="col-md-2"></div>
                                    <div class="col-md-8">
                                       <form id="form_add">
                                          <div class="form-group">
                                             <label>Pilih Karyawan</label>
                                             <select class="form-control select2" id="karyawan_add" name="karyawan" style="width: 100%;"></select>
                                          </div>
                                          <div class="form-group">
                                             <label>Nama Learning</label>
                                             <input type="text" placeholder="Masukkan Nama Learning Eksternal" name="nama" class="form-control field" required="required">
                                          </div>
                                          <div class="form-group">
                                             <label>Tanggal Pelaksanaan</label>
                                             <div class="has-feedback">
                                                <span class="fa fa-calendar form-control-feedback"></span>
                                                <input type="text" name="tanggal_pelaksanaan" class="form-control date-picker" placeholder="Tanggal Dibuat" readonly="readonly">
                                             </div>
                                          </div>
                                          <div class="form-group">
                                             <label>File Learning </label>
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
                                             <label>Keterangan </label>
														   <textarea name="keterangan" class="form-control" placeholder="Keterangan"></textarea>
                                          </div>
                                          <div class="form-group">
                                             <button type="button" onclick="do_add()" id="btn_add" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
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
                        <!-- <div class="callout callout-info"><label><i class="fa fa-info-circle"></i> Bantuan</label><br>
                           <ul>
                              <li>Klik pada kolom <b>Kode Materi</b> untuk menambah dan mengupdate soal-soal pelatihan</li>
                              <li>Klik pada kolom <b>Jumlah Soal</b> untuk melihat preview soal pelatihan</li>
                              <li>Klik pada kolom <b>Jumlah Bagian</b> untuk melihat preview bagian mana saja yang dapat pelatihan tersebut</li>
                           </ul>
                        </div> -->
                        <table id="table_data" class="table table-bordered table-striped table-responsive" width="100%">
                           <thead>
                              <tr>
                                 <th>No.</th>
                                 <th>Nama</th>
                                 <th>Materi</th>
                                 <th>Target Tanggal Tuntas</th> 
                                 <th>Nilai Pretest</th>
                                 <th>Nilai Postest</th>
                                 <th>Nilai Project</th> 
                                 <th>Status Pelatihan</th> 
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
                     <label class="col-md-6 control-label">Nama Karyawan</label>
                     <div class="col-md-6" id="data_nama_emp_view"></div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Materi</label>
                     <div class="col-md-6" id="data_name_view"></div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Tanggal Target</label>
                     <div class="col-md-6" id="data_tanggal_target_view"></div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Nilai Pretest</label>
                     <div class="col-md-6" id="data_pretest_view"></div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Nilai Postest</label>
                     <div class="col-md-6" id="data_postest_view"></div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Nilai Project</label>
                     <div class="col-md-6" id="data_project_view"></div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Keterangan</label>
                     <div class="col-md-6" id="data_keterangan_view"></div>
                  </div>
               </div>
               <div class="col-md-6">
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
                  <div id="tablePenilaianProject"></div>
               </div>
            </div>
         </div>
         <div class="modal-footer">
            <?php if (in_array($access['l_ac']['edt'], $access['access'])) {
               echo '<button type="submit" id="btn_for_edit" class="btn btn-info" onclick="edit_modal()"><i class="fa fa-edit"></i> Edit</button>';
            }?>
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
<script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
<script type="text/javascript">
  var url_select="<?php echo base_url('global_control/select2_global');?>";
   var table="learn_karyawan";
   var column="id";
   $(document).ready(function(){
		getSelect2("<?php echo base_url('learning/getMateriLearningBagian/select_materi_bagian')?>",'materi_add, #materi_search');
		getSelect2("<?php echo base_url('learning/getMateriLearningBagian/select_karyawan_bagian')?>",'karyawan_add, #karyawan_search');
      tableData('all');
      $('#form_add').submit(function(e){
         e.preventDefault();
         var data = new FormData(this);
         var urlx = "<?php echo base_url('learning/addLearningEksternal'); ?>";
         submitAjaxFile(urlx,data,null,null,null);
      });
   });
   function tableData(param) {
      $('#table_data').DataTable( {
         ajax: {
            url: "<?php echo base_url('learning/getHistoryLearningKaryawanBagian/view_all/')?>",
            type: 'POST',
            data:{access:"<?php echo $this->codegenerator->encryptChar($access);?>", param:param,form:$('#form_search').serialize(),},
				error: function(xhr, status, error) {
					show_modal_error(xhr.responseText);
				}
         },
			scrollX: true,
			bDestroy: true,
			scrollCollapse: true,
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
   }
   function view_modal(id) {
      var data={id:id};
      var callback=getAjaxData("<?php echo base_url('learning/getHistoryLearningKaryawanBagian/view_one')?>",data);  
      $('#view').modal('show');
      $('.header_data').html(callback['nama']);
      $('#data_nama_emp_view').html(callback['nama_karyawan']);
      $('#data_name_view').html(callback['nama']);
      $('#data_tanggal_target_view').html(callback['tanggal_target']);
      $('#data_pretest_view').html(callback['pretest']);
      $('#data_postest_view').html(callback['postest']);
      $('#data_project_view').html(callback['project']);
      $('#data_keterangan_view').html(callback['keterangan']);
      var status = callback['status_materi'];
      if(status == 'BELUM DIKIRIM'){
        $("#btn_for_edit").show();
      }else{
        $("#btn_for_edit").hide();
      }
      $('#data_create_date_view').html(callback['create_date']+' WIB');
      $('#data_update_date_view').html(callback['update_date']+' WIB');
      $('input[name="data_id_view"]').val(callback['id']);
      $('#data_create_by_view').html(callback['nama_buat']);
      $('#data_update_by_view').html(callback['nama_update']);
      $('#data_tabel_view').html(callback['tabel']);
      $('#tablePenilaianProject').html(callback['tablePenilaianProject']);
   }
	function do_cetak() {
      $.redirect("<?php echo base_url('learning/cetakHistoriLearning'); ?>", { data_filter: $('#form_search').serialize() }, "POST", "_blank");
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
   function do_add(){
      if($("#form_add")[0].checkValidity()) {
         $('#btn_addx').click();
         $('#form_add')[0].reset();
         refreshCode();
      }else{
         notValidParamx();
      }
   } 
   function view_modal_file(id) {
      var data = {id:id};
      var callback=getAjaxData("<?php echo base_url('learning/getHistoryLearningKaryawanBagian/view_file_jawaban')?>",data);   
      $('#view').modal('toggle');
      $('#view_modal_file').modal('show');
      $('.header_data').html('File');
      $('#fileView').html(callback['fileView']);
   }
</script>