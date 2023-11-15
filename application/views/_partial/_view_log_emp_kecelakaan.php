<div class="row">
	<div class="col-md-12">
		<h3> History Kecelakaan Kerja Karyawan </h3>
		<table id="table_kecelakaan" class="table table-bordered table-striped table-responsive" width="100%">
			<thead>
				<tr>
					<th>No.</th>
					<th>Nama</th>
					<th>No Kecelakaan</th>
					<th>Tanggal</th>
					<th>Kecelakaan Kerja</th>
					<th>Rumah Sakit</th>
					<th>Aksi</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
	</div>
</div>

<!-- view -->
<div id="view_kecelakaan" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h2 class="modal-title">Detail Data <b class="text-muted header_data"></b></h2>
				<input type="hidden" name="data_id_kecelakaan">
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">NIK</label>
							<div class="col-md-6" id="data_nik_kecelakaan"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Nama</label>
							<div class="col-md-6" id="data_nama_kecelakaan"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Jabatan</label>
							<div class="col-md-6" id="data_jabatan_kecelakaan"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Lokasi Kerja</label>
							<div class="col-md-6" id="data_loker_kecelakaan"></div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Status</label>
							<div class="col-md-6" id="data_status_kecelakaan"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Dibuat Tanggal</label>
							<div class="col-md-6" id="data_create_date_kecelakaan"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Diupdate Tanggal</label>
							<div class="col-md-6" id="data_update_date_kecelakaan"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Dibuat Oleh</label>
							<div class="col-md-6" id="data_create_by_kecelakaan">
							</div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Diupdate Oleh</label>
							<div class="col-md-6" id="data_update_by_kecelakaan">
							</div>
						</div>
					</div>
				</div>
				<hr>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Nomor Kecelakaan</label>
							<div class="col-md-6" id="data_no_kecelakaan"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Tanggal Kejadian</label>
							<div class="col-md-6" id="data_tgl_kecelakaan"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Jam Kejadian</label>
							<div class="col-md-6" id="data_jam_kecelakaan"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Kategori Kecelakaan</label>
							<div class="col-md-6" id="data_kategori_kecelakaan"></div>
						</div>
						<div class="form-group col-md-12" id="div_lokasi_dalam" style="display:none;">
							<label class="col-md-6 control-label">Lokasi Kejadian</label>
							<div class="col-md-6" id="data_lokasi_kecelakaan"></div>
						</div>
						<div class="form-group col-md-12" id="div_lokasi_luar" style="display:none;">
							<label class="col-md-6 control-label">Lokasi Kejadian</label>
							<div class="col-md-6" id="data_lokasi_luar"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Rumah Sakit</label>
							<div class="col-md-6" id="data_rs_kecelakaan"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Tangaal Cetak</label>
							<div class="col-md-6" id="data_tgl_cetak_kecelakaan"></div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Mengetahui</label>
							<div class="col-md-6" id="data_mengetahui_kecelakaan"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Menyatakan</label>
							<div class="col-md-6" id="data_menyatakan_kecelakaan"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Saksi 1</label>
							<div class="col-md-6" id="data_saksi1_kecelakaan"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Saksi 2</label>
							<div class="col-md-6" id="data_saksi2_kecelakaan"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Penanggung Jawab</label>
							<div class="col-md-6" id="data_penanggungjawab_kecelakaan"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Tembusan</label>
							<div class="col-md-6" id="data_tembusan_kecelakaan"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Keterangan</label>
							<div class="col-md-6" id="data_keterangan_kecelakaan"></div>
						</div>
					</div>
				</div>
				<hr>
				<div class="row">
					<div class="col-md-12">
						<div class="panel panel-danger">
							<div class="panel-heading bg-red"><b>Kronologi</b></div>
							<div class="panel-body">
								<div class="form-group col-md-12">
									<label class="col-md-4 control-label">Kejadian</label>
									<div class="col-md-8" id="data_kejadian_kecelakaan"></div>
								</div>
								<div class="form-group col-md-12">
									<label class="col-md-4 control-label">Alat</label>
									<div class="col-md-8" id="data_alat_kecelakaan"></div>
								</div>
								<div class="form-group col-md-12">
									<label class="col-md-4 control-label">Bagian Tubuh</label>
									<div class="col-md-8" id="data_bagiantubuh_kecelakaan"></div>
								</div>
							</div>
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
    // form_property();all_property();set_interval();reset_interval();
  })
	function log_kecelakaan(){
		$('#table_kecelakaan').DataTable().destroy();
		$('#table_kecelakaan').DataTable( {
			ajax: {
				url: "<?php echo base_url('kemp/log_kecelakaan_kerja/view_all/'.$this->codegenerator->encryptChar($profile['nik']))?>",
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
				{   targets: 1,
					width: '15%',
					render: function ( data, type, full, meta ) {
						return data;
					}
				},
				{   targets: 5,
					width: '7%',
					render: function ( data, type, full, meta ) {
						return '<center>'+data+'</center>';
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
  	function view_modal_kecelakaan(id) {
		var data={id_kecelakaan:id};
		var callback=getAjaxData("<?php echo base_url('kemp/log_kecelakaan_kerja/view_one')?>",data);  
		$('#view_kecelakaan').modal('show');
		$('.header_data').html(callback['no_sk']);
		$('#data_nik_kecelakaan').html(callback['nik']);
		$('#data_nama_kecelakaan').html(callback['nama']);
		$('#data_jabatan_kecelakaan').html(callback['nama_jabatan']);
		$('#data_loker_kecelakaan').html(callback['nama_loker']);
		$('#data_no_kecelakaan').html(callback['no_sk']);
		$('#data_tgl_kecelakaan').html(callback['tgl']);
		$('#data_tgl_cetak_kecelakaan').html(callback['tgl_cetak']);
		$('#data_jam_kecelakaan').html(callback['jam']+' WIB');
		$('#data_kategori_kecelakaan').html(callback['kategori']);
		$('#data_rs_kecelakaan').html(callback['rumahsakit']);
		$('#data_mengetahui_kecelakaan').html(callback['mengetahui']);
		$('#data_menyatakan_kecelakaan').html(callback['menyatakan']);
		$('#data_saksi1_kecelakaan').html(callback['saksi_1']);
		$('#data_saksi2_kecelakaan').html(callback['saksi_2']);
		$('#data_penanggungjawab_kecelakaan').html(callback['penanggungjawab']);
		$('#data_tembusan_kecelakaan').html(callback['tembusan']);
		$('#data_keterangan_kecelakaan').html(callback['keterangan']);
		$('#data_kejadian_kecelakaan').html(callback['kejadian']);
		$('#data_alat_kecelakaan').html(callback['alat']);
		$('#data_bagiantubuh_kecelakaan').html(callback['bagiantubuh']);
		var kategori = callback['ekategori'];
		if(kategori=='KK_DLM'){
			$('#div_lokasi_dalam').show();
			$('#div_lokasi_luar').hide();
			$('#data_lokasi_kecelakaan').html(callback['lokasi']);
		}else{
			$('#div_lokasi_dalam').hide();
			$('#div_lokasi_luar').show();
			$('#data_lokasi_luar').html(callback['lokasi_luar']);
		}
		var status = callback['status'];
		if(status==1){
			var statusval = '<b class="text-success">Aktif</b>';
		}else{
			var statusval = '<b class="text-danger">Tidak Aktif</b>';
		}
		$('#data_status_kecelakaan').html(statusval);
		$('#data_create_date_kecelakaan').html(callback['create_date']+' WIB');
		$('#data_update_date_kecelakaan').html(callback['update_date']+' WIB');
		$('input[name="data_id_kecelakaan"]').val(callback['id']);
		$('#data_create_by_kecelakaan').html(callback['nama_buat']);
		$('#data_update_by_kecelakaan').html(callback['nama_update']);
  	}
</script>