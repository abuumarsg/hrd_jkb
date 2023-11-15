<div class="row">
  <div class="col-md-12">
    <h3> History Denda Karyawan </h3>
	<div class="panel-heading bg-green"><i class="fas fa-exclamation-circle"></i> Data Denda Peringatan</div><br>
    <table id="table_denda" class="table table-bordered table-striped table-responsive" width="100%">
      <thead>
        <tr>
			<th>No.</th>
			<th>Nama</th>
			<th>Kode Denda</th>
			<th>No SK Peringatan</th>
			<th>Total Denda</th>
			<th>Diangsur</th>
			<th>Sudah Diangsur</th>
			<th>Saldo Denda</th>
			<th>Aksi</th>
        </tr>
      </thead>
      <tbody>
      </tbody>
    </table>
	<div class="panel-heading bg-yellow"><i class="fas fa-exclamation"></i> Data Denda Non Peringatan</div><br>
    <table id="table_denda_non" class="table table-bordered table-striped table-responsive" width="100%">
      <thead>
        <tr>
			<th>No.</th>
			<th>Nama</th>
			<th>Kode Denda</th>
			<th>Total Denda</th>
			<th>Diangsur</th>
			<th>Sudah Diangsur</th>
			<th>Saldo Denda</th>
			<th>Aksi</th>
        </tr>
      </thead>
      <tbody>
      </tbody>
    </table>
  </div>
</div>
<div id="view_denda" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h2 class="modal-title">Detail Data <b class="text-muted header_data"></b></h2>
				<input type="hidden" name="data_id_per">
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">NIK</label>
							<div class="col-md-6" id="data_nik_per"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Nama</label>
							<div class="col-md-6" id="data_nama_per"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Jabatan</label>
							<div class="col-md-6" id="data_jabatan_per"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Lokasi Kerja</label>
							<div class="col-md-6" id="data_loker_per"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Tanggal Denda</label>
							<div class="col-md-6" id="data_tgl_denda_per"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Jumlah Denda</label>
							<div class="col-md-6" id="data_jumlah_denda_per"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Jumlah Angsuran</label>
							<div class="col-md-6" id="data_jumlah_angsuran_per"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Mengetahui</label>
							<div class="col-md-6" id="data_mengetahui_per"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Menyetujui</label>
							<div class="col-md-6" id="data_menyetujui_per"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Dibuat</label>
							<div class="col-md-6" id="data_dibuat_per"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Keterangan</label>
							<div class="col-md-6" id="data_keterangan_per"></div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Status</label>
							<div class="col-md-6" id="data_status_per"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Dibuat Tanggal</label>
							<div class="col-md-6" id="data_create_date_per"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Diupdate Tanggal</label>
							<div class="col-md-6" id="data_update_date_per"></div>
						</div>
						<!-- <div class="form-group col-md-12">
							<label class="col-md-6 control-label">Dibuat Oleh</label>
							<div class="col-md-6" id="data_create_by_per">
							</div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Diupdate Oleh</label>
							<div class="col-md-6" id="data_update_by_per">
							</div>
						</div> -->
					</div>
				</div>
				<hr>
				<div class="row">
					<div class="col-md-12">
						<div id="data_tabel_per"></div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
			</div>
		</div>
	</div>
