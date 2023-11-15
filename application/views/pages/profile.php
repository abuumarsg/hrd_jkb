<?php $link=ucfirst($this->uri->segment(2));?>
<div class="content-wrapper">
   <section class="content-header">
      <h1>
         <a class="back" onclick="window.history.back()"><i class="fa fa-chevron-circle-left "></i></a>
         <i class="fa fa-user"></i> Profile
         <small><?php echo $profile['nama'];?></small>
      </h1>
      <ol class="breadcrumb">
         <li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
         <li class="active"><?php echo $link;?></li>
      </ol>
   </section>
   <section class="content">
      <div class="row">
         <div class="col-md-3">
            <div class="box box-success">
               <div class="box-body box-profile">
                  <img class="profile-user-img img-responsive img-circle view_photo" data-source-photo="<?php echo base_url($profile['foto']); ?>" src="<?php echo base_url($profile['foto']); ?>" alt="User profile picture">
                  <h3 class="profile-username text-center"><?php echo $profile['nama']; ?></h3>
                  <p class="text-muted text-center">Admin</p>
                  <ul class="list-group list-group-unbordered">
                     <li class="list-group-item">
                        <b>Terdaftar Sejak</b> <label class="pull-right label label-info"><?php echo $profile['create']; ?></label>
                     </li>
                     <?php
                     if ($profile['ev'] == 0) {
                        echo '<p style="color: red;" class="text-center">Kamu harus verifikasi email</p>';
                     }
                     ?>
                  </ul>
               </div>
            </div>
         </div>
         <div class="col-md-9">
            <div class="nav-tabs-custom">
               <ul class="nav nav-tabs">
                  <li class="active"><a onclick="info_admin()" href="#info" data-toggle="tab">Informasi Umum</a></li>
                  <li><a onclick="update_admin()" href="#update" data-toggle="tab">Update Informasi</a></li>
                  <li><a onclick="foto_admin()" href="#foto" data-toggle="tab">Upload Foto</a></li>
                  <li><a onclick="pass_admin()" href="#pass" data-toggle="tab">Ubah Password</a></li>
                  <li><a onclick="log_admin()" href="#log" data-toggle="tab">Riwayat Login</a></li>
               </ul>
               <div class="tab-content">
                  <div class="tab-pane active" id="info">
                     <table class='table table-bordered table-striped table-hover'>
                        <tr>
                           <th>Nama Lengkap</th>
                           <td class="view_nama"><?php echo ucwords($profile['nama']);?></td>
                        </tr>
                        <tr>
                           <th>Alamat</th>
                           <td class="view_alamat"><?php echo ucwords($profile['alamat']);?></td>
                        </tr>
                        <tr>
                           <th>Email</th>
                           <td class="view_email">
                              <?php 
                              if ($profile['ev'] == 0) {
                                 echo '<a data-toggle="tooltip" title="Email Belum Diverifikasi">'.$profile['email'].'</a>';
                                 echo ' <i style="color:red;" class="fa fa-warning"></i>';
                                 echo ' <a class="btn btn-xs btn-warning" href="'.base_url('pages/verifikasi').'">Verifikasi</a>';
                              }else{
                                 echo $profile['email'].' <i style="color:green;" data-toggle="tooltip" title="Terverifikasi" class="fa fa-check-circle"></i>';
                              }?>
                           </td>
                        </tr>
                        <tr>
                           <th>Username</th>
                           <td class="view_username"><?php echo $profile['username'];?></td>
                        </tr>
                        <tr>
                           <th>Jenis Kelamin</th>
                           <td class="view_jk">
                              <?php
                              if($profile['kelamin'] == 'l'){
                                 echo '<i class="fa fa-male" style="color:blue"></i> '.$this->otherfunctions->getGender($profile['kelamin']);'';
                              }else{
                                 echo '<i class="fa fa-female" style="color:#ff00a5"></i> '.$this->otherfunctions->getGender($profile['kelamin']);'';
                              }?>
                           </td>
                        </tr> 
                        <tr>
                           <th>Level Admin</th>
                           <td><label class="label label-info"> Admin Level <?php echo $profile['level'];?></label></td>
                        </tr>    
                        <tr>
                           <th>Tanggal Daftar</th>
                           <td><?php echo $profile['create'];?></td>
                        </tr>
                        <tr>
                           <th>Tanggal Update</th>
                           <td><?php echo $profile['update'];?></td>
                        </tr>
                        <tr>
                           <th>Terakhir Login</th>
                           <td><label class="label label-primary"><?php echo $profile['login'];?></label></td>
                        </tr>
                     </table>
                  </div>
                  <div class="tab-pane" id="update">
                     <div class="row">
                        <form id="form_update">
                           <div class="form-group clearfix">
                              <label class="col-sm-2 control-label">Nama</label>
                              <div class="col-sm-10">
                                 <input type="text" name="nama" class="form-control" placeholder="Nama" value="<?php echo $profile['nama'];?>">
                              </div>
                           </div>
                           <div class="form-group clearfix">
                              <label class="col-sm-2 control-label">Username</label>
                              <div class="col-sm-10">
                                 <input type="text" name="username" class="form-control" readonly="readonly" placeholder="Username" value="<?php echo $profile['username'];?>">
                              </div>
                           </div>
                           <div class="form-group clearfix">
                              <label class="col-sm-2 control-label">Email</label>
                              <div class="col-sm-10">
                                 <input type="email" name="email" class="form-control" placeholder="Email" value="<?php echo $profile['email'];?>">
                              </div>
                           </div>
                           <div class="form-group clearfix">
                              <label class="col-sm-2 control-label">No Handphone / Whatsapp</label>
                              <div class="col-sm-10">
                                 <input type="text" name="nomor" class="form-control" placeholder="Username" value="<?php echo $profile['hp'];?>">
                              </div>
                           </div>
                           <div class="form-group clearfix">
                              <label class="col-sm-2 control-label">Alamat</label>
                              <div class="col-sm-10">
                                 <textarea class="form-control" name="alamat" placeholder="Alamat"><?php echo $profile['alamat'];?></textarea>
                              </div>
                           </div>
                           <div class="form-group clearfix">
                              <label class="col-sm-2 control-label">Jenis Kelamin</label>
                              <div class="col-sm-10">
                                 <?php
                                 $op=$this->otherfunctions->getGenderList();
                                 $sel = array($profile['kelamin']);
                                 $ex = array('class'=>'form-control select2','style'=>'width:100%','placeholder'=>'Tipe','required'=>'required');
                                 echo form_dropdown('kelamin',$op,$sel,$ex);
                                 ?>
                              </div>
                           </div>
                           <div class="form-group clearfix">
                              <div class="col-sm-offset-2 col-sm-10">
                                 <button type="button" onclick="do_update()" class="btn btn-primary"><i class="fa fa-floppy-o"></i> Simpan</button>
                              </div>
                           </div>
                        </form>
                     </div>
                  </div>
                  <div class="tab-pane" id="foto">
                     <div class="row">
                        <div class="col-md-3">
                           <p class="text-center text-blue" style="font-size: 9pt;"><i class="fa fa-info-circle"></i> Foto Ideal 3x3</p>
                           <?php $foto = $this->otherfunctions->getFotoValueAdmin($profile['foto'],$profile['kelamin']); ?>
                           <input type="hidden" id="getKelamin" value="<?php echo $profile['kelamin']; ?>">
                           <div id="view_foto">
                              <img id="fotok" class="profile-user-img img-responsive img-circle view_photo" data-source-photo="<?php echo base_url($foto);?>"  src="<?php echo base_url($foto);?>" alt="User profile picture"><br>
                           </div>
                           <button type="button" class="btn btn-danger btn-flat btn-sm btn-block" onclick="modal_reset()"><i class="fa fa-rotate-right"></i> Reset Foto Default</button>
                        </div>
                        <div class="col-md-9">
                           <form id="form_foto_add">
                              <div class="callout callout-danger"><i class="fa fa-info-circle"></i> File foto harus tipe *.jpg, *.png, *.jpeg, *.gif dan ukuran file foto maksimal 1 MB</div>
                              <input id="uploadFile" placeholder="Nama Foto" disabled="disabled" class="form-control" required="required" >
                              <span class="input-group-btn">
                                 <div class="fileUpload btn btn-warning btn-flat" style="height: 33px">
                                    <span><i class="fa fa-folder-open"></i> Pilih Foto</span>
                                    <input id="uploadBtn" onchange="checkFile('#uploadBtn','#uploadFile','#btn_add',event)" type="file" class="upload" name="foto"/>
                                 </div>
                                 <button type="button" id="btn_add" class="btn btn-success" disabled="disabled"><i class="fa fa-floppy-o"></i> Upload</button>
                                 <button type="submit" id="btn_addx" style="display: none;"></button>
                              </span>
                           </form>
                        </div>
                     </div>
                  </div>
                  <div class="tab-pane" id="pass">
                        <form id="form_reset">
                           <div class="col-sm-3"></div>
                           <div class="callout callout-danger col-sm-7"><i class="fa fa-info-circle"></i> Password baru kamu tidak boleh sama dengan password lama</div>
                           <div class="form-group clearfix">
                              <label class="col-sm-3 control-label">Password Lama</label>
                              <div class="col-sm-7" style="padding-right: 0px;">
                                 <input type="password" name="old_pass" class="form-control r_pass" placeholder="Masukkan Password Lama"  required="required">
                              </div>
                           </div>
                           <div class="form-group clearfix">
                              <label class="col-sm-3 control-label">Password Baru</label>
                              <div class="col-sm-7" style="padding-right: 0px;">
                                 <input type="password" name="password1" id="password" class="form-control r_pass" placeholder="Password Baru" required="required" required="required">
                              </div>
                           </div>
                           <div class="form-group clearfix">
                              <label class="col-sm-3 control-label">Ulangi Password Baru</label>
                              <div class="col-sm-7" style="padding-right: 0px;">
                                 <input type="password" name="password2" id="ulangi_password" class="form-control r_pass" placeholder="Ulangi Password Baru" data-match="#password" data-match-error="Whoops, Password Tidak Sama!" required="required">
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
                  <div class="tab-pane" id="log">
                     <p style="color:red">Riwayat login kamu akan dihapus otomatis selama 6 bulan sekali</p>
                     <div class="row">
                        <div class="col-md-12">
                           <table id="table_data" class="table table-bordered table-striped table-responsive" style="width: 100%;">
                              <thead>
                                 <tr>
                                    <th width="1%">No.</th>
                                    <th width="25%">Tanggal Login</th>
                                 </tr>
                              </thead>
                              <tbody>
                              </tbody>
                           </table>
                           <?php 
                           if ($profile['log'] > 0) { ?>
                              <a onclick="modal_delete()" class="btn btn-flat btn-danger" id="btn_delete_log"><i class="fa fa-trash"></i> Hapus Riwayat Login</a>
                           <?php } ?>
                        </div>
                     </div>
                  </div>
               </div>
            </div> 
         </div>
      </div>
   </section>
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
               <input type="hidden" name="id" value="<?php echo $profile['id']; ?>">
               <p>Apakah anda yakin akan mereset foto profil anda??</p>
            </div>
         </form>
         <div class="modal-footer">
            <button type="button" onclick="do_reset_foto()" class="btn btn-primary"><i class="fa fa-rotate-right"></i> Reset Default</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
         </div>
      </div>
   </div>
