  <div class="login-box-body">
    <p class="login-box-msg"><b class="fnt">Reset Password</b></p>
    <form id="form_first">
      <input type="hidden" name="id" value="<?php echo $id;?>">
      <input type="hidden" name="token" value="<?php echo $token;?>">
      <input type="hidden" name="usage" value="<?php echo $usage;?>">
      <div class="form-group has-feedback">
        <input type="password" name="password1" id="pass1" class="form-control" placeholder="Masukkan Password Baru" required >
        <span class="form-control-feedback fa fa-lock"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" name="password2" id="pass2" class="form-control" placeholder="Ulangi Password" required onkeyup="checkPass(); return false;">
        <span class="form-control-feedback fa fa-lock"></span>
      </div>

      <div class="row">
        <div class="col-xs-8 pull-left">
          <b><span id="pesan"></span></b>
        </div>
        <div class="col-xs-4 pull-right">
          <button type="button" onclick="do_save_password()" id="svedt" class="btn btn-success btn-block "><i class="fa fa-floppy-o"></i> Simpan</button>
        </div>
      </div>
    </form>
  </div>
</div>
<script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
<script type="text/javascript">
function do_save_password() {
  submitAjax("<?php echo base_url('auth/token_verified')?>",null,'form_first',null,null,'auth');
}
</script>