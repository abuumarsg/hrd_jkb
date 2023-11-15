<div class="content-wrapper">
	<section class="content-header">
		<h1>
			<i class="fas fa-indent"></i> Data
			<small>Transaksi Non Karyawan</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fa fas fa-tachometer-alt"></i> Dashboard</a></li>
			<li class="active"><i class="fas fa-indent"></i> Transaksi Non Karyawan</li>
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
								<div class="col-md-6">
									<div class="form-group">
										<label>Bulan</label>
										<select class="form-control select2" id="bulan_export" name="bulan_export" style="width: 100%;">
											<option></option>
											<?php
											for ($i=1; $i <= 12; $i++) { 
												echo '<option value="'.$this->formatter->zeroPadding($i).'" '.$select.'>'.$this->formatter->getNameOfMonth($i).'</option>'; } ?>
										</select>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label>Tahun</label>
										<select class="form-control select2" id="tahun_export" name="tahun_export" style="width: 100%;">
											<option></option>
											<?php
											$year = $this->formatter->getYear();
											foreach ($year as $yk => $yv) {
												echo '<option value="'.$yk.'" '.$select.'>'.$yv.'</option>'; } ?>
										</select>
									</div>
								</div>
							</div>
							<div class="box-footer">
								<div class="col-md-12">
									<div class="pull-right">
										<button type="button" onclick="tableData('search')" class="btn btn-success"><i class="fa fa-eye"></i> Lihat Data</button>
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
				<div class="box box-info">
					<div class="box-header with-border">
						<h3 class="box-title"><i class="fas fa-indent"></i> Data Transaksi Non Karyawan</h3>
						<div class="box-tools pull-right">
							<button class="btn btn-box-tool" onclick="tableData('all');resetselect2();" data-toggle="tooltip" title="Refresh Table"><i class="fas fa-sync"></i></button>
							<button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
							<button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i class="fa fa-times"></i></button>
						</div>
					</div>
					<div class="box-body">
						<div id="accordion">
							<div class="panel">
								<?php if (in_array($access['l_ac']['add'], $access['access'])) {
									echo '<a href="#tambah" data-toggle="collapse" id="btn_tambah" data-parent="#accordion" class="btn btn-success "><i class="fa fa-plus"></i> Tambah Data</a> ';}
									if (in_array($access['l_ac']['rkp'], $access['access'])) {
										echo '<button type="button" onclick="rekap()" class="btn btn-warning" ><i class="fa fa-file-excel-o"></i> Export Data</button> ';}
									if (in_array($access['l_ac']['add'], $access['access'])) { ?>
									<div id="tambah" class="collapse">
										<br>
										<div class="box box-success">
											<div class="box-header with-border">
												<h3 class="box-title"><i class="fa fa-plus"></i> Tambah Transaksi Non Karyawan</h3>
											</div>
											<form id="form_add" class="form-horizontal">
												<div class="box-body">
													<div class="callout callout-info"><label><i class="fa fa-info-circle"></i> Bantuan</label><br> Untuk meminimalisir Nomor Surat Sama, Silahkan klik <b><i class="fa fa-refresh"></i></b> disamping kolom nomor surat sebelum menyimpan</div>
													<div class="col-md-6">
														<div class="row">
															<div class="form-group">
																<input type="hidden" id="id_non" name="id_non" value="">
																<label class="col-sm-3 control-label" style="vertical-align: middle;">Nomor SK</label>
																<div class="col-sm-8">
																	<input type="text" name="no_sk" id="no_sk_add" class="form-control" placeholder="Nomor SK" required="required">
																</div>
																<div class="col-sm-1">
																	<span class="control-label" style="vertical-align: middle;"><i class="fa fa-refresh" style="font-size: 16px;cursor:pointer;" onclick="refreshCode()"></i></span>
																</div>
															</div>
															<div class="form-group">
																<label class="col-sm-3 control-label">NIK</label>
																<div class="col-sm-7">
																	<input type="text" name="nik" id="nik" class="form-control" placeholder="Nomor Non Karyawan" required="required" readonly="readonly">
																</div>
																<div class="col-sm-1">
																	<button type="button" class="btn btn-default btn-sm" onclick="pilih_non_karyawan()">
																	<i class ="fa fa-plus"></i></button>
																</div>
															</div>
															<div class="form-group">
																<label class="col-sm-3 control-label">Nama Instansi / Non Karyawan</label>
																<div class="col-sm-9">
																	<input type="text" name="nama" id="nama" class="form-control" placeholder="Nama Karyawan" readonly="readonly">
																</div>
															</div>
															<div class="form-group">
																<label class="col-sm-3 control-label">No Telpon</label>
																<div class="col-sm-9">
																	<input type="text" name="no_telp" id="no_telp" class="form-control" placeholder="No Telpon" readonly="readonly">
																</div>
															</div>
															<div class="form-group">
																<label class="col-sm-3 control-label">Alamat</label>
																<div class="col-sm-9">
																	<input type="text" name="alamat" id="alamat" class="form-control" placeholder="Alamat" readonly="readonly">
																</div>
															</div>
															<div class="form-group">
																<label class="col-sm-3 control-label">Tanggal</label>
																<div class="col-sm-9">
																	<div class="has-feedback">
																		<span class="fa fa-calendar form-control-feedback"></span>
																		<input type="text" name="tanggal" class="form-control pull-right date-picker" placeholder="Tanggal" readonly="readonly" required="required">
																	</div>
																</div>
															</div>
														</div>
													</div>
													<div class="col-md-6">
														<div class="form-group">
															<label class="col-sm-3 control-label">Kegiatan</label>
															<div class="col-sm-9">
																<input type="text" required="required" name="kegiatan" class="form-control" placeholder="Untuk Kegiatan" required="required">
															</div>
														</div>
														<div class="form-group">
															<label class="col-sm-3 control-label">Besar Biaya</label>
															<div class="col-sm-9">
																<input type="text" required="required" name="biaya" class="form-control input-money" placeholder="Besar Biaya (Rp)" required="required">
															</div>
														</div>
														<div class="form-group">
															<label class="col-sm-3 control-label">Besar THR<br>(Jika Ada)</label>
															<div class="col-sm-9">
																<input type="text" name="thr" class="form-control input-money" placeholder="Besar THR (Rp)">
															</div>
														</div>
														<div class="form-group">
															<label class="col-sm-3 control-label">Keterangan</label>
															<div class="col-sm-9">
																<textarea name="keterangan" class="form-control" placeholder="Keterangan"></textarea>
															</div>
														</div>
														<div class="form-group">
															<label class="col-sm-3 control-label">Mengetahui</label>
															<div class="col-sm-9">
																<select class="form-control select2" name="mengetahui" id="data_mengetahui_add" required="required" style="width: 100%;"></select>
															</div>
														</div>
														<div class="form-group">
															<label class="col-sm-3 control-label">Menyetujui</label>
															<div class="col-sm-9">
																<select class="form-control select2" name="menyetujui" id="data_menyetujui_add" required="required" style="width: 100%;"></select>
															</div>
														</div>
													</div>
												</div>
												<div class="box-footer">
													<div class="pull-right">
														<button type="submit" id="simpan" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
													</div>
												</div>
											</form>
										</div>
									</div>
								<?php } ?>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="callout callout-info"><label><i class="fa fa-info-circle"></i> Bantuan</label><br>Pilih <b>NIK</b> untuk melihat detail data transaksi non karyawan maupun melakukan update pada data data transaksi non karyawan karyawan</div>
								<table id="table_data" class="table table-bordered table-striped" width="100%">
									<thead>
										<tr>
											<th>No</th>
											<th>NIK</th>
											<th>Nama</th>
											<th>Nomor</th>
											<th>Tanggal</th>
											<th>Biaya</th>
											<th>THR</th>
											<th>Kegiatan</th>
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
			<!-- MODAL PILIH KARYAWAN -->
