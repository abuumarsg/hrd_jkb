<?php
$foto = $this->otherfunctions->getFotoValue($profile['foto'],$profile['kelamin']);
?>
<div class="content-wrapper">
	<section class="content-header">
		<h1>
			<a class="back" onclick="window.history.back()"><i class="fa fa-chevron-circle-left "></i></a>
			<i class="fas fa-calendar-times"></i> Data Validasi Izin & Cuti
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
			</li>
			<li class="active view_nama_full">Data Validasi Izin & Cuti</li>
		</ol>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-warning">
				<?php
				// $jabatan=$this->model_karyawan->getEmployeeNik($profile['nik'])['jabatan'];
				// $bawahan=$this->model_master->getJabatanBawahan($jabatan);//['kode_jabatan'];
				// $jab = [];
				// foreach ($bawahan as $key) {
				// 	array_push($jab, $key->kode_jabatan);
				// }
				// // $dataz=$this->model_karyawan->getListIzinCutiBawahan($jab);
				// $data =  $this->model_karyawan->getListIzinCutiBawahanKar();
				// $jabatan_x = [];
				// foreach ($data as $d) {
				// 	$jabatan_x[]= $d->jabatan;
				// 	// $dataz = [];
				// 	// if (in_array($jab, $d->jabatan)) {
				// 	// 	foreach ($jabatan as $jab){
				// 	// 		$datax =  $this->model_karyawan->getListIzinCutiBawahanKar($jab);
				// 	// 		array_push($dataz, $datax);
				// 	// 	}
				// 	// }
				// }
				// $dataz = [];
				// if (!in_array($jabatan_x, $jab)) {
				// 	// echo 'oke';
				// 	foreach ($jab as $jab_x => $jab_y){
				// 		$datax =  $this->model_karyawan->getListIzinCutiBawahanKar($jab_y);
				// 		array_push($dataz, $datax);
				// // print_r($jab_x);
				// 	}
				// }else{
				// 	// echo 'gagal';
				// }
				// echo '<pre>';
				// // print_r($jabatan_x);
				// // print_r($jab);
				// print_r($dataz);

				?>
					<div class="box-header with-border">
						<h3 class="box-title"><i class="fas fa-calendar-times"></i> Data Seluruh Izin Cuti yang perlu anda validasi</small></h3>
						<div class="box-tools pull-right">
							<button class="btn btn-box-tool" onclick="reload_table('table_data')" data-toggle="tooltip" title="Refresh Table"><i class="fas fa-sync"></i></button>
							<button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
							<button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i class="fa fa-times"></i></button>
						</div>
					</div>
					<div class="box-body">
						<div class="row">
							<div class="col-md-12">
								<div class="pull-left" style="font-size: 8pt;">
								</div>
							</div>
						</div>
						<table id="table_data" class="table table-bordered table-striped data-table" width="100%">
							<thead>
								<tr>
									<th>No.</th>
									<th>NIK</th>
									<th>Nama</th>
									<th>Jenis</th>
									<th>Nama Jenis</th>
									<th>Tanggal Mulai</th>
									<th>Tanggal Selesai</th>
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
							<label class="col-md-6 control-label">Nomor Izin/Cuti</label>
							<div class="col-md-6" id="data_no_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Jenis Izin/Cuti</label>
							<div class="col-md-6" id="data_jenis_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Mulai</label>
							<div class="col-md-6" id="data_mulai_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Selesai</label>
							<div class="col-md-6" id="data_selesai_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Alasan</label>
							<div class="col-md-6" id="data_alasan_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Mengetahui</label>
							<div class="col-md-6" id="data_mengetahui_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Keterangan</label>
							<div class="col-md-6" id="data_keterangan_view"></div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Status Izin / Cuti</label>
							<div class="col-md-6" id="data_validasi_view">
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
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
			</div>
		</div>
	</div>
