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
       <i class="fa fa-tasks"></i> Daftar Penilaian Output (Target) 
        <small>Hasil Nilai</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo base_url('kpages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
        <li class="active">Daftar Agenda Penilaian</li>
      </ol>
    </section>
    <section class="content">
      <div class="row"> 
        <div class="col-md-12">
          <div class="box box-danger">
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-calendar"></i> Daftar Agenda</h3>
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
                  <div class="callout callout-info"><label><i class="fa fa-info-circle"></i> Bantuan</label><br>
                  <ul>
                    <li>Pilih Nama Agenda untuk melihat list Karyawan yang Anda nilai.</li>
                    <li>Jika Keterangan menunjukkan <label class="label label-success"><i class="fa fa-check"></i> Nilai Sudah Divalidasi</label> maka Anda dapat melihat nilai dari partisipan yang Anda beri nilai.</li>
                    <li>Jika Keterangan menunjukkan <label class="label label-danger"><i class="fa fa-times"></i> Nilai Belum Divalidasi</label> maka Anda <b>TIDAK</b> dapat melihat nilai dari partisipan yang Anda beri nilai.</li>
                  </ul>
                  </div>
                  <table id="table_data" class="table table-bordered table-striped table-responsive" width="100%">
                    <thead>
                      <tr>
                        <th>No.</th>
                        <th>Nama Agenda</th>
                        <th>Progress</th>
                        <th>Tanggal Agenda</th>
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
        url: "<?php echo base_url('kagenda/data_input_tasks/view_all')?>"
      },
      scrollX: true,
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
          return '<a href="<?php echo base_url();?>kpages/result_employee_tasks/'+full[5]+'">'+data+'<br><label class="label label-primary">'+full[6]+'</label><label class="label label-info">'+full[7]+'</label></a>';
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
          return '<center>'+data+'</center>';
        }
      },
      ],
        drawCallback: function() {
          $('[data-toggle="tooltip"]').tooltip();
        }
    });
  });
</script>