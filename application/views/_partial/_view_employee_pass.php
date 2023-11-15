<meta http-equiv="cache-control" content="no-cache" />
<meta http-equiv="pragma" content="no-cache" />
<script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
<div class="row">
  <form id="form_reset">
  	<?php 
  		//echo form_open('employee/up_pass',array('class'=>'form-horizontal'));
  	  echo '';
  	?>
    <input type="hidden" name="id_karyawan" value="<?php echo $profile['id_karyawan'];?>">
    <div class="form-group clearfix">
      <label class="col-sm-3 control-label">Password Lama</label>

      <div class="col-sm-7">
        <input type="password" name="old_pass" class="form-control" placeholder="Masukkan Password Lama" required>
      </div>
    </div>
    <div class="form-group clearfix">
      <label class="col-sm-3 control-label">Password Baru</label>

      <div class="col-sm-7">
        <div class="callout callout-danger"><i class="fa fa-info-circle"></i> Password baru kamu tidak boleh sama dengan password lama</div>
        <input type="password" name="password1" id="password" class="form-control" placeholder="Password Baru" required="required" onkeyup="checkPassword()">
      </div>
    </div>
    <div class="form-group clearfix">
      <label class="col-sm-3 control-label">Ulangi Password Baru</label>
      <div class="col-sm-7">
        <input type="password" name="password2" id="ulangi_password" class="form-control" placeholder="Ulangi Password Baru" onkeyup="checkPassword()" required="required">
        <span class="error_message"></span>
      </div>
    </div>
    <div class="form-group clearfix">
      <div class="col-sm-offset-3 col-sm-9">
        <button type="button" onclick="do_reset()" id="btn_save" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
        <!-- <button type="submit" id="svedt" class="btn btn-success btn-flat"><i class="fa fa-floppy-o"></i> Simpan</button> -->
      </div>
    </div>
    <?php //echo form_close();?>
  </form>
</div>

<script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
<script type="text/javascript">
  function do_reset(){
    $('#password,#ulangi_password').removeAttr('style');
    if($("#form_reset")[0].checkValidity()) {
      submitAjax("<?php echo base_url('employee/up_pass')?>",null,'form_reset',null,null);
      $('#table_data').DataTable().ajax.reload();
    }else{
      notValidParamx();
    } 
  }
</script>