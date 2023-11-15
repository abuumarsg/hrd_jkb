<?php $title= 'Rancangan Sikap'; ?>  
<div class="content-wrapper">
	<section class="content-header">
		<h1>
			<i class="fa fa-flask"></i> Rancangan 
			<small>Sikap <?= $nama; ?></small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?= base_url('pages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
			<li><a href="<?= base_url('pages/data_konsep_sikap');?>"><i class="fas fa-flask"></i> Rancangan Sikap</a></li>
			<li><a href="<?= base_url('pages/view_data_konsep_sikap/'.$this->codegenerator->encryptChar($kode));?>"><i class="fas fa-users"></i> Daftar Karyawan</a></li>
			<li class="active">Daftar Partisipan</li>
		</ol>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title"><i class="fa fa-users"></i> Daftar Partisipan <?= $emp_nama; ?></h3>
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
											<?php } ?>
										</div>
									</div>
								</div>
								<?php if (in_array('ADD', $access['access'])) {?>
									<div class="collapse" id="add">
										<br>
										<div class="col-md-2"></div>
										<div class="col-md-8">
											<form id="form_add">
												<input type="hidden" name="table" value="<?= $this->codegenerator->encryptChar($tabel);?>">
												<input type="hidden" name="id" value="<?= $this->codegenerator->encryptChar($emp_id);?>">
												<div class="form-group">
													<label>Pilih Karyawan</label>
													<select class="form-control select2" name="id_karyawan[]" id="data_karyawan_add" multiple="multiple" style="width: 100%;" required="required"></select>
												</div>
												<div class="form-group">
													<label>Sebagai</label>
													<div class="row">
														<div class="col-md-12">
															<div class="col-xs-4">
																<label>
																	<input type="radio" name="opsi" class="icheck-class" value="ATS" required="required"> Atasan
																</label>
															</div>
															<div class="col-xs-4">
																<label>
																	<input type="radio" name="opsi" class="icheck-class" value="BWH" required="required"> Bawahan
																</label>
															</div>
															<div class="col-xs-4">
																<label>
																	<input type="radio" name="opsi" class="icheck-class" value="RKN"  required="required"> Rekan
																</label>
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
								<?php } ?>
							</div>
						</div>
						<br>
						<div class="row">
							<div class="col-md-12">
								<div class="callout callout-info"><b><i class="fa fa-bullhorn"></i> Petunjuk</b>
									<ul>
										<li>Berikut adalah daftar partisipan yang <b>MENILAI <?=$emp_nama;?></b>.</li>
										<li>Rancangan <?=$nama;?> berkaitan langsung dengan agenda yang <b class="text-red">BELUM TERVALIDASI</b>, pastikan data diupdate dengan benar!</li>
										<li>Jika Anda melakukan proses <b>hapus (delete)</b> maka nilai yang bersangkutan <b class="text-red">juga akan terhapus</b> pada <b class="text-red">AGENDA TERKAIT</b> dan <b class="text-red">BELUM TERVALIDASI</b>.</b>
										<li><b>Cek kembali data Anda sebelum melakukan perubahan!</b></li>
									</ul>
								</div>
								<form id="form_del">
									<input type="hidden" name="partisipan_hide" value="">
									<input type="hidden" name="table" value="<?= $this->codegenerator->encryptChar($tabel);?>">
									<input type="hidden" name="id" value="<?= $this->codegenerator->encryptChar($emp_id);?>">
									<table id="table_data" class="table table-bordered table-striped table-responsive" width="100%">
										<thead>
											<tr>
												<th>No.</th>
												<th><input type="checkbox" name="select_all" id="select_all" onchange="select_checkbox('#select_all','.partisipan_check','#btn_del','parent')" value="all"> Pilih</th>
												<th>NIK</th>
												<th>Nama Karyawan</th>
												<th>Jabatan</th>
												<th>Bagian</th>
												<th>Lokasi Kerja</th>
												<th>Sebagai</th>
												<th>Sub Bobot Atasan (%)</th>
												<th>Aksi</th>
											</tr>
										</thead>
										<tbody>
										</tbody>
									</table>
									<div class="row">
										<div class="col-md-2">
											<label> Jumlah Sub Bobot Atasan (%)</label>
											<input type="text" class="form-control" readonly="readonly" id="count_sub_bobot">
											<span id="err_msg"></span>
										</div>
									</div>
									<div class="form-group">
										<button type="button" onclick="do_delete_many()" class="btn btn-danger" id="btn_del" disabled="disabled"><i class="fa fa-trash"></i> Hapus Pilihan</button>
										<button type="button" onclick="do_save_bobot()" class="btn btn-success" id="btn_sub_bobot" disabled="disabled"><i class="fa fa-floppy-o"></i> Simpan Bobot</button>
									</div>
								</form>
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
					</div>
					<div class="col-md-6">
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Lokasi Kerja</label>
							<div class="col-md-6" id="data_loker_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Bagian</label>
							<div class="col-md-6" id="data_bagian_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Sebagai</label>
							<div class="col-md-6" id="data_sebagai_view"></div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<?php if (in_array('EDT', $access['access'])) {
					echo '<button type="submit" class="btn btn-info" onclick="edit_modal()"><i class="fa fa-edit"></i> Edit</button>';
				}?>
				<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
			</div>
		</div>
	</div>
