<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<?php
		$word='';
		if($update){
			if (strtotime($up_date['tgl_mulai']) < strtotime($tgl) && strtotime($up_date['tgl_selesai']) > strtotime($tgl)) {
				$word.='<i class="fa fa-bell faa-ring animated" style="font-size:14pt;"></i> Harap melakukan update data pribadi Anda pada menu <a href="'.base_url('kpages/profile').'">Profile</a> pada tanggal <b style="color:blue;">'.date('d/m/Y H:i:s',strtotime($up_date['tgl_mulai'])).' WIB</b> sampai <b style="color:blue;">'.date('d/m/Y H:i:s',strtotime($up_date['tgl_selesai'])).' WIB</b>';
			}
			if (!empty($this->session->flashdata('msgsc'))) {
				echo '<div class="alert alert-success" id="alert-success">'.$this->session->flashdata('msgsc').'</div>';
			}elseif (!empty($this->session->flashdata('msgerr'))) {
				echo '<div class="alert alert-danger" id="alert-success">'.$this->session->flashdata('msgerr').'</div>';
			}
		}
		$count_i='';
		$word_valid='';
		// foreach ($data_izin as $izin) {
		// 	$count_i.=count($izin);
			if ($data_izin > 0) {
				$word_valid.='<i class="fas fa-calendar-times faa-ring animated" style="font-size:14pt;"></i>  Anda Memiliki '.$data_izin.' Izin / Cuti Bawahan Anda Untuk segera divalidasi, Untuk memvalidasi Silahkan masuk ke menu <a href="'.base_url('kpages/data_validasi_izin').'"><b style="color:blue;">Validasi Izin / Cuti Bawahan</b></a>';
			}
		// }
		if ($data_izin > 0 || $update) {
			echo '<div class="alert alert-warning">'.$word.'<br>'.$word_valid.'</div>';
		}
	?>
	<section class="content-header">
		<h1> Dashboard <small>Control panel</small></h1>
		<ol class="breadcrumb">
			<li><a href="#"><i class="fas fa-tachometer-alt"></i> Home</a></li>
			<li class="active">Dashboard</li>
		</ol>
	</section>
	<section class="content">
		<!-- <div id="show_event"></div> -->
		<div class="row">
			<div class="col-md-12">
				<div class="callout callout-info">
					<label style="font-size: 12pt;"><i class="fa fa-smile-o"></i> Hallo, <?php echo $nama;?>.</label><br>
					Sistem Penilaian Kinerja <?php echo $this->otherfunctions->companyClientProfile()['name_office'] ?> merupakan suatu perangkat lunak dengan <code>platform web</code> yang terintegrasi dengan proses bisnis yang berjalan pada <?php echo $this->otherfunctions->companyClientProfile()['name_office'] ?>. Untuk memastikan perangkat lunak ini berjalan secara baik, Anda bisa menggunakan browser yang kami rekomendasikan seperti <code>Google Chrome</code> dan <code>Mozilla Firefox</code> dengan versi terbaru, nikmati kemudahan untuk melakukan penilaian dengan sistem kami ini.
					<blockquote class="blockquote-reverse">
						<p class="mb-0" style="font-size: 9pt;"><?php echo $quote['quote'];?></p>
						<footer class="blockquote-footer" style="font-size: 8pt;"><cite
								title="Penulis <?php echo $quote['person'];?>"><?php echo $quote['person'];?></cite>
						</footer>
					</blockquote>
				</div>
				<div class="row">
					<section class="col-lg-7 connectedSortable">
						<div class="box box-info">
							<div class="box-header">
								<i class="fa fa-envelope"></i>
								<h3 class="box-title">Quick Email</h3>
								<div class="pull-right box-tools">
									<button type="button" class="btn btn-info btn-sm" data-widget="remove"
										data-toggle="tooltip" title="Remove"><i class="fa fa-times"></i></button>
								</div>
							</div>
							<div class="box-body">
								<form action="#" method="post">
									<div class="form-group">
										<input type="email" class="form-control" name="emailto" placeholder="Email to:">
									</div>
									<div class="form-group">
										<input type="text" class="form-control" name="subject" placeholder="Subject">
									</div>
									<div>
										<textarea class="textarea" placeholder="Message"
											style="width: 100%; height: 125px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"></textarea>
									</div>
								</form>
							</div>
							<div class="box-footer clearfix">
								<button type="button" class="pull-right btn btn-default" id="sendEmail">Send
									<i class="fa fa-arrow-circle-right"></i></button>
							</div>
						</div>
					</section>
					<section class="col-lg-5 connectedSortable">
						<div class="box box-solid bg-green-gradient">
							<div class="box-header">
								<i class="fa fa-calendar"></i>
								<h3 class="box-title">Calendar</h3>
								<div class="pull-right box-tools">
									<button type="button" class="btn btn-success btn-sm" data-widget="collapse"><i
											class="fa fa-minus"></i>
									</button>
									<button type="button" class="btn btn-success btn-sm" data-widget="remove"><i
											class="fa fa-times"></i>
									</button>
								</div>
							</div>
							<div class="box-body no-padding">
								<div id="calendar" style="width: 100%"></div>
							</div>
						</div>
					</section>
				</div>
			</div>
		</div>
	</section>
</div>
<script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
<script>
	$(document).ready(function () {
		load_event();
	})
</script>