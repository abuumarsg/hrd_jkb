
  <div class="content-wrapper">
    <section class="content-header">
      <h1>
        <i class="fa fa-flask"></i> Rancangan 
        <small>Detail KPI  <?php echo $nama; ?></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
        <li><a href="<?php echo base_url('pages/data_konsep_kpi');?>"><i class="fas fa-flask"></i> Rancangan KPI</a></li>
        <li><a href="<?php echo base_url('pages/view_data_konsep_kpi/'.$this->codegenerator->encryptChar($kode));?>"><i class="fas fa-briefcase"></i> Daftar Jabatan <?php echo $nama; ?></a></li>
        <li class="active">Detail KPI Jabatan</li>
      </ol>
    </section>
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box box-success">
            <div class="box-header with-border"> 
              <h3 class="box-title"><i class="fa fa-list"></i> KPI <?php echo $jabatan;?></h3>
              <div class="box-tools pull-right">
                <button class="btn btn-box-tool" onclick="reload_table('table_data');reload_table('table_data_rutin');reload_table('table_data_tambahan');reload_table('table_data_karyawan');reload_table('table_data_bobotkpi');" data-toggle="tooltip" title="Refresh Table"><i class="fas fa-sync"></i></button>
                <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
              <div class="row">
                <div class="col-md-12">
                  <div class="pull-left">
                    <?php 
                    if (in_array('IMP', $access['access'])) {
                      echo '<button class="btn btn-primary" type="button" data-toggle="modal" data-target="#import" aria-expanded="false" aria-controls="import" id="btn_import"><i class="fas fa-cloud-upload-alt"></i> Import</button> ';
                    }
                    if (in_array('EXP', $access['access'])) {
                      echo '<div class="btn-group">
                            <button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown"><i class="fas fa-file-excel-o"></i> Export
                            <span class="fa fa-caret-down"></span></button>
                            <ul class="dropdown-menu">
                              <li><a onclick="print_template()">Export Template</a></li>
                              <li><a onclick="rekap_data()">Export Data</a></li>
                            </ul>
                          </div>';
                    }
                    ?>
                  </div>
                  <div class="modal fade" id="import" role="dialog">
                    <div class="modal-dialog">
                      <div class="modal-content text-center">
                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal">&times;</button>
                          <h2 class="modal-title">Import Data Dari Excel</h2>
                        </div>
                        <form id="form_import" action="#">
                          <input type="hidden" name="tabel" value="<?php echo $tabel;?>">
                          <input type="hidden" name="kode_concept" value="<?php echo $this->uri->segment(3); ?>">
                          <input type="hidden" name="kode_jabatan" value="<?php echo $kode_jabatan;?>">
                          <div class="modal-body">
                            <div class="callout callout-danger text-left"><b><i class="fa fa-warning"></i> Peringatan</b><br>Pastikan karyawan yang berada pada nama jabatan yang sama memiliki <b>target dan bobot yang sama</b>!</div>
                            <p class="text-muted">File Data Template Konsep KPI harus tipe *.xls, *.xlsx, *.csv, *.ods dan *.ots</p>
                            <input id="uploadFilex" placeholder="Nama File" readonly="readonly" class="form-control" required="required">
                            <span class="input-group-btn">
                              <div class="fileUpload btn btn-warning">
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
                  </div>
                </div>
              </div><br>
              <div class="callout callout-info">
                <b><i class="fa fa-bullhorn"></i> Petunjuk</b><br>
                <ul>
                  <li>Edit data konsep KPI akan berpengaruh pada <b>Agenda KPI <b class="text-red">(yang belum dilakukan Validasi dan yang hanya terkait dengan <?=$nama?>)</b></b>. Pastikan data diedit dengan benar!</li>
                  <li>Jumlah bobot masing - masing <b>harus 100%</b></li>
                  <li>Jika ingin melakukan perubahan pada poin dan satuan yang ada pada <b>Rancangan KPI</b>, maka Anda harus melakukan perubahan pada menu <b>Master KPI</b> dan data <b>Rancangan KPI</b> akan <b class="text-green">menyesuaikan</b> perubahan yang Anda lakukan pada <b>Master KPI</b></li>
                </ul>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="panel panel-primary">
                    <div class="panel-heading"><b>Daftar KPI</b></div>
                    <div class="panel-body">
                      <div class="row">
                        <div class="col-md-12">
                          <div class="pull-left">
                            <?php if (in_array('ADD', $access['access'])) {
                              ?>
                              <button class="btn btn-success" type="button" data-toggle="collapse" data-target="#add" id="btn_add"><i class="fa fa-plus"></i> Tambah KPI</button>
                              <button class="btn btn-success" type="button" data-toggle="collapse" data-target="#add" id="btn_addx" style="display: none;"><i class="fa fa-plus"></i> Tambah KPI</button>
                              <?php
                            }?>
                          </div>
                        </div>
                      </div>
                      <?php if(in_array('ADD', $access['access'])){?>
                        <div class="collapse" id="add">
                          <br>
                          <form id="form_kpi_add">
                            <input type="hidden" name="kodeform" value="">
                            <input type="hidden" name="kode_kpi_hidden" value="">
                            <input type="hidden" name="tabel" value="<?php echo $tabel;?>">
                            <input type="hidden" name="kode_jabatan" value="<?php echo $kode_jabatan;?>">
                            <input type="hidden" name="kode_concept" value="<?php echo $this->uri->segment(3);?>">
                            <div class="col-md-12">
                              <div class="form-group">
                                <label>Pilih KPI</label>
                                <div class="clearfix"></div>
                                <div class="view-overflow">
                                  <table id="table_kpi" class="table table-bordered table-striped table-responsive" style="width: 100%;margin-top: 9px;">
                                    <thead>
                                      <tr>
                                        <th style="width: 10%;"></th>
                                        <th style="width: 45%;">Kode KPI</th>
                                        <th style="width: 45%;">KPI</th>
                                      </tr>
                                    </thead>
                                    <tbody id="get_kpi">
                                      
                                    </tbody>
                                  </table>
                                </div>
                              </div>
                              <div class="form-group">
                                <button type="button" onclick="do_add('kpi')" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
                              </div>
                            </div>
                          </form>
                        </div>
                      <?php } ?>
                      <br>
                      <div class="row">
                        <div class="col-md-12">
                          <form id="form_edit">
                            <input type="hidden" name="table" value="<?php echo $this->codegenerator->encryptChar($tabel);?>">
                            <input type="hidden" name="kode_jabatan" value="<?php echo $this->codegenerator->encryptChar($kode_jabatan);?>">
                            <input type="hidden" name="kode_concept" value="<?php echo $this->uri->segment(3);?>">
                          <table id="table_data" class="table table-bordered table-responsive" width="100%">
                            <thead>
                              <tr>
                                <th>No.</th>
                                <th>KPI</th>
                                <th>Sifat</th>
                                <th>Unit</th>
                                <th>Target</th>
                                <th>Bobot (%)</th>
                                <th>Penilai</th>
                                <th>Aksi</th>
                              </tr>
                            </thead>
                            <tbody>
                            </tbody>
                          </table>
                          <div class="row">
                            <div class="col-md-2">
                              <label> Jumlah Bobot KPI (%)</label>
                              <input type="text" class="form-control" readonly="readonly" id="count">
                            </div>
                            <div class="col-md-6">
                                <div id="bobot_err_msg" style="font-size:30pt;color:red"></div>
                            </div>
                          </div><br>
                          <div class="form-group">
                            <button type="button" onclick="do_edit()" id="btn_edit" class="btn btn-success" disabled="disabled"><i class="fa fa-floppy-o"></i> Simpan</button>
                          </div>
                          </form>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="col-md-12" style="padding-left: 0px;padding-right:0px">
                    <div class="panel panel-success">
                      <div class="panel-heading bg-green"><b><i class="fa fa-users"></i> Data Karyawan</b></div>
                      <div class="panel-body">
                        <div class="row">
                          <div class="col-md-12">
                            <div class="pull-left">
                              <?php 
                              if (in_array('ADD', $access['access'])) {
                               echo '<button class="btn btn-success" type="button" data-toggle="collapse" data-target="#add_karyawan" id="btn_karyawan_add"><i class="fa fa-plus"></i> Tambah Karyawan</button>';
                              }
                              ?>
                            </div>
                          </div>
                        </div>
                        <?php if(in_array('ADD', $access['access'])){?>
                          <div class="collapse" id="add_karyawan">
                            <br>
                            <form id="form_karyawan_add">
                              <input type="hidden" name="kodeform" value="karyawan">
                              <input type="hidden" name="tabel" value="<?php echo $tabel;?>">
                              <input type="hidden" name="kode_jabatan" value="<?php echo $kode_jabatan;?>">
                              <input type="hidden" name="kode_concept" value="<?php echo $this->uri->segment(3);?>">
                              <div class="col-md-2"></div>
                              <div class="col-md-8">
                                <div class="form-group">
                                  <label>Pilih Karyawan</label>
                                  <select class="form-control select2" name="kpi_karyawan[]" id="kpi_karyawan" multiple="multiple" style="width: 100%;"></select>
                                </div>
                                <div class="form-group">
                                  <button type="button" onclick="do_add('karyawan')" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
                                </div>
                              </div>
                            </form>
                          </div>
                        <?php } ?>
                        <br>
                        <div class="row">
                          <div class="col-md-12">
                            <table id="table_data_karyawan" class="table table-bordered table-responsive" width="100%">
                              <thead>
                                <tr>
                                  <th>No.</th>
                                  <th>NIK</th>
                                  <th>Nama</th>
                                  <th>Jabatan</th>
                                  <th>Bagian</th>
                                  <th>Lokasi Kerja</th>                                  
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
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div> 
  <!-- delete --> 
  <div id="delete" class="modal fade" role="dialog">
    <div class="modal-dialog modal-sm modal-danger">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title text-center">Konfirmasi Hapus</h4>
        </div>
        <form id="form_delete">
          <div class="modal-body text-center">
            <input type="hidden" id="data_column_delete" name="column">
            <input type="hidden" id="data_id_delete" name="id">
            <input type="hidden" id="data_kode_delete" name="kode">
            <input type="hidden" id="data_table_delete" name="table">
            <input type="hidden" id="data_kodeform_delete" name="kodeform">
            <input type="hidden" id="data_kode_rancangan" name="kode_rancangan">
            <p>Apakah anda yakin akan menghapus data ini ?</p>
          </div>
        </form>
        <div class="modal-footer">
          <button type="button" onclick="do_delete()" class="btn btn-primary"><i class="fa fa-trash"></i> Hapus</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
        </div>
      </div>
    </div>
  </div>
  <div id="view" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h2 class="modal-title">Detail Data <b class="text-muted header_data"></b></h2>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group col-md-12">
                <label class="col-md-6 control-label">Kode KPI</label>
                <div class="col-md-6" id="data_kode_view"></div>
              </div>
              <div class="form-group col-md-12">
                <label class="col-md-6 control-label">Nama KPI</label>
                <div class="col-md-6" id="data_name_view"></div>
              </div>
              <div class="form-group col-md-12">
                <label class="col-md-6 control-label">Rumus</label>
                <div class="col-md-6" id="data_rumus_view"></div>
              </div>
              <div class="form-group col-md-12">
                <label class="col-md-6 control-label">Unit / Satuan</label>
                <div class="col-md-6" id="data_unit_view"></div>
              </div>
              <div class="form-group col-md-12">
                <label class="col-md-6 control-label">Detail Rumus</label>
                <div class="col-md-6" id="data_detail_rumus_view"></div>
              </div>
              <div class="form-group col-md-12">
                <label class="col-md-6 control-label">Sumber Data</label>
                <div class="col-md-6" id="data_sumber_data_view"></div>
              </div>
              <div class="form-group col-md-12">
                <label class="col-md-6 control-label">Cara Menghitung</label>
                <div class="col-md-6" id="data_cara_menghitung_view"></div>
              </div>
              <div class="form-group col-md-12">
                <label class="col-md-6 control-label">Kaitan</label>
                <div class="col-md-6" id="data_kaitan_view"></div>
              </div>
              <div class="form-group col-md-12">
                <label class="col-md-6 control-label">Jenis Satuan</label>
                <div class="col-md-6" id="data_jenis_satuan_view"></div>
              </div>
              <div class="form-group col-md-12">
                <label class="col-md-6 control-label">Sifat</label>
                <div class="col-md-6" id="data_sifat_view"></div>
              </div>
              <div class="form-group col-md-12">
                <label class="col-md-6 control-label">Nilai Minimal</label>
                <div class="col-md-6" id="data_min_view"></div>
              </div>
              <div class="form-group col-md-12">
                <label class="col-md-6 control-label">Nilai Maksimal</label>
                <div class="col-md-6" id="data_max_view"></div>
              </div>
              <div class="form-group col-md-12">
                <label class="col-md-6 control-label">Jenis Batasan Poin</label>
                <div class="col-md-6" id="data_batasan_poin_view"></div>
              </div>
              <div class="form-group col-md-12">
                <label class="col-md-6 control-label">Penilai</label>
                <div class="col-md-6" id="data_penilai_view"></div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group col-md-12">
                <label class="col-md-6 control-label">Status</label>
                <div class="col-md-6" id="data_status_view">

                </div>
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
          <hr>
          <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">
              <h3 class="text-center">Rincian Point KPI</h3>
              <table class="table table-hover table-striped">
                <thead>
                  <tr class="bg-blue">
                    <th class="text-center">Point</th>
                    <th class="text-center">Satuan</th>
                  </tr>
                </thead>
                <tbody id="data_tr_view">
                </tbody>
              </table>
              
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
        </div>
      </div>
    </div>
  </div>
  <!-- <div id="modal_delete_partial"></div> -->
  <script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
  <script type="text/javascript">
    var url_select="<?php echo base_url('global_control/select2_global');?>";
    var url_select_where="<?php echo base_url('global_control/select2_global_where');?>";
    var url_add="<?php echo base_url('concept/view_detail_konsep_kpi/select_kpi');?>";

    var table="<?php echo $tabel;?>";
    var kode_j="<?php echo $kode_jabatan;?>";
    var kode_rancangan="<?php echo $this->codegenerator->encryptChar($kode);?>";
    $(document).ready(function(){
      $('#btn_import').click(function(){
        $('#form_import')[0].reset();
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
        var urladd = "<?php echo base_url('concept/import_detail_konsep_kpi'); ?>";
        submitAjaxFile(urladd,data_add,'#import','#progress2','.all_btn_import');
        reload_table('table_data');
      });
      $('#btn_karyawan_add').click(function(){
        var urlx="<?php echo base_url('concept/view_detail_konsep_kpi/select_karyawan');?>";
        var datax={table:table,jbt:kode_j};
        getSelect2(urlx,'kpi_karyawan',datax);
      })
      $('#btn_add').click(function(){
        $('#btn_addx').css('display','block');
        $('#btn_add').css('display','none');
        add_data_kpi();
      })
      $('#btn_addx').click(function(){
        $('#btn_add').css('display','block');
        $('#btn_addx').css('display','none');
        $('#table_kpi').DataTable().destroy();
      })
      dataXtable();
      dataXtableKaryawan('karyawan','<?php echo base_url('concept/view_detail_konsep_kpi/data_karyawan/')?>');
    });
    function checkFile(idf,idt,btnx) {
      var fext = ['xls', 'xlsx', 'csv', 'ods', 'ots'];
      pathFile(idf,idt,fext,btnx);
    }
    function dataXtableKaryawan(kodex,urlx) {
      //** data kpi ajax **//
      $('#table_data_'+kodex).DataTable( {
        ajax: {
          url: urlx,
          type: 'POST',
          data:{access:"<?php echo $this->codegenerator->encryptChar($access);?>",table:table,jbt:kode_j}
        },
        scrollX: true,
        "pagingType": "simple",
        columnDefs: [
        {   targets: 0, 
          width: '5%',
          render: function ( data, type, full, meta ) {
            return '<center>'+(meta.row+1)+'.</center>';
          }
        },
        {   targets: 6,
          width: '7%',
          render: function ( data, type, full, meta ) {
            return '<center>'+data+'</center>';
          }
        },
        ]
      });

    }
  function dataXtable() {
    //** data kpi ajax **//
    $('#table_data').DataTable( {
      ajax: {
        url: '<?php echo base_url('concept/view_detail_konsep_kpi/get_kpi/')?>',
        type: 'POST',
        data:{access:"<?php echo $this->codegenerator->encryptChar($access);?>",table:table,jbt:kode_j}
      },
      scrollX: true,
      bDestroy: true,
      "pagingType": "simple",
      columnDefs: [
      {   targets: 0, 
        width: '5%',
        render: function ( data, type, full, meta ) {
          return '<center>'+full[8]+(meta.row+1)+'.</center>';
        }
      },
      {   targets: 1,
        width: '50%',
        render: function ( data, type, full, meta ) {
          return data;
        }
      },
      {   targets: 6,
        width: '5%',
        render: function ( data, type, full, meta ) {
          return '<center>'+data+'</center>';
        }
      },
      {   targets: 7,
        width: '5%',
        render: function ( data, type, full, meta ) {
          var total=full[10];
          if (total > 100) {
            var msg_err_bobot='BOBOT MELEBIHI 100%';
          }else if (total < 100){
            var msg_err_bobot='BOBOT HANYA '+total+'%';
          }else{
            var msg_err_bobot='';
          }
          if (full[13] == 0) {
            var msg_err_bobot='ADA BOBOT 0%';
          }
          $('#bobot_err_msg').html(msg_err_bobot);
          return '<center>'+data+'</center><div style="display: none;">'+$('#count').val(total)+'</div>';
        }
      },
      ],
      drawCallback: function() {
        $('.select2').select2();
      }
    });

  }
  function view_modal(kode_kpi) {
    var data={kode_kpi:kode_kpi, table:table, jbt:kode_j};
    var callback=getAjaxData("<?php echo base_url('concept/view_detail_konsep_kpi/view_one')?>",data);  
    $('#view').modal('show');
    $('.header_data').html(callback['kpi']);
    $('#data_kode_view').html(callback['kode_kpi']);
    $('#data_name_view').html(callback['nama']);
    $('#data_rumus_view').html(callback['rumus_view']);
    $('#data_unit_view').html(callback['unit']);
    $('#data_detail_rumus_view').html(callback['detail_rumus']);
    $('#data_sumber_data_view').html(callback['sumber_data']);
    $('#data_kaitan_view').html(callback['kaitan_view']);
    $('#data_cara_menghitung_view').html(callback['cara_menghitung_view']);
    $('#data_jenis_satuan_view').html(callback['jenis_satuan_view']);
    $('#data_jenis_view').html(callback['jenis_view']);
    $('#data_sifat_view').html(callback['sifat_view']);
    $('#data_kode_bagian_view').html(callback['bagian']);
    $('#data_min_view').html(callback['min']);    
    $('#data_max_view').html(callback['max']);    
    $('#data_batasan_poin_view').html(callback['nama_batasan_poin']);    
    $('#data_penilai_view').html(callback['penilai']);
    $('#data_tr_view').html(callback['tr_table']);
    var status = callback['status'];
    if(status==1){
      var statusval = '<b class="text-success">Aktif</b>';
    }else{
      var statusval = '<b class="text-danger">Tidak Aktif</b>';
    }
    $('#data_status_view').html(statusval);
    $('#data_create_date_view').html(callback['create_date']+' WIB');
    $('#data_update_date_view').html(callback['update_date']+' WIB');
    $('#data_create_by_view').html(callback['nama_buat']);
    $('#data_update_by_view').html(callback['nama_update']);
  }
  function show_emp_concept(kodex) {
    var val = $('#penilai_'+kodex).val();
    if(val=='P3'){
      $('#emp_'+kodex).css('display','block');
      $('#jbt_penilai_'+kodex).css('display','block');
      select_data('fill_jbt_penilai'+kodex,url_select,'master_jabatan','kode_jabatan','nama');
      unsetoption('fill_jbt_penilai'+kodex,['JBT01','JBT02','JBT03']);
    }else{
      $('#emp_'+kodex).css('display','none');
      $('#jbt_penilai_'+kodex).css('display','none');
    }
  }
  function view_daftar() {
    $('#viewdaftar').slideToggle('slow');
  }
  function counttarget(kodex) {
    var nil = $('#'+kodex).val();
    if(nil>100){
      notValidCustom('Maximal Nilai Target Adalah 100');
    }
  }

  function countbobot(kodex) {
    var getBobot = $('input[name="'+kodex+'"]').map(function(){ 
      return this.value; 
    }).get();

    var nomor = getBobot;
    var total =  parseInt(0);
    var nol=false;
    for(i = 0; i <nomor.length; i++){
      if(nomor[i]==''){
        total +=  parseInt(0);
        nol = true;
      }else{
        total +=  parseInt(nomor[i]);
        if (parseInt(nomor[i]) == 0) {
          nol = true;
        }
      }
    }
    if(total>100 || total<100){
      $('#btn_edit').attr('disabled','disabled');
      $('#count').css('border-color','red');
      if (total > 100) {
        var msg_err_bobot='BOBOT MELEBIHI 100%';
      }else if (total < 100) {
        var msg_err_bobot='BOBOT HANYA '+total+'%';
      }else if (nol){
      //   var msg_err_bobot='ADA BOBOT 0%';
        var msg_err_bobot='';
      }else{
        var msg_err_bobot='BOBOT HANYA '+total+'%';
      }
      $('#bobot_err_msg').html(msg_err_bobot);
    }else{
      $('#btn_edit').removeAttr('disabled');
      $('#count').css('border-color','green');
      $('#bobot_err_msg').html('');
    }
    $('#count').val(total);

  }
  function add_data_kpi() {
    //** data kpi ajax **//
    $('#table_kpi').DataTable( {
      ajax: {
        url: '<?php echo base_url('concept/view_data_konsep_kpi/get_kpi/')?>',
        type: 'POST',
        data: {table:table,jbt:kode_j,jenis:'',access:"<?php echo $this->codegenerator->encryptChar($access);?>"}
      },
      scrollX: true,
      pagingType: "simple",
      columnDefs: [
      {   targets: 0, 
        width: '10%',
        render: function ( data, type, full, meta)  {
          return '<center>'+data+'</center>';
        }
      },
      {   targets: 1,
        width: '45%',
        render: function ( data, type, full, meta)  {
          return data;
        }
      },
      {   targets: 2,
        width: '45%',
        render: function ( data, type, full, meta ) {
          return data;
        }
      },
      ]
    });
  } 
    function delete_modal(id,column,dlx) {
      $('#delete').modal('toggle');
      $('#data_column_delete').val(column);
      if(column=='id_karyawan'){
        $('.header_data').html(dlx);
      }else{
        $('.header_data').html(id);
      }
      $('#data_id_delete').val(id);
      $('#data_kode_delete').val(kode_j);
      $('#data_table_delete').val(table);
      $('#data_kode_rancangan').val(kode_rancangan);
      if (column == 'id_karyawan') {
        $('#data_kodeform_delete').val('karyawan');
      }else{
        $('#data_kodeform_delete').val('kpi');
      }
    }
  function checkvalue() {
    var oTable = $("#table_kpi").dataTable();
    var matches = [];
    var checkedcollection = oTable.$("input[name='kode_kpi']:checked", { "page": "all" });
    checkedcollection.each(function (index, elem) {
        matches.push($(elem).val());
    });
    $("#form_kpi_add input[name='kode_kpi_hidden']").val(matches);
  }
  function check_emp_data(kode_j,table) {
    var data={table:table,jbt:kode_j};
    var callback=getAjaxData("<?php echo base_url('concept/view_detail_konsep_kpi/check_emp_data')?>",data);  
    if(callback['cek'] == 0){
      window.location.href = '<?php base_url('pages/view_data_konsep_kpi/'.$kode);?>'; 
    }
  }
  // //doing db transaction
  
  function do_delete(){
    submitAjax("<?php echo base_url('concept/delete_detail_konsep_kpi/')?>",'delete','form_delete',null,null);
    check_emp_data(kode_j,table);
    $('#table_data').DataTable().ajax.reload(function (){
      Pace.restart();
    });
    $('#table_data_karyawan').DataTable().ajax.reload(function (){
      Pace.restart();
    });
  }
  function do_edit(){
    if($("#form_edit")[0].checkValidity()) {
      submitAjax("<?php echo base_url()?>concept/edit_detail_konsep_kpi",null,'form_edit',null,null);
      $('#table_data').DataTable().ajax.reload(function (){
        Pace.restart();
      });
    }else{
      notValidParamx();
    } 
  }
  function do_add(kodex){
    if($("#form_"+kodex+"_add")[0].checkValidity()) {
      if(kodex!='karyawan'){
        submitAjax("<?php echo base_url('concept/add_detail_konsep_kpi')?>",null,'form_'+kodex+'_add',null,null);
      }else{
        submitAjax("<?php echo base_url('concept/add_detail_karyawan_kpi')?>",null,'form_'+kodex+'_add',null,null);
      }
      
      $('#table_data').DataTable().ajax.reload(function (){Pace.restart(); });
      $('#table_data_karyawan').DataTable().ajax.reload(function (){Pace.restart(); });
      $('#form_'+kodex+'_add')[0].reset();

      if(kodex!='karyawan'){
        $('#btn_'+kodex+'_addx').click();
      }else{
        $('#btn_'+kodex+'_add').click();
      }
    }else{
      notValidParamx();
    } 
  }
  function print_template() {
    window.open("<?php echo base_url('rekap/export_template_detail_konsep_kpi/'.$this->uri->segment(3).'/'.$this->uri->segment(4).'/'.$this->codegenerator->encryptChar($access)); ?>", "_blank");
  }
  function rekap_data() {
    window.open("<?php echo base_url('rekap/export_detail_konsep_kpi/'.$this->uri->segment(3).'/'.$this->uri->segment(4).'/'.$this->codegenerator->encryptChar($access)); ?>", "_blank");
  }
</script>