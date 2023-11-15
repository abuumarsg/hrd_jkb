<div class="content-wrapper">

    <section class="content-header">
            <h1><i class="fa fa-newspaper-o"></i> Berita
            <small>Perusahaan</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url('kpages/dashboard')?>"><i class="fa fas fa-tachometer-alt"></i></a> Dashboard</li>
            <li class="active"><i class="fa fa-newspaper-o"></i> Berita Perusahaan</li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-newspaper-o"></i> Berita Perusahaan</h3>
                        <div class="box-tools pull-right">
                            <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                            <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                    	<?php foreach ($berita as $b) { ?>
                    	<div class="row">
	                    	<div class="col-md-12">
                                <div class="col-md-2">
                                    <img class="berita-user-img img-responsive view_photo" style="max-width: 120px;" data-source-photo="<?php echo base_url($b->gambar);?>" src="<?php echo base_url($b->gambar); ?>" alt="<?php echo $b->judul?>">
                                </div>
                                <div class="col-md-10">
                                    <h3 class="mt-0"><a href="<?php echo base_url('kpages/read_berita/'.$this->codegenerator->encryptChar($b->id_berita)) ?>"><?php echo $b->judul?></a></h3>
                                    <h5><i class="fa  fa-calendar"></i>&nbsp;&nbsp;<?php echo $this->formatter->getDateTimeMonthFormatUser($b->create_date);?></h5>
                                        <p><?php 
                                                $ss=$b->isi;
                                                $max = 500;
                                                if (strlen($ss) > $max){
                                                    $offs = ($max - 3) - strlen($ss);
                                                    $ss = substr($ss, 0, strrpos($ss, ' ', $offs)) . '. . .';
                                                    echo $ss;
                                                }else{
                                                    echo $ss;
                                                }
                                            ?>
                                        </p>
                                    <a href="<?php echo base_url('kpages/read_berita/'.$this->codegenerator->encryptChar($b->id_berita))?>"  class="btn btn-success btn-sm"><i class="fa fa-eye"> Lanjutkan Membaca</i></a>
                                </div>
	                    	</div>
                    	</div>
                    	<hr>
                    	<?php } ?>
                    </div>
                    <div class="row">
                        <?php if(isset($paginasi) && $total > $limit) { ?>
                            <div class="paginasi col-md-12 text-center">
                                <?php  echo $paginasi; ?>
                                <div class="clearfix"></div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<style type="text/css">
	.paginasi {
		padding: 10px 20px;
		background-color: #fff;
		border-radius: 10px;
		min-height: 40px;
	}
	.paginasi a {
		padding: 3px 10px;
		background-color: #f60;
		color: #fff;
		border-radius: 3px;
		margin: 3px 1px;
		min-width: 10px;
		text-decoration: none;
	}
	.paginasi strong {
		padding: 3px 10px;
		background-color: #615C5C;
		color: #fff;
		border-radius: 3px;
		margin: 3px 1px;
		min-width: 10px;
	}
	.paginasi a:hover {
		background-color: #B37C06;
	}
</style>