<div class="content-wrapper">
	<section class="content-header">
			<h1><i class="fa fa-sitemap"></i> Struktur
			<small>Organisasi</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo base_url('kpages/dashboard')?>"><i class="fa fas fa-tachometer-alt"></i></a> Dashboard</li>
			<li class="active"><i class="fa fa-sitemap"></i> Struktur Organisasi</li>
		</ol>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-info">
					<div class="box-header with-border">
						<h3 class="box-title"><i class="fa fa-sitemap"></i> Struktur Organisasi</h3>
						<div class="box-tools pull-right">
							<button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
    						<button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i class="fa fa-times"></i></button>
						</div>
					</div>
					<div class="box-body">
						<div class="row">
							<?php foreach ($struktur as $s) { ?>
							<div class="col-md-12">
								<fieldset>
                  					<legend><i class="fa fa-sitemap"></i> Struktur Organisasi Unit <?php echo $s->nama_lokasi ?></legend>
									 <img class="berita-user-img img-responsive view_photo" style="max-width: 700px;" data-source-photo="<?php echo base_url($s->gambar);?>" src="<?php echo base_url($s->gambar); ?>" alt="<?php echo $s->judul?>">
								</fieldset>
								<br>
							</div>
						<?php } ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>