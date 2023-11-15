<div class="content-wrapper">
   <section class="content-header">
      <h1>
         <i class="fas fa-sliders-h"></i> Data Soal Learning
      </h1>
      <ol class="breadcrumb">
         <li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
         <li class="active">Data Soal Learning</li>
      </ol>
   </section>
   <section class="content">
      <div class="row">
         <div class="col-md-12">
            <div class="box box-success">
               <div class="box-header with-border">
                  <h3 class="box-title"><i class="fas fa-sliders-h"></i> Data Soal Learning <small id="name_materi_header"></small></h3>
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
                                          echo '<a href="#add" data-toggle="collapse"  data-parent="#accordion" class="btn btn-success"><i class="fa fa-plus"></i> Tambah Soal</a>  ';
                                          echo '<a href="#addProject" data-toggle="collapse"  data-parent="#accordion" class="btn btn-info"><i class="fa fa-plus"></i> Tambah Soal Project</a>';
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
                                             <label>Kode Soal</label>
                                             <input type="text" placeholder="Masukkan Kode Soal" id="data_kode_soal_add" name="kode_soal" class="form-control" required="required" readonly="readonly">
                                          </div>
                                          <div class="form-group">
                                             <label>Materi</label>
                                             <input type="text" placeholder="Masukkan Kode Materi" id="data_kode_addx" name="nama_materi" class="form-control" required="required" readonly="readonly">
                                          </div>
                                          <div class="form-group">
                                             <label>Nama Soal</label>
                                             <textarea name="soal" id="soal_add" class="form-control" required="required" cols="30" rows="10" placeholder="Masukkan Soal"></textarea>
                                          </div>
                                          <p class="text-muted"><i>File Soal harus bertipe *.jpg, *.jpeg, *.png</i></p>
                                          <div class="form-group">
                                             <div class="input-group">
                                                   <label class="input-group-btn">
                                                      <span class="btn btn-primary"> 
                                                         <i class="fas fa-folder-open"></i> Pilih File <input type="file" id="btnAddSoal" name="file" style="display: none;" onchange="checkFile('#btnAddSoal',null,null,event)">
                                                      </span>
                                                   </label>
                                                   <input type="text" class="form-control" readonly>
                                             </div>
                                          </div>
                                          <div class="form-group">
                                             <label>Pilihan A </label>
                                             <input type="text" placeholder="Masukkan Pilihan A" name="choice_a" class="form-control field" required="required">
                                          </div>
                                          <div class="form-group">
                                             <label>Pilihan B </label>
                                             <input type="text" placeholder="Masukkan Pilihan B" name="choice_b" class="form-control field" required="required">
                                          </div>
                                          <div class="form-group">
                                             <label>Pilihan C </label>
                                             <input type="text" placeholder="Masukkan Pilihan C" name="choice_c" class="form-control field" required="required">
                                          </div>
                                          <div class="form-group">
                                             <label>Pilihan D </label>
                                             <input type="text" placeholder="Masukkan Pilihan D" name="choice_d" class="form-control field" required="required">
                                          </div>
                                          <div class="form-group">
                                             <label>Pilihan E </label>
                                             <input type="text" placeholder="Masukkan Pilihan E" name="choice_e" class="form-control field" required="required">
                                          </div>
                                          <div class="form-group">
                                             <label>Jawaban Benar</label>
                                             <select class="form-control select2" id="correct_answer_add" name="correct_answer" style="width: 100%;"></select>
                                          </div>
                                          <!-- <div class="form-group">
                                             <label>Jenis Soal</label>
                                             <select class="form-control select2" id="jenis_soal_add" name="jenis_soal" style="width: 100%;"></select>
                                          </div> -->
                                          <div class="form-group">
                                             <button type="button" onclick="do_add()" id="btn_add" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
                                             <button type="submit" id="btn_addx" style="display: none;"></button>
                                          </div>
                                       </form>
                                    </div>
                                 </div>
                                 <div class="collapse" id="addProject">
                                    <div class="col-md-2"></div>
                                    <div class="col-md-8">
                                       <form id="form_add_project">
                                            <input type="hidden" name="kode_materi" id="kode_materiP_add">
                                          <div class="form-group">
                                             <label>Kode Soal</label>
                                             <input type="text" placeholder="Masukkan Kode Soal" id="data_kode_soalP_add" name="kode_soal_project" class="form-control" required="required" readonly="readonly">
                                          </div>
                                          <div class="form-group">
                                             <label>Materi</label>
                                             <input type="text" placeholder="Masukkan Kode Materi" id="data_kode_addPx" name="nama_materi" class="form-control" required="required" readonly="readonly">
                                          </div>
                                          <div class="form-group">
                                             <label>Nama Soal Project</label>
                                             <textarea name="soal" class="form-control" required="required" cols="30" rows="10" placeholder="Masukkan Soal"></textarea>
                                          </div>
                                          <div class="form-group">
                                             <label>Keterangan</label>
                                             <textarea name="ket" class="form-control" required="required" cols="30" rows="10" placeholder="Masukkan Soal"></textarea>
                                          </div>
                                          <div class="form-group">
                                             <button type="button" onclick="do_addProject()" id="btn_add" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
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
                                 <th>File</th>
                                 <th>Soal</th>
                                 <th>Jawaban A</th>
                                 <th>Jawaban B</th>
                                 <th>Jawaban C</th>
                                 <th>Jawaban D</th>
                                 <th>Jawaban E</th>
                                 <th>Jawaban Benar</th>
                                 <th>Status</th> 
                                 <th>Aksi</th>
                              </tr>
                           </thead>
                           <tbody>
                           </tbody>
                        </table>
                     </div>
                  </div>
                  <hr>
                  <legend style="color: green;"><i class="fa fa-fw fa-book"></i> Soal Project</legend>
                  <div class="row">
                     <div class="col-md-12">
                        <table id="table_dataP" class="table table-bordered table-striped table-responsive" width="100%">
                           <thead>
                              <tr>
                                 <th>No.</th>
                                 <th>Kode</th>
                                 <th>Soal Project</th>
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
               <div class="col-md-12 text-center">
                  <div id="data_view_file"></div>
               </div>
               <div class="col-md-6">
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Kode Materi</label>
                     <div class="col-md-6" id="data_kode_view"></div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Nama Materi</label>
                     <div class="col-md-6" id="data_name_view"></div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Soal</label>
                     <div class="col-md-6" id="data_soal_view"></div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Jawaban A</label>
                     <div class="col-md-6" id="data_choice_A_view"></div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Jawaban B</label>
                     <div class="col-md-6" id="data_choice_B_view"></div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Jawaban C</label>
                     <div class="col-md-6" id="data_choice_C_view"></div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Jawaban D</label>
                     <div class="col-md-6" id="data_choice_D_view"></div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Jawaban E</label>
                     <div class="col-md-6" id="data_choice_E_view"></div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Jawaban Benar</label>
                     <div class="col-md-6" id="data_correct_answer_view"></div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Jenis Soal</label>
                     <div class="col-md-6" id="data_jenis_soal_view"></div>
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
   <div class="modal-dialog modal-md">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h2 class="modal-title">Edit Data <b class="text-muted header_data"></b></h2>
         </div>
         <div class="modal-body">
            <form id="form_edit">
               <input type="hidden" id="data_id_edit" name="id" value="">
               <input type="hidden" id="data_kode_edit" name="kode" value="">
               <div class="form-group">
                  <label>Nama Soal</label>
                  <textarea name="soal" id="soal_edit" class="form-control" required="required" cols="30" rows="10" placeholder="Masukkan Soal"></textarea>
               </div>
               <p class="text-muted"><i>File Soal harus bertipe *.jpg, *.jpeg, *.png</i></p>
               <div class="form-group">
                  <div class="input-group">
                     <label class="input-group-btn">
                        <span class="btn btn-primary"> 
                           <i class="fas fa-folder-open"></i> Pilih File <input type="file" id="btnEditSoal" name="file" style="display: none;" onchange="checkFile('#btnEditSoal',null,null,event)">
                        </span>
                     </label>
                     <input type="text" class="form-control" id="nameFileEdit" readonly>
                  </div>
               </div>
               <input type="hidden" class="form-control" id="nameFileOld" name="file_old" readonly>
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
               <!-- <div class="form-group">
                  <label>Jenis Soal</label>
                  <select class="form-control select2" id="jenis_soal_edit" name="jenis_soal" style="width: 100%;"></select>
               </div> -->
               <div class="form-group">
                  <label>Pilih Materi Soal</label>
                  <select class="form-control select2" id="select_materi_edit" name="select_materi" style="width: 100%;"></select>
               </div>
            </div>
            <div class="modal-footer">
               <button type="button" onclick="do_edit()" id="btn_edit" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
               <button type="submit" id="btn_editx" style="display: none;"></button>
               <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
            </div>
         </form>
      </div>
   </div>
