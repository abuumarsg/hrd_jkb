<?php 
$getData = $this->codegenerator->decryptChar($this->uri->segment(3));
?>
<div class="content-wrapper">
	<section class="content-header">
		<h1>
			<a class="back" onclick="window.history.back()"><i class="fa fa-chevron-circle-left "></i></a>
			<i class="fa fa-file-text"></i> Raport
			<small><?php echo $profile['nama'];?></small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo base_url('kpages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
			<li><a href="<?php echo base_url('kpages/list_raport_group');?>"><i class="fa fa-calendar"></i> Daftar Agenda</a></li>
			<li class="active">Raport <?php echo $profile['nama'];?></li>
		</ol>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-md-3">
				<div class="box box-primary">
					<div class="box-body box-profile">
						<img class="profile-user-img img-responsive img-circle view_photo" src="<?php echo base_url($profile['foto']); ?>" alt="User profile picture">

						<h3 class="profile-username text-center"><?php echo ucwords($profile['nama']); ?></h3>

						<p class="text-muted text-center"><?php 
						if ($profile['nama_jabatan'] == "") {
							echo '<label class="label label-danger text-center">Tidak Punya Jabatan</label>';
						}else{
							echo $profile['nama_jabatan']; 
						}
						?></p>

						<ul class="list-group list-group-unbordered"> 
							<li class="list-group-item">
								<b>Terdaftar Sejak</b> <label class="pull-right label label-primary"><?php echo $this->formatter->getDateMonthFormatUser($profile['tgl_masuk']); ?></label>
							</li>
							<li class="list-group-item">
								<b>Periode Penilaian</b> <label class="pull-right label-wrap label-success"><?php echo (isset($kode['kode_periode']))?((!empty($kode['kode_periode']))?$this->model_master->getListPeriodePenilaianActive()[$kode['kode_periode']]:'Tahunan'):'Tahunan'; ?></label>
							</li>
							<li class="list-group-item">
								<b>Tahun</b> <label class="pull-right label label-warning"><?php echo (isset($kode['tahun']))?((!empty($kode['tahun']))?$kode['tahun']:'Unknown'):'Unknown'; ?></label>
							</li>
						</ul>
					</div>
				</div>
			</div>
			<div class="col-md-9">
				<div class="nav-tabs-custom">
					<ul class="nav nav-tabs">
						<li class="active"><a href="#info" data-toggle="tab">Informasi Umum</a></li>
					</ul>
					<div class="tab-content">
						<div class="tab-pane active" id="info">
							<div class="row">
								<div class="col-md-12">
									<table class='table table-bordered table-striped table-hover'>
										<tr>
											<th>Nama Lengkap</th>
											<td><?php echo ucwords($profile['nama']);?></td>
										</tr>
										<tr>
											<th>Email</th>
											<td><?php 
											if ($profile['email'] == "") {
												echo '<label class="label label-danger">Email Tidak Ada</label>';
											}else{
												echo $profile['email'];
											}
											?></td>
										</tr>
										<tr>
											<th>Username</th>
											<td><?php echo $profile['nik'];?></td>
										</tr>
										<tr>
											<th>Jenis Kelamin</th>
											<td><?php 
											if($profile['kelamin'] == 'l'){
												echo '<i class="fa fa-male" style="color:blue"></i> Laki-laki';
											}else{
												echo '<i class="fa fa-female" style="color:#ff00a5"></i> Perempuan';
											}?></td>
										</tr>
										<tr> 
											<th>Jabatan</th>
											<td><?php 
											if ($profile['nama_jabatan'] == "") {
												echo '<label class="label label-danger text-center">Tidak Punya Jabatan</label>';
											}else{
												echo $profile['nama_jabatan']; 
											}?></td>
										</tr>  
										<tr> 
											<th>Bagian</th>
											<td><?php 
											if ($profile['bagian'] == "") {
												echo '<label class="label label-danger text-center">Tidak Punya Bagian</label>';
											}else{
												echo $profile['bagian']; 
											}?></td>
										</tr> 
										<tr>
											<th>Lokasi Kerja</th>
											<td><?php 
											if ($profile['nama_loker'] == "") {
												echo '<label class="label label-danger text-center">Tidak Punya Lokasi Kerja</label>';
											}else{
												echo $profile['nama_loker']; 
											}
											?></td>
										</tr> 
									</table>
								</div>
							</div>
						</div>
					</div>
				</div> 
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="box box-success">
					<div class="box-header with-border">
						<h3 class="box-title"><i class="fas fa-file-text text-red"></i> Rapor Nilai Gabungan</h3>
						<div class="box-tools pull-right">
							<button class="btn btn-box-tool" onclick="reload_data('table_data')" data-toggle="tooltip" title="Refresh Data"><i class="fas fa-sync"></i></button>
							<button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
							<button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i class="fa fa-times"></i></button>
						</div>
					</div>
					<div class="box-body">
						<div id="table_data">

						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-7">
				<div class="box box-success">
					<div class="box-header with-border">
						<h3 class="box-title"><i class="fas fa-bullhorn text-red"></i> Komentar Dan Saran</h3>
						<div class="box-tools pull-right">
							<button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
							<button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i class="fa fa-times"></i></button>
						</div>
					</div>
					<div class="box-body">
						<div class="row">
							<div class="col-md-12">
								<div class="nav-tabs-custom">
									<ul class="nav nav-tabs">
										<li class="active"><a href="#tab_admin" data-toggle="tab" id="tab_info" onclick="saran_admin();">Saran Atasan</a></li>
										<li><a href="#tab_sikap" data-toggle="tab" id="tab_update_info" onclick="saran_sikap();">Saran Aspek Sikap</a></li>
									</ul>
									<div class="tab-content">
										<div class="tab-pane active" id="tab_admin">
											<div class="">
												<h4>Komentar Dan Saran Admin</h4>
												<div class="box-tools pull-right">
												</div>
												<table id="table_saran" class="table table-bordered table-striped table-responsive table_konversi" width="100%">
													<thead>
														<tr>
															<th>No.</th>
															<th>Komentar dan Saran</th>
															<th>Aksi</th>
														</tr>
													</thead>
													<tbody>
													</tbody>
												</table>
											</div>
										</div>

										<div class="tab-pane" id="tab_sikap">
											<div class="">
												<h4>Komentar Dan Saran Sikap</h4>
												<div class="box-tools pull-right">
												</div>
												<table id="table_sikap" class="table table-bordered table-striped table-responsive table_konversi" width="100%">
													<thead>
														<tr>
															<th>No.</th>
															<th>Aspek</th>
															<th>Kuisioner</th>
															<th>Komentar dan Saran</th>
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
					</div>
				</div>
			</div>
			<div class="col-md-5">
				<div class="box box-success">
					<div class="box-header with-border">
						<h3 class="box-title"><i class="fas fa-file-text text-red"></i> Konversi Nilai</h3>
						<div class="box-tools pull-right">
							<button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
							<button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i class="fa fa-times"></i></button>
						</div>
					</div>
					<div class="box-body">
						<div class="row">
							<div class="col-md-12">
								<!-- Custom Tabs -->
								<div class="nav-tabs-custom">
									<ul class="nav nav-tabs">
										<li class="active"><a href="#k_gabungan" data-toggle="tab" onclick="getDataKonversi('table_k_gabungan','k_gabungan')">Gabungan</a></li>
										<li><a href="#k_presensi" onclick="getDataKonversi('table_k_presensi','k_presensi')" data-toggle="tab">Presensi</a></li>
										<li><a href="#k_kpi" data-toggle="tab" onclick="getDataKonversi('table_k_kpi','k_kpi')">KPI</a></li>
										<li><a href="#k_sikap" data-toggle="tab" onclick="getDataKonversi('table_k_sikap','k_sikap')">Aspek Sikap</a></li>
									</ul>
									<div class="tab-content">	
										<div class="tab-pane active" id="k_gabungan">
											<h4>Konversi Gabungan</h4>
											<div id="table_view_k_gabungan"></div>
										</div>									
										<div class="tab-pane" id="k_presensi">
											<h4>Konversi Presensi</h4>
											<div id="table_view_k_presensi"></div>
										</div>
										<div class="tab-pane" id="k_kpi">
											<h4>Konversi KPI</h4>
											<div id="table_view_k_kpi"></div>
										</div>
										<div class="tab-pane" id="k_sikap">
											<h4>Konversi Aspek Sikap</h4>
											<div id="table_view_k_sikap"></div>
										</div>										
									</div>
								</div>
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
					<div class="col-md-12">
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Saran</label>
							<div class="col-md-6" id="data_saran_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Dikirim Tanggal</label>
							<div class="col-md-6" id="data_create_date_view"></div>
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
<script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
<script type="text/javascript">
	$(document).ready(function(){
		reload_data('table_data');
		reload_konversi('table_konversi');
		saran_admin();
		getDataKonversi('table_k_gabungan','k_gabungan');
	});
	function reload_data(id_data) {
		var data={data:'<?php echo $this->codegenerator->encryptChar($kode);?>'};
		var callback=getAjaxData("<?php echo base_url('kagenda/report_value_group')?>",data); 
		$('#'+id_data).html(callback['table_view']);
		$('#poin_old').html(callback['poin_old']);
		$('#poin_akhir').html(callback['poin_akhir']);
	}
	function reload_konversi(id_konversi) {
		var data={data:'<?php echo $this->codegenerator->encryptChar($kode);?>'};
		var callback=getAjaxData("<?php echo base_url('kagenda/report_value_group_koversi')?>",data); 
		$('#'+id_konversi).html(callback['table_view']);
	}

	function view_modal(id) {
		var data={id_saran:id};
		var callback=getAjaxData("<?php echo base_url('kagenda/saran_penilaian/view_one')?>",data);  
		$('#view').modal('show');
		$('#data_saran_view').html(callback['saran']);

		$('#data_create_date_view').html(callback['create_date']+' WIB');
		$('input[name="data_id_view"]').val(callback['id']);
	}
	function saran_admin() {
		$('#table_saran').DataTable( {
			ajax: {
				url: "<?php echo base_url('kagenda/saran_penilaian/view_all/')?>",
				type: 'POST',
				data:{
					id_karyawan:'<?php echo $profile['id_karyawan']; ?>',
					periode:'<?php echo $getData; ?>'
				}
			},
			scrollX: true,
			destroy: true,
			columnDefs: [
			{   targets: 0, 
				width: '10%',
				render: function ( data, type, full, meta ) {
					return '<center>'+(meta.row+1)+'.</center>';
				}
			},
			{   targets: 2, 
				width: '50%',
				render: function ( data, type, full, meta ) {
					return full[4];
				}
			},
			]
		});
	}
	function saran_sikap() {
		$('#table_sikap').DataTable();
		$('#table_sikap').DataTable().destroy();
		$('#table_sikap').DataTable( {
			ajax: {
				url: "<?php echo base_url('kagenda/saran_sikap/view_all/')?>",
				type: 'POST',
				data:{
					id_karyawan:'<?php echo $profile['id_karyawan']; ?>',
					periode:'<?php echo $getData; ?>'
				}
			},
			scrollX: true,
			destroy: true,
			columnDefs: [
			{   targets: 0, 
				width: '10%',
				render: function ( data, type, full, meta ) {
					return '<center>'+(meta.row+1)+'.</center>';
				}
			},
			]
		});
	}
	function getDataKonversi(t_id,refresh) {
		var usage=t_id.replace('table_k_','');
	  	//special
	  	var sub_th='';
	  	if (usage == 'presensi') {
	  		sub_th+='<th>Jenis Presensi</th><th>Nilai</th>';
	  	}
		$('#table_view_'+refresh).html(
			'<table id="'+t_id+'" class="table table-bordered table-striped table-responsive table_konversi" width="100%">'+
			'<thead>'+
			'<tr>'+
			'<th>Nama Konversi</th>'+
			'<th>Rentang Nilai</th>'+
			sub_th+
			'<th>Warna</th>'+
			'</tr>'+
			'</thead>'+
			'<tbody>'+
			'</tbody>'+
			'</table>'
			);
		if (refresh != '') {$('#'+t_id).DataTable().destroy();}  	
		$('#'+t_id).DataTable({
			ajax: {
				url: "<?php echo base_url('master/getAllKonversi')?>",
				type: 'POST',
				data:{usage: usage,option:'<?= $this->codegenerator->encryptChar($kode);?>'}
			},
			scrollX: true,
			order:false,
			columnDefs: [
			{   targets: 0, 
				width: '45%',
				render: function ( data, type, full, meta ) {
					return data;
				}
			},
			{   targets: 1,
				width: '45%',
				render: function ( data, type, full, meta ) {
					return '<center>'+data+'</center>';
				}
			},
			{   targets: 2,
				width: '10%',
				render: function ( data, type, full, meta ) {
					return '<center>'+data+'</center>';
				}
			}
			]
		});
	}
	// function send_email(id_approve,id,sebagai) {
	// 	submitAjax("<?php //echo base_url('global_control/send_email_approval_pa')?>",null,{id_approve:id_approve,id:id,sebagai:sebagai,agenda:"<?php //echo (isset($kode['kode_periode']))?((!empty($kode['kode_periode']))?$this->model_master->getListPeriodePenilaianActive()[$kode['kode_periode']].' - '.$kode['tahun']:'Tahunan'):'Tahunan'; ?>"},null,null,'status');
	// 	get_review_data();
	// }
</script>
