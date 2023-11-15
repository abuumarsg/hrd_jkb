<?php $kode = $this->codegenerator->decryptChar($this->uri->segment(3)); ?>
<div class="content-wrapper">
	<div class="alert alert-info">
		<i class="fa fa-calendar faa-shake animated text-blue" style="font-size: 14pt;"></i>
		<?php 
		if ($agd != "") {
			echo ' <b>Agenda Penilaian Output (Target) '.$agd['nama'].' Tahun '.$agd['tahun'].' Periode '.$agd['nama_periode'].'</b>';
		}
		?>
	</div> 
	<section class="content-header">
		<h1>
			<a class="back" href="<?php echo base_url('kpages/tasks');?>"><i class="fa fa-chevron-circle-left "></i></a> <i class="fa fa-users"></i> Daftar Karyawan
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo base_url('kpages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
			<li><a href="<?php echo base_url('kpages/tasks');?>"><i class="fa fa-calendar"></i> Daftar Agenda</a></li>
			<li class="active">Daftar Karyawan</li>
		</ol>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-info">
					<div class="box-header with-border">
						<h3 class="box-title"><i class="fa fa-users"></i> Daftar Karyawan Penilaian Output (Target)</h3>
						<div class="box-tools pull-right">
							<button class="btn btn-box-tool" onclick="reload_table('table_data')" data-toggle="tooltip" title="Refresh Table">
								<i class="fa fa-refresh"></i>
							</button>
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
											<button class="btn btn-primary" type="button" data-toggle="modal" data-target="#import" aria-expanded="false" aria-controls="import" id="btn_import"><i class="fas fa-cloud-upload-alt"></i> Import</button> 
											<button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown" onclick="print_template()"><i class="fas fa-file-excel-o"></i> Export Template</button>
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
												<input type="hidden" name="start" value="<?= $agd['tgl_mulai']; ?>">
												<input type="hidden" name="end" value="<?= $agd['tgl_selesai']; ?>">
												<input type="hidden" name="tahun" value="<?= $agd['tahun']; ?>">
												<input type="hidden" name="tabel" value="<?= $agd['nama_tabel']; ?>">
												<input type="hidden" name="batas" value="<?= $agd['batas']; ?>">
												<div class="modal-body">
													<p class="text-muted">File Data Template harus tipe *.xls, *.xlsx, *.csv, *.ods dan *.ots</p>
													<input id="uploadFilex" placeholder="Nama File" readonly="readonly" class="form-control" required="required">
													<span class="input-group-btn">
														<div class="fileUpload btn btn-warning" style="height: 33px;border-radius: 1px">
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
											<b><i class="fa fa-info-circle"></i> Bantuan</b><br>Pilih NIK untuk melakukan input nilai KPI Output
											<ul>
												<li>Jika terdapat tanda <i class="fa fa-times-circle text-red fa-fw"></i>, maka Anda <b>BELUM</b> melakukan Penilaian</li>
												<li>Jika terdapat tanda <i class="fa fa-check-circle text-green fa-fw"></i>, maka Anda <b>SUDAH</b> melakukan Penilaian</li>
												<li>Jika terdapat tanda <i class="fa fa-refresh fa-spin fa-fw text-yellow"></i>, maka Anda <b>BELUM SELESAI</b> melakukan Penilaian</li>
											</ul>
										</div>
										<div class="nav-tabs-custom">
											<table id="table_data" class="table table-bordered table-striped table-responsive" width="100%">
												<thead>
													<tr>
														<th>No.</th>
														<th>NIK</th> 
														<th>Nama Karyawan</th> 
														<th>Jabatan</th>
														<th>Lokasi Kerja</th>
														<th>Progress</th>
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
		$('#table_data').DataTable( {
			ajax: {
				url: "<?php echo base_url('kagenda/input_employee_tasks/view_all/'.$this->codegenerator->encryptChar($kode))?>"
			},
			processing:true,
			columnDefs: [
			{   targets: 0, 
				width: '3%',
				render: function ( data, type, full, meta ) {
					return '<center>'+(meta.row+1)+'.</center>';
				}
			},
			{   targets: 1,
				width: '7%',
				render: function ( data, type, full, meta ) {
					return '<a href="<?php echo base_url();?>kpages/input_tasks_value/'+full[7]+'/'+full[8]+'" target="_blank">'+full[6]+' '+data+' </a>';
				}
			},
			{   targets: [2,3,4],
				width: '10%',
				render: function ( data, type, full, meta ) {
					return data;
				}
			},
			{   targets: 5,
				width: '10%',
				render: function ( data, type, full, meta ) {
					return '<center>'+data+'</center>';
				}
			},
			],
			drawCallback: function() {
				$('[data-toggle="tooltip"]').tooltip();
			}
		});
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
	});
	function checkFile(idf,idt,btnx) {
		var fext = ['xls', 'xlsx', 'csv', 'ods', 'ots'];
		pathFile(idf,idt,fext,btnx);
	}
	function print_template() {
		window.open("<?php echo base_url('kagenda/export_input_kpi/'.$this->codegenerator->encryptChar($kode).'/all/template'); ?>", "_blank");
	}
	function rekap_data() {
		window.open("<?php echo base_url('kagenda/export_input_kpi/'.$this->codegenerator->encryptChar($kode).'/all/rekap'); ?>", "_blank");
	}
</script>