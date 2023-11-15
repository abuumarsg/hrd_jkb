<div class="content-wrapper">
	<section class="content-header">
		<h1>
			<a class="back" onclick="window.history.back()"><i class="fa fa-chevron-circle-left "></i></a>
			<i class="fa fa-line-chart"></i> Hasil Penilaian
			<small>Sikap</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
			<li><a href="<?php echo base_url('pages/data_hasil_sikap');?>"><i class="fa fa-calendar"></i> Daftar Agenda</a></li>
			<li class="active">Daftar Karyawan</li>
		</ol>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title"><i class="fa fa-search"></i> Advance Filter Data</h3>
						<div class="box-tools pull-right">
							<button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
							<button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i class="fa fa-times"></i></button>
						</div>
					</div>
					<div style="padding-top: 10px;">
						<form id="form_filter">
							<input type="hidden" name="usage" id="usage" value="all">
							<input type="hidden" name="mode" id="mode" value="">
							<div class="box-body">
								<div class="col-md-4">
									<div class="">
										<label>Pilih Departement</label>
										<select class="form-control select2" id="departemen_filter" name="departemen_filter" style="width: 100%;"></select>
									</div>
								</div>
								<div class="col-md-4">
									<div class="">
										<label>Pilih Bagian</label>
										<select class="form-control select2" id="bagian_filter" name="bagian_filter" style="width: 100%;"></select>
									</div>
								</div>
								<div class="col-md-4">
									<div class="">
										<label>Pilih Lokasi Kerja</label>
										<select class="form-control select2" id="loker_filter" name="loker_filter" style="width: 100%;"></select>
									</div>
								</div>
							</div>
							<div class="box-footer">
								<div class="col-md-12">
									<div class="pull-right">
										<button type="button" onclick="tableData('search')" class="btn btn-success"><i class="fa fa-eye"></i> Lihat
											Data</button>
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="box box-success">
					<div class="box-header with-border">
						<h3 class="box-title"><i class="fa fa-users"></i> Daftar Karyawan Penilaian Sikap</h3>
						<div class="box-tools pull-right">
							<button class="btn btn-box-tool" onclick="reload_table('table_data')" data-toggle="tooltip" title="Refresh Table"><i class="fas fa-sync"></i></button>
							<button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
							<button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i class="fa fa-times"></i></button>
						</div>
					</div>
					<div class="box-body">
						<div class="row">
							<div class="col-md-12">
								<?php if (in_array($access['l_ac']['rkp'], $access['access'])) {?>
								<form action="<?=base_url('rekap/rekap_nilai_sikap')?>" id="form_nilai_sikap" method="post">
									<input type="hidden" name="data" id="data_rekap" value="">
									<input type="hidden" name="tahun" id="tahun" value="">
									<input type="hidden" name="periode" id="periode" value="">
									<input type="hidden" name="data_form" value="">
								</form>
								<div class="pull-left">									
									<div class="btn-group">
										<button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown"><i class="fas fa-file-excel-o"></i> Export <span class="fa fa-caret-down"></span></button>
										<ul class="dropdown-menu">
											<li><a onclick="rekap_data()">Export Data Nilai</a></li>
											<li><a onclick="rekap_data_quisioner()">Export Data Kuisioner</a></li>
										</ul>
									</div>									
								</div>
								<?php
                  				}if(in_array('PRN', $access['access'])){?>
								<form action="<?=base_url('pages/print_page')?>" id="form_print_nilai_sikap" target="_blank" method="post">
									<input type="hidden" name="page" value="result_attd">
									<input type="hidden" name="data" id="data_rekap_print" value="">
									<input type="hidden" name="code" value="<?php echo $this->uri->segment(3);?>">
									<input type="hidden" name="data_form" value="">
								</form>
								<div class="pull-left" style="margin-left: 4px;">
									<button type="button" onclick="print_data()" class="btn btn btn-info"><i class="fa fa-print"></i> Print</button>
								</div>
								<?php }?>
								<?php if (in_array('EDT', $access['access']) && !$validasi) {?>
								<div class="pull-right">
									<a onclick="kalibrasi_nilai()" class="btn btn btn-primary" style="margin-left: 3px;"><i class="fa fa-balance-scale"></i>Kalibrasi Nilai</a>
								</div>
								<?php } ?>
								<?php if (in_array($access['l_ac']['rkp'], $access['access'])) {?>
								<div class="pull-right">
									<?php echo form_open('rekap/rekap_partisipan_sikap');?>
									<button type="submit" class="btn btn btn-danger"><i class="fa fa-users"></i> Rekap Partisipan</button>
									<input type="hidden" name="data" id="datax" value="">
									<input type="hidden" name="tahunx" id="tahunx" value="">
									<input type="hidden" name="periodex" id="periodex" value="">
									<input type="hidden" name="cod" value="<?php echo $this->uri->segment(3);?>">
									<?php echo form_close();?>
								</div>
								<?php } ?>
							</div>
						</div>
						<br>
						<div class="row">
							<div class="col-md-12">
								<div class="callout callout-info">
									<label><i class="fa fa-info-circle"></i> Bantuan</label><br>Pilih <b>NIK</b> untuk melihat Hasil
									Raport Penilaian Kinerja Sikap
								</div>
								<table id="table_data" class="table table-bordered table-striped table-responsive" width="100%">
									<thead>
										<tr>
											<th>No.</th>
											<th>NIK</th>
											<th>Nama Karyawan</th>
											<th>Jabatan</th>
											<th>Bagian</th>
											<th>Departemen</th>
											<th>Lokasi Kerja</th>
											<th>Jumlah Partisipan</th>
											<th><i class="fa fa-spin fa-refresh"></i> Belum Selesai</th>
											<th><i class="fa fa-check-circle"></i> Selesai</th>
											<th>Nilai Asli</th>
											<th>Nilai Kalibrasi</th>
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
<div id="view_partisipan" class="modal fade" role="dialog">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h2 class="modal-title">Partisipan <b class="text-muted header_data"></b></h2>
				<input type="hidden" name="data_id_view" value="">
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<ul>
							<div id="data_n_partisipan_view"></div>
						</ul>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
			</div>
		</div>
	</div>
