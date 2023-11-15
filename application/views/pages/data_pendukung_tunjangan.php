<style type="text/css">
	table#table_data thead tr th, table#table_data tbody tr td, table.DTFC_Cloned thead tr th{
		white-space: pre;
	}
	/*table.DTFC_Cloned tbody{
		overflow: hidden;
	}*/
</style>
	<!-- <section class="content"> -->
		<div class="row">
			<div class="col-md-12">
				<div class="box box-success">
					<div class="box-header with-border">
						<h3 class="box-title"><i class="fa fa-list"></i> Daftar Data Tunjangan</h3>
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
												$periode = $this->model_master->getListPeriodePenggajian();
												echo '<option></option>';
												foreach ($periode as $p) {
													echo '<option value="'.$p->kode_periode_penggajian.'">'.$p->nama.' ('.$p->nama_sistem_penggajian.')</option>';
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
								<div id="accordion">
									<div class="panel">
										<div class="row">
											<div class="col-md-12">
												<div class="pull-left">
													<?php 
													// echo $level;
													if (in_array($access['l_ac']['add'], $access['access'])) {
														echo '<a href="#add" data-toggle="collapse" id="btn_tambah"  data-parent="#accordion" class="btn btn-success" style="margin-right: 5px;"><i class="fa fa-plus"></i> Tambah Data Tunjangan</a>';
													}
													if (in_array($access['l_ac']['exp'], $access['access'])) {
														echo '<button type="button" class="btn btn-warning" onclick="model_export()"><i class="fas fa-file-excel-o"></i>  Export</button>';
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
											<input type="hidden" id="key_btn_tambah" value="0">
											<div class="collapse" id="add">
												<div class="col-md-2"></div>
												<div class="col-md-8">
													<form id="form_add">
														<div class="form-group">
															<label>Karyawan</label>
															<select class="form-control select2" name="karyawan[]" id="data_karyawan_add" style="width: 100%;" multiple="multiple" required="required">
																<option></option>
																<option value="all">Pilih Semua</option>
																<?php
																	$where = null;
																	if($level != 0 ){
																		$where = 'emp.golongan=1';	
																	}
																	$selet_emp = $this->model_karyawan->getEmployeeAllActive(null, null, $where);
																	foreach ($selet_emp as $se) {
																		echo '<option value="'.$se->id_karyawan.'">'.$se->nama.' ( '.$se->nama_jabatan.' )</option>';
																	}
																?>
															</select>
														</div>
														<div class="form-group">
															<label>Master Tunjangan</label>
															<select class="form-control select2" name="tunjangan[]" id="data_tunjangan_add" style="width: 100%;" multiple="multiple" required="required">
																<option></option>
																<option value="all">Pilih Semua</option>
																<?php
																foreach ($indukTunjanganNon as $si) {
																	echo '<option value="'.$si->kode_induk_tunjangan.'">'.$si->nama.'</option>';
																}
																?>
															</select>
														</div>
														<div class="form-group">
															<label>Pilih Periode Penggajian</label>
															<select class="form-control select2" name="periode" id="data_periode_add" style="width: 100%;">
																<?php
																$periode = $this->model_master->getListPeriodePenggajian();
																echo '<option></option>';
																foreach ($periode as $p) {
																	echo '<option value="'.$p->kode_periode_penggajian.'">'.$p->nama.' ('.$p->nama_sistem_penggajian.')</option>';
																}
																?>
															</select>
														</div>
														<div class="form-group">
															<button type="button" onclick="do_add()" id="btn_add" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
														</div>
													</form>
												</div>
											</div>
										<?php } ?>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<table id="table_data" class="table table-bordered table-striped table-responsive" width="100%">
									<thead>
										<tr>
											<th rowspan="2">No.</th>
											<th rowspan="2">NIK</th>
											<th rowspan="2">Nama</th>
											<th rowspan="2">Jabatan</th>
											<th rowspan="2">Bagian</th>
											<th rowspan="2">Lokasi Kerja</th>
											<?php
												echo '<th class="text-center" style="background-color:green;" colspan="'.count($indukTunjanganTetap).'">DATA TUNJANGAN TETAP</th>';
											?>
											<?php
												echo '<th class="text-center" style="background-color:yellow;" colspan="'.count($indukTunjanganNon).'">DATA TUNJANGAN TIDAK TETAP</th>';
											?>
											<th rowspan="2">Total (Rp)</th>
											<th rowspan="2">Periode</th>
											<th rowspan="2">Aksi</th>
										</tr>
										<tr>
											<?php
												foreach ($indukTunjanganTetap as $key_tt) {
													echo '<th>'.ucwords(strtolower(str_replace('TUNJANGAN','',$key_tt->nama))).' (Rp)</th>';
												}
											?>
											<?php
												foreach ($indukTunjanganNon as $key_it) {
													echo '<th>'.ucwords(strtolower(str_replace('TUNJANGAN','',$key_it->nama))).' (Rp)</th>';
												}
											?>
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
								<label class="col-md-6 control-label">Bagian</label>
								<div class="col-md-6" id="data_bagian_view"></div>
							</div>
							<div class="form-group col-md-12">
								<label class="col-md-6 control-label">Lokasi Kerja</label>
								<div class="col-md-6" id="data_loker_view"></div>
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
					<div class="row">
						<h3 class="text-center"><b>Data Tunjangan</b></h3><hr>
						<div id="data_tunjangan_view"></div>
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

	<div id="edit" class="modal fade" role="dialog">
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h2 class="modal-title">Edit Data <b class="text-muted header_data"></b></h2>
				</div>
				<form id="form_edit">
					<div class="modal-body">
						<input type="hidden" id="data_id_edit" name="id_tunjangan" value="">
						<div class="form-group">
							<label>Karyawan</label>
							<input type="text" class="form-control" id="nama_karyawan_edit" readonly="readonly">
						</div>
						<div class="form-group">
							<label>Jabatan</label>
							<input type="text" class="form-control" id="nama_jabatan_edit" readonly="readonly">
						</div>
						<div class="form-group">
							<label>Bagian</label>
							<input type="text" class="form-control" id="nama_bagian_edit" readonly="readonly">
						</div>
						<div class="form-group">
							<label>Master Tunjangan</label>
							<select class="form-control select2" name="tunjangan[]" id="data_tunjangan_edit" style="width: 100%;" multiple="multiple" required="required">
								<option></option>
								<option value="all">Pilih Semua</option>
								<?php
								foreach ($indukTunjanganNon as $six) {
									echo '<option value="'.$six->kode_induk_tunjangan.'">'.$six->nama.'</option>';
								}
								?>
							</select>
						</div>
						<div class="form-group">
							<label>Pilih Periode Penggajian</label>
							<select class="form-control select2" name="periode" id="data_periode_edit" style="width: 100%;">
								<?php
								// $periode = $this->model_master->getListPeriodePenggajian();
								echo '<option></option>';
								foreach ($periode as $p) {
									echo '<option value="'.$p->kode_periode_penggajian.'">'.$p->nama.' ('.$p->nama_sistem_penggajian.')</option>';
								}
								?>
							</select>
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

	<div id="delete" class="modal fade" role="dialog">
		<div class="modal-dialog modal-sm modal-danger">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title text-center">Konfirmasi Hapus</h4>
				</div>
				<form id="form_delete">
					<div class="modal-body text-center">
						<input type="hidden" id="data_id_delete" name="id">
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
	<div id="modal_delete_partial"></div>
	<script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
	<script type="text/javascript">
		var jumlah_induk_t = <?=count($indukTunjanganTetap)?>; 
		var jumlah_induk = <?=count($indukTunjanganNon)?>; 
		$(document).ready(function(){
			tableData('all');
			$('#btn_tambah').click(function(){
				var key = $('#key_btn_tambah').val();
				if(key == 0){
					$('#data_karyawan_add').val([]).trigger('change');
					$('#data_tunjangan_add').val([]).trigger('change');
					$('#key_btn_tambah').val('1');
				}else {
					$('#key_btn_tambah').val('0');
				}
			})
			$('#table_detail').DataTable();
		});
		function tableData(kode) {
			var periode = $('#data_periode_cari').val();
			$('#table_data').DataTable( {
				ajax: {
					url: "<?php echo base_url('cpayroll/data_tunjangan/view_all/')?>",
					type: 'POST',
					data:{access:"<?php echo $this->codegenerator->encryptChar($access);?>",periode:periode,kode:kode}
				},
				fixedColumns:   {
					leftColumns: 3,
					rightColumns: 2
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
					{   targets: 8+jumlah_induk_t+jumlah_induk,
						width: '10%',
						render: function ( data, type, full, meta ) {
							return '<center>'+data+'</center>';
						}
					},
				]
			});
		}

		function model_export() {
			$('#rekap_mode').modal('show');
		}
		function view_modal(id) {
			var insentif = $('#enc_ins_'+id).val();
			var data={id:id, mode: 'view',insentif: insentif};
			var callback=getAjaxData("<?php echo base_url('cpayroll/data_tunjangan/view_one')?>",data);  
			$('#view').modal('show');
			$('.header_data').html(callback['nama']);
			$('#data_nik_view').html(callback['nik']);
			$('#data_nama_view').html(callback['nama']);
			$('#data_jabatan_view').html(callback['jabatan']);
			$('#data_bagian_view').html(callback['bagian']);
			$('#data_loker_view').html(callback['loker']);
			$('#data_periode_view').html(callback['nama_periode']);
			$('#data_tunjangan_view').html(callback['data_tunjangan_view']);
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
			var id = $('input[name="data_id_view"]').val();
			var data={id:id, mode: 'edit'};
			var callback=getAjaxData("<?php echo base_url('cpayroll/data_tunjangan/view_one')?>",data); 
			$('#view').modal('toggle');
			setTimeout(function () {
				$('#edit').modal('show');
			},600); 
			$('.header_data').html(callback['nama']+' ('+callback['nik']+')');
			$('#data_id_edit').val(callback['id']);

			$('#data_tunjangan_edit').val(callback['e_tunjangan']).trigger('change');
			$('#data_periode_edit').val(callback['kode_periode']).trigger('change');
			$('#nama_karyawan_edit').val(callback['nama']);
			$('#nama_jabatan_edit').val(callback['jabatan']);
			$('#nama_bagian_edit').val(callback['bagian']);
		}
		function delete_modal(id) {
			var data={id:id, mode: 'delete'};
			var callback=getAjaxData("<?php echo base_url('cpayroll/data_tunjangan/view_one')?>",data);
			$('#data_id_delete').val(callback['id']);
			$('#data_name_delete').html(callback['nama']);
			$('#delete').modal('show');
		}
		function do_edit(){
			if($("#form_edit")[0].checkValidity()) {
				submitAjax("<?php echo base_url('cpayroll/edit_tunjangan')?>",'edit','form_edit',null,null);
				$('#table_data').DataTable().ajax.reload();
			}else{
				notValidParamx();
			} 
		}
		function do_add(){
			if($("#form_add")[0].checkValidity()) {
				submitAjax("<?php echo base_url('cpayroll/add_tunjangan')?>",null,'form_add',null,null);
				$('#table_data').DataTable().ajax.reload(function (){
					Pace.restart();
				});
				$('#form_add')[0].reset();
				$('#data_karyawan_add').val([]).trigger('change');
				$('#data_tunjangan_add').val([]).trigger('change');
				$('#data_periode_add').val('').trigger('change');
			}else{
				notValidParamx();
			} 
		} 

		function do_rekap(file) {
			if(file == 'pdf'){
				window.open('<?php echo base_url()."pages/rekap_data_insentif/"; ?>','_blank');
			}else{
				window.location.href = "<?php echo base_url('rekap/export_data_insentif')?>";
			}
		}
		function do_delete(){
			var id_karyawan = $('#data_id_delete').val();
			if(id_karyawan == ''){
				notValidParamx();
			}else{
				submitAjax("<?php echo base_url('cpayroll/delete_tunjangan')?>",'delete','form_delete',null,null);
				$('#table_data').DataTable().ajax.reload(function (){
					Pace.restart();
				});
			}
		}
	</script>