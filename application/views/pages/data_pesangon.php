<div class="content-wrapper">
	<section class="content-header">
		<h1>
			<i class="fas fa-clipboard-list"></i> Data
			<small> Data Pesangon dan Bonus</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fa fas fa-tachometer-alt"></i> Dashboard</a></li>
			<li class="active"><i class="fas fa-clipboard-list"></i> Data Pesangon dan Bonus</li>
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
								<div class="col-md-3"></div>
								<div class="col-md-6">
									<div class="form-group">
										<label>Pilih Tanggal</label>
										<div class="has-feedback">
											<span class="fa fa-calendar form-control-feedback"></span>
											<input type="text" class="form-control date-range-notime" id="tanggal" name="tanggal" placeholder="Tanggal">
										</div>
									</div>
								</div>
							</div>
							<div class="box-footer">
								<div class="col-md-12">
									<div class="pull-right">
										<button type="button" onclick="kode_akun('search')" class="btn btn-success"><i class="fa fa-eye"></i> Lihat Data</button>
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
						<h3 class="box-title"><i class="fas fa-clipboard-list"></i> Data Pesangon dan Bonus</h3>
						<div class="box-tools pull-right">
							<button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
							<button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i class="fa fa-times"></i></button>
						</div>
					</div>
					<div class="box-body">
						<div class="row">
							<div class="col-md-12">
								<div class="row">
									<div class="col-md-12">
										<div class="pull-left">
											<?php if (in_array($access['l_ac']['add'], $access['access'])) {
												echo '<a href="#add_kode_akun" data-toggle="collapse"  data-parent="#accordions" class="btn btn-success"><i class="fa fa-plus"></i> Tambah Data</a>';
												}
												if (in_array('EXP', $access['access']) || in_array('IMP', $access['access'])) {
													echo '<div class="btn-group">
														<button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown" style="margin-left: 5px;float: left;"><i class="fas fa-file-excel-o"></i> Export Import
														<span class="fa fa-caret-down"></span></button>
														<ul class="dropdown-menu">
														<li><a onclick=do_export()>Export Template</a></li>
														<li><a onclick=do_import()>Import Data</a></li>
														</ul>
													</div>';
												}
											?>
										</div>
										<div class="pull-right" style="font-size: 8pt;">
											<i class="fa fa-toggle-on stat scc"></i> Aktif<br>
											<i class="fa fa-toggle-off stat err"></i> Tidak Aktif
										</div>
									</div>
								</div>
								<?php if(in_array($access['l_ac']['add'], $access['access'])){?>
									<div class="collapse" id="add_kode_akun">
										<form id="form_add" class="form-horizontal">
											<div class="box-body">
												<!-- <div class="callout callout-info"><label><i class="fa fa-info-circle"></i> Bantuan</label><br> Untuk meminimalisir Nomor Surat Sama, Silahkan klik <b><i class="fa fa-refresh"></i></b> disamping kolom nomor surat sebelum menyimpan</div> -->
												<div class="col-md-6">
													<div class="row">
														<div class="form-group">
															<label class="col-sm-3 control-label" style="vertical-align: middle;">Nomor Bukti</label>
															<div class="col-sm-9">
																<input type="text" name="no_bukti" id="no_bukti" class="form-control" placeholder="Nomor Bukti" required="required">
															</div>
															<!-- <div class="col-sm-1">
																<span class="control-label" style="vertical-align: middle;"><i class="fa fa-refresh" style="font-size: 16px;cursor:pointer;" onclick="refreshCode()"></i></span>
															</div> -->
														</div>
														<div class="form-group">
															<label class="col-sm-3 control-label">Tanggal Transaksi</label>
															<div class="col-sm-9">
																<div class="has-feedback">
																	<span class="fa fa-calendar form-control-feedback"></span>
																	<input type="text" name="tanggal" class="form-control pull-right date-picker" placeholder="Tanggal Transaksi" readonly="readonly" required="required">
																</div>
															</div>
														</div>
														<div class="form-group">
															<label class="col-sm-3 control-label">Pilih Karyawan</label>
															<div class="col-sm-9">
																<select class="form-control select2" name="karyawan[]" id="data_karyawan_add" multiple="multiple" required="required" style="width: 100%;"></select>
															</div>
														</div>
														<div class="form-group">
															<label class="col-sm-3 control-label">No Akun</label>
															<div class="col-sm-9">
																<select class="form-control select2" name="no_akun" id="data_no_akun_add" required="required" style="width: 100%;"></select>
															</div>
														</div>
														<div class="form-group">
															<label class="col-sm-3 control-label">Nilai Bayar</label>
															<div class="col-sm-9">
																<input type="text" name="nilai_bayar" id="nilai_bayar" class="form-control input-money" placeholder="Nilai Bayar" required="required">
															</div>
														</div>
													</div>
												</div>
												<div class="col-md-6">
													<div class="row">
														<div class="form-group">
															<label class="col-sm-3 control-label">Nomor Proyek</label>
															<div class="col-sm-9">
																<input type="text" name="no_proyek" id="no_proyek" class="form-control" placeholder="Nomor Proyek">
															</div>
														</div>
														<div class="form-group">
															<label class="col-sm-3 control-label">Nama Proyek</label>
															<div class="col-sm-9">
																<input type="text" name="nama_proyek" id="nama_proyek" class="form-control" placeholder="Nama Proyek">
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
														<div class="form-group">
															<label class="col-sm-3 control-label">Catatan</label>
															<div class="col-sm-9">
																<textarea name="catatan" class="form-control" placeholder="Catatan / Keterangan"></textarea>
															</div>
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
								<?php } ?>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<table id="table_data" class="table table-bordered table-striped" width="100%">
									<thead>
										<tr>
											<th>No</th>
											<th>Nama Karyawan</th>
											<th>Tanggal</th>
											<th>No. Bukti</th>
											<th>No. Akun</th>
											<th>Catatan</th>
											<th>Nilai Bayar</th>
											<th>No. Proyek</th>
											<th>Nama Proyek</th>
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
	</section>	
