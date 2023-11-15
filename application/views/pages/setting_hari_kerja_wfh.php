<div class="content-wrapper">
  <section class="content-header">
    <h1>
      <i class="fa fa-gears"></i> Setting Aplikasi
      <small>Setting Hari Kerja WFH</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
      <li class="active">Setting Hari Kerja WFH</li>
    </ol>
  </section>
  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box box-success">
          <div class="box-header with-border">
            <h3 class="box-title"><i class="fas fa-briefcase"></i> Hari Kerja WFH</h3>
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
                      <?php if (in_array($access['l_ac']['imp'], $access['access'])) {
                           echo '<button class="btn btn-primary" type="button" data-toggle="modal" data-target="#import" aria-expanded="false" aria-controls="import" style="margin-right: 2px;"><i class="fas fa-cloud-upload-alt"></i> Import</button>';
                        } ?>
                        <?php if (in_array($access['l_ac']['rkp'], $access['access'])) { ?>
                        <div class="btn-group">
                           <button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown"><i class="fas fa-file-excel-o"></i> Export
                           <span class="fa fa-caret-down"></span></button>
                           <ul class="dropdown-menu">
                              <li><a href="<?php echo base_url('rekap/exportTemplateWFH/template');?>">Export Template</a></li>
                              <li><a href="<?php echo base_url('rekap/exportTemplateWFH/data');?>">Export Data</a></li>
                           </ul>
                        </div>
                        <?php } ?>
                    </div>
                      <div class="pull-right" style="font-size: 8pt;">
                        <i class="fa fa-toggle-on stat scc"></i> Aktif<br>
                        <i class="fa fa-toggle-off stat err"></i> Tidak Aktif
                      </div>
                  </div>
                </div>
              </div>
            </div>
            <br>
            <div class="row">
              <div class="col-md-12">
                <table id="table_data" class="table table-bordered table-striped table-responsive" width="100%">
                  <thead>
                    <tr>
                      <th>No.</th>
                      <th>NIK</th>
                      <th>Nama Karyawan</th>
                      <th>Jabatan</th>
                      <th>Bagian</th>
                      <th>Lokasi Kerja</th>
                      <th>Hari Kerja WFH</th>
                      <th>Hari Kerja Non WFH</th>
                      <th>Jenis</th>
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
        </div><br><hr>
        <div class="row">
            <div class="col-md-6">
              <div class="form-group col-md-12">
                  <label class="col-md-6 control-label">NIK</label>
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
                  <label class="col-md-6 control-label">Jumlah Hari Kerja WFH</label>
                  <div class="col-md-6" id="data_wfh_view"></div>
              </div>
              <div class="form-group col-md-12">
                  <label class="col-md-6 control-label">Jumlah Hari Kerja Non WFH</label>
                  <div class="col-md-6" id="data_non_wfh_view"></div>
              </div>
              <div class="form-group col-md-12">
                  <label class="col-md-6 control-label">Jenis Kerja</label>
                  <div class="col-md-6" id="data_jenis_kerja_view"></div>
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
          echo '<button type="button" class="btn btn-info" onclick="edit_modal()"><i class="fa fa-edit"></i> Edit</button>';
        }?>
        <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
      </div>
    </div>
  </div>
</div>

<!-- edit -->
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
            <label>Nama Karyawan</label>
            <input type="text" placeholder="Masukkan Nama" id="data_nama_edit" name="nama" value="" class="form-control" readonly="readonly">
          </div>
          <div class="form-group">
            <label>Jabatan</label>
            <input type="text" placeholder="Masukkan jabatan" id="data_jabatan_edit" name="jabatan" value="" class="form-control" readonly="readonly">
          </div>
          <div class="form-group">
            <label>Lokasi Kerja</label>
            <input type="text" placeholder="Masukkan Lokasi Kerja" id="data_loker_edit" name="loker" value="" class="form-control" readonly="readonly">
          </div>
          <div class="form-group">
            <label>Hari Kerja Non WFH</label>
            <input type="number" placeholder="Masukkan Hari Kerja Non WFH" id="data_wfh_edit" name="non_wfh" value="" class="form-control" required="required">
          </div>
          <div class="form-group">
            <label>Hari Kerja WFH</label>
            <input type="number" placeholder="Masukkan Hari Kerja WFH" id="data_non_wfh_edit" name="wfh" value="" class="form-control" required="required">
          </div>
					<div class="form-group">
						<label>Jenis Kerja</label>
						<?php
              $wfh = $this->otherfunctions->getWFHList();
							$sel = [null];
							$exsel = array('class'=>'form-control select2','id'=>'data_jenis_kerja_edit','required'=>'required','style'=>'width:100%');
							echo form_dropdown('jenis',$wfh,$sel,$exsel);
						?>
					</div>
        </div>
        <div class="modal-footer">
          <button type="button" onclick="do_edit()" class="btn btn-success" id="btn_edit"><i class="fa fa-floppy-o"></i> Simpan</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
        </div>
      </form>
    </div>
  </div>