</div>
<div id="view_sudah" class="modal fade" role="dialog">
	<div class="modal-dialog modal-md">
		<div class="modal-content modal-success">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h2 class="modal-title">Partisipan Selesai <b class="text-muted header_data"></b></h2>
				<input type="hidden" name="data_id_view">
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<ul>
							<div id="data_partisipan_sudah_view"></div>
						</ul>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
			</div>
		</div>
	</div>
</div>
<div id="view_belum" class="modal fade" role="dialog">
	<div class="modal-dialog modal-md">
		<div class="modal-content modal-danger">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h2 class="modal-title">Partisipan Belum Menilai <b class="text-muted header_data"></b></h2>
				<input type="hidden" name="data_id_view">
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<ul>
							<div id="data_partisipan_belum_view"></div>
						</ul>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
			</div>
		</div>
	</div>
</div>
<div id="view_kalibrasi_one" class="modal fade" role="dialog">
	<div class="modal-dialog modal-md">
		<div class="modal-content modal-default">
			<form id="form_edit">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h2 class="modal-title">Kalibrasi Nilai <b class="text-muted header_data"></b></h2>
					<input type="hidden" name="data_id_view">
				</div>
				<div class="modal-body">
					<input type="hidden" name="tabel" id="tabel" value="'">
					<input type="hidden" name="kode" id="kode" value="">
					<input type="hidden" name="id_karyawan" id="id_karyawan" value="">
					<div class="form-group">
						<label>Nilai Kalibrasi</label>
						<input type="number" step="0.01" required="required" max="100" placeholder="Masukkan Nilai Kalibrasi" class="form-control"
						 name="nilai" id="nilai" value="" />
					</div>
				</div>
			</form>
			<div class="modal-footer">
				<button type="button" onclick="do_edit()" id="btn_edit" class="btn btn-success"><i class="fa fa-floppy-o"></i>
					Simpan</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
			</div>
		</div>
	</div>