</div>
<div id="view_denda_non" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h2 class="modal-title">Detail Data <b class="text-muted header_data"></b></h2>
				<input type="hidden" name="data_id_non">
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">NIK</label>
							<div class="col-md-6" id="data_nik_non"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Nama</label>
							<div class="col-md-6" id="data_nama_non"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Jabatan</label>
							<div class="col-md-6" id="data_jabatan_non"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Lokasi Kerja</label>
							<div class="col-md-6" id="data_loker_non"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Tanggal Denda</label>
							<div class="col-md-6" id="data_tgl_denda_non"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Jumlah Denda</label>
							<div class="col-md-6" id="data_jumlah_denda_non"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Jumlah Angsuran</label>
							<div class="col-md-6" id="data_jumlah_angsuran_non"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Mengetahui</label>
							<div class="col-md-6" id="data_mengetahui_non"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Menyetujui</label>
							<div class="col-md-6" id="data_menyetujui_non"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Dibuat</label>
							<div class="col-md-6" id="data_dibuat_non"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Keterangan</label>
							<div class="col-md-6" id="data_keterangan_non"></div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Status</label>
							<div class="col-md-6" id="data_status_non"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Dibuat Tanggal</label>
							<div class="col-md-6" id="data_create_date_non"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Diupdate Tanggal</label>
							<div class="col-md-6" id="data_update_date_non"></div>
						</div>
						<!-- <div class="form-group col-md-12">
							<label class="col-md-6 control-label">Dibuat Oleh</label>
							<div class="col-md-6" id="data_create_by_non">
							</div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Diupdate Oleh</label>
							<div class="col-md-6" id="data_update_by_non">
							</div>
						</div> -->
					</div>
				</div>
				<hr>
				<div class="row">
					<div class="col-md-12">
						<div id="data_tabel_non"></div>
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
	function log_denda(){
		$('#table_denda').DataTable().destroy();
		$('#table_denda').DataTable( {
			ajax: {
				url: "<?php echo base_url('kemp/log_data_denda/view_all/'.$this->codegenerator->encryptChar($profile['nik']))?>",
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
			{   targets: 8,
				width: '10%',
				render: function ( data, type, full, meta ) {
					return '<center>'+data+'</center>';
				}
			},
			]
		});
		$('#table_denda_non').DataTable().destroy();
		$('#table_denda_non').DataTable( {
			ajax: {
				url: "<?php echo base_url('kemp/log_data_denda/view_all_non/'.$this->codegenerator->encryptChar($profile['nik']))?>",
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
			{   targets: 7,
				width: '5%',
				render: function ( data, type, full, meta ) {
					return '<center>'+data+'</center>';
				}
			},
			]
		});
	}
  	function view_modal_denda(id) {
		var data={id_denda:id};
		var callback=getAjaxData("<?php echo base_url('kemp/log_data_denda/view_one_per')?>",data);  
		$('#view_denda').modal('show');
		$('.header_data').html(callback['kode']);
		$('#data_nik_per').html(callback['nik']);
		$('#data_nama_per').html(callback['nama']);
		$('#data_loker_per').html(callback['loker']);
		$('#data_jabatan_per').html(callback['jabatan']);
		$('#data_tgl_denda_per').html(callback['tgl_denda']);
		$('#data_jumlah_denda_per').html(callback['jumlah_denda']);
		$('#data_jumlah_angsuran_per').html(callback['jumlah_angsuran']);
		$('#data_mengetahui_per').html(callback['mengetahui']);
		$('#data_menyetujui_per').html(callback['menyetujui']);
		$('#data_dibuat_per').html(callback['dibuat']);
		$('#data_keterangan_per').html(callback['keterangan']);
		var status = callback['status'];
		if(status==1){
			var statusval = '<b class="text-success">Aktif</b>';
		}else{
			var statusval = '<b class="text-danger">Tidak Aktif</b>';
		}
		$('#data_status_per').html(statusval);
		$('#data_create_date_per').html(callback['create_date']+' WIB');
		$('#data_update_date_per').html(callback['update_date']+' WIB');
		$('input[name="data_id_per"]').val(callback['id']);
		$('#data_create_by_per').html(callback['nama_buat']);
		$('#data_update_by_per').html(callback['nama_update']);
		$('#data_tabel_per').html(callback['tabel_per']);
  	}
  	function view_modal_non(id) {
		var data={id_denda:id};
		var callback=getAjaxData("<?php echo base_url('kemp/log_data_denda/view_one_non')?>",data);  
		$('#view_denda_non').modal('show');
		$('.header_data').html(callback['kode']);
		$('#data_nik_non').html(callback['nik']);
		$('#data_nama_non').html(callback['nama']);
		$('#data_loker_non').html(callback['loker']);
		$('#data_jabatan_non').html(callback['jabatan']);
		$('#data_tgl_denda_non').html(callback['tgl_denda']);
		$('#data_jumlah_denda_non').html(callback['jumlah_denda']);
		$('#data_jumlah_angsuran_non').html(callback['jumlah_angsuran']);
		$('#data_mengetahui_non').html(callback['mengetahui']);
		$('#data_menyetujui_non').html(callback['menyetujui']);
		$('#data_dibuat_non').html(callback['dibuat']);
		$('#data_keterangan_non').html(callback['keterangan']);
		var status = callback['status'];
		if(status==1){
			var statusval = '<b class="text-success">Aktif</b>';
		}else{
			var statusval = '<b class="text-danger">Tidak Aktif</b>';
		}
		$('#data_status_non').html(statusval);
		$('#data_create_date_non').html(callback['create_date']+' WIB');
		$('#data_update_date_non').html(callback['update_date']+' WIB');
		$('input[name="data_id_non"]').val(callback['id']);
		$('#data_create_by_non').html(callback['nama_buat']);
		$('#data_update_by_non').html(callback['nama_update']);
		$('#data_tabel_non').html(callback['tabel_non']);
		var smpn = callback['tabel_non'];
		if(smpn==''){
			$('#simpan_non').show();
		}else{
			$('#simpan_non').hide();
		}
  	}
</script>