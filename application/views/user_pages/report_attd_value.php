<?php $link=ucfirst($this->uri->segment(2));
$id=$this->uri->segment(4);
?> 
<div class="content-wrapper">
  <section class="content-header">
    <h1>
      <a class="back" onclick="window.history.back()"><i class="fa fa-chevron-circle-left "></i></a>
      <i class="fa fa-file-text"></i> Detail Nilai Sikap (360°)
      <small><?php echo ucwords($profile['nama']);?></small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo base_url('kpages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
      <li><a href="<?php echo base_url('kpages/result_attd_tasks');?>"><i class="fa fa-table"></i> Daftar Agenda</a></li>
      <li><a href="<?php echo base_url('kpages/result_attd_tasks_value/'.$agd['kode_agenda']);?>"><i class="fa fa-users"></i> Daftar Karyawan</a></li>
      <li class="active">Detail Nilai <?php echo ucwords($profile['nama']);?></li>
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
            if ($profile['jabatan'] == "") {
              echo '<label class="label label-danger text-center">Tidak Punya Jabatan</label>';
            }else{
             echo $profile['jabatan']; 
            }
            ?></p>

            <ul class="list-group list-group-unbordered"> 
              <li class="list-group-item">
                <b>Terdaftar Sejak</b> <label class="pull-right label label-primary"><?php echo $this->formatter->getDateTimeMonthFormatUser($profile['tgl_masuk']); ?></label>
              </li>
              <li class="list-group-item">
                <b>Agenda</b> <label class="pull-right label label-success"><?php echo $agd['nama_agenda']; ?></label>
              </li>
              <li class="list-group-item">
                <b>Tahun</b> <label class="pull-right label label-info"><?php echo $agd['tahun'];?></label>
              </li>
              <li class="list-group-item">
                <b>Semester</b> <label class="pull-right label label-warning"><?php echo 'Semester '.$agd['semester']; ?></label>
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
                      <td><?php echo $profile['username'];?></td>
                    </tr>
                    <tr>
                      <th>Jenis Kelamin</th>
                      <td><?php 
                      if($profile['kelamin'] == 'Pria'){
                        echo '<i class="fa fa-male" style="color:blue"></i> Laki-laki';
                      }else{
                        echo '<i class="fa fa-female" style="color:#ff00a5"></i> Perempuan';
                      }?></td>
                    </tr>
                    <tr> 
                      <th>Jabatan</th>
                      <td><?php 
                      if ($profile['jabatan'] == "") {
                        echo '<label class="label label-danger text-center">Tidak Punya Jabatan</label>';
                      }else{
                       echo $profile['jabatan']; 
                      }?></td>
                    </tr>   
                    <tr>
                      <th>Lokasi Kerja</th>
                      <td><?php 
                      if ($profile['loker'] == "") {
                        echo '<label class="label label-danger text-center">Tidak Punya Lokasi Kerja</label>';
                      }else{
                       echo $profile['loker']; 
                      }?></td>
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
          <h3 class="box-title"><i class="fa fa-file-text-o text-red"></i> Detail Nilai Sikap (360°)</h3>
          <div class="box-tools pull-right">
            <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
            <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i class="fa fa-times"></i></button>
          </div>
        </div>
        <div class="box-body">
          <table id="example1" class="table table-hover table-bordered table-striped" width="100%">
            <thead>
              <tr>
                <th>No.</th>
                <th>Kuisioner</th>
                <th>Kategori Aspek</th>
                <th>Nilai</th>
                <th>Keterangan</th>
              </tr>
            </thead>
            <tbody>
              <?php
                $n=1; 
                foreach ($kuisioner as $k) {
                  echo '<tr>
                  <td>'.$n.'.</td>
                  <td>'.$k.'</td>
                  <td class="text-center">';
                  $asps=$this->db->get_where('master_aspek_sikap',array('kode_aspek'=>$kode_asp[$n]))->row_array();
                  echo '<b>'.$asps['nama_aspek'].'</b>';
                  echo '</td>
                  <td class="text-center">';
                  if ($nilai[$n] == "" || $nilai[$n] == NULL) {
                    echo '<label class="label label-danger">Belum Dinilai</label>';
                  }else{
                    echo $nilai[$n];
                  }
                  echo '</td>
                  <td>';
                  if (isset($ket[$n])) {
                    echo $ket[$n];
                  }else{
                    echo '<label class="label label-default">Tidak Ada Keterangan</label>';
                  }
                  echo '</td>
                  </tr>';  
                  $n++;
                }
              ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</section>
</div>