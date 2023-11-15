<div class="row">
	<div class="col-md-12">
		<h3> History Perjanjian Kerja Karyawan </h3>
		<table id="table_perjanjian" class="table table-bordered table-striped table-responsive" width="100%">
			<thead>
				<tr>
					<th>No.</th>
					<th>Nama</th>
					<th>NO SK</th>
					<th>Tanggal SK</th>
					<th>Perjanjian</th>
					<th>Tanggal Berlaku</th>
					<th>Berlaku Sampai</th>
					<th>Aksi</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
	</div>
</div>
<div id="view_perjanjian" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h2 class="modal-title">Detail Data <b class="text-muted header_data"></b></h2>
				<input type="hidden" name="data_id_perjanjian">
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">NIK</label>
							<div class="col-md-6" id="data_nik_perjanjian"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Nama</label>
							<div class="col-md-6" id="data_nama_perjanjian"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Status Karyawan</label>
							<div class="col-md-6" id="data_status_karyawan_perjanjian"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Perjanjian Baru</label>
							<div class="col-md-6" id="data_perjanjian3_perjanjian"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Mengetahui</label>
							<div class="col-md-6" id="data_mengetahui_perjanjian"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Menyetujui</label>
							<div class="col-md-6" id="data_menyetujui_perjanjian"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Keterangan</label>
							<div class="col-md-6" id="data_keterangan_perjanjian"></div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Status</label>
							<div class="col-md-6" id="data_status_perjanjian"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Dibuat Tanggal</label>
							<div class="col-md-6" id="data_create_date_perjanjian"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Diupdate Tanggal</label>
							<div class="col-md-6" id="data_update_date_perjanjian"></div>
						</div>
					</div>
				</div>
				<hr>
				<div class="row">
					<div class="col-md-12">
						<div class="panel panel-success">
							<div class="panel-heading bg-green">
								<h4>Data Perjanjian Kerja</h4>
							</div>
							<div class="panel-body">
								<div class="form-group col-md-12">
									<label class="col-md-6 control-label">Nomor SK Baru</label>
									<div class="col-md-6" id="data_nosk2_perjanjian"></div>
								</div>
								<div class="form-group col-md-12">
									<label class="col-md-6 control-label">Perjanjian Baru</label>
									<div class="col-md-6" id="data_perjanjian2_perjanjian"></div>
								</div>
								<div class="form-group col-md-12">
									<label class="col-md-6 control-label">Tanggal SK Baru</label>
									<div class="col-md-6" id="data_tglsk2_perjanjian"></div>
								</div>
								<div class="form-group col-md-12">
									<label class="col-md-6 control-label">Tanggal Berlaku Baru</label>
									<div class="col-md-6" id="data_tglberlaku2_perjanjian"></div>
								</div>
								<div class="form-group col-md-12">
									<label class="col-md-6 control-label">Berlaku Sampai Tanggal</label>
									<div class="col-md-6" id="data_berlakusampai2_perjanjian"></div>
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
  	function log_perjanjian(){
		$('#table_perjanjian').DataTable().destroy();
		$('#table_perjanjian').DataTable( {
		ajax: {
				url: "<?php echo base_url('kemp/log_perjanjian_kerja/view_all/'.$this->codegenerator->encryptChar($profile['nik']))?>",
				type: 'POST',
				data: {
		
				}
			},
			scrollX: true,
			columnDefs: [{
					targets: 0,
					width: '5%',
					render: function (data, type, full, meta) {
						return '<center>' + (meta.row + 1) + '.</center>';
					}
				},
				{
					targets: 1,
					width: '15%',
					render: function (data, type, full, meta) {
						return data;
					}
				},
				{
					targets: 7,
					width: '5%',
					render: function (data, type, full, meta) {
						return '<center>' + data + '</center>';
					}
				},
			]
		});
  	}
  	function view_modal_perjanjian(id) {
		var data={id_p_kerja:id};
		var callback=getAjaxData("<?php echo base_url('kemp/log_perjanjian_kerja/view_one')?>",data);  
		$('#view_perjanjian').modal('show');
		$('.header_data').html(callback['no_sk']);
		$('#data_nik_perjanjian').html(callback['nik']);
		$('#data_nama_perjanjian').html(callback['nama']);
		$('#data_status_karyawan_perjanjian').html(callback['status_karyawan']);
		$('#data_perjanjian1_perjanjian').html(callback['status_lama']);
		$('#data_nosk1_perjanjian').html(callback['no_sk_lama']);
		$('#data_tglsk1_perjanjian').html(callback['tgl_sk_lama']);
		$('#data_tglberlaku1_perjanjian').html(callback['tgl_berlaku_lama']);
		$('#data_berlakusampai1_perjanjian').html(callback['berlaku_sampai_lama']);
		$('#data_perjanjian2_perjanjian').html(callback['status_baru']);
		$('#data_perjanjian3_perjanjian').html(callback['status_baru']);
		$('#data_nosk2_perjanjian').html(callback['no_sk_baru']);
		$('#data_tglsk2_perjanjian').html(callback['tgl_sk_baru']);
		$('#data_tglberlaku2_perjanjian').html(callback['tgl_berlaku_baru']);
		$('#data_berlakusampai2_perjanjian').html(callback['berlaku_sampai_baru']);
		$('#data_mengetahui_perjanjian').html(callback['mengetahuiv']);
		$('#data_menyetujui_perjanjian').html(callback['menyetujuiv']);
		$('#data_keterangan_perjanjian').html(callback['keterangan']);
		var status = callback['status'];
		if(status==1){
			var statusval = '<b class="text-success">Aktif</b>';
		}else{
			var statusval = '<b class="text-danger">Tidak Aktif</b>';
		}
		$('#data_status_perjanjian').html(statusval);
		$('#data_create_date_perjanjian').html(callback['create_date']+' WIB');
		$('#data_update_date_perjanjian').html(callback['update_date']+' WIB');
		$('input[name="data_id_perjanjian"]').val(callback['id']);
		$('#data_create_by_perjanjian').html(callback['nama_buat']);
		$('#data_update_by_perjanjian').html(callback['nama_update']);
 	}
</script>