<?php
// $foto = $this->otherfunctions->getFotoValue($non['foto'],$non['kelamin']);
// $color = $this->otherfunctions->getSkinColorText($adm['skin']);
?>
<style type="text/css">
	.wordwrap {
		white-space: pre-wrap;
		white-space: -moz-pre-wrap;
		white-space: -pre-wrap;
		white-space: -o-pre-wrap;
		word-wrap: break-word;
	}
</style>
<div class="content-wrapper">
	<section class="content-header">
		<h1>
			<a class="back" onclick="window.history.back()"><i class="fa fa-chevron-circle-left "></i></a>
			<i class="fas fa-indent"></i> Transaksi Non Karyawan
			<small><?php echo $non['nama_non'];?></small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fa fas fa-tachometer-alt"></i>
					Dashboard</a></li>
			<li><a href="<?php echo base_url('pages/transaksi_non_karyawan');?>"><i class="fas fa-indent"></i> Transaksi Non Karyawan</a>
			</li>
			<li class="active">Profile <?php echo $non['nama_non'];?></li>
		</ol>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-md-3">
				<div class="box box-success">
					<div class="box-body box-profile">
						<img class="profile-user-img img-responsive img-circle view_photo" width="100px"
							data-source-photo="<?php echo base_url($foto); ?>" src="<?php echo base_url($foto); ?>"
							alt="User profile picture">
						<h3 class="profile-username text-center"><?php echo $non['nama_non']; ?></h3>
						<ul class="list-group list-group-unbordered">
							<!-- 
							<li class="list-group-item">
								<b>Lokasi Kerja</b><?php
								// if ($non['nama_loker'] == "") {
								// 	echo '<label class="pull-right label label-danger text-center">Tidak Punya Lokasi Kerja</label>';
								// }else{
								// 	echo '<label class="pull-right label label-success">'.$non['nama_loker'].'</label>';
								// }
								?>
							</li> -->
						</ul>
					</div>
				</div>
			</div>
			<div class="col-md-9">
				<div class="box box-primary">
					<div class="nav-tabs-custom">
						<ul class="nav nav-tabs">
							<li><a href="#info" data-toggle="tab"><i class="fa fa-user"></i> Informasi Umum</a></li>
						</ul>
						<div class="tab-content">
							<div class="tab-pane active" id="info" style="overflow:auto;">
								<table class='table table-bordered table-striped table-hover'>
									<tr>
										<th>NIK</th>
										<td><?php echo ucwords($non['nik']);?></td>
									</tr>
									<tr>
										<th>Nama Non Karyawan / Instansi</th>
										<td><?php echo ucwords($non['nama_non']);?></td>
									</tr>
									<tr>
										<th>No Telpon</th>
										<td><?php if ($non['no_telp'] == "") {
												echo '<label class="label label-danger text-center">Tidak Ada Data</label>';
											}else{
												echo $non['no_telp'];
											}?>
										</td>
									</tr>
									<tr>
										<th>Alamat</th>
										<td><?php
											if ($non['alamat'] == NULL || $non['alamat'] == "") {
												echo '<label class="label label-danger">Alamat Belum Diinput</label>';
											}else{
          										echo ucwords($non['alamat']);
											}  ?>
										</td>
									</tr>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="box box-warning">
					<div class="box-header with-border">
						<h3 class="box-title"><i class="fas fa-indent"></i> Data Seluruh Transaksi Non Karyawan <small><?php echo $non['nama_non'];?></small></h3>
						<div class="box-tools pull-right">
							<button class="btn btn-box-tool" onclick="reload_table('table_data')" data-toggle="tooltip" title="Refresh Table"><i class="fas fa-sync"></i></button>
							<button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
							<button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i class="fa fa-times"></i></button>
						</div>
					</div>
					<div class="box-body">
						<div class="row">
							<div class="col-md-12">
								<div class="pull-left" style="font-size: 8pt;">
									<?php 
										echo form_open('rekap/data_transaksi_non_karyawan');
										if (in_array($access['l_ac']['rkp'], $access['access'])) {
											echo '<input type="hidden" name="mode" value="nik">
											<input type="hidden" name="nik" value="'.$non['nik'].'">';
											echo '<button type="submit" class="btn btn-warning" ><i class="fa fa-file-excel-o"></i> Export Data</button>';
										}
										echo form_close();
									?>
								</div>
								<div class="pull-right" style="font-size: 8pt;">
									<i class="fa fa-toggle-on stat scc"></i> Aktif<br>
									<i class="fa fa-toggle-off stat err"></i> Tidak Aktif
								</div>
							</div>
						</div>
						<table id="table_data" class="table table-bordered table-striped data-table" width="100%">
							<thead>
								<tr>
									<th>No.</th>
									<th>Nama</th>
									<th>Nomor</th>
									<th>Tanggal Transaksi</th>
									<th>Kegiatan</th>
									<th>Biaya</th>
									<th>Keterangan</th>
									<th>Tanggal</th>
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
<!-- view -->
<div id="view" class="modal fade" role="dialog">
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
							<label class="col-md-6 control-label">Nomor</label>
							<div class="col-md-6" id="data_nosk_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Tanggal</label>
							<div class="col-md-6" id="data_tglsk_view"></div>
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
							<label class="col-md-6 control-label">Kegiatan</label>
							<div class="col-md-6" id="data_kegiatan_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Biaya</label>
							<div class="col-md-6" id="data_biaya_view"></div>
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
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Dibuat Oleh</label>
							<div class="col-md-6" id="data_create_by_view">
							</div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Diupdate Oleh</label>
							<div class="col-md-6" id="data_update_by_view">
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="form-group col-md-12">
						<div class="form-group col-md-12">
							<label class="col-md-3 control-label">Keterangan</label>
							<div class="col-md-9" id="data_keterangan_view"></div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<?php if (in_array($access['l_ac']['edt'], $access['access'])) {
					echo '<button type="submit" class="btn btn-info" onclick="edit_modal()"><i class="fa fa-edit"></i> Edit</button>';
				}?>
				<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
			</div>
		</div>
	</div>
