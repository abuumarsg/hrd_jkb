<div class="content-wrapper">
    <?php 
    if (!empty($this->session->flashdata('msgsc'))) {
      echo '<div class="alert alert-success" id="alert-success">'.$this->session->flashdata('msgsc').'</div>';
    }elseif (!empty($this->session->flashdata('msgerr'))) {
      echo '<div class="alert alert-danger" id="alert-success">'.$this->session->flashdata('msgerr').'</div>';
    }
    ?>
    <section class="content-header">
      <h1>
         <a class="back" onclick="window.history.back()"><i class="fa fa-chevron-circle-left "></i></a> <i class="fa fa-bell"></i> Notifikasi
        <small><?php echo $notif['judul'];?></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo base_url('kpages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
        <li><a href="<?php echo base_url('kpages/read_all_notification');?>"><i class="far fa-bell"></i> Semua Notifikasi</a></li>
        <li class="active">Notifikasi <?php echo $notif['judul'];?></li>
      </ol>
    </section>
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box box-solid">
              <div class="box-header">
                <h3 class="box-title"><?php
                  if ($notif['tipe'] == "info") {
                    echo '<i class="fa fa-bullhorn"></i> ';
                  }elseif ($notif['tipe'] == "warning") {
                    echo '<i class="fa fa-warning"></i> ';
                  }else{
                    echo '<i class="fa fa-times-circle"></i> ';
                  }
                 echo $notif['judul'];
                 if ($notif['sifat'] == '1') {
                   echo ' <label class="label label-danger">Penting</label>';
                 }
                 ?>
                 </h3>
                <div class="box-tools pull-right">
                  <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                  <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i class="fa fa-times"></i></button>
                </div>
              </div>
              <div class="box-body">
                <div class="row">
                  <div class="col-md-3">
                    <h4>Keterangan Notifikasi :</h4>
                    <table class="table" style="border: 0px;">
                      <tr>
                        <th>Judul</th>
                        <td><?php echo $notif['judul'];?></td>
                      </tr>
                      <tr>
                        <th>Sifat</th>
                        <td><?php
                          if ($notif['sifat'] == 1) {
                            echo '<i class="fa fa-dot-circle-o" style="color:red;"></i> <label class="label label-danger">Penting</label>';
                          }else{
                            echo '<label class="label label-default">Tidak Penting</label>';
                          }
                        ?></td>
                      </tr>
                      <tr>
                        <th>Tipe</th>
                        <td><?php
                          if ($notif['tipe'] == 'info') {
                            echo '<label class="label label-info"><i class="fa fa-bullhorn"></i> Informasi Pemberitahuan</label>';
                          }elseif ($notif['tipe'] == "warning") {
                            echo '<label class="label label-warning"><i class="fa fa-warning"></i> Peringatan</label>';
                          }else{
                            echo '<label class="label label-danger"><i class="fa fa-times-circle"></i> Larangan</label>';
                          }
                        ?></td>
                      </tr>
                      <tr>
                        <th>Expired</th>
                        <td><?php echo '<i class="fa fa-clock-o text-blue"></i> '.$this->formatter->getDateTimeMonthFormatUser($notif['end_date']).' WIB';?></td>
                      </tr>
                    </table>
                  </div>
                  <div class="col-md-9">
                    <a class="btn btn-danger pull-right" data-toggle="modal" href="#del"><i class="fa fa-trash" data-toggle="tooltip" title="Hapus Notifikasi"></i></a>
                    <div id="del" class="modal fade" role="dialog">
                      <div class="modal-dialog modal-sm modal-danger">
                        <div class="modal-content">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title text-center">Konfirmasi Hapus</h4>
                          </div>
                          <div class="modal-body text-center">
                            <p>Apakah anda yakin akan menghapus data Notifikasi dengan Judul <b><?php echo $notif['judul'];?></b></p>
                          </div>
                          <div class="modal-footer">
                            <?php echo form_open('kagenda/del_notif_users');?>
                            <input type="hidden" name="kode" value="<?php echo $notif['kode_notif'];?>">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-trash"></i> Hapus</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
                            <?php echo form_close();?>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <?php
                          if ($notif['tipe'] == 'info') {
                            echo '<div class="callout callout-info" style="background-color:white;"><label style="font-size: 12pt;" class="text-aqua"><i class="fa fa-bullhorn"></i> Informasi Pemberitahuan';
                          }elseif ($notif['tipe'] == "warning") {
                            echo '<div class="callout callout-warning" style="background-color:white;"><label style="font-size: 12pt;" class="text-yellow"><i class="fa fa-warning"></i> Peringatan';
                          }else{
                            echo '<div class="callout callout-danger" style="background-color:white;"><label style="font-size: 12pt;" class="text-red"><i class="fa fa-times-circle"></i> Larangan';
                          }
                        ?></label><br>
                      <p><?php echo $notif['isi'];?></p>
                      <small>Salam, <br><br><cite title="Source Title"><?php echo $notif['nama_buat'];?></cite></small>
                    </div>
                  </div>
                </div>
              </div>
            </div>
        </div>
      </div>
    </section>
</div>