  <div class="content-wrapper">
    <div class="alert alert-info">
      <i class="fa fa-calendar faa-shake animated text-blue" style="font-size: 14pt;"></i>
      <?php 
      if ($agd != "") {
        echo ' <b>Agenda Penilaian Sikap (360°) '.$agd['nama_agenda'].' Tahun '.$agd['tahun'].' Semester '.$agd['semester'].'</b>';
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
       <a class="back" onclick="window.history.back()"><i class="fa fa-chevron-circle-left "></i></a> <i class="fa fa-users"></i> Daftar Karyawan Penilaian Sikap (360°)<br>
       
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
        <li><a href="<?php echo base_url('pages/result_attitude_tasks');?>"><i class="fa fa-table"></i> Daftar Agenda</a></li>
        <li class="active">Daftar Karyawan</li>
      </ol>
    </section>
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-users"></i> Daftar Karyawan Penilaian Sikap (360°)</h3>
              <div class="box-tools pull-right">
                
                <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
              
              <div class="row">
                <div class="col-md-12">
                  <?php 
                  if (count($kar) == 0) {
                    echo '<div class="callout callout-danger"><label><i class="fa fa-info-circle"></i> Data Kosong</label><br>Tidak ada data yang ditampilkan</div>';
                  }else{
                    if ($agd['semester'] == 0) {
                      $per="Januari - Desember Tahun ".$agd['tahun'];
                    }elseif ($agd['semester'] == 1) {
                      $per="Januari - Juni Tahun ".$agd['tahun'];
                    }else{
                      $per="Juli - Desember Tahun ".$agd['tahun'];
                    }
                    if (isset($sbawah)) {
                      $sbawah=$sbawah;
                    }else{
                      $sbawah=NULL;
                    }
                    if (isset($satas)) {
                      $satas=$satas;
                    }else{
                      $satas=NULL;
                    }
                    if (isset($srekan)) {
                      $srekan=$srekan;
                    }else{
                      $srekan=NULL;
                    }
                    $datax=array('data'=>$data,'kalibrasi'=>$kalibrasi,'periode'=>$agd['nama_agenda'].' '.$per);
                    $datakar=array('kar'=>$kar,'nik'=>$nik,'nama'=>$nm,'jabatan'=>$jbt,'lok'=>$lok,'bawah'=>$sbawah,'rekan'=>$srekan,'atas'=>$satas,'partisipan'=>$pr1);
                    $datax1=array('data'=>$datakar,'periode'=>$agd['nama_agenda'].' '.$per);
                  ?>
                  <div class="row">
                    <div class="col-md-12">
                      <?php if (in_array($access['l_ac']['rkp'], $access['access'])) {?>
                      <div class="pull-left">
                        <?php echo form_open('rekap/rekap_nilai_sikap');?>
                        <input type="hidden" name="data" value="<?php echo base64_encode(serialize($datax));?>">
                        <input type="hidden" name="th" value="<?php echo $agd['tahun']; ?>">
                        <input type="hidden" name="smt" value="<?php echo $agd['semester']; ?>">
                        <button type="submit" class="btn btn-flat btn-warning"><i class="fa fa-cloud-download"></i> Rekap Nilai</button>
                        <?php echo form_close();?>
                      </div><?php
                      }if(in_array($access['l_ac']['prn'], $access['access'])){?>
                      <div class="pull-left">
                        <?php echo form_open('pages/print_page');?>
                        <input type="hidden" name="page" value="<?php echo "result_attd";?>">
                        <input type="hidden" name="data" value="<?php echo base64_encode(serialize($datax));?>">
                        <button type="submit" class="btn btn-flat btn-info"><i class="fa fa-print"></i> Print</button>
                        <?php echo form_close();?>
                      </div><?php }?>
                      <div class="pull-right">
                        <a href="#kalibrasi" data-toggle="modal" class="btn btn-flat btn-primary"><i class="fa fa-balance-scale"></i> Kalibrasi Nilai</a>
                      </div>
                      <?php if (in_array($access['l_ac']['rkp'], $access['access'])) {?>
                      <div class="pull-right">
                        <?php echo form_open('rekap/rekap_partisipan_sikap');?>
                        <input type="hidden" name="data" value="<?php echo base64_encode(serialize($datax1));?>">
                        <input type="hidden" name="th" value="<?php echo $agd['tahun']; ?>">
                        <input type="hidden" name="smt" value="<?php echo $agd['semester']; ?>">
                        <button type="submit" class="btn btn-flat btn-danger"><i class="fa fa-users"></i> Rekap Partisipan</button>
                        <?php echo form_close();?>
                      </div><?php } ?>
                    </div>
                  </div>
                  <div id="kalibrasi" class="modal fade" role="dialog">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal">&times;</button>
                          <h4 class="modal-title text-center">Kalibrasi Nilai Sikap</h4>
                        </div>
                        <div class="modal-body">
                          <?php
                            echo form_open('agenda/kalibrasi_sikap');
                            echo '<input type="hidden" name="data" value="'.base64_encode(serialize($datax)).'">
                            <input type="hidden" name="tabel" value="'.$agd['tabel_agenda'].'">
                            <input type="hidden" name="kode" value="'.$agd['kode_agenda'].'">
                            <label>Pilih Karyawan yang akan dikalibrasi</label>';
                            foreach ($kar as $kkx) {
                              $nmkar=$this->db->query("SELECT nama FROM karyawan WHERE id_karyawan = '$kkx'")->row_array();
                              $empl[$kkx]=$nmkar['nama'];
                            }
                            $sel = array(NULL);
                            $ex = array('class'=>'form-control select2','multiple'=>'multiple','data-placeholder'=>'Pilih Karyawan','style'=>'width:100%','required'=>'required');
                            echo form_dropdown('karyawan[]',$empl,$sel,$ex).'<br><br><label>Pilih Opsi</label>';
                            $op=array('+'=>'Tambah','-'=>'Kurangi');
                            $sel1 = array(NULL);
                            $ex1 = array('class'=>'form-control select2','style'=>'width:100%','required'=>'required');
                            echo form_dropdown('operator',$op,$sel1,$ex1);
                            echo '<br><br><label>Nilai Kalibrasi</label><input type="number" step="0.01" required="required" max="100" placeholder="Masukkan Nilai Kalibrasi" class="form-control" name="nilai"/>';
                          ?>
                        </div>
                        <div class="modal-footer">
                          <button type="submit" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
                          <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
                          <?php echo form_close();?>
                        </div>
                      </div>

                    </div>
                  </div>
                  <br>
                  <div class="callout callout-info"><label><i class="fa fa-info-circle"></i> Bantuan</label><br>
                    <ul>
                      <li>Pilih Nama Karyawan untuk melihat hasil Rapor Penilaian Kinerja Sikap (360°)</li>
                      <li>Pilih Partisipan <b>(Jumlah Partisipan, Belum Selesai, Selesai)</b> untuk melihat detail keterangan Partisipan</li>
                    </ul>
                  </div>
                  <table id="example1" class="table table-bordered table-striped table-hover">
                    <thead>
                      <tr>
                        <th>No.</th>
                        <th>Nama Karyawan</th>
                        <th>Jabatan</th>
                        <th>Lokasi Kerja</th>
                        <th>Jumlah Partisipan</th>
                        <th><i class="fa fa-spin fa-refresh"></i> Belum Selesai</th>
                        <th><i class="fa fa-check-circle"></i> Selesai</th>
                        <th class="text-center">Nilai Asli</th>
                        <th class="text-center">Nilai Kalibrasi</th>
                        <th class="text-center">Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                           $n1=1; 
                          foreach ($kar as $k) {
                            $na[$k]=$nats[$k]+$nbwh[$k]+$nrkn[$k];
                            $pr[$k]=implode('', $pr1[$k]);
                            $jmp1[$k]=array_filter(explode(';', $pr[$k]));
                            $jmp[$k]=count(array_filter(explode(';', $pr[$k])));
                            
                            if (isset($sbawah[$k])) {
                              $bbw[$k]=count($sbawah[$k]);
                            }else{
                              $bbw[$k]=0;
                            }
                            if (isset($satas[$k])) {
                              $bat[$k]=count($satas[$k]);
                            }else{
                              $bat[$k]=0;
                            }
                            if (isset($srekan[$k])) {
                              $brk[$k]=count($srekan[$k]);
                            }else{
                              $brk[$k]=0;
                            }
                            if (isset($sdiri[$k])) {
                              $bdr[$k]=count($sdiri[$k]);
                            }else{
                              $bdr[$k]=0;
                            }
                            $sudah[$k]=$bbw[$k]+$bat[$k]+$brk[$k]+$bdr[$k];
                            $belum[$k]=$jmp[$k]-$sudah[$k];
                              echo '<tr>
                                    <td width="3%">'.$n1.'.</td>
                                    <td><a href="'.base_url('pages/report_attitude_value/'.$kode.'/'.$k).'">';
                                    if ($belum[$k] == $jmp[$k]) {
                                      echo '<i class="fa fa-times-circle text-red" data-toggle="tooltip" title="Belum Dinilai"></i> ';
                                    }elseif ($sudah[$k] < $jmp[$k] && $sudah[$k] != 0) {
                                      echo '<i class="fa fa-refresh fa-spin text-yellow"  data-toggle="tooltip" title="Belum Selesai"></i> ';
                                    }else{
                                      echo '<i class="fa fa-check-circle text-green"  data-toggle="tooltip" title="Selesai"></i> ';
                                    }
                                    echo $nm[$k].'</a></td>
                                    <td>'.$jbt[$k].'</td>
                                    <td>'.$lok[$k].'</td>
                                    <td class="text-center"><a href="#dtall'.$n1.'" data-toggle="modal">'.$jmp[$k].' Partisipan</a></td>
                                    <td class="text-center">';
                                    if ($belum[$k] != 0) {
                                      echo '<a href="#dtb'.$n1.'" data-toggle="modal">'.$belum[$k].' Partisipan</a>';
                                    }else{
                                      echo '<label class="label label-success">Sudah Selesai Semua</label>';
                                    }
                                    echo '</td>
                                    <td class="text-center">';
                                    if ($sudah[$k] != 0) {
                                      echo '<a href="#dts'.$n1.'" data-toggle="modal">'.$sudah[$k].' Partisipan</a>';
                                    }else{
                                      echo '<label class="label label-danger">Belum Selesai Semua</label>';
                                    }
                                    echo '</td>';
                                    $selisih[$k]=$kalibrasi[$k]-$na[$k];
                                    if ($kalibrasi[$k] != 0) {
                                      if (number_format($selisih[$k],2,',',',') > 0) {
                                        $partial[$k]=' <small style="color:#00a526"><b>(+'.number_format($selisih[$k],2,',',',').')</b></small>';
                                      }elseif(number_format($selisih[$k],2,',',',') < 0){
                                        $partial[$k]=' <small class="text-danger"><b>('.number_format($selisih[$k],2,',',',').')</b></small>';
                                      }else{
                                        $partial[$k]='';
                                      }
                                    }else{
                                      $partial[$k]='';
                                    }
                                    echo '<td class="text-center">'.number_format($na[$k],2,',',',').$partial[$k].'</td>
                                    <td class="text-center">';
                                    if ($kalibrasi[$k] == 0) {
                                      $kalibrasi[$k] = NULL;
                                      echo number_format($na[$k],2,',',',');
                                      
                                    }else{
                                      echo number_format($kalibrasi[$k],2,',',',');
                                    }
                                    echo '</td>
                                    <td class="text-center"><a href="#kal_edt'.$n1.'" data-toggle="modal" class="btn btn-primary"><i class="fa fa-balance-scale" data-toggle="tooltips" title="Kalibrasi Nilai"></i></a></td>
                                    </tr>
                                    <div id="kal_edt'.$n1.'" class="modal fade" role="dialog">
                                      <div class="modal-dialog">
                                        <div class="modal-content">
                                          <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title text-center">Kalibrasi Nilai '.$nm[$k].'</h4>
                                          </div>
                                          <div class="modal-body">
                                            '.form_open('agenda/kalibrasi_one_sikap');
                                            if ($kalibrasi[$k] == 0) {
                                              $kkl[$k]=NULL;
                                            }else{
                                              $kkl[$k]=number_format($kalibrasi[$k],2);
                                            }
                                            echo '
                                            <input type="hidden" name="tabel" value="'.$agd['tabel_agenda'].'">
                                            <input type="hidden" name="kode" value="'.$agd['kode_agenda'].'">
                                            <input type="hidden" name="id_karyawan" value="'.$k.'">
                                            <label>Nilai Kalibrasi</label><input type="number" step="0.01" required="required" max="100" placeholder="Masukkan Nilai Kalibrasi" class="form-control" name="nilai" value="'.$kkl[$k].'"/>
                                          </div>
                                          <div class="modal-footer">
                                            <button type="submit" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
                                            '.form_close().'
                                          </div>
                                        </div>

                                      </div>
                                    </div>
                                    <div id="dtall'.$n1.'" class="modal fade" role="dialog">
                                      <div class="modal-dialog">
                                        <div class="modal-content">
                                          <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title text-center">Partisipan '.$nm[$k].'</h4>
                                          </div>
                                          <div class="modal-body">
                                            <ul>';
                                            foreach ($jmp1[$k] as $pall) {
                                              $pa1[$k]=explode(':', $pall);
                                              $idd[$k]=$pa1[$k][1];
                                              $kkall[$k]=$this->db->query("SELECT nama FROM karyawan WHERE id_karyawan = '$idd[$k]'")->row_array();
                                              if ($pa1[$k][0] == "ATS") {
                                                echo '<li>'.$kkall[$k]['nama'].' <label class="label label-success"><i class="fa fa-star"></i> Atasan</label></li>';
                                              }
                                              if ($pa1[$k][0] == "RKN") {
                                                echo '<li>'.$kkall[$k]['nama'].' <label class="label label-primary">Rekan</label></li>';
                                              }
                                              if ($pa1[$k][0] == "BWH") {
                                                echo '<li>'.$kkall[$k]['nama'].' <label class="label label-danger">Bawahan</label></li>';
                                              }
                                              if ($pa1[$k][0] == "DRI") {
                                                echo '<li>'.$kkall[$k]['nama'].' <label class="label label-info"><i class="fa fa-user"></i> Diri Sendiri</label></li>';
                                              }
                                            }
                                            echo '</ul>
                                          </div>
                                          <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
                                          </div>
                                        </div>

                                      </div>
                                    </div>
                                    <div id="dtb'.$n1.'" class="modal fade" role="dialog">
                                      <div class="modal-dialog">
                                        <div class="modal-content modal-danger">
                                          <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title text-center">Partisipan Belum Selesai Untuk '.$nm[$k].'</h4>
                                          </div>
                                          <div class="modal-body">
                                            <ul>';
                                            $xx=1;
                                            foreach ($jmp1[$k] as $ajm) {
                                              $pp[$k]=explode(':', $ajm);
                                              $ara[$k][$xx]=$pp[$k][1];
                                              $ara1[$k][$xx]=$pp[$k][0];
                                              $xx++;
                                            }
                                            if (isset($sbawah[$k])) {
                                              foreach ($sbawah[$k] as $bwa) {
                                                if (($key[$k] = array_search($bwa, $ara[$k])) !== false) {
                                                    unset($ara[$k][$key[$k]]);
                                                }
                                              }

                                            }

                                            if (isset($satas[$k])) {
                                              foreach ($satas[$k] as $ata) {
                                                if (($key1[$k] = array_search($ata, $ara[$k])) !== false) {
                                                    unset($ara[$k][$key1[$k]]);
                                                }
                                              }
                                            }
                                            if (isset($srekan[$k])) {
                                              foreach ($srekan[$k] as $rka) {
                                                if (($key2[$k] = array_search($rka, $ara[$k])) !== false) {
                                                    unset($ara[$k][$key2[$k]]);
                                                }
                                              }
                                            }
                                            if (isset($sdiri[$k])) {
                                                if (($key3[$k] = array_search($sdiri[$k], $ara[$k])) !== false) {
                                                    unset($ara[$k][$key3[$k]]);
                                                }
                                            }
                                            foreach ($ara[$k] as $kkw => $arr) {
                                              $kkall1[$k]=$this->db->query("SELECT nama FROM karyawan WHERE id_karyawan = '$arr'")->row_array();
                                             if ($ara1[$k][$kkw] == "ATS") {
                                               echo '<li>'.$kkall1[$k]['nama'].' <label class="label label-success"><i class="fa fa-star"></i> Atasan</label></li>';
                                             }
                                             if ($ara1[$k][$kkw] == "BWH") {
                                               echo '<li>'.$kkall1[$k]['nama'].' <label class="label label-danger">Bawahan</label></li>';
                                             }
                                             if ($ara1[$k][$kkw] == "RKN") {
                                               echo '<li>'.$kkall1[$k]['nama'].' <label class="label label-primary">Rekan</label></li>';
                                             }
                                             if ($ara1[$k][$kkw] == "DRI") {
                                               echo '<li>'.$kkall1[$k]['nama'].' <label class="label label-info"><i class="fa fa-user"></i> Diri Sendiri</label></li>';
                                             }
                                            }
                                            echo '</ul>
                                          </div>
                                          <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
                                          </div>
                                        </div>

                                      </div>
                                    </div>
                                    <div id="dts'.$n1.'" class="modal fade" role="dialog">
                                      <div class="modal-dialog">
                                        <div class="modal-content modal-success">
                                          <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title text-center">Partisipan Sudah Selesai Untuk '.$nm[$k].'</h4>
                                          </div>
                                          <div class="modal-body">
                                            <ul>';
                                            if (isset($sbawah[$k])) {
                                              foreach ($sbawah[$k] as $bwa) {
                                                $kk=$this->db->query("SELECT nama FROM karyawan WHERE id_karyawan = '$bwa'")->row_array();
                                                echo '<li>'.$kk['nama'].' <label class="label label-danger">Bawahan</label></li>';
                                              }
                                            }
                                            if (isset($satas[$k])) {
                                              foreach ($satas[$k] as $ata) {
                                                $kk1=$this->db->query("SELECT nama FROM karyawan WHERE id_karyawan = '$ata'")->row_array();
                                                echo '<li>'.$kk1['nama'].' <label class="label label-success"><i class="fa fa-star"></i> Atasan</label></li>';
                                              }
                                            }
                                            if (isset($srekan[$k])) {
                                              foreach ($srekan[$k] as $rka) {
                                                $kk2=$this->db->query("SELECT nama FROM karyawan WHERE id_karyawan = '$rka'")->row_array();
                                                echo '<li>'.$kk2['nama'].' <label class="label label-primary">Rekan</label></li>';
                                              }
                                            }
                                            if (isset($sdiri[$k])) {
                                                $kk2=$this->db->query("SELECT nama FROM karyawan WHERE id_karyawan = '$sdiri[$k]'")->row_array();
                                                echo '<li>'.$kk2['nama'].' <label class="label label-info"><i class="fa fa-user"></i> Diri Sendiri</label></li>';
                                            }
                                            echo '</ul>
                                          </div>
                                          <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
                                          </div>
                                        </div>

                                      </div>
                                    </div>';
                            
                            
                            $n1++;
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