</div>
<!-- SOAL PROJECT -->
<div id="viewProject" class="modal fade" role="dialog">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h2 class="modal-title">Detail Data <b class="text-muted header_data"></b></h2>
            <input type="hidden" name="data_id_viewP">
         </div>
         <div class="modal-body">
            <div class="row">
               <div class="col-md-6">
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Kode Materi</label>
                     <div class="col-md-6" id="data_kode_viewP"></div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Nama Materi</label>
                     <div class="col-md-6" id="data_name_viewP"></div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Soal Project</label>
                     <div class="col-md-6" id="data_soal_viewP"></div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Keterangan</label>
                     <div class="col-md-6" id="data_keterangan_viewP"></div>
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Status</label>
                     <div class="col-md-6" id="data_status_viewP"></div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Dibuat Tanggal</label>
                     <div class="col-md-6" id="data_create_date_viewP"></div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Diupdate Tanggal</label>
                     <div class="col-md-6" id="data_update_date_viewP"></div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Dibuat Oleh</label>
                     <div class="col-md-6" id="data_create_by_viewP">
                     </div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Diupdate Oleh</label>
                     <div class="col-md-6" id="data_update_by_viewP">
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="modal-footer">
            <?php if (in_array($access['l_ac']['edt'], $access['access'])) {
               echo '<button type="submit" class="btn btn-info" onclick="edit_modal2()"><i class="fa fa-edit"></i> Edit</button>';
            }?>
            <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
         </div>
      </div>
   </div>
