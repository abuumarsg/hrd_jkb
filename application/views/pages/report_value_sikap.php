<div class="content-wrapper">
  <section class="content-header">
    <h1>
      <a class="back" onclick="window.history.back()"><i class="fa fa-chevron-circle-left "></i></a>
      <i class="fa fa-file-text"></i> Raport Sikap (360°)
      <small><?php echo $profile['nama'];?></small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
      <li><a href="<?php echo base_url('pages/data_hasil_sikap');?>"><i class="fas fa-calendar"></i> Daftar Agenda</a></li>
      <li><a href="<?php echo base_url('pages/view_employee_result_sikap/'.$this->uri->segment(3));?>"><i class="fa fa-users"></i> Daftar Karyawan</a></li>
      <li class="active">Raport <?php echo $profile['nama'];?></li>
    </ol>
  </section>
  <section class="content">
    <div class="row">
      <div class="col-md-3">
        <div class="box box-primary">
          <div class="box-body box-profile">
            <img class="profile-user-img img-responsive img-circle view_photo" src="<?php if($profile['foto'] == null){
            if($profile['kelamin'] == 'l'){
                echo base_url('asset/img/user-photo/user.png');
              }else{
                echo base_url('asset/img/user-photo/userf.png');
              }
            }else{
              echo base_url($profile['foto']);
            } ?>" alt="User profile picture">

            <h3 class="profile-username text-center"><?php echo ucwords($profile['nama']); ?></h3>

            <p class="text-muted text-center"><?php 
            if ($profile['nama_jabatan'] == "") {
              echo '<label class="label label-danger text-center">Tidak Punya Jabatan</label>';
            }else{
             echo $profile['nama_jabatan']; 
            }
            ?></p>

            <ul class="list-group list-group-unbordered"> 
              <li class="list-group-item">
                <b>Terdaftar Sejak</b> <label class="pull-right label label-primary"><?php echo $this->formatter->getDateMonthFormatUser($profile['tgl_masuk']); ?></label>
              </li>
              <li class="list-group-item">
								<div class="row">
								<div class="col-md-3">
									<b>Agenda</b>
								</div>
								<div class="col-md-9">
									<label class="pull-right label-wrap label-success" id="nama_agenda"></label>
								</div>
								</div>
							</li>
              <li class="list-group-item">
                <b>Tahun</b> <label class="pull-right label label-info" id="tahun_agenda"></label>
              </li>
              <li class="list-group-item">
                <b>Periode</b> <label class="pull-right label label-warning" id="periode_agenda"></label>
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
                      <td><?php echo $profile['nik'];?></td>
                    </tr>
                    <tr>
                      <th>Jenis Kelamin</th>
                      <td><?php 
                      if($profile['kelamin'] == 'l'){
                        echo '<i class="fa fa-male" style="color:blue"></i> Laki-laki';
                      }else{
                        echo '<i class="fa fa-female" style="color:#ff00a5"></i> Perempuan';
                      }
                      ?></td>
                    </tr>
                    <tr> 
                      <th>Jabatan</th>
                      <td><?php 
                      if ($profile['nama_jabatan'] == "") {
                        echo '<label class="label label-danger text-center">Tidak Punya Jabatan</label>';
                      }else{
                       echo $profile['nama_jabatan']; 
                      }
                      ?></td>
                    </tr>   
                    <tr>
                      <th>Lokasi Kerja</th>
                      <td><?php 
                      if ($profile['nama_loker'] == "") {
                        echo '<label class="label label-danger text-center">Tidak Punya Lokasi Kerja</label>';
                      }else{
                       echo $profile['nama_loker']; 
                      }
                      ?></td>
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
          <h3 class="box-title"><i class="fa fa-file-text text-red"></i> Rapor Nilai Sikap (360°)</h3>
          <div class="box-tools pull-right">
            <button class="btn btn-box-tool" onclick="refreshData()" data-toggle="tooltip" title="Refresh Table"><i class="fas fa-sync"></i></button>
            <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
            <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i class="fa fa-times"></i></button>
          </div>
        </div>
        <div class="box-body">
          <div class="row">
            <div class="col-md-12">
              <div class="callout callout-info"><label><i class="fa fa-info-circle"></i> Bantuan</label><br>Pilih Nama Aspek Sikap untuk melihat keterangan lebih lengkap</div>
              <div id="table_view" style="overflow: auto;"></div>
            </div>
          </div>          
        </div>
      </div>
    </div>
  </div>
  <div class="row">
   <div class="col-md-9">
    <div class="box box-success">
      <div class="box-header with-border">
       <h3 class="box-title"><i class="fa fa-file-text text-red"></i> Konversi Nilai</h3>
       <div class="box-tools pull-right">
        <button class="btn btn-box-tool" onclick="refreshKonversi('refresh')" data-toggle="tooltip" title="Refresh Table"><i class="fas fa-sync"></i></button>
        <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
        <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i class="fa fa-times"></i></button>
       </div>
      </div>
      <div class="box-body">
       <div class="row">
        <div class="col-md-12">
          <table id="table_konversi" class="table table-bordered table-striped table-responsive" width="100%">
            <thead>
              <tr>
                <th>Nama Konversi Nilai Sikap</th>
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
   <div class="col-md-3">
    <div class="box box-success">
      <div class="box-header with-border">
       <h3 class="box-title"><i class="fa fa-file-text text-red"></i> Hasil Akhir</h3>
       <div class="box-tools pull-right">
       </div>
      </div>
      <div class="box-body">
       <div class="row">
        <div class="col-md-12">
          <div id="data_nilai_akhir">
            
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
    var url_select="<?php echo base_url('global_control/select2_global');?>";
    $(document).ready(function(){
      refreshData();
      refreshKonversi('data');
    })
    function refreshData() {
      var datax={table:"<?php echo $this->codegenerator->encryptChar($table);?>",kode_agenda:"<?php echo $this->codegenerator->encryptChar($kode);?>",id:"<?php echo $this->codegenerator->encryptChar($id_kar);?>"}
      var callback=getAjaxData("<?php echo base_url('agenda/report_value_sikap/view_all/')?>",datax);

      $('#nama_agenda').html(callback['nama_agenda']);
      $('#tahun_agenda').html(callback['tahun_agenda']);
      $('#periode_agenda').html(callback['periode_agenda']);
      $('#table_view').html(callback['table_view']);
      $('#data_nilai_akhir').html(callback['nilai_akhir']);
    }
    function refreshKonversi(usage) {
    if(usage == 'refresh'){
      $('#table_konversi').DataTable().destroy();
    }
    $('#table_konversi').DataTable({
      ajax: {
        url: "<?php echo base_url('agenda/report_value_sikap/view_konversi')?>",
        type: 'POST',
        data:{table:"<?php echo $this->codegenerator->encryptChar($table);?>",kode_agenda:"<?php echo $this->codegenerator->encryptChar($kode);?>",id:"<?php echo $this->codegenerator->encryptChar($id_kar);?>"}
      },
      scrollX: true,
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