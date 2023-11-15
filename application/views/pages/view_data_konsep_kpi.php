  <div class="content-wrapper">
  	<section class="content-header">
  		<h1>
  			<i class="fa fa-flask"></i> Rancangan
  			<small>KPI <?php echo $nama; ?></small>
  		</h1>
  		<ol class="breadcrumb">
  			<li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
  			<li><a href="<?php echo base_url('pages/data_konsep_kpi');?>"><i class="fas fa-flask"></i> Rancangan KPI</a>
  			</li>
  			<li class="active">Daftar Jabatan <?php echo $nama; ?></li>
  		</ol>
  	</section>
  	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title"><i class="fa fa-search"></i> Advance Filter Data</h3>
						<div class="box-tools pull-right">
							<button class="btn btn-box-tool" onclick="refreshData()" data-toggle="tooltip" title="Refresh Data"><i class="fas fa-sync"></i></button>
							<button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
							<button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i class="fa fa-times"></i></button>
						</div>
					</div>
					<div style="padding-top: 10px;">
						<form id="form_filter">
							<input type="hidden" name="param" value="all">
							<input type="hidden" name="mode" value="data">
							<div class="box-body">
								<div class="col-md-2"></div>
								<div class="col-md-8">
									<label class="col-sm-2 control-label">Pilih Lokasi Kerja</label>
									<div class="col-sm-8">
										<select class="form-control select2" id="lokasi_ser" name="lokasi" style="width: 100%;"></select>
									</div>
									<div class="col-sm-2 pull-left">
										<button type="button" onclick="tableData('search')" class="btn btn-success"><i class="fa fa-eye"></i> Lihat Data</button>
									</div>
								</div>
							</div>
							<div class="box-footer"></div>
						</form>
					</div>
				</div>
			</div>
		</div>
  		<div class="row">
  			<div class="col-md-12">
  				<div class="box box-success">
  					<div class="box-header with-border">
  						<h3 class="box-title"><i class="fa fa-briefcase"></i> Daftar Jabatan KPI</h3>
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
  											<?php if (in_array('ADD', $access['access'])) { ?>
  												<button class="btn btn-success" type="button" data-toggle="collapse" data-target="#add"
  												id="btn_add_collapse"><i class="fa fa-plus"></i> Tambah Jabatan</button>
											<?php } if (in_array('SNC', $access['access'])) {?>
												<button class="btn btn-info" type="button" data-toggle="modal" data-target="#sync"
  												id="btn_sync"><i class="fa fa-magic"></i> Sync dari Agenda</button>
  											<?php } 
											if (in_array('IMP', $access['access'])) {
												echo '<button class="btn btn-primary" type="button" data-toggle="modal" data-target="#import" aria-expanded="false" aria-controls="import" id="btn_import"><i class="fas fa-cloud-upload-alt"></i> Import</button> ';
											}
											if (in_array('EXP', $access['access'])) {
												echo '<div class="btn-group">
												<button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown"><i class="fas fa-file-excel-o"></i> Export
												<span class="fa fa-caret-down"></span></button>
												<ul class="dropdown-menu">
												<li><a onclick="print_template()">Export Template</a></li>
												<li><a onclick="rekap_data()">Export Data</a></li>
												</ul>
												</div>';
											}
											?>
  										</div>
  										<div class="modal fade" id="import" role="dialog">
  											<div class="modal-dialog">
  												<div class="modal-content text-center">
  													<div class="modal-header">
  														<button type="button" class="close" data-dismiss="modal">&times;</button>
  														<h2 class="modal-title">Import Data Dari Excel</h2>
  													</div>
  													<form id="form_import" action="#">
  														<input type="hidden" name="tabel" value="<?php echo $tabel; ?>">
  														<input type="hidden" name="kode_concept" value="<?php echo $this->uri->segment(3); ?>">
  														<div class="modal-body">
														  <div class="callout callout-danger text-left"><b><i class="fa fa-warning"></i> Peringatan</b><br>Pastikan karyawan yang berada pada nama jabatan yang sama memiliki <b>target dan bobot yang sama</b>!</div>
  															<p class="text-muted">File Data Template Konsep KPI harus tipe *.xls, *.xlsx,*.csv, *.ods dan *.ots</p>
  															<input id="uploadFilex" placeholder="Nama File" readonly="readonly" class="form-control"
  																required="required">
  															<span class="input-group-btn">
  																<div class="fileUpload btn btn-warning">
  																	<span><i class="fa fa-folder-open"></i> Pilih File</span>
  																	<input id="uploadBtnx" type="file" class="upload" name="file"
  																		onchange="checkFile('#uploadBtnx','#uploadFilex','#save')" />
  																</div>
  															</span>
  														</div>
  														<div class="modal-footer">
  															<div id="progress2" style="float: left;"></div>
  															<button class="btn btn-primary all_btn_import" id="save" type="button" disabled
  																style="margin-right: 4px;"><i class="fa fa-check-circle"></i> Upload</button>
  															<button id="savex" type="submit" style="display: none;"></button>
  															<button type="button" class="btn btn-default all_btn_import"
  																data-dismiss="modal">Kembali</button>
  														</div>
  													</form>
  												</div>
  											</div>
  										</div>
  										<div class="pull-right" style="font-size: 8pt;">
											<i class="fa fa-toggle-on stat scc"></i> Aktif<br>
											<i class="fa fa-toggle-off stat err"></i> Tidak Aktif
  										</div>
  									</div>
  								</div>
  								<?php if(in_array('ADD', $access['access'])){?>
  								<div class="collapse" id="add">
  									<br>
  									<form id="form_add">
  										<div class="row">
  											<div class="col-md-2"></div>
  											<div class="col-md-8">
  												<input type="hidden" name="tabel" value="<?php echo $tabel; ?>">
  												<input type="hidden" name="kode_concept" value="<?php echo $this->uri->segment(3); ?>">
  												<div class="form-group">
  													<label>Jabatan</label>
  													<select class="form-control select2" name="jabatan" id="data_jabatan_add"
  														style="width: 100%;"></select>
  												</div>
  												<div class="form-group">
  													<label>Pilih KPI</label>
  													<input type="hidden" name="kode_kpi_hidden" value="" style="background-color: black;">
  													<table id="table_data_kpi" class="table table-bordered table-striped table-responsive"
  														style="width: 100%;">
  														<thead>
  															<tr>
  																<th style="width: 10%;"></th>
  																<th style="width: 45%;">Kode KPI</th>
  																<th style="width: 45%;">KPI</th>
  															</tr>
  														</thead>
  														<tbody id="get_kpi"></tbody>
  													</table>
  												</div>
  											</div>
  										</div>
  										<div class="row">
  											<div class="col-md-12">
  												<div class="form-group">
  													<button type="button" onclick="do_add()" class="btn btn-success pull-right"><i
  															class="fa fa-floppy-o"></i> Simpan</button>
  												</div>
  											</div>
  										</div>
  									</form>
  								</div>
  								<?php } ?>
  							</div>
  						</div>
  						<br>
  						<div class="callout callout-info">
  							<b><i class="fa fa-bullhorn"></i> Petunjuk</b><br>
  							Penambahan dan pengurangan data konsep KPI akan berpengaruh pada <b>Agenda KPI <b class="text-red">(yang belum dilakukan Validasi dan yang hanya terkait dengan <?=$nama?>)</b></b>. Pastikan data Anda benar sebelum menambah ataupun menghapus jabatan!
  						</div>
  						<div class="row">
  							<div class="col-md-12">
  								<!-- Data Begin Here -->
  								<table id="table_data" class="table table-bordered table-striped table-responsive" width="100%">
  									<thead>
  										<tr>
  											<th>No.</th>
  											<th>Nama Jabatan</th>
  											<th>Bagian</th>
  											<th>Level Jabatan</th>
  											<th>Lokasi</th>
  											<th>Bobot KPI</th>
  											<th>Jumlah KPI</th>
  											<th>Jumlah Karyawan</th>
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
  <!-- view -->
  <div id="view" class="modal fade" role="dialog">
  	<div class="modal-dialog modal-lg">
  		<div class="modal-content">
  			<div class="modal-header">
  				<button type="button" class="close" data-dismiss="modal">&times;</button>
  				<h2 class="modal-title">Detail Data <b class="text-muted header_data"></b></h2>
  				<input type="hidden" name="data_kode_view">
  			</div>
  			<div class="modal-body">
  				<div class="row">
  					<div class="col-md-6">
  						<div class="form-group col-md-12">
  							<label class="col-md-6 control-label">Nama Jabatan</label>
  							<div class="col-md-6" id="data_nama_view"></div>
  						</div>
  						<div class="form-group col-md-12">
  							<label class="col-md-6 control-label">Nama Bagian</label>
  							<div class="col-md-6" id="data_bagian_view"></div>
  						</div>
						<div class="form-group col-md-12">
  							<label class="col-md-6 control-label">Level Jabatan</label>
  							<div class="col-md-6" id="data_jabatan_view"></div>
  						</div>
  					</div>
  					<div class="col-md-6">
  						<div class="form-group col-md-12">
  							<label class="col-md-6 control-label">Jumlah Karyawan</label>
  							<div class="col-md-6" id="data_jum_emp_view"></div>
  						</div>
  						<div class="form-group col-md-12">
  							<label class="col-md-6 control-label">Jumlah KPI</label>
  							<div class="col-md-6" id="data_jumkpi_view"></div>
  						</div>
  					</div>
  					<div class="col-md-12">
  						<div class="panel panel-primary">
  							<div class="panel-heading bg-blue"><b>Daftar KPI</b></div>
  							<div class="panel-body div-overflow">
  								<div class="col-md-12" id="data_kpi_view"></div>
  							</div>
  						</div>
  						<div class="panel panel-success">
  							<div class="panel-heading bg-green"><b><i class="fa fa-users"></i> Daftar Karyawan</b></div>
  							<div class="panel-body div-overflow">
  								<div class="col-md-12" id="data_kpikaryawan_view"></div>
  							</div>
  						</div>
  					</div>
  				</div>
  			</div>
  			<div class="modal-footer">
  				<!-- <button type="button" class="btn btn-info"><i class="fa fa-user"></i> Edit Penilai</button> -->
  				<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
  			</div>
  		</div>
  	</div>
  </div>
  <!-- edit -->
  <div id="edit" class="modal fade" role="dialog">
  	<div class="modal-dialog modal-md">
  		<div class="modal-content">
  			<div class="modal-header">
  				<button type="button" class="close" data-dismiss="modal">&times;</button>
  				<h2 class="modal-title">Edit Penilai <b class="text-muted header_data"></b></h2>
  			</div>
  			<div class="modal-body text-left">
  				<div class="form-group">
  					<label>Pilih Penilai</label>
  				</div>
  				<div class="form-group" style="display: none;padding-top: 10px;" id="jbt_penilai">
  					<label>Pilih Jabatan</label>
  					<select id="fill_jbt_penilai" class="form-control select2" style="width: 100%;"
  						placeholder="Pilih Penilai" name="jbt_penilai"></select>
  				</div>
  				<div class="form-group" style="display: none;padding-top: 10px;" id="emp">
  					<label>Pilih Karyawan</label>
  				</div>
  			</div>
  			<div class="modal-footer">
  				<button type="button" class="btn btn-success" data-dismiss="modal"><i class="fa fa-floppy-o"></i>
  					Simpan</button>
  			</div>
  		</div>

  	</div>
  </div>
  <!-- delete -->
  <div id="delete" class="modal fade" role="dialog">
  	<div class="modal-dialog modal-sm modal-danger">
  		<div class="modal-content">
  			<div class="modal-header">
  				<button type="button" class="close" data-dismiss="modal">&times;</button>
  				<h4 class="modal-title text-center">Konfirmasi Hapus</h4>
  			</div>
  			<form id="form_delete">
  				<div class="modal-body text-center">
  					<input type="hidden" id="data_kode_jabatan_delete" name="kode_jabatan">
  					<input type="hidden" name="tabel" value="<?php echo $tabel; ?>">
  					<input type="hidden" name="kode_concept" value="<?php echo $this->uri->segment(3); ?>">
  					<p>Apakah anda yakin akan menghapus data dengan nama <b id="data_name_delete" class="header_data"></b> ?</p>
  				</div>
  			</form>
  			<div class="modal-footer">
  				<button type="button" onclick="do_delete()" class="btn btn-primary"><i class="fa fa-trash"></i> Hapus</button>
  				<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
  			</div>
  		</div>
  	</div>
  </div>
  <?php if (in_array('SNC', $access['access'])) {?>
  <!-- sync -->
  <div id="sync" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h2 class="modal-title">Sync dari Agenda KPI</h2>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
					<div class="callout callout-info"><label><i class="fa fa-bullhorn"></i> Perhatian</label><br>
						Jika Anda melakukan proses Sinkronisasi data dari Agenda KPI ke Rancangan KPI maka, data Rancangan KPI akan <b><i>replace</i></b> atau data lama <b class="text-red">AKAN HILANG</b> dan digantikan data baru dari Agenda KPI. 
					</div>
					<form id="form_sync">
						<input type="hidden" name="kode" value="<?=$this->uri->segment(3)?>">
						<input type="hidden" name="table" value="<?=$tabel?>">
						<div class="form-group">
						<label>Pilih Agenda KPI</label>
						<select class="form-control select2" name="agenda" id="data_agenda_sync" style="width: 100%;" required="required"></select>
						</div>
					</form>
					</div>
				</div>
			</div>
			<div class="modal-footer">
			<?php if (in_array('ADD', $access['access'])) { ?>
				<button class="btn btn-info" type="button" onclick="do_sync()" id="btn_sync_agenda"><i class="fa fa-magic"></i> Sync</button>
			<?php } ?>
			<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
			</div>
		</div>
    </div>
  </div>
	<?php } ?>
  <script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
  <script type="text/javascript">
  	// diisi
  	var table = "<?php echo $tabel;?>";
  	var code_c = "<?php echo $this->codegenerator->encryptChar($kode);?>";
  	var column = "id_c_kpi";
   	var url_select="<?php echo base_url('global_control/select2_global');?>";
  	$(document).ready(function () {
      	tableData('all');
  		$('#btn_import').click(function () {
  			$('#form_import')[0].reset();
  		})
         select_data('lokasi_ser',url_select,'master_loker','kode_loker','nama');
  		$('#save').click(function () {
  			$('.all_btn_import').attr('disabled', 'disabled');
  			$('#progress2').html(
  				'<i class="fa fa-spinner fa-pulse fa-fw"></i> Mohon Menunggu, data sedang di upload....')
  			setTimeout(function () {
  				$('#savex').click();
  			}, 1000);
  		})
  		$('#form_import').submit(function (e) {
  			e.preventDefault();
  			var data_add = new FormData(this);
  			var urladd = "<?php echo base_url('concept/import_detail_konsep_kpi'); ?>";
  			submitAjaxFile(urladd, data_add, '#import', '#progress2', '.all_btn_import');
  			reload_table('table_data');
  		});
  		//** search **//
  		$("#search_tambahan").on("keyup", function () {
  			var value = $(this).val().toLowerCase();
  			$("#get_kpi_tambahan tr").filter(function () {
  				$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
  			});
  		});
  		$("#search_rutin").on("keyup", function () {
  			var value = $(this).val().toLowerCase();
  			$("#get_kpi_rutin tr").filter(function () {
  				$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
  			});
  		});

  		$('#btn_add_collapse').click(function () {
  			dataXtable();
  			refreshJabatan();
  		})
		$('#btn_sync').click(function () {
  			getSelect2("<?php echo base_url('agenda/agenda_kpi/get_select2')?>", 'data_agenda_sync');
  		})

  	});
	function tableData(kode){
		$('#table_data').DataTable().destroy();
		if(kode=='all'){
			var lokasi = null;
		}else{
			var lokasi = $('#lokasi_ser').val();
		}
  		$('#table_data').DataTable({
  			ajax: {
  				url: "<?php echo base_url('concept/view_data_konsep_kpi/view_all/')?>",
  				type: 'POST',
  				data: {
  					access: "<?php echo $this->codegenerator->encryptChar($access);?>",
  					table: table,
  					code: code_c,
  					lokasi: lokasi					
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
  						return '<a href="<?php echo base_url('pages/view_detail_konsep_kpi/')?>' + full[9] + '/' + full[10] + '">' + data + '</a>';
  					}
  				},
  				//aksi
  				{
  					targets: [5,6,7,8],
  					width: '7%',
  					render: function (data, type, full, meta) {
  						return '<center>' + data + '</center>';
  					}
  				},
  			]
  		});
	}
  	function refreshJabatan() {
  		var url_select_jabatan = "<?php echo base_url('concept/view_data_konsep_kpi/select_jabatan');?>";
  		var data_sel_jabatan = {
  			tabel: table,
  			access: "<?= $this->codegenerator->encryptChar($access);?>"
  		};
  		getSelect2(url_select_jabatan, 'data_jabatan_add', data_sel_jabatan);
  	}

  	function view_modal(id) {
  		var data = {
  			table: table,
  			kode_jabatan: id
  		};
  		var callback = getAjaxData("<?php echo base_url('concept/view_data_konsep_kpi/view_one')?>", data);
  		$('#view').modal('show');
  		$('#data_kode_view').html(callback['kode']);
  		$('.header_data').html(callback['nama']);
  		$('#data_nama_view').html(callback['nama']);
  		$('#data_bagian_view').html(callback['nama_bagian']);
  		$('#data_jabatan_view').html(callback['nama_level']);
  		$('#data_jumkpi_view').html(callback['jumlah_kpi']);
  		$('#data_jum_emp_view').html(callback['jumlah_emp']);
  		if (callback['data_kpi'] != 'null') {
  			$('#data_kpi_view').html(callback['data_kpi']);
  			$('.view').css('display', 'block');
  		} else {
  			$('.view').css('display', 'none');
  		}
  		$('#data_kpikaryawan_view').html(callback['data_karyawan']);
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
      $('input[name="data_kode_view"]').val(callback['kode']);
  	}
    function edit_modal() {
  		var kode = $('input[name="data_kode_view"]').val();
      var data={kode_jabatan:kode};
  		var callback = getAjaxData("<?php echo base_url('concept/view_data_konsep_kpi/view_one')?>", data);
  		$('#view').modal('toggle');
        setTimeout(function () {
        $('#edit').modal('show');
      },600);
  		$('#data_kode_view').html(callback['kode']);
  		$('.header_data').html(callback['nama']);
  		$('#data_nama_view').html(callback['nama']);
  	}
  	function delete_modal(id) {
  		var col = 'kode_jabatan';
  		var data = {
  			kode_jabatan: id,
  			table: table
  		};
  		var callback = getAjaxData("<?php echo base_url('concept/view_data_konsep_kpi/view_one')?>", data);
  		$('#delete').modal('show');
  		$('#data_name_delete').html(callback['nama']);
  		$('#data_kode_jabatan_delete').val(callback['kode']);
  	}
  	function dataXtable() {
		$('#table_data_kpi').DataTable().destroy();
  		$('#table_data_kpi').DataTable({
  			ajax: {
  				url: '<?php echo base_url('concept/view_data_konsep_kpi/get_kpi ')?>',
  				type: 'POST',
  				data: {
  					access: "<?php echo $this->codegenerator->encryptChar($access);?>"
  				}
  			},
  			scrollX: true,
  			pagingType: "simple",
  			columnDefs: [{
  					targets: 0,
  					width: '10%',
  					render: function (data, type, full, meta) {
  						return '<center>' + data + '</center>';
  					}
  				},
  				{
  					targets: 1,
  					width: '45%',
  					render: function (data, type, full, meta) {
  						return data;
  					}
  				},
  				{
  					targets: 2,
  					width: '45%',
  					render: function (data, type, full, meta) {
  						return data;
  					}
  				},
  			]
  		});
  	}

  	function checkFile(idf, idt, btnx) {
  		var fext = ['xls', 'xlsx', 'csv', 'ods', 'ots'];
  		pathFile(idf, idt, fext, btnx);
  	}

  	function checkvalue() {
  		var oTable = $("#table_data_kpi").dataTable();
  		var matches = [];
  		var checkedcollection = oTable.$("input[name='kode_kpi']:checked", {
  			"page": "all"
  		});
  		checkedcollection.each(function (index, elem) {
  			matches.push($(elem).val());
  		});
  		$("#form_add input[name='kode_kpi_hidden']").val(matches);
  	}
  	//doing db transaction
  	function do_status(id, data) {
  		var datax = {
  			id: id,
  			act: data
  		};
  		submitAjax("<?php echo base_url('master/status_bidang')?>", null, datax, null, null, 'status');
  		$('#table_data').DataTable().ajax.reload(function () {
  			Pace.restart();
  		});
  	}

  	function do_edit() {
  		if ($("#form_edit")[0].checkValidity()) {
  			submitAjax("<?php echo base_url('master/edt_bidang')?>", 'edit', 'form_edit', null, null);
  			$('#table_data').DataTable().ajax.reload(function () {
  				Pace.restart();
  			});
  		} else {
  			notValidParamx();
  		}
  	}

  	function do_add() {
  		if ($("#form_add")[0].checkValidity()) {
  			submitAjax("<?php echo base_url('concept/add_jabatan_kpi')?>", null, 'form_add', null, null);
  			$('#table_data').DataTable().ajax.reload(function () {
  				Pace.restart();
  			});
  			$('#form_add')[0].reset();
  			refreshJabatan();
  		} else {
  			notValidParamx();
  		}
  	}
	
	function do_sync() {
  		if ($("#form_sync")[0].checkValidity()) {
  			submitAjax("<?php echo base_url('concept/sync_from_agenda_kpi')?>", 'sync', 'form_sync', null, null);
  			$('#table_data').DataTable().ajax.reload(function () {
  				Pace.restart();
  			});
  			$('#data_agenda_sync').val('').trigger('change');
  		} else {
  			notValidParamx();
  		}
  	}

  	function do_delete() {
  		submitAjax("<?php echo base_url('concept/delete_data_konsep_kpi')?>", 'delete', 'form_delete', null, null);
  		$('#table_data').DataTable().ajax.reload(function () {
  			Pace.restart();
  		});
  	}

  	function print_template() {
  		window.open("<?php echo base_url('rekap/export_template_data_konsep_kpi/'.$this->uri->segment(3).'/'.$this->codegenerator->encryptChar($access)); ?>","_blank");
  	}

  	function rekap_data() {
  		window.open("<?php echo base_url('rekap/export_data_konsep_kpi/'.$this->uri->segment(3).'/'.$this->codegenerator->encryptChar($access)); ?>","_blank");
  	}
  </script>