</div>
<div id="editProject" class="modal fade" role="dialog">
   <div class="modal-dialog modal-md">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h2 class="modal-title">Edit Data <b class="text-muted header_data"></b></h2>
         </div>
         <div class="modal-body">
            <form id="form_edit_project">
               <input type="hidden" id="data_id_editP" name="id" value="">
               <input type="hidden" id="data_kode_edit_oldP" name="kode_old" value="">
               <input type="hidden" id="data_kode_editP" name="kode" value="">
               <div class="form-group">
                  <label>Nama Soal Project</label>
                  <textarea name="soal" id="soal_editP" class="form-control" required="required" cols="30" rows="10" placeholder="Masukkan Soal"></textarea>
               </div>
               <div class="form-group">
                  <label>Keterangan</label>
                  <textarea name="keterangan" id="keterangan_editP" class="form-control" required="required" cols="30" rows="10" placeholder="Masukkan Soal"></textarea>
               </div>
               <div class="form-group">
                  <label>Pilih Materi Soal</label>
                  <select class="form-control select2" id="select_materi_editP" name="select_materi" style="width: 100%;"></select>
               </div>
            </div>
            <div class="modal-footer">
               <button type="button" onclick="do_editProject()" id="btn_editP" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
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
   var table="learn_soal";
   var column="id";
   $(document).ready(function(){
		getSelect2("<?php echo base_url('learning/getSoal/select_correct_answer')?>",'correct_answer_add');
		getSelect2("<?php echo base_url('learning/getSoal/select_jenis_materi')?>",'jenis_soal_add');
      var dataxx={ kode:"<?=$this->codegenerator->decryptChar($this->uri->segment(3));?>"};
      var callback=getAjaxData("<?php echo base_url('learning/getMateriLearning/view_one')?>",dataxx);  
        $('#kode_materi_add').val(callback['kode']);
        $('#kode_materiP_add').val(callback['kode']);
        $('#data_kode_addx').val(callback['nama']);
        $('#data_kode_addPx').val(callback['nama']);
        $('#name_materi_header').html(callback['nama']);
        refreshCode();
        $('#table_data').DataTable( {
            ajax: {
                url: "<?php echo base_url('learning/getSoal/view_all/')?>",
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
            {   targets: 9,
                width: '10%',
                render: function ( data, type, full, meta ) {
                return '<center>'+data+'</center>';
                }
            },
            {   targets: 10, 
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
            var urlx = "<?php echo base_url('learning/add_soal'); ?>";
            submitAjaxFile(urlx,data,null,null,null);
         });
         $('#form_edit').submit(function(e){
            e.preventDefault();
            var data = new FormData(this);
            var urlx = "<?php echo base_url('learning/edit_soal'); ?>";
            submitAjaxFile(urlx,data,'#edit',null,null);
         });
        dataTableProject();
   });
   function refreshCode() {
      kode_generator("<?php echo base_url('learning/getSoal/kode');?>",'data_kode_soal_add');
      kode_generator("<?php echo base_url('learning/getSoal/kodeSoalProject');?>",'data_kode_soalP_add');
   }
   function view_modal(id) {
      var data={id:id};
      var callback=getAjaxData("<?php echo base_url('learning/getSoal/view_one')?>",data);  
      $('#view').modal('show');
      $('.header_data').html(callback['nama']);
      $('#data_kode_view').html(callback['kode']);
      $('#data_view_file').html(callback['file']);
      $('#data_name_view').html(callback['nama']);
      $('#data_soal_view').html(callback['soal']);
      $('#data_choice_A_view').html(callback['choice_A']);
      $('#data_choice_B_view').html(callback['choice_B']);
      $('#data_choice_C_view').html(callback['choice_C']);
      $('#data_choice_D_view').html(callback['choice_D']);
      $('#data_choice_E_view').html(callback['choice_E']);
      $('#data_correct_answer_view').html(callback['correct_answer']);
      $('#data_jenis_soal_view').html(callback['jenis_soal']);
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
      $('#data_id_editP').val(callback['id']);
      $('#data_kode_edit').val(callback['kode']);
      $('#nameFileEdit').val(callback['data_file']);
      $('#nameFileOld').val(callback['data_file']);
      $('#data_kode_editP').val(callback['kode']);
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
      var callback=getAjaxData("<?php echo base_url('learning/getSoal/view_one')?>",data);
      var datax={table:table,column:column,id:id,nama:callback['soal']};
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
         $('#btn_editx').click();
         $('#form_edit')[0].reset();
         refreshCode();
      }else{
         notValidParamx();
      }
   }
   function do_add(){
      if($("#form_add")[0].checkValidity()) {
         $('#btn_addx').click();
         $('#form_add')[0].reset();
         $('#table_data').DataTable().ajax.reload(function (){
            Pace.restart();
         });
         $('#form_add')[0].reset();
         refreshCode();
      }else{
         notValidParamx();
      }
   }
   // =========================================== SOAL PROJECT ===========================================
   function dataTableProject(){      
      $('#table_dataP').DataTable( {
            ajax: {
                url: "<?php echo base_url('learning/getSoal/view_all_soal_project/')?>",
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
   }
   function do_addProject(){
      if($("#form_add_project")[0].checkValidity()) {
         submitAjax("<?php echo base_url('learning/add_soal_project')?>",null,'form_add_project',null,null);
         $('#table_dataP').DataTable().ajax.reload(function (){
            Pace.restart();
         });
         $('#form_add_project')[0].reset();
         refreshCode();
      }else{
         notValidParamx();
      } 
   }
   function do_status2(id,data) {
      var data_table={status:data};
      var where={id:id};
      var datax={table:'learn_soal_project',where:where,data:data_table};
      submitAjax("<?php echo base_url('global_control/change_status')?>",null,datax,null,null,'status');
      $('#table_dataP').DataTable().ajax.reload();
   }
   function view_modal2(id) {
      var data={id:id};
      var callback=getAjaxData("<?php echo base_url('learning/getSoal/view_one_project')?>",data);  
      $('#viewProject').modal('show');
      $('.header_data').html(callback['nama']);
      $('#data_kode_viewP').html(callback['kode']);
      $('#data_name_viewP').html(callback['nama']);
      $('#data_soal_viewP').html(callback['soal']);
      $('#data_keterangan_viewP').html(callback['keterangan']);
      var status = callback['status'];
      if(status==1){
         var statusval = '<b class="text-success">Aktif</b>';
      }else{
         var statusval = '<b class="text-danger">Tidak Aktif</b>';
      }
      $('#data_status_viewP').html(statusval);
      $('#data_create_date_viewP').html(callback['create_date']+' WIB');
      $('#data_update_date_viewP').html(callback['update_date']+' WIB');
      $('input[name="data_id_viewP"]').val(callback['id']);
      $('#data_create_by_viewP').html(callback['nama_buat']);
      $('#data_update_by_viewP').html(callback['nama_update']);
   }
   function edit_modal2() {
		getSelect2("<?php echo base_url('learning/getSoal/select_materi')?>",'select_materi_editP');
      var id = $('input[name="data_id_viewP"]').val();
      var data={id:id};
      var callback=getAjaxData("<?php echo base_url('learning/getSoal/view_one_project')?>",data); 
      $('#viewProject').modal('toggle');
      setTimeout(function () {
         $('#editProject').modal('show');
      },600); 
      $('.header_data').html(callback['nama']);
      $('#data_id_editP').val(callback['id']);
      $('#data_kode_edit_oldP').val(callback['kode']);
      $('#data_kode_editP').val(callback['kode']);
      $('#soal_editP').val(callback['soal']);
      $('#keterangan_editP').val(callback['keterangan']);
      $('#select_materi_editP').val(callback['kode_materi']).trigger('change');
   }
   function delete_modal(id) {
      var data={id:id};
      var callback=getAjaxData("<?php echo base_url('learning/getSoal/view_one_project')?>",data);
      var datax={table:table,column:column,id:id,nama:callback['soal']};
      loadModalAjax("<?php echo base_url('pages/load_modal_delete')?>",'modal_delete_partial',datax,'delete');
   }
   function do_editProject(){
      if($("#form_edit_project")[0].checkValidity()) {
         submitAjax("<?php echo base_url('learning/edit_soal_project')?>",'editProject','form_edit_project',null,null);
         $('#table_dataP').DataTable().ajax.reload();
      }else{
         notValidParamx();
      } 
   }
   function delete_modal2(id) {
      var data={id:id};
      var callback=getAjaxData("<?php echo base_url('learning/getSoal/view_one_project')?>",data);
      var datax={table:'learn_soal_project',column:'id',id:id,nama:callback['soal'],table_view:'table_dataP'};
      loadModalAjax("<?php echo base_url('pages/load_modal_delete')?>",'modal_delete_partial',datax,'delete');
   }
   function checkFile(idf,idt,btnx) {
      var fext = ['jpg', 'jpeg', 'png'];
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