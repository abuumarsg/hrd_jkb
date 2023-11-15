<footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>HSOFT</b> v1.0
    </div>
    <strong><img src="<?php echo base_url('asset/img/favicon.png');?>" width="20px"> Copyright &copy; <?php echo date("Y");?> <a href="http://hucle-consulting.com" target="blank"><img src="<?php echo base_url('asset/img/hucle.png');?>" width="15px"> <b class="fnt">PT. HUCLE Indonesia</b></a> | </strong> All rights
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
<script src="<?php echo base_url('asset/vendor/jquery.redirect-master/jquery.redirect.js');?>"></script>
<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>
<script src="<?php echo base_url('asset/bower_components/raphael/raphael.min.js');?>"></script>
<script src="<?php echo base_url('asset/bower_components/morris.js/morris.min.js');?>"></script>
<script src="<?php echo base_url('asset/bower_components/jquery-sparkline/dist/jquery.sparkline.min.js');?>"></script>
<script src="<?php echo base_url('asset/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js');?>"></script>
<script src="<?php echo base_url('asset/plugins/jvectormap/jquery-jvectormap-world-mill-en.js');?>"></script>
<script src="<?php echo base_url('asset/bower_components/jquery-knob/dist/jquery.knob.min.js');?>"></script>
<script src="<?php echo base_url('asset/bower_components/moment/min/moment.min.js');?>"></script>
<script src="<?php echo base_url('asset/bower_components/bootstrap-daterangepicker/daterangepicker.js');?>"></script>
<script src="<?php echo base_url('asset/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js');?>"></script>
<script src="<?php echo base_url('asset/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js');?>"></script>
<script src="<?php echo base_url('asset/dist/js/pages/dashboard.js');?>"></script>
<script src="<?php echo base_url('asset/plugins/pace/pace.min.js');?>"></script>
<script src="<?php echo base_url('asset/vendor/overhang/dist/overhang.min.js');?>"></script>
<script src="<?php echo base_url('asset/vendor/validator/js/validator.js');?>"></script>
<script src="<?php echo base_url('asset/vendor/toastr/toastr.min.js');?>"></script>
<script src="<?php echo base_url('asset/bower_components/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js');?>"></script>
<script src="<?php echo base_url('asset/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js');?>"></script>
<script type="text/javascript">window.onload = date_time('date_time');</script>
<script>
  $(document).ready(function () {
    all_property();form_property();
    $(".sidebar-menu").tree();
    if (localStorage.getItem("skin") != null) {
      localStorage.clear("skin");
    }
    $('#skinX').click(function() {
      changeTheme("<?php echo base_url('kemp/changeSkin');?>","<?php echo $this->session->userdata('emp')['id'];?>",$('#skinX').data('skin'));
    });
  });
  toastr.options = {
          "closeButton": false,
          "debug": false,
          "newestOnTop": false,
          "progressBar": true,
          "positionClass": "toast-top-center",
          "preventDuplicates": false,
          "onclick": null,
          "showDuration": "300",
          "hideDuration": "1000",
          "timeOut": "5000",
          "extendedTimeOut": "1000",
          "showEasing": "swing",
          "hideEasing": "linear",
          "showMethod": "fadeIn",
          "hideMethod": "fadeOut"
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
<script>
  //auto logout
function auto_logout() {
  window.location = "<?php echo base_url('auth/lock');?>";
}
//   $(document).ready(function(){
//  $("#save1").click(function(){
//   $("#swprog").modal({backdrop: 'static', keyboard: false});
//   var minutesLabel = document.getElementById("minutes");
//   var secondsLabel = document.getElementById("seconds");
//   var totalSeconds = 0;
//   setInterval(setTime, 1000);

//   function setTime() {
//     ++totalSeconds;
//     secondsLabel.innerHTML = pad(totalSeconds % 60);
//     minutesLabel.innerHTML = pad(parseInt(totalSeconds / 60));
//   }

//   function pad(val) {
//     var valString = val + "";
//     if (valString.length < 2) {
//       return "0" + valString;
//     } else {
//       return valString;
//     }
//   }
// });  
// }); 
// $(document).ready(function(){
//  $("#save2").click(function(){
//   $("#swprog").modal({backdrop: 'static', keyboard: false});
//   var minutesLabel = document.getElementById("minutes");
//   var secondsLabel = document.getElementById("seconds");
//   var totalSeconds = 0;
//   setInterval(setTime, 1000);

//   function setTime() {
//     ++totalSeconds;
//     secondsLabel.innerHTML = pad(totalSeconds % 60);
//     minutesLabel.innerHTML = pad(parseInt(totalSeconds / 60));
//   }

//   function pad(val) {
//     var valString = val + "";
//     if (valString.length < 2) {
//       return "0" + valString;
//     } else {
//       return valString;
//     }
//   }
// });   
// }); 
// function isi(n){
//   var mbu = $("#kt"+n);
//   if ($("#up"+n).val() > $("#up"+n).data("bawah") && $("#up"+n).val() < $("#up"+n).data("atas")) {
//     $("#kt"+n).attr("disabled","disabled");
//     $("#kt"+n).removeAttr("required");
//     $("#ps"+n).html("");
//     $("#sv").removeAttr("disabled");
//   }else{
//     $("#kt"+n).removeAttr("disabled");
//     $("#kt"+n).attr("required","required");
//     if (mbu.val() == "") {
//       $("#sv").attr("disabled","disabled");
//     }
//     mbu.keyup(function(){
//       if (mbu.val() != "") {
//         if (mbu.val().length > 10) {
//           $("#sv").removeAttr("disabled");
//         }else{
//           $("#sv").attr("disabled","disabled"); 
//         }
//       }else{
//         $("#sv").attr("disabled","disabled"); 
//       }

//     });

//     $("#ps"+n).html("Anda Harus Mengisi Keterangan");
//   }
  
// }
  /*
  $(document).ready(function(){

   // Add new element
   $(".add").click(function(){

    // Finding total number of elements added
    var total_element = $(".element").length;
   
    // last <div> with element class id
    var lastid = $(".element:last").attr("id");
    var split_id = lastid.split("_");
    var nextindex = Number(split_id[1]) + 1;

    var max = 5;
    // Check total number elements
    if(total_element < max ){
     // Adding new div container after last occurance of element class
     $(".element:last").after("<div class='element' id='div_"+ nextindex +"'></div>");
   
     // Adding element to <div>
     $("#div_" + nextindex).append("<label class='col-sm-3 control-label'>Data Anak Ke - "+ nextindex +"</label><div class='col-sm-7'><label>Nama Anak Ke - "+ nextindex +"</label> <input type='text' class='form_control' placeholder='Masukkan Nama' id='txt_"+ nextindex +"'></div><div class='col-sm-2'><span id='remove_" + nextindex + "' class='remove btn btn-danger'><i class='fa fa-trash'></i></span><div>");
   
    }
   
   });

   // Remove element
   $('.contai1').on('click','.remove',function(){
   
    var id = this.id;
    var split_id = id.split("_");
    var deleteindex = split_id[1];

    // Remove <div> with id
    if (deleteindex-1 != 0) {
      $("#div_" + deleteindex).remove();
    }
    

   }); 
  });*/
//   $(document).ready(function() {
//     $('#stt_m').change(function(){
//       if ($(this).val() == 'Menikah') {
//         $('#passg').show();
//       }else{
//         $('#passg').hide();
//       }
//     });
//   });
//   $(function () {
//   $("#example1").DataTable({
//     "paging": true,
//     "lengthChange": true,
//     "searching": true,
//     "ordering": true,
//     "info": true,
//     "autoWidth": true,
//     "scrollX": true
//   });
//   $("#example3").DataTable({
//     "paging": true,
//     "lengthChange": true,
//     "searching": true,
//     "ordering": true,
//     "info": true,
//     "autoWidth": true,
//     "scrollX": true
//   });
//   $('#example2').DataTable({
//     "paging": true,
//     "lengthChange": true,
//     "searching": true,
//     "ordering": true,
//     "info": true,
//     "autoWidth": true,
//     "scrollX": true
//   });
//   $('#std').DataTable({
//     "paging": true,
//     "lengthChange": true,
//     "searching": true,
//     "ordering": true,
//     "info": true,
//     "autoWidth": true,
//     "scrollX": true
//   });
// });
//   $('.select2').select2();
// $("#alert-success").fadeTo(8000, 300).slideUp(300, function(){
//   $("#alert-success").slideUp(300);
// }); 
// $("#sld").fadeTo(5000, 500).slideUp(500, function(){
//   $("#sld").slideUp(500);
// });
// $("#alert-warning").fadeTo(8000, 300).slideUp(300, function(){
//   $("#alert-warning").slideUp(300);
// });
// $("#alert-danger").fadeTo(8000, 300).slideUp(300, function(){
//   $("#alert-danger").slideUp(300);
// });
// $('#tt1').datepicker({
//     language: "id",
//     format: "dd/mm/yyyy",
//     todayHighlight: true
// });
// $('#tt2').datepicker({
//     language: "id",
//     format: "dd/mm/yyyy",
//     todayHighlight: true
// });
// $('#tt3').datepicker({
//     language: "id",
//     format: "dd/mm/yyyy",
//     todayHighlight: true
// });
//   $(function () {
    
//     $('#datepicker').datepicker({
//       format: 'dd/mm/yyyy',
//     })
//     $('#datepicker1').datepicker({
//       format: 'dd/mm/yyyy',
//     })
//     $('#datepicker2').datepicker({
//       format: 'dd/mm/yyyy',
//     })
//     $('#datepicker3').datepicker({
//       format: 'dd/mm/yyyy',
//     })
//     $('.timepicker').timepicker({
//       showInputs: false,
//       showMeridian: false,
//       showSeconds: true,
//       minuteStep: 1,
//       secondStep: 1,
//       defaultTime: '08:00:00'
//     });
//     $('.timepicker2').timepicker({
//       showInputs: false,
//       showMeridian: false,
//       showSeconds: true,
//       minuteStep: 1,
//       secondStep: 1,
//       defaultTime: '16:00:00'
//     });
//     $('.timepicker3').timepicker({
//       showInputs: false,
//       showMeridian: false,
//       showSeconds: true,
//       minuteStep: 1,
//       secondStep: 1,
//       defaultTime: '08:00:00'
//     });
//     $('.timepicker4').timepicker({
//       showInputs: false,
//       showMeridian: false,
//       showSeconds: true,
//       minuteStep: 1,
//       secondStep: 1,
//       defaultTime: '16:00:00'
//     });
//     $('input').iCheck({
//       checkboxClass: 'icheckbox_square-blue',
//       radioClass: 'iradio_square-blue',
//       increaseArea: '20%' // optional
//     });
//   });
</script>
</body>
</html>