</div>
<div id="delete_log" class="modal fade" role="dialog">
   <div class="modal-dialog modal-sm modal-danger text-center">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Konfirmasi Hapus</h4>
         </div>
         <div class="modal-body">
            <p>Apakah anda yakin akan menghapus semua data riwayat login anda ??</p>
         </div>
         <div class="modal-footer">
            <form id="form_delete">
               <input type="hidden" name="id" value="<?php echo $profile['id']; ?>">
               <button type="button" onclick="do_delete_all()" class="btn btn-primary"><i class="fa fa-trash"></i> Hapus</button>
               <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
            </form>
         </div>
      </div>
   </div>
</div>
<script type="text/javascript" src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
<script type="text/javascript">
   $(document).ready(function(){
      form_property();all_property();info_admin();
   });
   function info_admin(){
      var id = '<?php echo $profile['id']; ?>';
      var data={id:id};
      var callback=getAjaxData("<?php echo base_url('admin/profile/view_all')?>",data);
      $('.view_nama').html(callback['nama']);
      $('.view_email').html(callback['email']);
      $('.view_alamat').html(callback['alamat']);
      $('.view_username').html(callback['username']);
      $('.view_jk').html(callback['jk']);
   }
   function update_admin(){
      // log_admin();
   }
   function do_update(){
      if($("#form_update")[0].checkValidity()) {
         submitAjax("<?php echo base_url('admin/up_data')?>",null,'form_update',null);
      }else{
         notValidParamx();
      } 
   }
   function foto_admin(){
      log_admin();
      $('#btn_add').click(function(){
         if($("#form_foto_add")[0].checkValidity()) {
            $('#btn_addx').click();
         }else{
            notValidParamx();
         }
      })
      $('#form_foto_add').submit(function(e){
         e.preventDefault();
         var data_add = new FormData(this);
         var urladd = "<?php echo base_url('admin/up_foto');?>";
         submitAjaxFile(urladd,data_add,null,null,null);
         var callback=getAjaxData("<?php echo base_url('admin/profile/view_all')?>",null);
         var foto = '<?php echo base_url(); ?>'+callback['foto'];
         var outputp = document.getElementsByClassName('view_photo')[0];
         outputp.src = foto;
         var outputp = document.getElementsByClassName('view_photo')[1];
         outputp.src = foto;
         var outputp = document.getElementsByClassName('view_photo')[2];
         outputp.src = foto;
         var outputp = document.getElementsByClassName('view_photo')[3];
         outputp.src = foto;
      });    
   }
   function modal_reset() {
      $('#reset').modal('toggle');
   }
   function do_reset_foto(){
      if($("#form_reset_foto")[0].checkValidity()) {
         submitAjax("<?php echo base_url('admin/res_foto')?>",'reset','form_reset_foto',null,null);
         var callback=getAjaxData("<?php echo base_url('admin/profile/view_all')?>",null);
         if(callback['kodekelamin']=='l'){
            var foto = '<?php echo base_url('asset/img/admin-photo/userm.png');?>';
         }else{
            var foto = '<?php echo base_url('asset/img/admin-photo/userf.png');?>';
         }
         var outputp = document.getElementsByClassName('view_photo')[0];
         outputp.src = foto;
         var outputp = document.getElementsByClassName('view_photo')[1];
         outputp.src = foto;
         var outputp = document.getElementsByClassName('view_photo')[2];
         outputp.src = foto;
         var outputp = document.getElementsByClassName('view_photo')[3];
         outputp.src = foto;
      }else{
         notValidParamx();
      } 
   }
   function checkFile(idf,idt,btnx,event) {
      var fext = ['jpg', 'png', 'jpeg', 'gif'];
      pathFile(idf,idt,fext,btnx);
      var output1 = document.getElementById('fotok');
      if($('#uploadFile').val()==''){
         var callback=getAjaxData("<?php echo base_url('admin/profile/view_all')?>",null);
         if(callback['foto']==''){
            if(callback['kelamin']=='l'){
               var foto = '<?php echo base_url('asset/img/admin-photo/userm.png');?>';
            }else{
               var foto = '<?php echo base_url('asset/img/admin-photo/userf.png');?>';
            }
         }else{
            var foto = '<?php echo base_url(); ?>'+callback['foto'];
         }
         output1.src = foto;
      }else{
         output1.src = URL.createObjectURL(event.target.files[0]);
      }
   }
   function pass_admin(){
      // $('.r_pass').val('');
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
         submitAjax("<?php echo base_url('admin/up_pass')?>",null,'form_reset',null,null);
      }else{
         notValidParamx();
      } 
   }
   function log_admin(){
      $('#table_data').DataTable().destroy();
      $('#table_data').DataTable( {
         ajax: {
            url: "<?php echo base_url().'admin/profile/view_log/'.$profile['id']; ?>",
            type: 'POST'
         },
         scrollX: true,
         columnDefs: [
         {   targets: 0, 
            width: '5%',
            render: function ( data, type, full, meta ) {
               return '<center>'+(meta.row+1)+'.</center>';
            }
         }
         ]
      });      
   }
   function modal_delete() {
      $('#delete_log').modal('toggle');
   }
   function do_delete_all(){
      if($("#form_delete")[0].checkValidity()) {
         submitAjax("<?php echo base_url('admin/del_log')?>",'delete_log','form_delete',null,null);
         $('#table_data').DataTable().ajax.reload(function (){
            Pace.restart();
         });
         var callback=getAjaxData("<?php echo base_url().'admin/profile/view_one/'.$profile['id']; ?>",null);
         if(callback['jumlah_log']<1){
            $('#btn_delete_log').css('display','none');
         }else{
            $('#btn_delete_log').css('display','block');
         }
      }else{
         notValidParamx();
      } 
   }
</script>