<div class="content-wrapper">
	<section class="content-header">
		<h1>
			<i class="fa fas fa-file-contract"></i> Data
			<small>Perjanjian Kerja</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fa fas fa-tachometer-alt"></i> Dashboard</a></li>
			<li class="active"><i class="fa fas fa-file-contract"></i> Perjanjian Kerja</li>
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
										<label>Pilih Bagian</label>
										<select class="form-control select2" id="bagian_export" name="bagian_export" style="width: 100%;"></select>
									</div>
									<div class="form-group">
										<label>Pilih Lokasi Kerja</label>
										<select class="form-control select2" id="unit_export" name="unit_export" style="width: 100%;"></select>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label>Bulan</label>
										<select class="form-control select2" id="bulan_export" name="bulan_export" style="width: 100%;">
											<option></option>
											<?php
											for ($i=1; $i <= 12; $i++) { 
												echo '<option value="'.$this->formatter->zeroPadding($i).'" '.$select.'>'.$this->formatter->getNameOfMonth($i).'</option>';
											} ?>
										</select>
									</div>
									<div class="form-group">
										<label>Tahun</label>
										<select class="form-control select2" id="tahun_export" name="tahun_export" style="width: 100%;">
											<option></option>
											<?php
											$year = $this->formatter->getYear();
											foreach ($year as $yk => $yv) {
												echo '<option value="'.$yk.'" '.$select.'>'.$yv.'</option>';
											} ?>
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
						<h3 class="box-title"><i class="fa fas fa-file-contract"></i> Data Perjanjian Kerja</h3>
						<div class="box-tools pull-right">
							<button class="btn btn-box-tool" onclick="tableData('all')" data-toggle="tooltip" title="Refresh Table"><i class="fas fa-sync"></i></button>
							<button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
							<button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i class="fa fa-times"></i></button>
						</div>
					</div>
					<div class="box-body">
						<div id="accordion">
							<div class="panel">
								<?php if (in_array($access['l_ac']['add'], $access['access'])) {
									echo '<a href="#tambah" data-toggle="collapse" id="btn_tambah" data-parent="#accordion" class="btn btn-success "><i class="fa fa-plus"></i> Tambah Perjanjian Kerja</a> ';}
									if (in_array($access['l_ac']['rkp'], $access['access'])) {
										echo '<button type="button" onclick="rekap()" class="btn btn-warning" ><i class="fa fa-file-excel-o"></i> Export Data</button>';}
									if (in_array($access['l_ac']['add'], $access['access'])) { ?>
								<div id="tambah" class="collapse">
									<br>
									<div class="box box-success">
										<div class="box-header with-border">
											<h3 class="box-title"><i class="fa fa-plus"></i> Tambah Perjanjian Kerja</h3>
										</div>
										<form id="form_add" class="form-horizontal">
											<div class="box-body">
												<div class="callout callout-info"><label><i class="fa fa-info-circle"></i> Bantuan</label><br> Untuk meminimalisir Nomor Surat Sama, Silahkan klik <b><i class="fa fa-refresh"></i></b> disamping kolom nomor surat sebelum menyimpan</div>
												<div class="col-md-6">
													<div class="row">
														<div class="form-group">
															<input type="hidden" name="nonaktif" value="tidak">
															<label class="col-sm-3 control-label">NIK</label>
															<div class="col-sm-7">
																<input type="text" name="nik" id="nik" class="form-control" placeholder="Nomor Induk Karyawan" required="required" readonly="readonly">
															</div>
															<div class="col-sm-1">
																<button type="button" class="btn btn-default btn-sm" id="plus" onclick="pilih_karyawan()">
																	<i class ="fa fa-plus"></i></button>
																</div>
															</div>
															<div class="form-group">
																<label class="col-sm-3 control-label">Nama Karyawan</label>
																<div class="col-sm-9">
																	<input type="text" name="nama" id="nama" class="form-control" placeholder="Nama Karyawan" required="required" readonly="readonly">
																</div>
															</div>
															<div class="form-group">
																<label class="col-sm-3 control-label">Perjanjian Sebelumnya</label>
																<div class="col-sm-9">
																	<input type="hidden" name="status_lama" id="status_lama">
																	<input type="text" name="nama_status_lama" id="nama_status_lama" class="form-control" placeholder="Perjanjian Sebelumnya" readonly="readonly">
																</div>
															</div>
															<div class="form-group">
																<label class="col-sm-3 control-label">Nomor SK</label>
																<div class="col-sm-8">
																	<input type="text" name="no_sk_baru" id="no_sk_baru_add" class="form-control" placeholder="Nomor SK Baru" required="required">
																</div>
																<div class="col-sm-1">
																	<span class="control-label" style="vertical-align: middle;"><i class="fa fa-refresh" style="font-size: 16px;cursor:pointer;" onclick="refreshCode()"></i></span>
																</div>
															</div>
															<div class="form-group">
																<label class="col-sm-3 control-label">Tanggal SK</label>
																<div class="col-sm-9">
																	<div class="has-feedback">
																		<span class="fa fa-calendar form-control-feedback"></span>
																		<input type="text" name="tgl_sk_baru" id="tgl_sk_baru_add" class="form-control pull-right date-picker" placeholder="Tanggal SK Baru" required="required" readonly="readonly">
																	</div>
																</div>
															</div>
														</div>
														<div class="form-group">
															<label class="col-sm-3 control-label">Perjanjian Baru</label>
															<div class="col-sm-9">
																<select class="form-control select2" name="perjanjian_baru" id="data_perjanjian_add" onchange="cekKodePerjanjian(this.value)" required="required" style="width: 100%;"></select>
															</div>
														</div>
														<div class="form-group">
															<label class="col-sm-3 control-label">Status Karyawan Baru</label>
															<div class="col-sm-9">
																<select class="form-control select2" name="status_karyawan" id="status_karyawan" required="required" style="width: 100%;"></select>
															</div>
														</div>
													</div>
													<div class="col-md-6">
														<div class="form-group">
															<label class="col-sm-3 control-label">Tanggal Berlaku</label>
															<div class="col-sm-9">
																<div class="has-feedback">
																	<span class="fa fa-calendar form-control-feedback"></span>
																	<input type="text" name="tgl_berlaku_baru" id="tgl_berlaku_baru_add" class="form-control pull-right date-picker" placeholder="Tanggal Berlaku" required="required" readonly="readonly">
																</div>
															</div>
														</div>
														<div class="form-group">
															<label class="col-sm-3 control-label">Berlaku Sampai</label>
															<div class="col-sm-9">
																<div class="has-feedback">
																	<span class="fa fa-calendar form-control-feedback"></span>
																	<input type="text" name="tgl_berlaku_sampai_baru" id="berlaku_sampai_baru_add" class="form-control pull-right date-picker" placeholder="Berlaku Sampai" required="required">
																</div>
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
															<label class="col-sm-3 control-label">Keterangan</label>
															<div class="col-sm-9">
																<textarea name="keterangan" class="form-control" placeholder="Keterangan"></textarea>
															</div>
														</div>
														<div class="form-group" id="div_nonaktif" style="display: none;">
															<label class="col-sm-3 control-label"></label>
															<div class="col-sm-9">
																<input type="checkbox" name="nonaktif" value="ya"> Nonaktifkan Karyawan
															</div>
														</div>
													</div>
												</div>
												<div class="box-footer">
													<div class="pull-right">
														<button type="submit" id="btn_add" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
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
								<div class="callout callout-info"><label><i class="fa fa-info-circle"></i> Bantuan</label><br>Pilih <b>NIK</b> (Nomor Induk Karyawan) untuk melihat detail Perjanjian maupun melakukan update pada data Perjanjian karyawan</div>
								<table id="table_data" class="table table-bordered table-striped" width="100%">
									<thead>
										<tr>
											<th>No</th>
											<th>NIK</th>
											<th>Nama</th>
											<th>Nomor SK</th>
											<th>Tanggal SK</th>
											<th>Perjanjian</th>
											<th>Berlaku Sampai</th>
											<th>Jumlah Mutasi</th>
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
	<div class="modal-dialog modal-md">
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
							<th width="25%">Nama Karyawan</th>
							<th width="25%">Status Perjanjian</th>
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
							<label class="col-md-6 control-label">NIK</label>
							<div class="col-md-6" id="data_nik_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Nama</label>
							<div class="col-md-6" id="data_nama_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Jabatan</label>
							<div class="col-md-6" id="data_jabatan_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Lokasi</label>
							<div class="col-md-6" id="data_loker_view"></div>
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
				<hr>
				<div class="row">
					<div class="col-md-12">
						<div id="data_tabel_view"></div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
			</div>
		</div>
	</div>
</div>
<!-- delete -->
<div id="modal_delete_partial"></div>
<script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
<script type="text/javascript">
	var url_select="<?php echo base_url('global_control/select2_global');?>";
	var table="data_perjanjian_kerja";
	var column="id_p_kerja";
	$(document).ready(function(){
		tableData('all');
		refreshCode();
		refreshData();
		submitForm('form_add');
		select_data('data_perjanjian_add',url_select,'master_surat_perjanjian','kode_perjanjian','nama');
		getSelect2("<?php echo base_url('employee/mutasi_jabatan/employee')?>",'data_mengetahui_add,#data_menyetujui_add');
	});
	function refreshData() {
      	getSelect2("<?php echo base_url('master/master_bagian/get_select2')?>",'bagian_export');
		select_data('unit_export',url_select,'master_loker','kode_loker','nama','placeholder');
		unsetoption('bagian_export',['BAG001','BAG002']);
	}
	function resetselectAdd() {
		$('#status_karyawan').val('').trigger('change');
		$('#data_perjanjian_add').val('').trigger('change');
		$('#data_mengetahui_add').val('').trigger('change');
		$('#data_menyetujui_add').val('').trigger('change');
	}
	function tableData(kode) {
		$('input[name="param"').val(kode);
		$('#table_data').DataTable().destroy();
		if(kode=='all'){
			var datax = {param:'all',access:"<?php echo $this->codegenerator->encryptChar($access);?>"};
		}else{
			var bagian = $('#bagian_export').val();
			var unit = $('#unit_export').val();
			var bulan = $('#bulan_export').val();
			var tahun = $('#tahun_export').val();
			var datax = {param:'search',bagian:bagian,unit:unit,bulan:bulan,tahun:tahun,access:"<?php echo $this->codegenerator->encryptChar($access);?>"};
		}
		$('#table_data').DataTable( {
			ajax: {
				url: "<?php echo base_url('employee/perjanjian_kerja/view_all/')?>",
				type: 'POST',
				data: datax
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
				width: '10%',
				render: function ( data, type, full, meta ) {
					return '<a href="<?php base_url()?>view_perjanjian_kerja/'+full[9]+'">' +data+'</a>';
				}
			},
			{   targets: 7,
				width: '10%',
				render: function ( data, type, full, meta ) {
					return ''+data+' Perjanjian Kerja';
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
		var callback=getAjaxData("<?php echo base_url('employee/perjanjian_kerja/count/')?>",datax);  
		$('#btn_print_excel').css('display',callback['display']);
	}
	function pilih_karyawan() {
		$('#modal_pilih_karyawan').modal('toggle');
		$('#modal_pilih_karyawan .header_data').html('Pilih Karyawan');
		$('#table_pilih').DataTable( {
			ajax: "<?php echo base_url('employee/pilih_k_perjanjian')?>",
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
			]
		});
	}
	function refreshCode(param='PKWT') {
		kode_generator("<?php echo base_url('employee/perjanjian_kerja/kode/');?>"+param,'no_sk_baru_add');
	}
	function view_modal(id) {
		var data={id_p_kerja:id};
		var callback=getAjaxData("<?php echo base_url('employee/perjanjian_kerja/view_one')?>",data);  
		$('#view').modal('show');
		$('.header_data').html(callback['no_sk']);
		$('#data_nik_view').html(callback['nik']);
		$('#data_nama_view').html(callback['nama']);
		$('#data_perjanjian1_view').html(callback['status_lama']);
		$('#data_nosk1_view').html(callback['no_sk_lama']);
		$('#data_tglsk1_view').html(callback['tgl_sk_lama']);
		$('#data_tglberlaku1_view').html(callback['tgl_berlaku_lama']);
		$('#data_berlakusampai1_view').html(callback['berlaku_sampai_lama']);
		$('#data_perjanjian2_view').html(callback['status_baru']);
		$('#data_nosk2_view').html(callback['no_sk_baru']);
		$('#data_tglsk2_view').html(callback['tgl_sk_baru']);
		$('#data_tglberlaku2_view').html(callback['tgl_berlaku_baru']);
		$('#data_berlakusampai2_view').html(callback['berlaku_sampai_baru']);
		$('#data_mengetahui_view').html(callback['mengetahui']);
		$('#data_menyetujui_view').html(callback['menyetujui']);
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
	function delete_modal(id) {
		var data={id_p_kerja:id};
		var callback=getAjaxData("<?php echo base_url('employee/perjanjian_kerja/view_one')?>",data);
		var datax={table:table,column:'nik',id:callback['nik'],nama:callback['nama']};
		loadModalAjax("<?php echo base_url('pages/load_modal_delete')?>",'modal_delete_partial',datax,'delete');
	}
	function do_status(id,data) {
		var data_table={status:data};
		var where={id_p_kerja:id};
		var datax={table:table,where:where,data:data_table};
		submitAjax("<?php echo base_url('global_control/change_status')?>",null,datax,null,null,'status');
		$('#table_data').DataTable().ajax.reload();
	}
	function submitForm(form) {
		$('#'+form).validator().on('submit', function (e) {
			if (e.isDefaultPrevented()) {
				notValidParamx();
			}else{
				e.preventDefault(); 
				do_add()
			}
		})
	}
	function do_add(){
		if($("#form_add")[0].checkValidity()) {
			submitAjax("<?php echo base_url('employee/add_perjanjian_kerja')?>",null,'form_add',null,null);
			$('#table_data').DataTable().ajax.reload(function (){
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
		window.location.href = "<?php echo base_url('rekap/export_perjanjian_kerja')?>?"+data;
	}
	function cekKodePerjanjian(kode_perjanjian) {
		var status = $('#data_perjanjian_add').val();
			if(status=='PTSP'){
				$('#div_nonaktif').show();
			}else if(status=='RSGN'){
				$('#div_nonaktif').show();
			}else{
				$('#div_nonaktif').hide();
			}
		var data={kode:status};
		var callback=getAjaxData("<?php echo base_url('employee/cek_kode_perjanjian')?>",data);
		$('#status_karyawan').html(callback['status_karyawan']);
		if(callback['nama_status'] == 'TETAP'){
			refreshCode('JK');
		}else{
			refreshCode();
		}
	}
	$(document).on('click', '.pilih', function (e1) {
		$("#nik").val($(this).data('nik'));
		$("#nama").val($(this).data('nama'));
		$("#status_lama").val($(this).data('status_perjanjian'));
		$("#nama_status_lama").val($(this).data('nama_status_perjanjian'));
		$("#no_sk_lama").val($(this).data('no_sk_lama'));
		$("#tgl_sk_lama").val($(this).data('tgl_sk_lama'));
		$("#tgl_berlaku_lama").val($(this).data('tgl_berlaku_lama'));
		$("#tgl_berlaku_sampai_lama").val($(this).data('tgl_berlaku_sampai_lama'));
		$('#modal_pilih_karyawan').modal('hide');
	});
	$(document).ready(function(){
	// function ubah(){
		$('#data_perjanjian_add, #tgl_berlaku_baru_add').change(function(){
			var ij = $('#data_perjanjian_add').val();
			var ik = $('#tgl_berlaku_baru_add').val();
			var nik = $('#nik').val();
			$.ajax({
				method : "POST",
				url   : '<?php echo base_url('employee/tanggal_janji')?>',
				async : false,
				dataType : 'json',
				data: {	tgl_berlaku: ik,
					status: ij,
					nik: nik
				},
				success : function(data){
					$('#berlaku_sampai_baru_add').val(data);
				}
			});
		})
	// }
	})
</script> 
