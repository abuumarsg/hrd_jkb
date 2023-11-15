
<!-- <?php //echo form_open('employee/edt_grade',array('class'=>'form-horizontal'));?> -->
<form id="form_add">
  <input type="hidden" name="id_karyawan" value="<?php echo $profile['id_karyawan'];?>">
  <input type="hidden" name="nik" value="<?php echo $profile['nik'];?>">
  <div class="form-group clearfix">
    <label class="col-sm-4 control-label">Grade Sebelumnya</label>
    <div class="col-sm-8">
      <p id="view_nama_grade"><?php echo $grade['nama'];?></p>
    </div>
  </div>
  <div class="form-group clearfix">
    <label class="col-sm-4 control-label">Update grade</label>
    <div class="col-sm-8">
      <select class="form-control select2" id="data_grade_add" name="grade" style="width: 100%;"></select>
    </div>
  </div>
  <div class="form-group clearfix">
    <div class="col-sm-offset-2 col-sm-10">
      <button type="button" class="btn btn-primary" onclick="edit_grade()"><i class="fa fa-floppy-o"></i> Simpan</button>
      <!-- <button type="submit" class="btn btn-primary"><i class="fa fa-floppy-o"></i> Simpan</button> -->
    </div>
  </div>
</form>
<script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
<script src="<?php echo base_url('asset/bower_components/select2/dist/js/select2.full.min.js');?>"></script>
<script src="<?php echo base_url('asset/bower_components/jquery-ui/jquery-ui.min.js');?>"></script>
<script src="<?php echo base_url('asset/bower_components/bootstrap/dist/js/bootstrap.min.js');?>"></script>
<script src="<?php echo base_url('asset/plugins/timepicker/bootstrap-timepicker.min.js');?>"></script>
<script src="<?php echo base_url('asset/customs.js');?>"></script>
<script src="<?php echo base_url('asset/ajax.js');?>"></script>
<script src="<?php echo base_url('asset/bower_components/datatables.net/js/jquery.dataTables.min.js');?>"></script>
<script src="<?php echo base_url('asset/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js');?>"></script>

<script src="<?php echo base_url('asset/bower_components/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js');?>"></script>
<script src="<?php echo base_url('asset/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js');?>"></script>
<script src="<?php echo base_url('asset/vendor/overhang/dist/overhang.min.js');?>"></script>
<script src="<?php echo base_url('asset/vendor/validator/js/validator.js');?>"></script>
<script src="<?php echo base_url('asset/vendor/toastr/toastr.min.js');?>"></script>
<script type="text/javascript">
  $(document).ready(function(){
    form_property();all_property();set_interval();reset_interval();
    //realtimeAjax("<?php //echo base_url('pages/getNotifAjax')?>","show_notif");
  });
</script>
<script type="text/javascript">
  select_data('data_grade_add','<?php echo base_url('global_control/select2_global');?>','master_rank','kode_rank','nama');
  $(document).ready(function(){
  })
  function edit_grade() {
    var nik = '<?php echo $this->uri->segment(3); ?>';
    submitAjax("<?php echo base_url('employee/edt_grade')?>",null,'form_add',null,null);
    var data={nik:nik};
    var callback=getAjaxData("<?php echo base_url('employee/emppribadi')?>",data);
    $('#view_nama_grade, .view_grade').html(callback['getGrade'])
  }
</script>