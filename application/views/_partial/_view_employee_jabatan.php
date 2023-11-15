<meta http-equiv="cache-control" content="no-cache" />
<meta http-equiv="pragma" content="no-cache" />
<form id="form_add_jabatan">
   <input type="hidden" name="id_karyawan" value="<?php echo $profile['id_karyawan'];?>">
   <input type="hidden" name="nik" value="<?php echo $profile['nik'];?>">
   <div id="update_pa">
      <div class="form-group clearfix" id="shw_jbt">
         <label class="col-sm-3 control-label">Jabatan</label>
         <div class="col-sm-9">
            <select class="form-control select2" id="data_jabatan_add" name="jabatan" style="width: 100%;"></select>
         </div>
      </div>
      <div class="form-group clearfix">
         <label class="col-sm-3 control-label"></label>
         <div class="col-sm-9">
            <label style="vertical-align: middle;">
               <span id="jabSecond_off" style="font-size: 20px;" onclick="jabSecond();"><i class="far fa-square" aria-hidden="true"></i></span>
               <span id="jabSecond_on" style="display: none; font-size: 20px;" onclick="jabSecond();"><i class="far fa-check-square" aria-hidden="true"></i></span>
               <span style="padding-bottom: 9px;vertical-align: middle;"><b> Ceklist Jika ada Jabatan Lain</b></span>
               <input type="hidden" name="jabatan_second">
            </label>
         </div>
      </div>
      <div class="form-group" id="div_jabatan_sekunder" style="display:none;">
         <div class="col md-12">
            <label class="col-sm-3 control-label">Pilih Jabatan</label>
            <div class="col-sm-9">
               <select name="jabatan_sekunder[]" id="jabatan_sekunder" multiple="multiple" class="form-control select2" style="width: 100%;"></select>
            </div>
         </div>
      </div>
      <div class="form-group clearfix" id="shw_lok">
         <label class="col-sm-3 control-label">Lokasi Kerja</label>
         <div class="col-sm-9">
            <select class="form-control select2" id="data_loker_add" name="loker" style="width: 100%;"></select>
         </div>
      </div>
      <div class="form-group clearfix" id="shw_lok">
         <label class="col-sm-3 control-label">Grade</label>
         <div class="col-sm-9">
            <select class="form-control select2" id="data_grade_add" name="grade" style="width: 100%;"></select>
         </div>
      </div>
      <div class="form-group clearfix" id="shw_lok">
         <label class="col-sm-3 control-label">Status Karyawan</label>
         <div class="col-sm-9">
            <select class="form-control select2" id="data_status_karyawan_add" name="status_karyawan" style="width: 100%;"></select>
         </div>
      </div>
      <div class="form-group clearfix" id="shw_lok">
         <label class="col-sm-3 control-label">Sistem Penggajian</label>
         <div class="col-sm-9">
            <select class="form-control select2" id="data_sistem_penggajian_add" name="sistem_penggajian" style="width: 100%;"></select>
         </div>
      </div>
      <div class="form-group clearfix">
         <label for="nama" class="col-sm-3 control-label">Gaji Pokok</label>
         <div class="col-sm-9">
            <?php
            $gaji_pokok[null] = 'Pilih Data';
            $sel8 = array(NULL);
            $exsel8 = array('class'=>'form-control select2','placeholder'=>'Gaji Pokok','id'=>'gaji_pokok','onchange'=>'cekgaji(this.value)','style'=>'width:100%');
            echo form_dropdown('gaji_pokok',$gaji_pokok,$sel8,$exsel8);
            ?>
         </div>
      </div>
      <div class="form-group clearfix" id="shw_lok">
         <label class="col-sm-3 control-label">Besaran Gaji</label>
         <div class="col-sm-9">
            <?php $gaji=(!empty($profile['gaji']))?$this->formatter->getFormatMoneyUser($profile['gaji']):null;?>
            <input type="text" name="gaji" id="gaji" class="form-control input-money" onkeyup="cekgaji()" placeholder="Masukkan Besaran Gaji" value="<?php echo $gaji;?>" readonly="readonly">
         </div>
      </div>
      <div class="form-group clearfix">
         <div class="col-sm-offset-3 col-sm-9">
            <button type="button" class="btn btn-primary" id="simpan_jabatan" disabled="disabled" onclick="edit_jabatan()"><i class="fa fa-floppy-o"></i> Simpan</button>
         </div>
      </div>
   </div>
</form>
<script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
<script type="text/javascript">
  $(document).ready(function(){
    form_property();all_property();
  });
