  <?php $kode = $this->codegenerator->decryptChar($this->uri->segment(3)); ?>
  <div class="content-wrapper">
    <div class="alert alert-info">
      <i class="fa fa-calendar faa-shake animated text-blue" style="font-size: 14pt;"></i>
      <?php 
      if ($agd != "") {
        echo ' <b>Agenda Penilaian Output (Target) '.$agd['nama'].' Tahun '.$agd['tahun'].' Periode '.$agd['nama_periode'].'</b>';
      }
      ?>
    </div> 
    <?php 
    if (!empty($this->session->flashdata('msgsc'))) {
      echo '<div class="alert alert-success" id="alert-success">'.$this->session->flashdata('msgsc').'</div>';
    }elseif (!empty($this->session->flashdata('msgerr'))) {
      echo '<div class="alert alert-danger" id="alert-success">'.$this->session->flashdata('msgerr').'</div>';
    }
    ?>
    <section class="content-header">
      <h1>
       <a class="back" href="<?php echo base_url('kpages/result_tasks');?>"><i class="fa fa-chevron-circle-left "></i></a> <i class="fa fa-users"></i> Daftar Karyawan Penilaian Output (Target)
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo base_url('kpages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
        <li><a href="<?php echo base_url('kpages/result_tasks');?>"><i class="fa fa-calendar"></i> Daftar Agenda</a></li>
        <li class="active">Daftar Karyawan</li>
      </ol>
    </section>
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-users"></i> Daftar Karyawan Penilaian Output (Target)</h3>
              <div class="box-tools pull-right">
                <button class="btn btn-box-tool" onclick="reload_table('table_data')" data-toggle="tooltip" title="Refresh Table">
                  <i class="fa fa-refresh"></i>
                </button>
                <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
              <div class="row">
                <div class="col-md-12">
                  <div class="callout callout-info">
                    <b><i class="fa fa-info-circle"></i> Bantuan</b><br>Pilih NIK untuk melakukan melihat nilai KPI Output
                    <ul>
                      <li>Jika terdapat tanda <i class="fa fa-times-circle text-red fa-fw"></i>, maka Anda <b>BELUM</b> melakukan Penilaian</li>
                      <li>Jika terdapat tanda <i class="fa fa-check-circle text-green fa-fw"></i>, maka Anda <b>SUDAH</b> melakukan Penilaian</li>
                      <li>Jika terdapat tanda <i class="fa fa-refresh fa-spin fa-fw text-yellow"></i>, maka Anda <b>BELUM SELESAI</b> melakukan Penilaian</li>
                    </ul>
                  </div>
                  <table id="table_data" class="table table-bordered table-striped table-responsive" width="100%">
                    <thead>
                      <tr>
                        <th>No.</th>
                        <th>NIK</th> 
                        <th>Nama Karyawan</th> 
                        <th>Jabatan</th>
                        <th>Lokasi Kerja</th>
                        <th>Progress</th>
                        <th>Keterangan</th>
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
      $('#table_data').DataTable( {
        ajax: {
          url: "<?php echo base_url('kagenda/input_employee_tasks/view_all/'.$this->codegenerator->encryptChar($kode))?>"
        },
        processing:true,
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
            return '<a href="<?php echo base_url();?>kpages/report_value_tasks/'+full[7]+'/'+full[8]+'" target="_blank">'+full[6]+' '+data+'</a>';
          }
        },
        {   targets: 2,
          width: '15%',
          render: function ( data, type, full, meta ) {
            return data;
          }
        },
        {   targets: 3,
          width: '10%',
          render: function ( data, type, full, meta ) {
            return data;
          }
        },
        {   targets: 4,
          width: '10%',
          render: function ( data, type, full, meta ) {
            return data;
          }
        },
        {   targets: 5,
          width: '10%',
          render: function ( data, type, full, meta ) {
            return '<center>'+data+'</center>';
          }
        },
        {   targets: 6,
          width: '5%',
          render: function ( data, type, full, meta ) {
            return '<center>'+full[10]+'</center>';
          }
        },
        ],
        drawCallback: function() {
          $('[data-toggle="tooltip"]').tooltip();
        }
      });
    })
  </script>