<div class="modal modal-default fade" id="modal_pilih_karyawan" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h2 class="modal-title">Pilih Karyawan</h2>
			</div>
			<div class="modal-body">
				<table id="table_pilih" class="table table-bordered table-striped table-responsive" width="100%">
					<thead>
						<tr>
							<th width="7%">NO</th>
							<th width="25%">NIK</th>
							<th width="25%">Nama</th>
							<th width="25%">No Telp</th>
							<th>Alamat</th>
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
							<div class="col-md-6" id="data_nomor_view"></div>
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
							<label class="col-md-6 control-label">No Telpon</label>
							<div class="col-md-6" id="data_no_telp_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Alamat</label>
							<div class="col-md-6" id="data_alamat_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Tanggal</label>
							<div class="col-md-6" id="data_tanggal_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Biaya</label>
							<div class="col-md-6" id="data_biaya_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">THR</label>
							<div class="col-md-6" id="data_thr_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Kegiatan</label>
							<div class="col-md-6" id="data_kegiatan_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Keterangan</label>
							<div class="col-md-6" id="data_keterangan_view"></div>
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
				<!-- <hr>
				<div class="row">
					<div class="col-md-12">
						<div id="data_tabel_view"></div>
					</div>
				</div> -->
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
			<div class="modal-body">
				<form id="form_edit">
					<div class="col-md-12">
						<div class="col-md-6">
							<input type="hidden" id="data_id_edit" name="id" value="">
							<div class="form-group">
								<label>Nomor</label>
								<input type="text" placeholder="Masukkan Nomor" id="data_nomor_edit" name="nomor" value="" class="form-control" required="required">
							</div>
							<div class="form-group">
								<label>NIK</label>
								<input type="text" placeholder="NIK" id="data_nik_edit" name="nik" value="" class="form-control" readonly="readonly">
							</div>
							<div class="form-group">
								<label>Nama Instansi / Non Karyawan</label>
								<input type="text" placeholder="Nama Instansi / Non Karyawan" id="data_nama_edit" name="nama" value="" class="form-control" readonly="readonly">
							</div>
							<div class="form-group">
								<label>Tanggal</label>
								<div class="has-feedback">
									<input type="text" id="data_tanggal_edit" value="" name="tanggal" class="form-control date-picker" placeholder="Tanggal Berlaku" readonly="readonly">
								</div>
							</div>
							<div class="form-group">
								<label>Kegiatan</label>
								<input type="text" placeholder="Masukkan Nama Bagian" id="data_kegiatan_edit" name="kegiatan" value="" class="form-control" required="required">
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Besar Biaya</label>
								<input type="text" placeholder="Besar Biaya" id="data_biaya_edit" name="biaya" value="" class="form-control input-money" required="required">
							</div>
							<div class="form-group">
								<label>Besar THR (Jika Ada)</label>
								<input type="text" placeholder="Besar THR" id="data_thr_edit" name="thr" value="" class="form-control input-money">
							</div>
							<div class="form-group">
								<label>Mengetahui</label>
								<select class="form-control select2" name="mengetahui" id="data_mengetahui_edit" style="width: 100%;"></select>
								<input type="text" id="hide_text_edit" class="hidex-text">
							</div>
							<div class="form-group">
								<label>Menyetujui</label>
								<select class="form-control select2" name="menyetujui" id="data_menyetujui_edit" style="width: 100%;"></select>
								<input type="text" id="hide_text_edit" class="hidex-text">
							</div>
							<div class="form-group">
								<label>Keterangan</label>
								<textarea class="form-control" name="keterangan" id="data_keterangan_edit" placeholder="Keterangan"></textarea>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
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
	var table="transaksi_non_karyawan";
	var column="id";
	$(document).ready(function(){
		submitForm('form_add');
		submitForm('form_edit');
		refreshCode();
		tableData('all');
		getSelect2("<?php echo base_url('employee/mutasi_jabatan/employee')?>",'data_mengetahui_add,#data_menyetujui_add');
	});
	function tableData(kode) {
		$('input[name="param"').val(kode);
		// $('#table_data').DataTable().destroy();
		if(kode=='all'){
			var datax = {param:'all',access:"<?php echo $this->codegenerator->encryptChar($access);?>"};
		}else{
			var bulan = $('#bulan_export').val();
			var tahun = $('#tahun_export').val();
			var datax = {param:'search',bulan:bulan,tahun:tahun,access:"<?php echo $this->codegenerator->encryptChar($access);?>"};
		}
		$('#table_data').DataTable( {
			ajax: {
				url: "<?php echo base_url('employee/transaksi_non_karyawan/view_all/')?>",
				type: 'POST',
				data: datax
			},
			bDestroy: true,
			scrollX: true,
			columnDefs: [
			{   targets: 0, 
				width: '5%',
				render: function ( data, type, full, meta ) {
					return '<center>'+(meta.row+1)+'.</center>';
				}
			},
			{   targets: 1,
				width: '10%',
				render: function ( data, type, full, meta ) {
					return data;
				}
			},
			{   targets: 7,
				width: '20%',
				render: function ( data, type, full, meta ) {
					return ''+data+'';
				}
			},
			{   targets: 8, 
				width: '7%',
				render: function ( data, type, full, meta ) {
					return '<center>'+data+'</center>';
				}
			},
			]
		});
	}
	function submitForm(form) {
		$('#'+form).validator().on('submit', function (e) {
			if (e.isDefaultPrevented()) {
				notValidParamx();
			} else {
				e.preventDefault(); 
				if(form=='form_add'){
					do_add()
				}else{
					do_edit()
				}
			}
		})
	}
	function do_edit(){
		if($("#form_edit")[0].checkValidity()) {
			submitAjax("<?php echo base_url('employee/edit_transaksi_non_karyawan')?>",'edit','form_edit',null,null);
			$('#table_data').DataTable().ajax.reload();
		}else{
			refreshCode();
			notValidParamx();
		} 
	}
	function resetselect2() {
		$('#bagian_export').val('').trigger('change');
		$('#unit_export').val('').trigger('change');
		$('#bulan_export').val('').trigger('change');
		$('#tahun_export').val('').trigger('change');
	}
	function resetselectAdd() {
		$('#data_statusmutasi_add').val('').trigger('change');
		$('#data_jabatanbaru_add').val('').trigger('change');
		$('#data_lokasibaru_add').val('').trigger('change');
		$('#data_mengetahui_add').val('').trigger('change');
		$('#data_menyetujui_add').val('').trigger('change');
	}
	function pilih_non_karyawan() {
		$('#modal_pilih_karyawan').modal('toggle');
		$('#modal_pilih_karyawan .header_data').html('Pilih Data Non Karyawan');
		$('#table_pilih').DataTable( {
			ajax: "<?php echo base_url('employee/pilih_non_karyawan')?>",
			scrollX: true,
			destroy: true,
			columnDefs: [
			{   targets: 0, 
				width: '10%',
				render: function ( data, type, full, meta ) {
					return '<center>'+(meta.row+1)+'.</center>';
				}
			},
			{   targets: 1,
				render: function ( data, type, full, meta ) {
					return data;
				}
			},
			{   targets: 2,
				render: function ( data, type, full, meta ) {
					return data;
				}
			},
			{   targets: 3,
				render: function ( data, type, full, meta ) {
					return data;
				}
			},
			{   targets: 4,
				render: function ( data, type, full, meta ) {
					return data;
				}
			},
			]
		});
	}
	function refreshCode() {
		kode_generator("<?php echo base_url('employee/transaksi_non_karyawan/kode');?>",'no_sk_add');
	}
	function view_modal(id) {
		var data={id:id};
		var callback=getAjaxData("<?php echo base_url('employee/transaksi_non_karyawan/view_one')?>",data);  
		$('#view').modal('show');
		$('.header_data').html(callback['nama']);
		$('#data_nik_view').html(callback['nik']);
		$('#data_nomor_view').html(callback['nomor']);
		$('#data_nama_view').html(callback['nama']);
		$('#data_no_telp_view').html(callback['alamat']);
		$('#data_alamat_view').html(callback['no_telp']);
		$('#data_tanggal_view').html(callback['tanggal']);
		$('#data_biaya_view').html(callback['biaya']);
		$('#data_thr_view').html(callback['thr']);
		$('#data_kegiatan_view').html(callback['kegiatan']);
		$('#data_keterangan_view').html(callback['keterangan']);
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
		$('#data_tabel_view').html(callback['tabel']);
		$('#data_loker_view').html(callback['loker']);
		$('#data_jabatan_view').html(callback['jabatan']);
	}
	function edit_modal() {
		getSelect2("<?php echo base_url('employee/mutasi_jabatan/employee')?>",'data_mengetahui_edit,#data_menyetujui_edit');
		var id = $('input[name="data_id_view"]').val();
		var data={id:id};
		var callback=getAjaxData("<?php echo base_url('employee/transaksi_non_karyawan/view_one')?>",data); 
		$('#view').modal('toggle');
		setTimeout(function () {
		$('#edit').modal('show');
		},600); 
		$('.header_data').html(callback['nama']);
		$('#data_id_edit').val(callback['id']);
		$('#data_nomor_edit').val(callback['nomor']);
		$('#data_nik_edit').val(callback['nik']);
		$('#data_nama_edit').val(callback['nama']);
		$('#data_tanggal_edit').val(callback['tgl_sk_e']);
		$('#data_kegiatan_edit').val(callback['kegiatan']);
		$('#data_biaya_edit').val(callback['biaya']);
		$('#data_thr_edit').val(callback['thr']);
		$('#data_mengetahui_edit').val(callback['mengetahui']);
		$('#data_menyetujui_edit').val(callback['menyetujui']);
		$('#data_keterangan_edit').val(callback['keterangan']);
	}
	function delete_modal(id) {
		var data={id:id};
		var callback=getAjaxData("<?php echo base_url('employee/transaksi_non_karyawan/view_one')?>",data);
		var datax={table:table,column:'id',id:callback['id'],nama:callback['nama']};
		loadModalAjax("<?php echo base_url('pages/load_modal_delete')?>",'modal_delete_partial',datax,'delete');
	}
	function do_add(){
		if($("#form_add")[0].checkValidity()) {
			submitAjax("<?php echo base_url('employee/add_transaksi_non_karyawan')?>",null,'form_add',null,null);
			$('#table_data').DataTable().ajax.reload(function(){
				Pace.restart();
			});
			$('#form_add')[0].reset();
			refreshCode();
			resetselectAdd();
		}else{
			notValidParamx();
		} 
	}
	function rekap() {
		var data=$('#form_filter').serialize();
		window.location.href = "<?php echo base_url('rekap/data_transaksi_non_karyawan')?>?"+data;
	}
	$(document).on('click', '.pilih', function (e1) {
		$("#id_non").val($(this).data('id_non'));
		$("#nik").val($(this).data('nik'));
		$("#nama").val($(this).data('nama'));
		$("#no_telp").val($(this).data('no_telp'));
		$("#alamat").val($(this).data('alamat'));
		$('#modal_pilih_karyawan').modal('hide');
	});
</script> 