</script>
<script type="text/javascript">
   function refresh_jabatan(){
      var url_select="<?php echo base_url('global_control/select2_global');?>";
      select_data('data_loker_add',url_select,'master_loker','kode_loker','nama');
      select_data('data_status_karyawan_add',url_select,'master_status_karyawan','kode_status','nama');
      select_data('data_sistem_penggajian_add',url_select,'master_sistem_penggajian','kode_master_penggajian','nama');
      getSelect2("<?php echo base_url('employee/emp_part_jabatan_grade/grade');?>",'data_grade_add'); 
      getSelect2("<?php echo base_url('master/master_jabatan/nama_jabatan')?>",'data_jabatan_add,#jabatan_sekunder');
      $('#form_add_jabatan select[name="jabatan"]').val('<?php echo $profile['jabatan']; ?>').trigger('change');
      $('#form_add_jabatan select[name="loker"]').val('<?php echo $profile['loker']; ?>').trigger('change');
      $('#form_add_jabatan select[name="grade"]').val('<?php echo $profile['grade']; ?>').trigger('change');
      $('#form_add_jabatan select[name="gaji_pokok"]').val('<?php echo $profile['gaji_pokok']; ?>').trigger('change');
      $('#form_add_jabatan select[name="status_karyawan"]').val('<?php echo $profile['status_karyawan']; ?>').trigger('change');
      $('#form_add_jabatan select[name="sistem_penggajian"]').val('<?php echo $profile['kode_penggajian']; ?>').trigger('change');
      var jab_sekunder=[];
      <?php 
      $jb_s=(isset($profile['jabatan_sekunder'])?$this->otherfunctions->getParseOneLevelVar($profile['jabatan_sekunder']):[]);
      if (isset($jb_s)) {
        foreach ($jb_s as $key=>$jbs) {?>
          jab_sekunder[<?=$key?>]="<?= $jbs ?>";
        <?php }
      } ?>
      var ada_jabatan ="<?php echo $profile['jabatan_sekunder'];?>";
      if(ada_jabatan==''){
         $('#jabSecond_off').show();
         $('#jabSecond_on').hide();
      }else{
         $('#jabSecond_off').hide();
         $('#jabSecond_on').show();
         $('#div_jabatan_sekunder').show();
         getSelect2('<?php echo base_url('master/master_jabatan/nama_jabatan'); ?>','jabatan_sekunder');
         $('#jabatan_sekunder').val(jab_sekunder).trigger('change');
      }
      $('#jabSecond_off').click(function(){
         $('#jabSecond_off').hide();
         $('#jabSecond_on').show();
         $('input[name="jabatan_second"]').val('1');
      })
      $('#jabSecond_on').click(function(){
         $('#jabSecond_off').show();
         $('#jabSecond_on').hide();
         $('input[name="jabatan_second"]').val('0');
      })
   }
   function jabSecond(f) {
      setTimeout(function () {
        var jab_sekunder=[];
        <?php 
        $jb_s=(isset($profile['jabatan_sekunder'])?$this->otherfunctions->getParseOneLevelVar($profile['jabatan_sekunder']):[]);
        if (isset($jb_s)) {
          foreach ($jb_s as $key=>$jbs) {?>
            jab_sekunder[<?=$key?>]="<?= $jbs ?>";
          <?php }
        } ?>
         var name = $('input[name="jabatan_second"]').val();
         if(name == 1) {
            $('#div_jabatan_sekunder').show();
            $('#jabatan_sekunder').attr('required','required');
            getSelect2('<?php echo base_url('master/master_jabatan/nama_jabatan'); ?>','jabatan_sekunder');
         $('#jabatan_sekunder').val(jab_sekunder).trigger('change');
         }else {
            $('#div_jabatan_sekunder').hide();
            $('#jabatan_sekunder').removeAttr('required');
         }
      },100);
   }
   function edit_jabatan() {
      var nik = '<?php echo $profile['nik']; ?>';
      submitAjax("<?php echo base_url('employee/edit_jabatan')?>",null,'form_add_jabatan',null,null);
      var data={nik:nik};
      var callback=getAjaxData("<?php echo base_url('employee/emppribadi')?>",data);
      $('.view_nama_jabatan').html(callback['nama_jbt']);
      $('.view_loker').html(callback['nama_loker']);
      $('.view_bagian').html(callback['nama_bgn']);
      $('.view_stkaryawan').html(callback['status_karyawan']);
      $('.view_grade').html(callback['grade']);
      $('.view_lvljabatan').html(callback['nama_lvl']);
   }
  function cekgaji(gaji_pokok) {
    var gaji_pokok = $('#gaji_pokok').val();
    var gaji = $('#gaji').val();
    var d_gaji = '<?php echo $profile['gaji'];?>';
      if(gaji_pokok=='matrix'){
        $('#gaji').attr('readonly', 'readonly');
        $('#gaji').val('');
        $('#simpan_jabatan').removeAttr('disabled');
      }else{
        if(gaji==''){
          $('#simpan_jabatan').attr('disabled', 'disabled');
          if(d_gaji==''){
            $('#gaji').removeAttr('readonly');
            $('#simpan_jabatan').attr('disabled', 'disabled');
          }else{
            $('#gaji').removeAttr('readonly');
            $('#gaji').val('<?php echo (!empty($profile['gaji']))?$this->formatter->getFormatMoneyUser($profile['gaji']):null;?>');
            $('#simpan_jabatan').removeAttr('disabled');
          }
        }else{
          $('#gaji').removeAttr('readonly');
          $('#simpan_jabatan').removeAttr('disabled');
        }
      }
  }
  </script>