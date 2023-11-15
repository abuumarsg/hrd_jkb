<div class="content-wrapper">
    <section class="content-header">
      <h1>
        <i class="fa fa-gears"></i> General
        <small>Setting</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
        <li class="active">Setting Root Password</li>
      </ol>
    </section>
    <section class="content">
      <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-6">
          <div class="box box-danger">
            <div class="box-header with-border">
              <i class="fa fa-gears"></i>
              <h3 class="box-title">Halaman Coba Kirim Whatsapp</h3>
            </div>
            <div class="box-body">
              <div class="row">
                <div class="col-md-12">
                  <form id="form_add">
                    <div class="form-group">
                      <label>Nomor HP</label>
                      <input type="text" placeholder="Masukkan Nomor Handphone" name="nomor" class="form-control" required="required">
                    </div>
                    <div class="form-group">
                      <label>Pesan</label>
                      <textarea class="form-control" placeholder="Masukkan Pesan Anda" name="pesan"  required="required"></textarea>
                    </div>
                  </form>
                </div>
              </div>
            </div>
            <div class="box-footer">
              <div class="form-group pull-right">
                <button type="button" onclick="do_add()" id="btn_add" class="btn btn-success"><i class="fa fa-send"></i> Kirim Pesan</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section> 
</div>
<script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
<script type="text/javascript">
  function reload_data(id) {
    var data={id:id};
  }
  function do_add(){
    if($("#form_add")[0].checkValidity()) {
      submitAjax("<?php echo base_url('master/sendwa')?>",null,'form_add',null,null);
    }else{
      notValidParamx();
    } 
  }
</script>