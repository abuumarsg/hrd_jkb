<footer class="text-center">
  <p>&copy <?php echo date("Y");?> <a href="http://hucle-consulting.com" target="blank"><img src="<?php echo base_url('asset/img/hucle.png');?>" width="15px"> <b class="fnt">PT. HUCLE Indonesia</b></a> | All Rights Reserved.</p>
</footer>
<script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
<script src="<?php echo base_url('asset/bower_components/bootstrap/dist/js/bootstrap.min.js');?>"></script>
<script src="<?php echo base_url('asset/bower_components/jquery-ui/jquery-ui.min.js');?>"></script>
<script src="<?php echo base_url('asset/bower_components/jquery-slimscroll/jquery.slimscroll.min.js');?>"></script>
<script src="<?php echo base_url('asset/bower_components/fastclick/lib/fastclick.js');?>"></script>
<script src="<?php echo base_url('asset/dist/js/adminlte.min.js');?>"></script>
<script src="<?php echo base_url('asset/dist/js/demo.js');?>"></script>
<script src="<?php echo base_url('asset/plugins/iCheck/icheck.min.js');?>"></script>
<script src="<?php echo base_url('asset/customs.js');?>"></script>
<script src="<?php echo base_url('asset/ajax.js');?>"></script>
<script src="<?php echo base_url('asset/vendor/overhang/dist/overhang.min.js');?>"></script>
<script src="<?php echo base_url('asset/plugins/pace/pace.min.js');?>"></script>
<script src="<?php echo base_url('asset/vendor/toastr/toastr.min.js');?>"></script>
<script>
  // toastr.options = {
  //         "closeButton": false,
  //         "debug": false,
  //         "newestOnTop": false,
  //         "progressBar": true,
  //         "positionClass": "toast-top-center",
  //         "preventDuplicates": false,
  //         "onclick": null,
  //         "showDuration": "300",
  //         "hideDuration": "1000",
  //         "timeOut": "5000",
  //         "extendedTimeOut": "1000",
  //         "showEasing": "swing",
  //         "hideEasing": "linear",
  //         "showMethod": "fadeIn",
  //         "hideMethod": "fadeOut"
  //       }
  // <?php if($this->session->flashdata('success')){ ?>
  //   toastr.success('<?php echo $this->session->flashdata('success'); ?>');
  // <?php }else if($this->session->flashdata('error')){  ?>
  //   toastr.error('<?php echo $this->session->flashdata('error'); ?>');
  // <?php }else if($this->session->flashdata('warning')){  ?>
  //   toastr.warning('<?php echo $this->session->flashdata('warning'); ?>');
  // <?php }else if($this->session->flashdata('info')){  ?>
  //   toastr.info('<?php echo $this->session->flashdata('info'); ?>');
  // <?php } ?>

  
  $(document).ready(function () {
    $('.sidebar-menu').tree()
  });

</script>
<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%'
    });
  });
</script>
</body>
</html>