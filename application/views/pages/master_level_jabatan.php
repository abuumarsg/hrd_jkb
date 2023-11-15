  <div class="content-wrapper">
    <section class="content-header"> 
      <h1>
        <i class="fa fa-database"></i> Master Data 
        <small>Level Jabatan</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
        <li class="active">Master Data Level Jabatan</li>
      </ol>
    </section>
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box box-danger">
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-list"></i> Daftar Level Jabatan</h3>
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
                        <?php if (in_array($access['l_ac']['add'], $access['access'])) {
                          echo '<button class="btn btn-success" type="button" id="btn_add_collapse" data-toggle="collapse" data-target="#add" aria-expanded="false" aria-controls="import"><i class="fa fa-plus"></i> Tambah Level</button>';
                        }?>
                      </div>
                      <div class="pull-right" style="font-size: 8pt;">
                        <i class="fa fa-toggle-on stat scc"></i> Aktif<br>
                        <i class="fa fa-toggle-off stat err"></i> Tidak Aktif
                      </div>
                    </div>
                  </div>
                  
                  <?php if (in_array($access['l_ac']['add'], $access['access'])) {?>
                    <div class="row">
                      <div class="col-md-12">
                        <div class="collapse" id="add">
                          <br>
                          <div class="col-md-2"></div>
                          <div class="col-md-8">
                            <form id="form_add">
                              <p class="text-danger">Semua data harus diisi!</p>
                              <div class="form-group">
                                <label>Kode Level Jabatan</label>
                                <input type="text" placeholder="Masukkan Kode Level Jabatan" id="data_kode_add" name="kode" class="form-control" required="required" readonly="readonly">
                              </div>
                              <div class="form-group">
                                <label>Nama Level Jabatan</label>
                                <input type="text" placeholder="Masukkan Nama Level Jabatan" name="nama" class="form-control" required="required">
                              </div>
                              <div class="form-group">
                                <label>Nama Level Struktur</label>
                                <select class="form-control select2" name="struktur" id="data_struktur_add" style="width: 100%;"></select>
                              </div>
                              <!-- <div class="form-group clearfix card-trans" style="padding: 10px;">
                                <label>Bobot Penilaian</label>
                                <label class="text-danger"> Jumlah Bobot Harus 100%</label>
                                <div class="row">
                                  <div class="col-md-12">
                                    <label>Bobot Target</label>
                                    <div class="has-feedback">
                                      <input type="number" max="100" min="0" id="bobot_add[]" placeholder="Bobot Target" name="bobot_out" class="form-control bobot_add">
                                      <span class="form-control-feedback">%</span>
                                    </div>
                                  </div>
                                  <div class="col-md-12">
                                    <div class="has-feedback">
                                      <label>Bobot Sikap dan Perilaku</label>
                                      <input type="number" max="100" min="0" id="bobot_add[]" placeholder="Bobot Sikap" name="bobot_sikap" class="form-control bobot_add">
                                      <span class="form-control-feedback">%</span>
                                    </div>
                                  </div>
                                  <div class="col-md-12">
                                    <div class="has-feedback">
                                      <label>Bobot Pencapaian OS</label>
                                      <input type="number" max="100" min="0" id="bobot_add[]" placeholder="Bobot Pencapaian OS" name="bobot_tcorp" class="form-control bobot_add">
                                      <span class="form-control-feedback">%</span>
                                    </div>
                                  </div>
                                  <div class="col-md-12">
                                    <label>Bobot Total</label>
                                    <div class="has-feedback">
                                      <input type="number" class="form-control" id="data_hasilbobot_add" required="required" readonly="readonly">
                                      <span class="form-control-feedback">%</span>
                                    </div>
                                  </div>
                                </div>
                              </div> -->
                              <div class="form-group">
                                <button type="button" onclick="do_add()" id="btn_add" class="btn btn-success" id="savex"><i class="fa fa-floppy-o"></i> Simpan</button>
                              </div>
                            </form>
                          </div>
                        </div>
                      </div>
                      </div><?php } ?>
                    </div>
                  </div>
                  <br>
                  <div class="row">
                    <div class="col-md-12">
                      <table id="table_data" class="table table-bordered table-striped table-responsive" width="100%">
                        <thead>
                          <tr>
                            <th>No.</th>
                            <th>Kode Level Jabatan</th>
                            <th>Level Jabatan</th>
                            <th>Level Struktur</th>
                            <!-- <th>Bobot Penilaian</th> -->
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
                <div class="col-md-6">
                  <div class="form-group col-md-12">
                    <label class="col-md-6 control-label">Kode Level Jabatan</label>
                    <div class="col-md-6" id="data_kode_view"></div>
                  </div>
                  <div class="form-group col-md-12">
                    <label class="col-md-6 control-label">Nama Level Jabatan</label>
                    <div class="col-md-6" id="data_name_view"></div>
                  </div>
                  <!-- <div class="form-group col-md-12">
                    <label class="col-md-6 control-label">Bobot Target</label>
                    <div class="col-md-6" id="data_bobot_tg_view"></div>
                  </div>
                  <div class="form-group col-md-12">
                    <label class="col-md-6 control-label">Bobot Sikap dan Perilaku</label>
                    <div class="col-md-6" id="data_bobot_sk_view"></div>
                  </div>
                  <div class="form-group col-md-12">
                    <label class="col-md-6 control-label">Bobot Pencapaian OS</label>
                    <div class="col-md-6" id="data_bobot_os_view"></div>
                  </div> -->
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
                <input type="hidden" id="data_kode_edit_old" name="kode_old" value="">
                <div class="form-group">
                  <label>Kode Level Jabatan</label>
                  <input type="text" placeholder="Masukkan Level Jabatan" id="data_kode_edit" name="kode"  class="form-control" required="required" readonly="readonly">
                </div>
                <div class="form-group">
                  <label>Nama Level Jabatan</label>
                  <input type="text" placeholder="Masukkan Level Jabatan" id="data_name_edit" name="nama" class="form-control" required="required">
                </div>
                <div class="form-group">
                  <label>Nama Level Struktur</label>
                  <select class="form-control select2" name="struktur" id="data_struktur_edit" style="width: 100%;"></select>
                </div>
                <!-- <div class="form-group card-trans" style="padding: 10px;margin-right: 0px;">
                  <label>Bobot Penilaian</label>
                  <label class="text-danger"> Jumlah Bobot Harus 100%</label>
                  <div class="row">
                    <div class="col-md-12">
                      <label>Bobot Target</label>
                      <div class="has-feedback">
                        <input type="number" max="100" min="0" id="bobot_edit[]" placeholder="Bobot Target" name="bobot_tg_edit" class="form-control bobot_edit">
                        <span class="form-control-feedback">%</span>
                      </div>
                    </div>
                    <div class="col-md-12">
                      <div class="has-feedback">
                        <label>Bobot Sikap dan Perilaku</label>
                        <input type="number" max="100" min="0" id="bobot_edit[]" placeholder="Bobot Sikap" name="bobot_sk_edit" class="form-control bobot_edit">
                        <span class="form-control-feedback">%</span>
                      </div>
                    </div>
                    <div class="col-md-12">
                      <div class="has-feedback">
                        <label>Bobot Pencapaian OS</label>
                        <input type="number" max="100" min="0" id="bobot_edit[]" placeholder="Bobot Pencapaian OS" name="bobot_tc_edit" class="form-control bobot_edit">
                        <span class="form-control-feedback">%</span>
                      </div>
                    </div>
                    <div class="col-md-12">
                      <label>Bobot Total</label>
                      <div class="has-feedback">
                        <input type="number" class="form-control" id="data_hasilbobot_edit" required="required" readonly="readonly">
                        <span class="form-control-feedback">%</span>
                      </div>
                    </div>
                  </div>
                </div> -->
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
    //wajib diisi
    var table="master_level_jabatan";
    var column="id_level_jabatan";
    $(document).ready(function(){
      
      
      refreshCode();
      $('#table_data').DataTable( {
        ajax: {
          url: "<?php echo base_url('master/master_level_jabatan/view_all/')?>",
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
        {   targets: 1,
          width: '15%',
          render: function ( data, type, full, meta ) {
            return data;
          }
        },
        {   targets: 2,
          width: '15%',
          render: function ( data, type, full, meta ) {
            return data;
          }
        },
        {   targets: 3,
          width: '10%',
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
      //aksi
      {   targets: 6, 
        width: '7%',
        render: function ( data, type, full, meta ) {
          return '<center>'+data+'</center>';
        }
      },
      ]
    });

      $('.bobot_add').keyup(function(){
        var users = $('input[id="bobot_add[]"]').map(function(){ 
          return this.value; 
        }).get();
        getAjaxCount(null,users,"data_hasilbobot_add");
      })

      $('.bobot_edit').keyup(function(){
        var users = $('input[id="bobot_edit[]"]').map(function(){ 
          return this.value; 
        }).get();
        getAjaxCount(null,users,"data_hasilbobot_edit");
      })

      $('#btn_add_collapse').click(function(){
        select_data('data_struktur_add',url_select,'master_level_struktur','kode_level_struktur','nama','0');
      })
    });
    function refreshCode() {
      kode_generator("<?php echo base_url('master/master_level_jabatan/kode');?>",'data_kode_add');
    }
    function view_modal(id) {
      var data={id_level_jabatan:id};
      var callback=getAjaxData("<?php echo base_url('master/master_level_jabatan/view_one')?>",data);  
      $('#view').modal('show');
      $('.header_data').html(callback['nama']);
      $('#data_kode_view').html(callback['kode_jabatan']);
      $('#data_name_view').html(callback['nama']);
      $('#data_bobot_tg_view').html(callback['bobot_out']);
      $('#data_bobot_sk_view').html(callback['bobot_sikap']);
      $('#data_bobot_os_view').html(callback['bobot_tcorp']);
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

      select_data('data_struktur_edit',url_select,'master_level_struktur','kode_level_struktur','nama','0');
      var id = $('input[name="data_id_view"]').val();
      var data={id_level_jabatan:id};
      var callback=getAjaxData("<?php echo base_url('master/master_level_jabatan/view_one')?>",data); 
      $('#view').modal('toggle');
      setTimeout(function () {
       $('#edit').modal('show');
     },600);
      $('.header_data').html(callback['nama']);
      $('#data_id_edit').val(callback['id']);
      $('#data_kode_edit_old').val(callback['kode_jabatan']);
      $('#data_kode_edit').val(callback['kode_jabatan']);
      $('#data_name_edit').val(callback['nama']);
      $('#data_struktur_edit').val(callback['kode_level_struktur']);
      $('input[name="bobot_tg_edit"]').val(callback['bobot_out_val']);
      $('input[name="bobot_sk_edit"]').val(callback['bobot_sikap_val']);
      $('input[name="bobot_tc_edit"]').val(callback['bobot_tcorp_val']);
      $('#data_hasilbobot_edit').val(callback['sumbobot']);
    }
    function delete_modal(id) {
      var data={id_level_jabatan:id};
      var callback=getAjaxData("<?php echo base_url('master/master_level_jabatan/view_one')?>",data);
      var datax={table:table,column:column,id:id,nama:callback['nama']};
      loadModalAjax("<?php echo base_url('pages/load_modal_delete')?>",'modal_delete_partial',datax,'delete');
    }
  //doing db transaction
  function do_status(id,data) {
    var data_table={status:data};
    var where={id_level_jabatan:id};
    var datax={table:table,where:where,data:data_table};
    submitAjax("<?php echo base_url('global_control/change_status')?>",null,datax,null,null,'status');
    $('#table_data').DataTable().ajax.reload(function (){
      Pace.restart();
    });
  }
  function do_edit(){
    if($("#form_edit")[0].checkValidity()) {
      if($('#data_hasilbobot_edit').val()!=100){
        notValidParamx();
      }else{
        submitAjax("<?php echo base_url('master/edt_level_jabatan')?>",'edit','form_edit',null,null);
        $('#table_data').DataTable().ajax.reload(function (){
          Pace.restart();
        });
      }
    }else{
      notValidParamx();
    } 
  }
  function do_add(){
    if($("#form_add")[0].checkValidity()) {
      if($('#data_hasilbobot_add').val()!=100){
        notValidParamx();
      }else{
        submitAjax("<?php echo base_url('master/add_level_jabatan')?>",null,'form_add',null,null);
        $('#table_data').DataTable().ajax.reload(function (){
          Pace.restart();
        });
        $('#form_add')[0].reset();
        refreshCode();
      }
    }else{
      notValidParamx();
    }
  }
</script>