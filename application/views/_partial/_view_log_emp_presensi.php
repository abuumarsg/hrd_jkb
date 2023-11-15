<div class="row">
	<div class="col-md-12">
		<h3> History Presensi Karyawan </h3>
		<table id="table_presensi" class="table table-bordered table-striped table-responsive" width="100%">
			<thead>
				<tr>
					<th>No.</th>
					<th>Tanggal</th>
					<th>Jam Masuk</th>
					<th>Jam Keluar</th>
					<th>Jumlah Jam kerja</th>
					<th>Jadwal jam Kerja</th>
					<th>Ijin / Cuti</th>
					<th>Lembur</th>
					<th>Over</th>
					<th>Terlambat / Pulang Awal</th>
					<th>Hari Libur</th>
					<th>Aksi</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
	</div>
</div>

<!-- view -->
<div id="view_presensi" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h2 class="modal-title">Detail Data Presensi <b class="text-muted header_data"></b></h2>
				<input type="hidden" name="data_id_view">
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Tanggal</label>
							<div class="col-md-6" id="data_tgl_presensi_view"><b></b></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Jam Masuk</label>
							<div class="col-md-6" id="data_tglmulai_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Jam Keluar</label>
							<div class="col-md-6" id="data_tglselesai_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Jumlah Jam Kerja</label>
							<div class="col-md-6" id="data_jmljamkerja_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Jadwal Kerja</label>
							<div class="col-md-6" id="data_jadwalkerja_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Over</label>
							<div class="col-md-6" id="data_over_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Ijin/Cuti</label>
							<div class="col-md-6" id="data_ijincuti_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Lembur</label>
							<div class="col-md-6" id="data_lebur_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Hari Libur</label>
							<div class="col-md-6" id="data_libur_view"></div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Status</label>
							<div class="col-md-6" id="data_status_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Dibuat Tanggal</label>
							<div class="col-md-6" id="data_create_date_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Diupdate Tanggal</label>
							<div class="col-md-6" id="data_update_date_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Dibuat Oleh</label>
							<div class="col-md-6" id="data_create_by_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Diupdate Oleh</label>
							<div class="col-md-6" id="data_update_by_view"></div>
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
		form_property();all_property();set_interval();reset_interval();
	})
	function log_presensi(){
		$('#table_presensi').DataTable().destroy();
		$('#table_presensi').DataTable( {
			ajax: {
				url: "<?php echo base_url('kemp/log_presensi/view_all/'.$this->codegenerator->encryptChar($profile['nik']))?>",
				type: 'POST',
				data:{}
			},
			fixedColumns:   {
				leftColumns: 2,
				rightColumns: 1
			},
			scrollCollapse: true,
			scrollX: true,
			autoWidth: false,
			columnDefs: [
			{   targets: 0, 
				width: '5%',
				render: function ( data, type, full, meta ) {
					return '<center>'+(meta.row+1)+'.</center>';
				}
			},
			{   targets: 11, 
				width: '10%',
				render: function ( data, type, full, meta ) {
					return '<center>'+data+'</center>';
				}
			},
			],
			drawCallback: function( settings ) {
				$('[data-toggle="tooltip"]').tooltip();
			}
		});
	}
	function view_modal_presensi(id) {
		var data={id_presensi:id};
		var callback=getAjaxData("<?php echo base_url('kemp/log_presensi/view_one/')?>",data);  
		$('#view_presensi').modal('show');
		$('.header_data').html(callback['tgl_presensi']);
		$('input[name="data_id_view"]').val(callback['id']);
		$('#data_tgl_presensi_view').html(callback['tgl_presensi']);
		$('#data_tglmulai_view').html(callback['tgl_masuk']);
		$('#data_tglselesai_view').html(callback['tgl_selesai']);
		$('#data_jmljamkerja_view').html(callback['jam_kerja']);
		$('#data_jadwalkerja_view').html(callback['jadwal']);
		$('#data_over_view').html(callback['over']);
		$('#data_ijincuti_view').html(callback['ijin_cuti']);
		$('#data_lebur_view').html(callback['lembur']);
		$('#data_libur_view').html(callback['libur']);
		$('#data_status_view').html(callback['status']);
		$('#data_create_date_view').html(callback['create_date']);
		$('#data_update_date_view').html(callback['update_date']);
		$('#data_create_by_view').html(callback['create_by']);
		$('#data_update_by_view').html(callback['update_by']);
	}
</script>