</div>
<div id="m_need" class="modal fade" role="dialog">
	<div class="modal-dialog modal-sm modal-default">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title text-center">Validasi Izin / Cuti</h4>
			</div>
			<form id="form_need">
				<div class="modal-body text-center">
					<input type="hidden" id="data_idk_need" name="id_kar">
					<input type="hidden" id="data_id_need" name="id">
					<input type="hidden" id="data_jenis_need" name="jenis">
					<p>Mohon Validasi Izin / Cuti Karyawan atas nama <b id="data_name_need" class="header_data"></b> berikut !!</p>
				</div>
			</form>
			<div class="modal-footer">
				<button type="button" onclick="do_validasi(2,0,'m_need')" class="btn btn-danger"><i class="fa fa-times-circle"></i> Tidak Diizinkan</button>
				<button type="button" onclick="do_validasi(2,1,'m_need')" class="btn btn-success"><i class="fa fa-check-circle"></i> Diizinkan</button>
			</div>
		</div>
	</div>
</div>
<div id="m_yes" class="modal fade" role="dialog">
	<div class="modal-dialog modal-sm modal-default">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title text-center">Validasi Izin / Cuti</h4>
			</div>
			<form id="form_yes">
				<div class="modal-body text-center">
					<input type="hidden" id="data_idk_yes" name="id_kar">
					<input type="hidden" id="data_id_yes" name="id">
					<input type="hidden" id="data_jenis_yes" name="jenis">
					<p>Apakah Anda yakin akan mengubah status izin / cuti dari <b class="text-green">DiIzinkan</b> menjadi <b class="text-red">Tidak Diizinkan</b></b> atas nama karyawan <b id="data_name_yes" class="header_data"></b> ??</p>
				</div>
			</form>
			<div class="modal-footer">
				<button type="button" onclick="do_validasi(1,0,'m_yes')" class="btn btn-danger"><i class="fa fa-times-circle"></i> Tidak Diizinkan</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
			</div>
		</div>
	</div>
</div>
<div id="m_no" class="modal fade" role="dialog">
	<div class="modal-dialog modal-sm modal-default">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title text-center">Validasi Izin / Cuti</h4>
			</div>
			<form id="form_no">
				<div class="modal-body text-center">
					<input type="hidden" id="data_idk_no" name="id_kar">
					<input type="hidden" id="data_id_no" name="id">
					<input type="hidden" id="data_jenis_no" name="jenis">
					<p>Apakah Anda yakin akan mengubah status izin / cuti dari <b class="text-red">Tidak Diizinkan</b> menjadi <b class="text-green">DiIzinkan</b></b> atas nama karyawan <b id="data_name_no" class="header_data"></b> ??</p>
				</div>
			</form>
			<div class="modal-footer">
				<button type="button" onclick="do_validasi(0,1,'m_no')" class="btn btn-success"><i class="fa fa-check-circle"></i> Diizinkan</button>
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
					<p>Apakah anda yakin akan menghapus data dengan nama <b id="data_name_delete" class="header_data"></b> ?</p>
				</div>
			</form>
			<div class="modal-footer">
				<button type="button" onclick="do_delete()" class="btn btn-primary"><i class="fa fa-trash"></i>
					Hapus</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
			</div>
		</div>
	</div>
