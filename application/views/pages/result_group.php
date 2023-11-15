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
       <i class="fa fa-tasks"></i> Daftar Penilaian 
        <small>Hasil Nilai</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
        <li class="active">Daftar Rapor Penilaian</li>
      </ol>
    </section>
    <section class="content">
      <div class="row">
        <div class="col-md-12"> 
          <div class="box box-danger">
            <div class="box-header with-border"> 
              <h3 class="box-title"><i class="fa fa-calendar"></i> Daftar Rapor</h3>
              <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
              <div class="row">
                <div class="col-md-12">
                  <?php 
                  if (count($agd) == 0) {
                    echo '<div class="callout callout-danger"><label><i class="fa fa-info-circle"></i> Data Kosong</label><br>Tidak ada data yang ditampilkan, Jika Anda ingin melihat riwayat penilaian maka dapat Anda lihat pada menu <a href="'.base_url('pages/log_agenda').'">Agenda Penilaian > Log Agenda</a></div>';
                  }else{
                    if (count($att) == 0 && isset($_SESSION['nilai'])) {
                       echo '<div class="callout callout-danger"><label><i class="fa fa-info-circle"></i> Data Kosong</label><br>Tidak ada data yang ditampilkan</div>';
                    }
                  ?>
                  <div class="col-md-12">
                    <div class="col-md-4"></div>
                    <div class="col-md-4">
                    <?php echo form_open('pages/result_group');
                      echo '<div class="row">
                            <div class="col-md-6">
                            <div class="form-group">
                            <label>Pilih Tahun</label>';
                            foreach ($agd as $gd) {
                              $y[$gd]=$gd;
                            }
                            if (isset($thn)) {
                              $sel = array($thn);
                            }else{
                              $sel = array(date("Y"));
                            }
                            $ex = array('class'=>'form-control select2','style'=>'width:100%;','placeholder'=>'Tahun','required'=>'required');
                            echo form_dropdown('tahun',$y,$sel,$ex);
                          echo '</div></div>
                          <div class="col-md-6">
                          <div class="form-group">
                            <label>Pilih Semester</label>';
                            $op1=array('1'=>'Semester 1','2'=>'Semester 2','0'=>'Tahunan');
                            if (isset($smtr)) {
                                $sel1 = array($smtr);
                            }else{
                              $sel1 = array('1');
                            }  
                            $ex1 = array('class'=>'form-control select2','style'=>'width:100%;','placeholder'=>'Pelaporan','required'=>'required');
                            echo form_dropdown('semester',$op1,$sel1,$ex1);
                          echo '</div></div></div>
                          <div class="row">
                            <div class="col-md-6">
                          <div class="form-group">
                            <label>Pilih Bagian</label>';
                            $op2=array('ALL'=>'Semua Bagian');
                            $bagx=$this->db->query("SELECT nama_kategori,kode_kategori FROM master_kategori_jabatan WHERE status = 'aktif' AND kode_kategori != 'KAT0'")->result();
                            foreach ($bagx as $bax) {
                              $op2[$bax->kode_kategori]=$bax->nama_kategori;
                            }
                            if (isset($bagian)) {
                                $sel2 = array($bagian);
                            }else{
                              $sel2 = array('ALL');
                            }  
                            $ex2 = array('class'=>'form-control select2','style'=>'width:100%;','placeholder'=>'Pelaporan','required'=>'required');
                            echo form_dropdown('bagian',$op2,$sel2,$ex2);
                          echo '</div></div>
                          <div class="col-md-6">
                          <div class="form-group">
                            <label>Pilih Lokasi Kerja</label>';
                            $op3=array('ALL'=>'Semua Lokasi');
                            $loker=$this->db->query("SELECT nama,kode_loker FROM master_loker WHERE status = 'aktif'")->result();
                            foreach ($loker as $lokx) {
                              $op3[$lokx->kode_loker]=$lokx->nama;
                            }
                            if (isset($lokerx)) {
                                $sel3 = array($lokerx);
                            }else{
                              $sel3 = array('ALL');
                            }  
                            $ex3 = array('class'=>'form-control select2','style'=>'width:100%;','placeholder'=>'Pelaporan','required'=>'required');
                            echo form_dropdown('loker',$op3,$sel3,$ex3);
                          echo '</div></div></div>
                          <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-flat" id="save1"><i class="fa fa-eye"></i> Lihat</button>
                          </div>
                          
                        ';
                    echo form_close();?>
                  </div>
                  </div>
                  <?php 
                  if (isset($att) && count($att) > 0 && isset($_SESSION['nilai'])) {                  
                    if ($smtr == 0) {
                      $per="Januari - Desember Tahun ".$thn;
                    }elseif ($smtr == 1) {
                      $per="Januari - Juni Tahun ".$thn;
                    }else{
                      $per="Juli - Desember Tahun ".$thn;
                    }
                    $data=array('data'=>$att,'bobot'=>$bobot,'periode'=>$per);
                    
                  ?>
                  <div class="row">
                    <div class="col-md-12">
                      <?php if(in_array($access['l_ac']['rkp'], $access['access'])){?>
                      <div class="pull-left">
                        <?php echo form_open('rekap/rekap_nilai_akhir');?>
                        <input type="hidden" name="data" value="<?php echo base64_encode(serialize($data));?>">
                        <input type="hidden" name="th" value="<?php echo $thn; ?>">
                        <input type="hidden" name="smt" value="<?php echo $smtr; ?>">
                        <input type="hidden" name="bagian" value="<?php echo $bagian; ?>">
                        <input type="hidden" name="loker" value="<?php echo $lokerx; ?>">
                        <button type="submit" class="btn btn-flat btn-warning"><i class="fa fa-cloud-download"></i> Rekap Nilai</button>
                        <?php echo form_close();?>
                      </div><?php } if(in_array($access['l_ac']['prn'], $access['access'])){?>
                      <div class="pull-left">
                        <?php echo form_open('pages/print_page');?>
                        <input type="hidden" name="page" value="<?php echo "result_group";?>">
                        <input type="hidden" name="data" value="<?php echo base64_encode(serialize($data));?>">
                        <input type="hidden" name="bagian" value="<?php echo $bagian; ?>">
                        <input type="hidden" name="loker" value="<?php echo $lokerx; ?>">
                        <button type="submit" class="btn btn-flat btn-info"><i class="fa fa-print"></i> Print</button>
                        <?php echo form_close();?>
                      </div><?php } ?>
                    </div>
                  </div>
                  <br>
                  <div id="print">
                    <div class="callout callout-info"><label><i class="fa fa-info-circle"></i> Bantuan</label><br>Pilih Tahun dan Semester untuk melihat daftar karyawan dan nilai gabungan</div>
                  <table id="example2" class="table table-bordered table-striped table-hover">
                    <thead>
                      <tr>
                        <th>No.</th>
                        <th>Nomor Induk</th>
                        <th>Nama Karyawan</th>
                        <th>Jabatan</th>
                        <th>Bagian</th>
                        <th>Lokasi Kerja</th>
                        <th>Nilai Target</th>
                        <th>Nilai Sikap</th>
                        <th>Nilai OS</th>
                        <th>Nilai Total</th>
                        <th>Huruf</th>
                        <!-- 
                        <th>Progress</th>
                        <th>Tanggal Mulai</th>
                        <th>Tanggal Selesai</th>
                        <th>Keterangan</th> --> 
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                        $n=1; 
                        foreach ($att as $k => $v) {
                          if ($bagian == $v['kode_bagian'] && $lokerx == $v['kode_loker']) {
                            echo '<tr>
                                  <td width="3%">'.$n.'.</td>
                                  <td><a href="'.base_url('pages/report_group/'.$k).'" target="blank">'.$v['nik'].'</a></td>
                                  <td>'.$v['nama'].'</td>
                                  <td>'.$v['jabatan'].'</td>
                                  <td>'.$v['bagian'].'</td>
                                  <td>'.$v['loker'].'</td>
                                  <td>';
                                  if (isset($v['nilai_target'])) {
                                    $nat[$k]=$v['nilai_target']*($bobot[$k]['bobot_out']/100);
                                    echo number_format($nat[$k],2,',',',');

                                  }else{
                                    echo number_format(0,2,',',',');
                                    $nat[$k]=0;
                                  }
                                  echo '</td>
                                  <td>';
                                  if (isset($v['nilai_sikap'])) {
                                    if ($v['nilai_kalibrasi'] != 0) {
                                      $n_sikap=$v['nilai_kalibrasi'];
                                    }else{
                                      $n_sikap=$v['nilai_sikap'];
                                    }
                                    $nas[$k]=$n_sikap*($bobot[$k]['bobot_skp']/100);
                                    echo number_format($nas[$k],2,',',',');
                                  }else{
                                    echo number_format(0,2,',',',');
                                    $nas[$k]=0;

                                  }
                                  echo '</td>
                                  <td>';
                                  if (isset($v['nilai_corp'])) {
                                    $nac[$k]=$v['nilai_corp']*($bobot[$k]['bobot_tc']/100);
                                    echo number_format($nac[$k],2,',',',');

                                  }else{
                                    echo number_format(0,2,',',',');
                                    $nac[$k]=0;
                                  }
                                  $nil[$k]=$nat[$k]+$nas[$k]+$nac[$k];
                                  echo '</td>
                                  <td>'.number_format(($nat[$k]+$nas[$k]+$nac[$k]),2,',',',').'</td>
                                  ';
                                  $con=$this->db->get_where('master_konversi_nilai',array('status'=>'aktif'))->result();
                                  foreach ($con as $c) {
                                    if (number_format(($nat[$k]+$nas[$k]+$nac[$k]),2) >= $c->awal && number_format(($nat[$k]+$nas[$k]+$nac[$k]),2) <= $c->akhir) {
                                      echo '<td style="background-color:'.$c->warna.';color:#fff;" class="text-center">'.$c->huruf.'</td>';
                                    }
                                  }
                                  echo '
                                </tr>';
                                $n++;
                          }elseif($bagian == 'ALL' && $lokerx == $v['kode_loker']){
                            echo '<tr>
                                  <td width="3%">'.$n.'.</td>
                                  <td><a href="'.base_url('pages/report_group/'.$k).'" target="blank">'.$v['nik'].'</a></td>
                                  <td>'.$v['nama'].'</td>
                                  <td>'.$v['jabatan'].'</td>
                                  <td>'.$v['bagian'].'</td>
                                  <td>'.$v['loker'].'</td>
                                  <td>';
                                  if (isset($v['nilai_target'])) {
                                    $nat[$k]=$v['nilai_target']*($bobot[$k]['bobot_out']/100);
                                    echo number_format($nat[$k],2,',',',');

                                  }else{
                                    echo number_format(0,2,',',',');
                                    $nat[$k]=0;
                                  }
                                  echo '</td>
                                  <td>';
                                  if (isset($v['nilai_sikap'])) {
                                    if ($v['nilai_kalibrasi'] != 0) {
                                      $n_sikap=$v['nilai_kalibrasi'];
                                    }else{
                                      $n_sikap=$v['nilai_sikap'];
                                    }
                                    $nas[$k]=$n_sikap*($bobot[$k]['bobot_skp']/100);
                                    echo number_format($nas[$k],2,',',',');
                                  }else{
                                    echo number_format(0,2,',',',');
                                    $nas[$k]=0;

                                  }
                                  echo '</td>
                                  <td>';
                                  if (isset($v['nilai_corp'])) {
                                    $nac[$k]=$v['nilai_corp']*($bobot[$k]['bobot_tc']/100);
                                    echo number_format($nac[$k],2,',',',');

                                  }else{
                                    echo number_format(0,2,',',',');
                                    $nac[$k]=0;
                                  }
                                  $nil[$k]=$nat[$k]+$nas[$k]+$nac[$k];
                                  echo '</td>
                                  <td>'.number_format(($nat[$k]+$nas[$k]+$nac[$k]),2,',',',').'</td>
                                  ';
                                  $con=$this->db->get_where('master_konversi_nilai',array('status'=>'aktif'))->result();
                                  foreach ($con as $c) {
                                    if (number_format(($nat[$k]+$nas[$k]+$nac[$k]),2) >= $c->awal && number_format(($nat[$k]+$nas[$k]+$nac[$k]),2) <= $c->akhir) {
                                      echo '<td style="background-color:'.$c->warna.';color:#fff;" class="text-center">'.$c->huruf.'</td>';
                                    }
                                  }
                                  echo '
                                </tr>';
                                $n++;
                          }elseif($lokerx == 'ALL' && $bagian == $v['kode_bagian']){
                            echo '<tr>
                                  <td width="3%">'.$n.'.</td>
                                  <td><a href="'.base_url('pages/report_group/'.$k).'" target="blank">'.$v['nik'].'</a></td>
                                  <td>'.$v['nama'].'</td>
                                  <td>'.$v['jabatan'].'</td>
                                  <td>'.$v['bagian'].'</td>
                                  <td>'.$v['loker'].'</td>
                                  <td>';
                                  if (isset($v['nilai_target'])) {
                                    $nat[$k]=$v['nilai_target']*($bobot[$k]['bobot_out']/100);
                                    echo number_format($nat[$k],2,',',',');

                                  }else{
                                    echo number_format(0,2,',',',');
                                    $nat[$k]=0;
                                  }
                                  echo '</td>
                                  <td>';
                                  if (isset($v['nilai_sikap'])) {
                                    if ($v['nilai_kalibrasi'] != 0) {
                                      $n_sikap=$v['nilai_kalibrasi'];
                                    }else{
                                      $n_sikap=$v['nilai_sikap'];
                                    }
                                    $nas[$k]=$n_sikap*($bobot[$k]['bobot_skp']/100);
                                    echo number_format($nas[$k],2,',',',');
                                  }else{
                                    echo number_format(0,2,',',',');
                                    $nas[$k]=0;

                                  }
                                  echo '</td>
                                  <td>';
                                  if (isset($v['nilai_corp'])) {
                                    $nac[$k]=$v['nilai_corp']*($bobot[$k]['bobot_tc']/100);
                                    echo number_format($nac[$k],2,',',',');

                                  }else{
                                    echo number_format(0,2,',',',');
                                    $nac[$k]=0;
                                  }
                                  $nil[$k]=$nat[$k]+$nas[$k]+$nac[$k];
                                  echo '</td>
                                  <td>'.number_format(($nat[$k]+$nas[$k]+$nac[$k]),2,',',',').'</td>
                                  ';
                                  $con=$this->db->get_where('master_konversi_nilai',array('status'=>'aktif'))->result();
                                  foreach ($con as $c) {
                                    if (number_format(($nat[$k]+$nas[$k]+$nac[$k]),2) >= $c->awal && number_format(($nat[$k]+$nas[$k]+$nac[$k]),2) <= $c->akhir) {
                                      echo '<td style="background-color:'.$c->warna.';color:#fff;" class="text-center">'.$c->huruf.'</td>';
                                    }
                                  }
                                  echo '
                                </tr>';
                                $n++;
                          }elseif($bagian == 'ALL' && $lokerx == 'ALL'){
                            echo '<tr>
                                  <td width="3%">'.$n.'.</td>
                                  <td><a href="'.base_url('pages/report_group/'.$k).'" target="blank">'.$v['nik'].'</a></td>
                                  <td>'.$v['nama'].'</td>
                                  <td>'.$v['jabatan'].'</td>
                                  <td>'.$v['bagian'].'</td>
                                  <td>'.$v['loker'].'</td>
                                  <td>';
                                  if (isset($v['nilai_target'])) {
                                    $nat[$k]=$v['nilai_target']*($bobot[$k]['bobot_out']/100);
                                    echo number_format($nat[$k],2,',',',');

                                  }else{
                                    echo number_format(0,2,',',',');
                                    $nat[$k]=0;
                                  }
                                  echo '</td>
                                  <td>';
                                  if (isset($v['nilai_sikap'])) {
                                    if ($v['nilai_kalibrasi'] != 0) {
                                      $n_sikap=$v['nilai_kalibrasi'];
                                    }else{
                                      $n_sikap=$v['nilai_sikap'];
                                    }
                                    $nas[$k]=$n_sikap*($bobot[$k]['bobot_skp']/100);
                                    echo number_format($nas[$k],2,',',',');
                                  }else{
                                    echo number_format(0,2,',',',');
                                    $nas[$k]=0;

                                  }
                                  echo '</td>
                                  <td>';
                                  if (isset($v['nilai_corp'])) {
                                    $nac[$k]=$v['nilai_corp']*($bobot[$k]['bobot_tc']/100);
                                    echo number_format($nac[$k],2,',',',');

                                  }else{
                                    echo number_format(0,2,',',',');
                                    $nac[$k]=0;
                                  }
                                  $nil[$k]=$nat[$k]+$nas[$k]+$nac[$k];
                                  echo '</td>
                                  <td>'.number_format(($nat[$k]+$nas[$k]+$nac[$k]),2,',',',').'</td>
                                  ';
                                  $con=$this->db->get_where('master_konversi_nilai',array('status'=>'aktif'))->result();
                                  foreach ($con as $c) {
                                    if (number_format(($nat[$k]+$nas[$k]+$nac[$k]),2) >= $c->awal && number_format(($nat[$k]+$nas[$k]+$nac[$k]),2) <= $c->akhir) {
                                      echo '<td style="background-color:'.$c->warna.';color:#fff;" class="text-center">'.$c->huruf.'</td>';
                                    }
                                  }
                                  echo '
                                </tr>';
                                $n++;
                          }
                           
                          /*
                          echo '<tr>
                                  <td width="3%">'.$n.'.</td>
                                  <td width="15%"><a href="'.base_url('pages/result_tasks_group/'.$l->kode_agenda).'">'.$l->nama_agenda.' <br>
                                  <label class="label label-primary">'.$l->tahun.'</label>';
                                  if ($l->semester == 1) {
                                    echo '<label class="label label-info">Semester 1</label>';
                                  }else{
                                    echo '<label class="label label-success">Semester 2</label>';

                                  }
                                  echo '</a></td>
                                  <td width="30%">';
                                  if ($l->keterangan == "not_entry") {
                                    echo '<label class="label label-danger">Indikator Belum Dimasukkan</label>';
                                  }else{
                                    $nmtb1=$l->tabel_agenda;
                                    $dto=$this->db->query("SELECT * FROM $nmtb1 WHERE nilai_out != 0")->num_rows();
                                    $dto1=$this->db->query("SELECT * FROM $nmtb1 WHERE nilai_sikap != 0")->num_rows();
                                    $dto2=$this->db->query("SELECT * FROM $nmtb1 WHERE nilai_tc != 0")->num_rows();
                                    $dtt1=$this->db->query("SELECT * FROM $nmtb1")->num_rows();
                                    $tdto=$dto+$dto1+$dto2;
                                    $jm1= ($tdto/($dtt1*3))*100;
                                    $jm=number_format($jm1,2);
                                    /*
                                    if ($jm == 100) {
                                      $dtt=array('keterangan'=>"done");
                                      $this->db->where('kode_agenda',$l->kode_agenda);
                                      $this->db->update('agenda',$dtt);
                                      $this->db->where('kode_agenda',$l->kode_agenda);
                                      $this->db->update('log_agenda',$dtt);
                                    }else{
                                      $dtt=array('keterangan'=>"progress");
                                      $this->db->where('kode_agenda',$l->kode_agenda);
                                      $this->db->update('agenda',$dtt);
                                      $this->db->where('kode_agenda',$l->kode_agenda);
                                      $this->db->update('log_agenda',$dtt);
                                    }
                                    
                                    echo '<div class="progress active">
                                        <div class="progress-bar progress-bar-primary progress-bar-striped" role="progressbar" aria-valuenow="'.$jm.'" aria-valuemin="0" aria-valuemax="100" style="width: '.$jm.'%">
                                          '.$jm.'%
                                        </div>
                                      </div>';
                                  }
                                  echo '</td>
                                  <td>'.date('d/m/Y H:i:s',strtotime($l->tgl_mulai)).' WIB</td>
                                  <td>'.date('d/m/Y H:i:s',strtotime($l->tgl_selesai)).' WIB</td>
                                  <td>';
                                  if ($l->keterangan == "not_entry") {
                                    echo '<label class="label label-danger">Indikator Belum Dimasukkan</label>';
                                  }elseif ($l->keterangan == "progress") {
                                    echo '<label class="label label-warning">Proses Entry Data</label>';
                                  }else{
                                    echo '<label class="label label-success">Semua Data Selesai Diisi</label>';
                                  }
                                  echo '<br>';
                                  if (date("Y-m-d H:i:s",strtotime($l->tgl_selesai)) < date("Y-m-d H:i:s",strtotime($tgl))) {
                                    echo '<label class="label label-danger">Waktu Agenda Sudah Habis, Agenda Ditutup</label>';
                                  }elseif ((date("Y-m-d H:i:s",strtotime($l->tgl_mulai)) <= date("Y-m-d H:i:s",strtotime($tgl))) && (date("Y-m-d H:i:s",strtotime($l->tgl_selesai)) >= date("Y-m-d H:i:s",strtotime($tgl)))) {
                                    echo '<label class="label label-info">Agenda Sedang Berlangsung</label>';
                                  }
                                  echo '</td>
                                </tr>';
                             */   
                                
                          
                        }
                      ?>
                    </tbody>
                  </table>
                </div>
                  <?php } }
                  if (count($att) > 0 && isset($_SESSION['nilai'])) {
                    $mm=array();
                    if (isset($nat)) {
                      $minnat=number_format(min($nat),2,',',',');
                      $maxnat=number_format(max($nat),2,',',',');
                      $avgnat=number_format((array_sum($nat)/count($nat)),2,',',',');
                      array_push($mm, 1);
                    }else{
                      $minnat=number_format(0,2,',',',');
                      $maxnat=number_format(0,2,',',',');
                      $avgnat=number_format(0,2,',',',');
                    }
                    if (isset($nas)) {
                      $minnas=number_format(min($nas),2,',',',');
                      $maxnas=number_format(max($nas),2,',',',');
                      $avgnas=number_format((array_sum($nas)/count($nas)),2,',',',');
                      array_push($mm, 1);
                    }else{
                      $minnas=number_format(0,2,',',',');
                      $maxnas=number_format(0,2,',',',');
                      $avgnas=number_format(0,2,',',',');
                    }
                    if (isset($nac)) {
                      $minnac=number_format(min($nac),2,',',',');
                      $maxnac=number_format(max($nac),2,',',',');
                      $avgnac=number_format((array_sum($nac)/count($nac)),2,',',',');
                      array_push($mm, 1);
                    }else{
                      $minnac=number_format(0,2,',',',');
                      $maxnac=number_format(0,2,',',',');
                      $avgnac=number_format(0,2,',',',');
                    }
                    if (isset($nil)) {
                      $minnil=number_format(min($nil),2,',',',');
                      $maxnil=number_format(max($nil),2,',',',');
                      $avgnil=number_format((array_sum($nil)/count($nil)),2,',',',');
                      array_push($mm, 1);
                    }else{
                      $minnil=number_format(0,2,',',',');
                      $maxnil=number_format(0,2,',',',');
                      $avgnil=number_format(0,2,',',',');
                    }
                    if (count($mm) > 0) {
                    
                    ?>
                    <div class="row">
                      <div class="col-md-3"></div>
                      <div class="col-md-6">
                        <table class="table table-hover table-striped table-bordered text-center">
                          <thead class="bg-blue">
                            <tr>
                              <th>Keterangan</th>
                              <th>Nilai Target</th>
                              <th>Nilai Sikap</th>
                              <th>Nilai OS</th>
                              <th>Nilai Total</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr class="bg-red">
                              <td>Nilai Terendah</td>
                              <td><?php echo $minnat;?></td>
                              <td><?php echo $minnas;?></td>
                              <td><?php echo $minnac;?></td>
                              <td><?php echo $minnil;?></td>
                            </tr>
                            <tr class="bg-green">
                              <td>Nilai Tertinggi</td>
                              <td><?php echo $maxnat;?></td>
                              <td><?php echo $maxnas;?></td>
                              <td><?php echo $maxnac;?></td>
                              <td><?php echo $maxnil;?></td>
                            </tr>
                            <tr class="bg-aqua">
                              <td>Nilai Rata - Rata</td>
                              <td><b><?php echo $avgnat;?></b></td>
                              <td><b><?php echo $avgnas;?></b></td>
                              <td><b><?php echo $avgnac;?></b></td>
                              <td><b><?php echo $avgnil;?></b></td>
                            </tr>
                          </tbody>
                        </table>
                      </div>

                    </div>
                    
                    <?php }}?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div> 