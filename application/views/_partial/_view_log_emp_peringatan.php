<div class="row">
	<div class="col-md-12">
		<h3> History peringatan Karyawan </h3>
		<table id="table_peringatan" class="table table-bordered table-striped table-responsive" width="100%">
			<thead>
				<tr>
					<th>No.</th>
					<th>Nama</th>
					<th>NO SK</th>
					<th>Tanggal SK</th>
					<th>Peringatan</th>
					<th>Berlaku Sampai</th>
					<th>Aksi</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
	</div>
</div>
<div id="view_peringatan" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h2 class="modal-title">Detail Data <b class="text-muted header_data"></b></h2>
				<input type="hidden" name="data_id_peringatan">
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">NIK</label>
							<div class="col-md-6" id="data_nik_peringatan"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Nama</label>
							<div class="col-md-6" id="data_nama_peringatan"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Nomor SK</label>
							<div class="col-md-6" id="data_nosk_peringatan"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Tanggal SK</label>
							<div class="col-md-6" id="data_tglsk_peringatan"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Tanggal Berlaku</label>
							<div class="col-md-6" id="data_tglberlaku_peringatan"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Mengetahui</label>
							<div class="col-md-6" id="data_mengetahui_peringatan"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Menyetujui</label>
							<div class="col-md-6" id="data_menyetujui_peringatan"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Dibuat</label>
							<div class="col-md-6" id="data_dibuat_peringatan"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Pelanggaran</label>
							<div class="col-md-6" id="data_pelanggaran_peringatan"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Denda</label>
							<div class="col-md-6" id="data_denda_peringatan"></div>
						</div>
						<div class="form-group col-md-12" id="besaran_denda_v" style="display:none;">
							<label class="col-md-6 control-label">Besaran Denda</label>
							<div class="col-md-6" id="data_besaran_denda_peringatan"></div>
						</div>
						<div class="form-group col-md-12" id="angsuran_denda" style="display:none;">
							<label class="col-md-6 control-label">Angsuran</label>
							<div class="col-md-6" id="data_angsuran_peringatan"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Keterangan (Sanksi)</label>
							<div class="col-md-6" id="data_keterangan_peringatan"></div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Status</label>
							<div class="col-md-6" id="data_status_peringatan"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Dibuat Tanggal</label>
							<div class="col-md-6" id="data_create_date_peringatan"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Diupdate Tanggal</label>
							<div class="col-md-6" id="data_update_date_peringatan"></div>
						</div>
						<!-- <div class="form-group col-md-12">
							<label class="col-md-6 control-label">Dibuat Oleh</label>
							<div class="col-md-6" id="data_create_by_peringatan">
							</div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Diupdate Oleh</label>
							<div class="col-md-6" id="data_update_by_peringatan">
							</div>
						</div> -->
					</div>
				</div>
				<hr>
				<div class="row">
					<div class="col-md-6">
						<div class="panel panel-danger">
							<div class="panel-heading bg-red"><h4>Data Sebelumnya</h4></div>
							<div class="panel-body">
								<div class="form-group col-md-12">
									<label class="col-md-6 control-label">Peringatan Sebelumnya</label>
									<div class="col-md-6" id="data_peringatanasal_peringatan"></div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="panel panel-success">
							<div class="panel-heading bg-green"><h4>Data Baru</h4></div>
							<div class="panel-body">
								<div class="form-group col-md-12">
									<label class="col-md-6 control-label">Peringatan Baru</label>
									<div class="col-md-6" id="data_peringatanbaru_peringatan"></div>
								</div>
								<div class="form-group col-md-12">
									<label class="col-md-6 control-label">Berlaku Sampai</label>
									<div class="col-md-6" id="data_tglberlakusampai_peringatan"></div>
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
    form_property();all_property();set_interval();reset_interval();
  })
  function log_peringatan(){
    $('#table_peringatan').DataTable().destroy();
    $('#table_peringatan').DataTable( {
		ajax: {
			url: "<?php echo base_url('kemp/log_peringatan_karyawan/view_all/'.$this->codegenerator->encryptChar($profile['nik']))?>",
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
		{   targets: 6,
			width: '10%',
			render: function ( data, type, full, meta ) {
				return '<center>'+data+'</center>';
			}
		},
		]
    });
  }
  function view_modal_peringatan(id) {
		var data={id_peringatan:id};
		var callback=getAjaxData("<?php echo base_url('kemp/log_peringatan_karyawan/view_one')?>",data);  
		$('#view_peringatan').modal('show');
		$('.header_data').html(callback['no_sk']);
		$('#data_nosk_peringatan').html(callback['no_sk']);
		$('#data_tglsk_peringatan').html(callback['tgl_sk']);
		$('#data_tglberlaku_peringatan').html(callback['tgl_berlaku']);
		$('#data_tglberlakusampai_peringatan').html(callback['berlaku_sampai']);
		$('#data_nik_peringatan').html(callback['nik']);
		$('#data_nama_peringatan').html(callback['nama']);
		$('#data_peringatanasal_peringatan').html(callback['status_lama']);
		$('#data_peringatanbaru_peringatan').html(callback['status_baru']);
		$('#data_mengetahui_peringatan').html(callback['mengetahui']);
		$('#data_menyetujui_peringatan').html(callback['menyetujui']);
		$('#data_dibuat_peringatan').html(callback['dibuat']);
		$('#data_pelanggaran_peringatan').html(callback['pelanggaran']);
		$('#data_keterangan_peringatan').html(callback['keterangan']);
		$('#data_besaran_denda_peringatan').html(callback['besaran_denda']);
		$('#data_angsuran_peringatan').html(callback['jumlah_angsuran']+' Kali');
		var denda = callback['denda'];
		if(denda==1){
			var dendaval = '<b class="text-success">Ada Denda</b>';
			$('#besaran_denda_v').show();
			$('#angsuran_denda').show();
		}else{
			var dendaval = '<b class="text-danger">Tidak Ada Denda</b>';
			$('#besaran_denda_v').hide();
			$('#angsuran_denda').hide();
		}
		$('#data_denda_peringatan').html(dendaval);
		var status = callback['status'];
		if(status==1){
			var statusval = '<b class="text-success">Aktif</b>';
		}else{
			var statusval = '<b class="text-danger">Tidak Aktif</b>';
		}
		$('#data_status_peringatan').html(statusval);
		$('#data_create_date_peringatan').html(callback['create_date']+' WIB');
		$('#data_update_date_peringatan').html(callback['update_date']+' WIB');
		$('input[name="data_id_peringatan"]').val(callback['id']);
		$('#data_create_by_peringatan').html(callback['nama_buat']);
		$('#data_update_by_peringatan').html(callback['nama_update']);
  	}
</script>