</div>
<?php if(in_array($access['l_ac']['imp'], $access['access'])) { ?>
	<div class="modal fade" id="import" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
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
								<li>Anda <b>HARUS</b> menggunakan template EXCEL dari Export Template!</li>
							</ul>
						</div>
						<p class="text-muted text-center">File Data Presensi harus tipe *.xls, *.xlsx, *.csv, *.ods dan *.ots</p>
						<input id="uploadFilex" placeholder="Nama File" readonly="readonly" class="form-control" required="required">
						<span class="input-group-btn text-center">
							<div class="fileUpload btn btn-warning btn-flat">
								<span><i class="fa fa-folder-open text-center"></i> Pilih File</span>
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
<?php } ?>
<!-- view kode_akun-->
<div id="view_kode_akun" class="modal fade" role="dialog">
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
							<label class="col-md-6 control-label">Nama</label>
							<div class="col-md-6" id="data_nama_akun_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">No Akun</label>
							<div class="col-md-6" id="data_kode_akun_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Tanggal</label>
							<div class="col-md-6" id="data_tanggal_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">No Bukti</label>
							<div class="col-md-6" id="data_no_bukti_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Catatan</label>
							<div class="col-md-6" id="data_catatan_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Nilai Bayar</label>
							<div class="col-md-6" id="data_nominal_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">No Proyek</label>
							<div class="col-md-6" id="data_no_proyek_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Nama Proyek</label>
							<div class="col-md-6" id="data_nama_proyek_view"></div>
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
							<div class="col-md-6" id="data_status_akun_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Dibuat Tanggal</label>
							<div class="col-md-6" id="data_create_date_akun_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Diupdate Tanggal</label>
							<div class="col-md-6" id="data_update_date_akun_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Dibuat Oleh</label>
							<div class="col-md-6" id="data_create_by_akun_view">
							</div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Diupdate Oleh</label>
							<div class="col-md-6" id="data_update_by_akun_view">
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<?php if (in_array($access['l_ac']['edt'], $access['access'])) {
					echo '<button type="submit" class="btn btn-info" onclick="edit_modal_akun()"><i class="fa fa-edit"></i> Edit</button>';
				}?>
				<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
			</div>
		</div>
	</div>
