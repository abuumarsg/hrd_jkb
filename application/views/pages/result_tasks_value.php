  <div class="content-wrapper">
    <div class="alert alert-info">
      <i class="fa fa-calendar faa-shake animated text-blue" style="font-size: 14pt;"></i>
      <?php 
      if ($agd != "") {
        echo ' <b>Agenda Penilaian KPI Output '.$agd['nama_agenda'].' Tahun '.$agd['tahun'].' Semester '.$agd['semester'].'</b>';
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
       <a class="back" onclick="window.history.back()"><i class="fa fa-chevron-circle-left "></i></a> <i class="fa fa-users"></i> Daftar Karyawan Penilaian Output(Target)
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
        <li><a href="<?php echo base_url('pages/result_tasks');?>"><i class="fa fa-table"></i> Daftar Agenda</a></li>
        <li class="active">Daftar Hasil Penilaian</li>
      </ol>
    </section>
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box box-warning">
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-users"></i> Daftar Karyawan Penilaian Output(Target)</h3>
              <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
              <div class="row">
                <div class="col-md-12">
                  <?php 
                  if (count($tabel) == 0) {
                    echo '<div class="callout callout-danger"><label><i class="fa fa-info-circle"></i> Data Kosong</label><br>Tidak ada data yang ditampilkan</div>';
                  }else{
                    if ($agd['semester'] == 0) {
                      $per="Januari - Desember Tahun ".$agd['tahun'];
                    }elseif ($agd['semester'] == 1) {
                      $per="Januari - Juni Tahun ".$agd['tahun'];
                    }else{
                      $per="Juli - Desember Tahun ".$agd['tahun']; 
                    }
                    $data=array('data'=>$tabel,'periode'=>$agd['nama_agenda'].' '.$per);
                    $dataxx=array('nilai'=>$nilai,'nmtb'=>$nmtb,'user'=>$user,'penilai'=>$penilai,'data'=>$data);
                  ?>
                  <div class="row">
                    <div class="col-md-12">
                        <?php if(in_array($access['l_ac']['rkp'], $access['access'])){?>
                      <div class="pull-left"> 
                        <?php echo form_open('rekap/rekap_nilai_output');?>
                        <input type="hidden" name="data" value="<?php echo base64_encode(serialize($data));?>">
                        <input type="hidden" name="th" value="<?php echo $agd['tahun']; ?>">
                        <input type="hidden" name="smt" value="<?php echo $agd['semester']; ?>">
                        <button type="submit" class="btn btn-flat btn-warning"><i class="fa fa-cloud-download"></i> Rekap Nilai</button>
                        <?php echo form_close();?>
                      </div><?php } if(in_array($access['l_ac']['prn'], $access['access'])){?>
                      <div class="pull-left">
                        <?php echo form_open('pages/print_page');?>
                        <input type="hidden" name="page" value="<?php echo "result_tasks";?>">
                        <input type="hidden" name="data" value="<?php echo base64_encode(serialize($data));?>">
                        <button type="submit" class="btn btn-flat btn-info"><i class="fa fa-print"></i> Print</button>
                        <?php echo form_close();?>
                      </div><?php } if(in_array($access['l_ac']['rkp'], $access['access'])){?>
                      <div class="pull-right">
                        <?php echo form_open('rekap/rekap_partisipan_output');?>
                        <input type="hidden" name="th" value="<?php echo $agd['tahun']; ?>">
                        <input type="hidden" name="smt" value="<?php echo $agd['semester']; ?>">
                        <input type="hidden" name="data" value="<?php echo base64_encode(serialize($dataxx));?>">
                        <button type="submit" class="btn btn-flat btn-danger"><i class="fa fa-users"></i> Rekap Partisipan</button>
                        <?php echo form_close();?>
                      </div><?php } ?>
                    </div>
                  </div>
                  <br>
                  <div class="callout callout-info"><label><i class="fa fa-info-circle"></i> Bantuan</label><br>Pilih Nama Karyawan untuk melihat Rapor Penilaian Kinerja</div>
                  <table id="example1" class="table table-bordered table-striped  table-hover">
                    <thead>
                      <tr>
                        <th>No.</th>
                        <th>NIK</th>
                        <th>Nama Karyawan</th>
                        <th>Jabatan</th>
                        <th>Lokasi Kerja</th>
                        <th>Jumlah Indikator</th>
                        <th class="text-center">Nilai Target</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                        $n1=1;
                        foreach ($tabel as $k => $l) {
                          echo '<tr>
                                    <td width="3%">'.$n1.'.</td>
                                    <td><a href="'.base_url('pages/report_value/'.$nmtb.'/'.$k).'">';
                                    
                                    echo $l['nik'].'</a></td>
                                    <td>'.$l['nama'].'</td>
                                    <td>'.$l['jabatan'].'</td>
                                    <td>'.$l['loker'].'</td>
                                    <td>'.$l['ind'].' Indikator</td>
                                    <td class="text-center">'.number_format($l['nilai'],2,',',',').'</td>
                                    </tr>';
                                    $n1++;
                        }
                        // $tab=$this->db->query("SELECT * FROM $nmtb")->result();
                        // foreach ($tab as $t) {
                        //   $res[$t->id_karyawan]=$t->id_karyawan;
                        // }
                        // foreach ($res as $k) {
                        //   $nilai=$this->db->get_where($nmtb,array('id_karyawan'=>$k))->result();
                        //   $data1=$this->db->get_where($nmtb,array('id_karyawan'=>$k))->num_rows();
                        //   $ky=$this->db->query("SELECT nama,jabatan FROM karyawan WHERE id_karyawan = '$k'")->row_array();
                        //   $jbt=$this->db->get_where('master_jabatan',array('kode_jabatan'=>$ky['jabatan']))->row_array();
                        //   foreach ($nilai as $hs) {
                        //     $jo[$k][]=$hs->id_jabatan;
                        //     $lo[$k][]=$hs->id_loker;
                        //   }
                        //   $jo2[$k]=array_unique($jo[$k]);
                        //   $lo2[$k]=array_unique($lo[$k]);
                        //   $jo1[$k]=array_values($jo2[$k]);
                        //   $lo1[$k]=array_values($lo2[$k]);
                        //   if (count($jo1[$k]) > count($lo1[$k])) {
                        //     foreach ($lo1[$k] as $lo2[$k]) {
                        //       array_push($lo1[$k], $lo2[$k]);
                        //     }
                        //   }
                        //   if(count($jo1[$k]) < count($lo1[$k])){
                        //     foreach ($jo1[$k] as $jo2[$k]) {
                        //       array_push($jo1[$k], $jo2[$k]);
                        //     }
                        //   }
                        //   $np=1;
                        //   if (count($jo1[$k]) > 1) {
                        //     foreach ($jo1[$k] as $ka=>$jaa) {
                        //       $hasilx=$this->db->get_where($nmtb,array('id_jabatan'=>$jaa,'id_loker'=>$lo1[$k][$ka],'id_karyawan'=>$k))->result();
                        //       $nx1[$np]=array();
                        //       $nx2[$np]=array();
                        //       $nx3[$np]=array();
                        //       $nx4[$np]=array();
                        //       $nx5[$np]=array();
                        //       $nx6[$np]=array();
                        //       $vl3[$np]=array();
                        //       foreach ($hasilx as $hx) {
                        //         array_push($vl3[$np], $hx->nilai_out);
                        //         array_push($nx1[$np], $hx->na1);
                        //         array_push($nx2[$np], $hx->na2);
                        //         array_push($nx3[$np], $hx->na3);
                        //         array_push($nx4[$np], $hx->na4);
                        //         array_push($nx5[$np], $hx->na5);
                        //         array_push($nx6[$np], $hx->na6);
                        //       }
                        //       $vl13[$np] = array_filter($vl3[$np]);
                        //       if (count($vl13[$np]) != 0) {
                        //         if (array_sum($nx1[$np]) != 0) {
                        //           $nnn[$np][]=array_sum($nx1[$np]);
                        //         }
                        //         if (array_sum($nx2[$np]) != 0) {
                        //           $nnn[$np][]=array_sum($nx2[$np]);
                        //         }
                        //         if (array_sum($nx3[$np]) != 0) {
                        //           $nnn[$np][]=array_sum($nx3[$np]);
                        //         }
                        //         if (array_sum($nx4[$np]) != 0) {
                        //           $nnn[$np][]=array_sum($nx4[$np]);
                        //         }
                        //         if (array_sum($nx5[$np]) != 0) {
                        //           $nnn[$np][]=array_sum($nx5[$np]);
                        //         }
                        //         if (array_sum($nx6[$np]) != 0) {
                        //           $nnn[$np][]=array_sum($nx6[$np]);
                        //         }
                        //         $avgx[$np] = array_sum($nnn[$np])/count($nnn[$np]);
                        //       }else{
                        //         $avgx[$np]=0;
                        //       }
                        //       $np++;
                        //     }
                            
                        //     foreach ($nilai as $n) {
                        //       $nout[$k][]=($n->bobot_out/100)*$n->nilai_out;
                        //       $nsk[$k][($n->bobot_skp/100)*$n->nilai_sikap]=($n->bobot_skp/100)*$n->nilai_sikap;
                        //       $ntc[$k][($n->bobot_tc/100)*$n->nilai_tc]=($n->bobot_tc/100)*$n->nilai_tc;
                        //       $bbout[$k][$n->bobot_out]=$n->bobot_out;
                        //     }
                        //     $bbout1[$k]=array_filter($bbout[$k]);
                        //     $bbt1[$k]=implode('', $bbout1[$k])/100;
                        //     $na[$k][]=array_sum($avgx)/count($avgx);
                        //   }else{
                        //     foreach ($nilai as $n) {
                        //       $nout[$k][]=($n->bobot_out/100)*$n->nilai_out;
                        //       $nsk[$k][($n->bobot_skp/100)*$n->nilai_sikap]=($n->bobot_skp/100)*$n->nilai_sikap;
                        //       $ntc[$k][($n->bobot_tc/100)*$n->nilai_tc]=($n->bobot_tc/100)*$n->nilai_tc;
                        //       $nx1[$k][]=$n->na1;
                        //       $nx2[$k][]=$n->na2;
                        //       $nx3[$k][]=$n->na3;
                        //       $nx4[$k][]=$n->na4;
                        //       $nx5[$k][]=$n->na5;
                        //       $nx6[$k][]=$n->na6;
                        //       $bbout[$k][$n->bobot_out]=$n->bobot_out;
                        //     }
                        //     $bbout1[$k]=array_filter($bbout[$k]);
                        //     $bbt1[$k]=implode('', $bbout1[$k])/100;
                        //     $vl13[$k] = array_filter($nout[$k]);
                        //     if (count($vl13[$k]) != 0) {
                        //       if (array_sum($nx1[$k]) != 0) {
                        //         $nnn[$k][]=array_sum($nx1[$k]);
                        //       }
                        //       if (array_sum($nx2[$k]) != 0) {
                        //         $nnn[$k][]=array_sum($nx2[$k]);
                        //       }
                        //       if (array_sum($nx3[$k]) != 0) {
                        //         $nnn[$k][]=array_sum($nx3[$k]);
                        //       }
                        //       if (array_sum($nx4[$k]) != 0) {
                        //         $nnn[$k][]=array_sum($nx4[$k]);
                        //       }
                        //       if (array_sum($nx5[$k]) != 0) {
                        //         $nnn[$k][]=array_sum($nx5[$k]);
                        //       }
                        //       if (array_sum($nx6[$k]) != 0) {
                        //         $nnn[$k][]=array_sum($nx6[$k]);
                        //       }
                        //       $na[$k][] = array_sum($nnn[$k])/count($nnn[$k]);
                        //     }else{
                        //       $na[$k][]=0;
                        //     }
                        //   }
                        //   /*
                        //   foreach ($nsk[$k] as $nk[$k]) {
                        //     $na[$k][]=$nk[$k];
                        //   }
                        //   foreach ($ntc[$k] as $nt[$k]) {
                        //     $na[$k][]=$nt[$k];
                        //   }

                        //   $naa[$k]=$na[$k][0]+$na[$k][1]+$na[$k][2];
                        //   */
                        //     echo '<tr>
                        //           <td width="3%">'.$n1.'.</td>
                        //           <td><a href="'.base_url('pages/report_value/'.$nmtb.'/'.$k).'">';
                        //           $con=$this->db->get_where('master_konversi_nilai',array('status'=>'aktif'))->result();
                        //           foreach ($con as $c) {
                        //             if (number_format($na[$k][0],2) >= $c->awal && number_format($na[$k][0],2) <= $c->akhir) {
                        //               if ($c->warna != NULL) {
                        //                 echo '<i class="fa fa-circle" style="color:'.$c->warna.'"></i> ';
                        //               }
                        //             }
                        //           }
                        //           echo $ky['nama'].'</a></td>
                        //           <td>'.$jbt['jabatan'].'</td>
                        //           <td>'.$data1.' Indikator</td>
                        //           <td class="text-center">'.number_format($na[$k][0],2,',',',').'</td>
                        //           </tr>';
                          
                        //   $n1++;
                        // }
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