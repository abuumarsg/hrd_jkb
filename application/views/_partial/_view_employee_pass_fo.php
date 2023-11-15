<meta http-equiv="Pragma" content="no-cache">
<div class="row">
  <form id="form_reset">
    <div class="col-sm-3"></div>
    <div class="callout callout-danger col-sm-7"><i class="fa fa-info-circle"></i> Password baru kamu tidak boleh sama dengan password lama</div>
  	<?php 
  	  echo '<input type="hidden" name="nik" value="'.$profile['nik'].'">';
  	?>
    <div class="form-group clearfix">
      <label class="col-sm-3 control-label">Password Lama</label>

      <div class="col-sm-7" style="padding-right: 0px;">
        <input type="password" name="old_pass" class="form-control r_pass" placeholder="Masukkan Password Lama"  required="required">
      </div>
    </div>
    <div class="form-group clearfix">
      <label class="col-sm-3 control-label">Password Baru</label>

      <div class="col-sm-7" style="padding-right: 0px;">
        
        <input type="password" name="password1" id="password" onkeyup="checkPassword()" class="form-control r_pass" placeholder="Password Baru" required="required" required="required">
      </div>
    </div>
    <div class="form-group clearfix">
      <label class="col-sm-3 control-label">Ulangi Password Baru</label>
      <div class="col-sm-7" style="padding-right: 0px;">
        <input type="password" name="password2" id="ulangi_password" class="form-control r_pass" placeholder="Ulangi Password Baru" onkeyup="checkPassword()" required="required">
        <span class="error_message"></span>
      </div>
    </div>
    <div class="form-group clearfix">
      <div class="col-sm-offset-3 col-sm-9">
        <button type="submit" class="btn btn-primary"><i class="fa fa-floppy-o"></i> Simpan</button>
      </div>
    </div>
  </form>
</div>
<script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
<script type="text/javascript">
  $(document).ready(function(){
    form_property();all_property();
  })
  function pass_fo(){
    $('.r_pass').val('');
      $('#form_reset').validator().on('submit', function (e) {
        if (e.isDefaultPrevented()) {
            notValidParamx();
        } else {
          e.preventDefault(); 
           do_reset()
           return false;
        }
    })    
  }
  function do_reset(){
    $('#password,#ulangi_password').removeAttr('style');
    if($("#form_reset")[0].checkValidity()) {
      submitAjax("<?php echo base_url('kemp/up_pass')?>",null,'form_reset',null,null);
      $('#table_data').DataTable().ajax.reload();
    }else{
      notValidParamx();
    } 
  }
</script>