</div>
<!-- edit kode_akun-->
<div id="edit_akun" class="modal fade" role="dialog">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h2 class="modal-title">Edit Data <b class="text-muted header_data"></b></h2>
			</div>
			<div class="modal-body">
				<form id="form_edit">
					<input type="hidden" id="data_id_edit" name="id" value="">
					<input type="hidden" id="data_id_karyawan_edit" name="id_karyawan" value="">
					<div class="form-group">
						<label>Nama Karyawan</label>
						<input type="text" placeholder="Masukkan Nama Karyawan" id="data_nama_karyawan_edit" name="karyawan" value="" class="form-control" readonly="readonly">
					</div>
					<div class="form-group">
						<label>Nomor Bukti</label>
						<input type="text" name="no_bukti" id="data_no_bukti_edit" class="form-control" placeholder="Nomor Bukti" required="required">
					</div>
					<div class="form-group">
						<label>Tanggal Transaksi</label>
						<div class="has-feedback">
							<span class="fa fa-calendar form-control-feedback"></span>
							<input type="text" name="tanggal" class="form-control pull-right date-picker" id="data_tanggal_edit" placeholder="Tanggal Transaksi" readonly="readonly" required="required">
						</div>
					</div>
					<div class="form-group">
						<label>Data Pesangon dan Bonus</label>
						<select class="form-control select2" name="no_akun" id="data_kode_akun_edit" required="required" style="width: 100%;"></select>
					</div>
					<div class="form-group">
						<label>Nilai Bayar</label>
						<input type="text" name="nilai_bayar" id="data_nominal_edit" class="form-control input-money" placeholder="Nilai Bayar" required="required">
					</div>
					<div class="form-group">
						<label>Nomor Proyek</label>
						<input type="text" name="no_proyek" id="data_no_proyek_edit" class="form-control" placeholder="Nomor Proyek">
					</div>
					<div class="form-group">
						<label>Nama Proyek</label>
						<input type="text" name="nama_proyek" id="data_nama_proyek_edit" class="form-control" placeholder="Nama Proyek">
					</div>
					<div class="form-group">
						<label>Mengetahui</label>
						<select class="form-control select2" name="mengetahui" id="data_mengetahui_edit" required="required" style="width: 100%;"></select>
					</div>
					<div class="form-group">
						<label>Menyetujui</label>
						<select class="form-control select2" name="menyetujui" id="data_menyetujui_edit" required="required" style="width: 100%;"></select>
					</div>
					<div class="form-group">
						<label>Catatan</label>
						<textarea name="catatan" class="form-control" id="data_catatan_edit" placeholder="catatan"></textarea>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" onclick="edit_kode_akun()" id="btn_edit_kode_akun" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- delete Data Pesangon dan Bonus-->
<div id="delete_kode_akun" class="modal fade" role="dialog">
	<div class="modal-dialog modal-sm modal-danger">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title text-center">Konfirmasi Hapus</h4>
			</div>
			<form id="form_delete_akun">
				<div class="modal-body text-center">
					<input type="hidden" id="data_column_delete_akun" name="column" value="id_akun">
					<input type="hidden" id="data_id_delete_akun" name="id" value="">
					<input type="hidden" id="data_table_delete_akun" name="table" value="data_pph_kode_akun">
					<p>Apakah anda yakin akan menghapus data dengan nama <b id="data_name_delete"
							class="header_data"></b> ?</p>
				</div>
			</form>
			<div class="modal-footer">
				<button type="button" onclick="do_delete_akun()" class="btn btn-primary"><i class="fa fa-trash"></i>
					Hapus</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
			</div>
		</div>
	</div>
