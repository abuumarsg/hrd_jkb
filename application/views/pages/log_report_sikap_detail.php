<div class="content-wrapper">
  <section class="content-header">
    <h1>
      <a class="back" onclick="window.history.back()"><i class="fa fa-chevron-circle-left "></i></a>
      <i class="fa fa-file-text"></i> Raport Sikap (360°)
      <small><?php echo $nama?></small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
      <li><a href="<?php echo base_url('pages/log_agenda');?>"><i class="fa fa-calendar"></i> Daftar Log Agenda</a></li>
      <li><a href="<?php echo base_url('pages/result_log_sikap/'.$agd['kode_agenda']);?>"><i class="fa fa-users"></i> Daftar Karyawan</a></li>
      <li><a href="<?php echo base_url('pages/log_report_sikap/'.$agd['kode_agenda'].'/'.$id);?>"><i class="fa fa-users"></i> Raport <?php echo $nama;?></a></li>
      <li class="active">Detail Raport</li>
    </ol>
  </section>
  <section class="content">
  <div class="row">
    <div class="col-md-12">
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-file-text-o text-red"></i> Rapor Nilai Sikap (360°) <?php echo $nama;?></h3>
          <div class="box-tools pull-right">
            <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
            <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i class="fa fa-times"></i></button>
          </div>
        </div>
        <div class="box-body">
          <div class="callout callout-success"><label><i class="fa fa-tags"></i> Aspek Sikap <?php echo $aspek['nama_aspek']; ?></label></div>
          <div class="row">
            <div class="col-md-12">
              <div class="row">
                <div class="col-md-4"></div>
                <div class="col-md-4">
                  <table class="table">
                    <?php 

                      foreach ($partx as $aa) {
                        $a1=explode(':', $aa);
                        if ($a1[0] == "ATS") {
                          $ns=1;
                          foreach ($rt_ats as $rtaa) {
                            if ($rtaa != 0) {
                              $rt1[$ns]=$rtaa;
                            }
                            $ns++;
                          }
                          if (isset($rt1)) {
                            $rta=array_sum($rt1)/count($rt1);
                            echo '<tr>
                                    <th class="bg-blue text-center">Rata-Rata Atasan</th>
                                    <td class="text-center">'.number_format($rta,2,',',',').'</td>
                                  </tr>';
                          }
                        }
                        if ($a1[0] == "BWH") {
                          $ns1=1;
                          foreach ($rt_bwh as $rtab) {
                            if ($rtab != 0) {
                              $rt1b[$ns1]=$rtab;
                            }
                            $ns1++;
                          }
                          if (isset($rt1b)) {
                            $rtab=array_sum($rt1b)/count($rt1b);
                            echo '<tr>
                                    <th class="bg-blue text-center">Rata-Rata Bawahan</th>
                                    <td class="text-center">'.number_format($rtab,2,',',',').'</td>
                                  </tr>';
                          }
                        }
                        if ($a1[0] == "RKN") {
                          $ns2=1;
                          foreach ($rt_rkn as $rtar) {
                            if ($rtar != 0) {
                              $rt1r[$ns2]=$rtar;
                            }
                            $ns2++;
                          }
                          if (isset($rt1r)) {
                            $rtar=array_sum($rt1r)/count($rt1r);
                            echo '<tr>
                                    <th class="bg-blue text-center">Rata-Rata Rekan</th>
                                    <td class="text-center">'.number_format($rtar,2,',',',').'</td>
                                  </tr>';
                          }
                        }
                      }
                    ?>
                    
                  </table>
                </div>
              </div>
              <div style="overflow: scroll;">
                <table id="example1" class="table table-striped table-hover table-bordered">
                  <thead>
                    <tr class="bg-green">
                      <th>No.</th>
                      <th>Kuisioner</th>
                      
                      <?php 
                        foreach ($part as $p) {
                          $p1=explode(':', $p);
                          $ikd=$p1[1];
                          $kr=$this->db->query("SELECT nama FROM karyawan WHERE id_karyawan = '$ikd'")->row_array();
                          if ($p1[0] == 'DRI') {
                            echo '<th style="font-size:8pt;" class="text-center" colspan="2">'.$kr['nama'].'<br>(<i class="fa fa-user"></i> Diri Sendiri)</th>';
                          }
                          if ($p1[0] == 'ATS') {
                            echo '<th style="font-size:8pt;" class="text-center" colspan="2">'.$kr['nama'].'<br>(<i class="fa fa-star"></i> Atasan)</th>';
                          }
                          if ($p1[0] == 'BWH') {
                            echo '<th style="font-size:8pt;" class="text-center" colspan="2">'.$kr['nama'].'<br>(Bawahan)</th>';
                          }
                          if ($p1[0] == 'RKN') {
                            echo '<th style="font-size:8pt;" class="text-center" colspan="2">'.$kr['nama'].'<br>(Rekan)</th>';
                          }
                        }
                      ?>
                    </tr>
                    <tr>
                      <th class="bg-green" colspan="2"></th>
                      <?php 
                        foreach ($part as $p) {
                          echo '<th class="text-center bg-blue">Nilai</th>
                          <th class="text-center bg-aqua">Keterangan</th>';
                        }
                      ?>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $n=1;
                      foreach ($kuis as $k) {
                        echo '<tr>
                          <td>'.$n.'.</td>
                          <td width="50%">'.$k.'</td>';
                          foreach ($part as $pp) {
                            $pp1=explode(':', $pp);
                            if ($pp1[0] == "DRI") {
                              echo '<td class="text-center bg-info">'.$n_dri[$n].'</td>
                              <td>'.$k_dri[$n].'</td>';
                              $no[$pp][]=$n_dri[$n];
                            }
                            if ($pp1[0] == "ATS") {
                              $nx[$n]=array_filter(explode(';', $n_ats[$n]));
                              foreach ($nx[$n] as $nn) {
                                $ne[$n]=explode(':', $nn);
                                $ni[$n][$ne[$n][0]]=$ne[$n][1];
                              }
                              $nx1[$n]=array_filter(explode(';', $k_ats[$n]));
                              foreach ($nx1[$n] as $nn1) {
                                $ne1[$n]=explode(':', $nn1);
                                $ni1[$n][$ne1[$n][0]]=$ne1[$n][1];
                              }
                              if (isset($ni[$n][$pp1[1]])) {
                                $na[$n]=$ni[$n][$pp1[1]];
                                if (isset($ni1[$n][$pp1[1]])) {
                                  $ke[$n]=$ni1[$n][$pp1[1]];
                                }else{
                                  $ke[$n]='<label class="label label-default">Tidak Ada Komentar</label>';
                                }
                                
                              }else{
                                $na[$n]=0;
                                $ke[$n]='<label class="label label-default">Tidak Ada Komentar</label>';

                              }                              
                              echo '<td class="text-center bg-info">'.$na[$n].'</td>
                              <td>'.$ke[$n].'</td>';
                              $no[$pp][]=$na[$n];
                            }
                            if ($pp1[0] == "BWH") {
                              $nxb[$n]=array_filter(explode(';', $n_bwh[$n]));
                              foreach ($nxb[$n] as $nnb) {
                                $neb[$n]=explode(':', $nnb);
                                $nib[$n][$neb[$n][0]]=$neb[$n][1];
                              }
                              $nx1b[$n]=array_filter(explode(';', $k_bwh[$n]));
                              foreach ($nx1b[$n] as $nn1b) {
                                $ne1b[$n]=explode(':', $nn1b);
                                $ni1b[$n][$ne1b[$n][0]]=$ne1b[$n][1];
                              }
                              if (isset($nib[$n][$pp1[1]])) {
                                $nab[$n]=$nib[$n][$pp1[1]];
                                if (isset($ni1b[$n][$pp1[1]])) {
                                  $keb[$n]=$ni1b[$n][$pp1[1]];
                                }else{
                                  $keb[$n]='<label class="label label-default">Tidak Ada Komentar</label>';
                                }
                                
                              }else{
                                $nab[$n]=0;
                                $keb[$n]='<label class="label label-default">Tidak Ada Komentar</label>';

                              }
                              echo '<td class="text-center bg-info">'.$nab[$n].'</td>
                              <td>'.$keb[$n].'</td>';
                              $no[$pp][]=$nab[$n];
                            }
                            if ($pp1[0] == "RKN") {
                              $nxr[$n]=array_filter(explode(';', $n_rkn[$n]));
                              foreach ($nxr[$n] as $nnr) {
                                $ner[$n]=explode(':', $nnr);
                                $nir[$n][$ner[$n][0]]=$ner[$n][1];
                              }
                              $nx1r[$n]=array_filter(explode(';', $k_rkn[$n]));
                              foreach ($nx1r[$n] as $nn1r) {
                                $ne1r[$n]=explode(':', $nn1r);
                                $ni1r[$n][$ne1r[$n][0]]=$ne1r[$n][1];
                              }
                              if (isset($nir[$n][$pp1[1]])) {
                                $nar[$n]=$nir[$n][$pp1[1]];
                                
                                if (isset($ni1r[$n][$pp1[1]])) {
                                  $ker[$n]=$ni1r[$n][$pp1[1]];
                                }else{
                                  $ker[$n]='<label class="label label-default">Tidak Ada Komentar</label>';
                                }
                              }else{
                                $nar[$n]=0;
                                $ker[$n]='<label class="label label-default">Tidak Ada Komentar</label>';

                              }
                              echo '<td class="text-center bg-info">'.$nar[$n].'</td>
                              <td>'.$ker[$n].'</td>';
                              $no[$pp][]=$nar[$n];
                            }
                          }
                        echo '
                        <tr>';
                        $n++;
                      }
                      echo '<tr>
                      <td colspan="2" class="text-center bg-navy"><b>Rata - Rata</b></td>';
                      foreach ($part as $o) {
                        echo '<td class="text-center bg-blue"><b>'.number_format((array_sum($no[$o])/count($no[$o])),2,',',',').'</b></td>
                        <td class="text-center bg-navy"></td>';
                      }

                      echo '</tr>';
                    ?>

                  </tbody>
                </table>
              </div>
              
            </div>
            
          </div>
          
        </div>
      </div>
    </div>
  </div>
</section>
</div>