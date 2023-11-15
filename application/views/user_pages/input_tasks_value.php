<?php
$kode_back = $this->uri->segment(5);
?>
<div class="content-wrapper">
	<section class="content-header">
		<h1>
			<a href="<?php echo base_url('kpages/input_tasks_value/'.$this->uri->segment(3).'/'.$this->uri->segment(5)); ?>" title="Kembali"><i class="fa fa-chevron-circle-left "></i></a>
			<i class="fa fa-edit"></i> Input KPI
			<small><?php echo $nama; ?></small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo base_url('kpages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
			<li><a href="<?php echo base_url('kpages/tasks');?>"><i class="fa fa-calendar"></i> Daftar Agenda</a></li>
			<li><a href="<?php echo base_url('kpages/input_employee_tasks/'.$this->uri->segment(3));?>"><i class="fa fa-users"></i> Daftar Karyawan</a></li>
			<li class="active">Input Nilai <?php echo $nama; ?></li>
		</ol>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-success">
					<div class="box-header with-border">
						<h3 class="box-title"><i class="fa fa-list"></i> Daftar KPI Untuk <?php echo $nama; ?></h3>
						<div class="box-tools pull-right">
							<button class="btn btn-box-tool" onclick="reload_table('table_data')" data-toggle="tooltip" title="Refresh Table"><i class="fas fa-sync"></i></button>
							<button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
							<button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i class="fa fa-times"></i></button>
						</div>
					</div>
					<div class="box-body">
						<div class="row">
							<div class="col-md-12">
								<div class="row">
									<div class="col-md-12">
										<div class="pull-left">
											
										</div>
										<div class="pull-right" style="font-size: 8pt;">
											
										</div>
									</div>
								</div>
							</div>
						</div>
						<br>
						<?php
						?>
						<div class="row">
							<div class="col-md-12">
							<form id="form_add">
								<input type="hidden" name="id_karyawan" value="<?php echo $id_kar; ?>">
								<input type="hidden" name="tabel" value="<?php echo $tabel; ?>">
								<div class="row">
									<div class="col-md-12">
										<div class="panel panel-primary">
											<div class="panel-heading bg-blue"><b>Daftar KPI</b></div>
											<div class="panel-body">
                      <div class="callout callout-info"><b><i class="fa fa-bullhorn"></i> Petunjuk</b><br>
                        <ul>
                          <li>Masukkan nilai sesuai dengan data yang objektif</li>
                          <li>Nilai hanya bisa diinput berupa <b>Angka</b></li>
                          <li>Batas nilai Minimal <b>TIDAK BOLEH</b> negatif</li>
                        </ul>
                        <p>Jika muncul peringatan <b>Harap Cek Data Kembali</b>, maka nilai yang Anda masukkan tidak sesuai dengan ketentuan diatas</p>
                      </div>
											<input type="hidden" name="id_tasks_hidden">
                      <?php 
                      for ($i=1; $i <= $this->otherfunctions->poin_max_range() ; $i++) { 
                        echo '<input type="hidden" name="poin'.$i.'_hidden">';
                      }
                      ?>
											<table id="table_data" class="table table-bordered table-striped table-hover" width="100%">
												<thead>
													<tr>
													<th>No.</th>
													<th>KPI</th>
													<th>Cara Menghitung</th>
													<th>Sumber Data</th>
													<th>Detail Rumus</th>
													<th>Target</th>
													<th>Bobot</th>
													<?php
													$periode = $this->formatter->getNameOfMonthByPeriode($start,$end,$tahun);
													foreach ($periode as $pkey => $pval) {
														echo '<th>'.$pval.'</th>';
													}
													?>
													</tr>
												</thead>
												<tbody>
												</tbody>
											</table>
											</div>
										</div>
									</div>
								</div>
							</form>
							</div>
							<div class="col-md-12">
								<button type="button" onclick="do_add()" id="btn_add" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
								<button type="button" onclick="do_reset()" class="btn btn-danger"><i class="fa fa-sync"></i> Reset</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div> 

<script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
<script type="text/javascript">
	var url_select="<?php echo base_url('global_control/select2_global');?>";
    $(document).ready(function(){
      dataTable();
      $('#btn_import').click(function(){
        $('#form_import')[0].reset();
      })
      $('#import').modal({
        show: false,
        backdrop: 'static',
        keyboard: false
      }) 
      $('#save').click(function(){
        $('.all_btn_import').attr('disabled','disabled');
        $('#progress2').html('<i class="fa fa-spinner fa-pulse fa-fw"></i> Mohon Menunggu, data sedang di upload....')
        setTimeout(function () {
         $('#savex').click();
       },1000);
      })
      $('#form_import').submit(function(e){
        e.preventDefault();
        var data_add = new FormData(this);
        var urladd = "<?php echo base_url('kagenda/import_input_kpi'); ?>";
        submitAjaxFile(urladd,data_add,'#import','#progress2','.all_btn_import');
        reload_table('table_data');
      });
    })
  function checkFile(idf,idt,btnx) {
     var fext = ['xls', 'xlsx', 'csv', 'ods', 'ots'];
     pathFile(idf,idt,fext,btnx);
  }
  function dataTable() {
      $('#table_data').DataTable( {
        ajax: {
          url: "<?php echo base_url('kagenda/input_tasks_value/view_all/'.$this->codegenerator->encryptChar($kode).'/'.$this->uri->segment(4))?>",
          type: 'POST',
        },
        scrollX: true,
        bDestroy: true,
        columnDefs: [
        {   targets: 0, 
          width: '5%',
          render: function ( data, type, full, meta ) {
            return   '<center>'+(meta.row+1)+'.</center>'+
                     '<input type="hidden" value="'+full[0]+'" name="id_task[]">';
          }
        },
        {   targets: 1, 
          width: '5%',
          render: function ( data, type, full, meta ) {
            return '<div style="white-space:normal;word-wrap: break-word;">'+data+'</div>';
          }
        },
				{   targets: 2, 
          width: '5%',
          render: function ( data, type, full, meta ) {
            return data;
          }
        },
        {   targets: 4,
          width: '45%',
          render: function ( data, type, full, meta ) {
            return data;
          }
        },
        {   targets: 6, 
          width: '5%',
          render: function ( data, type, full, meta ) {
            return   '<center>'+data+'%</center>';
          }
        },
        //aksi
        // {   targets: 10,
        //   width: '7%',
        //   render: function ( data, type, full, meta ) {
        //     return data+'<span style="display: none;"></span>';
        //   }
        // },
        ],
         drawCallback: function() {
            $('.select2').select2();
            $('[data-toggle="tooltip"]').tooltip();
         }
      });
  }
  function checkpoin($kx) {
   var oTable = $("#table_data").dataTable();
   var matches = [];
   var checkedcollection = oTable.$(".poin_"+$kx, { "page": "all" });
   checkedcollection.each(function (index, elem) {
    if($(elem).val()==''){
      var ex = 'null';
    }else{
      var ex = $(elem).val();
    }
      matches.push(ex);
   });
   $("input[name='poin"+$kx+"_hidden']").val(matches);
  }

  function checkIdTasks() {
   var oTable = $("#table_data").dataTable();
   var matches = [];
   var checkedcollection = oTable.$("input[name='id_task[]']", { "page": "all" });
   checkedcollection.each(function (index, elem) {
      matches.push($(elem).val());
   });
   $("input[name='id_tasks_hidden']").val(matches);
  }
   function do_add(){
    checkIdTasks();
    for (var i = 1; i <=4; i++) {
      checkpoin(i);
    }
    if($("#form_add")[0].checkValidity()) {
      submitAjax("<?php echo base_url('kagenda/add_input_task')?>",null,'form_add',null,null);
      dataTable();
    }else{
      notValidParamx();
    }
   }
    function max_target(val,target,id) {
      if(parseFloat(val)>parseFloat(target)){
        // notValidParamxCustom('Nilai tidak boleh lebih besar dari target');
        // $('#'+id).val(target);
      }
    }
    function print_template() {
      window.open("<?php echo base_url('rekap/export_input_kpi/'.$this->codegenerator->encryptChar($kode).'/'.$this->uri->segment(4).'/template'); ?>", "_blank");
    }
    function rekap_data() {
      window.open("<?php echo base_url('rekap/export_input_kpi/'.$this->codegenerator->encryptChar($kode).'/'.$this->uri->segment(4).'/rekap'); ?>", "_blank");
    }
    function do_reset() {
      $('.form-control').val(null);
    }
</script>
