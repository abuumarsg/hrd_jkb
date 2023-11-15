<div class="row">
	<div class="col-md-12">
		<div class="box box-success">
			<div class="box-header with-border">
				<h3 class="box-title"><i class="fa fa-list"></i> Daftar Data Denda</h3>
				<div class="box-tools pull-right">
					<button class="btn btn-box-tool" onclick="reload_table('table_data')" data-toggle="tooltip"
						title="Refresh Table"><i class="fas fa-sync"></i></button>
					<button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i
							class="fa fa-minus"></i></button>
					<button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i
							class="fa fa-times"></i></button>
				</div>
			</div>
			<div class="box-body">
				<div class="row">
					<div class="col-md-12">
						<div class="row">
							<div class="col-md-12">
								<div class="pull-left">
									<?php if (in_array($access['l_ac']['add'], $access['access'])) {
												echo '<button class="btn btn-success" id="btn_tambah" type="button" data-toggle="collapse" data-target="#add" aria-expanded="false" aria-controls="import"><i class="fa fa-plus"></i> Tambah Angsuran Denda</button>';
											}?>
								</div>
								<!-- <div class="pull-right" style="font-size: 8pt;">
									<i class="fa fa-toggle-on stat scc"></i> Aktif<br>
									<i class="fa fa-toggle-off stat err"></i> Tidak Aktif
								</div> -->
							</div>
						</div>
						<?php if(in_array($access['l_ac']['add'], $access['access'])){?>
						<div class="collapse" id="add">
							<input type="hidden" id="key_btn_tambah" value="0">
							<br>
							<div class="col-md-2"></div>
							<div class="col-md-8">
								<form id="form_add">
									<input type="hidden" id="id_karyawan" name="id_karyawan">
									<div class="col-md-12">
										<div class="form-group">
											<label>Kode Angsuran Denda</label>
											<input type="text" placeholder="Masukkan Kode Angsuran Denda" id="data_kode_add" name="kode" class="form-control" required="required" value="" readonly="readonly">
										</div>
									</div>
									<div class="col-md-11">
										<div class="form-group">
											<label>Kode Denda</label>
												<input type="text" name="kode_denda" id="kode_denda" class="form-control" placeholder="Kode Denda Karyawan" required="required" readonly="readonly">
										</div>
									</div>
									<div class="col-md-1">
										<div class="form-group">
											<button type="button" class="btn btn-default btn-sm" onclick="pilih_denda()" style="margin-top: 28px"> <i class="fa fa-plus"></i></button>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label>Nama Karyawan</label>
											<input type="text" placeholder="Nama" id="data_nama_add" name="nama" class="form-control field" required="required" readonly="readonly">
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label>NIK Karyawan</label>
											<input type="text" placeholder="NIK" id="data_nik_add" name="nik" class="form-control field" required="required" readonly="readonly">
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label>Besar Angsuran</label>
											<input type="text" placeholder="Besar Angsuran" id="data_besar_angsuran_add" name="besar_angsuran" class="form-control field" required="required" readonly="readonly">
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label>Diangsur</label>
											<input type="text" placeholder="Diangsur" id="data_diangsur_add" name="diangsur" class="form-control field" required="required" readonly="readonly">
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label>Total Denda</label>
											<input type="text" placeholder="Total Denda" id="data_total_add" name="total" class="form-control field" required="required" readonly="readonly">
										</div>
									</div>
									<div class="col-md-12">
										<div class="form-group">
											<label>Status Denda</label>
											<input type="text" placeholder="Status Denda" id="data_status_add" name="status" class="form-control field" required="required" readonly="readonly">
										</div>
									</div>
									<div class="col-md-12">
										<div class="form-group">
											<label>Tanggal Denda</label>
											<input type="text" placeholder="Tanggal Denda" id="data_tgl_add" name="tgl" class="form-control field" required="required" readonly="readonly">
										</div>
									</div>
									<div class="col-md-12">
										<div class="form-group">
											<label>Keterangan</label>
											<textarea name="keterangan" id="data_keterangan_add" class="form-control field" placeholder="Keterangan"></textarea>
										</div>
									</div>
									<div class="col-md-12">
										<div class="form-group">
											<label>Periode Penggajian</label>
											<select class="form-control select2" name="periode" id="data_periode_add"
												style="width: 100%;">
												<?php
													// $periode = $this->model_master->getListPeriodePenggajian();
													// echo '<option></option>';
													// foreach ($periode as $p) {
													// 	echo '<option value="'.$p->kode_periode_penggajian.'">'.$p->nama.' ('.$p->nama_sistem_penggajian.')</option>';
													// }
													?>
											</select>
										</div>
									</div>
									<div class="col-md-12">
										<div class="form-group">
											<button type="button" onclick="do_add()" id="btn_add" class="btn btn-success"><i
													class="fa fa-floppy-o"></i> Simpan</button>
										</div>
									</div>
								</form>
							</div>
						</div>
						<?php } ?>
					</div>
				</div>
				<br>
				<div class="row">
					<div class="col-md-12">
						<!-- Data Begin Here -->
						<table id="table_data" class="table table-bordered table-striped table-responsive" width="100%">
							<thead>
								<tr>
									<th>No.</th>
									<th>Kode Angsuran</th>
									<th>Kode Denda</th>
									<th>Karyawan</th>
									<th>Angsuran Ke</th>
									<th>Saldo Denda</th>
									<th>Status Denda</th>
									<th>Periode Penggajian</th>
									<th>Tanggal</th>
									<!-- <th>Status</th> -->
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
	</div>
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
							<label class="col-md-6 control-label">Kode Denda</label>
							<div class="col-md-6" id="data_kode_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Nama Karyawan</label>
							<div class="col-md-6" id="data_karyawan_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Jabatan</label>
							<div class="col-md-6" id="data_jabatan_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Bagian</label>
							<div class="col-md-6" id="data_bagian_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Lokasi Kerja</label>
							<div class="col-md-6" id="data_loker_view"></div>
						</div>
						<!-- <div class="form-group col-md-12">
							<label class="col-md-6 control-label">Nominal</label>
							<div class="col-md-6" id="data_nominal_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Angsuran Ke</label>
							<div class="col-md-6" id="data_angsuran_ke_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Periode Penggajian</label>
							<div class="col-md-6" id="data_periode_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Keterangan</label>
							<div class="col-md-6" id="data_keterangan_view"></div>
						</div> -->
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
					<div class="col-md-12">
						<div id="data_tabel_view"></div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<?php if (in_array($access['l_ac']['edt'], $access['access'])) {
					// echo '<button type="submit" class="btn btn-info" onclick="edit_modal()"><i class="fa fa-edit"></i> Edit</button>';
				}?>
				<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
			</div>
		</div>
	</div>
