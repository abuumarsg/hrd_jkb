<div class="login-box-body">
    <!-- <a class="btn btn-primary" href="<?php echo base_url('auth');?>"><i class="fa fa-chevron-circle-left"></i> Kembali ke Login</a> -->
    <p class="login-box-msg"><br><b>KONFIRMASI OTP</b></p> 
    <!-- <div class="alert alert-info text-center" id="alert-success"><h4><i class="fa fa-info-circle"></i> Petunjuk</h4> Pastikan Nomor Handphone Anda terdaftar Whatsapps untuk menerima panduan Reset Password</div> -->
    <form id="form_reset">
      <div class="form-group has-feedback">
        <input type="text" id="id_short" name="id_short" value="<?=$emp['id_karyawan']?>">
        <input type="text" name="nomor" id="nomor" class="form-control" placeholder="NOMOR" readonly="readonly" value="<?=$nomor?>">
      </div>
      <div class="form-group has-feedback">
        <input type="text" name="password1" id="password" class="form-control" placeholder="Masukkan Kode OTP" required="required">
      </div>
    </form>
    <div class="row">
      <div class="col-xs-12 text-center">
        <span class="text-center"><b id="message_callback"></b></span>
        <div id="countdown"></div>
      </div>
      <!-- <div class="col-xs-8 text-left">
        <a type="button" href="<?=base_url()?>" style="padding-top:-10px;display:none;" id="btn_login" class="btn btn-success"><i class="fas fa-sign-in-alt"></i> Login</a>
      </div> -->
      <div class="col-xs-4 pull-right">
        <button type="button" onclick="do_reset()" id="btn_save" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
      </div>
    </div>

  </div>
</div>
<script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
<script type="text/javascript">
  function submitAjaxCall(urlx, formx, usage=null){
    if (usage == 'status') {
      var data = formx;
    } else {
      var data = $('#' + formx).serialize();
    }
    var viewx;
    $.ajax({
      url: urlx,
      method: "POST",
      data: data,
      async: false,
      dataType: 'json',
      success: function (data) {
        viewx = data;
      },
      error: function (data) {
        viewx = data;
      }
    });
    return viewx;
  }
  // function checkPassword() {
  //   var password = $('#password');
  //   var u_password = $('#ulangi_password');
  //   var err = {
  //     'border-color': 'red'
  //   };
  //   var scc = {
  //     'border-color': 'green'
  //   };
  //   if (password.val().length < 6 || u_password.val().length < 6) {
  //     $('.error_message').html('<i class="fa fa-times"></i> Password Harus Lebih Dari atau Sama Dengan 6 Karakter').css('color', 'red');
  //     $('#btn_save').attr('disabled', 'disabled');
  //     password.css(err);
  //     u_password.css(err);
  //   } else {
  //     if (password.val() == '' || u_password.val() == '') {
  //       $('#btn_save').attr('disabled', 'disabled');
  //       password.css();
  //       u_password.css();
  //     } else if (password.val() != u_password.val()) {
  //       $('.error_message').html('<i class="fa fa-times"></i> Password Tidak Sama').css('color', 'red');
  //       $('#btn_save').attr('disabled', 'disabled');
  //       password.css(err);
  //       u_password.css(err);
  //     } else {
  //       $('.error_message').html('');
  //       $('#btn_save').removeAttr('disabled', 'disabled');
  //       password.css(scc);
  //       u_password.css(scc);
  //     }
  //   }
  // }
  // function do_reset(){
  //   $('#password,#ulangi_password').removeAttr('style');
  //   if($("#form_reset")[0].checkValidity()) {
  //     var id = $('#id_short').val();
  //     var password = $('#password').val();
  //     var password2 = $('#ulangi_password').val();
  //     var datax = {id:id,password:password,password2:password2};
  //     const dtx =submitAjaxCall("<?php echo base_url('general/submit_new_password')?>", datax, 'status');
  //     if(dtx['status'] == 'true'){
  //       countdown();
  //       $('#message_callback').html(dtx['message']).css('color','#00A65A');
  //       $('#btn_save').prop('disabled', true);
  //       $('#btn_login').show();
  //     }else{
  //       $('#message_callback').html(dtx['message']).css('color','#DD4B39');
  //       $('#btn_save').prop('disabled', false);
  //     }
  //   }else{
  //     notValidParamx();
  //   } 
  // }
  function countdown(){
    var timeleft = 60;
    var downloadTimer = setInterval(function(){
      if(timeleft <= 0){
        clearInterval(downloadTimer);
        // window.location.href = "<?=base_url()?>";
      } else {
        document.getElementById("countdown").innerHTML = "Jika belum menerika kode OTP silahkan kirim OTP, " + timeleft + " detik lagi";
      }
      timeleft -= 1;
    }, 1000);
  }
</script>