</div>
<!-- edit -->
<div id="edit" class="modal fade" role="dialog">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h2 class="modal-title">Edit Data <b class="text-muted header_data"></b></h2>
			</div>
			<div class="modal-body">
				<form id="form_edit">
					<input type="hidden" id="data_id_edit" name="id" value="">
					<input type="hidden" id="data_old_edit" name="old" value="">
					<input type="hidden" name="table" value="<?php echo $this->codegenerator->encryptChar($tabel);?>">
					<input type="hidden" name="id_e" value="<?php echo $this->codegenerator->encryptChar($emp_id);?>">
					<div class="form-group">
						<label>Partisipan <b class="text-muted header_data"></b></label>
					</div>
					<div class="form-group">
						<label>Sebagai</label>
						<div class="row">
							<div class="col-md-12">
								<div class="col-xs-4">
									<label>
										<input type="radio" class="icheck-class" name="opsi_m" value="ATS" required="required"> Atasan
									</label>
								</div>
								<div class="col-xs-4">
									<label>
										<input type="radio" class="icheck-class" name="opsi_m" value="BWH" required="required"> Bawahan
									</label>
								</div>
								<div class="col-xs-4">
									<label>
										<input type="radio" class="icheck-class" name="opsi_m" value="RKN"  required="required"> Rekan
									</label>
								</div>
							</div>
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
<script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
<script type="text/javascript">
  //wajib diisi
  var url_select="<?php echo base_url('global_control/select2_global');?>";
  var table="<?php echo $tabel;?>";
  var code_c="<?php echo $this->codegenerator->encryptChar($kode);?>";
  var id_e="<?php echo $emp_id;?>";
  var column="id_c_sikap";
  $(document).ready(function(){ 
  	$('#table_data').DataTable( {
  		ajax: {
  			url: "<?php echo base_url('concept/view_detail_konsep_sikap/view_all')?>",
  			type: 'POST',
  			data:{access:"<?php echo $this->codegenerator->encryptChar($access);?>",code:code_c,table:table,id:id_e}
  		},
  		scrollX: true,
  		columnDefs: [
  		{   targets: 0, 
  			width: '5%',
  			render: function ( data, type, full, meta ) {
  				return '<center>'+(meta.row+1)+'.</center>';
  			}
  		},
  		{   targets: 8, 
  			width: '10%',
  			render: function ( data, type, full, meta ) {
  				return '<center>'+data+'</center>';
  			}
  		},
      //aksi
      {   targets: 9, 
      	width: '5%',
      	render: function ( data, type, full, meta ) {
      		return '<center>'+data+'</center>';
      	}
      },
      ],
      drawCallback: function() {
      	$('[data-toggle="tooltip"]').tooltip();
      }
   });
  	$('#btn_add_collapse').click(function(){
  		refreshEmp();
	});
  });
  function refreshEmp() {
  	var data={id:id_e,table:table};
  	getSelect2('<?php echo base_url('concept/view_detail_konsep_sikap/get_employee'); ?>','data_karyawan_add',data);
  }
  function checkvalue() {
  	var oTable = $("#table_data").dataTable();
  	var matches = [];
  	var checkedcollection = oTable.$("input[name='partisipan_check']:checked", { "page": "all" });
  	checkedcollection.each(function (index, elem) {
  		matches.push($(elem).val());
  	});
  	$("#form_del input[name='partisipan_hide']").val(matches);
  	select_checkbox('#select_all','.partisipan_check','#btn_del','child');
  }
  function view_modal(id) {
  	var data={id:id_e,id_e:id,table:table};
  	var callback=getAjaxData("<?php echo base_url('concept/view_detail_konsep_sikap/view_one')?>",data);  
  	$('#view').modal('show');
  	$('.header_data').html(callback['nama']);
  	$('#data_nik_view').html(callback['nik']);
  	$('#data_name_view').html(callback['nama']);
  	$('#data_jabatan_view').html(callback['nama_jabatan']);
  	$('#data_loker_view').html(callback['nama_loker']);
  	$('#data_bagian_view').html(callback['bagian']);
  	$('#data_sebagai_view').html(callback['sebagai']);
  	$('input[name="data_id_view"]').val(callback['id']);
  }
  function edit_modal() {
  	var id = $('input[name="data_id_view"]').val();
  	var data={id:id_e,id_e:id,table:table};
  	var callback=getAjaxData("<?php echo base_url('concept/view_detail_konsep_sikap/view_one')?>",data); 
  	$('#view').modal('toggle');
  	setTimeout(function () {
  		$('#edit').modal('show');
  	},600); 
  	$('.header_data').html(callback['nama']);
  	$('#data_id_edit').val(callback['id']);
  	$('#data_old_edit').val(callback['old']);
  	var satuan=callback['sebagai_val'];
  	$('input[name=opsi_m][value='+satuan+']').icheck('checked'); 
  }
  function countbobot(kodex,btnx) {
  	var getBobot = $('input[name="'+kodex+'"]').map(function(){ 
  		return this.value; 
  	}).get();

  	var nomor = getBobot;
  	var total =  parseInt(0);
  	for(i = 0; i <nomor.length; i++){
  		if(nomor[i]==''){
  			total +=  parseInt(0);
  		}else{
  			total +=  parseInt(nomor[i]);
  		}
  	}
  	if(total>100 || total<100){
  		$('#btn_'+btnx).attr('disabled','disabled');
  		$('#count_'+btnx).css('border-color','red');
  		$('#err_msg').html('<i class="fa fa-times"></i> Jumlah Bobot Harus 100%').css('color','red');
  	}else{
  		$('#btn_'+btnx).removeAttr('disabled');
  		$('#count_'+btnx).css('border-color','green');
  		$('#err_msg').html('');
  	}
  	$('#count_'+btnx).val(total);

  }
  function delete_modal(id) {
  	var data={id_c_sikap:id,table:table};
  	var callback=getAjaxData("<?php echo base_url('concept/view_data_konsep_sikap/view_one')?>",data);
  	var datax={table:table,column:column,id:id,nama:callback['nama'],nama_tabel:callback['nama_tabel']};
  	loadModalAjax("<?php echo base_url('pages/load_modal_delete')?>",'modal_delete_partial',datax,'delete');
  }
  function resetCount() {
  	$('#count_sub_bobot').val('').css('border-color','white');
  	$('#btn_sub_bobot').attr('disabled','disabled');
  }
	//doing db transaction
	function do_delete(id,data) {
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
			submitAjax("<?php echo base_url('concept/edt_partisipant')?>",'edit','form_edit',null,null);
			$('#table_data').DataTable().ajax.reload(function (){
				Pace.restart();
			});
			resetCount();
		}else{
			notValidParamx();
		} 
	}
	function do_delete_many() {
		if($("#form_del")[0].checkValidity()) {
			submitAjax("<?php echo base_url('concept/delete_many_partisipant')?>",null,'form_del',null,null);
			$('#table_data').DataTable().ajax.reload(function (){
				Pace.restart();
			});
			$('#form_del')[0].reset();
			$('#btn_del').attr('disabled','disabled');
			$('#select_all').removeAttr('checked').icheck('update'); 
			resetCount();
			refreshEmp();
		}else{
			notValidParamx();
		}
	}
	function do_save_bobot() {
		if($("#form_del")[0].checkValidity()) {
			submitAjax("<?php echo base_url('concept/edt_sub_bobot_atasan')?>",null,'form_del',null,null);
			$('#table_data').DataTable().ajax.reload(function (){
				Pace.restart();
			});
		}else{
			notValidParamx();
		}
	}
	function do_add(){
		if($("#form_add")[0].checkValidity()) {
			submitAjax("<?php echo base_url('concept/add_detail_konsep_sikap')?>",null,'form_add',null,null);
			$('#table_data').DataTable().ajax.reload(function (){
				Pace.restart();
			});
			$('#form_add')[0].reset();
			// $('input[type="radio"]').iCheck('uncheck');
			$('input[type="radio"]').removeAttr('checked').iCheck('update'); 
			refreshEmp();
         	// $('#data_karyawan_add').val([]).trigger('change');
		}else{
			notValidParamx();
		} 
	}  
</script>