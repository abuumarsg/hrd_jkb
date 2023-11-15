
  <?php if (!empty($this->session->flashdata('error'))) {
			echo '<div class="alert alert-danger" id="alert-danger">'.$this->session->flashdata('error').'</div>';
  }?>
  <div class="login-box-body">
      <p class="login-box-msg">Login <b class="fnt">HSOFT</b></p>
      <form id="form_first">
        <div class="form-group has-feedback">
          <?php echo form_input('username','',array('class'=>'form-control','placeholder'=>'Username','required'=>'required','id'=>'username'));?>
          <span class="form-control-feedback fa fa-at"></span>
        </div>
        <div class="form-group has-feedback">
          <?php echo form_password('password','',array('class'=>'form-control','placeholder'=>'Password','required'=>'required','id'=>'password'));?>
          <!-- <span class="fa fa-lock form-control-feedback"></span> -->
        </div>
        <div class="form-group has-feedback text-center">
          <div id="captcha_img" class="text-center"></div>
          <a onclick="get_captcha()" style="cursor: pointer;"><i class="fa fa-refresh"></i> Refresh Captcha</a>
          <input type="text" class="form-control" placeholder="Masukkan Captcha" required="required" name="captcha">
        </div>
        <div class="row">
          <div class="col-xs-8">
            <div class="checkbox icheck">
              <label>
                <input type="checkbox" name="remember"> Ingat Login Saya
              </label>
            </div>
          </div>
      </form>
        <div class="col-xs-4">
          <button type="button" onclick="do_auth()" id="action_btn" class="btn btn-success btn-block "><i class="fas fa-sign-in-alt"></i> Login</button>
        </div>
      </div>
    <div class="pull-right">
      <a href="auth/lupa" style="padding-top:-10px;"><b>Lupa Password?</b></a>
    </div><br>
			<div class="row">
				<div class="col-md-12">
					<div class="social-auth-links text-center">
						<p>- OR -</p>
						<div id="g_id_onload"
									data-client_id="<?=API_ID_GOOGLE?>"
									data-context="signin"
									data-ux_mode="popup"
									data-login_uri="<?=base_url()?>"
									data-auto_select="false"
									data-callback="handleCredentialResponse">
						</div>

						<button class="btn" style="background:none;border:none;box-shadow:none"><div class="g_id_signin"
								data-type="standard"
								data-theme="filled_black"
								data-text="signin_with"
								data-size="medium"
								data-logo_alignment="left">
							</div>
						</button>
					</div>
				</div>
			</div>
    <div id="forget_pass"></div>

  </div>
  <!-- <a href="https://wmers.net/Sistem_Penilaian_Kinerja_BPRWM.apk" class="btn btn-block  btn-danger"><i class="fa fa-android"></i> Download App Android</a> -->
</div>
<script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
<script src="https://accounts.google.com/gsi/client" async defer></script>
<script type="text/javascript">
$(document).ready(function () {
    form_key('form_first','action_btn');
    get_captcha();
  $("#alert-danger").fadeTo(5000, 300).slideUp(300, function(){
    $("#alert-danger").slideUp(300);
  }); 
});
function get_captcha() {
  var callback=getAjaxData("<?= base_url('auth/get_captcha');?>",null);
  $('#captcha_img').html('<img src="<?= base_url()?>'+callback['captcha_img']+'" style="width:200px"/>');
}
function do_auth() {
  submitAjax("<?php echo base_url('auth/do_login')?>",null,'form_first',null,null,'auth');
}
function handleCredentialResponse(response) {
    submitAjax("<?php echo base_url('auth/google')?>", null, {token:response.credential}, null, null, 'status');
}
</script>
