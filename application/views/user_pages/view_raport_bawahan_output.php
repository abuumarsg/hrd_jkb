<?php 
	$link=ucfirst($this->uri->segment(2));
	$id=$idk;
	$foto = $this->otherfunctions->getFotoValue($profile['foto'],$profile['kelamin']);
?> 
<div class="content-wrapper">
	<section class="content-header">
		<h1>
			<a class="back" href="<?php echo base_url('kpages/list_raport_output');?>"><i class="fa fa-chevron-circle-left "></i></a>
			<i class="fa fa-file-text"></i> Raport
			<small><?php echo $profile['nama'];?></small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo base_url('kpages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
        <li><a href="<?php echo base_url('kpages/raport_bawahan_output');?>"><i class="fa fa-calendar"></i> Daftar Agenda</a></li>
			<li><a href="<?php echo base_url('kpages/list_raport_bawahan_output/'.$this->uri->segment(3));?>"><i class="fa fa-users"></i> Daftar Bawahan</a></li>
			<li class="active">Raport <?php echo $profile['nama'];?></li>
		</ol>
	</section>
	<section class="content">
		<?php $this->load->view('_partial/_pa_profile_employee',['profile'=>$profile,'agd'=>$agd]) ?>
		<div class="row">
			<div class="col-md-12">
				<div class="box box-success">
					<div class="box-header with-border">
						<h3 class="box-title"><i class="fa fa-file-text text-red"></i> Rapor Nilai KPI</h3>
						<div class="box-tools pull-right">
							<button class="btn btn-box-tool" onclick="getTable();" data-toggle="tooltip" title="Refresh Data"><i class="fas fa-sync"></i></button>
							<button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
							<button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i class="fa fa-times"></i></button>
						</div>
					</div>
					<div class="box-body">
						<div class="row">
							<div class="col-md-12">
								<div class="div-overflow">
									<table class="table_data table table-striped table-hover display nowrap" style="width: 100%;">
										<thead>
											<tr class="bg-green">
												<th>No.</th>
												<th>Indikator</th>
												<th>Bobot</th>
												<th>Penilai</th>
												<th>Cara Menghitung</th> 
												<th class="text-center">Target</th>
												<?php
												if($profile['kode_periode'] == "BLN") {
													$periode = $this->formatter->getNameOfMonthByPeriode($agd['start'],$agd['end'],$agd['tahun']);
													foreach ($periode as $pkey => $pval) {
														echo '<th class="text-center" colspan="3">'.$pval.'</th>';
													}
												}elseif ($profile['kode_periode'] == "SMT") {
													$bulan=$this->formatter->getMonth();
													echo '<th class="text-center" colspan="3">'.$bulan[$this->otherfunctions->addFrontZero($agd['start'])].' - '.$bulan[$this->otherfunctions->addFrontZero($agd['end'])].'</th>';
												}
												?>
												<th class="text-center">Nilai Akhir</th>
												</tr>
												<tr>
												<th class="bg-green" colspan="6"></th>
												<?php
												if ($profile['kode_periode'] == "BLN") {
													$periode = $this->formatter->getNameOfMonthByPeriode($agd['start'],$agd['end'],$agd['tahun']);
													foreach ($periode as $pkey => $pval) {
														echo '<th class="bg-red text-center">Capaian</th><th class="bg-yellow text-center">Nilai</th><th class="bg-blue text-center">Poin</th>';
													}
												}elseif ($profile['kode_periode'] == "SMT") {
													echo '<th class="bg-red text-center">Capaian</th><th class="bg-yellow text-center">Nilai</th><th class="bg-blue text-center">Poin</th>';
												}
												?>
												<th class="bg-green text-center">Nilai</th>
											</tr>
										</thead>
										<tbody  id="gettable">
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-md-10" style="padding-left: 0px;">
					<div class="box box-success">
						<div class="box-header with-border">
							<h3 class="box-title"><i class="fa fa-filter text-red"></i> Konversi Nilai KPI</h3>
							<div class="box-tools pull-right">
								<button class="btn btn-box-tool" onclick="refreshKonversi()" data-toggle="tooltip" title="Refresh Table"><i class="fas fa-sync"></i></button>
								<button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
								<button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i class="fa fa-times"></i></button>
							</div>
						</div>
						<div class="box-body">
							<div class="row">
								<div class="col-md-12">
									<div style="overflow:auto">
										<table id="table_konversi" class="table table-bordered table-striped table-responsive" width="100%">
											<thead>
												<tr>
													<th>Nama Konversi Nilai KPI </th>
													<th>Rentang Nilai</th>
													<th>Warna</th>
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
				<div class="col-md-2" style="padding-right: 0px;">
					<div class="box box-success">
						<div class="box-header with-border">
							<h3 class="box-title"><i class="fa fa-file-text text-red"></i> Total Nilai Akhir</h3>
							<div class="box-tools pull-right">
								<button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
								<button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i class="fa fa-times"></i></button>
							</div>
						</div>

						<div class="box-body">
							<div class="row">
								<div class="col-md-12">
									<div class="div-overflow">
										<table class="table table-bordered">
											<tr>
												<th class="text-center bg-aqua" style="font-size:17pt">Nilai Akhir KPI</th>
											</tr>
											<tr>
												<th class="text-center" id="nilai_akhir" style="font-size:50pt">0</th>
											</tr>
											<tr>
												<th class="text-center" id="konversi_akhir" style="font-size:20pt">Unknown</th>
											</tr>
										</table>
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
<script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
<script type="text/javascript">
	$(document).ready(function(){
		getTable();
		konversi();
	})
	function getTable() {
		var data={
			table:'<?php echo $nmtb; ?>',
			id:'<?php echo $idk; ?>',
			agenda: '<?php echo base64_encode(serialize($agd)); ?>'
		};
		var callback=getAjaxData("<?php echo base_url('kagenda/getRaport')?>",data);
		$('#gettable').html(callback['tabel']);
		$('#nilai_akhir').html(callback['nilai']).css({"background-color": callback['color'], "color": "white"});
		$('#konversi_akhir').html(callback['huruf']);
	}
	function refreshKonversi() {
		konversi();
	}
 	function konversi() {
 		$('#table_konversi').DataTable({
			ajax: {
				url: "<?php echo base_url('kagenda/getKonversiKpi')?>",
				type: 'POST',
			},
			order:[1,'DESC'],
			scrollX: true,
			bDestroy: true,
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
</script>