</div>
<!-- edit -->
<?php if (in_array($access['l_ac']['edt'], $access['access'])) {?>
<div id="edit" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h2 class="modal-title">Edit Data <b class="text-muted header_data"></b></h2>
			</div>
			<div class="modal-body">
				<form id="form_edit">
					<div class="row">
						<div class="col-md-6">
							<input type="hidden" id="data_id_edit" name="id" value="">
							<div class="form-group clearfix">
								<label>Nomor</label>
								<input type="text" placeholder="Masukkan NO SK" id="data_nosk_edit" name="no_sk"
									value="" class="form-control" required="required">
							</div>
							<div class="form-group clearfix">
								<label>Tanggal</label>
								<div>
									<div class="has-feedback">
										<span class="fa fa-calendar form-control-feedback"></span>
										<input type="text" id="data_tglsk_edit" value="" name="tanggal"
											class="form-control pull-right date-picker" placeholder="Tanggal Berlaku"
											readonly="readonly">
									</div>
								</div>
							</div>
							<div class="form-group clearfix">
								<label>NIK</label>
								<input type="text" placeholder="Masukkan Nama Bagian" id="data_nik_edit" name="nik"
									value="" class="form-control" required="required" disabled="disabled">
							</div>
							<div class="form-group clearfix">
								<label>Nama</label>
								<input type="text" placeholder="Masukkan Nama Bagian" id="data_nama_edit" name="nama"
									value="" class="form-control" required="required" disabled="disabled">
							</div>
							<div class="form-group clearfix">
								<label>Kegiatan</label>
								<input type="text" placeholder="Masukkan Kegiatan" id="data_kegiatan_edit" name="kegiatan"
									value="" class="form-control" required="required">
							</div>
							<div class="form-group clearfix">
								<label>Biaya</label>
								<input type="text" placeholder="Masukkan Biaya" id="data_biaya_edit" name="biaya"
									value="" class="form-control input-money" required="required">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group clearfix">
								<label>Mengetahui</label>
								<select class="form-control select2" name="mengetahui" id="data_mengetahui_edit"
									required="required" style="width: 100%;"></select>
							</div>
							<div class="form-group clearfix">
								<label>Menyetujui</label>
								<select class="form-control select2" name="menyetujui" id="data_menyetujui_edit"
									required="required" style="width: 100%;"></select>
							</div>
							<div class="form-group clearfix">
								<label>Keterangan</label>
								<textarea class="form-control" name="keterangan" id="data_keterangan_edit"
									placeholder="Target, indikator penilaian masa evaluasi"></textarea>
							</div>
						</div>
					</div>
				</div>
			<div class="modal-footer">
				<button type="submit" id="btn_edit" class="btn btn-success"><i class="fa fa-floppy-o"></i>
					Simpan</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
			</div>
			</form>
		</div>
	</div>
</div>
<?php } ?>
<!-- delete -->
<div id="modal_delete_partial"></div>
<script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
<script type="text/javascript">
	var url_select = "<?php echo base_url('global_control/select2_global');?>";
	var table = "transaksi_non_karyawan";
	var column = "id";
	$(document).ready(function () {
		submitForm('form_add');
		submitForm('form_edit');
		$('#table_data').DataTable({
			ajax: {
				url: "<?php echo base_url('employee/view_transaksi_non_karyawan/view_all/'.$this->uri->segment(3))?>",
				type: 'POST',
				data: {
					access: "<?php echo base64_encode(serialize($access));?>"
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
				{
					targets: 8,
					width: '7%',
					render: function (data, type, full, meta) {
						return '<center>' + data + '</center>';
					}
				},
				{
					targets: 9,
					width: '10%',
					render: function (data, type, full, meta) {
						return '<center>' + data + '</center>';
					}
				},
			]
		});
	});
	function view_modal(id) {
		var data = {
			id: id
		};
		var callback = getAjaxData("<?php echo base_url('employee/view_transaksi_non_karyawan/view_one')?>", data);
		$('#view').modal('show');
		$('.header_data').html(callback['no_sk']);
		$('#data_nosk_view').html(callback['no_sk']);
		$('#data_tglsk_view').html(callback['tgl_sk']);
		$('#data_nik_view').html(callback['nik']);
		$('#data_nama_view').html(callback['nama']);
		$('#data_mengetahui_view').html(callback['vmengetahui']);
		$('#data_menyetujui_view').html(callback['vmenyetujui']);
		$('#data_keterangan_view').html(callback['vketerangan']);
		$('#data_kegiatan_view').html(callback['kegiatan']);
		$('#data_biaya_view').html(callback['biaya']);
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
	function edit_modal() {
		getSelect2("<?php echo base_url('employee/view_mutasi_jabatan/employee')?>",
			'data_mengetahui_edit,#data_menyetujui_edit');
		unsetoption('data_jabatanbaru_edit', ['JBT01', 'JBT02', 'JBT03']);
		var id = $('input[name="data_id_view"]').val();
		var data = {
			id: id
		};
		var callback = getAjaxData("<?php echo base_url('employee/view_transaksi_non_karyawan/view_one')?>", data);
		$('#view').modal('toggle');
		setTimeout(function () {
			$('#edit').modal('show');
		}, 600);
		$('.header_data').html(callback['nama']);
		$('#data_id_edit').val(callback['id']);
		$('#data_nosk_edit').val(callback['no_sk']);
		$('#data_tglsk_edit').val(callback['tgl_sk_e']);
		$('#data_nik_edit').val(callback['nik']);
		$('#data_nama_edit').val(callback['nama']);
		$('#data_mengetahui_edit').val(callback['mengetahui']).trigger('change');
		$('#data_menyetujui_edit').val(callback['menyetujui']).trigger('change');
		$('#data_keterangan_edit').val(callback['keterangan']);
		$('#data_kegiatan_edit').val(callback['kegiatan']);
		$('#data_biaya_edit').val(callback['biaya']);
		// addValueEditor('data_keterangan_edit',callback['keterangan']);
	}
	function delete_modal(id) {
		var data = {
			id: id
		};
		var callback = getAjaxData("<?php echo base_url('employee/view_transaksi_non_karyawan/view_one')?>", data);
		var datax = {
			table: table,
			column: column,
			id: id,
			nama: callback['nama']
		};
		loadModalAjax("<?php echo base_url('pages/load_modal_delete')?>", 'modal_delete_partial', datax, 'delete');
	}
	function do_status(id, data) {
		var data_table = {
			status: data
		};
		var where = {
			id: id
		};
		var datax = {
			table: table,
			where: where,
			data: data_table
		};
		submitAjax("<?php echo base_url('global_control/change_status')?>", null, datax, null, null, 'status');
		$('#table_data').DataTable().ajax.reload();
	}
	function submitForm(form) {
		$('#' + form).validator().on('submit', function (e) {
			if (e.isDefaultPrevented()) {
				notValidParamx();
			} else {
				e.preventDefault();
				if (form == 'form_add') {
					do_add()
				} else {
					do_edit()
				}
			}
		})
	}
	function do_edit() {
		if ($("#form_edit")[0].checkValidity()) {
			submitAjax("<?php echo base_url('employee/edit_transaksi_non_karyawan')?>", 'edit', 'form_edit', null, null);
			$('#table_data').DataTable().ajax.reload();
		} else {
			notValidParamx();
		}
	}
	function do_print(id) {
		var nik = "<?php echo $this->uri->segment(3)?>";
		window.location.href = "<?php echo base_url('cetak_word/cetak_mutasi/')?>" + id + '/' + nik;
	}
</script>
