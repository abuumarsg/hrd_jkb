<meta http-equiv="cache-control" content="no-cache" />
<meta http-equiv="pragma" content="no-cache" />
<form id="form_pribadi_add">
   <input type="hidden" name="id_karyawan" value="<?php echo $profile['id_karyawan'];?>">
   <div class="form-group clearfix">
      <label class="col-sm-2 control-label"></label>
      <div class="col-sm-10">
         <b class="text-red">Kosongkan form isian bila tidak ada data</b>
      </div>
   </div>
   <div class="form-group clearfix">
      <label class="col-sm-2 control-label">Username dan NIK</label>
      <div class="col-sm-10">
         <input type="text" name="username" id="nik" class="form-control" placeholder="Username" value="<?php echo $profile['nik'];?>" readonly="readonly" data-toggle="tooltip" title="NIK atau Username akan otomatis Update berdasarkan tanggal lahir dan tanggal masuk karyawan">
         <span id="errmsg"></span>
      </div>
   </div>
   <div class="form-group clearfix">
      <label class="col-sm-2 control-label">ID Finger</label>
      <div class="col-sm-10">
         <input type="text" name="finger_code" id="id_finger" class="form-control" placeholder="ID Finger Karyawan" value="<?php echo $profile['id_finger'];?>" onblur="cekfinger(this.value)">
         <div class="text-danger" id="notif_finger" style="font-size: 9pt;"></div>
      </div>
   </div>
   <div class="form-group clearfix">
      <label class="col-sm-2 control-label">Nomor KTP</label>
      <div class="col-sm-10">
         <input type="number" name="no_ktp" class="form-control" placeholder="Masukkan Nomor KTP" value="<?php echo $profile['no_ktp'];?>">
      </div>
   </div>
   <div class="form-group clearfix">
      <label class="col-sm-2 control-label">Nama</label>
      <div class="col-sm-10">
         <input type="text" name="nama" class="form-control" placeholder="Masukkan Nama" value="<?php echo $profile['nama'];?>">
      </div>
   </div>
   <div class="form-group clearfix">
      <label for="nama" class="col-sm-2 control-label">Tempat Tanggal Lahir</label>
      <div class="col-sm-10">
         <div class="col-md-6">
            <label>Tempat Lahir</label>
            <input type="text" class="form-control" name="tempat_lahir" placeholder="Tempat Lahir" value="<?php echo $profile['tempat_lahir'];?>">
         </div>
         <div class="col-md-6">
            <label>Tanggal Lahir</label>
               <input type="text" name="tgl_lahir" id="tgl_lahir" class="form-control pull-right date-picker" placeholder="Tanggal Lahir" value="<?php echo $this->formatter->getDateFormatUser($profile['tgl_lahir']); ?>" readonly="readonly">
         </div>
      </div>
   </div>
   <div class="form-group clearfix">
      <label class="col-sm-2 control-label">Tanggal Masuk</label>
      <div class="col-sm-10">
         <div class="has-feedback">
            <span class="fa fa-calendar form-control-feedback"></span>
            <input type="text" name="tgl_masuk" id="tgl_masuk" class="form-control pull-right date-picker" placeholder="Tanggal Masuk" value="<?php echo $this->formatter->getDateFormatUser($profile['tgl_masuk']); ?>" readonly="readonly">
         </div>
      </div>    
   </div>
   <div class="form-group clearfix">
      <label class="col-sm-2 control-label">Email</label>
      <div class="col-sm-10">
         <input type="email" name="email" class="form-control" placeholder="Email" value="<?php echo $profile['email'];?>">
      </div>
   </div>
   <div class="form-group clearfix">
      <label class="col-sm-2 control-label">Jenis Kelamin</label>
      <div class="col-sm-10">
         <?php
         $sel2 = array(null);
         $ex2 = array('class'=>'form-control select2','style'=>'width:100%;');
         echo form_dropdown('kelamin',$kelamin,$sel2,$ex2);
         ?>
      </div>
   </div>
   <div class="form-group clearfix">
      <label class="col-sm-2 control-label">Status Nikah</label>
      <div class="col-sm-10">
         <?php
         $sel3 = array(null);
         $ex3 = array('class'=>'form-control select2','style'=>'width:100%;');
         echo form_dropdown('nikah',$nikah,$sel3,$ex3);
         ?>
      </div>
   </div>
   <div class="form-group clearfix">
      <label class="col-sm-2 control-label">Agama</label>
      <div class="col-sm-10">
         <?php
         $sel4 = array(null);
         $ex4 = array('class'=>'form-control select2','style'=>'width:100%;');
         echo form_dropdown('agama',$agama,$sel4,$ex4);
         ?>
      </div>
   </div>
   <div class="form-group clearfix">
      <label class="col-sm-2 control-label">Alamat Asal</label>
      <div class="col-sm-10">
         <div class="col-md-6">
            <label>Nama Jalan</label>
            <input type="text" name="alamat_asal_jalan" class="form-control" placeholder="Masukkan Nama Jalan" value="<?php echo $profile['alamat_asal_jalan'];?>">
            <label>Nama Desa</label>
            <input type="text" name="alamat_asal_desa" class="form-control" placeholder="Masukkan Nama Desa" value="<?php echo $profile['alamat_asal_desa'];?>">
            <label>Nama Kecamatan</label>
            <input type="text" name="alamat_asal_kecamatan" class="form-control" placeholder="Masukkan Nama Kecamatan" value="<?php echo $profile['alamat_asal_kecamatan'];?>">
         </div>
         <div class="col-md-6">
            <label>Nama Kabupaten / Kota</label>
            <input type="text" name="alamat_asal_kabupaten" class="form-control" placeholder="Masukkan Nama Kabupaten atau Kota" value="<?php echo $profile['alamat_asal_kabupaten'];?>">
            <label>Nama Provinsi</label>
            <input type="text" name="alamat_asal_provinsi" class="form-control" placeholder="Masukkan Nama Provinsi" value="<?php echo $profile['alamat_asal_provinsi'];?>">
            <label>Kode Pos</label>
            <input type="number" name="alamat_asal_pos" maxlength="6" class="form-control" placeholder="Masukkan Kode Pos" value="<?php echo $profile['alamat_asal_pos'];?>">
         </div>
      </div>
   </div>
   <div class="form-group clearfix">
      <label class="col-sm-2 control-label">Alamat Sekarang</label>
      <div class="col-sm-10">
         <div class="col-md-6">
            <label>Nama Jalan</label>
            <input type="text" name="alamat_sekarang_jalan" class="form-control" placeholder="Masukkan Nama Jalan" value="<?php echo $profile['alamat_sekarang_jalan'];?>">
            <label>Nama Desa</label>
            <input type="text" name="alamat_sekarang_desa" class="form-control" placeholder="Masukkan Nama Desa" value="<?php echo $profile['alamat_sekarang_desa'];?>">
            <label>Nama Kecamatan</label>
            <input type="text" name="alamat_sekarang_kecamatan" class="form-control" placeholder="Masukkan Nama Kecamatan" value="<?php echo $profile['alamat_sekarang_kecamatan'];?>">
         </div>
         <div class="col-md-6">
            <label>Nama Kabupaten / Kota</label>
            <input type="text" name="alamat_sekarang_kabupaten" class="form-control" placeholder="Masukkan Nama Kabupaten atau Kota" value="<?php echo $profile['alamat_sekarang_kabupaten'];?>">
            <label>Nama Provinsi</label>
            <input type="text" name="alamat_sekarang_provinsi" class="form-control" placeholder="Masukkan Nama Provinsi" value="<?php echo $profile['alamat_sekarang_provinsi'];?>">
            <label>Kode Pos</label>
            <input type="number" name="alamat_sekarang_pos" maxlength="6" class="form-control" placeholder="Masukkan Kode Pos" value="<?php echo $profile['alamat_sekarang_pos'];?>">
         </div>
      </div>
   </div>
   <div class="form-group clearfix">
      <label class="col-sm-2 control-label">Golongan Darah, Tinggi Badan, Berat Badan</label>
      <div class="col-sm-10">
         <div class="col-md-4">
            <label>Golongan Darah</label>
            <?php
            $sel1 = array(null);
            $ex1 = array('class'=>'form-control select2','style'=>'width:100%;');
            echo form_dropdown('gol_darah',$darah,$sel1,$ex1);
            ?>
         </div>
         <div class="col-md-4">
            <label>Tinggi Badan (Cm)</label>
            <input type="number" name="tinggi" class="form-control" min="100" max="250" placeholder="Masukkan Tinggi Badan" value="<?php echo $profile['tinggi_badan'];?>">
         </div>
         <div class="col-md-4">
            <label>Berat Badan (Kg)</label>
            <input type="number" name="berat" class="form-control" min="20" max="400" placeholder="Masukkan Berat Badan" value="<?php echo $profile['berat_badan'];?>">
         </div>
      </div>
   </div>
   <div class="form-group clearfix">
      <label class="col-sm-2 control-label">Nomor Penting</label>
      <div class="col-sm-10">
         <div class="col-md-6">
            <label>Handphone</label>
            <input type="text" name="no_hp" maxlength="15" class="form-control" pattern="\d*" maxlength="16" placeholder="Masukkan Nomor Handphone" value="<?php echo $profile['no_hp'];?>">
            <label>NPWP</label>
            <input type="text" name="npwp" class="form-control" maxlength="25" placeholder="Masukkan Nomor NPWP" value="<?php echo $profile['npwp'];?>">
         </div>
         <div class="col-md-6">
            <label>BPJS-TK</label>
            <input type="text" name="bpjstk" class="form-control" maxlength="25" placeholder="Masukkan Nomor BPJS-TK" value="<?php echo $profile['bpjstk'];?>">
            <label>BPJS-KES</label>
            <input type="text" name="bpjskes" class="form-control" maxlength="25" placeholder="Masukkan Nomor BPJS-KES" value="<?php echo $profile['bpjskes'];?>">
         </div>
      </div>
   </div>
   <div class="form-group clearfix">
      <label class="col-sm-2 control-label">Akun Bank</label>
      <div class="col-sm-10">
         <div class="col-md-6">
            <label>Nomor Rekening</label>
            <input type="text" name="rekening" class="form-control" pattern="\d*" maxlength="16" placeholder="Masukkan Nomor Rekening" value="<?php echo $profile['rekening'];?>">
         </div>
         <div class="col-md-6">
            <label>Nama Bank</label>
            <select class="form-control select2" id="bank_id" name="bank" style="width: 100%;"></select>
         </div>
      </div>
   </div>
   <div class="form-group clearfix">
      <label class="col-sm-2 control-label">Ukuran Baju & Ukuran Sepatu</label>
      <div class="col-sm-10">
         <div class="col-md-6">
            <label>Ukuran Baju</label>
            <?php
            $baju[null] = 'Pilih Data';
            $sel6 = array(null);
            $ex6 = array('class'=>'form-control select2','style'=>'width:100%;');
            echo form_dropdown('baju',$baju,$sel6,$ex6);
            ?>
         </div>
         <div class="col-md-6">
            <label>Ukuran Sepatu</label>
            <input type="number" name="sepatu" class="form-control" placeholder="Masukkan Nomor Sepatu" value="<?php echo $profile['sepatu'];?>">
         </div>
      </div>
   </div>
   <div class="form-group clearfix">
      <label class="col-sm-2 control-label">Status Pajak</label>
      <div class="col-sm-10">
         <?php
         $status_pajak[null] = 'Pilih Data';
         $sel5 = array(null);
         $ex5 = array('class'=>'form-control select2','style'=>'width:100%;');
         echo form_dropdown('status_pajak',$status_pajak,$sel5,$ex5);
         ?>
      </div>
   </div>
   <div class="form-group clearfix">
      <label for="nama" class="col-sm-2 control-label">Metode Perhitungan PPh 21</label>
      <div class="col-sm-10">
         <?php
         $metode_pph[null] = 'Pilih Data';
         $sel7 = array(NULL);
         $exsel7 = array('class'=>'form-control select2','placeholder'=>'Metode Perhitungan PPh 21','id'=>'metode_pph','style'=>'width:100%');
         echo form_dropdown('metode_pph',$metode_pph,$sel7,$exsel7);
         ?>
      </div>
   </div>
   <div class="form-group clearfix">
      <label for="nama" class="col-sm-2 control-label">Golongan Karyawan</label>
      <div class="col-sm-10">
         <?php
         $golongan[null] = 'Pilih Data';
         $selgL = array(NULL);
         $exselgL = array('class'=>'form-control select2','placeholder'=>'Golongan Karyawan','style'=>'width:100%');
         echo form_dropdown('golongan',$golongan,$selgL,$exselgL);
         ?>
      </div>
   </div>
   <div class="form-group clearfix">
      <div class="col-sm-offset-2 col-sm-10">
         <button type="button" onclick="add_pribadi()" class="btn btn-primary" id="simpan_edit"><i class="fa fa-floppy-o"></i> Simpan</button>
      </div>
   </div>
