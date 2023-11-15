<div class="content-wrapper">
   <section class="content-header">
      <h1>
         <i class="fas fa-toolbox"></i> Setting Materi & Penilaian
      </h1>
      <ol class="breadcrumb">
         <li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
         <li class="active">Setting Materi & Penilaian</li>
      </ol>
   </section>
   <section class="content">
      <div class="row">
         <div class="col-md-12">
            <div class="box box-success">
               <div class="box-header with-border">
                  <h3 class="box-title"><i class="fas fa-toolbox"></i> Setting Materi & Penilaian</h3>
                  <div class="box-tools pull-right">
                     <button class="btn btn-box-tool" onclick="tableData('seach')" data-toggle="tooltip" title="Refresh Table"><i class="fas fa-sync"></i></button>
                     <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                     <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i class="fa fa-times"></i></button>
                  </div>
               </div>
               <div class="box-body">
                  <div class="row">
                     <div class="col-md-12">
                        <div class="col-md-1"></div>
                        <form id="form_search">
                           <div class="col-md-3">
                              <div class="form-group">
                                 <label>Pilih Materi</label>
                                 <select class="form-control select2" id="materi_search" name="materi" style="width: 100%;"></select>
                              </div>
                           </div>
                           <div class="col-md-3">
                              <div class="form-group">
                                 <label>Pilih Karyawan</label>
                                 <select class="form-control select2" id="karyawan_search" name="karyawan" style="width: 100%;"></select>
                              </div>
                           </div>
                           <div class="col-md-3">
                              <div class="form-group">
                                 <label>Pilih Status</label>
                                 <select class="form-control select2" name="status" style="width: 100%;">
                                    <option value="BELUM DIKIRIM">BELUM DIKIRIM</option>
                                    <option value="PROSES">PROSES</option>
                                    <option value="BELUM DINILAI">BELUM DINILAI</option>
                                    <!-- <option value="SELESAI">SELESAI</option> -->
                                 </select>
                              </div>
                           </div>
                        </form>
                        <div class="col-md-2">
                           <div class="form-group" style="padding-top:27px;">
                              <button type="button" onclick="tableData('seach')" class="btn btn-success"><i class="fas fa-search"></i> Cari</button>
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
                                          echo '<a href="#add" data-toggle="collapse"  data-parent="#accordion" class="btn btn-success"><i class="fa fa-plus"></i> Setting Karyawan</a>';
                                       } ?>
                                    </div>
                                 </div>
                              </div>
                              <?php if(in_array($access['l_ac']['add'], $access['access'])){?>
                                 <div class="collapse" id="add">
                                    <div class="col-md-2"></div>
                                    <div class="col-md-8">
                                       <form id="form_add">
                                          <div class="form-group">
                                             <label>Pilih Materi</label>
                                             <select class="form-control select2" id="materi_add" name="materi" style="width: 100%;"></select>
                                          </div>
                                          <div class="form-group">
                                             <label>Pilih Karyawan</label>
                                             <select class="form-control select2" id="karyawan_add" name="karyawan[]" style="width: 100%;" multiple="multiple"></select>
                                          </div>
                                          <div class="form-group">
                                             <label>Target Tanggal Tuntas</label>
                                             <div class="has-feedback">
                                                <span class="fa fa-calendar form-control-feedback"></span>
                                                <input type="text" name="target_tanggal" class="form-control date-picker" placeholder="Target Tanggal Tuntas" readonly="readonly" required="required">
                                             </div>
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
                                 <th>Status Project</th> 
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
<div id="edit" class="modal fade" role="dialog">
   <div class="modal-dialog modal-md">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h2 class="modal-title">Edit Data <b class="text-muted header_data"></b></h2>
         </div>
         <div class="modal-body">
            <form id="form_edit">
               <input type="text" id="data_id_edit" name="id" value="">
               <div class="form-group">
                  <label>Karyawan</label>
                  <input type="text" placeholder="Masukkan Kode Materi" id="data_nama_edit" name="nama" value="" class="form-control" required="required" readonly="readonly">
               </div>
               <div class="form-group">
                  <label>Pilih Materi</label>
                  <select class="form-control select2" id="materi_edit" name="materi" style="width: 100%;"></select>
               </div>

               <div class="form-group">
                  <label>Target Tanggal Tuntas</label>
                  <div class="has-feedback">
                     <span class="fa fa-calendar form-control-feedback"></span>
                     <input type="text" name="target_tanggal" id="target_tanggal_edit" class="form-control date-picker" placeholder="Target Tanggal Tuntas" readonly="readonly" required="required">
                  </div>
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
<div id="nilaiLearning" class="modal fade" role="dialog">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h2 class="modal-title">Nilai Project <b class="text-muted header_data"></b></h2>
            <input type="hidden" name="data_id_view">
         </div>
         <div class="modal-body">
            <div class="row">
               <div class="col-md-12">
                  <div id="tablePenilaianProject"></div>
               <!-- <p>ertsdf sfwr fsdf</p> -->
               </div>
            </div>
         </div>
         <div class="modal-footer">
            <!-- <button type="submit" id="btn_for_edit" class="btn btn-success" onclick="edit_modal()"><i class="fa fa-floppy-o"></i> Simpan</button> -->
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
<div id="modal_delete_partial"></div>
<script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
<script src="<?=base_url('asset/learning/tabel/vendor/jquery/jquery-3.2.1.min.js');?>"></script>
<script type="text/javascript">
  var url_select="<?php echo base_url('global_control/select2_global');?>";
   var table="learn_karyawan";
   var column="id";
   $(document).ready(function(){
		getSelect2("<?php echo base_url('learning/getMateriLearningBagian/select_materi_bagian')?>",'materi_add, #materi_search');
		getSelect2("<?php echo base_url('learning/getMateriLearningBagian/select_karyawan_bagian')?>",'karyawan_add, #karyawan_search');
      tableData('all');
   });
   function tableData(param) {
      $('#table_data').DataTable( {
         ajax: {
            url: "<?php echo base_url('learning/getLearningKaryawanBagian/view_all/')?>",
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
         {   targets: 8,
            width: '10%',
            render: function ( data, type, full, meta ) {
               return '<center>'+data+'</center>';
            }
         },
         {   targets: 9, 
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
      var callback=getAjaxData("<?php echo base_url('learning/getLearningKaryawanBagian/view_one')?>",data);  
      $('#view').modal('show');
      $('.header_data').html(callback['nama']);
      $('#data_nama_emp_view').html(callback['nama_karyawan']);
      $('#data_name_view').html(callback['nama']);
      $('#data_tanggal_target_view').html(callback['tanggal_target']);
      $('#data_pretest_view').html(callback['pretest']);
      $('#data_postest_view').html(callback['postest']);
      $('#data_project_view').html(callback['project']);
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
   }
   function edit_modal() {
		getSelect2("<?php echo base_url('learning/getMateriLearningBagian/select_materi_bagian')?>",'materi_edit');
      var id = $('input[name="data_id_view"]').val();
      var data={id:id};
      var callback=getAjaxData("<?php echo base_url('learning/getLearningKaryawanBagian/view_one')?>",data); 
      $('#view').modal('toggle');
      setTimeout(function () {
         $('#edit').modal('show');
      },600); 
      $('.header_data').html(callback['nama']);
      $('#data_id_edit').val(callback['id']);
      $('#data_nama_edit').val(callback['nama_karyawan']);
      $('#materi_edit').val(callback['kode']).trigger('change');
      $('#target_tanggal_edit').val(callback['tanggal_target']);
   }
   function do_edit(){
      if($("#form_edit")[0].checkValidity()) {
         submitAjax("<?php echo base_url('learning/edit_setting_pelatihan')?>",'edit','form_edit',null,null);
         $('#table_data').DataTable().ajax.reload();
      }else{
         notValidParamx();
      } 
   }
   function do_add(){
      if($("#form_add")[0].checkValidity()) {
         submitAjax("<?php echo base_url('learning/add_setting_pelatihan')?>",null,'form_add',null,null);
         $('#table_data').DataTable().ajax.reload(function (){
            Pace.restart();
         });
         $('#form_add')[0].reset();
         $('#materi_add').val(null).trigger('change');
         $('#karyawan_add').val(null).trigger('change');
      }else{
         notValidParamx();
      } 
   }
   function sendToKaryawan(id) {
      var data={id:id};
      var cbx=getAjaxData("<?php echo base_url('learning/getLearningKaryawanBagian/view_one')?>",data);  
      let text = "Kirim Materi & Pelatihan kepada "+cbx['nama_karyawan']+".\nApakah anda yakin ?";
      if (confirm(text) == true) {
         submitAjax("<?php echo base_url('learning/sendMateriToKaryawan')?>", null, data, null, null, 'status');
         // submitAjaxCall("<?php //echo base_url('learning/sendMateriToKaryawan')?>", data, 'status');
         $('#table_data').DataTable().ajax.reload(function (){
            Pace.restart();
         });
      }
   }
   function nilaiLearning(id) {
      var data={id:id};
      var cbx=getAjaxData("<?php echo base_url('learning/getLearningKaryawanBagian/view_one')?>",data);  
      $('#nilaiLearning').modal('show');
      $('#tablePenilaianProject').html(cbx['tablePenilaianProject']);
   }
   function view_modal_file(id) {
      var data = {id:id};
      var callback=getAjaxData("<?php echo base_url('learning/getLearningKaryawanBagian/view_file_jawaban')?>",data);   
      $('#nilaiLearning').modal('toggle');
      $('#view_modal_file').modal('show');
      $('.header_data').html('File');
      $('#fileView').html(callback['fileView']);
   }
	function myFunction(no) {
      // var form = '#form_nilai'+no;
      var id_soal = $('#id_soal_'+no).val();
      var id_jawaban = $('#id_jawaban_'+no).val();
      var kode_materi = $('#kode_materi_'+no).val();
      var id_karyawan = $('#id_karyawan_'+no).val();
      var inputnilai = $('#inputnilai_'+no).val();
      if(isNaN(inputnilai)){
         alert("PENILAIAN HARUS ANGKA");
      }else{
         if(inputnilai == '' || inputnilai == null){
            alert("PENILAIAN TIDAK BOLEH KOSONG");
         }else{
            if(inputnilai < 1 || inputnilai > 100){
               alert('NILAI HARUS RENTANG 1 SAMPAI 100  !!');
            }else{
               var datax = {
                  id_soal:id_soal,
                  id_jawaban:id_jawaban,
                  kode_materi:kode_materi,
                  id_karyawan:id_karyawan,
                  inputnilai:inputnilai,
               };
               submitAjax("<?php echo base_url('learning/add_penilaian_project')?>",null, datax, null, null,'status');
            }
         }
      }
	}
</script>