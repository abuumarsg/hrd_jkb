<div class="content-wrapper">
    <section class="content-header">
      <h1>
        <i class="fa fa-gears"></i> Setting Aplikasi
        <small>Setting Root Password</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
        <li class="active">Setting Root Password</li>
      </ol>
    </section>
    <section class="content">
      <div class="row">
        <div class="col-md-4"></div>
        <div class="col-md-4">
          <div class="box box-danger">
            <div class="box-header">
              <div class="box-tools pull-right">
                <a href="#" class="btn btn-box-tool" data-toggle="popover" data-placement="bottom" data-trigger="focus" data-content="Password ini digunakan untuk masuk ke akun seluruh karyawan dan admin"><i class="fa fa-question-circle" style="color: blue;font-size: 12pt"></i></a>
              </div>
            </div>
            <div class="box-body text-center">
              <div class="row">
                <p style="font-size: 60pt;color: black"><i class="fa fa-key"></i></p>                
              </div>
              <div class="row">
                <div class="col-md-12">
                  <p style="word-wrap: break-word;"><b id="data_password"></b></p>
                  <p>Password ini bisa digunakan untuk masuk ke akun seluruh karyawan dan admin</p><hr>
                  <p style="color: red;">Jangan Gunakan Password dengan kombinasi yang mudah diketahui orang lain seperti "123456"</p>
                  <form id="form_edit">
                    <div class="form-group has-feedback">
                      <input type="password" name="password" class="form-control" placeholder="Password" required="required" value="" id="password">
                      <span class="fa fa-lock form-control-feedback"></span>
                    </div>
                  </form>
                  <div class="row">
                    <div class="col-xs-8">
                      <div class="checkbox icheck">
                        <label>
                          <input type="checkbox" onchange="document.getElementById('password').type = this.checked ? 'text' : 'password'"> Show Password
                        </label>
                      </div>
                    </div>
                    <div class="col-xs-4">
                      <button type="button" class="btn btn-success" onclick="do_edit()" id="btn_edit" title="Simpan"><i class="fa fa-floppy-o"></i> Simpan</button>
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
<script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
<script type="text/javascript">
  $(document).ready(function(){
    getData();
  });
  function getData() {
    var data={id:1};
    var callback=getAjaxData("<?php echo base_url('master/master_root_password/view_one')?>",data); 
    $('#data_password').html(callback['plain']);
    $('#password').val(callback['plain']);
  }
  function do_edit(){
    if($("#form_edit")[0].checkValidity()) {
      submitAjax("<?php echo base_url('master/reset_root_pass')?>",'edit','form_edit',null,null);
      getData();
    }else{
      notValidParamx();
    } 
  }
</script>