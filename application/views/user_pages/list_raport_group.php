  <div class="content-wrapper">
    <section class="content-header">
      <h1>
        <i class="fa fa-line-chart"></i> Raport Akhir
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo base_url('kpages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
        <li class="active">Raport Akhir</li>
      </ol>
    </section>
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box box-warning">
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-list"></i> Daftar Penilaian</h3>
              <div class="box-tools pull-right">
                <button class="btn btn-box-tool" onclick="reload_table('table_data')" data-toggle="tooltip" title="Refresh Table"><i class="fas fa-sync"></i></button>
                <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
              <div class="row">
                <div class="col-md-12">
                  <table id="table_data" class="table table-bordered table-striped table-responsive" width="100%">
                    <thead>
                      <tr>
                        <th>No.</th>
                        <th>Tahun</th>
                        <?php 
                          foreach ($periode as $prd) {
                            echo '<th>'.$prd.'</th>';
                          }
                        ?>
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
  var table="agenda_sikap";
  var column="id_a_sikap";
  var count_periode="<?php echo (isset($periode)) ? count($periode)+1 : 0?>";
  $(document).ready(function(){ 
    $('#table_data').DataTable( {
      ajax: {
        url: "<?php echo base_url('kagenda/list_raport_group/view_all')?>",
        type: 'POST',
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
          return data;
        }
      },
      <?php 
        $c=1;
        foreach ($periode as $prd) { ?>
      {   targets: '<?php echo $c;?>',
        width: '15%',
        render: function ( data, type, full, meta ) {
          return data;
        }
      },    
      <?php    $c++;
        }
      ?>   
      ]
    });
  });
</script>