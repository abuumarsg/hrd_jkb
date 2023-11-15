<meta http-equiv="cache-control" content="no-cache" />
<meta http-equiv="pragma" content="no-cache" />
<script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
<div class="row" style="overflow: auto;">
	<div class="col-md-3">
	  <p class="text-center text-blue" style="font-size: 9pt;"><i class="fa fa-info-circle"></i> Foto Ideal 3x3</p>
    <?php 
      if(!empty($profile['foto'])){
        $foto = $profile['foto'];
      }else{
        if($profile['kelamin']=='l'){
          $foto = 'asset/img/user-photo/user.png';
        }elseif($profile['kelamin']=='p'){
          $foto = 'asset/img/user-photo/userf.png';
        }
      }
    ?>
    <input type="hidden" id="getKelamin" value="<?php echo $profile['kelamin']; ?>">
    <div id="view_foto">
	  	<img id="fotok" class="profile-user-img img-responsive img-circle view_photo" data-source-photo="<?php echo base_url($foto);?>"  src="<?php echo base_url($foto);?>" alt="User profile picture"><br>
	  </div>
    <button type="button" class="btn btn-danger btn btn-sm btn-block" onclick="modal_reset()"><i class="fa fa-rotate-right"></i> Reset Foto Default</button>
	</div>
	<div class="col-md-9">
    <form id="form_edit_foto">
      <input type="hidden" name="nik" value="<?php echo $profile['nik']; ?>">
  	  <div class="callout callout-danger"><i class="fa fa-info-circle"></i> File foto harus tipe *.jpg, *.png, *.jpeg, *.gif dan ukuran file foto maksimal 1 MB</div>
  	  <input id="uploadFile" placeholder="Nama Foto" disabled="disabled" class="form-control" required="required" >
  	  <span class="input-group-btn">
  	    <div class="fileUpload btn btn-warning btn">
  	      <span><i class="fa fa-folder-open"></i> Pilih Foto</span>
          <input id="uploadBtn" onchange="checkFile('#uploadBtn','#uploadFile','#btn_add')" type="file" class="upload" name="foto"/>
  	    </div>
        <button type="button" id="btn_add" class="btn btn-success" disabled="disabled"><i class="fa fa-floppy-o"></i> Upload</button>
        <button type="submit" id="btn_edit_foto" style="display: none;"></button>
  	  </span>
    </form>
	</div>
</div>
<div id="reset" class="modal fade" role="dialog">
  <div class="modal-dialog modal-sm modal-danger">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title text-center">Konfirmasi Reset Foto</h4>
      </div>
      <form id="form_reset_foto">
        <div class="modal-body text-center">
          <input type="hidden" name="nik" value="<?php echo $profile['nik']; ?>">
          <input type="hidden" name="kelamin" value="<?php echo $profile['kelamin']; ?>">
          <p><b>Apakah anda yakin akan mereset Foto <?php echo $profile['nama'];?> ?</b></p>
        </div>
      </form>
      <div class="modal-footer">
        <button type="button" onclick="do_reset_foto()" class="btn btn-primary"><i class="fa fa-rotate-right"></i> Reset Default</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
      </div>
    </div>
  </div>
</div>
<script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
<script type="text/javascript">
  $(document).ready(function(){
    form_property();all_property();log_fo();$('#table_data').DataTable().destroy();
  })
  function foto_fo(){
    form_key("form_edit_foto","btn_add");
    $('#btn_add').click(function(){
      if($("#form_edit_foto")[0].checkValidity()) {
        $('#btn_edit_foto').click();
      }else{
        notValidParamx();
      }
    })
    $('#form_edit_foto').submit(function(e){
      e.preventDefault(); 
      var data_add = new FormData(this);
      var urladd = "<?php echo base_url('kemp/up_foto');?>";
      submitAjaxFile(urladd,data_add,null,null,null);
      var nik = '<?php echo $profile['nik']; ?>';
      var data={nik:nik};
      var callback=getAjaxData("<?php echo base_url('kemp/emppribadi')?>",data);
      var foto = '<?php echo base_url(); ?>'+callback['foto'];
      var outputp = document.getElementById('fotop');
      outputp.src = foto;
      var outputk = document.getElementById('fotok');
      outputk.src = foto;
      var fp_side = document.getElementById('fp_side');
      fp_side.src = foto;
      $('#form_edit_foto')[0].reset();
    });
  }
  function modal_reset() {
    $('#reset').modal('toggle');
  }
  function do_reset_foto(){
    if($("#form_reset_foto")[0].checkValidity()) {
      submitAjax("<?php echo base_url('kemp/res_foto')?>",'reset','form_reset_foto',null,null);
      var nik = '<?php echo $profile['nik']; ?>';
      var kelamin = $('#getKelamin').val();
      var data={nik:nik,kelamin:kelamin};
      var callback=getAjaxData("<?php echo base_url('kemp/emppribadi')?>",data);
      if(callback['kelamin']=='l' || callback['kelamin']=='L'){
        var foto = '<?php echo base_url(); ?>asset/img/user-photo/user.png';
      }else{
        var foto = '<?php echo base_url(); ?>asset/img/user-photo/userf.png';
      }
      var outputp = document.getElementById('fotop');
      outputp.src = foto;
      var outputk = document.getElementById('fotok');
      outputk.src = foto;
      var fp_side = document.getElementById('fp_side');
      fp_side.src = foto;
    }else{
      notValidParamx();
    } 
  }
  function checkFile(idf,idt,btnx,evemt) {
    var fext = ['jpg', 'png', 'jpeg', 'gif'];
    pathFile(idf,idt,fext,btnx);
    var output1 = document.getElementById('fotok');
    if($('#uploadFile').val()==''){
      var nik = '<?php echo $profile['nik']; ?>';
      var data={nik:nik};
      var callback=getAjaxData("<?php echo base_url('kemp/emppribadi')?>",data);
      if(callback['foto']==''){
        if(callback['kelamin']=='L'){
          var foto = '<?php echo base_url(); ?>asset/img/user-photo/user.png';
        }else{
          var foto = '<?php echo base_url(); ?>asset/img/user-photo/userf.png';
        }
      }else{
        var foto = '<?php echo base_url(); ?>'+callback['foto'];
      }
      output1.src = foto;
    }else{
      output1.src = URL.createObjectURL(event.target.files[0]);
    }
  }
</script>