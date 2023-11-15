  <div class="content-wrapper">
    <div class="alert alert-info">
      <i class="fa fa-calendar faa-shake animated text-blue" style="font-size: 14pt;"></i>
      <?php 
      if ($agd != "") {
        echo ' <b>Agenda Penilaian Sikap (360°) '.$agd['nama_agenda'].' Tahun '.$agd['tahun'].' '.$agd['periode'].'</b>';
      }
      ?>
    </div>
    <section class="content-header">
      <h1>
       <a class="back" onclick="window.history.back()"><i class="fa fa-chevron-circle-left "></i></a> <i class="fa fa-users"></i> Daftar Karyawan Sikap
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo base_url('kpages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
        <li><a href="<?php echo base_url('kpages/attitude_tasks');?>"><i class="fa fa-calendar"></i> Daftar Agenda</a></li>
        <li class="active">Daftar Karyawan</li>
      </ol>
    </section>
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-users"></i> Daftar Karyawan Sikap</h3>
              <div class="box-tools pull-right">
                <button class="btn btn-box-tool" onclick="reload_table('table_data')" data-toggle="tooltip" title="Refresh Table"><i class="fa fa-refresh"></i></button>
                <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
              <div class="row">
                <div class="col-md-12">
                  <div class="btn-group">
                      <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown"><i class="fas fa-file-export"></i> Export Import Aspek Sikap
                      <span class="fa fa-caret-down"></span></button>
                      <ul class="dropdown-menu">
                        <li><a href="<?php echo base_url('krekap/exportAspekSikap/'.$this->codegenerator->encryptChar($kode));?>"><i class="fa fa-download"></i> Export Template Aspek Sikap</a></li>
                        <li><a  data-toggle="modal" data-target="#importAspekSikap"><i class="fa fa-upload"></i> Import Aspek Sikap</a></li>
                      </ul>
                  </div><br><br>
                  <div class="callout callout-info"><label><i class="fa fa-info-circle"></i> Bantuan</label><br>Pilih Nama Karyawan untuk melakukan Input Penilaian Kinerja</div>
                  <table  id="table_data" class="table table-bordered table-striped table-hover" width="100%">
                    <thead>
                      <tr>
                        <th>No.</th>
                        <th>Nama Karyawan</th>
                        <th>Jabatan</th>
                        <th>Bagian</th>
                        <th>Lokasi Kerja</th>
                        <th>Anda Sebagai</th>
                        <th>Status</th> 
                        <th>Nilai Anda</th>
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

<div class="modal fade" id="importAspekSikap" role="dialog">
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
                     <li>Gunakan File Template Aspek Sikap yang telah anda Download dari <b>"Export Template Aspek Sikap"</b></li>
                  </ul>
               </div>
               <p class="text-muted">File harus tipe *.xls, *.xlsx, *.csv, *.ods dan *.ots</p>
               <input id="uploadFile" placeholder="Nama File" readonly="readonly" class="form-control" required="required">
               <span class="input-group-btn">
                  <div class="fileUpload btn btn-warning btn-flat">
                     <span><i class="fa fa-folder-open"></i> Pilih File</span>
                     <input id="uploadBtnx" type="file" class="upload" name="file" onchange="checkFile('#uploadBtnx','#uploadFile','#save')" />
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
</div>
<script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
  <script type="text/javascript">
    var url_select="<?php echo base_url('global_control/select2_global');?>";
    $(document).ready(function(){
      $('#table_data').DataTable( {
        ajax: {
          url: "<?php echo base_url('kagenda/input_attitude_task_value/view_all/'.$this->codegenerator->encryptChar($kode))?>"
        },
        columnDefs: [
        {   targets: 0, 
          width: '5%',
          render: function ( data, type, full, meta ) {
            return '<center>'+(meta.row+1)+'.</center>';
          }
        },
        {   targets: 6,
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
        ]
      });
      $('#save').click(function(){
        $('.all_btn_import').attr('disabled','disabled');
        setTimeout(function () {
          $('#savex').click();
        },1000);
      })
      $('#form_import').submit(function(e){
        e.preventDefault();
            $('#progress2').html('<i class="fa fa-spinner fa-pulse fa-fw"></i> Mohon Tunggu, Data sedang di upload....');
        $('#progress2').show();
        var data_add = new FormData(this);
        var urladd = "<?php echo base_url('krekap/import_aspek_sikap/'.$this->codegenerator->encryptChar($kode)); ?>";
        submitAjaxFile(urladd,data_add, null, null,'.all_btn_import','table_data');
        $('#table_data').DataTable().ajax.reload(function (){
          Pace.restart();
        });
        $('#importAspekSikap').modal('hide');
        $('#progress2').hide();
      });
    })
  	function checkFile(idf,idt,btnx) {
  		var fext = ['xls', 'xlsx', 'csv', 'ods', 'ots'];
  		pathFile(idf,idt,fext,btnx);
  	}
  </script>