</div>
<div id="kalibrasi_nilai" class="modal fade" role="dialog">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h2 class="modal-title">Kalibrasi Nilai Sikap</h2>
				<input type="hidden" name="data_id_view">
			</div>
			<div class="modal-body">
				<form id="form_kalibrasi">
					<input type="hidden" name="data" id="data_nilai_akhir" value="">
					<input type="hidden" name="tabel" id="tabel_kalibrasi" value="">
					<div class="form-group">
						<label class="clearfix">Pilih Karyawan yang akan dikalibrasi</label>
						<select id="karyawan_kalibrasi" name="karyawan[]" class="form-control" multiple="multiple" style="width:100%"></select>
					</div>
					<div class="form-group">
						<label class="clearfix">Pilih Opsi</label>
						<?php 
    						$op=array('+'=>'Tambah','-'=>'Kurangi');
    						$sel1 = array(NULL);
    						$ex1 = array('class'=>'form-control select2','style'=>'width:100%','required'=>'required');
    						echo form_dropdown('operator',$op,$sel1,$ex1); 
    						?>
					</div>
					<div class="form-group">
						<label class="clearfix">Nilai Kalibrasi</label>
						<input type="number" step="0.01" required="required" max="100" placeholder="Masukkan Nilai Kalibrasi" class="form-control"
						 name="nilai" />
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" onclick="do_kalibrasi()" id="btn_kalibrasi" class="btn btn-success"><i class="fa fa-floppy-o"></i>
					Simpan</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
			</div>
		</div>
	</div>
</div>

