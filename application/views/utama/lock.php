
<div class="lockscreen-wrapper lockscreen">
  <div class="lockscreen-name"><?php echo $this->session->userdata('data_lock')['nama'];?></div>
  <div class="lockscreen-item">
    <div class="lockscreen-image">
      <img src="<?php echo base_url($this->session->userdata('data_lock')['foto']);?>" alt="User Image">
    </div>
    <form class="lockscreen-credentials" id="form_first">
      <div class="input-group">
        <input type="hidden" name="username" value="<?php echo $this->session->userdata('data_lock')['uname'];?>">
        <input type="password" class="form-control" name="password" placeholder="Password" required="required">

        <div class="input-group-btn">
          <button type="button" onclick="do_open_gate()" class="btn" id="action_btn"><i class="fa fa-arrow-right text-muted"></i></button>
        </div>
      </div>
    </form>
  </div>
  <div class="help-block text-center">
    Masukkan password Anda untuk kembali mendapatkan Sesi Login Terakhir.
  </div>
  <div class="text-center">
    <a href="<?php echo base_url('auth/logout');?>" class="btn btn-info"><i class="fa fa-user"></i> Login dengan akun berbeda</a>
  </div><br><br><br><br>
</div>
<script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
<script type="text/javascript">
$(document).ready(function () {
    $('#form_first input').keydown(function(e) {
    if (e.keyCode == 13 || e.which == 13) {
      do_open_gate();
      e.preventDefault();
    }
  });
});  
function do_open_gate() {
  submitAjax("<?php echo base_url('auth/do_login')?>",null,'form_first',null,null,'auth');
}
</script>