</div>
<script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
<script type="text/javascript">
	var url_select="<?php echo base_url('global_control/select2_global');?>";
	var table="view_izin_cuti_karyawan";
	var column="id_izin_cuti";
	$(document).ready(function(){
		$('#table_data').DataTable( {
			ajax: {
				url: "<?php echo base_url('kemp/data_validasi_izin/view_all/'.$this->codegenerator->encryptChar($profile['nik']))?>",
				type: 'POST',
				data:{ }
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
						return data;
					}
				},
				{   targets: 8,
					width: '10%',
					render: function ( data, type, full, meta ) {
						return '<center>'+data+'</center>';
					}
				},
				{   targets: 9,
					width: '7%',
					render: function ( data, type, full, meta ) {
						return '<center>'+data+'</center>';
					}
				},
			]
		});
	});
	function modal_need(id) {
		var data={id_izin_cuti:id};
		$('#m_need').modal('toggle');
		var callback=getAjaxData("<?php echo base_url('kemp/data_validasi_izin/view_one')?>",data);
		$('#m_need #data_id_need').val(callback['id']);
		$('#m_need #data_idk_need').val(callback['id_karyawan']);
		$('#m_need #data_jenis_need').val(callback['nama_jenis_ic']);
		$('#m_need .header_data').html(callback['nama']);
	}
	function modal_yes(id) {
		var data={id_izin_cuti:id};
		$('#m_yes').modal('toggle');
		var callback=getAjaxData("<?php echo base_url('kemp/data_validasi_izin/view_one')?>",data);
		$('#m_yes #data_id_yes').val(callback['id']);
		$('#m_yes #data_idk_yes').val(callback['id_karyawan']);
		$('#m_yes #data_jenis_yes').val(callback['nama_jenis_ic']);
		$('#m_yes .header_data').html(callback['nama']);
	}
	function modal_no(id) {
		var data={id_izin_cuti:id};
		$('#m_no').modal('toggle');
		var callback=getAjaxData("<?php echo base_url('kemp/data_validasi_izin/view_one')?>",data);
		$('#m_no #data_id_no').val(callback['id']);
		$('#m_no #data_idk_no').val(callback['id_karyawan']);
		$('#m_no #data_jenis_no').val(callback['nama_jenis_ic']);
		$('#m_no .header_data').html(callback['nama']);
	}
  	function do_validasi(data,val,form){
		if(data==2){
			var id = $('#data_id_need').val();
			var idk = $('#data_idk_need').val();
			var jenis = $('#data_jenis_need').val();
		}else if(data==1){
			var id = $('#data_id_yes').val();
			var idk = $('#data_idk_yes').val();
			var jenis = $('#data_jenis_yes').val();
		}else if(data==0){
			var id = $('#data_id_no').val();
			var idk = $('#data_idk_no').val();
			var jenis = $('#data_jenis_no').val();
		}
		var datax={id_izin_cuti:id,id_k:idk,validasi_db:data,validasi:val,jenis_db:jenis};
		submitAjax("<?php echo base_url('kemp/validasi_izin')?>",form,datax,null,null,'status');
		$('#table_data').DataTable().ajax.reload();
  	}
	function view_modal(id) {
		var data={id_izin_cuti:id};
		var callback=getAjaxData("<?php echo base_url('kemp/data_validasi_izin/view_one')?>",data);  
		$('#view').modal('show');
		$('.header_data').html(callback['nomor']);
		$('#data_nik_view').html(callback['nik']);
		$('#data_nama_view').html(callback['nama']);
		$('#data_no_view').html(callback['nomor']);
		$('#data_jenis_view').html(callback['jenis_cuti']);
		$('#data_mulai_view').html(callback['tanggal_mulai']);
		$('#data_selesai_view').html(callback['tanggal_selesai']);
		$('#data_skd_view').html(callback['skd']);
		$('#data_alasan_view').html(callback['alasan']);
		$('#data_mengetahui_view').html(callback['mengetahui']);
		$('#data_menyetujui_view').html(callback['menyetujui']);
		$('#data_keterangan_view').html(callback['keterangan']);
		$('#data_validasi_view').html(callback['validasi']);
		$('#data_jabatan_view').html(callback['nama_jabatan']);
		$('#data_bagian_view').html(callback['nama_bagian']);
		var status = callback['e_validasi'];
		if(status==2){
			$('#tombol_edit').show();
		}else{
			$('#tombol_edit').hide();
		}
		$('#data_create_date_view').html(callback['create_date']+' WIB');
		$('#data_update_date_view').html(callback['update_date']+' WIB');
		$('input[name="data_id_view"]').val(callback['id']);
		$('#data_create_by_view').html(callback['nama_buat']);
		$('#data_update_by_view').html(callback['nama_update']);
	}
	function delete_modal(id) {
		var table="data_izin_cuti_karyawan";
		var column="id_izin_cuti";
		var data={id_izin_cuti:id};
		$('#delete').modal('toggle');
		var callback=getAjaxData("<?php echo base_url('kemp/data_validasi_izin/view_one')?>",data);
		$('#delete #data_id_delete').val(callback['id']);
		$('#delete #data_column_delete').val(column);
		$('#delete #data_table_delete').val(table);
		$('#delete .header_data').html(callback['nama']);
	}
  	function do_delete(){
		var data_table={atasan_del:0};
		var table = $('#data_table_delete').val();
		var where={id_izin_cuti:$('#data_id_delete').val()};
		var datax={table:table,where:where,data:data_table};
		submitAjax("<?php echo base_url('global_control/change_status')?>",'delete',datax,null,null,'status');
		$('#table_data').DataTable().ajax.reload();
  	}
</script> 