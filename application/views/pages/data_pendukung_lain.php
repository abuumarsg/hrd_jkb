<div class="row">
	<div class="col-md-12">
		<div class="box box-success">
			<div class="box-header with-border">
				<h3 class="box-title"><i class="fa fa-list"></i> Data Pendukung Lain-Lain</h3>
				<div class="box-tools pull-right">
					<button class="btn btn-box-tool" onclick="reload_table('table_data')" data-toggle="tooltip" title="Refresh Table"><i class="fas fa-sync"></i></button>
					<button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
					<button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i class="fa fa-times"></i></button>
				</div>
			</div>
			<div class="box-body">
				<div class="row">
					<div class="col-md-1"> </div>
					<div class="col-md-8">
						<div class="form-group">
							<label class="col-md-3 control-label">Periode Penggajian</label>
							<div class="col-md-9">
								<select class="form-control select2" name="periode" id="data_periode_cari" style="width: 100%;">
									<?php
										$periode = $this->payroll->getPeriodeBulananHarian($admin);
										echo '<option></option>';
										foreach ($periode as $p => $val) {
											echo '<option value="'.$p.'">'.$val.'</option>';
										}
									?>
								</select>
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="pull-right">
							<button type="button" onclick="tableData('search')" class="btn btn-success"><i class="fa fa-eye"></i> Lihat Data</button>
						</div>
					</div>
				</div><hr>
				<div class="row">
					<div class="col-md-12">
						<div class="row">
							<div class="col-md-12">
								<div class="pull-left">
									<?php 
										if (in_array($access['l_ac']['add'], $access['access'])) {
											echo '<button class="btn btn-success" id="btn_tambah" type="button" data-toggle="collapse" data-target="#add" aria-expanded="false" aria-controls="import" style="margin-right:5px;"><i class="fa fa-plus"></i> Tambah Data</button>';
										}
										// if (in_array($access['l_ac']['exp'], $access['access'])) {
										// 	echo '<button type="button" class="btn btn-warning" onclick="model_export()"><i class="fas fa-file-excel-o"></i> Export</button>';
										// }
									?>
									<?php if (in_array($access['l_ac']['exp'], $access['access'])) { ?>
										<div class="btn-group">
											<button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown"><i class="fas fa-file-export"></i> Export Import Data
											<span class="fa fa-caret-down"></span></button>
											<ul class="dropdown-menu">
												<li><a onclick="model_export()"><i class="fa fa-download"></i> Export Data</a></li>
												<li><a onclick="export_template()"><i class="fa fa-download"></i> Download Template</a></li>
												<li><a data-toggle="modal" data-target="#importPendukungLain"><i class="fa fa-upload"></i> Import Data</a></li>
											</ul>
										</div>
									<?php } ?>
								</div>
								<div class="pull-right" style="font-size: 8pt;">
									<i class="fa fa-toggle-on stat scc"></i> Aktif<br>
									<i class="fa fa-toggle-off stat err"></i> Tidak Aktif
								</div>
							</div>
						</div>
						<?php if(in_array($access['l_ac']['add'], $access['access'])){?>
							<div class="collapse" id="add">
								<input type="hidden" id="key_btn_tambah" value="0">
								<br>
								<div class="col-md-2"></div>
								<div class="col-md-8">
									<form id="form_add">
										<div class="form-group">
											<label>Nama Tunjangan Lainnya</label>
											<input type="text" id="data_nama_add" name="nama" class="form-control" placeholder="Masukkan nama" required="required">
										</div>
										<div class="form-group">
											<label>Karyawan</label>
											<select class="form-control select2" name="karyawan[]" id="data_karyawan_add"  onchange="getPeriodeFromKar('add',this.value)" style="width: 100%;"></select>
										</div>
										<div class="form-group">
											<label>Nominal</label>
											<input type="text" id="data_nominal_add" name="nominal" class="form-control field input-money" placeholder="Masukkan Nominal" min="0" value="Rp. 0" step="0.01" required="required">
										</div>
										<div class="form-group">
											<label>Sifat</label>
											<select class="form-control select2" name="sifat" id="data_sifat_add" style="width: 100%;" required="required">
												<option></option>
												<option value="penambah">Penambah</option>
												<option value="pengurang">Pengurang</option>
											</select>
										</div>
										<div class="form-group clearfix">
											<label>Kartu Hallo</label><br>
											<a id="hallo_no" style="font-size: 16pt;"><i class="far fa-square"></i></a>
											<a id="hallo_yes" style="font-size: 16pt;display: none;"><i class="fa fa-check-square"></i></a>
											<span style="padding-bottom: 9px;vertical-align: middle;font-size: 16pt;"><small class="text-muted"><font color="red">&nbsp;&nbsp;(Ceklist Jika Termasuk Kartu Hallo)</font></small></span><br>
											<input type="hidden" name="hallo" id="hallo_add" class="form-control" placeholder="Kartu Hallo" readonly>
										</div>
										<div class="form-group">
											<label>Keterangan</label>
											<textarea class="form-control" name="keterangan" id="data_keterangan_add" placeholder="Keterangan"></textarea>
										</div>
										<div id="div_bulanan" style="display:none">
											<div class="form-group">
												<label>Periode Penggajian</label>
												<select class="form-control select2" name="periode" id="data_periode_add" style="width: 100%;">
												</select>
											</div>
										</div>
										<div class="form-group">
											<button type="button" onclick="confr_add()" id="btn_add" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
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
						<table id="table_data" class="table table-bordered table-striped table-responsive" width="100%">
							<thead>
								<tr>
									<th>No.</th>
									<th>Kode</th>
									<th>Nama Pendukung</th>
									<th>Karyawan</th>
									<th>jabatan</th>
									<th>Nominal</th>
									<th>Sifat</th>
									<th>Keterangan</th>
									<th>Periode</th>
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
							<label class="col-md-6 control-label">Kode</label>
							<div class="col-md-6" id="data_kode_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Nama Pendukung Lainnya</label>
							<div class="col-md-6" id="data_nama_pendukung_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Nama</label>
							<div class="col-md-6" id="data_karyawan_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Jabatan</label>
							<div class="col-md-6" id="data_jabatan_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Nominal</label>
							<div class="col-md-6" id="data_nominal_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Sifat</label>
							<div class="col-md-6" id="data_sifat_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Kartu Hallo</label>
							<div class="col-md-6" id="data_hallo_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Keterangan</label>
							<div class="col-md-6" id="data_keterangan_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Periode</label>
							<div class="col-md-6" id="data_periode_view"></div>
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
					<input type="hidden" id="kode_periode" name="kode_periode_old" value="">
					<div class="form-group">
						<label>Nama Tunjangan Lainnya</label>
						<input type="text" id="data_nama_edit" name="nama" class="form-control" placeholder="Masukkan Nama" required="required">
					</div>
					<div class="form-group">
						<label>Karyawan</label>
						<select class="form-control select2" name="karyawan" id="data_karyawan_edit" onchange="getPeriodeFromKarEdit('edit',this.value)" style="width: 100%;" required="required">
							<option></option>
						</select>
					</div>
					<div class="form-group">
						<label>Nominal</label>
						<input type="text" id="data_nominal_edit" name="nominal" class="form-control field input-money" placeholder="Masukkan Nominal" min="0" value="Rp. 0" step="0.01" required="required">
					</div>
					<div class="form-group">
						<label>Sifat</label>
						<select class="form-control select2" name="sifat" id="data_sifat_edit" style="width: 100%;" required="required">
							<option></option>
							<option value="penambah">Penambah</option>
							<option value="pengurang">Pengurang</option>
						</select>
					</div>
					<div class="form-group clearfix">
						<label>Kartu Hallo</label><br>
						<a id="hallo_no_edit" style="font-size: 16pt;"><i class="far fa-square"></i></a>
						<a id="hallo_yes_edit" style="font-size: 16pt;display: none;"><i class="fa fa-check-square"></i></a>
						<span style="padding-bottom: 9px;vertical-align: middle;font-size: 16pt;"><small class="text-muted"><font color="red">&nbsp;&nbsp;(Ceklist Jika Termasuk Kartu Hallo)</font></small></span><br>
						<input type="hidden" name="hallo" id="hallo_edit" class="form-control" placeholder="Kartu Hallo" readonly>
					</div>
					<div class="form-group">
						<label>Keterangan</label>
						<textarea class="form-control" name="keterangan" id="data_keterangan_edit" placeholder="Keterangan"></textarea>
					</div>
					<div id="div_bulanan_edit" style="display:none">
						<div class="form-group">
							<label>Periode Penggajian</label>
							<select class="form-control select2" name="periode" id="data_periode_edit" style="width: 100%;">
							</select>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" onclick="do_edit()" id="btn_edit" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
				</div>
			</form>
		</div>
	</div>
</div>

<div id="rekap_mode" class="modal fade" role="dialog">
	<div class="modal-dialog modal-sm modal-warning">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title text-center"><i class="fas fa-file-export"></i> Rekap Mode</h4>
			</div>
			<div class="modal-body text-center">
				<div class="col-md-6" style="text-align: center;">
					<button type="button" class="btn btn-danger" onclick="do_rekap('pdf')"><i class="fas fa-file-pdf fa-fw"></i>PDF</button>
				</div>
				<div class="col-md-6" style="text-align: center;">
					<button type="button" class="btn btn-primary" onclick="do_rekap('excel')"><i class="fas fa-file-excel fa-fw"></i>Excel</button>
				</div>
				<input type="hidden" id="usage_rekap_mode" value="">
				<div class="clearfix"></div>
			</div>
			<div class="modal-footer">
			</div>
		</div>
	</div>
</div>

<!-- view -->
<div id="peringatan" class="modal fade" role="dialog">
	<div class="modal-dialog modal-md modal-danger">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h2 class="modal-title"><i class="fa fas fa-exclamation-triangle"></i>Peringatan</h2>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<div class="callout callout-info">Data Untuk Karyawan <b id="data_karyawan_peringatan"></b> Sudah ada. Yakin Simpan?</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn btn-info" onclick="do_add()"><i class="fa fa-edit"></i> Simpan</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="importPendukungLain" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content text-center">
			<div class="modal-header">
				<button type="button" class="close all_btn_import" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Import Data Dari Excel</h4>
			</div>
			<form id="form_import" action="#">
				<div class="modal-body">
					<div class="callout callout-info text-left">
						<b><i class="fa fa-bullhorn"></i> Bantuan</b><br>
						<ul>
							<li>Pastikan cell pada file Excel tidak ada yang di <b>MERGE</b>, jika ada yang di <b>MERGE</b> harap melakukan <b style="color: red">UNMERGE</b> terlebih dahulu!</li>
							<li>Gunakan File Template Excel yang telah anda Download dari <b>"Export Template"</b></li>
						</ul>
					</div>
					<div class="form-group">
						<label>Pilih Periode Penggajian</label>
						<select class="form-control select2" name="periode" id="data_periode_import" style="width: 100%;">
							<?php
							$periode = $this->model_master->getListPeriodePenggajian();
							echo '<option></option>';
							foreach ($periode as $p) {
								echo '<option value="'.$p->kode_periode_penggajian.'">'.$p->nama.' ('.$p->nama_sistem_penggajian.')</option>';
							}
							?>
						</select>
					</div>
					<p class="text-muted">File Data Presensi harus tipe *.xls, *.xlsx, *.csv, *.ods dan *.ots</p>
					<input id="uploadFilex" placeholder="Nama File" readonly="readonly" class="form-control" required="required">
					<span class="input-group-btn">
						<div class="fileUpload btn btn-warning btn-flat">
							<span><i class="fa fa-folder-open"></i> Pilih File</span>
							<input id="uploadBtnx" type="file" class="upload" name="file" onchange="checkFile('#uploadBtnx','#uploadFilex','#save')" />
						</div>
					</span>
				</div> 
				<div class="modal-footer">
					<div id="progress2" style="float: left;"></div>
					<button class="btn btn-primary all_btn_import" id="save" type="button" disabled style="margin-right: 4px;"><i class="fa fa-check-circle"></i> Upload</button>
					<button id="savex" type="submit" style="display: none;"></button>
					<button type="button" class="btn btn-default all_btn_import" data-dismiss="modal">Kembali</button>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- delete -->
<div id="modal_delete_partial"></div>
<script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
<script type="text/javascript">
	var url_select="<?php echo base_url('global_control/select2_global');?>";
	var table="data_pendukung_lain";
	var column="id_pen_lain";
	$(document).ready(function(){
		tableData('all');
		$('#btn_tambah').click(function(){
		});
		getSelect2("<?php echo base_url('master/master_pinjaman/employee')?>",'data_karyawan_add');
		$('#save').click(function(){
			$('.all_btn_import').attr('disabled','disabled');
			$('#progress2').html('<i class="fa fa-spinner fa-pulse fa-fw"></i> Mohon Tunggu, Data sedang di upload....')
			setTimeout(function () {
				$('#savex').click();
			},1000);
		})
		$('#form_import').submit(function(e){
			e.preventDefault();
			var data_add = new FormData(this);
			var urladd = "<?php echo base_url('cpayroll/import_pendukung_lain'); ?>";
			submitAjaxFile(urladd,data_add,'#importPendukungLain','#progress2','.all_btn_import');
			$('#table_data').DataTable().ajax.reload(function (){
				Pace.restart();
			});
		});
		$('#hallo_no').click(function(){
			$('#hallo_no').hide();
			$('#hallo_yes').show();
			$('#hallo_add').val('1');
		})
		$('#hallo_yes').click(function(){
			$('#hallo_yes').hide();
			$('#hallo_no').show();
			$('#hallo_add').val('0');
		})
	});
	function tableData(kode) {
		var periode = $('#data_periode_cari').val();
		$('#table_data').DataTable({
			ajax: {
				url: "<?php echo base_url('cpayroll/data_pendukung_lain/view_all/')?>",
				type: 'POST',
				data:{access:"<?php echo base64_encode(serialize($access));?>",periode:periode,kode:kode}
			},
			scrollX: true,
			bDestroy: true,
			scrollCollapse: true,
			columnDefs: [
			{   targets: 0, 
				width: '5%',
				render: function ( data, type, full, meta ) {
					return '<center>'+(meta.row+1)+'.</center>';
				}
			},
			{   targets: 10,
				width: '5%',
				render: function ( data, type, full, meta ) {
					return '<center>'+data+'</center>';
				}
			},
			{   targets: 11, 
				width: '10%',
				render: function ( data, type, full, meta ) {
					return '<center>'+data+'</center>';
				}
			},
			]
		});
	}
	function refreshCode() {
		kode_generator("<?php echo base_url('cpayroll/data_pendukung_lain/kode');?>",'data_kode_add');
	}
	function view_modal(id) { 
		var data={id_pen_lain:id, mode: 'view'};
		var callback=getAjaxData("<?php echo base_url('cpayroll/data_pendukung_lain/view_one')?>",data);
		$('#view').modal('show')
		$('.header_data').html(callback['nama']);
		$('#data_kode_view').html(callback['kode']);
		$('#data_nama_pendukung_view').html(callback['nama']);
		$('#data_hallo_view').html(callback['hallo']);
		$('#data_karyawan_view').html(callback['nama_karyawan']);
		$('#data_jabatan_view').html(callback['jabatan']);
		$('#data_nominal_view').html(callback['nominal']);
		$('#data_sifat_view').html(callback['sifat']);
		$('#data_keterangan_view').html(callback['keterangan']);
		$('#data_periode_view').html(callback['periode']);
		// $('#data_jenis_view').html(callback['jenis']);
		// $('#data_minggu_view').html(callback['minggu_view']);
		// $('#data_bulan_view').html(callback['bulan_view']);
		// $('#data_tahun_view').html(callback['tahun']);
		var status = callback['status'];
		if(status==1){
			var statusval = '<b class="text-success">Aktif</b>';
		}else{
			var statusval = '<b class="text-danger">Tidak Aktif</b>';
		}
		$('#data_status_view').html(statusval);
		$('#data_create_date_view').html(callback['create_date']+' WIB');
		$('#data_update_date_view').html(callback['update_date']+' WIB');
		$('input[name="data_id_view"]').val(callback['id']);
		$('#data_create_by_view').html(callback['nama_buat']);
		$('#data_update_by_view').html(callback['nama_update']);
	}
	function edit_modal() {
		getSelect2("<?php echo base_url('master/master_pinjaman/employee')?>",'data_karyawan_edit');
		var id = $('input[name="data_id_view"]').val();
		var data={id_pen_lain:id, mode: 'edit'};
		var callback=getAjaxData("<?php echo base_url('cpayroll/data_pendukung_lain/view_one')?>",data); 
		$('#view').modal('toggle');
		setTimeout(function () {
			$('#edit').modal('show');
		},600); 
		$('.header_data').html(callback['nama']+' ( '+callback['nik']+' )');
		$('#data_id_edit').val(callback['id']);
		$('#data_kode_edit_old').val(callback['kode']);

		$('#data_kode_edit').val(callback['kode']);
		$('#data_nama_edit').val(callback['nama']);
		// $('#data_karyawan_edit').val(callback['id_karyawan']).trigger('change');
		$('#data_nominal_edit').val(callback['nominal']);
		$('#data_sifat_edit').val(callback['sifat']).trigger('change');
		$('#data_jenis_edit').val(callback['e_jenis']).trigger('change');
		$('#data_keterangan_edit').val(callback['keterangan']);
		// $('#data_periode_edit').val(callback['periode']).trigger('change');
		$('#data_periode_edit').html(callback['periode']);
		$('#kode_periode').val(callback['kode_periode']);
		$('#minggu_edit').val(callback['minggu']).trigger('change');
		$('#bulan_edit').val(callback['bulan']).trigger('change');
		$('#data_tahun_edit').val(callback['tahun']).trigger('change');
		$('#data_karyawan_edit').val(callback['karyawan']).trigger('change');
		$('#hallo_edit').val(callback['hallo']);
		var hallo = callback['hallo'];
		if(hallo == '1'){
			$('#hallo_no_edit').hide();
			$('#hallo_yes_edit').show();
		}else{
			$('#hallo_yes_edit').hide();
			$('#hallo_no_edit').show();
		}
		$('#hallo_no_edit').click(function(){
			$('#hallo_no_edit').hide();
			$('#hallo_yes_edit').show();
			$('#hallo_edit').val('1');
		})
		$('#hallo_yes_edit').click(function(){
			$('#hallo_yes_edit').hide();
			$('#hallo_no_edit').show();
			$('#hallo_edit').val('0');
		})
	}
	function delete_modal(id) {
		var data={id_pen_lain:id, mode: 'view'};
		var callback=getAjaxData("<?php echo base_url('cpayroll/data_pendukung_lain/view_one')?>",data);
		var datax={table:table,column:column,id:id,nama:callback['nama']};
		loadModalAjax("<?php echo base_url('pages/load_modal_delete')?>",'modal_delete_partial',datax,'delete');
	}
	/*doing db transaction*/
	function do_status(id,data) {
		var data_table={status:data};
		var where={id_pen_lain:id};
		var datax={table:table,where:where,data:data_table};
		submitAjax("<?php echo base_url('global_control/change_status')?>",null,datax,null,null,'status');
		$('#table_data').DataTable().ajax.reload(function(){
			Pace.restart();
		});
	}
	function do_edit(){
		if($("#form_edit")[0].checkValidity()) {
			submitAjax("<?php echo base_url('cpayroll/edt_pendukung_lain')?>",'edit','form_edit',null,null);
			$('#table_data').DataTable().ajax.reload();
		}else{
			notValidParamx();
		} 
	}
	function confr_add() {
		var data = $('#form_add').serialize();
		var callback = getAjaxData("<?php echo base_url('cpayroll/check_emp_data_pendukung_lain')?>",data);
		if(callback['data'] == 'ada'){
			$('#peringatan').modal('show');
			$('#data_karyawan_peringatan').html(callback['karyawan']);
		}else if(callback['data'] == 'tidak'){
			do_add();
		}
	}
	function do_add(){
		if($("#form_add")[0].checkValidity()) {
			$('#peringatan').modal('hide');
			submitAjax("<?php echo base_url('cpayroll/add_pendukung_lain')?>",null,'form_add',"<?php echo base_url('cpayroll/data_pendukung_lain/kode');?>",'data_kode_add');
			$('#table_data').DataTable().ajax.reload(function(){
				Pace.restart();
			});
			// $('#form_add')[0].reset();
			// $('#data_karyawan_add').val([]).trigger('change');
			// $('#data_tahun_add').val('').trigger('change');
			// $('#data_periode_add').val('').trigger('change');
			refreshCode();
		}else{
			notValidParamx();
		} 
	}
	function getPeriodeFromKar(usage,value) {
		var data={idkar:value};
		var callback=getAjaxData("<?php echo base_url('master/master_pinjaman/periodeKar')?>",data);
		var value = callback['value'];
		if(value == 'HARIAN'){
			// $('#div_harian').show();
			// $('#div_bulanan').hide();
			$('#div_bulanan').show();
			$('#data_periode_'+usage).html(callback['select']);
		}else{
			// $('#div_harian').hide();
			$('#div_bulanan').show();
			$('#data_periode_'+usage).html(callback['select']);
		}
	}
	function getPeriodeFromKarEdit(usage,value) {
		var data={idkar:value};
		var callback=getAjaxData("<?php echo base_url('master/master_pinjaman/periodeKar')?>",data);
		var value = callback['value'];
		if(value == 'HARIAN'){
			// $('#div_harian_edit').show();
			// $('#div_bulanan_edit').hide();
			$('#div_bulanan_edit').show();
			$('#data_periode_'+usage).html(callback['select']);
		}else{
			// $('#div_harian_edit').hide();
			$('#div_bulanan_edit').show();
			$('#data_periode_'+usage).html(callback['select']);
		}
	}
	function model_export() {
		$('#rekap_mode').modal('show');
	}

	function do_rekap(file) {
		if(file == 'pdf'){
			window.open('<?php echo base_url()."pages/rekap_data_pendukung_lain/"; ?>','_blank');
		}else{
			window.location.href = "<?php echo base_url('rekap/export_data_pendukung_lain')?>";
		}
	}
	function export_template() {
		window.location.href = "<?php echo base_url('rekap/export_template_data_pendukung_lain')?>";
	}
	function checkFile(idf,idt,btnx) {
		var fext = ['xls', 'xlsx', 'csv', 'ods', 'ots'];
		pathFile(idf,idt,fext,btnx);
	}
</script>