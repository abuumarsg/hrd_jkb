<div class="row">
	<div class="col-md-12">
		<h3> History Perjalanan Dinas Karyawan </h3>
		<table id="table_dinas" class="table table-bordered table-striped table-responsive" width="100%">
			<thead>
				<tr>
					<th>No.</th>
					<th>Nama</th>
					<th>NO Perjalanan Dinas</th>
					<th>Tanggal</th>
					<th>Tujuan</th>
					<th>Kendaraan</th>
					<th>Aksi</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
	</div>
</div>

<!-- view -->
<div id="view_dinas" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h2 class="modal-title">Detail Data <b class="text-muted header_data"></b></h2>
				<input type="hidden" name="data_id_dinas">
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">NIK</label>
							<div class="col-md-6" id="data_nik_dinas"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Nama</label>
							<div class="col-md-6" id="data_nama_dinas"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Nomor Perjalanan Dinas</label>
							<div class="col-md-6" id="data_nosk_dinas"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Mengetahui</label>
							<div class="col-md-6" id="data_mengetahui_dinas"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Menyetujui</label>
							<div class="col-md-6" id="data_menyetujui_dinas"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Dibuat</label>
							<div class="col-md-6" id="data_dibuat_dinas"></div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Status</label>
							<div class="col-md-6" id="data_status_dinas"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Dibuat Tanggal</label>
							<div class="col-md-6" id="data_create_date_dinas"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Diupdate Tanggal</label>
							<div class="col-md-6" id="data_update_date_dinas"></div>
						</div>
						<!-- <div class="form-group col-md-12">
							<label class="col-md-6 control-label">Dibuat Oleh</label>
							<div class="col-md-6" id="data_create_by_dinas">
							</div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Diupdate Oleh</label>
							<div class="col-md-6" id="data_update_by_dinas">
							</div>
						</div> -->
					</div>
				</div>
				<hr>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Tanggal Berangkat</label>
							<div class="col-md-6" id="data_berangkat_dinas"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Tanggal Sampai</label>
							<div class="col-md-6" id="data_sampai_dinas"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Tanggal Pulang</label>
							<div class="col-md-6" id="data_pulang_dinas"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Tujuan</label>
							<div class="col-md-6" id="data_tujuan_dinas"></div>
						</div>
						<div class="form-group col-md-12" id="jarak_dinas">
							<label class="col-md-6 control-label">Jarak</label>
							<div class="col-md-6" id="data_jarak_dinas"></div>
						</div>	
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Kendaraan</label>
							<div class="col-md-6" id="data_kedaraan_dinas"></div>
						</div>				
					</div>
					<div class="col-md-6">
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Menginap</label>
							<div class="col-md-6" id="data_menginap_dinas"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Penginapan</label>
							<div class="col-md-6" id="data_nama_penginapan_dinas"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Tugas</label>
							<div class="col-md-6" id="data_tugas_dinas"></div>
						</div>	
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Keterangan</label>
							<div class="col-md-6" id="data_keterangan_dinas"></div>
						</div>						
					</div>
				</div>
				<div id="tabel_tunjangan"></div>
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
	function log_dinas(){
		$('#table_dinas').DataTable().destroy();
		$('#table_dinas').DataTable( {
			ajax: {
				url: "<?php echo base_url('kemp/log_perjalanan_dinas/view_all/'.$this->codegenerator->encryptChar($profile['nik']))?>",
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
				{   targets: 3,
					width: '16%',
					render: function ( data, type, full, meta ) {
						return data;
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
	}
  function view_modal_dinas(id) {
		var data={id_pd:id};
		var callback=getAjaxData("<?php echo base_url('kemp/log_perjalanan_dinas/view_one')?>",data);  
		$('#view_dinas').modal('show');
		$('.header_data').html(callback['no_sk']);
		$('#data_nosk_dinas').html(callback['no_sk']);
		$('#data_nik_dinas').html(callback['nik']);
		$('#data_nama_dinas').html(callback['nama']);
		$('#data_berangkat_dinas').html(callback['tanggal_berangkat']);
		$('#data_sampai_dinas').html(callback['tanggal_sampai']);
		$('#data_pulang_dinas').html(callback['tanggal_pulang']);
		$('#data_tujuan_dinas').html(callback['tujuan']);
		$('#data_kedaraan_dinas').html(callback['kendaraan']);
		$('#data_mengetahui_dinas').html(callback['mengetahui']);
		$('#data_menyetujui_dinas').html(callback['menyetujui']);
		$('#data_dibuat_dinas').html(callback['dibuat']);
		$('#data_tugas_dinas').html(callback['tugas']);
		$('#data_keterangan_dinas').html(callback['keterangan']);
		$('#data_nama_penginapan_dinas').html(callback['nama_penginapan']);
		$('#data_jarak_dinas').html(callback['jarak']);
		var menginap = callback['menginap'];
		var mengipal_val=(menginap==1)?'<b class="text-success">Menginap</b>':'<b class="text-danger">Tidak Menginap</b>';
		$('#data_menginap_dinas').html(mengipal_val);
		$('#tabel_tunjangan').html(callback['tabel_tunjangan']);
		var status = callback['status'];
		var statusval =(status==1)?'<b class="text-success">Aktif</b>':'<b class="text-danger">Tidak Aktif</b>';
		$('#data_status_dinas').html(statusval);
		$('#data_create_date_dinas').html(callback['create_date']+' WIB');
		$('#data_update_date_dinas').html(callback['update_date']+' WIB');
		$('input[name="data_id_dinas"]').val(callback['id']);
		$('#data_create_by_dinas').html(callback['nama_buat']);
		$('#data_update_by_dinas').html(callback['nama_update']);
  }
</script>