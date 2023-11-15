<script src="<?php echo base_url('asset/bower_components/select2/dist/js/select2.full.min.js');?>"></script>
<script src="<?php echo base_url('asset/plugins/timepicker/bootstrap-timepicker.min.js');?>"></script>
<script src="<?php echo base_url('asset/bower_components/bootstrap-daterangepicker/daterangepicker.js');?>"></script>
<script src="<?php echo base_url('asset/bower_components/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js');?>"></script>
<script src="<?php echo base_url('asset/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js');?>"></script>
<script src="<?php echo base_url('asset/bower_components/bootstrap-datepicker/dist/locales/bootstrap-datepicker.id.min.js');?>"></script>
<script src="<?php echo base_url('asset/vendor/overhang/dist/overhang.min.js');?>"></script>

<script src="<?php echo base_url('asset/vendor/toastr/toastr.min.js');?>"></script>
<script src="<?php echo base_url('asset/customs.js');?>"></script>
<script src="<?php echo base_url('asset/ajax.js');?>"></script>
<script type="text/javascript">
  $(document).ready(function(){
    form_property();all_property();
  });
  <?php if($this->session->flashdata('success')){ ?>
    toastr.success('<?php echo $this->session->flashdata('success'); ?>');
  <?php }else if($this->session->flashdata('error')){  ?>
    toastr.error('<?php echo $this->session->flashdata('error'); ?>');
  <?php }else if($this->session->flashdata('warning')){  ?>
    toastr.warning('<?php echo $this->session->flashdata('warning'); ?>');
  <?php }else if($this->session->flashdata('info')){  ?>
    toastr.info('<?php echo $this->session->flashdata('info'); ?>');
  <?php } ?>
</script>