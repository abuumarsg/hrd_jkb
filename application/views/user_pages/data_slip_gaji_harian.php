<div class="content-wrapper">
	<section class="content-header">
		<h1>
			<i class="fa far fa-credit-card"></i> Penggajian Harian
			<small>Data penggajian Harian</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo base_url('kpages/dashboard'); ?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
			</li>
			<li class="active">Data Penggajian Harian</li>
		</ol>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title"><i class="fas fa-search fa-fw"></i> Filter Pencarian</h3>
						<div class="box-tools pull-right">
							<button class="btn btn-box-tool" onclick="refreshData()" data-toggle="tooltip" title="Refresh Data"><i class="fas fa-sync"></i></button>
							<button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
							<button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i class="fa fa-times"></i></button>
						</div>
					</div>
					<div class="box-body">
						<form id="adv_form_filter">
							<input type="hidden" name="usage" value="data">
							<input type="hidden" name="id" value="<?=$this->codegenerator->encryptChar($id_admin)?>">
							<div class="row">
								<div class="col-md-12">
									<div class="col-md-4">
										<div class="form-group">
											<label>Minggu</label>
											<?php
											$minggu_ser = $this->otherfunctions->listWeek();
											$selm_ser = array();
											$exm_ser = array('class' => 'form-control select2', 'id' => 'minggu_ser', 'style' => 'width:100%;', 'required' => 'required');
											echo form_dropdown('minggu', $minggu_ser, $selm_ser, $exm_ser);
											?>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label>Bulan</label>
											<?php
											$bulan_ser = $this->formatter->getMonth();
											$sel_ser = array(date('m'));
											$ex_ser = array('class' => 'form-control select2', 'id' => 'bulan_ser', 'style' => 'width:100%;', 'required' => 'required');
											echo form_dropdown('bulan', $bulan_ser, $sel_ser, $ex_ser);
											?>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label>Tahun</label>
											<?php
											$tahun_ser = $this->formatter->getYear();
											$sels_ser = array(date('Y'));
											$exs_ser = array('class' => 'form-control select2', 'id' => 'tahun_ser', 'style' => 'width:100%;', 'required' => 'required');
											echo form_dropdown('tahun', $tahun_ser, $sels_ser, $exs_ser);
											?>
										</div>
									</div>
								</div>
							</div>
						</form>
					</div>
					<div class="box-footer">
						<div class="col-md-12">
							<div class="pull-right">
								<button type="button" onclick="table_data('search')" class="btn btn-success"><i class="fa fa-eye"></i> Lihat Data</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="box box-success">
					<div class="box-header with-border">
						<h3 class="box-title"><i class="fa fa-list"></i> Daftar Data Penggajian Harian</h3>
						<div class="box-tools pull-right">
							<button class="btn btn-box-tool" onclick="table_data('all')" data-toggle="tooltip" title="Refresh Table"><i class="fas fa-sync"></i></button>
							<button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
							<button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i class="fa fa-times"></i></button>
						</div>
					</div>
					<div class="box-body">
						<div class="row">
							<div class="col-md-12">
								<div id="accordion">
									<div class="panel">
										<div class="row">
											<div class="col-md-12">
												<div class="pull-left">
													<div class="dropdown" style="float: left;margin-left: 5px;">
														<button class="btn btn-warning dropdown-toggle" type="button" data-toggle="dropdown"><i class="fa fa-print fa-fw"></i> Cetak <span class="caret"></span>
														</button>
														<ul class="dropdown-menu">
															<li><a onclick="do_print_slip()">Slip Gaji</a></li>
														</ul>
													</div>
												</div>
												<div class="pull-right" style="font-size: 8pt;">
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<style type="text/css">
							table#table_data thead tr th,
							table#table_data tbody tr td,
							table.DTFC_Cloned thead tr th {
								white-space: pre;
							}

							table.DTFC_Cloned tbody {
								overflow: hidden;
							}
						</style>
						<div class="row">
							<div class="col-md-12">
								<table id="table_data" class="table table-bordered table-striped table-responsive" width="100%">
									<thead>
										<tr>
											<th>No.</th>
											<th>NIK Karyawan</th>
											<th>Nama Karyawan</th>
											<th>Jabatan</th>
											<th>Grade</th>
											<th>Tanggal Masuk</th>
											<th>Masa Kerja</th>
											<th>Gaji Pokok (Rp)</th>
											<th>Presensi</th>
											<th>Lainnya</th>
											<th>Nominal Lainnya (Rp)</th>
											<th>Keterangan Lainnya</th>
											<th>Gaji Diterima (Rp)</th>
											<th>Jam Lembur</th>
											<th>Gaji Lembur (Rp)</th>
											<th>Gaji Bersih (Rp)</th>
											<th>Nomor Rekening</th>
											<th>Tanggal</th>
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
	</section>
