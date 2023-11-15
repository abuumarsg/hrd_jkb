<?php $title= 'Rancangan Sikap'; ?>  
<div class="content-wrapper">
	<section class="content-header">
		<h1>
			<i class="fa fa-flask"></i> Rancangan 
			<small>Sikap <?php echo $nama; ?></small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
			<li><a href="<?php echo base_url('pages/data_konsep_sikap');?>"><i class="fas fa-flask"></i> Rancangan Sikap</a></li>
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
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title"><i class="fa fa-users"></i> Daftar Karyawan <?php echo $nama; ?></h3>
						<div class="box-tools pull-right">
							<button class="btn btn-box-tool" onclick="reload_table('table_data')" data-toggle="tooltip" title="Refresh Table"><i class="fas fa-sync"></i></button>
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
											<?php if(in_array('ADD',$access['access'])){?>
												<button class="btn btn-success" type="button" data-toggle="collapse" data-parent="#accordion" data-target="#add" id="btn_add_collapse"><i class="fa fa-plus"></i> Tambah Karyawan</button>
											<?php } if(in_array('SNC',$access['access'])){?>
												<button class="btn btn-info" type="button" data-toggle="modal" data-target="#sync"
  												id="btn_sync"><i class="fa fa-magic"></i> Sync dari Agenda</button>
											<?php } if(in_array('GNR',$access['access'])){?>
												<button class="btn btn-primary" type="button" data-toggle="collapse" data-parent="#accordion" data-target="#generate"><i class="fa fa-recycle"></i> Generate Otomatis</button>
											<?php } ?>
										</div>
										<div class="pull-right" style="font-size: 8pt;">
											<i class="fa fa-toggle-on stat scc"></i> Aktif<br>
											<i class="fa fa-toggle-off stat err"></i> Tidak Aktif
										</div>
									</div>
								</div>
								<?php if (in_array('ADD', $access['access'])) {?>
									<div class="collapse" id="add">
										<br>
										<div class="col-md-2"></div>
										<div class="col-md-8">
											<form id="form_add">
												<input type="hidden" name="table" value="<?php echo $this->codegenerator->encryptChar($tabel);?>">
												<div class="form-group">
													<label>Pilih Karyawan</label>
													<select class="form-control select2" name="id_karyawan" id="data_karyawan_add" style="width: 100%;"></select>
												</div>
												<div class="form-group">
													<label>Pilih Opsi Generate</label>
													<div class="row">
														<div class="col-md-12">
															<div class="col-md-4">
																<div class="checkbox">
																	<label>
																		<input type="checkbox" class="icheck-class" name="opsi_m[]" class="check" value="ATS"> Atasan
																	</label>
																</div>
															</div>
															<div class="col-md-4">
																<div class="checkbox">
																	<label>
																		<input type="checkbox" class="icheck-class" name="opsi_m[]" class="check" value="BWH"> Bawahan
																	</label>
																</div>
															</div>
															<div class="col-md-4">
																<div class="checkbox">
																	<label>
																		<input type="checkbox" class="icheck-class" name="opsi_m[]" class="check" value="RKN"> Rekan
																	</label>
																</div>
															</div>
														</div>
													</div>
												</div>
												<div class="form-group">
													<button type="button" onclick="do_add()" id="btn_add" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
												</div>
											</form>
										</div>
									</div>
									<?php } if (in_array('GNR', $access['access'])) {?>
									<div class="collapse" id="generate">
										<br>
										<div class="col-md-2"></div>
										<div class="col-md-8">
											<form id="form_generate">
												<input type="hidden" name="table" value="<?php echo $this->codegenerator->encryptChar($tabel);?>">
												<div class="form-group">
													<div class="callout callout-info">
														<b><i class="fa fa-info-circle"></i> Generate untuk seluruh karyawan</b>
													</div>
													<label>Pilih Opsi Generate</label>
													<div class="row">
														<div class="col-md-12">
															<div class="col-md-4">
																<div class="checkbox">
																	<label>
																		<input type="checkbox" class="icheck-class" name="opsi[]" value="ATS"> Atasan
																	</label>
																</div>
															</div>
															<div class="col-md-4">
																<div class="checkbox">
																	<label>
																		<input type="checkbox" class="icheck-class" name="opsi[]" value="BWH"> Bawahan
																	</label>
																</div>
															</div>
															<div class="col-md-4">
																<div class="checkbox">
																	<label>
																		<input type="checkbox" class="icheck-class" name="opsi[]" value="RKN"> Rekan
																	</label>
																</div>
															</div>
														</div>
													</div>
												</div>
												<div class="form-group">
													<button type="button" onclick="do_generate()" id="btn_generate" class="btn btn-danger"><i class="fa fa-recycle"></i> Generate</button>
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
								<div class="callout callout-info"><b><i class="fa fa-bullhorn"></i> Petunjuk</b>
									<ul>
										<li>Pilih <b>NIK Karyawan</b> untuk melihat Detail partisipan karyawan yang Anda pilih!</li>
										<li>Jika terdapat tanda <i class="fa fa-user-times" style="color: orange"></i> pada nama karyawan, maka anda harus menyesuaikan data HRIS terbaru.</li>
										<li>Rancangan <?=$nama;?> berkaitan langsung dengan agenda yang <b class="text-red">BELUM TERVALIDASI</b>, pastikan data diupdate dengan benar!</li>
										<li>Jika Anda melakukan proses <b>hapus (delete)</b> maka nilai yang bersangkutan <b class="text-red">juga akan terhapus</b> pada <b class="text-red">AGENDA TERKAIT</b> dan <b class="text-red">BELUM TERVALIDASI</b>.</b>
										<li><b>Cek kembali data Anda sebelum melakukan perubahan!</b></li>
									</ul>
								</div>
								<table id="table_data" class="table table-bordered table-striped table-responsive" width="100%">
									<thead>
										<tr>
											<th>No.</th>
											<th>NIK</th>
											<th>Nama Karyawan</th>
											<th>Jabatan</th>
											<th>Bagian</th>
											<th>Lokasi Kerja</th>                        
											<th>Jumlah Partisipan</th>
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
							<label class="col-md-6 control-label">Nama Karyawan</label>
							<div class="col-md-6" id="data_name_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Jabatan</label>
							<div class="col-md-6" id="data_jabatan_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Lokasi Kerja</label>
							<div class="col-md-6" id="data_loker_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Bagian</label>
							<div class="col-md-6" id="data_bagian_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Jumlah Partisipan</label>
							<div class="col-md-6" id="data_partisipan_view"></div>
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
				<hr>
				<div class="row">
					<div class="col-md-12">
						<h4 class="text-center">Detail Partisipan</h4>
						<div id="data_table_partisipan_view" class="div-overflow"></div>
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
<div id="delete" class="modal fade" role="dialog">
  <div class="modal-dialog modal-sm modal-danger">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title text-center">Konfirmasi Hapus</h4>
      </div>
      <form id="form_delete">
        <div class="modal-body text-center">
          <input type="hidden" id="data_column_delete" name="column">
          <input type="hidden" id="data_id_delete" name="id">
          <input type="hidden" id="data_table_delete" name="table">
          <input type="hidden" id="data_table_drop" name="table_drop">
          <input type="hidden" id="data_link_table" name="link_table">
          <input type="hidden" id="data_link_col" name="link_col">
          <input type="hidden" id="data_link_data_col" name="link_data_col">
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
		<h2 class="modal-title">Sync dari Agenda Aspek Sikap</h2>
		</div>
		<div class="modal-body">
			<div class="row">
				<div class="col-md-12">
				<div class="callout callout-info"><label><i class="fa fa-bullhorn"></i> Perhatian</label><br>
					Jika Anda melakukan proses Sinkronisasi data dari Agenda Aspek Sikap ke Rancangan Aspek Sikap maka, data Rancangan Aspek Sikap akan <b><i>replace</i></b> atau data lama <b class="text-red">AKAN HILANG</b> dan digantikan data baru dari Agenda Aspek Sikap. 
				</div>
				<form id="form_sync">
					<input type="hidden" name="kode" value="<?=$this->uri->segment(3)?>">
					<input type="hidden" name="table" value="<?=$tabel?>">
					<div class="form-group">
					<label>Pilih Agenda Aspek Sikap</label>
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
  //wajib diisi
	var url_select="<?php echo base_url('global_control/select2_global');?>";
	var table="<?php echo $tabel;?>";
	var code_c="<?php echo $this->codegenerator->encryptChar($kode);?>";
	var column="id_c_sikap";
	var url_select="<?php echo base_url('global_control/select2_global');?>";
  	$(document).ready(function(){ 
      	tableData('all');
		$('#btn_sync').click(function () {
			getSelect2("<?php echo base_url('agenda/agenda_sikap/get_select2')?>", 'data_agenda_sync');
		})
		select_data('lokasi_ser',url_select,'master_loker','kode_loker','nama');
		$('#btn_add_collapse').click(function(){
			refreshEmp();
		})
  	});
	function tableData(kode){
		$('#table_data').DataTable().destroy();
		if(kode=='all'){
			var lokasi = null;
		}else{
			var lokasi = $('#lokasi_ser').val();
		}
		$('#table_data').DataTable( {
			ajax: {
				url: "<?php echo base_url('concept/view_data_konsep_sikap/view_all')?>",
				type: 'POST',
				data:{access:"<?php echo $this->codegenerator->encryptChar($access);?>",code:code_c,table:table,lokasi: lokasi	}
			},
			scrollX: true,
			drawCallback: function() {
				$('[data-toggle="tooltip"]').tooltip();
			},
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
						return '<a href="<?php echo base_url('pages/view_detail_konsep_sikap/')?>'+full[11]+'/'+full[10]+'">'+data+'</a>';
					}
				},
				{   targets: 7,
					width: '10%',
					render: function ( data, type, full, meta ) {
						return '<center>'+data+'</center>';
					}
				},
				{   targets: 8,
					width: '10%',
					render: function ( data, type, full, meta ) {
						return '<center>'+data+'</center>';
					}
				},
				{   targets: 9, 
					width: '5%',
					render: function ( data, type, full, meta ) {
						return '<center>'+data+'</center>';
					}
				},
			]		
		});
	}
  function refreshEmp() {
  	var data={table:table,access:"<?= $this->codegenerator->encryptChar($access);?>"};
  	getSelect2('<?php echo base_url('concept/view_data_konsep_sikap/get_employee'); ?>','data_karyawan_add',data);
  }
  function view_modal(id) {
  	var data={id_c_sikap:id,table:table};
  	var callback=getAjaxData("<?php echo base_url('concept/view_data_konsep_sikap/view_one')?>",data);  
  	$('#view').modal('show');
  	$('.header_data').html(callback['nama']);
  	$('#data_nik_view').html(callback['nik']);
  	$('#data_name_view').html(callback['nama']);
  	$('#data_jabatan_view').html(callback['nama_jabatan']);
  	$('#data_loker_view').html(callback['nama_loker']);
  	$('#data_bagian_view').html(callback['bagian']);
  	$('#data_partisipan_view').html(callback['jumlah_partisipan']);
  	$('#data_table_partisipan_view').html(callback['table_data']);
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
  	var data={id_c_sikap:id};
  	var callback=getAjaxData("<?php echo base_url('concept/view_data_konsep_sikap/view_one')?>",data); 
  	$('#view').modal('toggle');
  	setTimeout(function () {
  		$('#edit').modal('show');
  	},600); 
  	$('.header_data').html(callback['nama']);
  	$('#data_id_edit').val(callback['id']);
  	$('#data_kode_edit_old').val(callback['kode_c_sikap']);
  	$('#data_kode_edit').val(callback['kode_c_sikap']);
  	$('#data_name_edit').val(callback['nama']);
  }
  function delete_modal(id) {
  	var data={id_c_sikap:id,table:table};
  	var callback=getAjaxData("<?php echo base_url('concept/view_data_konsep_sikap/view_one')?>",data);
  	setTimeout(function () {
  		$('#delete').modal('show');
  	},600); 
	$('#data_id_delete').val(callback['id_karyawan']);
	$('#data_table_delete').val(table);
  	$('.header_data').html(callback['nama']);
  }
	//doing db transaction
	function do_status(id,data) {
		var data_table={status:data};
		var where={id_c_sikap:id};
		var datax={table:table,where:where,data:data_table};
		submitAjax("<?php echo base_url('global_control/change_status')?>",null,datax,null,null,'status');
		$('#table_data').DataTable().ajax.reload(function (){
			Pace.restart();
		});
	}
	function do_edit(){
		if($("#form_edit")[0].checkValidity()) {
			submitAjax("<?php echo base_url('concept/edt_concept_sikap')?>",'edit','form_edit',null,null);
			$('#table_data').DataTable().ajax.reload(function (){
				Pace.restart();
			});
		}else{
			notValidParamx();
		} 
	}
	function do_add(){
		if($("#form_add")[0].checkValidity()) {
			submitAjax("<?php echo base_url('concept/add_data_konsep_sikap')?>",null,'form_add',null,null);
			$('#table_data').DataTable().ajax.reload(function (){
				Pace.restart();
			});
			$('#form_add')[0].reset();
			$('input[type="checkbox"]').icheck('unchecked');
			refreshEmp();
		}else{
			notValidParamx();
		} 
	}
	function do_generate() {
		Pace.restart();
		if($("#form_generate")[0].checkValidity()) {
			submitAjax("<?php echo base_url('concept/generate_data_konsep_sikap')?>",null,'form_generate',null,null);
			$('#table_data').DataTable().ajax.reload(function (){
				Pace.restart();
			});
			$('#form_generate')[0].reset();
			$('input[type="checkbox"]').icheck('unchecked');
			refreshEmp();
		}else{
			notValidParamx();
		} 
	}
	function do_sync() {
		if ($("#form_sync")[0].checkValidity()) {
			submitAjax("<?php echo base_url('concept/sync_from_agenda_sikap')?>", 'sync', 'form_sync', null, null);
			$('#table_data').DataTable().ajax.reload(function () {
				Pace.restart();
			});
			$('#data_agenda_sync').val('').trigger('change');
		} else {
			notValidParamx();
		}
	}
	function do_delete(){
		submitAjax("<?php echo base_url('concept/del_employee_sikap')?>",'delete','form_delete',null,null);
		$('#table_data').DataTable().ajax.reload(function (){
			Pace.restart();
		});
		refreshEmp();
	}
</script>
