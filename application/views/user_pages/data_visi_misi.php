<div class="content-wrapper">

	<section class="content-header">
			<h1><i class="fa fa-info-circle"></i> Sejarah
			<small>Visi & Misi</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo base_url('kpages/dashboard')?>"><i class="fa fas fa-tachometer-alt"></i></a> Dashboard</li>
			<li class="active"><i class="fa fa-university"></i> Sejarah, Visi & Misi</li>
		</ol>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-info">
					<div class="box-header with-border">
						<h3 class="box-title"><i class="fa fa-envelope"></i> Sejarah, Visi & Misi</h3>
						<div class="box-tools pull-right">
							<button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
    						<button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i class="fa fa-times"></i></button>
						</div>
					</div>
					<div class="box-body">
						<div class="row">
							<div class="col-md-12">
								<fieldset>
                  					<legend><i class="fa fa-briefcase"></i> Visi</legend>
									<p align="justify"><?php echo $visi_misi['visi'];?></p>
								</fieldset>
								<br>
								<fieldset>
                  					<legend><i class="fa fa-briefcase"></i> Misi</legend>
									<p align="justify"><?php echo $visi_misi['misi'];?></p>
								</fieldset>
								<br>
								<fieldset>
                  					<legend><i class="fa fa-briefcase"></i> Sejarah Perusahaan</legend>
									<p align="justify"><?php echo $visi_misi['sejarah'];?></p>
								</fieldset>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>