</div>
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
					<div class="col-md-12">
						<div class="col-md-6">
							<div class="form-group col-md-12">
								<label class="col-md-6 control-label">NIK</label>
								<div class="col-md-6" id="data_nik_view"></div>
							</div>
							<div class="form-group col-md-12">
								<label class="col-md-6 control-label">Nama</label>
								<div class="col-md-6" id="data_name_view"></div>
							</div>
							<div class="form-group col-md-12">
								<label class="col-md-6 control-label">Jabatan</label>
								<div class="col-md-6" id="data_jabatan_view"></div>
							</div>
							<div class="form-group col-md-12">
								<label class="col-md-6 control-label">Grade</label>
								<div class="col-md-6" id="data_grade_view"></div>
							</div>
							<div class="form-group col-md-12">
								<label class="col-md-6 control-label">Bagian</label>
								<div class="col-md-6" id="data_bagian_view"></div>
							</div>
							<div class="form-group col-md-12">
								<label class="col-md-6 control-label">Lokasi Kerja</label>
								<div class="col-md-6" id="data_loker_view"></div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group col-md-12">
								<label class="col-md-6 control-label">Status</label>
								<div class="col-md-6" id="data_status_view"></div>
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
							<!-- <div class="form-group col-md-12">
								<div id="data_pph_view"></div>
							</div> -->
						</div>
					</div>
					<hr>
					<div class="col-md-12">
						<div class="col-md-6">
							<div class="form-group col-md-12">
								<label class="col-md-6 control-label">Tanggal Masuk</label>
								<div class="col-md-6" id="data_tanggal_view"></div>
							</div>
							<div class="form-group col-md-12">
								<label class="col-md-6 control-label">Masa Kerja</label>
								<div class="col-md-6" id="data_masa_view"></div>
							</div>
							<div class="form-group col-md-12">
								<label class="col-md-6 control-label">Nomor Rekening</label>
								<div class="col-md-6" id="data_rekening_view"></div>
							</div>
							<div class="form-group col-md-12">
								<label class="col-md-6 control-label">Sistem Penggajian</label>
								<div class="col-md-6" id="data_sistem_view"></div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group col-md-12">
								<label class="col-md-6 control-label">Minggu</label>
								<div class="col-md-6" id="data_minggu_view"></div>
							</div>
							<div class="form-group col-md-12">
								<label class="col-md-6 control-label">Bulan</label>
								<div class="col-md-6" id="data_bulan_view"></div>
							</div>
							<div class="form-group col-md-12">
								<label class="col-md-6 control-label">Tahun</label>
								<div class="col-md-6" id="data_tahun_view"></div>
							</div>
							<div class="form-group col-md-12">
								<label class="col-md-6 control-label">Gaji Pokok</label>
								<div class="col-md-6" id="data_gaji_pokok_view"></div>
							</div>
							<div class="form-group col-md-12">
								<label class="col-md-6 control-label">Hari Kerja</label>
								<div class="col-md-6" id="data_hari_kerja_view"></div>
							</div>
							<div class="form-group col-md-12">
								<label class="col-md-6 control-label">Gaji</label>
								<div class="col-md-6" id="data_gaji_diterima_view"></div>
							</div>
							<div class="form-group col-md-12">
								<div id="data_gaji_bersih_view"></div>
							</div>
						</div>
					</div>
					<div class="col-md-12">
						<div style="text-align: center;">
							<h3>Detail Data</h3>
						</div>
						<div class="col-md-12">
							<div id="data_lembur_view"></div>
						</div>
						<br>
						<div class="col-md-12">
							<div id="data_bpjs_view"></div>
						</div>
						<br>
						<br>
						<div class="col-md-12">
							<div id="data_lainnya_view"></div>
						</div>
					</div>
				</div>
				<!-- <div class="row">
					<div class="col-md-12">
						<div id="data_tabel_view"></div>
					</div>
				</div> -->
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
			</div>
		</div>
	</div>
