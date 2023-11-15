
  <div class="login-box-body">
    <p class="login-box-msg">Daftar <b class="fnt">Admin</b> Baru</p>
    <?php 
    if (!empty($this->session->flashdata('msgsc'))) {
      echo '<div class="alert alert-success" id="alert-success">'.$this->session->flashdata('msgsc').'</div>';
    }elseif (!empty($this->session->flashdata('msgerr'))) {
      echo '<div class="alert alert-danger" id="alert-success">'.$this->session->flashdata('msgerr').'</div>';
    }
    ?>
    <?php echo form_open('auth/reg_in');?>
      <div class="form-group has-feedback">
        <input type="text" name="nama" class="form-control" placeholder="Nama Lengkap" required>
        <span class="fa fa-user form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <textarea name="alamat" class="form-control" placeholder="Alamat Lengkap" required></textarea>
        <span class="fa fa-home form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="email" name="email" class="form-control" placeholder="Email" required>
        <span class="fa fa-envelope form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="number" name="hp" class="form-control" placeholder="Nomor Ponsel" maxlength="14" required>
        <span class="fa fa-phone form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <?php 
        $op = array('l'=>'Laki-laki','p'=>'Perempuan');
        $sel = array('Laki-laki');
        $ex = array('class'=>'form-control','placeholder'=>'Jenis Kelamin','required'=>'required');
        echo form_dropdown('kelamin',$op,$sel,$ex);?>
        <span class="fa fa-child form-control-feedback" data-toggle="tooltip" title="Jenis Kelamin"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="text" name="username" class="form-control" placeholder="Username" required>
        <span class="form-control-feedback fa fa-at"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" name="password1" id="pass1" class="form-control" placeholder="Password" onkeyup="checkPass();return false;" required>
        <span class="fa fa-lock form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" name="password2" id="pass2" class="form-control" placeholder="Ulangi Password" onkeyup="checkPass();return false;" required>
        <span class="fa fa-lock form-control-feedback"></span>
      </div>
      <span id="pesan"></span>
      <div class="row">
        <div class="col-xs-4 pull-right">
          <button type="submit" class="btn btn-success btn-block btn-flat" id="svedt"><i class="fa fa-floppy-o"></i> Daftar</button>
        </div>
        <div class="col-xs-8 pull-left">
          <a href="<?php echo base_url('auth');?>" class="text-center" >Sudah Punya Akun?</a>
        </div>
      </div>
    <?php echo form_close();?>

  </div>
</div>