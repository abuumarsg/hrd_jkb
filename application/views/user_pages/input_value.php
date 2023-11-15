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
       <a class="back" onclick="window.history.back()"><i class="fa fa-chevron-circle-left "></i></a> <i class="fa fa-edit"></i> Input Nilai Output (Target)
       <small><?php echo $nama;?></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo base_url('kpages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
        <li><a href="<?php echo base_url('kpages/tasks');?>"><i class="fa fa-table"></i> Daftar Agenda</a></li>
        <li><a href="<?php echo base_url('kpages/input_tasks_value/'.$agd['kode_agenda'])?>"><i class="fa fa-users"></i> Daftar Karyawan</a></li>
        <li class="active">Input Nilai <?php echo $nama;?></li>
      </ol>
    </section>
    <section class="content"> 
      <?php 
        $jo=array();
        $lo=array();
        foreach ($tabel as $hs) {
          array_push($jo, $hs->id_jabatan);
          array_push($lo, $hs->id_loker);
        }

        $jo2=array_unique($jo);
        $lo2=array_unique($lo);
        $jo1=array_values($jo2);
        $lo1=array_values($lo2);
        if (count($jo1) > count($lo1)) {
          foreach ($lo1 as $lo2) {
            array_push($lo1, $lo2);
          }
        }
        if(count($jo1) < count($lo1)){
          foreach ($jo1 as $jo2) {
            array_push($jo1, $jo2); 
          }
        }
        foreach ($jo1 as $k => $ja) {
            $xs[$k]=array();
            $jabat=$this->db->get_where('master_jabatan',array('id_jabatan'=>$ja))->row_array();
            $loker=$this->db->get_where('master_loker',array('id_loker'=>$lo1[$k]))->row_array();
            $dtd=$this->db->get_where($ntabel,array('id_karyawan'=>$id))->row_array();
            $kode=$dtd['kode_agenda'];
            $agd=$this->db->get_where('agenda',array('kode_agenda'=>$kode))->row_array();
            //$hasil2=$this->db->get_where($ntabel,array('id_jabatan'=>$ja,'id_loker'=>$lo1[$k],'id_karyawan'=>$id))->result(); 
            $hasil2=$this->db->query("SELECT * FROM $ntabel WHERE id_jabatan = '$ja' AND id_loker = '$lo1[$k]' AND id_karyawan = '$id' ORDER BY urutan ASC")->result(); 
      ?>
      <div class="row">
        <div class="col-md-12">
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-table"></i> Daftar Indikator Untuk <?php echo $nama;?></h3>
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
                    echo form_open('kagenda/input_value');
                    if (count($jo1) > 1) {
                      echo '<div class="col-md-4"></div>
                      <div class="col-md-4">
                      <table class="table table-bordered">
                        <thead>
                          <tr>
                            <th class="bg-red">Jabatan</th>
                            <th class="bg-yellow">Lokasi Kerja</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td>'.$jabat['jabatan'].'</td>
                            <td>'.$loker['nama'].'</td>
                          </tr>
                        </tbody> 
                      </table>
                      </div>';
                    }
                  ?>
                  <div class="table-responsive">
                  <table class="table table-bordered table-striped">
                    <thead>
                      <tr class="bg-blue">
                        <th>No.</th>
                        <th>Indikator</th>
                        <th>Cara Mengukur</th>
                        <th>Target</th>
                        <th>Bobot</th>
                        <?php 
                        if ($jabat['kode_periode'] == 'BLN') {
                          if ($smt == 1) {
                            echo '<th>Januari</th>
                            <th>Februari</th>
                            <th>Maret</th>
                            <th>April</th>
                            <th>Mei</th>
                            <th>Juni</th>
                            ';
                          }else{
                            echo '<th>Juli</th>
                            <th>Agustus</th>
                            <th>September</th>
                            <th>Oktober</th>
                            <th>November</th>
                            <th>Desember</th>
                            ';
                          }
                        }elseif ($jabat['kode_periode'] == 'SMT') {
                          if ($smt == 1) {
                            echo '<th>Januari - Juni</th>';
                          }else{
                            echo '<th>Juli - Desember</th>';
                          }
                        }
                        
                        ?>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                        $n=1;

                        foreach ($hasil2 as $t) {
                          
                          $jb=$this->db->get_where('master_jabatan',array('id_jabatan'=>$t->id_jabatan))->row_array();
                          $kjb=$jb['atasan'];
                          $jb2=$this->db->get_where('master_jabatan',array('kode_jabatan'=>$kjb))->row_array();
                          $jbb[$jb2['jabatan']]=$jb2['kode_jabatan'];
                          $expl=explode(';', $t->id_penilai);
                          if (in_array($nik, $expl)) {
                            $ckk=1;
                            array_push($xs[$k], 1);
                          }else{
                            $ckk=0;
                          }
                          if ($jabatanx == $jb2['kode_jabatan'] && $t->kode_penilai == 'P1') {
                            echo '<tr>
                                  <td width="3%">'.$n.'.</td> 
                                  <td>'.$t->indikator.'</td>
                                  <td>'.$t->cara_mengukur.'</td>
                                  <td>'.$t->target.'%</td>
                                  <td>'.$t->bobot.' '.$t->satuan.'</td>';
                                  if ($jbtk['kode_periode'] == 'BLN') {
                                    if ($t->kaitan == 1) {
                                      echo '<td class="text-center" width="8%">';
                                      if ($t->na1 == 0) {
                                        echo '<label class="label label-danger">Nilai Belum Terkait</label>';
                                      }else{
                                        echo number_format($t->na1,2);
                                      }
                                      echo '</td>
                                      <td class="text-center" width="8%">';
                                      if ($t->na2 == 0) {
                                        echo '<label class="label label-danger">Nilai Belum Terkait</label>';
                                      }else{
                                        echo number_format($t->na2,2);
                                      }
                                      echo '</td>
                                      <td class="text-center" width="8%">';
                                      if ($t->na3 == 0) {
                                        echo '<label class="label label-danger">Nilai Belum Terkait</label>';
                                      }else{
                                        echo number_format($t->na3,2);
                                      }
                                      echo '</td>
                                      <td class="text-center" width="8%">';
                                      if ($t->na4 == 0) {
                                        echo '<label class="label label-danger">Nilai Belum Terkait</label>';
                                      }else{
                                        echo number_format($t->na4,2);
                                      }
                                      echo '</td>
                                      <td class="text-center" width="8%">';
                                      if ($t->na5 == 0) {
                                        echo '<label class="label label-danger">Nilai Belum Terkait</label>';
                                      }else{
                                        echo number_format($t->na5,2);
                                      }
                                      echo '</td>
                                      <td class="text-center" width="8%">';
                                      if ($t->na6 == 0) {
                                        echo '<label class="label label-danger">Nilai Belum Terkait</label>';
                                      }else{
                                        echo number_format($t->na6,2);
                                      }
                                      echo '</td>';
                                    }else{
                                      if ($t->ln1 == '0') {
                                        echo '<td width="8%"><input type="number" style="min-width:200px;" min="0" max="100" name="ln1['.$t->kode_indikator.']" placeholder="Input Nilai" class="form-control" step="0.01"></td>';
                                      }else{
                                        echo '<td width="8%">';
                                        $nx_1[$t->kode_indikator]=array();
                                        $ni2[$t->kode_indikator]=explode(',', $t->ln1);
                                        $ni[$t->kode_indikator]=array_filter($ni2[$t->kode_indikator]);
                                        foreach ($ni[$t->kode_indikator] as $naa[$t->kode_indikator]) {
                                          $front[$t->kode_indikator]= str_replace('{', '', $naa[$t->kode_indikator]);
                                          $back1[$t->kode_indikator]= str_replace('}', '', $front[$t->kode_indikator]);
                                          $back2[$t->kode_indikator]=explode(' ', $back1[$t->kode_indikator]);
                                          if ($back2[$t->kode_indikator][0] == 'KAR') {
                                            $back3[$t->kode_indikator]=explode(':', $back2[$t->kode_indikator][1]);
                                            if ($back3[$t->kode_indikator][0] == $_SESSION['emp']['id']) {
                                              array_push($nx_1[$t->kode_indikator],$back3[$t->kode_indikator][1]);
                                            }
                                          }
                                        }
                                        if (isset($nx_1[$t->kode_indikator])) {
                                          $nfa1[$t->kode_indikator]=implode('', $nx_1[$t->kode_indikator]);
                                        }else{
                                          $nfa1[$t->kode_indikator]=NULL;
                                        }
                                        echo '<input type="number" style="min-width:200px;" min="0" max="100" name="ln1['.$t->kode_indikator.']" placeholder="Input Nilai"  value="'.$nfa1[$t->kode_indikator].'"  class="form-control" step="0.01"></td>';

                                      }
                                      if ($t->ln2 == '0') {
                                        echo '<td width="8%"><input type="number" style="min-width:200px;" min="0" max="100" name="ln2['.$t->kode_indikator.']" placeholder="Input Nilai" class="form-control" step="0.01"></td>';
                                      }else{
                                        echo '<td width="8%">';
                                        $nx_2[$t->kode_indikator]=array();
                                        $ni2[$t->kode_indikator]=explode(',', $t->ln2);
                                        $ni[$t->kode_indikator]=array_filter($ni2[$t->kode_indikator]);
                                        foreach ($ni[$t->kode_indikator] as $naa[$t->kode_indikator]) {
                                          $front[$t->kode_indikator]= str_replace('{', '', $naa[$t->kode_indikator]);
                                          $back1[$t->kode_indikator]= str_replace('}', '', $front[$t->kode_indikator]);
                                          $back2[$t->kode_indikator]=explode(' ', $back1[$t->kode_indikator]);
                                          if ($back2[$t->kode_indikator][0] == 'KAR') {
                                            $back3[$t->kode_indikator]=explode(':', $back2[$t->kode_indikator][1]);
                                            if ($back3[$t->kode_indikator][0] == $_SESSION['emp']['id']) {
                                              array_push($nx_2[$t->kode_indikator],$back3[$t->kode_indikator][1]);
                                            }
                                          }
                                        }
                                        if (isset($nx_2[$t->kode_indikator])) {
                                          $nfa2[$t->kode_indikator]=implode('', $nx_2[$t->kode_indikator]);
                                        }else{
                                          $nfa2[$t->kode_indikator]=NULL;
                                        }
                                        echo '<input type="number" style="min-width:200px;"  min="0" max="100" name="ln2['.$t->kode_indikator.']" placeholder="Input Nilai"  value="'.$nfa2[$t->kode_indikator].'"  class="form-control" step="0.01"></td>';

                                      }
                                      if ($t->ln3 == '0') {
                                        echo '<td width="8%"><input type="number" style="min-width:200px;" min="0" max="100" name="ln3['.$t->kode_indikator.']" placeholder="Input Nilai" class="form-control" step="0.01"></td>';
                                      }else{
                                        echo '<td width="8%">';
                                        $nx_3[$t->kode_indikator]=array();
                                        $ni2[$t->kode_indikator]=explode(',', $t->ln3);
                                        $ni[$t->kode_indikator]=array_filter($ni2[$t->kode_indikator]);
                                        foreach ($ni[$t->kode_indikator] as $naa[$t->kode_indikator]) {
                                          $front[$t->kode_indikator]= str_replace('{', '', $naa[$t->kode_indikator]);
                                          $back1[$t->kode_indikator]= str_replace('}', '', $front[$t->kode_indikator]);
                                          $back2[$t->kode_indikator]=explode(' ', $back1[$t->kode_indikator]);
                                          if ($back2[$t->kode_indikator][0] == 'KAR') {
                                            $back3[$t->kode_indikator]=explode(':', $back2[$t->kode_indikator][1]);
                                            if ($back3[$t->kode_indikator][0] == $_SESSION['emp']['id']) {
                                              array_push($nx_3[$t->kode_indikator],$back3[$t->kode_indikator][1]);
                                            }
                                          }
                                        }
                                        if (isset($nx_3[$t->kode_indikator])) {
                                          $nfa3[$t->kode_indikator]=implode('', $nx_3[$t->kode_indikator]);
                                        }else{
                                          $nfa3[$t->kode_indikator]=NULL;
                                        }
                                        echo '<input type="number" style="min-width:200px;" min="0" max="100" name="ln3['.$t->kode_indikator.']" placeholder="Input Nilai"  value="'.$nfa3[$t->kode_indikator].'" class="form-control" step="0.01"></td>';

                                      }
                                      if ($t->ln4 == '0') {
                                        echo '<td width="8%"><input type="number" style="min-width:200px;" min="0" max="100" name="ln4['.$t->kode_indikator.']" placeholder="Input Nilai" class="form-control" step="0.01"></td>';
                                      }else{
                                        echo '<td width="8%">';
                                        $nx_4[$t->kode_indikator]=array();
                                        $ni2[$t->kode_indikator]=explode(',', $t->ln4);
                                        $ni[$t->kode_indikator]=array_filter($ni2[$t->kode_indikator]);
                                        foreach ($ni[$t->kode_indikator] as $naa[$t->kode_indikator]) {
                                          $front[$t->kode_indikator]= str_replace('{', '', $naa[$t->kode_indikator]);
                                          $back1[$t->kode_indikator]= str_replace('}', '', $front[$t->kode_indikator]);
                                          $back2[$t->kode_indikator]=explode(' ', $back1[$t->kode_indikator]);
                                          if ($back2[$t->kode_indikator][0] == 'KAR') {
                                            $back3[$t->kode_indikator]=explode(':', $back2[$t->kode_indikator][1]);
                                            if ($back3[$t->kode_indikator][0] == $_SESSION['emp']['id']) {
                                              array_push($nx_4[$t->kode_indikator],$back3[$t->kode_indikator][1]);
                                            }
                                          }
                                        }
                                        if (isset($nx_4[$t->kode_indikator])) {
                                          $nfa4[$t->kode_indikator]=implode('', $nx_4[$t->kode_indikator]);
                                        }else{
                                          $nfa4[$t->kode_indikator]=NULL;
                                        }
                                        echo '<input type="number" style="min-width:200px;" min="0" max="100" name="ln4['.$t->kode_indikator.']" placeholder="Input Nilai"  value="'.$nfa4[$t->kode_indikator].'" class="form-control" step="0.01"></td>';

                                      }
                                      if ($t->ln5 == '0') {
                                        echo '<td width="8%"><input type="number" style="min-width:200px;" min="0" max="100" name="ln5['.$t->kode_indikator.']" placeholder="Input Nilai" class="form-control" step="0.01"></td>';
                                      }else{
                                        echo '<td width="8%">';
                                        $nx_5[$t->kode_indikator]=array();
                                        $ni2[$t->kode_indikator]=explode(',', $t->ln5);
                                        $ni[$t->kode_indikator]=array_filter($ni2[$t->kode_indikator]);
                                        foreach ($ni[$t->kode_indikator] as $naa[$t->kode_indikator]) {
                                          $front[$t->kode_indikator]= str_replace('{', '', $naa[$t->kode_indikator]);
                                          $back1[$t->kode_indikator]= str_replace('}', '', $front[$t->kode_indikator]);
                                          $back2[$t->kode_indikator]=explode(' ', $back1[$t->kode_indikator]);
                                          if ($back2[$t->kode_indikator][0] == 'KAR') {
                                            $back3[$t->kode_indikator]=explode(':', $back2[$t->kode_indikator][1]);
                                            if ($back3[$t->kode_indikator][0] == $_SESSION['emp']['id']) {
                                              array_push($nx_5[$t->kode_indikator],$back3[$t->kode_indikator][1]);
                                            }
                                          }
                                        }
                                        if (isset($nx_5[$t->kode_indikator])) {
                                          $nfa5[$t->kode_indikator]=implode('', $nx_5[$t->kode_indikator]);
                                        }else{
                                          $nfa5[$t->kode_indikator]=NULL;
                                        }
                                        echo '<input type="number" style="min-width:200px;" min="0" max="100" name="ln5['.$t->kode_indikator.']" placeholder="Input Nilai"  value="'.$nfa5[$t->kode_indikator].'" placeholder="Input Nilai"  class="form-control" step="0.01"></td>';

                                      }
                                      if ($t->ln6 == '0') {
                                        echo '<td width="8%"><input type="number" style="min-width:200px;" min="0" max="100" name="ln6['.$t->kode_indikator.']" placeholder="Input Nilai" class="form-control" step="0.01"></td>';
                                      }else{
                                        echo '<td width="8%">';
                                        $nx_6[$t->kode_indikator]=array();
                                        $ni2[$t->kode_indikator]=explode(',', $t->ln6);
                                        $ni[$t->kode_indikator]=array_filter($ni2[$t->kode_indikator]);
                                        foreach ($ni[$t->kode_indikator] as $naa[$t->kode_indikator]) {
                                          $front[$t->kode_indikator]= str_replace('{', '', $naa[$t->kode_indikator]);
                                          $back1[$t->kode_indikator]= str_replace('}', '', $front[$t->kode_indikator]);
                                          $back2[$t->kode_indikator]=explode(' ', $back1[$t->kode_indikator]);
                                          if ($back2[$t->kode_indikator][0] == 'KAR') {
                                            $back3[$t->kode_indikator]=explode(':', $back2[$t->kode_indikator][1]);
                                            if ($back3[$t->kode_indikator][0] == $_SESSION['emp']['id']) {
                                              array_push($nx_6[$t->kode_indikator],$back3[$t->kode_indikator][1]);
                                            }
                                          }
                                        }
                                        if (isset($nx_6[$t->kode_indikator])) {
                                          $nfa6[$t->kode_indikator]=implode('', $nx_6[$t->kode_indikator]);
                                        }else{
                                          $nfa6[$t->kode_indikator]=NULL;
                                        }
                                        echo '<input type="number" style="min-width:200px;" min="0" max="100" name="ln6['.$t->kode_indikator.']" value="'.$nfa6[$t->kode_indikator].'" placeholder="Input Nilai" class="form-control" step="0.01"></td>';

                                      }

                                    }
                                  }elseif ($jbtk['kode_periode'] == 'SMT') {
                                    if ($t->kaitan == 1) {
                                      echo '<td class="text-center" width="8%">';
                                      if ($t->na6 == 0) {
                                        echo '<label class="label label-danger">Nilai Belum Terkait</label>';
                                      }else{
                                        echo number_format($t->na6,2);
                                      }
                                      echo '</td>';
                                    }else{
                                      if ($t->ln6 == '0') {
                                        echo '<td width="8%"><input type="number" style="min-width:200px;" min="0" max="100" name="ln6['.$t->kode_indikator.']" placeholder="Input Nilai" class="form-control" step="0.01"></td>';
                                      }else{
                                        echo '<td width="8%">';
                                        $nx6[$t->kode_indikator]=array();
                                        $ni6[$t->kode_indikator]=explode(',', $t->ln6);
                                        $nix6[$t->kode_indikator]=array_filter($ni6[$t->kode_indikator]);
                                        foreach ($ni6[$t->kode_indikator] as $naa6[$t->kode_indikator]) {
                                          $front6[$t->kode_indikator]= str_replace('{', '', $naa6[$t->kode_indikator]);
                                          $back16[$t->kode_indikator]= str_replace('}', '', $front6[$t->kode_indikator]);
                                          $back26[$t->kode_indikator]=explode(' ', $back16[$t->kode_indikator]);
                                          if ($back26[$t->kode_indikator][0] == 'KAR') {
                                            $back36[$t->kode_indikator]=explode(':', $back26[$t->kode_indikator][1]);
                                            if ($back36[$t->kode_indikator][0] == $_SESSION['emp']['id']) {
                                              array_push($nx6[$t->kode_indikator],$back36[$t->kode_indikator][1]);
                                            }
                                          }
                                        }
                                        if (isset($nx6[$t->kode_indikator])) {
                                          $nfa_6[$t->kode_indikator]=implode('', $nx6[$t->kode_indikator]);
                                        }else{
                                          $nfa_6[$t->kode_indikator]=NULL;
                                        }
                                        echo '<input type="number" style="min-width:200px;" min="0" max="100" name="ln6['.$t->kode_indikator.']" value="'.$nfa_6[$t->kode_indikator].'" placeholder="Input Nilai" class="form-control" step="0.01"></td>';
                                      }
                                    }
                                  }
                                  echo '</tr>'; 
                                  $n++;
                                }elseif ($ckk == 1 && $t->kode_penilai == 'P4') {
                                  echo '<tr>
                                  <td width="3%">'.$n.'.</td> 
                                  <td>'.$t->indikator.'</td>
                                  <td>'.$t->cara_mengukur.'</td>
                                  <td>'.$t->target.'%</td>
                                  <td>'.$t->bobot.' '.$t->satuan.'</td>';
                                  if ($jbtk['kode_periode'] == 'BLN') {
                                    if ($t->kaitan == 1) {
                                      echo '<td class="text-center" width="8%">';
                                      if ($t->na1 == 0) {
                                        echo '<label class="label label-danger">Nilai Belum Terkait</label>';
                                      }else{
                                        echo number_format($t->na1,2);
                                      }
                                      echo '</td>
                                      <td class="text-center" width="8%">';
                                      if ($t->na2 == 0) {
                                        echo '<label class="label label-danger">Nilai Belum Terkait</label>';
                                      }else{
                                        echo number_format($t->na2,2);
                                      }
                                      echo '</td>
                                      <td class="text-center" width="8%">';
                                      if ($t->na3 == 0) {
                                        echo '<label class="label label-danger">Nilai Belum Terkait</label>';
                                      }else{
                                        echo number_format($t->na3,2);
                                      }
                                      echo '</td>
                                      <td class="text-center" width="8%">';
                                      if ($t->na4 == 0) {
                                        echo '<label class="label label-danger">Nilai Belum Terkait</label>';
                                      }else{
                                        echo number_format($t->na4,2);
                                      }
                                      echo '</td>
                                      <td class="text-center" width="8%">';
                                      if ($t->na5 == 0) {
                                        echo '<label class="label label-danger">Nilai Belum Terkait</label>';
                                      }else{
                                        echo number_format($t->na5,2);
                                      }
                                      echo '</td>
                                      <td class="text-center" width="8%">';
                                      if ($t->na6 == 0) {
                                        echo '<label class="label label-danger">Nilai Belum Terkait</label>';
                                      }else{
                                        echo number_format($t->na6,2);
                                      }
                                      echo '</td>';
                                    }else{
                                      if ($t->ln1 == '0') {
                                        echo '<td width="8%"><input type="number" style="min-width:200px;" min="0" max="100" name="ln1['.$t->kode_indikator.']" placeholder="Input Nilai" class="form-control" step="0.01"></td>';
                                      }else{
                                        echo '<td width="8%">';
                                        $nx_1[$t->kode_indikator]=array();
                                        $ni2[$t->kode_indikator]=explode(',', $t->ln1);
                                        $ni[$t->kode_indikator]=array_filter($ni2[$t->kode_indikator]);
                                        foreach ($ni[$t->kode_indikator] as $naa[$t->kode_indikator]) {
                                          $front[$t->kode_indikator]= str_replace('{', '', $naa[$t->kode_indikator]);
                                          $back1[$t->kode_indikator]= str_replace('}', '', $front[$t->kode_indikator]);
                                          $back2[$t->kode_indikator]=explode(' ', $back1[$t->kode_indikator]);
                                          if ($back2[$t->kode_indikator][0] == 'KAR') {
                                            $back3[$t->kode_indikator]=explode(':', $back2[$t->kode_indikator][1]);
                                            if ($back3[$t->kode_indikator][0] == $_SESSION['emp']['id']) {
                                              array_push($nx_1[$t->kode_indikator],$back3[$t->kode_indikator][1]);
                                            }
                                          }
                                        }
                                        if (isset($nx_1[$t->kode_indikator])) {
                                          $nfa1[$t->kode_indikator]=implode('', $nx_1[$t->kode_indikator]);
                                        }else{
                                          $nfa1[$t->kode_indikator]=NULL;
                                        }
                                        echo '<input type="number" style="min-width:200px;" min="0" max="100" name="ln1['.$t->kode_indikator.']" placeholder="Input Nilai"  value="'.$nfa1[$t->kode_indikator].'"  class="form-control" step="0.01"></td>';

                                      }
                                      if ($t->ln2 == '0') {
                                        echo '<td width="8%"><input type="number" style="min-width:200px;" min="0" max="100" name="ln2['.$t->kode_indikator.']" placeholder="Input Nilai" class="form-control" step="0.01"></td>';
                                      }else{
                                        echo '<td width="8%">';
                                        $nx_2[$t->kode_indikator]=array();
                                        $ni2[$t->kode_indikator]=explode(',', $t->ln2);
                                        $ni[$t->kode_indikator]=array_filter($ni2[$t->kode_indikator]);
                                        foreach ($ni[$t->kode_indikator] as $naa[$t->kode_indikator]) {
                                          $front[$t->kode_indikator]= str_replace('{', '', $naa[$t->kode_indikator]);
                                          $back1[$t->kode_indikator]= str_replace('}', '', $front[$t->kode_indikator]);
                                          $back2[$t->kode_indikator]=explode(' ', $back1[$t->kode_indikator]);
                                          if ($back2[$t->kode_indikator][0] == 'KAR') {
                                            $back3[$t->kode_indikator]=explode(':', $back2[$t->kode_indikator][1]);
                                            if ($back3[$t->kode_indikator][0] == $_SESSION['emp']['id']) {
                                              array_push($nx_2[$t->kode_indikator],$back3[$t->kode_indikator][1]);
                                            }
                                          }
                                        }
                                        if (isset($nx_2[$t->kode_indikator])) {
                                          $nfa2[$t->kode_indikator]=implode('', $nx_2[$t->kode_indikator]);
                                        }else{
                                          $nfa2[$t->kode_indikator]=NULL;
                                        }
                                        echo '<input type="number" style="min-width:200px;"  min="0" max="100" name="ln2['.$t->kode_indikator.']" placeholder="Input Nilai"  value="'.$nfa2[$t->kode_indikator].'"  class="form-control" step="0.01"></td>';

                                      }
                                      if ($t->ln3 == '0') {
                                        echo '<td width="8%"><input type="number" style="min-width:200px;" min="0" max="100" name="ln3['.$t->kode_indikator.']" placeholder="Input Nilai" class="form-control" step="0.01"></td>';
                                      }else{
                                        echo '<td width="8%">';
                                        $nx_3[$t->kode_indikator]=array();
                                        $ni2[$t->kode_indikator]=explode(',', $t->ln3);
                                        $ni[$t->kode_indikator]=array_filter($ni2[$t->kode_indikator]);
                                        foreach ($ni[$t->kode_indikator] as $naa[$t->kode_indikator]) {
                                          $front[$t->kode_indikator]= str_replace('{', '', $naa[$t->kode_indikator]);
                                          $back1[$t->kode_indikator]= str_replace('}', '', $front[$t->kode_indikator]);
                                          $back2[$t->kode_indikator]=explode(' ', $back1[$t->kode_indikator]);
                                          if ($back2[$t->kode_indikator][0] == 'KAR') {
                                            $back3[$t->kode_indikator]=explode(':', $back2[$t->kode_indikator][1]);
                                            if ($back3[$t->kode_indikator][0] == $_SESSION['emp']['id']) {
                                              array_push($nx_3[$t->kode_indikator],$back3[$t->kode_indikator][1]);
                                            }
                                          }
                                        }
                                        if (isset($nx_3[$t->kode_indikator])) {
                                          $nfa3[$t->kode_indikator]=implode('', $nx_3[$t->kode_indikator]);
                                        }else{
                                          $nfa3[$t->kode_indikator]=NULL;
                                        }
                                        echo '<input type="number" style="min-width:200px;" min="0" max="100" name="ln3['.$t->kode_indikator.']" placeholder="Input Nilai"  value="'.$nfa3[$t->kode_indikator].'" class="form-control" step="0.01"></td>';

                                      }
                                      if ($t->ln4 == '0') {
                                        echo '<td width="8%"><input type="number" style="min-width:200px;" min="0" max="100" name="ln4['.$t->kode_indikator.']" placeholder="Input Nilai" class="form-control" step="0.01"></td>';
                                      }else{
                                        echo '<td width="8%">';
                                        $nx_4[$t->kode_indikator]=array();
                                        $ni2[$t->kode_indikator]=explode(',', $t->ln4);
                                        $ni[$t->kode_indikator]=array_filter($ni2[$t->kode_indikator]);
                                        foreach ($ni[$t->kode_indikator] as $naa[$t->kode_indikator]) {
                                          $front[$t->kode_indikator]= str_replace('{', '', $naa[$t->kode_indikator]);
                                          $back1[$t->kode_indikator]= str_replace('}', '', $front[$t->kode_indikator]);
                                          $back2[$t->kode_indikator]=explode(' ', $back1[$t->kode_indikator]);
                                          if ($back2[$t->kode_indikator][0] == 'KAR') {
                                            $back3[$t->kode_indikator]=explode(':', $back2[$t->kode_indikator][1]);
                                            if ($back3[$t->kode_indikator][0] == $_SESSION['emp']['id']) {
                                              array_push($nx_4[$t->kode_indikator],$back3[$t->kode_indikator][1]);
                                            }
                                          }
                                        }
                                        if (isset($nx_4[$t->kode_indikator])) {
                                          $nfa4[$t->kode_indikator]=implode('', $nx_4[$t->kode_indikator]);
                                        }else{
                                          $nfa4[$t->kode_indikator]=NULL;
                                        }
                                        echo '<input type="number" style="min-width:200px;" min="0" max="100" name="ln4['.$t->kode_indikator.']" placeholder="Input Nilai"  value="'.$nfa4[$t->kode_indikator].'" class="form-control" step="0.01"></td>';

                                      }
                                      if ($t->ln5 == '0') {
                                        echo '<td width="8%"><input type="number" style="min-width:200px;" min="0" max="100" name="ln5['.$t->kode_indikator.']" placeholder="Input Nilai" class="form-control" step="0.01"></td>';
                                      }else{
                                        echo '<td width="8%">';
                                        $nx_5[$t->kode_indikator]=array();
                                        $ni2[$t->kode_indikator]=explode(',', $t->ln5);
                                        $ni[$t->kode_indikator]=array_filter($ni2[$t->kode_indikator]);
                                        foreach ($ni[$t->kode_indikator] as $naa[$t->kode_indikator]) {
                                          $front[$t->kode_indikator]= str_replace('{', '', $naa[$t->kode_indikator]);
                                          $back1[$t->kode_indikator]= str_replace('}', '', $front[$t->kode_indikator]);
                                          $back2[$t->kode_indikator]=explode(' ', $back1[$t->kode_indikator]);
                                          if ($back2[$t->kode_indikator][0] == 'KAR') {
                                            $back3[$t->kode_indikator]=explode(':', $back2[$t->kode_indikator][1]);
                                            if ($back3[$t->kode_indikator][0] == $_SESSION['emp']['id']) {
                                              array_push($nx_5[$t->kode_indikator],$back3[$t->kode_indikator][1]);
                                            }
                                          }
                                        }
                                        if (isset($nx_5[$t->kode_indikator])) {
                                          $nfa5[$t->kode_indikator]=implode('', $nx_5[$t->kode_indikator]);
                                        }else{
                                          $nfa5[$t->kode_indikator]=NULL;
                                        }
                                        echo '<input type="number" style="min-width:200px;" min="0" max="100" name="ln5['.$t->kode_indikator.']" placeholder="Input Nilai"  value="'.$nfa5[$t->kode_indikator].'" placeholder="Input Nilai"  class="form-control" step="0.01"></td>';

                                      }
                                      if ($t->ln6 == '0') {
                                        echo '<td width="8%"><input type="number" style="min-width:200px;" min="0" max="100" name="ln6['.$t->kode_indikator.']" placeholder="Input Nilai" class="form-control" step="0.01"></td>';
                                      }else{
                                        echo '<td width="8%">';
                                        $nx_6[$t->kode_indikator]=array();
                                        $ni2[$t->kode_indikator]=explode(',', $t->ln6);
                                        $ni[$t->kode_indikator]=array_filter($ni2[$t->kode_indikator]);
                                        foreach ($ni[$t->kode_indikator] as $naa[$t->kode_indikator]) {
                                          $front[$t->kode_indikator]= str_replace('{', '', $naa[$t->kode_indikator]);
                                          $back1[$t->kode_indikator]= str_replace('}', '', $front[$t->kode_indikator]);
                                          $back2[$t->kode_indikator]=explode(' ', $back1[$t->kode_indikator]);
                                          if ($back2[$t->kode_indikator][0] == 'KAR') {
                                            $back3[$t->kode_indikator]=explode(':', $back2[$t->kode_indikator][1]);
                                            if ($back3[$t->kode_indikator][0] == $_SESSION['emp']['id']) {
                                              array_push($nx_6[$t->kode_indikator],$back3[$t->kode_indikator][1]);
                                            }
                                          }
                                        }
                                        if (isset($nx_6[$t->kode_indikator])) {
                                          $nfa6[$t->kode_indikator]=implode('', $nx_6[$t->kode_indikator]);
                                        }else{
                                          $nfa6[$t->kode_indikator]=NULL;
                                        }
                                        echo '<input type="number" style="min-width:200px;" min="0" max="100" name="ln6['.$t->kode_indikator.']" value="'.$nfa6[$t->kode_indikator].'" placeholder="Input Nilai" class="form-control" step="0.01"></td>';

                                      }

                                    }
                                  }elseif ($jbtk['kode_periode'] == 'SMT') {
                                    if ($t->kaitan == 1) {
                                      echo '<td class="text-center" width="8%">';
                                      if ($t->na6 == 0) {
                                        echo '<label class="label label-danger">Nilai Belum Terkait</label>';
                                      }else{
                                        echo number_format($t->na6,2);
                                      }
                                      echo '</td>';
                                    }else{
                                      if ($t->ln6 == '0') {
                                        echo '<td width="8%"><input type="number" style="min-width:200px;" min="0" max="100" name="ln6['.$t->kode_indikator.']" placeholder="Input Nilai" class="form-control" step="0.01"></td>';
                                      }else{
                                        echo '<td width="8%">';
                                        $nx6[$t->kode_indikator]=array();
                                        $ni6[$t->kode_indikator]=explode(',', $t->ln6);
                                        $nix6[$t->kode_indikator]=array_filter($ni6[$t->kode_indikator]);
                                        foreach ($ni6[$t->kode_indikator] as $naa6[$t->kode_indikator]) {
                                          $front6[$t->kode_indikator]= str_replace('{', '', $naa6[$t->kode_indikator]);
                                          $back16[$t->kode_indikator]= str_replace('}', '', $front6[$t->kode_indikator]);
                                          $back26[$t->kode_indikator]=explode(' ', $back16[$t->kode_indikator]);
                                          if ($back26[$t->kode_indikator][0] == 'KAR') {
                                            $back36[$t->kode_indikator]=explode(':', $back26[$t->kode_indikator][1]);
                                            if ($back36[$t->kode_indikator][0] == $_SESSION['emp']['id']) {
                                              array_push($nx6[$t->kode_indikator],$back36[$t->kode_indikator][1]);
                                            }
                                          }
                                        }
                                        if (isset($nx6[$t->kode_indikator])) {
                                          $nfa_6[$t->kode_indikator]=implode('', $nx6[$t->kode_indikator]);
                                        }else{
                                          $nfa_6[$t->kode_indikator]=NULL;
                                        }
                                        echo '<input type="number" style="min-width:200px;" min="0" max="100" name="ln6['.$t->kode_indikator.']" value="'.$nfa_6[$t->kode_indikator].'" placeholder="Input Nilai" class="form-control" step="0.01"></td>';
                                      }
                                    }
                                  }
                                  echo '</tr>'; 
                                  $n++;
                                }
                        }
                      ?>
                    </tbody>
                  </table>
                  </div>
                  <?php
                    $sx1[$k]=array_unique($xs[$k]);
                    $sx2[$k]=implode('', $sx1[$k]);
                    if ($jabatanx == $kjb || $sx2[$k] == '1') {
                      
                    
                  ?>
                  <div class="form-group pull-right">
                    <input type="hidden" name="kode" value="<?php echo $kode;?>">
                    <input type="hidden" name="penilai" value="KAR">
                    <input type="hidden" name="tabel" value="<?php echo $ntabel;?>">
                    <input type="hidden" name="id" value="<?php echo $id;?>">
                    <button class="btn btn-danger" type="reset" onclick="function myFunction() {location.reload();}"><i class="fa fa-refresh"></i> Reset</button>
                    <button class="btn btn-success" type="submit"><i class="fa fa-floppy-o"></i> Simpan</button>
                  </div>
                  <?php
                  }
                  echo form_close();
                } ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <?php } ?>
    </section>
  </div> 