</div>
<div id="slip_mode" class="modal fade" role="dialog">
	<div class="modal-dialog modal-sm modal-warning">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title text-center"><i class="fas fa-file-export"></i> Cetak Slip</h4>
			</div>
			<div class="modal-body text-center">
				<form id="form_filter_slip">
					<input type="hidden" name="mode" id="usage_slip_mode" value="">
					<input type="hidden" name="usage" value="data">
					<div class="form-group">
						<label>Pilih Periode Penggajian</label>
						<select class="form-control select2" name="periode" id="data_kode_periode_slip" style="width: 100%;">
							<?php
							$periodew = $this->model_master->getPeriodePenggajianHarian(['a.status_gaji' => 0, 'a.status' => 1, 'a.create_by' => $id_admin, 'a.kode_master_penggajian' => 'HARIAN'], null, 1);
							echo '<option></option>';
							foreach ($periodew as $pw) {
								echo '<option value="' . $pw->kode_periode_penggajian_harian . '">' . $pw->nama . ' (' . $pw->nama_sistem_penggajian . ')</option>';
							}
							?>
						</select>
					</div>
				</form>
				<div class="col-md-12" style="text-align: center;">
					<button type="button" class="btn btn-danger" onclick="do_print_slip()"><i class="fas fa-file-pdf fa-fw"></i>PDF</button>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="modal-footer">
			</div>
		</div>
	</div>
</div>
<div id="modal_delete_partial"></div>
<script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js'); ?>"></script>
<script type="text/javascript">
	var url_select = "<?php echo base_url('global_control/select2_global'); ?>";
	var table = "data_penggajian";
	var column = "id_penggajian";
	$(document).ready(function() {
		table_data('all');
	});
	function table_data(kode) {
		$('#table_data').DataTable({
			ajax: {
				url: "<?php echo base_url('cpayroll/data_penggajian_harian/view_all/') ?>",
				type: 'POST',
				data: {
					form: $('#adv_form_filter').serialize(),
					access: "<?php echo $this->codegenerator->encryptChar($access); ?>",
					param: kode,
				}
			},
			fixedColumns: {
				leftColumns: 3,
				rightColumns: 1
			},
			bDestroy: true,
			scrollX: true,
			autoWidth: false,
			columnDefs: [{
					targets: 0,
					width: '5%',
					render: function(data, type, full, meta) {
						return '<center>' + (meta.row + 1) + '.</center>';
					}
				},
				{
					targets: 13,
					width: '5%',
					render: function(data, type, full, meta) {
						return '<center>' + data + '</center>';
					}
				},
				{
					targets: 14,
					width: '10%',
					render: function(data, type, full, meta) {
						return '<center>' + data + '</center>';
					}
				},
			]
		});
	}

	function view_modal(id) {
		var data = {
			id_pay: id,
			mode: 'view'
		};
		var callback = getAjaxData("<?php echo base_url('cpayroll/data_penggajian_harian/view_one') ?>", data);
		$('#view').modal('show');
		$('.header_data').html(callback['nama_karyawan']);
		$('input[name="data_id_view"]').val(callback['id']);

		$('#data_nik_view').html(callback['nik']);
		$('#data_name_view').html(callback['nama_karyawan']);

		$('#data_jabatan_view').html(callback['jabatan']);
		$('#data_grade_view').html(callback['grade']);
		$('#data_bagian_view').html(callback['bagian']);
		$('#data_loker_view').html(callback['loker']);
		$('#data_sistem_view').html(callback['sistem']);
		$('#data_tanggal_view').html(callback['tanggal']);
		$('#data_masa_view').html(callback['masa']);
		$('#data_rekening_view').html(callback['rekening']);
		$('#data_penambah_view').html(callback['penambah']);
		$('#data_pengurang_view').html(callback['pengurang']);
		$('#data_lembur_view').html(callback['lembur']);
		$('#data_bpjs_view').html(callback['data_bpjs']);
		$('#data_lainnya_view').html(callback['data_lainnya']);
		$('#data_hari_kerja_view').html(callback['presensi']);
		$('#data_gaji_diterima_view').html(callback['gaji_diterima']);

		$('#data_gaji_bersih_view').html(callback['total_gaji']);
		$('#data_gaji_pokok_view').html(callback['gaji_pokok']);
		$('#data_pph_view').html(callback['pph']);

		// $('#data_periode_view').html(callback['periode']);
		$('#data_minggu_view').html(callback['minggu_view']);
		$('#data_bulan_view').html(callback['bulan_view']);
		$('#data_tahun_view').html(callback['tahun']);
		var status = callback['status'];
		if (status == 1) {
			var statusval = '<b class="text-success">Aktif</b>';
		} else {
			var statusval = '<b class="text-danger">Tidak Aktif</b>';
		}
		$('#data_status_view').html(statusval);
		$('#data_create_date_view').html(callback['create_date'] + ' WIB');
		$('#data_update_date_view').html(callback['update_date'] + ' WIB');
		$('#data_create_by_view').html(callback['nama_buat']);
		$('#data_update_by_view').html(callback['nama_update']);
	}
	function do_print_slip() {
		$.redirect("<?php echo base_url('kpages/slip_gaji_harian'); ?>", {
				data_filter: $('#adv_form_filter').serialize()
			},
			"POST", "_blank");
	}
</script>