</form>
<script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
<script type="text/javascript">
   $(document).ready(function(){
      form_property();all_property();set_interval();reset_interval();
   //realtimeAjax("<?php //echo base_url('pages/getNotifAjax')?>","show_notif");
   });
</script>
<script type="text/javascript">
   $(document).ready(function(){
      $('#tgl_lahir, #tgl_masuk').change(function(){
         var ij = $('#tgl_lahir').val();
         var ik = $('#tgl_masuk').val();
         $.ajax({
            method : "POST",
            url   : '<?php echo base_url('employee/generate_nik')?>',
            async : false,
            dataType : 'json',
            data: { tgl_lahir: ij,
               tgl_masuk: ik
            },
            success : function(data){
               $('#nik').val(data['nik']);
               if (data['status_data']) {
                  $('#errmsg').html(data['msg']).css('color','red');  
                  // $('#simpan_edit').attr('disabled','disabled'); 
               }else{
                  $('#errmsg').html(data['msg']).css('color','green');
                  // $('#simpan_edit').removeAttr('disabled','disabled');   
               }
            }
         });
      })
   })
   function pribadi(){
      submitForm('form_pribadi_add');
      var url_select="<?php echo base_url('global_control/select2_global');?>";
      select_data('bank_id',url_select,'master_bank','kode','nama');
      $('#form_pribadi_add #bank_id').val('<?php echo $profile['nama_bank']; ?>').trigger('change');
      $('select[name="gol_darah"]').val('<?php echo $profile['gol_darah']; ?>').trigger('change');
      $('select[name="kelamin"]').val('<?php echo $profile['kelamin']; ?>').trigger('change');
      $('select[name="nikah"]').val('<?php echo $profile['status_nikah']; ?>').trigger('change');
      $('select[name="agama"]').val('<?php echo $profile['agama']; ?>').trigger('change');
      $('select[name="status_pajak"]').val('<?php echo $profile['status_pajak']; ?>').trigger('change');
      $('select[name="metode_pph"]').val('<?php echo $profile['metode_pph']; ?>').trigger('change');
      $('select[name="baju"]').val('<?php echo $profile['baju']; ?>').trigger('change');
   }
   function add_pribadi() {
      var nik = $('#nik').val();
      submitAjax("<?php echo base_url('employee/edit_pribadi')?>",null,'form_pribadi_add',null,null);
      var data={nik:nik};
      var callback=getAjaxData("<?php echo base_url('employee/emppribadi')?>",data);
      if(callback['kelamin']=='l'){
         var foto = '<?php echo base_url(); ?>asset/img/user-photo/user.png';
      }else{
         var foto = '<?php echo base_url(); ?>asset/img/user-photo/userf.png';
      }
      var outputp = document.getElementById('fotop');
      outputp.src = foto;
      var outputk = document.getElementById('fotok');
      outputk.src = foto;
   }
   function submitForm(form) {
      $('#'+form).validator().on('submit', function (e) {
         if (e.isDefaultPrevented()) {
            notValidParamx();
         } else {
            e.preventDefault();
            add_pribadi();
         }
      })
   }
   function cekfinger(id_finger) {
      if(id_finger==''){
         $('#id_finger').css('border-color','#DD4B39');
         $('#notif_finger').html('Please fill out this field.');
         $('#id_finger_label').css('color','#DD4B39');
      }else{
         var id_karyawan = '<?php echo $profile['id_karyawan'];?>';
         var data={id_finger:id_finger,id_karyawan:id_karyawan};
         var callback=getAjaxData("<?php echo base_url('employee/cek_id_finger_nik')?>",data);
         if(callback['val']=='false'){
            $('#id_finger').css('border-color','#DD4B39');
            $('#notif_finger').html('ID Finger Sudah Ada.');
            $('#id_finger_label').css('color','#DD4B39');
         }else if(callback['val']=='yes'){
            $('#id_finger').css('border-color','#00A65A');
            $('#notif_finger').html('Ini ID Finger Anda Yang Lama');
            $('#id_finger_label').css('color','#00A65A');
         }else{
            $('#id_finger').css('border-color','#00A65A');
            $('#notif_finger').html('');
            $('#id_finger_label').css('color','#00A65A');
         }
      }
   }
</script>