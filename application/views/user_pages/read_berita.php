<div class="content-wrapper">

    <section class="content-header">
            <h1><i class="fa fa-newspaper-o"></i> Berita
            <small>Perusahaan</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url('kpages/dashboard')?>"><i class="fa fas fa-tachometer-alt"></i> Dashboard</li></a>
            <li><a href="<?php echo base_url('kpages/data_berita')?>"><i class="fa fa-newspaper-o"></i> Berita</li></a>
            <li class="active"> <?=$nama_berita?></li>
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-8">
                <div class="box box-success">
                    <div class="box-body">
                        <h2 class="text-info"><i class="fa fa-newspaper-o"></i> <?php echo $berita['judul'] ?></h2>
                        <p class="text-muted" style="font-size:9pt;">&nbsp;&nbsp;&nbsp;<i class="fa fa-calendar"></i>&nbsp;&nbsp;<?php echo $this->formatter->getDateTimeMonthFormatUser($berita['create_date']);?></p>
                        <div class="row">
                            <div class="col-md-12">
                                <img class="berita-user-img img-responsive view_photo" style="max-width: 690px;" data-source-photo="<?php echo base_url($berita['gambar']);?>" src="<?php echo base_url($berita['gambar']); ?>" alt="<?php echo $berita['judul']?>">
                               <br>
                                <p align="justify"><?php echo $berita['isi'];?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                 <div class="box box-success">
                    <div class="box-body">
                        <!-- <h3 class="text-info"><i class="fa fa-newspaper-o"></i> Berita - Berita Lainnya</h3>
                        <div class="row">
                            <div class="col-md-12">
                                <hr>
                                <ul>
                                    <?php 
                                    // foreach ($beritaAktif as $b){ 
                                    // echo '<li><a href="'.base_url('kpages/read_berita/'.$this->codegenerator->encryptChar($b->id_berita)).'">'.$b->judul.'</a></li>';
                                    //  } 
                                     ?>
                                </ul>
                            </div>
                        </div> -->
                        <div class="widget">
                            <h3>Arsip Berita</h3>
                            <div id="accordion-first" class="clearfix">
                                <div class="accordion" id="accordion4">
                                        <?php
                                        foreach ($tgl_berita as $tanggal){
                                            $tgl_val=$this->model_master->jumlahTanggalBerita($tanggal->bulan,$tanggal->tahun);
                                            if($tgl_val->num_rows()>0){ ?>
                                            <div class="accordion-group">
                                                <div class="accordion-heading">
                                                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion4"
                                                        href="#<?=$tanggal->bulan?>">
                                                        <em class="fas fa-arrow-alt-circle-right icon-fixed-width"></em> <?=$tanggal->bulan?>
                                                        <?=$tanggal->tahun?></a>
                                                </div>
                                                <div id="<?=$tanggal->bulan?>" class="accordion-body collapse">
                                                    <div class="accordion-inner">
                                                        <ul>
                                                            <?php foreach ($tgl_val->result() as $berita) { ?>
                                                            <li>
                                                                <a href="<?php echo base_url('kpages/read_berita/'.$this->codegenerator->encryptChar($berita->id_berita));?>"> <?=$this->otherfunctions->batasi_kata($berita->judul,3)?></a>
                                                            </li>
                                                            <?php } ?>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php }} ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>