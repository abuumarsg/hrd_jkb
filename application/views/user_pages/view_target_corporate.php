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
        <a class="back" onclick="window.history.back()"><i class="fa fa-chevron-circle-left "></i></a> <i class="fa fa-crosshairs"></i> Target Corporate
        <small><?php echo $nama; ?></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo base_url('kpages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
        <li><a href="<?php echo base_url('kpages/target_corporate');?>"><i class="fa fa-crosshairs"></i> Target Corporate</a></li>
        <li class="active"><?php echo $nama; ?></li> 
      </ol>
    </section>
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-list"></i> <?php echo $nama; 
              ?></h3>
              <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
              
              <div class="row">
                <div class="col-md-12">
                  <?php
                  
                  if (count($view) == 0) {
                    echo '<div class="callout callout-danger"><label><i class="fa fa-info-circle"></i> Data Kosong</label><br>Tidak ada data yang ditampilkan</div>';
                  }else{
                  ?>
                  <table id="example1" class="table table-bordered table-striped table-hover">
                    <thead>
                      <tr>
                        <th>No.</th>
                        <th>Nama Cabang</th>
                        <?php
                          if ($smt == "1") {
                            echo '<th>Anggaran Juni '.$th.'</th>
                            <th>OS Desember '.date("Y",strtotime("-1 Years",strtotime($th))).'</th>
                            <th>Target Growth</th>
                            <th>OS Juni '.$th.'</th>';
                          }else{
                            echo '<th>Anggaran Desember '.$th.'</th>
                            <th>OS Juni '.date("Y",strtotime("-1 Years",strtotime($th))).'</th>
                            <th>Target Growth</th>
                            <th>OS Desember '.$th.'</th>';
                          }
                        ?>
                        <th>Growth</th>
                        <th>Pencapaian</th>
                        <th>Nilai</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                        $n=1;
                        
                        foreach ($view as $l) {
                          if ($l->id_target == 1) {
                            echo '<tr>
                                  <td width="3%" class="bg-warning">'.$n.'.</td>
                                  <td class="bg-warning">'.$l->nama.'</td>';
                                  if ($smt == "1") {
                                    echo '<td class="bg-warning">'.number_format($l->anggaran_juni,0,',','.').'</td>
                                    <td class="bg-warning">'.number_format($l->os_desember,0,',','.').'</td>
                                    <td class="bg-primary">'.number_format($l->target_growth,0,',','.').'</td>
                                    <td class="bg-warning">'.number_format($l->os_juni,0,',','.').'</td>';
                                  }else{
                                    echo '<td>'.number_format($l->anggaran_desember,0,',','.').'</td>
                                    <td class="bg-warning">'.number_format($l->os_juni,0,',','.').'</td>
                                    <td class="bg-primary">'.number_format($l->target_growth,0,',','.').'</td>
                                    <td class="bg-warning">'.number_format($l->os_desember,0,',','.').'</td>';
                                  }

                                  echo '<td class="bg-primary">'.number_format($l->growth,0,',','.').'</td>
                                  <td class="bg-primary">'.$l->pencapaian.'</td>
                                  <td class="bg-primary">'.$l->nilai.'</td>
                                </tr>';
                          }else{
                            echo '<tr>
                                  <td width="3%">'.$n.'.</td>
                                  <td>'.$l->nama.'</td>';
                                  if ($smt == "1") {
                                      echo '<td>'.number_format($l->anggaran_juni,0,',','.').'</td>
                                      <td>'.number_format($l->os_desember,0,',','.').'</td>
                                      <td class="bg-primary">'.number_format($l->target_growth,0,',','.').'</td>
                                      <td>'.number_format($l->os_juni,0,',','.').'</td>';
                                    
                                  }else{
                                      echo '<td>'.number_format($l->anggaran_desember,0,',','.').'</td>
                                      <td>'.number_format($l->os_juni,0,',','.').'</td>
                                      <td class="bg-primary">'.number_format($l->target_growth,0,',','.').'</td>
                                      <td>'.number_format($l->os_desember,0,',','.').'</td>';
                                    
                                  }

                                  echo '<td class="bg-primary">'.number_format($l->growth,0,',','.').'</td>
                                  <td class="bg-primary">'.$l->pencapaian.'</td>
                                  <td class="bg-primary">'.$l->nilai.'</td>
                                </tr>';
                          }
                          
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
        </div>
      </div>
    </section>
  </div> 