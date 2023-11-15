<div class="content-wrapper">
	<section class="content-header">
		<h1>
			<a href="<?php echo base_url('pages/data_input_kpi'); ?>" title="Kembali"><i class="fas fa-chevron-circle-left"></i></a>
			<i class="fa fa-edit"></i> Input
			<small>Kpi</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
			<li><a href="<?php echo base_url('pages/data_input_kpi');?>"><i class="fa fa-calendar"></i> Agenda KPI</a></li>
			<li class="active">Daftar Karyawan</li>
		</ol>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-success">
					<div class="box-header with-border">
						<h3 class="box-title"><i class="fa fa-list"></i> Daftar Karyawan Penilaian KPI Output (Target)</h3>
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
											<?php 
											if (in_array('IMP', $access['access'])) {
												echo '<button class="btn btn-primary" type="button" data-toggle="modal" data-target="#import" aria-expanded="false" aria-controls="import" id="btn_import"><i class="fas fa-cloud-upload-alt"></i> Import</button> ';
											}
											if (in_array('EXP', $access['access'])) {
												echo '<div class="btn-group">
												<button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown"><i class="fas fa-file-excel-o"></i> Export
												<span class="fa fa-caret-down"></span></button>
												<ul class="dropdown-menu">
												<li><a onclick="print_template()">Export Template</a></li>
												<li><a onclick="rekap_data()">Export Data</a></li></ul></div>';
											}
											?>
										</div>
									</div>
								</div>
							</div>
							<div class="modal fade" id="import" role="dialog">
								<div class="modal-dialog">
									<div class="modal-content text-center">
										<div class="modal-header">
											<button type="button" class="close all_btn_import" data-dismiss="modal">&times;</button>
											<h2 class="modal-title">Import Data Dari Excel</h2>
										</div>
										<form id="form_import" action="#">
											<input type="hidden" name="start" value="<?= $start; ?>">
											<input type="hidden" name="end" value="<?= $end; ?>">
											<input type="hidden" name="tahun" value="<?= $tahun; ?>">
											<input type="hidden" name="tabel" value="<?= $tabel; ?>">
											<input type="hidden" name="batas" value="<?= $batas; ?>">
											<div class="modal-body">
												<p class="text-muted">File Data Template harus tipe *.xls, *.xlsx, *.csv, *.ods dan *.ots</p>
												<input id="uploadFilex" placeholder="Nama File" readonly="readonly" class="form-control" required="required">
												<span class="input-group-btn">
													<div class="fileUpload btn btn-warning">
														<span><i class="fa fa-folder-open"></i> Pilih File</span>
														<input id="uploadBtnx" type="file" class="upload" name="file" onchange="checkFile('#uploadBtnx','#uploadFilex','#save')" />
													</div>
												</span>
											</div>
											<div class="modal-footer">
												<div id="progress2" style="float: left;"></div>
												<button class="btn btn-primary all_btn_import" id="save" type="button" disabled style="margin-right: 4px;"><i
													class="fa fa-check-circle"></i> Upload</button>
													<button id="savex" type="submit" style="display: none;"></button>
													<button type="button" class="btn btn-default all_btn_import" data-dismiss="modal">Kembali</button>
												</div>
											</form>
										</div>
									</div>
								</div>
							</div>
							<br>
							<div class="row">
								<div class="col-md-12">
									<div class="callout callout-info">
										<label><i class="fa fa-info-circle"></i> Bantuan</label><br>Pilih <b>NIK</b> untuk melakukan Input Penilaian
										Kinerja
									</div>
									<table id="table_data" class="table table-bordered table-striped table-responsive table-hover" width="100%">
										<thead>
											<tr>
												<th>No.</th>
												<th>NIK</th>
												<th>Nama Karyawan</th>
												<th>Jabatan</th>
												<th>Bagian</th>
												<th>Departemen</th>
												<th>Lokasi</th>
												<th>Nilai KPI Output</th>
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
	<?php $periode = $this->formatter->getNameOfMonthByPeriode($agd['start'],$agd['end'],$agd['tahun']);?>
	<script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
	<script type="text/javascript">
		var url_select = "<?php echo base_url('global_control/select2_global');?>";
		var max_month="<?=((isset($periode))?count($periode):0)?>"; 
		$(document).ready(function () {
			var t=$('#table_data').DataTable({
				ajax: {
					url: "<?php echo base_url('agenda/view_employee_kpi/view_all/'.$this->codegenerator->encryptChar($kode))?>",
					type: 'POST',
					data: {
						access: "<?php echo $this->codegenerator->encryptChar($access);?>"
					}
				},
				processing: true,
				scrollX: true,
				order:[1,'asc'],
				columnDefs: [{
					targets: 0,
					width: '5%',
					render: function (data, type, full, meta) {
						return '<center>' + (meta.row + 1) + '.</center>';
					}
				},
				{
					targets: 1,
					width: '8%',
					render: function (data, type, full, meta) {
						var kodex=11+parseInt(max_month);
						var kodex1=12+parseInt(max_month);
						return '<a href="<?php echo base_url();?>pages/input_kpi_value/' + full[kodex]+'/'+full[kodex1] + '" target="_blank">' + data +
						'</a>';
					}
				},
				{
					targets: 7,
					width: '5%',
					render: function (data, type, full, meta) {
						return '<center>' + full[8+parseInt(max_month)] + '</center>';
					}
				},
				]
			});
			t.on( 'order.dt search.dt', function () {
				t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
					cell.innerHTML = i+1;
				} );
			}).draw();
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
				var urladd = "<?php echo base_url('agenda/import_input_kpi'); ?>";
				submitAjaxFile(urladd,data_add,'#import','#progress2','.all_btn_import');
				reload_table('table_data');
			});
		});
		function checkFile(idf,idt,btnx) {
			var fext = ['xls', 'xlsx', 'csv', 'ods', 'ots'];
			pathFile(idf,idt,fext,btnx);
		}
		function print_template() {
			window.open("<?php echo base_url('rekap/export_input_kpi/'.$this->codegenerator->encryptChar($kode).'/all/template'); ?>", "_blank");
		}
		function rekap_data() {
			window.open("<?php echo base_url('rekap/export_input_kpi/'.$this->codegenerator->encryptChar($kode).'/all/rekap'); ?>", "_blank");
		}
	</script>
