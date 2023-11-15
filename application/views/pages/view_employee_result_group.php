  <div class="content-wrapper">
  	<section class="content-header">
  		<h1>
  			<a class="back" href="<?php echo base_url('pages/data_hasil_group'); ?>"><i class="fa fa-chevron-circle-left "></i></a> <i class="fa fa-users"></i> Daftar Karyawan
  		</h1>
  		<ol class="breadcrumb">
  			<li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
  			<li><a href="<?php echo base_url('pages/data_hasil_group');?>"><i class="fa fa-calendar"></i> Daftar Agenda Gabungan</a></li>
  			<li class="active">Daftar Karyawan</li>
  		</ol>
  	</section>
  	<section class="content">
  		<div class="row">
  			<div class="col-md-12">
  				<div class="box box-primary">
  					<div class="box-header with-border">
  						<h3 class="box-title"><i class="fa fa-search"></i> Advance Filter Data</h3>
  						<div class="box-tools pull-right">
  							<button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
  							<button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i class="fa fa-times"></i></button>
  						</div>
  					</div>
  					<div style="padding-top: 10px;">
  						<form id="form_filter">
  							<input type="hidden" name="usage" id="usage" value="all">
  							<input type="hidden" name="mode" id="mode" value="">
							<input type="hidden" name="nama_periode" value="<?= ((isset($kode['kode_periode']))?$this->model_master->getListPeriodePenilaianActive()[$kode['kode_periode']].' '.$kode['tahun']:$kode['tahun']); ?>">  
  							<div class="box-body">
  								<div class="col-md-4">
  									<div class="">
  										<label>Pilih Departement</label>
  										<select class="form-control select2" id="departemen_filter" name="departemen_filter" style="width: 100%;"></select>
  									</div>
  								</div>
  								<div class="col-md-4">
  									<div class="">
  										<label>Pilih Bagian</label>
  										<select class="form-control select2" id="bagian_filter" name="bagian_filter" style="width: 100%;"></select>
  									</div>
  								</div>
  								<div class="col-md-4">
  									<div class="">
  										<label>Pilih Lokasi Kerja</label>
  										<select class="form-control select2" id="loker_filter" name="loker_filter" style="width: 100%;"></select>
  									</div>
  								</div>
  							</div>
  							<div class="box-footer">
  								<div class="col-md-12">
  									<div class="pull-right">
  										<button type="button" onclick="tableData('search')" class="btn btn-success"><i class="fa fa-eye"></i> Lihat Data</button>
  									</div>
  								</div>
  							</div>
  						</form>
  					</div>
  				</div>
  			</div>
  		</div>
  		<div class="row">
  			<div class="col-md-12">
  				<div class="box box-warning">
  					<div class="box-header with-border">
  						<h3 class="box-title"><i class="fa fa-users"></i> Daftar Karyawan Raport Gabungan <?php
  						$data = ['rekap'=>$kode,'periode'=>null];
  						echo (isset($kode['kode_periode']))?$this->model_master->getListPeriodePenilaianActive()[$kode['kode_periode']].' '.$kode['tahun']:$kode['tahun']; ?></h3>
  						<div class="box-tools pull-right">
  							<button class="btn btn-box-tool" onclick="tableData('all')" data-toggle="tooltip" title="Refresh Table"><i class="fas fa-sync"></i></button>
  							<button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
  							<button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i class="fa fa-times"></i></button>
  						</div>
  					</div>
  					<div class="box-body">
  						<div class="row">
  							<div class="col-md-12">
  								<?php if(in_array($access['l_ac']['rkp'], $access['access'])){?>
  									<div class="pull-left"> 
  										<button type="button" class="btn btn-warning" onclick="rekap_data()" style="margin-right: 3px;"><i class="fas fa-file-excel-o"></i> Export Data</button>
  									</div>
  								<?php } 
  								if(in_array('PRN', $access['access'])){?>
  									<div class="pull-left">
  										<button type="button" class="btn btn-info" onclick="print_data()"><i class="fa fa-print"></i> Print</button>
  									</div>
  								<?php } ?>
  							</div>
  						</div>
  						<br>
  						<div class="row">
  							<div class="col-md-12">
  								<div class="callout callout-info">
  									<label><i class="fa fa-info-circle"></i> Bantuan</label><br>
  									Pilih NIK Karyawan untuk melihat Rapor Penilaian Kinerja
  								</div>
  								<table id="table_data" class="table table-bordered table-striped table-responsive table-hover" width="100%">
  									<thead>
  										<tr>
  											<th rowspan="2">No.</th>
  											<th rowspan="2">NIK</th>
  											<th rowspan="2">Nama Karyawan</th>
  											<th rowspan="2">Jabatan</th>
  											<th rowspan="2">Bagian</th>
  											<th rowspan="2">Departemen</th>
  											<th rowspan="2">Lokasi Kerja</th>
  											<th colspan="2" class="text-center">Nilai</th>
  											<th rowspan="2">Nilai Total</th>
  											<th rowspan="2">Kedisiplinan</th>
  											<th rowspan="2" class="text-center bg-blue">Total Nilai Sekarang <?php echo (isset($kode['kode_periode']))?$this->model_master->getListPeriodePenilaianActive()[$kode['kode_periode']].' '.$kode['tahun']:$kode['tahun']; ?></th>
                       						<th rowspan="2">Huruf</th>
  										</tr>
										<tr>
											<th>Nilai Aspek KPI Output</th>
										  	<th>Nilai Aspek Sikap 360°</th>
										</tr>
  									</thead>
  									<tbody>
  									</tbody>
  								</table>
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
  		select_data('bagian_filter',url_select,'master_bagian','kode_bagian','nama');
  		select_data('loker_filter',url_select,'master_loker','kode_loker','nama');
  		select_data('departemen_filter',url_select,'master_departement','kode_departement','nama');
  		unsetoption('bagian_filter',['BAG001','BAG002']);
  		unsetoption('departemen_filter',['DEP001']);
      	var getform = $('#form_filter').serialize();
      	$('input[name="data_form"]').val(getform);
  		tableData('all');
  		
  	})
  	function tableData(kode) { 
  		$('#usage').val(kode);
  		$('#mode').val('data');
  		$('#table_data').DataTable().destroy();
  		if(kode == 'all'){ $('#form_filter .select2').val('').trigger('change'); }
  		var getform = $('#form_filter').serialize();
  		$('input[name="data_form"]').val(getform);
  		var datax = {form:getform,access:"<?php echo $this->codegenerator->encryptChar($access);?>",data:'<?= $this->codegenerator->encryptChar($kode);?>'};
  		var t=$('#table_data').DataTable( {
  			ajax: {
  				url: "<?php echo base_url('agenda/view_employee_result_group')?>",
  				type: 'POST',
  				data:datax
  			},
  			scrollX: true,
			processing: true,
			order:[1,'asc'],
  			columnDefs: [
  			{   targets: 0, 
  				width: '5%',
  				render: function ( data, type, full, meta ) {
  					return '<center>'+(meta.row+1)+'.</center>';
  				}
  			},
  			{   targets: 1,
  				width: '5%',
  				render: function ( data, type, full, meta ) {
  					return '<a href="<?php echo base_url('pages/report_value_group/');?>'+full[13]+'" target="_blank">'+data+'</a>';
  				}
  			},
  			{   targets: [2,3],
  				width: '30%',
  				render: function ( data, type, full, meta ) {
  					return data;
  				}
  			},
  			{   targets: [7,8,9,10,11,12],
  				width: '5%',
  				render: function ( data, type, full, meta ) {
  					return '<center>'+data+'</center>';
  				}
			  },
  			]
  		});
  		t.on( 'order.dt search.dt', function () {
  			t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
  				cell.innerHTML = i+1;
  			} );
  		}).draw();
  	}
	function rekap_data() {
		window.open("<?php echo base_url('rekap/export_pa_periode/'.$this->codegenerator->encryptChar($kode).'/'.$this->codegenerator->encryptChar($access)) ?>/?"+$('#form_filter').serialize(), "_blank");
	}
	function print_data() {
		window.open("<?php echo base_url('pages/print_direct/result_employee_group/'.$this->codegenerator->encryptChar($kode).'/'.$this->codegenerator->encryptChar($access)) ?>/?"+$('#form_filter').serialize(), "_blank");
	}
</script>