</div>

<!-- edit -->
<div id="edit" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h2 class="modal-title">Edit Data <b class="text-muted header_data"></b></h2>
			</div>
			<form id="form_edit">
				<div class="modal-body">
					<input type="hidden" id="data_id_edit" name="id" value="">
					<input type="hidden" id="data_kode_edit_old" name="kode_old" value="">
					<div class="form-group">
						<label>Kode Angsuran</label>
						<input type="text" placeholder="Masukkan Kode Angsuran" id="data_kode_edit" name="kode_angsuran"
							class="form-control" required="required" value="" readonly="readonly">
					</div>
					<div class="form-group">
						<label>Nama Karyawan</label>
						<input type="text" placeholder="Masukkan Nama Karyawan" id="data_name_edit" name="nama"
							class="form-control field" required="required" readonly="readonly">
					</div>
					<div class="form-group">
						<label>Besar Angsuran</label>
						<input type="text" placeholder="Masukkan Nominal Tunjangan" id="data_besar_angsuran_edit"
							name="nominal" class="form-control field input-money" readonly="readonly"
							required="required">
					</div>
					<div class="form-group">
						<label>Angsuran Ke</label>
						<input type="text" placeholder="Angsuran Ke" id="data_angsuran_ke_edit" name="angsuran_ke"
							class="form-control field input-number" required="required" readonly="readonly">
					</div>
					<div class="form-group">
						<label>Tanggal Angsuran</label>
						<input type="text" placeholder="Angsuran Ke" id="data_tgl_angsuran_edit" name="angsuran_ke"
							class="form-control field input-number" required="required" readonly="readonly">
					</div>
					<div class="form-group">
						<label>Saldo Denda</label>
						<input type="text" placeholder="Angsuran Ke" id="data_saldo_denda_edit" name="angsuran_ke"
							class="form-control field input-number" required="required" readonly="readonly">
					</div>
					<div class="form-group">
						<label>Total Denda</label>
						<input type="text" placeholder="Angsuran Ke" id="data_total_denda_edit" name="angsuran_ke"
							class="form-control field input-number" required="required" readonly="readonly">
					</div>
					<div class="form-group">
						<label>Status Denda</label>
						<input type="text" placeholder="Angsuran Ke" id="data_status_denda_edit" name="angsuran_ke"
							class="form-control field input-number" required="required" readonly="readonly">
					</div>
					<div class="form-group">
						<label>Keterangan</label>
						<textarea name="keterangan" id="data_keterangan_edit" class="form-control field"
							placeholder="Keterangan" readonly="readonly"></textarea>
					</div>
					<div class="form-group">
						<label>Periode Penggajian</label>
						<select class="form-control select2" name="periode" id="data_periode_edit" style="width: 100%;">
							<?php
							// $periode = $this->model_master->getListPeriodePenggajian();
							// echo '<option></option>';
							// foreach ($periode as $p) {
							// 	echo '<option value="'.$p->kode_periode_penggajian.'">'.$p->nama.' ('.$p->nama_sistem_penggajian.')</option>';
							// }
							?>
						</select>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" onclick="do_edit()" id="btn_edit" class="btn btn-success"><i
							class="fa fa-floppy-o"></i> Simpan</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
				</div>
			</form>
		</div>
	</div>
