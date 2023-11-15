<div class="row">
	<div class="col-md-12">
		<h3> History Izin & Cuti Karyawan </h3>
		<table id="table_izin" class="table table-bordered table-striped table-responsive" width="100%">
			<thead>
				<tr>
					<th>No.</th>
					<th>NO Izin/Cuti</th>
					<th>Jenis</th>
					<th>Nama Jenis</th>
					<th>Tanggal Mulai</th>
					<th>Tanggal Selesai</th>
					<th>SKD Dibayar</th>
					<th>Alasan</th>
					<th>Aksi</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
	</div>
</div>

<!-- view -->
<div id="view_izin" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h2 class="modal-title">Detail Data <b class="text-muted header_data"></b></h2>
				<input type="hidden" name="data_id_izin">
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">NIK</label>
							<div class="col-md-6" id="data_nik_izin"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Nama</label>
							<div class="col-md-6" id="data_nama_izin"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Nomor Izin/Cuti</label>
							<div class="col-md-6" id="data_no_izin"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Jenis Izin/Cuti</label>
							<div class="col-md-6" id="data_jenis_izin"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Mulai</label>
							<div class="col-md-6" id="data_mulai_izin"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Selesai</label>
							<div class="col-md-6" id="data_selesai_izin"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">SKD Dibayar</label>
							<div class="col-md-6" id="data_skd_izin"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Alasan</label>
							<div class="col-md-6" id="data_alasan_izin"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Mengetahui</label>
							<div class="col-md-6" id="data_mengetahui_izin"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Menyetujui</label>
							<div class="col-md-6" id="data_menyetujui_izin"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Keterangan</label>
							<div class="col-md-6" id="data_keterangan_izin"></div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Status</label>
							<div class="col-md-6" id="data_status_izin">
							</div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Dibuat Tanggal</label>
							<div class="col-md-6" id="data_create_date_izin"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Diupdate Tanggal</label>
							<div class="col-md-6" id="data_update_date_izin"></div>
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
	function log_izin(){
		$('#table_izin').DataTable().destroy();
		$('#table_izin').DataTable( {
			ajax: {
				url: "<?php echo base_url('kemp/log_izin_cuti/view_all/'.$this->codegenerator->encryptChar($profile['nik']))?>",
				type: 'POST',
				data:{}
			},
			scrollX: true,
			columnDefs: [
				{   targets: 0, 
					width: '5%',
					render: function ( data, type, full, meta ) {
						return '<center>'+(meta.row+1)+'.</center>';
					}
				},
				{   targets: 1,
					width: '15%',
					render: function ( data, type, full, meta ) {
						return data;
					}
				},
				{   targets: 2,
					render: function ( data, type, full, meta ) {
						return '<center>'+data+'</center>';
					}
				},
				{   targets: 8,
					width: '10%',
					render: function ( data, type, full, meta ) {
						return '<center>'+data+'</center>';
					}
				},
			]
		});
	}
  function view_modal_izin(id) {
		var data={id_izin_cuti:id};
		var callback=getAjaxData("<?php echo base_url('kemp/log_izin_cuti/view_one')?>",data);  
		$('#view_izin').modal('show');
		$('.header_data').html(callback['nomor']);
		$('#data_nik_izin').html(callback['nik']);
		$('#data_nama_izin').html(callback['nama']);
		$('#data_no_izin').html(callback['nomor']);
		$('#data_jenis_izin').html(callback['jenis_cuti']);
		$('#data_mulai_izin').html(callback['tanggal_mulai']);
		$('#data_selesai_izin').html(callback['tanggal_selesai']);
		$('#data_skd_izin').html(callback['skd']);
		$('#data_alasan_izin').html(callback['alasan']);
		$('#data_mengetahui_izin').html(callback['mengetahui']);
		$('#data_menyetujui_izin').html(callback['menyetujui']);
		$('#data_keterangan_izin').html(callback['keterangan']);
		var status = callback['status'];
		if(status==1){
			var statusval = '<b class="text-success">Aktif</b>';
		}else{
			var statusval = '<b class="text-danger">Tidak Aktif</b>';
		}
		$('#data_status_izin').html(statusval);
		$('#data_create_date_izin').html(callback['create_date']+' WIB');
		$('#data_update_date_izin').html(callback['update_date']+' WIB');
		$('input[name="data_id_izin"]').val(callback['id']);
		$('#data_create_by_izin').html(callback['nama_buat']);
		$('#data_update_by_izin').html(callback['nama_update']);
  }
</script>