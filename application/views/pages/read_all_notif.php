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
        <i class="fa fa-bell-o"></i> Semua Notifikasi
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
        <li class="active">Semua Notifikasi</li>
      </ol>
    </section>
    <section class="content">
      <?php
        if (count($notif) == 0) {
          echo '<div class="callout callout-danger"><label><i class="fa fa-info-circle"></i> Data Kosong</label><br>Tidak ada data yang ditampilkan</div>';
        }else{
          echo '<div class="list-group">';
          foreach ($notif as $n) { 
            $del=explode(';', $n['id_del']);
            $read=explode(';', $n['id_read']);
            if (!in_array($adm,$del)) {
              echo '<a href="'.base_url('pages/read_notification/'.$n['kode']).'"';
              if (in_array($adm, $read)) {
                echo ' class="list-group-item"';
              }else{
                echo ' class="list-group-item list-group-item-warning"';
              }
              echo '>
                      <h4 class="list-group-item-heading">'.$n['judul'];
                      if ($n['sifat'] == 1) {
                        echo ' <label class="label label-danger">Penting</label>';
                      }
                      echo '<small class="text-muted pull-right">'.$this->formatter->getDateTimeMonthFormatUser($n['start']).' WIB</small></h4><span class="pull-right"> ';
                      if (in_array($adm, $read)) {
                        echo '<i class="fa fa-check" style="color:green;"></i>';
                      }else{
                        echo '<i class="fa fa-circle" style="color:red;"></i>';
                      }
                      echo '</span><p class="list-group-item-text">';
                      if ($n['tipe'] == "info") {
                        echo '<label class="label label-info"><i class="fa fa-bullhorn"></i> Informasi Pemberitahuan</label>';
                      }elseif ($n['tipe'] == "warning") {
                        echo '<label class="label label-warning"><i class="fa fa-warning"></i> Peringatan</label>';
                      }else{
                        echo '<label class="label label-danger"><i class="fa fa-times-circle"></i> Larangan</label>';
                      }
                      echo '</p>
                    </a>';
            }
          }
          echo '</div>';
        
        }
      ?>
    </section>
</div>