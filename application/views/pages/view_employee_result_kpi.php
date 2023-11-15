<div class="content-wrapper">
	<section class="content-header">
  		<h1>
  			<a class="back" href="<?php echo base_url('pages/data_hasil_kpi'); ?>"><i class="fa fa-chevron-circle-left "></i></a> <i class="fa fa-users"></i> Daftar Karyawan KPI
  		</h1>
  		<ol class="breadcrumb">
  			<li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
  			<li><a href="<?php echo base_url('pages/data_hasil_kpi');?>"><i class="fa fa-calendar"></i> Daftar Agenda</a></li>
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
  						<h3 class="box-title"><i class="fa fa-users"></i> Daftar Karyawan KPI</h3>
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
  										<?php if(in_array($access['l_ac']['rkp'], $access['access'])){?>
  											<div class="pull-left"> 
  												<?php echo form_open('rekap/rekap_nilai_output');?>
  												<input type="hidden" name="data" id="rekap_data" value="">
  												<input type="hidden" name="th" value="<?php echo $agd['tahun']; ?>">
  												<input type="hidden" name="smt" value="<?php echo $agd['periode']; ?>">
  												<input type="hidden" name="data_form" value="">
  												<button type="submit" class="btn btn-warning" style="margin-right: 3px;"><i class="fas fa-file-excel-o"></i> Export Data</button>
  												<?php echo form_close();?>
  											</div>
  										<?php } 
  										if(in_array('PRN', $access['access'])){?>
  											<div class="pull-left">
  												<?php echo form_open('pages/print_page',['target'=>'_blank']);?>
  												<input type="hidden" name="page" value="<?php echo "result_tasks";?>">
  												<input type="hidden" name="data" id="print_data" value="">
  												<input type="hidden" name="data_form" value="">
  												<button type="submit" class="btn btn-info"><i class="fa fa-print"></i> Print</button>
  												<?php echo form_close();?>
  											</div>
  										<?php } if(in_array($access['l_ac']['rkp'], $access['access'])){?>
  											<div class="pull-right">
  												<?php echo form_open('rekap/rekap_partisipan_output');?>
  												<input type="hidden" name="th" value="<?php echo $agd['tahun']; ?>">
  												<input type="hidden" name="smt" value="<?php echo $agd['periode']; ?>">
  												<input type="hidden" name="data" id="rekap_partisipan" value="">
  												<button type="submit" class="btn btn-danger"><i class="fa fa-users"></i> Rekap Partisipan</button>
  												<?php echo form_close();?>
  											</div>
  										<?php } ?>
  									</div>
  								</div>
  								<br>
  								<div class="callout callout-info">
  									<label><i class="fa fa-info-circle"></i> Bantuan</label><br>
  									Pilih NIK Karyawan untuk melihat Rapor KPI
  								</div>
  								<table id="table_data" class="table table-bordered table-striped table-responsive" width="100%">
  									<thead>
  										<tr>
  											<th>No.</th>
  											<th>NIK</th>
  											<th>Nama Karyawan</th>
  											<th>Jabatan</th>
  											<th>Bagian</th>
  											<th>Departemen</th>
  											<th>Lokasi Kerja</th>
  											<th>Jumlah KPI</th><?php
											$periode = $this->formatter->getNameOfMonthByPeriode($agd['start'],$agd['end'],$agd['tahun']);
											foreach ($periode as $pkey => $pval) {
												echo '<th class="text-center">'.$pval.'</th>';
											}
											?>
  											<th class="text-center">Nilai KPI</th>
  											<th class="text-center">Huruf</th>
  											<th class="text-center">Progress</th>
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
	var max_month="<?=((isset($periode))?count($periode):0)?>"; 
  	$(document).ready(function(){
  		select_data('bagian_filter',url_select,'master_bagian','kode_bagian','nama');
  		select_data('loker_filter',url_select,'master_loker','kode_loker','nama');
  		select_data('departemen_filter',url_select,'master_departement','kode_departement','nama');
  		unsetoption('bagian_filter',['BAG001','BAG002']);
  		unsetoption('departemen_filter',['DEP001']);
  		tableData('all');
  		var getform = $('#form_filter').serialize();
  		$('input[name="data_form"]').val(getform);
  	});
  	function tableData(kode) {
  		$('#usage').val(kode);
  		$('#mode').val('data');
  		$('#table_data').DataTable().destroy();
  		if(kode == 'all'){ $('#form_filter .select2').val('').trigger('change'); }
  		var getform = $('#form_filter').serialize();
  		$('input[name="data_form"]').val(getform);
  		var datax = {form:getform,access:"<?php echo $this->codegenerator->encryptChar($access);?>"};
  		var t=$('#table_data').DataTable( {
  			ajax: {
  				url: "<?php echo base_url('agenda/view_employee_kpi/view_all/'.$this->codegenerator->encryptChar($kode))?>",
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
  				width: '10%',
  				render: function ( data, type, full, meta ) {
					var kodex=11+parseInt(max_month);
					var kodex1=12+parseInt(max_month);
  					return '<a href="<?php echo base_url("pages/report_value_kpi/");?>'+full[kodex]+'/'+full[kodex1]+'" target="_blank">'+data+'</a>';
  				}
  			},
  			{   targets: 7,
  				width: '5%',
  				render: function ( data, type, full, meta ) {
  					return data+' KPI';
  				}
  			},
			{   targets: [(10+parseInt(max_month))],
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
  		var callback=getAjaxData("<?php echo base_url('agenda/view_employee_kpi/view_all/'.$this->codegenerator->encryptChar($kode).'/rekap')?>",datax);
  		$('#rekap_data').val(callback['rekap_nilai']);
  		$('#print_data').val(callback['rekap_nilai']);
  		$('#rekap_partisipan').val(callback['rekap_partisipan']);
  	}

  </script>