</div>

<div class="modal modal-default fade" id="modal_pilih_denda" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal">&times;</button>
		        <h2 class="modal-title">Pilih Denda</h2>
		    </div>
			<div class="modal-body">
					<table id="table_pilih_denda" class="table table-bordered table-striped table-responsive" width="100%">
						<thead>
							<tr>
								<th width="7%">NO</th>
								<th width="7%">NO Denda</th>
								<th width="25%">NIK</th>
								<th width="25%">Nama Karyawan</th>
								<th width="25%">Jabatan</th>
								<th>Jenis</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default pull-right" data-dismiss="modal">Kembali</button>
			</div>
		</div>
	</div>
</div>
<!-- delete -->
<div id="modal_delete_partial"></div>
<script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
<script type="text/javascript">
	/*wajib diisi*/
	var url_select = "<?php echo base_url('global_control/select2_global');?>";
	var table = "data_denda_angsuran";
	var column = "kode_denda";
	$(document).ready(function () {
		$('#table_data').DataTable({
			ajax: {
				url: "<?php echo base_url('cpayroll/data_denda/view_all/')?>",
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
					width: '10%',
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
				/*aksi*/
				{
					targets: 8,
					width: '10%',
					render: function (data, type, full, meta) {
						return '<center>' + data + '</center>';
					}
				},
			]
		});
		select_data('data_karyawan_add', url_select, 'karyawan', 'id_karyawan', 'nama');
		$('#data_karyawan_add').val([]).trigger('change');
		$('#btn_tambah').click(function () {
			refreshCode();
		})
	});

	function refreshCode() {
		kode_generator("<?php echo base_url('cpayroll/data_denda/kode');?>", 'data_kode_add');
	}
	function pilih_denda() {
		$('#modal_pilih_denda').modal('toggle');
		$('#modal_pilih_denda .header_data').html('Pilih Denda');
		$('#table_pilih_denda').DataTable( {
			ajax: "<?php echo base_url('cpayroll/pilih_denda')?>",
			scrollX: true,
			destroy: true,
			columnDefs: [
			{   targets: 0, 
				width: '10%',
				render: function ( data, type, full, meta ) {
					return '<center>'+(meta.row+1)+'.</center>';
				}
			},
			{   targets: 5,
				render: function ( data, type, full, meta ) {
					return data;
				}
			},
			]
		});
	}
	$(document).on('click','.pilih',function(){
		document.getElementById("id_karyawan").value = $(this).attr('data-id_karyawan');
		document.getElementById("data_nik_add").value = $(this).attr('data-nik');
		document.getElementById("data_nama_add").value = $(this).attr('data-nama');
		document.getElementById("kode_denda").value = $(this).attr('data-kode');
		document.getElementById("data_tgl_add").value = $(this).attr('data-tgl_denda');
		document.getElementById("data_total_add").value = $(this).attr('data-total_denda');
		document.getElementById("data_diangsur_add").value = $(this).attr('data-diangsur');
		document.getElementById("data_besar_angsuran_add").value = $(this).attr('data-besar_angsuran');
		document.getElementById("data_status_add").value = $(this).attr('data-status_denda');
		document.getElementById("data_keterangan_add").value = $(this).attr('data-keterangan');
		getPeriodeFromKar('add',$(this).attr('data-id_karyawan'));
		$('#modal_pilih_denda').modal('hide');
	});
	function view_modal(id) {
		if ($('#key_btn_tambah').val() == 1) {
			$('#btn_tambah').click();
		}
		var data = {
			kode_denda: id,
			mode: 'view'
		};
		var callback = getAjaxData("<?php echo base_url('cpayroll/data_denda/view_one')?>", data);
		$('#view').modal('show');
		$('.header_data').html(callback['kode_denda']);
		$('#data_kode_view').html(callback['kode_denda']);
		$('#data_name_view').html(callback['nama']);
		$('#data_nominal_view').html(callback['nominal']);
		$('#data_angsuran_ke_view').html(callback['angsuran_ke']);
		$('#data_keterangan_view').html(callback['keterangan']);
		$('#data_karyawan_view').html(callback['karyawan']);
		$('#data_jabatan_view').html(callback['jabatan']);
		$('#data_bagian_view').html(callback['bagian']);
		$('#data_loker_view').html(callback['loker']);
		$('#data_periode_view').html(callback['periode']);
      	$('#data_tabel_view').html(callback['tabel']);

		var status = callback['status'];
		if (status == 1) {
			var statusval = '<b class="text-success">Aktif</b>';
		} else {
			var statusval = '<b class="text-danger">Tidak Aktif</b>';
		}
		$('#data_status_view').html(statusval);
		$('#data_create_date_view').html(callback['create_date'] + ' WIB');
		$('#data_update_date_view').html(callback['update_date'] + ' WIB');
		$('input[name="data_id_view"]').val(callback['kode_denda']);
		$('#data_create_by_view').html(callback['nama_buat']);
		$('#data_update_by_view').html(callback['nama_update']);
	}

	function edit_modal(id) {
		var data = { 
			kode_angsuran: id,
			mode: 'view'
		};
		var callback = getAjaxData("<?php echo base_url('cpayroll/data_denda/view_one_angsuran')?>", data);
		$('#view').modal('toggle');
		setTimeout(function () {
			$('#edit').modal('show');
		}, 600);
		$('.header_data').html(callback['nama']);
		$('#data_id_edit').val(callback['id']);
		$('#data_kode_edit_old').val(callback['kode']);
		$('#data_kode_edit').val(callback['kode']);
		$('#data_name_edit').val(callback['nama']);
		$('#data_besar_angsuran_edit').val(callback['besar_angsuran']);
		$('#data_angsuran_ke_edit').val(callback['angsuran_ke']);
		$('#data_tgl_angsuran_edit').val(callback['tgl_angsuran']);
		$('#data_saldo_denda_edit').val(callback['saldo_denda']);
		$('#data_total_denda_edit').val(callback['total_denda']);
		$('#data_status_denda_edit').val(callback['status_denda']);
		$('#data_keterangan_edit').val(callback['keterangan']);
		// $('#data_periode_edit').val(callback['periode_e']).trigger('change');
		$('#data_periode_edit').html(callback['periode_e']);

		select_data('data_karyawan_edit', url_select, 'karyawan', 'id_karyawan', 'nama');
		$('#data_karyawan_edit').val(callback['karyawan']).trigger('change');
	}
	function delete_modal_u(id) {
		var data = {
			kode_angsuran: id,
			mode: 'view'
		};
		var callback = getAjaxData("<?php echo base_url('cpayroll/data_denda/view_one_angsuran')?>", data);
		var datax = {
			table: table,
			column: 'kode_angsuran',
			id: id,
			nama: callback['nama']
		};
		loadModalAjax("<?php echo base_url('pages/load_modal_delete')?>", 'modal_delete_partial', datax, 'delete');
	}
	function delete_modal(id) {
		var data = {
			kode_denda: id,
			mode: 'view'
		};
		var callback = getAjaxData("<?php echo base_url('cpayroll/data_denda/view_one')?>", data);
		var datax = {
			table: table,
			column: column,
			id: id,
			nama: callback['nama']
		};
		loadModalAjax("<?php echo base_url('pages/load_modal_delete')?>", 'modal_delete_partial', datax, 'delete');
	}
	/*doing db transaction*/
	function do_status(id, data) {
		var data_table = {
			status: data
		};
		var where = {
			id_pinjaman: id
		};
		var datax = {
			table: table,
			where: where,
			data: data_table
		};
		submitAjax("<?php echo base_url('global_control/change_status')?>", null, datax, null, null, 'status');
		$('#table_data').DataTable().ajax.reload();
	}

	function do_edit() {
		if ($("#form_edit")[0].checkValidity()) {
			submitAjax("<?php echo base_url('cpayroll/edit_data_denda')?>", 'edit', 'form_edit', null, null);
			$('#table_data').DataTable().ajax.reload();
		} else {
			notValidParamx();
		}
	}
	function getPeriodeFromKar(usage,value) {
		var data={idkar:value};
		var callback=getAjaxData("<?php echo base_url('master/master_pinjaman/periodeKar')?>",data);
		$('#data_periode_'+usage).html(callback['select']);
	}
	function do_add() {
		if ($("#form_add")[0].checkValidity()) {
			submitAjax("<?php echo base_url('cpayroll/add_angsuran_denda')?>", null, 'form_add', "<?php echo base_url('cpayroll/data_denda/kode');?>", 'data_kode_add');
			$('#table_data').DataTable().ajax.reload(function () {
				Pace.restart();
			});
			$('#form_add')[0].reset();
			$('#data_karyawan_add').val('').trigger('change');
			$('#data_periode_add').val('').trigger('change');
			refreshCode();
		} else {
			notValidParamx();
		}
	}

</script>
