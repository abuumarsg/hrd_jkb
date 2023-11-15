  <div class="content-wrapper">
    <div class="alert alert-info">
      <i class="fa fa-calendar faa-shake animated text-blue" style="font-size: 14pt;"></i>
      <?php 
      if ($agd != "") {
        echo ' <b>Agenda Penilaian Sikap (360°) '.$agd['nama_agenda'].' Tahun '.$agd['tahun'].' '.$agd['periode'].'</b>';
      }
      ?>
    </div>
    <section class="content-header">
      <h1>
       <a class="back" onclick="window.history.back()"><i class="fa fa-chevron-circle-left "></i></a> <i class="fa fa-users"></i> Daftar Karyawan Sikap
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo base_url('kpages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
        <li><a href="<?php echo base_url('kpages/result_attd_tasks');?>"><i class="fa fa-calendar"></i> Daftar Agenda</a></li>
        <li class="active">Daftar Karyawan</li>
      </ol>
    </section>
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-users"></i> Daftar Karyawan Sikap</h3>
              <div class="box-tools pull-right">
                <button class="btn btn-box-tool" onclick="reload_table('table_data')" data-toggle="tooltip" title="Refresh Table"><i class="fa fa-refresh"></i></button>
                <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
              <div class="row">
                <div class="col-md-12">
                  <div class="callout callout-info">
                      <b><i class="fa fa-info-circle"></i> Bantuan</b><br>Pilih Nama Karyawan untuk melihat Detail Input Penilaian Sikap
											<ul>
												<li>Jika terdapat tanda <i class="fa fa-times-circle text-red fa-fw"></i>, maka Anda <b>BELUM</b> melakukan Penilaian</li>
												<li>Jika terdapat tanda <i class="fa fa-check-circle text-green fa-fw"></i>, maka Anda <b>SUDAH</b> melakukan Penilaian</li>
												<li>Jika terdapat tanda <i class="fa fa-refresh fa-spin fa-fw text-yellow"></i>, maka Anda <b>BELUM SELESAI</b> melakukan Penilaian</li>
											</ul>
                    </div>
                  <table  id="table_data" class="table table-bordered table-striped table-hover" width="100%">
                    <thead>
                      <tr>
                        <th>No.</th>
                        <th>Nama Karyawan</th>
                        <th>Jabatan</th>
                        <th>Lokasi Kerja</th>
                        <th>Bagian</th>
                        <th>Anda Sebagai</th>
                        <th>Status</th> 
                        <th>Nilai Anda</th>
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
      </div>
    </section>
  </div> 

<script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
  <script type="text/javascript">
    var url_select="<?php echo base_url('global_control/select2_global');?>";
    $(document).ready(function(){
      var t=$('#table_data').DataTable( {
        ajax: {
          url: "<?php echo base_url('kagenda/input_attitude_task_value/view_all/'.$this->codegenerator->encryptChar($kode))?>"
        },
        processing:true,
        order:[7,'desc'],
        columnDefs: [
        {   targets: 0, 
          width: '5%',
          render: function ( data, type, full, meta ) {
            return '<center>'+(meta.row+1)+'.</center>';
          }
        },
        {   targets: 1,
          width: '15%',
          render: function ( data, type, full, meta ) {
            return full[8];
          }
        },
        {   targets: 6,
          width: '10%',
          render: function ( data, type, full, meta ) {
            return '<center>'+data+'</center>';
          }
        },
        {   targets: 7,
          width: '10%',
          render: function ( data, type, full, meta ) {
            return '<center>'+data+'</center>';
          }
        },
        ]
      });
      t.on( 'order.dt search.dt', function () {
        t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
      }).draw();
    })
  </script>