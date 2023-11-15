  <div class="content-wrapper">
    <section class="content-header">
      <h1>
        <i class="fas fa-line-chart"></i> Hasil Penilaian 
        <small>Sikap</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
        <li class="active"> Daftar Agenda</li>
      </ol>
    </section>
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box box-success">
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-calendar"></i> Daftar Agenda Sikap</h3>
              <div class="box-tools pull-right">
                <button class="btn btn-box-tool" onclick="reload_table('table_data')" data-toggle="tooltip" title="Refresh Table"><i class="fas fa-sync"></i></button>
                <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
              <div class="row">
                <div class="col-md-12">
                </div>
              </div>
              <br>
              <div class="row">
                <div class="col-md-12">
                  <div class="callout callout-info"><label><i class="fa fa-info-circle"></i> Bantuan</label><br>Pilih <b>Nama Agenda</b> untuk melihat raport Penilaian Sikap</div>
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
        url: "<?php echo base_url('agenda/data_hasil_sikap/view_all')?>",
        type: 'POST',
        data:{access:"<?php echo $this->codegenerator->encryptChar($access);?>"}
      },
      scrollX: true,
      columnDefs: [
      {   targets: 0, 
        width: '3%',
        render: function ( data, type, full, meta ) {
          return '<center>'+(meta.row+1)+'.</center>';
        }
      },
      {   targets: 1,
        width: '25%',
        render: function ( data, type, full, meta ) {
          return '<a href="<?php echo base_url();?>pages/view_employee_result_sikap/'+full[5]+'">'+data+'<br><label class="label label-primary">'+full[6]+'</label><label class="label label-info">Periode '+full[7]+'</label></a>';
        }
      },
      {   targets: 2,
        width: '15%',
        render: function ( data, type, full, meta ) {
          return data;
        }
      },
      {   targets: [3,4],
        width: '5%',
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