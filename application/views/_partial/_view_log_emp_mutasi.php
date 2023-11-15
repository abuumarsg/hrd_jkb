<div class="row">
  <div class="col-md-12">
    <h3> History Mutasi/Promosi/Demosi Karyawan </h3>
    <table id="table_mutasi" class="table table-bordered table-striped table-responsive" width="100%">
      <thead>
        <tr>
          <th>No.</th>
          <th>Nama</th>
          <th>NO SK</th>
          <th>Tanggal SK</th>
          <th>Status</th>
          <th>Jabatan Baru</th>
          <th>Lokasi Baru</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
      </tbody>
    </table>
  </div>
</div>
<div id="view_mutasi" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h2 class="modal-title">Detail Data <b class="text-muted header_data"></b></h2>
				<input type="hidden" name="data_id_view">
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Nomor SK</label>
							<div class="col-md-6" id="data_nosk_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Tanggal SK</label>
							<div class="col-md-6" id="data_tglsk_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Tanggal Berlaku</label>
							<div class="col-md-6" id="data_tglberlaku_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">NIK</label>
							<div class="col-md-6" id="data_nik_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Nama</label>
							<div class="col-md-6" id="data_nama_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Status Mutasi</label>
							<div class="col-md-6" id="data_statusmutasi_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Masa Percobaan</label>
							<div class="col-md-6" id="data_percobaan_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Mengetahui</label>
							<div class="col-md-6" id="data_mengetahui_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Menyetujui</label>
							<div class="col-md-6" id="data_menyetujui_view"></div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Status</label>
							<div class="col-md-6" id="data_status_view">

							</div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Dibuat Tanggal</label>
							<div class="col-md-6" id="data_create_date_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Diupdate Tanggal</label>
							<div class="col-md-6" id="data_update_date_view"></div>
						</div>
						<!-- <div class="form-group col-md-12">
							<label class="col-md-6 control-label">Dibuat Oleh</label>
							<div class="col-md-6" id="data_create_by_view">
							</div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Diupdate Oleh</label>
							<div class="col-md-6" id="data_update_by_view">
							</div>
						</div> -->
					</div>
				</div>
				<div class="row">
					<div class="form-group col-md-12">
						<div class="form-group col-md-12">
							<label class="col-md-3 control-label">Keterangan (Target)</label>
							<div class="col-md-9" id="data_keterangan_view"></div>
						</div>
					</div>
				</div>
				<hr>
				<div class="row">
					<div class="col-md-6">
						<div class="panel panel-danger">
							<div class="panel-heading bg-red"><b>Status Lama</b></div>
							<div class="panel-body">
								<div class="form-group col-md-12">
									<label class="col-md-6 control-label">Jabatan Lama</label>
									<div class="col-md-6" id="data_jabatanlama_view"></div>
								</div>
								<div class="form-group col-md-12">
									<label class="col-md-6 control-label">Lokasi Lama</label>
									<div class="col-md-6" id="data_lokasiasal_view"></div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="panel panel-success">
							<div class="panel-heading bg-green"><b>Status Baru</b></div>
							<div class="panel-body">
								<div class="form-group col-md-12">
									<label class="col-md-6 control-label">Jabatan Baru</label>
									<div class="col-md-6" id="data_jabatanbaru_view"></div>
								</div>
								<div class="form-group col-md-12">
									<label class="col-md-6 control-label">Lokasi Baru</label>
									<div class="col-md-6" id="data_lokasibaru_view"></div>
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
    form_property();all_property();set_interval();reset_interval();log_mutasi();
  })
  function log_mutasi(){
    $('#table_mutasi').DataTable().destroy();
    $('#table_mutasi').DataTable( {
    ajax: {
				url: "<?php echo base_url('kemp/log_mutasi_jabatan/view_all/'.$this->codegenerator->encryptChar($profile['nik']))?>",
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
					width: '10%',
					render: function (data, type, full, meta) {
						return '<center>' + data + '</center>';
					}
				},
			]
    });
  }
  function view_modal_mutasi(id) {
		var data = {
			id_mutasi: id
		};
		var callback = getAjaxData("<?php echo base_url('kemp/log_mutasi_jabatan/view_one')?>", data);
		$('#view_mutasi').modal('show');
		$('.header_data').html(callback['no_sk']);
		$('#data_nosk_view').html(callback['no_sk']);
		$('#data_tglsk_view').html(callback['tgl_sk']);
		$('#data_tglberlaku_view').html(callback['tgl_berlaku']);
		$('#data_nik_view').html(callback['nik']);
		$('#data_nama_view').html(callback['nama']);
		$('#data_lokasiasal_view').html(callback['lokasi_asal']);
		$('#data_lokasibaru_view').html(callback['vlokasi_baru']);
		$('#data_statusmutasi_view').html(callback['vstatus_mutasi']);
		$('#data_jabatanlama_view').html(callback['jabatan_lama']);
		$('#data_jabatanbaru_view').html(callback['vjabatan_baru']);
		$('#data_mengetahui_view').html(callback['vmengetahui']);
		$('#data_percobaan_view').html(callback['vpercobaan']);
		$('#data_menyetujui_view').html(callback['vmenyetujui']);
		$('#data_keterangan_view').html(callback['vketerangan']);
		var status = callback['status'];
		if (status == 1) {
			var statusval = '<b class="text-success">Aktif</b>';
		} else {
			var statusval = '<b class="text-danger">Tidak Aktif</b>';
		}
		$('#data_status_view').html(statusval);
		$('#data_create_date_view').html(callback['create_date'] + ' WIB');
		$('#data_update_date_view').html(callback['update_date'] + ' WIB');
		$('input[name="data_id_view"]').val(callback['id']);
		$('#data_create_by_view').html(callback['nama_buat']);
		$('#data_update_by_view').html(callback['nama_update']);
  }
</script>