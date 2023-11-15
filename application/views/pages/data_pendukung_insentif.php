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
						<h3 class="box-title"><i class="fa fa-list"></i> Daftar Data Insentif</h3>
						<div class="box-tools pull-right">
							<button class="btn btn-box-tool" onclick="reload_table('table_data')" data-toggle="tooltip" title="Refresh Table"><i class="fas fa-sync"></i></button>
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
													<?php 
													if (in_array($access['l_ac']['add'], $access['access'])) {
														echo '<a href="#add" data-toggle="collapse" id="btn_tambah"  data-parent="#accordion" class="btn btn-success" style="margin-right: 5px;"><i class="fa fa-plus"></i> Tambah Data Insentif</a>';
													}
													if (in_array($access['l_ac']['exp'], $access['access'])) {
														echo '<button type="button" class="btn btn-warning" onclick="model_export()"><i class="fas fa-file-excel-o"></i>Export</button>';
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
																	$selet_emp = $this->model_karyawan->getEmployeeAllActive();
																	foreach ($selet_emp as $se) {
																		echo '<option value="'.$se->id_karyawan.'">'.$se->nama.' ( '.$se->nama_jabatan.' )</option>';
																	}
																?>
															</select>
														</div>
														<div class="form-group">
															<label>Master Insentif</label>
															<select class="form-control select2" name="insentif[]" id="data_insentif_add" style="width: 100%;" multiple="multiple" required="required">
																<option></option>
																<option value="all">Pilih Semua</option>
																<?php
																$selet_ins = $this->model_master->getListInsentif();
																foreach ($selet_ins as $si) {
																	echo '<option value="'.$si->id_insentif.'">'.$si->nama.' ( '.$this->formatter->getFormatMoneyUser($si->nominal).' - '.$si->tahun.' )</option>';
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
											<th rowspan="2">Grade</th>
											<?php
												echo '<th class="text-center" colspan="'.count($masterInsentif).'">DATA INSENTIF</th>';
											?>
											<th rowspan="2">Nominal Insentif (Rp)</th>
											<th rowspan="2">Aksi</th>
										</tr>
										<tr>
											<?php
												foreach ($masterInsentif as $mi) {
													echo '<th>'.ucwords(strtolower($mi->nama)).' (Rp)</th>';
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
								<label class="col-md-6 control-label">Grade</label>
								<div class="col-md-6" id="data_grade_view"></div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group col-md-12">
								<label class="col-md-6 control-label">Total Insentif</label>
								<div class="col-md-6" id="data_nominal_view"></div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<table id="table_detail" class="table table-bordered table-striped table-responsive" width="100%">
								<thead>
									<tr>
										<th>No.</th>
										<th>Kode Insentif</th>
										<th>Nama Insentif</th>
										<th>Nominal Insentif</th>
										<th>Tahun</th>
									</tr>
								</thead>
								<tbody>
								</tbody>
							</table>
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

	<div id="edit" class="modal fade" role="dialog">
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h2 class="modal-title">Edit Data <b class="text-muted header_data"></b></h2>
				</div>
				<form id="form_edit">
					<div class="modal-body">
						<input type="hidden" id="data_id_edit" name="id_karyawan" value="">
						<div class="form-group">
							<label>Karyawan</label>
							<select class="form-control select2" name="karyawan" id="data_karyawan_edit" style="width: 100%;" required="required">
								<option></option>
								<option value="all">Pilih Semua</option>
								<?php
								foreach ($selet_emp as $se) {
									echo '<option value="'.$se->id_karyawan.'">'.$se->nama.' ( '.$se->nama_jabatan.' )</option>';
								}
								?>

							</select>
						</div>
						<div class="form-group">
							<label>Master Insentif</label>
							<select class="form-control select2" name="insentif[]" id="data_insentif_edit" style="width: 100%;" multiple="multiple" required="required">
								<option></option>
								<option value="all">Pilih Semua</option>
								<?php
								foreach ($selet_ins as $si) {
									echo '<option value="'.$si->id_insentif.'">'.$si->nama.' ( '.$this->formatter->getFormatMoneyUser($si->nominal).' - '.$si->tahun.' )</option>';
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
		var jumlah_master = <?=count($masterInsentif)?>; 
		$(document).ready(function(){
			$('#table_data').DataTable( {
				ajax: {
					url: "<?php echo base_url('cpayroll/data_insentif/view_all/')?>",
					type: 'POST',
					data:{access:"<?php echo $this->codegenerator->encryptChar($access);?>"}
				},
				fixedColumns:   {
					leftColumns: 3,
					rightColumns: 1
				},
				scrollX: true,
				autoWidth: false,
				columnDefs: [
				{   targets: 0, 
					width: '5%',
					render: function ( data, type, full, meta ) {
						return '<center>'+(meta.row+1)+'.</center>';
					}
				},
				{   targets: 8+jumlah_master,
					width: '10%',
					render: function ( data, type, full, meta ) {
						return '<center>'+data+'</center>';
					}
				},
				]
			});
			$('#btn_tambah').click(function(){
				var key = $('#key_btn_tambah').val();
				if(key == 0){
					$('#data_karyawan_add').val([]).trigger('change');
					$('#data_insentif_add').val([]).trigger('change');
					$('#key_btn_tambah').val('1');
				}else { $('#key_btn_tambah').val('0'); }
			})
			$('#table_detail').DataTable();
		});

		function model_export() {
			$('#rekap_mode').modal('show');
		}
		function view_modal(id) {
			$('#table_detail').DataTable().destroy();
			var insentif = $('#enc_ins_'+id).val();
			var data={id_karyawan:id, mode: 'view',insentif: insentif};
			var callback=getAjaxData("<?php echo base_url('cpayroll/data_insentif/view_one')?>",data);  
			$('#view').modal('show');
			$('.header_data').html(callback['nama']);

			$('#data_nik_view').html(callback['nik']);
			$('#data_nama_view').html(callback['nama']);
			$('#data_jabatan_view').html(callback['jabatan']);
			$('#data_bagian_view').html(callback['bagian']);
			$('#data_loker_view').html(callback['loker']);
			$('#data_grade_view').html(callback['grade']);
			$('#data_nominal_view').html(callback['nominal']);
			$('#table_detail tbody').html(callback['detail']);
			$('#table_detail').DataTable();
			
			$('input[name="data_id_view"]').val(callback['id']);
		}
		function edit_modal() {
			var id = $('input[name="data_id_view"]').val();
			var insentif = $('#enc_ins_'+id).val();
			var data={id_karyawan:id, mode: 'edit',insentif: insentif};
			var callback=getAjaxData("<?php echo base_url('cpayroll/data_insentif/view_one')?>",data); 
			$('#view').modal('toggle');
			setTimeout(function () {
				$('#edit').modal('show');
			},600); 
			$('.header_data').html(callback['nama']+' ('+callback['nik']+')');
			$('#data_id_edit').val(callback['id']);

			$('#data_karyawan_edit').val(callback['id']).trigger('change');
			$('#data_insentif_edit').val(callback['detail']).trigger('change');
		}
		function delete_modal(id) {
			var data={id_karyawan:id, mode: 'delete'};
			var callback=getAjaxData("<?php echo base_url('cpayroll/data_insentif/view_one')?>",data);
			$('#data_id_delete').val(callback['id']);
			$('#data_name_delete').html(callback['nama']);
			$('#delete').modal('show');
		}
		function do_edit(){
			if($("#form_edit")[0].checkValidity()) {
				submitAjax("<?php echo base_url('cpayroll/edit_insentif')?>",'edit','form_edit',null,null);
				$('#table_data').DataTable().ajax.reload();
			}else{
				notValidParamx();
			} 
		}
		function do_add(){
			if($("#form_add")[0].checkValidity()) {
				submitAjax("<?php echo base_url('cpayroll/add_insentif')?>",null,'form_add',null,null);
				$('#table_data').DataTable().ajax.reload(function (){
					Pace.restart();
				});
				$('#form_add')[0].reset();
				$('#data_karyawan_add').val([]).trigger('change');
				$('#data_insentif_add').val([]).trigger('change');
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
				submitAjax("<?php echo base_url('cpayroll/delete_insentif')?>",'delete','form_delete',null,null);
				$('#table_data').DataTable().ajax.reload(function (){
					Pace.restart();
				});
			}
		}
	</script>