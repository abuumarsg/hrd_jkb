<div class="content-wrapper">
  <section class="content-header">
    <h1>
      <i class="fa fa-gears"></i> Setting Aplikasi
      <small>Setting Manajemen Admin</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
      <li class="active">Setting Manajemen Admin</li>
    </ol>
  </section>
  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box box-success">
          <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-user-secret"></i> Data Admin</h3>
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
                        echo '<button class="btn btn-success" type="button" data-toggle="collapse" data-target="#add_adm" id="add_button"><i class="fa fa-plus"></i> Tambah Admin</button>';
                      }?>
                    </div>
                      <div class="pull-right" style="font-size: 8pt;">
                        <i class="fa fa-toggle-on stat scc"></i> Aktif<br>
                        <i class="fa fa-toggle-off stat err"></i> Tidak Aktif (Suspen)
                      </div>
                  </div>
                </div>
                <?php if(in_array($access['l_ac']['add'], $access['access'])){?>
                  <div class="collapse" id="add_adm">
                    <br>
                    <div class="col-md-2"></div>
                    <div class="col-md-8">
                      <form id="form_add">
                        <div class="form-group">
                          <label>Pilih Karyawan</label>
                          <select name="employee[]" class="form-control select2" multiple="multiple" placeholder="Pilih Karyawan" id="data_karyawan_add" style="width: 100%;" required="required"></select>
                        </div>
                        <div class="form-group">
                          <label>Pilih User Group</label>
                          <select name="u_group" class="form-control select2" id="data_usergroup_add" style="width: 100%;" required="required"></select>
                        </div>
                        <div class="form-group">
                          <label>Level Admin</label>
                          <?php
                            $level[null] = 'Pilih Data';
                            $sel3 = [null];
                            $exsel3 = array('class'=>'form-control select2','id'=>'data_level_add','required'=>'required','style'=>'width:100%');
                            echo form_dropdown('level',$level,$sel3,$exsel3);
                          ?>
                        </div>
                        <div class="form-group">
                          <button type="button" onclick="do_add()" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
                        </div>
                      </form>
                    </div>
                  </div>
                <?php } ?>
              </div>
            </div>
            <br>
            <div class="row">
              <div class="col-md-12">
                <table id="table_data" class="table table-bordered table-striped table-responsive" width="100%">
                  <thead>
                    <tr>
                      <th>No.</th>
                      <th>Nama</th>
                      <th>Email</th>
                      <th>User Group</th>
                      <th>Last Login</th>
                      <th>Level Admin</th>
                      <th>Tanggal</th>
                      <th>Status Admin</th>
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
              <label class="col-md-6 control-label">Nama</label>
              <div class="col-md-6" id="data_name_view"></div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Username</label>
              <div class="col-md-6" id="data_username_view"></div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Email</label>
              <div class="col-md-6" id="data_email_view"></div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Alamat</label>
              <div class="col-md-6" id="data_alamat_view"></div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Admin Level</label>
              <div class="col-md-6" id="data_level_view"></div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Nomor HP</label>
              <div class="col-md-6" id="data_hp_view"></div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Jenis Kelamin</label>
              <div class="col-md-6" id="data_kelamin_view"></div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">User Group</label>
              <div class="col-md-6" id="data_group_view"></div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Last Login</label>
              <div class="col-md-6" id="data_lastlogin_view"></div>
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
      </div>
      <div class="modal-footer">
        <?php if (in_array($access['l_ac']['edt'], $access['access'])) {
          echo '<button type="button" class="btn btn-success" onclick="reset_modal()"><i class="fa fa-key"></i> Reset Password</button>';
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
            <label>Nama Admin</label>
            <input type="text" placeholder="Masukkan Nama" id="data_name_edit" name="nama" value="" class="form-control" required="required">
          </div>
          <div class="form-group">
            <label>Username</label>
            <input type="text" placeholder="Masukkan Username" id="data_username_edit" name="username" value="" class="form-control" required="required">
          </div>
          <div class="form-group">
            <label>Email</label>
            <input type="email" placeholder="Masukkan Email" id="data_email_edit" name="email" value="" class="form-control" required="required">
          </div>
          <div class="form-group">
            <label>Alamat</label>
            <textarea name="alamat" class="form-control" id="data_alamat_edit" required="required"></textarea>
          </div>
          <div class="form-group">
            <label>Nomor HP</label>
            <input type="number" placeholder="Masukkan Nomor HP" id="data_nohp_edit" name="no_hp" value="" class="form-control" required="required">
          </div>
					<div class="form-group" id="div_level" style="display:none;">
						<label>Level Admin</label>
						<?php
							$sel = [null];
							$exsel = array('class'=>'form-control select2','id'=>'data_level_edit','required'=>'required','style'=>'width:100%');
							echo form_dropdown('level',$level,$sel,$exsel);
						?>
					</div>
          <div class="form-group">
            <label>Pilih User Group</label>
            <select name="u_group" class="form-control select2" id="data_usergroup_edit" style="width: 100%;" required="required"></select>
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

<!-- reset -->
<div id="reset" class="modal fade" role="dialog">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h2 class="modal-title">Reset Password <b class="text-muted header_data"></b></h2>
      </div>
      <div class="modal-body">
        <form id="form_reset">
          <input type="hidden" id="data_id_reset" name="id" value="">
          <div class="form-group">
            <label>Password Lama</label>
            <input type="password" placeholder="Masukkan Password Lama" name="old_password" class="form-control" required="required">
          </div>
          <div class="form-group">
            <label>Password Baru</label>
            <input type="password" class="form-control" name="password" placeholder="Masukkan Password Baru" onkeyup="checkPassword()" id="password" required="required">
          </div>
          <div class="form-group">
            <label>Ulangi Password Baru</label>
            <input type="password" class="form-control" name="u_password" placeholder="Masukkan Password Baru" onkeyup="checkPassword()" id="ulangi_password" required="required">
          </div>
          <small class="error_message"></small>
        </div>
        <div class="modal-footer">
          <button type="button" onclick="do_reset()" id="btn_save" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
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
      $('#table_data').DataTable( {
        ajax: {
          url: "<?php echo base_url('admin/list_admin/view_all/')?>",
          type: 'POST',
          data:{access:"<?php echo $this->codegenerator->encryptChar($access);?>"}
        },
        scrollX: true,
        columnDefs: [
        {   targets: 0, 
          width: '3%',
          render: function ( data, type, full, meta ) {
            return '<center>'+(meta.row+1)+'.</center>';
          }
        },
        {   targets: 1,
          width: '15%',
          render: function ( data, type, full, meta ) {
            return full[9]+' '+data;
          }
        },
        {   targets: 2,
          width: '10%',
          render: function ( data, type, full, meta ) {
            return data;
          }
        },
        {   targets: 3,
          width: '10%',
          render: function ( data, type, full, meta ) {
            return '<center>'+data+'</center>';
          }
        },
        {   targets: 4,
          width: '15%',
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
        {   targets: 7,
          width: '5%',
          render: function ( data, type, full, meta ) {
            return '<center>'+data+'</center>';
          }
        },
        //aksi
        {   targets: 8, 
          width: '8%',
          render: function ( data, type, full, meta ) {
            return '<center>'+data+'</center>';
          }
        },
        ]
      });
    });
    function view_modal(id) {
      var data={id_admin:id};
      var callback=getAjaxData("<?php echo base_url('admin/list_admin/view_one')?>",data);  
      $('#view').modal('show');
      $('.header_data').html(callback['nama']);
      $('#data_foto_view').html('<img class="profile-user-img img-responsive img-circle view_photo" data-source-photo="'+callback['foto']+'" src="'+callback['foto']+'" alt="User profile picture">');
      $('#data_name_view').html(callback['nama']);
      $('#data_username_view').html(callback['username']);
      $('#data_email_view').html(callback['email']);
      $('#data_alamat_view').html(callback['alamat']);
		  $('#data_level_view').html(callback['v_level']);
      $('#data_hp_view').html(callback['hp']);
      $('#data_kelamin_view').html(callback['kelamin']);
      $('#data_group_view').html(callback['user_group']);
      $('#data_lastlogin_view').html(callback['last_login']);
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
      select_data('data_usergroup_edit',url_select,'master_user_group','id_group','nama');
      var data={id_admin:id};
      var callback=getAjaxData("<?php echo base_url('admin/list_admin/view_one')?>",data); 
      $('#view').modal('toggle');
      setTimeout(function () {
        $('#edit').modal('show');
      },500); 
      $('.header_data').html(callback['nama']);
      $('#data_id_edit').val(callback['id']);
      $('#data_username_edit').val(callback['username']);
      $('#data_name_edit').val(callback['nama']);
      $('#data_email_edit').val(callback['email']);
      $('#data_alamat_edit').val(callback['alamat']);
      $('#data_nohp_edit').val(callback['hp']);
      $('#data_usergroup_edit').val(callback['user_group_val']).trigger('change');
      var dt_level=getAjaxData("<?php echo base_url('admin/list_admin/level')?>",null);
      var level = dt_level['level'];
      if(level==0 || level==1){
        if(level <= callback['level']){
          $('#div_level').show();
          $('#data_level_edit').val(callback['level']).trigger('change');
        }
      }else{
        $('#div_level').hide();
        $('#data_level_edit').val(callback['level']).trigger('change');
      }
    }
    function reset_modal() {
      $('#form_reset')[0].reset();
      $('#password_baru_reset').css('border-color','#D2D6DE');
      $('#upassword_baru_reset').css('border-color','#D2D6DE');
      $('#pass_notif_text').css('display','none');
      var id = $('input[name="data_id_view"]').val();
      var data={id_admin:id};
      var callback=getAjaxData("<?php echo base_url('admin/list_admin/view_one')?>",data); 
      $('#view').modal('toggle');
      setTimeout(function () {
        $('#reset').modal('show');
      },500); 
      $('.header_data').html(callback['nama']);
      $('#data_id_reset').val(callback['id']);
    }
    function delete_modal(id) {
      var data={id_admin:id};
      var callback=getAjaxData("<?php echo base_url('admin/list_admin/view_one')?>",data);
      var datax={table:table,column:column,id:id,nama:callback['nama']};
      loadModalAjax("<?php echo base_url('pages/load_modal_delete')?>",'modal_delete_partial',datax,'delete');
    }
    //doing db transaction
    function do_status(id,data) {
      var data_table={status_adm:data};
      var where={id_admin:id};
      var datax={table:table,where:where,data:data_table};
      submitAjax("<?php echo base_url('global_control/change_status')?>",null,datax,null,null,'status');
      $('#table_data').DataTable().ajax.reload();
    }
    function do_edit(){
      if($("#form_edit")[0].checkValidity()) {
        submitAjax("<?php echo base_url('admin/edt_admin')?>",'edit','form_edit',null,null);
        $('#table_data').DataTable().ajax.reload();
      }else{
        notValidParamx();
      } 
    }
    function do_reset(){
      $('#password,#ulangi_password').removeAttr('style');
      if($("#form_reset")[0].checkValidity()) {
        submitAjax("<?php echo base_url('admin/reset_password')?>",'reset','form_reset',null,null);
        $('#table_data').DataTable().ajax.reload();
      }else{
        notValidParamx();
      } 
    }
    function do_add(){
      if($("#form_add")[0].checkValidity()) {
        submitAjax("<?php echo base_url('admin/add_admin')?>",null,'form_add',null,null);
        $('#table_data').DataTable().ajax.reload();
        $('#form_add')[0].reset();
        setTimeout(function () {
          $('#add_button').click();
        },500); 
      }else{
        notValidParamx();
      } 
    }
</script>