<script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
<script type="text/javascript">
	var url_select = "<?php echo base_url('global_control/select2_global');?>";
	$(document).ready(function () {
		select_data('bagian_filter', url_select, 'master_bagian', 'kode_bagian', 'nama');
		select_data('loker_filter', url_select, 'master_loker', 'kode_loker', 'nama');
		select_data('departemen_filter', url_select, 'master_departement', 'kode_departement', 'nama');
		unsetoption('bagian_filter', ['BAG001', 'BAG002']);
		unsetoption('departemen_filter', ['DEP001']);
		console.clear();
		tableData('all');
		var getform = $('#form_filter').serialize();
		$('input[name="data_form"]').val(getform);
		preventEnterKey();
		readyx();
	});

	function tableData(kode) {
		$('#usage').val(kode);
		$('#mode').val('data');
		$('#table_data').DataTable().destroy();
		if (kode == 'all') {
			$('#form_filter .select2').val('').trigger('change');
		}

		var getform = $('#form_filter').serialize();
		$('input[name="data_form"]').val(getform);
		var datax = {
			form: getform,
			access: "<?= $this->codegenerator->encryptChar($access);?>"
		};
		var t=$('#table_data').DataTable({
			ajax: {
				url: "<?php echo base_url('agenda/view_employee_result_sikap/view_all/'.$this->codegenerator->encryptChar($kode))?>",
				type: 'POST',
				data: datax
			},
			scrollX: true,
			processing: true,
			order:[1,'asc'],
			columnDefs: [{
					targets: 0,
					width: '5%',
					render: function (data, type, full, meta) {
						return '<center>' + (meta.row + 1) + '.</center>';
					}
				},
				{
					targets: 1,
					width: '5%',
					render: function (data, type, full, meta) {
						return '<a href="<?php echo base_url();?>pages/report_value_sikap/' + full[13] + '/' + full[14] + '" target="_blank">' + data +
							'</a>';
					}
				},
				{
					targets: [2,3],
					width: '30%',
					render: function (data, type, full, meta) {
						return data;
					}
				},
				{
					targets: [7,8,9],
					width: '7%',
					render: function (data, type, full, meta) {
						return '<center>' + data + '</center>';
					}
				},
				{
					targets: [10,11,12],
					width: '5%',
					render: function (data, type, full, meta) {
						return '<center>' + data + '</center>';
					}
				},
			]
		});
		t.on( 'order.dt search.dt', function () {
			t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
				cell.innerHTML = i+1;
			} );
		}).draw();
		readyx();
	}

	function readyx() {
		var getform = $('#form_filter').serialize();
		$('input[name="data_form"]').val(getform);
		var datax = {
			form: getform,
			access: "<?php echo $this->codegenerator->encryptChar($access);?>"
		};
		var callback = getAjaxData(
			"<?php echo base_url('agenda/view_employee_result_sikap/get_property/'.$this->codegenerator->encryptChar($kode))?>",
			datax);
		$('#data_rekap').val(callback['rekap_nilai']);
		$('#data_rekap_print').val(callback['rekap_nilai']);
		$('#periode').val(callback['periode']);
		$('#tahun').val(callback['tahun']);
		$('#datax').val(callback['rekap_nilai']);
		$('#periodex').val(callback['periode']);
		$('#tahunx').val(callback['tahun']);
	}

	function view_partisipan(id) {
		var data = {
			id_karyawan: id,
			access: "<?= $this->codegenerator->encryptChar($access);?>"
		};
		var callback = getAjaxData(
			"<?php echo base_url('agenda/view_employee_result_sikap/view_one/'.$this->codegenerator->encryptChar($kode))?>",
			data);
		$('#view_partisipan').modal('show');
		$('.header_data').html(callback['nama']);
		$('#data_n_partisipan_view').html(callback['data_all']);
	}

	function view_sudah(id) {
		var data = {
			id_karyawan: id,
			access: "<?= $this->codegenerator->encryptChar($access);?>"
		};
		var callback = getAjaxData(
			"<?php echo base_url('agenda/view_employee_result_sikap/view_one/'.$this->codegenerator->encryptChar($kode))?>",
			data);
		$('#view_sudah').modal('show');
		$('.header_data').html(callback['nama']);
		$('#data_partisipan_sudah_view').html(callback['data_done']);
	}

	function view_belum(id) {
		var data = {
			id_karyawan: id,
			access: "<?= $this->codegenerator->encryptChar($access);?>"
		};
		var callback = getAjaxData(
			"<?php echo base_url('agenda/view_employee_result_sikap/view_one/'.$this->codegenerator->encryptChar($kode))?>",
			data);
		$('#view_belum').modal('show');
		$('.header_data').html(callback['nama']);
		$('#data_partisipan_belum_view').html(callback['data_unfinish']);
	}

	function view_kalibrasi_one(id) {
		var data = {
			id_karyawan: id,
			access: "<?= $this->codegenerator->encryptChar($access);?>"
		};
		var callback = getAjaxData(
			"<?php echo base_url('agenda/view_employee_result_sikap/view_other/'.$this->codegenerator->encryptChar($kode))?>",
			data);
		$('#view_kalibrasi_one').modal('show');
		$('.header_data').html(callback['nama']);
		$('#tabel').val(callback['tabel']);
		$('#kode').val(callback['kode']);
		$('#id_karyawan').val(callback['id_karyawan']);
		$('#nilai').val(callback['nilai_kalibrasi']);
	}

	function kalibrasi_nilai() {
		$('#form_kalibrasi')[0].reset();
		var callback = getAjaxData("<?php echo base_url('agenda/view_employee_result_sikap/kalibrasi_all/'.$this->codegenerator->encryptChar($kode))?>",{access:"<?php echo $this->codegenerator->encryptChar($access);?>"}	);
		$('#kalibrasi_nilai').modal('show');
		var data = callback['karyawan'];
		$('#karyawan_kalibrasi').select2({
			data: data
		});
		$('#tabel_kalibrasi').val(callback['tabel']);
		$('#data_nilai_akhir').val(callback['nilai_akhir']);
	}

	function do_edit() {
		if ($("#form_edit")[0].checkValidity()) {
			submitAjax("<?php echo base_url('agenda/edit_kalibrasi')?>", 'view_kalibrasi_one', 'form_edit', null, null);
			$('#table_data').DataTable().ajax.reload(function () {
				Pace.restart();
			});
			$('#form_edit')[0].reset();
		} else {
			notValidParamx();
		}
	}

	function do_kalibrasi() {
		if ($("#form_kalibrasi")[0].checkValidity()) {
			submitAjax("<?php echo base_url('agenda/kalibrasi_sikap')?>", 'kalibrasi_nilai', 'form_kalibrasi', null, null);
			$('#table_data').DataTable().ajax.reload(function () {
				Pace.restart();
			});
			$('#form_kalibrasi')[0].reset();
		} else {
			notValidParamx();
		}
	}	
	function rekap_data() {
		$('#form_nilai_sikap').submit();
	}
	function print_data() {
		$('#form_print_nilai_sikap').submit();
	}
	function rekap_data_quisioner() {
		window.open("<?php echo base_url('rekap/rekap_nilai_sikap_kuisioner/'.$this->codegenerator->encryptChar($kode).'/'.$this->codegenerator->encryptChar($access)) ?>/?"+$('#form_filter').serialize(), "_blank");
	}
</script>
