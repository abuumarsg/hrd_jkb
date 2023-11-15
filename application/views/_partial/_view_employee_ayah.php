<meta http-equiv="cache-control" content="no-cache" />
<meta http-equiv="pragma" content="no-cache" />
<form id="form_ayah_add">
  <div class="row">
    <div class="col-md-12">
      <input type="hidden" name="id_karyawan" value="<?php echo $profile['id_karyawan'];?>">
      <input type="hidden" name="nik" value="<?php echo $profile['nik'];?>">
      <div class="form-group clearfix">
        <label class="col-sm-3 control-label">Nama Ayah</label>
        <div class="col-sm-9">
          <input type="text" name="nama_ayah" class="form-control" placeholder="Nama Ayah" value="<?php echo $profile['nama_ayah'];?>">
        </div>
      </div>
      <div class="form-group clearfix">
        <label for="nama" class="col-sm-3 control-label">Tempat Tanggal Lahir</label>
        <div class="col-sm-9">
          <input type="text" class="form-control" name="tempat_lahir_ayah" placeholder="Tempat Lahir" value="<?php echo $profile['tempat_lahir_ayah'];?>">
          <div class="has-feedback">
          <span class="fa fa-calendar form-control-feedback"></span>
            <input type="text" name="tanggal_lahir_ayah" class="form-control pull-right date-picker" placeholder="Tanggal Lahir" value="<?php echo $this->formatter->getDateFormatUser($profile['tanggal_lahir_ayah']);?>" readonly="readonly">
          </div>
        </div>
      </div>
      <div class="form-group clearfix">
        <label class="col-sm-3 control-label">Nomor Telp. Ayah</label>
        <div class="col-sm-9">
          <input type="text" name="no_hp_ayah" class="form-control" maxlength="15" placeholder="Nomor Telpon Ayah" value="<?php echo $profile['no_hp_ayah'];?>">
        </div>
      </div>
      <div class="form-group clearfix">
        <label class="col-sm-3 control-label">Pendidikan Terakhir</label>
        <div class="col-sm-9">
          <?php
            $pendidikan[null] = 'Pilih Data';
            $sel4 = array(null);
            $ex4 = array('class'=>'form-control select2','style'=>'width:100%;','required'=>'required');
            echo form_dropdown('pendidikan_terakhir_ayah',$pendidikan,$sel4,$ex4);
          ?>
        </div>
      </div>
      <div class="form-group clearfix">
        <label class="col-sm-3 control-label">Alamat</label>
        <div class="col-sm-9">
          <input type="text" name="alamat_ayah" class="form-control" placeholder="Alamat Sekarang" value="<?php echo $profile['alamat_ayah'];?>">
        </div>
      </div>
      <div class="form-group clearfix">
        <label class="col-sm-3 control-label">Desa/Kelurahan</label>
        <div class="col-sm-9">
          <input type="text" name="desa_ayah" class="form-control" placeholder="Nama Desa/Kelurahan Sekarang" value="<?php echo $profile['desa_ayah'];?>">
        </div>
      </div>
      <div class="form-group clearfix">
        <label class="col-sm-3 control-label">Kecamatan</label>
        <div class="col-sm-9">
          <input type="text" name="kecamatan_ayah" class="form-control" placeholder="Nama Kecamatan Sekarang" value="<?php echo $profile['kecamatan_ayah'];?>">
        </div>
      </div>
      <div class="form-group clearfix">
        <label class="col-sm-3 control-label">Kabupaten/Kota</label>
        <div class="col-sm-9">
          <input type="text" name="kabupaten_ayah" class="form-control" placeholder="Nama Kabupaten/Kota Sekarang" value="<?php echo $profile['kabupaten_ayah'];?>">
        </div>
      </div>
      <div class="form-group clearfix">
        <label class="col-sm-3 control-label">Provinsi</label>
        <div class="col-sm-9">
          <input type="text" name="provinsi_ayah" class="form-control" placeholder="Nama Provinsi Sekarang" value="<?php echo $profile['provinsi_ayah'];?>">
        </div>
      </div>
      <div class="form-group clearfix">
        <label class="col-sm-3 control-label">Kode POS</label>
        <div class="col-sm-9">
          <input type="number" name="kode_pos_ayah" maxlength="6" class="form-control" placeholder="Kode POS Sekarang" value="<?php echo $profile['kode_pos_ayah'];?>">
        </div>
      </div>
      <div class="form-group clearfix">
        <div class="col-sm-offset-3 col-sm-9">
          <button type="button" class="btn btn-primary" onclick="add_ayah()"><i class="fa fa-floppy-o"></i> Simpan</button>
          <!-- <button type="submit" class="btn btn-primary"><i class="fa fa-floppy-o"></i> Simpan</button> -->
        </div>
      </div>
    </div>
  </div>
</form>
<script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
<script type="text/javascript">
  $(document).ready(function(){
    form_property();all_property();set_interval();
  });
  function data_ayah(){
    $('select[name="pendidikan_terakhir_ayah"]').val('<?php echo $profile['pendidikan_terakhir_ayah']; ?>').trigger('change');
  }
  function add_ayah() {
    submitAjax("<?php echo base_url('employee/edit_ayah')?>",null,'form_ayah_add',null,null);
  }
</script>