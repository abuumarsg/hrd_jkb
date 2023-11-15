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
        <i class="fa fa-crosshairs"></i> Target 
        <small>Corporate</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo base_url('kpages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
        <li class="active">Target Corporate</li>
      </ol>
    </section>
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-list"></i> Daftar Target Corporate</h3>
              <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
              <div class="row">
                <div class="col-md-12">
                  <?php 
                  if (count($target) == 0) {
                    echo '<div class="callout callout-danger"><label><i class="fa fa-info-circle"></i> Data Kosong</label><br>Tidak ada data yang ditampilkan</div>';
                  }else{
                  ?>
                  <div class="callout callout-info"><label><i class="fa fa-info-circle"></i> Bantuan</label><br>Pilih Nama Target untuk melihat Data Target Corporate</div>
                  <table id="example1" class="table table-bordered table-striped table-hover">
                    <thead>
                      <tr>
                        <th>No.</th>
                        <th>Nama Target</th>
                        <th>Kaitan Data</th>
                        <th>Keterangan</th>
                        <th>Tanggal</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                        $n=1;
                        foreach ($target as $l) {
                          $tb=$l->nama_tabel;
                          $dat=$this->db->get_where($tb,array('id_target'=>'1'))->row_array();
                          if ($dat['target_growth'] == NULL) {
                            $st='<label class="label label-danger">Data Masih Kosong</label>';
                          }else{
                            $st='<label class="label label-success">Data Terisi</label>';
                          }
                          echo '<tr>
                                  <td width="3%">'.$n.'.</td>
                                  <td><a href="'.base_url('kpages/view_target_corporate/'.$l->kode_target).'">'.$l->nama_target.'</a> ';
                                  echo '<br>';
                                  if ($l->semester == '1') {
                                    echo '<label class="label label-info">Semester 1</label>';
                                  }else{
                                    echo '<label class="label label-success">Semester 2</label>';

                                  }
                                  echo '</td>
                                  <td class="text-center">';
                                  if ($l->kode_agenda == NULL) {
                                    echo '<label class="label label-danger">Data Target Corporate Tidak Berkaitan Dengan Agenda</label>';
                                  }else{
                                    $dta=$this->db->get_where('log_agenda',array('kode_agenda'=>$l->kode_agenda))->row_array();
                                    if ($dta == "") {
                                      echo '<label class="label label-danger">Data Target Corporate Tidak Berkaitan Dengan Agenda</label><br>';
                                    }else{
                                      echo '<p><i class="fa fa-check-circle text-success"></i> Data Target Corporate Terkait dengan '.$dta['nama_agenda'].' Semester '.$dta['semester'].'</p>';
                                    }
                                  }
                                  echo '</td>
                                  <td class="text-center">'.$st.'</td>
                                  <td class="text-center"><label class="label label-warning" data-toggle="tooltip" title="Dibuat Tanggal"><i class="fa fa-pencil"></i> '.$this->formatter->getDateTimeMonthFormatUser($l->create_date).' WIB</label><br><label class="label label-primary" data-toggle="tooltip" title="Diupdate Tanggal"><i class="fa fa-edit"></i> '.$this->formatter->getDateTimeMonthFormatUser($l->update_date).' WIB</label></td>
                                  ';
                          $n++;      
                        }
                      ?>
                    </tbody>
                  </table>
                  <?php } ?>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div> 