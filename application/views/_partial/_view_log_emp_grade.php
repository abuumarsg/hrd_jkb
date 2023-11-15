<div class="row">
	<div class="col-md-12">
		<h3> History Grade Karyawan </h3>
		<table id="table_grade" class="table table-bordered table-striped table-responsive" width="100%">
			<thead>
				<tr>
					<th>No.</th>
					<th>Nama</th>
					<th>NO SK</th>
					<th>Tanggal SK</th>
					<th>Grade</th>
					<th>Tanggal Berlaku</th>
					<th>Aksi</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
	</div>
</div>
<div id="view_grade" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h2 class="modal-title">Detail Data <b class="text-muted header_data"></b></h2>
				<input type="hidden" name="data_id_grade">
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">NIK</label>
							<div class="col-md-6" id="data_nik_grade"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Nama</label>
							<div class="col-md-6" id="data_nama_grade"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Nomor SK</label>
							<div class="col-md-6" id="data_nosk_grade"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Tanggal SK</label>
							<div class="col-md-6" id="data_tglsk_grade"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Tanggal Berlaku</label>
							<div class="col-md-6" id="data_tglberlaku_grade"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Mengetahui</label>
							<div class="col-md-6" id="data_mengetahui_grade"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Menyetujui</label>
							<div class="col-md-6" id="data_menyetujui_grade"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Keterangan</label>
							<div class="col-md-6" id="data_keterangan_grade"></div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Status</label>
							<div class="col-md-6" id="data_status_grade">

							</div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Dibuat Tanggal</label>
							<div class="col-md-6" id="data_create_date_grade"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Diupdate Tanggal</label>
							<div class="col-md-6" id="data_update_date_grade"></div>
						</div>
					</div>
				</div>
				<hr>
				<div class="row">
					<div class="col-md-6">
						<div class="panel panel-danger">
							<div class="panel-heading bg-red"><h4>Data Sebelumnya</h4></div>
							<div class="panel-body">
								<div class="form-group col-md-12">
									<label class="col-md-4 control-label">Grade Sebelumnya</label>
									<div class="col-md-8" id="data_gradeasal_grade"></div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="panel panel-success">
							<div class="panel-heading bg-green"><h4>Data Baru</h4></div>
							<div class="panel-body">
								<div class="form-group col-md-12">
									<label class="col-md-4 control-label">Grade Baru</label>
									<div class="col-md-8" id="data_gradebaru_grade"></div>
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
	function log_grade(){
		$('#table_grade').DataTable().destroy();
		$('#table_grade').DataTable( {
			ajax: {
				url: "<?php echo base_url('kemp/log_grade_karyawan/view_all/'.$this->codegenerator->encryptChar($profile['nik']))?>",
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
			{   targets: 6,
				width: '10%',
				render: function ( data, type, full, meta ) {
					return '<center>'+data+'</center>';
				}
			},
			]
		});
	}
  	function view_modal_grade(id) {
		var data={id_grade:id};
		var callback=getAjaxData("<?php echo base_url('kemp/log_grade_karyawan/view_one')?>",data);  
		$('#view_grade').modal('show');
		$('.header_data').html(callback['no_sk']);
		$('#data_nosk_grade').html(callback['no_sk']);
		$('#data_tglsk_grade').html(callback['tgl_sk']);
		$('#data_tglberlaku_grade').html(callback['tgl_berlaku']);
		$('#data_tglberlakusampai_grade').html(callback['berlaku_sampai']);
		$('#data_nik_grade').html(callback['nik']);
		$('#data_nama_grade').html(callback['nama']);
		$('#data_gradeasal_grade').html(callback['grade_lama']);
		$('#data_gradebaru_grade').html(callback['grade_baru']);
		$('#data_mengetahui_grade').html(callback['mengetahui']);
		$('#data_menyetujui_grade').html(callback['menyetujui']);
		$('#data_keterangan_grade').html(callback['keterangan']);
		var status = callback['status'];
		if(status==1){
			var statusval = '<b class="text-success">Aktif</b>';
		}else{
			var statusval = '<b class="text-danger">Tidak Aktif</b>';
		}
		$('#data_status_grade').html(statusval);
		$('#data_create_date_grade').html(callback['create_date']+' WIB');
		$('#data_update_date_grade').html(callback['update_date']+' WIB');
		$('input[name="data_id_grade"]').val(callback['id']);
		$('#data_create_by_grade').html(callback['nama_buat']);
		$('#data_update_by_grade').html(callback['nama_update']);
  	}
</script>