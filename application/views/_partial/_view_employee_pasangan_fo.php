<meta http-equiv="cache-control" content="no-cache" />
<meta http-equiv="pragma" content="no-cache" />
<form id="form_pasangan_add">
  <input type="hidden" name="id_karyawan" value="<?php echo $profile['id_karyawan'];?>">
  <input type="hidden" name="nik" value="<?php echo $profile['nik'] ?>">
  <div class="row">
    <div class="col-md-12">
      <div class="form-group clearfix">
        <label class="col-sm-3 control-label">Nama Suami/Istri</label>
        <div class="col-sm-9">
        <input type="text" name="nama_pasangan" class="form-control" placeholder="Nama Suami/Istri" value="<?php echo $profile['nama_pasangan'];?>">
        </div>
      </div>
      <div class="form-group clearfix">
        <label for="nama" class="col-sm-3 control-label">Tempat Tanggal Lahir</label>
        <div class="col-sm-9">
          <input type="text" class="form-control" name="tempat_lahir_pasangan" placeholder="Tempat Lahir" value="<?php echo $profile['tempat_lahir_pasangan'];?>">
          <div class="has-feedback">
            <span class="fa fa-calendar form-control-feedback"></span>
          <input type="text" name="tanggal_lahir_pasangan" class="form-control pull-right date-picker" id="date1" placeholder="Tanggal Lahir" value="<?php echo date('d/m/Y',strtotime($profile['tanggal_lahir_pasangan']));?>">
          </div>
        </div>
      </div>
      <div class="form-group clearfix">
        <label class="col-sm-3 control-label">Pendidikan Terakhir</label>
        <div class="col-sm-9">
          <?php
            $pendidikan[null] = 'Pilih Data';
            $sel4 = array(null);
            $ex4 = array('class'=>'form-control select2','style'=>'width:100%;','required'=>'required');
            echo form_dropdown('pendidikan_terakhir_pasangan',$pendidikan,$sel4,$ex4);
          ?>
        </div>
      </div>
      <div class="form-group clearfix">
        <label class="col-sm-3 control-label">Alamat</label>
        <div class="col-sm-9">
        <input type="text" name="alamat_pasangan" class="form-control" placeholder="Alamat" value="<?php echo $profile['alamat_pasangan'];?>">
        </div>
      </div>
      <div class="form-group clearfix">
        <label class="col-sm-3 control-label">Desa/Kelurahan</label>
        <div class="col-sm-9">
        <input type="text" name="desa_pasangan" class="form-control" placeholder="Nama Desa/Kelurahan" value="<?php echo $profile['desa_pasangan'];?>">
        </div>
      </div>
      <div class="form-group clearfix">
        <label class="col-sm-3 control-label">Kecamatan</label>
        <div class="col-sm-9">
        <input type="text" name="kecamatan_pasangan" class="form-control" placeholder="Nama Kecamatan" value="<?php echo $profile['kecamatan_pasangan'];?>">
        </div>
      </div>
      <div class="form-group clearfix">
        <label class="col-sm-3 control-label">Kabupaten/Kota</label>
        <div class="col-sm-9">
        <input type="text" name="kabupaten_pasangan" class="form-control" placeholder="Nama Kabupaten/Kota" value="<?php echo $profile['kabupaten_pasangan'];?>">
        </div>
      </div>
      <div class="form-group clearfix">
        <label class="col-sm-3 control-label">Provinsi</label>
        <div class="col-sm-9">
        <input type="text" name="provinsi_pasangan" class="form-control" placeholder="Nama Provinsi" value="<?php echo $profile['provinsi_pasangan'];?>">
        </div>
      </div>
      <div class="form-group clearfix">
        <label class="col-sm-3 control-label">Kode POS</label>
        <div class="col-sm-9">
        <input type="number" name="kode_pos_pasangan" maxlength="6" class="form-control" placeholder="Kode POS" value="<?php echo $profile['kode_pos_pasangan'];?>">
        </div>
      </div>
      <div class="form-group clearfix">
        <div class="col-sm-offset-3 col-sm-9">
          <button type="button" class="btn btn-primary" onclick="add_pasangan()"><i class="fa fa-floppy-o"></i> Simpan</button>
        </div>
      </div>
    </div>
  </div>
</form>
<script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
<script type="text/javascript">
  function data_pasangan(){
    $('select[name="pendidikan_terakhir_pasangan"]').val('<?php echo $profile['pendidikan_terakhir_pasangan']; ?>').trigger('change');
  }
  function add_pasangan() {
    submitAjax("<?php echo base_url('kemp/edit_pasangan')?>",null,'form_pasangan_add',null,null);
  }
</script>