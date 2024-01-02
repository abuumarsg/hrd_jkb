  <div class="login-box-body">
    <a class="btn btn-primary" href="<?php echo base_url('auth');?>"><i class="fa fa-chevron-circle-left"></i> Kembali ke Login</a>
    <p class="login-box-msg"><br><b>Lupa Password</b></p> 
    <div class="alert alert-info text-center" id="alert-success"><h4><i class="fa fa-info-circle"></i> Petunjuk</h4> Pastikan Nomor Handphone Anda terdaftar Whatsapps untuk menerima panduan Reset Password dan juga terdaftar di sistem HSOFT</div>
    <form id="form_first">
    <div class="form-group has-feedback">
      <input type="text" name="email" class="form-control" id="input_nomor" placeholder="Masukkan Nomor Handphone Anda" required="required">
      <!-- <span class="form-control-feedback fa fa-envelope"></span> -->
      <span class="form-control-feedback fab fa-whatsapp" style="padding-top:10px;"></span>
      <div class="text-center" id="notif_input_nomor" style="font-size: 9pt;"></div>
    </div>
    </form>
    <div class="row">
      <span class="text-center"><b id="message_callback"></b></span>
      <div class="col-xs-8 text-center">
        <button type="button" onclick="do_send_email('admin')" class="btn btn-danger btn-block" id="kirimnotifadm" style="display:none;"><i class="fa fa-paper-plane"></i> Reset Password Admin</button>
      </div>
      <div class="col-xs-4 pull-right">
        <button type="button" onclick="do_send_email('emp')" class="btn btn-success btn-block" id="kirimnotif" disabled="disabled"><i class="fa fa-paper-plane"></i> Kirim</button>
      </div>
    </div>

  </div>
</div>
<script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
<script type="text/javascript">
  $('#input_nomor').keyup(function() {
    var nomor = $('#input_nomor').val();
    var cek = getAjaxData("<?php echo base_url('general/cek_nomor')?>",{nomor:nomor});
    if(cek['msg'] == 'true'){
      $('#input_nomor').css('border-color','#00A65A');
      $('#notif_input_nomor').css('color','#00A65A');
      $('#kirimnotif').prop('disabled', false);
    }else{
      $('#input_nomor').css('border-color','#DD4B39');
      $('#notif_input_nomor').css('color','#DD4B39');
      $('#kirimnotif').prop('disabled', true);
    }
    $('#notif_input_nomor').html('<b>'+cek['ret']+'</b>');
    if(cek['msg_adm'] == 'true'){
      $('#kirimnotifadm').show();
    }else{
      $('#kirimnotifadm').hide();
    }
  });
  // function do_send_email(param) {
  //   var nomor = $('#input_nomor').val();
  //   var datax = {param:param,email:nomor};
  //   const dtx =submitAjaxCall("<?php echo base_url('general/send_nomor_forget')?>", datax, 'status');
  //   if(dtx['status'] == 'true'){
  //     $('#message_callback').html(dtx['message']).css('color','#00A65A');
  //     $('#input_nomor').val('');
  //     $('#kirimnotif').hide();
  //     $('#kirimnotifadm').hide();
  //     $('#notif_input_nomor').hide();
  //   }else{
  //     $('#message_callback').html(dtx['message']).css('color','#DD4B39');
  //   }
  // }
  function do_send_email(param) {
    var nomor = $('#input_nomor').val();
    var datax = {param:param,nomor:nomor};
    const dtx =submitAjaxCall("<?php echo base_url('general/send_otp')?>", datax, 'status');
    if(dtx['status'] == 'true'){
      $.redirect("<?php echo base_url('general/confirmNumberOTP'); ?>", datax, "POST");
    }else{
      $('#message_callback').html(dtx['message']).css('color','#DD4B39');
    }
  }
  function submitAjaxCall(urlx, formx, usage=null){//, url_kode, idf_kode, usage) {
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
</script>