  <div class="content-wrapper">
    <div class="alert alert-info" id="alert-danger">
      <i class="fa fa-calendar faa-shake animated text-blue" style="font-size: 14pt;"></i>
      <?php 
      if ($agd != "") {
        echo ' <b>Agenda Penilaian Output (Target) '.$agd['nama_agenda'].' Tahun '.$agd['tahun'].' Semester '.$agd['semester'].'</b>';
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
       <a class="back" onclick="window.history.back()"><i class="fa fa-chevron-circle-left "></i></a> <i class="fa fa-users"></i> Daftar Karyawan Penilaian Output (Target)
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
        <li><a href="<?php echo base_url('pages/tasks');?>"><i class="fa fa-table"></i> Daftar Agenda Penilaian Output (Target)</a></li>
        <li class="active">Daftar Karyawan Penilaian Output (Target)</li>
      </ol>
    </section>
    <section class="content">
      <div class="row"> 
        <div class="col-md-12">
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-users"></i> Daftar Karyawan Penilaian Output (Target)</h3>
              <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
              
              <div class="row">
                <div class="col-md-12">
                  <div class="row">
                    <div class="col-md-12">
                      <?php 
                        if (in_array($access['l_ac']['imp'],$access['access'])) {
                          echo '<button class="btn btn-primary btn-flat" type="button" data-toggle="modal" data-target="#import" aria-expanded="false" aria-controls="import"><i class="fa fa-cloud-upload"></i> Import</button> ';
                        }
                        if (in_array($access['l_ac']['exp'], $access['access'])) {
                          echo '<button class="btn btn-success btn-flat" type="button" data-toggle="modal" data-target="#export" aria-expanded="false" aria-controls="export"><i class="fa fa-cloud-download"></i> Export</button> ';
                        }
                        if (in_array($access['l_ac']['imp'], $access['access'])) {
                      ?>
                      
                      
                      <div class="modal fade" id="import" role="dialog">
                        <div class="modal-dialog">
                          <div class="modal-content text-center">
                            <div class="modal-header">
                              <button type="button" class="close" data-dismiss="modal">&times;</button>
                              <h4 class="modal-title">Import Data Dari Excel</h4>
                            </div>
                            <div class="modal-body">
                              <?php echo form_open_multipart('agenda/import_value');?>
                              <p style="color:red;">File harus tipe *.xls, *.xlsx, *.csv, *.ods dan *.ots</p>
                              <input id="uploadFile" placeholder="Nama File" disabled="disabled" class="form-control" required="required">
                              <span class="input-group-btn">
                                <div class="fileUpload btn btn-warning btn-flat">
                                  <span><i class="fa fa-folder-open"></i> Pilih File</span>
                                  <input id="uploadBtn" type="file" class="upload" name="file"/>
                                </div>
                              </span>
                            </div>
                            <div class="modal-footer">
                              <input type="hidden" name="kode" value="<?php echo $kode;?>">
                              <input type="hidden" name="tabel_agd" value="<?php echo $nmtb;?>">
                              <button class="btn btn-flat btn-primary" id="save" type="submit" disabled><i class="fa fa-check-circle"></i> Upload</button>
                              <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
                              <?php echo form_close();?>
                            </div>
                          </div>
                        </div>
                      </div><?php } if (in_array($access['l_ac']['exp'], $access['access'])) {?>
                      <div class="modal fade" id="export" role="dialog">
                        <div class="modal-dialog">
                          <div class="modal-content text-center">
                            <div class="modal-header">
                              <button type="button" class="close" data-dismiss="modal">&times;</button>
                              <h4 class="modal-title">Export Data Ke Excel</h4>
                            </div>
                            <div class="modal-body">
                              <p>Pilih Indikator</p>
                              <p class="text-red">Kosongkan Jika Semua Indikator</p>
                              <?php echo form_open('agenda/export_value');
                              $tbagd=$nmtb;
                              $ts=$this->db->query("SELECT kode_indikator FROM $tbagd WHERE kaitan = '0'")->result();
                              foreach ($ts as $t) {
                                $ind[$t->kode_indikator]=$t->kode_indikator;
                              }
                              foreach ($ind as $a) {
                                $indi=$this->db->get_where('master_indikator',array('kode_indikator'=>$a))->row_array();
                                $s=$indi['kpi'];
                                $max_length = 50;
                                if (strlen($s) > $max_length)
                                {
                                  $offset = ($max_length - 3) - strlen($s);
                                  $s = substr($s, 0, strrpos($s, ' ', $offset)) . '..';
                                  $word=$s;
                                }else{
                                  $word=$indi['kpi'];
                                }
                                $op12[$a]=$word;
                              }
                              $sel1 = array(NULL);
                              $ex1 = array('class'=>'form-control select2','multiple'=>'multiple','data-placeholder'=>'Pilih Indikator','style'=>'width:100%;');
                              echo form_dropdown('indi[]',$op12,$sel1,$ex1);
                              ?>
                            </div>
                            <div class="modal-footer">
                              <input type="hidden" name="kode" value="<?php echo $kode;?>">
                              <input type="hidden" name="tabel_agd" value="<?php echo $nmtb;?>">
                              <button class="btn btn-success btn-flat" type="submit"><i class="fa fa-check-circle"></i> Download</button>
                              <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
                              <?php echo form_close();?>
                            </div> 
                          </div>
                        </div>
                      </div><?php } ?>
                    </div>
                  </div>
                  <br>
                  <?php 
                  if (count($tabel) == 0) {
                    echo '<div class="callout callout-danger"><label><i class="fa fa-info-circle"></i> Data Kosong</label><br>Tidak ada data yang ditampilkan</div>';
                  }else{
                  ?>
                  <div class="callout callout-info"><label><i class="fa fa-info-circle"></i> Bantuan</label><br>Pilih Nama Karyawan untuk melakukan Input Penilaian Kinerja</div>
                  <table id="example1" class="table table-bordered table-striped table-hover">
                    <thead>
                      <tr>
                        <th>No.</th>
                        <th>NIK</th>
                        <th>Nama Karyawan</th>
                        <th>Jabatan</th>
                        <th>Lokasi Kerja</th>
                        <th>Jumlah Indikator</th>
                        <th class="text-center">Nilai Output (Target)</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                        $n1=1;
                        foreach ($tabel as $k => $l) {
                          echo '<tr>
                                    <td width="3%">'.$n1.'.</td>
                                    <td><a href="'.base_url('pages/input_value/'.$kode.'/'.$k).'">';
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
                        // if (count($tab) != 0) {
                        //   foreach ($res as $k) {
                        //     $nilai=$this->db->get_where($nmtb,array('id_karyawan'=>$k))->result();
                        //     $data1=$this->db->get_where($nmtb,array('id_karyawan'=>$k))->num_rows();
                        //     $ky=$this->db->query("SELECT nama,jabatan FROM karyawan WHERE id_karyawan = '$k'")->row_array();
                        //     $jbt=$this->db->get_where('master_jabatan',array('kode_jabatan'=>$ky['jabatan']))->row_array();
                        //     foreach ($nilai as $hs) {
                        //       $jo[$k][]=$hs->id_jabatan;
                        //       $lo[$k][]=$hs->id_loker;
                        //     }
                        //     $jo2[$k]=array_unique($jo[$k]);
                        //     $lo2[$k]=array_unique($lo[$k]);
                        //     $jo1[$k]=array_values($jo2[$k]);
                        //     $lo1[$k]=array_values($lo2[$k]);
                        //     if (count($jo1[$k]) > count($lo1[$k])) {
                        //       foreach ($lo1[$k] as $lo2[$k]) {
                        //         array_push($lo1[$k], $lo2[$k]);
                        //       }
                        //     }
                        //     if(count($jo1[$k]) < count($lo1[$k])){
                        //       foreach ($jo1[$k] as $jo2[$k]) {
                        //         array_push($jo1[$k], $jo2[$k]);
                        //       }
                        //     }
                        //     $np=1;
                        //     if (count($jo1[$k]) > 1) {
                        //       foreach ($jo1[$k] as $ka=>$jaa) {
                        //         $hasilx=$this->db->get_where($nmtb,array('id_jabatan'=>$jaa,'id_loker'=>$lo1[$k][$ka],'id_karyawan'=>$k))->result();
                        //         $nx1[$np]=array();
                        //         $nx2[$np]=array();
                        //         $nx3[$np]=array();
                        //         $nx4[$np]=array();
                        //         $nx5[$np]=array();
                        //         $nx6[$np]=array();
                        //         $vl3[$np]=array();
                        //         foreach ($hasilx as $hx) {
                        //           array_push($vl3[$np], $hx->nilai_out);
                        //           array_push($nx1[$np], $hx->na1);
                        //           array_push($nx2[$np], $hx->na2);
                        //           array_push($nx3[$np], $hx->na3);
                        //           array_push($nx4[$np], $hx->na4);
                        //           array_push($nx5[$np], $hx->na5);
                        //           array_push($nx6[$np], $hx->na6);
                        //         }
                        //         $vl13[$np] = array_filter($vl3[$np]);
                        //         if (count($vl13[$np]) != 0) {
                        //           if (array_sum($nx1[$np]) != 0) {
                        //             $nnn[$np][]=array_sum($nx1[$np]);
                        //           }
                        //           if (array_sum($nx2[$np]) != 0) {
                        //             $nnn[$np][]=array_sum($nx2[$np]);
                        //           }
                        //           if (array_sum($nx3[$np]) != 0) {
                        //             $nnn[$np][]=array_sum($nx3[$np]);
                        //           }
                        //           if (array_sum($nx4[$np]) != 0) {
                        //             $nnn[$np][]=array_sum($nx4[$np]);
                        //           }
                        //           if (array_sum($nx5[$np]) != 0) {
                        //             $nnn[$np][]=array_sum($nx5[$np]);
                        //           }
                        //           if (array_sum($nx6[$np]) != 0) {
                        //             $nnn[$np][]=array_sum($nx6[$np]);
                        //           }
                        //           $avgx[$np] = array_sum($nnn[$np])/count($nnn[$np]);
                        //         }else{
                        //           $avgx[$np]=0;
                        //         }
                        //         $np++;
                        //       }
                              
                        //       foreach ($nilai as $n) {
                        //         $nout[$k][]=($n->bobot_out/100)*$n->nilai_out;
                        //         $ntc[$k][($n->bobot_tc/100)*$n->nilai_tc]=($n->bobot_tc/100)*$n->nilai_tc;
                        //         $bbout[$k][$n->bobot_out]=$n->bobot_out;
                        //       }
                        //       $bbout1[$k]=array_filter($bbout[$k]);
                        //       $bbt1[$k]=implode('', $bbout1[$k])/100;
                        //       $na[$k][]=array_sum($avgx)/count($avgx);
                        //     }else{
                        //       foreach ($nilai as $n) {
                        //         $nout[$k][]=($n->bobot_out/100)*$n->nilai_out;
                        //         $ntc[$k][($n->bobot_tc/100)*$n->nilai_tc]=($n->bobot_tc/100)*$n->nilai_tc;
                        //         $nx1[$k][]=$n->na1;
                        //         $nx2[$k][]=$n->na2;
                        //         $nx3[$k][]=$n->na3;
                        //         $nx4[$k][]=$n->na4;
                        //         $nx5[$k][]=$n->na5;
                        //         $nx6[$k][]=$n->na6;
                        //         $bbout[$k][$n->bobot_out]=$n->bobot_out;
                        //       }
                        //       $bbout1[$k]=array_filter($bbout[$k]);
                        //       print($bbout1);
                        //       $bbt1[$k]=implode('', $bbout1[$k])/100;
                        //       $vl13[$k] = array_filter($nout[$k]);
                        //       if (count($vl13[$k]) != 0) {
                        //         if (array_sum($nx1[$k]) != 0) {
                        //           $nnn[$k][]=array_sum($nx1[$k]);
                        //         }
                        //         if (array_sum($nx2[$k]) != 0) {
                        //           $nnn[$k][]=array_sum($nx2[$k]);
                        //         }
                        //         if (array_sum($nx3[$k]) != 0) {
                        //           $nnn[$k][]=array_sum($nx3[$k]);
                        //         }
                        //         if (array_sum($nx4[$k]) != 0) {
                        //           $nnn[$k][]=array_sum($nx4[$k]);
                        //         }
                        //         if (array_sum($nx5[$k]) != 0) {
                        //           $nnn[$k][]=array_sum($nx5[$k]);
                        //         }
                        //         if (array_sum($nx6[$k]) != 0) {
                        //           $nnn[$k][]=array_sum($nx6[$k]);
                        //         }
                        //         $na[$k][] = array_sum($nnn[$k])/count($nnn[$k]);
                        //       }else{
                        //         $na[$k][]=0;
                        //       }
                        //     }
                        //       echo '<tr>
                        //             <td width="3%">'.$n1.'.</td>
                        //             <td><a href="'.base_url('pages/input_value/'.$kode.'/'.$k).'">';
                        //             $con=$this->db->get_where('master_konversi_nilai',array('status'=>'aktif'))->result();
                        //             foreach ($con as $c) {
                        //               if (number_format($na[$k][0],2) >= $c->awal && number_format($na[$k][0],2) <= $c->akhir) {
                        //                 if ($c->warna != NULL) {
                        //                   echo '<i class="fa fa-circle" style="color:'.$c->warna.'"></i> ';
                        //                 }
                        //               }
                        //             }
                        //             echo $ky['nama'].'</a></td>
                        //             <td>'.$jbt['jabatan'].'</td>
                        //             <td>'.$data1.' Indikator</td>
                        //             <td class="text-center">'.number_format($na[$k][0],2,',',',').'</td>
                        //             </tr>';
                            
                        //     $n1++;
                        //   }
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