</div>
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
<!-- delete -->
<div id="modal_delete_partial"></div>
<script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
<script type="text/javascript">
    var url_select="<?php echo base_url('global_control/select2_global');?>";
    //wajib diisi
    var table="admin";
    var column="id_admin";
    $(document).ready(function(){
      form_key("form_reset","btn_rst");
      $('#add_button').click(function () {
        getSelect2("<?php echo base_url('employee/mutasi_jabatan/employee')?>",'data_karyawan_add');
        select_data('data_usergroup_add',url_select,'master_user_group','id_group','nama');
      });
      tableData('all');
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
         var urladd = "<?php echo base_url(); ?>rekap/importWFH";
         submitAjaxFile(urladd,data_add,'#import','#progress2','.all_btn_import');
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
      $('#table_data').DataTable( {
         ajax: {
            url: "<?php echo base_url('employee/employee/view_all_wfh')?>",
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
         {   targets: [9,10,11],
            width: '6%',
            render: function ( data, type, full, meta ) {
               return '<center>'+data+'</center>';
            }
         },
         ]
      });
   }
    function view_modal(id) {
      var data={id_karyawan:id};
      var callback=getAjaxData("<?php echo base_url('employee/employee/view_one_wfh')?>",data);  
      $('#view').modal('show');
      $('.header_data').html(callback['nama']);
      $('#data_foto_view').html('<img class="profile-user-img img-responsive img-circle view_photo" data-source-photo="'+callback['foto']+'" src="'+callback['foto']+'" alt="User profile picture">');
      $('#data_nik_view').html(callback['nik']);
      $('#data_nama_view').html(callback['nama']);
      $('#data_jabatan_view').html(callback['nama_jabatan']);
      $('#data_loker_view').html(callback['nama_loker']);
      $('#data_wfh_view').html(callback['wfh']);
      $('#data_non_wfh_view').html(callback['non_wfh']);
      $('#data_jenis_kerja_view').html(callback['jenis_kerja']);
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
      var id = $('input[name="data_id_view"]').val();
      var data={id_karyawan:id};
      var callback=getAjaxData("<?php echo base_url('employee/employee/view_one_wfh')?>",data); 
      $('#view').modal('toggle');
      setTimeout(function () {
        $('#edit').modal('show');
      },500); 
      $('.header_data').html(callback['nama']);
      $('#data_id_edit').val(callback['id']);
      $('#data_nik_edit').val(callback['nik']);
      $('#data_nama_edit').val(callback['nama']);
      $('#data_jabatan_edit').val(callback['nama_jabatan']);
      $('#data_loker_edit').val(callback['nama_loker']);
      $('#data_wfh_edit').val(callback['wfh_edit']);
      $('#data_non_wfh_edit').val(callback['non_wfh_edit']);
      $('#data_jenis_kerja_edit').val(callback['jenis_kerja_edit']).trigger('change');
    }
    function do_edit(){
      if($("#form_edit")[0].checkValidity()) {
        submitAjax("<?php echo base_url('employee/edit_wfh')?>",'edit','form_edit',null,null);
        $('#table_data').DataTable().ajax.reload();
      }else{
        notValidParamx();
      } 
    }
  function checkFile(idf,idt,btnx) {
    var fext = ['xls', 'xlsx', 'csv', 'ods', 'ots'];
    pathFile(idf,idt,fext,btnx);
  }
</script>