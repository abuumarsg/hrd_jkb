<?php
$foto = $this->otherfunctions->getFotoValue($profile['foto'],$profile['kelamin']);
?>
<div class="content-wrapper">
	<section class="content-header">
		<h1>
			<a class="back" onclick="window.history.back()"><i class="fa fa-chevron-circle-left "></i></a>
			<i class="fas fa-chalkboard"></i> Tugas Pembelajaran <small class="view_nama_full"><?php echo $profile['nama'];?></small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
			</li>
			<li class="active view_nama_full">Tugas Pembelajaran <?php echo $profile['nama'];?></li>
		</ol>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-warning">
					<div class="box-header with-border">
						<h3 class="box-title"><i class="fas fa-chalkboard"></i> Tugas Pembelajaran dan Pengembangan yang harus diikuti</small></h3>
						<div class="box-tools pull-right">
							<button class="btn btn-box-tool" onclick="reload_table('table_data')" data-toggle="tooltip" title="Refresh Table"><i class="fas fa-sync"></i></button>
							<button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
							<button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i class="fa fa-times"></i></button>
						</div>
					</div>
					<div class="box-body">
						<table id="table_data" class="table table-bordered table-striped data-table" width="100%">
							<thead>
								<tr>
									<th>No.</th>
									<th>Nama Pembelajaran</th>
									<th>Target Tanggal Selesai</th>
									<th>Soal</th>
									<th>Waktu</th>
									<th>Status</th>
									<th>Aksi</th>
								</tr>
							</thead>
							<tbody>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
<div id="viewStart" class="modal fade" role="dialog">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h2 class="modal-title"><b class="text-muted header_data"></b></h2>
				<input type="hidden" name="id" id="data_id_view">
				<input type="hidden" name="idkar" id="data_idkar_view">
				<input type="hidden" name="materi" id="data_materi_view">
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<div id="textToBegin"></div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button class="btn btn-success" onclick="doBegin()"><i class="fas fa-arrow-circle-right"></i> MULAI</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
			</div>
		</div>
	</div>
</div>
<div id="modal_hasil" class="modal fade" role="dialog">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h2 class="modal-title"><b class="text-muted header_data"></b></h2>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<div class="form-group col-md-12">
							<label class="col-md-4 control-label">Materi Pembelajaran</label>
							<div class="col-md-8" id="data_materi_hasil"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-4 control-label">Nilai Pre Test</label>
							<div class="col-md-8" id="data_pre_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-4 control-label">Nilai Post Test</label>
							<div class="col-md-8" id="data_post_view"></div>
						</div><hr>
						<div class="form-group col-md-12" id="data_project_view">
							<!-- <label class="col-md-4 control-label">Nilai Project</label>
							<div class="col-md-8"></div> -->
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
			</div>
		</div>
	</div>
</div>
<script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$('#table_data').DataTable( {
			ajax: {
				url: "<?php echo base_url('learning/getDataTaskToFollow/view_all/')?>",
				type: 'POST',
				data:{ }
			},
			scrollX: true,
			columnDefs: [
				{   targets: 0, 
					width: '5%',
					render: function ( data, type, full, meta ) {
						return '<center>'+(meta.row+1)+'.</center>';
					}
				},
				{   targets: 6, 
					width: '10%',
					render: function ( data, type, full, meta ) {
						return '<center>'+data+'</center>';
					}
				},
			]
		});
	});
	function view_modal_start(id) {
		var data={id:id};
		var callback=getAjaxData("<?php echo base_url('learning/getDataTaskToFollow/view_one')?>",data);  
		$('#viewStart').modal('show');
		$('.header_data').html(callback['nama_materi']);
		$('#data_id_view').val(id);
		$('#data_idkar_view').val(callback['id_karyawan']);
		$('#data_materi_view').val(callback['kode_materi']);
		$('#textToBegin').html(callback['text']);
	}
	function view_hasil(id) {
		var data={id:id};
		var callback=getAjaxData("<?php echo base_url('learning/getDataTaskToFollow/view_one')?>",data);  
		$('#modal_hasil').modal('show');
		$('.header_data').html(callback['nama_materi']);
		// $('#textResult').html(callback['textResult']);
		$('#data_materi_hasil').html(callback['nama_materi']);
		$('#data_pre_view').html(callback['pretest']);
		$('#data_post_view').html(callback['posttest']);
		$('#data_project_view').html(callback['project']);
	}
	function doBegin() {
		var id = $('#data_id_view').val();
		var idkar = $('#data_idkar_view').val();
		var kode_materi = $('#data_materi_view').val();
		$.redirect("<?php echo base_url('learning/pagesStartPelatihan'); ?>", 
		{
			id: id,
			idkar: idkar,
			kode_materi: kode_materi,
		},
		"POST", "_blank");
	}
</script> 