</div>
<div id="modal_delete_partial"></div>
<script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
<script type="text/javascript">
	var url_select="<?php echo base_url('global_control/select2_global');?>";
	var table="data_pph_kode_akun";
	var column="id_akun";
	$(document).ready(function(){
		kode_akun('all');
		refreshCode();
		submitForm('form_add');
		// $('#save').click(function () {
		// 	$('.all_btn_import').attr('disabled', 'disabled');
		// 	$('#progress2').html('<i class="fa fa-spinner fa-pulse fa-fw"></i> Mohon Tunggu, Data sedang di upload....')
		// 	setTimeout(function () {
		// 		$('#savex').click();
		// 	}, 1000);
		// })
		// $('#form_import').submit(function (e) {
		// 	e.preventDefault();
		// 	var data_add = new FormData(this);
		// 	var urladd = "<?php //echo base_url('master/import_grade_perjalanan_dinas'); ?>";
		// 	submitAjaxFile(urladd, data_add, '#import', '#progress2', '.all_btn_import');
		// 	$('#table_data').DataTable().ajax.reload(function () {
		// 		Pace.restart();
		// 	});
		// });
		getSelect2("<?php echo base_url('presensi/data_presensi/employee')?>",'data_karyawan_add,#data_mengetahui_add,#data_menyetujui_add');
		getSelect2("<?php echo base_url('cpayroll/data_kode_akun/kode_akun')?>",'data_no_akun_add');
		$('#save').click(function(){
	        $('.all_btn_import').attr('disabled','disabled');
	        setTimeout(function () {
	        	$('#savex').click();
	        },1000);
		})
		$('#form_import').submit(function(e){
			e.preventDefault();
	        $('#progress2').html('<i class="fa fa-spinner fa-pulse fa-fw"></i> Mohon Tunggu, Data sedang di upload....');
			$('#progress2').show();
			var data_add = new FormData(this);
			var urladd = "<?php echo base_url('cpayroll/import_pph_kode_akun'); ?>";
			submitAjaxFile(urladd,data_add, null, null,'.all_btn_import','table_data');
			$('#table_data').DataTable().ajax.reload(function (){
				Pace.restart();
			});
			$('#import').modal('hide');
			$('#progress2').hide();
		});
	});
  	function checkFile(idf,idt,btnx) {
  		var fext = ['xls', 'xlsx', 'csv', 'ods', 'ots'];
  		pathFile(idf,idt,fext,btnx);
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
	function refreshCode() {
		$('#data_karyawan_add').val([]).trigger('change');
		$('#data_mengetahui_add').val([]).trigger('change');
		$('#data_menyetujui_add').val([]).trigger('change');
		$('#data_no_akun_add').val([]).trigger('change');
	}
//========== Data Pesangon dan Bonus Data Pesangon dan Bonus ===================
	function kode_akun(kode) {
		$('#table_data').DataTable().destroy();
		var data = $('#form_filter').serialize();
		var datax = {form:data,kode:kode,access:"<?php echo $this->codegenerator->encryptChar($access);?>"};
		$('#table_data').DataTable( {
			ajax: {
				url: "<?php echo base_url('cpayroll/data_kode_akun/view_all/')?>",
				type: 'POST',
				data:datax,
			},
			scrollX: true,
			bDestroy: true,
			scrollCollapse: true,
			fixedColumns:   {
				leftColumns: 3,
				rightColumns: 1
			},
			columnDefs: [
			{   targets: 0, 
				width: '5%',
				render: function ( data, type, full, meta ) {
					return '<center>'+(meta.row+1)+'.</center>';
				}
			},
			{   targets: 4,
				width: '15%',
				render: function ( data, type, full, meta ) {
					return data;
				}
			},
			{   targets: 5, 
				width: '20%',
				render: function ( data, type, full, meta ) {
					return data;
				}
			},
			{   targets: [9,10,11],
				width: '10%',
				render: function ( data, type, full, meta ) {
					return '<center>'+data+'</center>';
				}
			},
			]
		});
	}
	function view_kode_akun(id) {
		var data={id_akun:id};
		var callback=getAjaxData("<?php echo base_url('cpayroll/data_kode_akun/view_one')?>",data);  
		$('#view_kode_akun').modal('show');
		$('.header_data').html(callback['nama_karyawan']);
		$('#data_kode_akun_view').html(callback['kode']);
		$('#data_nama_akun_view').html(callback['nama_karyawan']);
		$('#data_tanggal_view').html(callback['tanggal']);
		$('#data_no_bukti_view').html(callback['no_bukti']);
		$('#data_catatan_view').html(callback['catatan']);
		$('#data_nominal_view').html(callback['nominal']);
		$('#data_no_proyek_view').html(callback['no_proyek']);
		$('#data_nama_proyek_view').html(callback['nama_proyek']);
		$('#data_mengetahui_view').html(callback['nama_mengetahui']);
		$('#data_menyetujui_view').html(callback['nama_menyetujui']);
		var status = callback['status'];
		if(status==1){
			var statusval = '<b class="text-success">Aktif</b>';
		}else{
			var statusval = '<b class="text-danger">Tidak Aktif</b>';
		}
		$('#data_status_akun_view').html(statusval);
		$('#data_create_date_akun_view').html(callback['create_date']+' WIB');
		$('#data_update_date_akun_view').html(callback['update_date']+' WIB');
		$('input[name="data_id_view"]').val(callback['id']);
		$('#data_create_by_akun_view').html(callback['nama_buat']);
		$('#data_update_by_akun_view').html(callback['nama_update']);
	}
	function edit_modal_akun() {
		getSelect2("<?php echo base_url('cpayroll/data_kode_akun/kode_akun')?>",'data_kode_akun_edit');
		getSelect2("<?php echo base_url('presensi/data_presensi/employee')?>",'data_mengetahui_edit,#data_menyetujui_edit');
		var id = $('input[name="data_id_view"]').val();
		var data={id_akun:id};
		var callback=getAjaxData("<?php echo base_url('cpayroll/data_kode_akun/view_one')?>",data); 
		$('#view_kode_akun').modal('toggle');
		setTimeout(function () {
			$('#edit_akun').modal('show');
		},600); 
		$('.header_data').html(callback['nama_karyawan']);
		$('#data_id_edit').val(callback['id']);
		$('#data_id_karyawan_edit').val(callback['id_karyawan']);
		$('#data_nama_karyawan_edit').val(callback['nama_karyawan']);
		$('#data_kode_akun_edit').val(callback['kode_edit']).trigger('change');
		$('#data_tanggal_edit').val(callback['tanggal']);
		$('#data_no_bukti_edit').val(callback['no_bukti']);
		$('#data_catatan_edit').val(callback['catatan']);
		$('#data_nominal_edit').val(callback['nominal']);
		$('#data_no_proyek_edit').val(callback['no_proyek']);
		$('#data_nama_proyek_edit').val(callback['nama_proyek']);
		$('#data_mengetahui_edit').val(callback['mengetahui']).trigger('change');
		$('#data_menyetujui_edit').val(callback['menyetujui']).trigger('change');
	}
	function edit_kode_akun(){
		if($("#form_edit")[0].checkValidity()) {
			submitAjax("<?php echo base_url('cpayroll/edit_data_kode_akun')?>",'edit_akun','form_edit',null,null);
			$('#table_data').DataTable().ajax.reload();
		}else{
			notValidParamx();
		} 
	}
	function do_status_kode_akun(id,data) {
		var data_table={status:data};
		var where={id_akun:id};
		var datax={table:table,where:where,data:data_table};
		submitAjax("<?php echo base_url('global_control/change_status')?>",null,datax,null,null,'status');
		$('#table_data').DataTable().ajax.reload();
	}
	function delete_kode_akun(id) {
		var data={id_akun:id};
		$('#delete_kode_akun').modal('toggle');
		var callback=getAjaxData("<?php echo base_url('cpayroll/data_kode_akun/view_one')?>",data);
		$('#delete_kode_akun #data_id_delete_akun').val(callback['id']);
		$('#delete_kode_akun .header_data').html(callback['nama_karyawan']);
	}
	function do_delete_akun(){
		submitAjax("<?php echo base_url('global_control/delete')?>",'delete_kode_akun','form_delete_akun',null,null);
		$('#table_data').DataTable().ajax.reload(function (){
			Pace.restart();
		});
	}
	function do_add(){
		if($("#form_add")[0].checkValidity()) {
			submitAjax("<?php echo base_url('cpayroll/add_data_kode_akun')?>",null,'form_add',null,null);
			$('#table_data').DataTable().ajax.reload(function(){
				Pace.restart();
			});
			$('#form_add')[0].reset();
			refreshCode();
		}else{
			notValidParamx();
		} 
	}
	function do_import() {
		$('#import').modal('show');
	}
	function do_export() {
		window.location.href = "<?php echo base_url('rekap/export_template_pph_kode_akun')?>";
	}
</script>
