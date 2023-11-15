<!--

/**
 * GFEACORP - Web Developer
 *
 * @package  Codeigniter
 * @author   Galeh Fatma Eko Ardiansa <galeh.fatma@gmail.com>
 */
--><footer class="main-footer">
  <div class="pull-right hidden-xs">
    <b>HSOFT</b> v<?php echo $this->otherfunctions->companyProfile()['version']; ?>
  </div>
  <strong><img src="<?php echo base_url('asset/img/favicon.png');?>" width="20px"> Copyright &copy; <?php echo date("Y");?> <a href="http://hucle-consulting.com" target="blank"><img src="<?php echo base_url('asset/img/hsoftl.png');?>" width="15px"> <b class="fnt">PT. HUCLE Indonesia</b></a> | </strong> All rights
  reserved.
</footer>
</div>
<script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
<script src="<?php echo base_url('asset/bower_components/select2/dist/js/select2.full.min.js');?>"></script>
<script src="<?php echo base_url('asset/bower_components/jquery-ui/jquery-ui.min.js');?>"></script>
<script src="<?php echo base_url('asset/bower_components/bootstrap/dist/js/bootstrap.min.js');?>"></script>
<script src="<?php echo base_url('asset/bower_components/jquery-slimscroll/jquery.slimscroll.min.js');?>"></script>
<script src="<?php echo base_url('asset/bower_components/fastclick/lib/fastclick.js');?>"></script>
<script src="<?php echo base_url('asset/dist/js/adminlte.min.js');?>"></script>
<script src="<?php echo base_url('asset/dist/js/demo.js');?>"></script>
<script src="<?php echo base_url('asset/plugins/iCheck/icheck.min.js');?>"></script>
<script src="<?php echo base_url('asset/plugins/viewerjs/dist/viewer.min.js');?>"></script>
<script src="<?php echo base_url('asset/plugins/timepicker/bootstrap-timepicker.min.js');?>"></script>
<script src="<?php echo base_url('asset/plugins/chart.js/dist/Chart.min.js');?>"></script>
<script src="<?php echo base_url('asset/customs.js');?>"></script>
<script src="<?php echo base_url('asset/ajax.js');?>"></script>
<script src="<?php echo base_url('asset/chartajax.js');?>"></script>
<script src="<?php echo base_url('asset/bower_components/datatables.net/js/jquery.dataTables.min.js');?>"></script>
<script src="<?php echo base_url('asset/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js');?>"></script>
<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>
<script type="text/javascript">
  var base_url = "<?php print base_url(); ?>";
</script>
<script src="<?php echo base_url('asset/bower_components/sweetalert2/dist/sweetalert2.min.js');?>"></script>
<script src="<?php echo base_url('asset/bower_components/raphael/raphael.min.js');?>"></script>
<script src="<?php echo base_url('asset/bower_components/jquery-sparkline/dist/jquery.sparkline.min.js');?>"></script>
<script src="<?php echo base_url('asset/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js');?>"></script>
<script src="<?php echo base_url('asset/plugins/jvectormap/jquery-jvectormap-world-mill-en.js');?>"></script>
<script src="<?php echo base_url('asset/bower_components/jquery-knob/dist/jquery.knob.min.js');?>"></script>
<script src="<?php echo base_url('asset/bower_components/moment/min/moment.min.js');?>"></script>
<script src="<?php echo base_url('asset/bower_components/bootstrap-daterangepicker/daterangepicker.js');?>"></script>
<script src="<?php echo base_url('asset/bower_components/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js');?>"></script>
<script src="<?php echo base_url('asset/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js');?>"></script>
<script src="<?php echo base_url('asset/bower_components/bootstrap-datepicker/dist/locales/bootstrap-datepicker.id.min.js');?>"></script>
<script src="<?php echo base_url('asset/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js');?>"></script>
<script src="<?php echo base_url('asset/dist/js/pages/dashboard.js');?>"></script>
<script src="<?php echo base_url('asset/plugins/pace/pace.min.js');?>"></script>
<script src="<?php echo base_url('asset/vendor/overhang/dist/overhang.min.js');?>"></script>
<script src="<?php echo base_url('asset/vendor/validator/js/validator.js');?>"></script>
<script src="<?php echo base_url('asset/vendor/toastr/toastr.min.js');?>"></script>
<script src="<?php echo base_url('asset/vendor/iconpicker/dist/js/fontawesome-iconpicker.js');?>"></script>
<script src="<?php echo base_url('asset/plugins/JsTree/dist/jstree.min.js');?>"></script>
<script type="text/javascript">window.onload = date_time('date_time');</script>
<script src="<?php echo base_url('asset/plugins/input-mask/jquery.inputmask.js');?>"></script>
<script src="<?php echo base_url('asset/plugins/input-mask/jquery.inputmask.date.extensions.js');?>"></script>
<script src="<?php echo base_url('asset/plugins/input-mask/jquery.inputmask.extensions.js');?>"></script>
<script src="<?php echo base_url('asset/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js');?>"></script>
<script src="https://cdn.datatables.net/fixedcolumns/3.2.6/js/dataTables.fixedColumns.min.js"></script>
<script src="<?php echo base_url('asset/vendor/jquery.redirect-master/jquery.redirect.js');?>"></script>
<script type="text/javascript">
  $(document).ready(function(){
    all_property();form_property();set_interval();reset_interval();

    //change skin
    if (localStorage.getItem("skin") != null) {
      localStorage.clear("skin");
    }
    $('#skinX').click(function() {
      changeTheme("<?php echo base_url('admin/changeSkin');?>","<?php echo $this->session->userdata('adm')['id'];?>",$('#skinX').data('skin'));
    });
  });
  //auto logout
  function auto_logout() {
    window.location = "<?php echo base_url('auth/lock');?>";
  }
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

</body>
</html>