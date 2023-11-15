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
        <i class="fa fa-calendar"></i> Agenda 
        <small>Penilaian</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
        <li class="active">Daftar Agenda Penilaian</li>
      </ol>
    </section>
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-calendar"></i> Daftar Agenda Output (Target)</h3>
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
                      <div class="pull-left">
                        <?php 
                          if (in_array($access['l_ac']['add'], $access['access'])) {
                            echo '<button class="btn btn-primary btn-flat" type="button" data-toggle="collapse" data-target="#add" aria-expanded="false" aria-controls="import"><i class="fa fa-calendar"></i> Buat Agenda Baru</button>';
                          }
                        ?>
                      </div>
                      <div class="pull-right" style="font-size: 8pt;">
                        <i class="fa fa-toggle-on stat scc"></i> Aktif<br>
                        <i class="fa fa-toggle-off stat err"></i> Tidak Aktif
                      </div>
                    </div>
                  </div>
                  <?php if (in_array($access['l_ac']['add'], $access['access'])) { ?>
                  <div class="collapse" id="add">
                    <br>
                    <div class="col-md-2"></div>
                    <div class="col-md-8">
                      <?php echo form_open('agenda/add_agenda');?>
                      <p class="text-danger">Semua data harus diisi!
                        <div class="form-group">
                          <label>Nama Agenda Output(Target)</label>
                          <input type="text" placeholder="Masukkan Nama Agenda" name="nama" class="form-control"  required="required">
                        </div>
                        <div class="form-group">
                          <label>Tanggal Mulai dan Selesai:</label>

                          <div class="input-group">
                            <div class="input-group-addon">
                              <i class="fa fa-clock-o"></i>
                            </div>
                            <input type="text" class="form-control pull-right" id="reservationtime" name="date" required="required">
                          </div>
                        </div>
                        <div class="form-group">
                          <div class="col-md-6">
                            <label>Pilih Tahun</label>
                            <?php
                            $years =  range(date("Y",strtotime("+3 Year")), 2015);
                            for ($i=0; $i < count($years) ; $i++) { 
                              $y[$years[$i]]=$years[$i];
                            }
                            $sel = array(date("Y"));
                            $ex = array('class'=>'form-control select2','style'=>'width:100%;','placeholder'=>'Tahun','required'=>'required');
                            echo form_dropdown('tahun',$y,$sel,$ex);
                            ?>
                          </div>
                          <div class="col-md-6">
                            <label>Pilih Semester</label>
                            <?php
                            $op1=array('1'=>'Semester 1','2'=>'Semester 2');
                            $sel1 = array('1');
                            $ex1 = array('class'=>'form-control select2','style'=>'width:100%;','placeholder'=>'Pelaporan','required'=>'required');
                            echo form_dropdown('semester',$op1,$sel1,$ex1);
                            ?>
                          </div>
                        </div>
                        <br>
                        <br>
                        <br>
                        <br>
                        <div class="form-group">
                          <button type="submit" class="btn btn-success" id="save1"><i class="fa fa-floppy-o"></i> Simpan</button>
                        </div>
                        <?php echo form_close();?>
                    </div>
                  </div>
                <?php } ?>
                </div>
              </div><br>
            
              <div class="row">
                <div class="col-md-12">
                  <?php 
                  if (count($agenda) == 0) {
                    echo '<div class="callout callout-danger"><label><i class="fa fa-info-circle"></i> Data Kosong</label><br>Tidak ada data yang ditampilkan</div>';
                  }else{
                  ?>
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>No.</th>
                        <th>Nama Agenda</th>
                        <th>Kaitkan Data</th>
                        <th>Progress</th>
                        <th>Tanggal Agenda</th>
                        <th>Keterangan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                        $n=1;
                        foreach ($agenda as $l) {
                          echo '<tr>
                                  <td width="3%">'.$n.'.</td>
                                  <td width="15%">'.$l->nama_agenda.'<br>
                                  <label class="label label-primary">'.$l->tahun.'</label>';
                                  if ($l->semester == 1) {
                                    echo '<label class="label label-info">Semester 1</label>';
                                  }else{
                                    echo '<label class="label label-success">Semester 2</label>';

                                  }
                                  echo '</td>
                                  <td width="12%" class="text-center">';
                                  if ($l->keterangan == "not_entry") {
                                    echo '<a href="#rc'.$n.'" data-toggle="collapse" class="btn btn-info btn-flat btn-block btn-sm"><i class="fa fa-gear"></i> Kaitkan Rancangan Output</a>
                                    <div id="rc'.$n.'" class="collapse"><br>';
                                    echo form_open('agenda/create_task');
                                    echo '<div class="input-group">';
                                    $agdx1=$this->db->query("SELECT * FROM concept_kpi WHERE status = 'aktif' AND nama_tabel != 'NULL'")->result();
                                      foreach ($agdx1 as $ax1) {
                                        $opx12[$ax1->kode_c_kpi]=$ax1->nama;
                                      }
                                      $selx1 = array(NULL);
                                      $exx1 = array('class'=>'form-control select2','placeholder'=>'Indikator','required'=>'required','style'=>'width:100%;');
                                      echo form_dropdown('concept',$opx12,$selx1,$exx1);
                                      echo '<span class="input-group-btn">
                                            <button type="submit" id="savagdk'.$n.'" class="btn btn-success btn-flat"><i class="fa fa-chain"></i> Kaitkan</button>
                                          </span>
                                      <input type="hidden" name="kode_agenda" value="'.$l->kode_agenda.'"> 
                                      '.form_close().'
                                      </div>';
                                  }else{
                                    $kd=$l->kode_agenda;
                                    //target
                                    $tco=$this->db->query("SELECT * FROM target_corporate WHERE kode_agenda = '$kd'")->row_array();
                                    if ($tco != "") {
                                      $tbco=$tco['nama_tabel'];
                                      $tbc=$this->db->query("SELECT nilai FROM $tbco WHERE id_target = '1' AND nilai != '0'")->num_rows();
                                      $avltc=1;
                                    }else{
                                      $avltc=0;
                                    }
                                    
                                    $tdn=$this->db->query("SELECT * FROM dp_denda WHERE kode_agenda = '$kd'")->row_array();
                                    if ($tdn != "") {
                                      $tbd=$tdn['nama_tabel'];
                                      $tbdn=$this->db->query("SELECT ratapa FROM $tbd WHERE id_denda = '1' AND ratapa != '0'")->num_rows();
                                      $avldn=1;
                                    }else{
                                      $avldn=0;
                                    }
                                    
                                    $tps=$this->db->query("SELECT * FROM dp_presensi WHERE kode_agenda = '$kd'")->row_array();
                                    if ($tps != "") {
                                      $tp=$tps['nama_tabel'];
                                      $tpsn=$this->db->get($tp)->num_rows();
                                      $avlps=1;
                                    }else{
                                      $avlps=0;
                                    }
                                    
                                    $tag=$this->db->query("SELECT * FROM dp_anggaran WHERE kode_agenda = '$kd'")->row_array();
                                    if ($tag != "") {
                                      $tagr=$tag['nama_tabel'];
                                      $tagn=$this->db->get($tagr)->num_rows();
                                      $avlag=1;
                                    }else{
                                      $avlag=0;
                                    }
                                    if ($avltc == 0 || $avldn == 0 || $avlps == 0 || $avlag == 0) {
                                      if ($avltc == 0) {
                                        echo '<a href="'.base_url('agenda/new_target_corporate/'.$kd).'" class="btn btn-danger btn-flat btn-block btn-sm" id="savagdtc'.$n.'"><i class="fa fa-times-circle"></i> Buat Data Target Corporate</a>';
                                      }
                                      if ($avldn == 0) {
                                        echo '<a href="'.base_url('agenda/new_denda/'.$kd).'" class="btn btn-danger btn-flat btn-block btn-sm" id="savagddnd'.$n.'"><i class="fa fa-times-circle"></i> Buat Data Denda</a>';
                                      }
                                      if ($avlps == 0) {
                                        echo '<a href="'.base_url('agenda/new_presensi/'.$kd).'" class="btn btn-danger btn-flat btn-block btn-sm" id="savagdps'.$n.'"><i class="fa fa-times-circle"></i> Buat Data Presensi</a>';
                                      }
                                      if ($avlag == 0) {
                                        echo '<a href="'.base_url('agenda/new_anggaran/'.$kd).'" class="btn btn-danger btn-flat btn-block btn-sm" id="savagdag'.$n.'"><i class="fa fa-times-circle"></i> Buat Data Anggaran</a>';
                                      }

                                      if ($avltc == 1) {
                                        if ($tbc == 0) {
                                          echo '<a href="#dpn'.$n.'" data-toggle="collapse" class="btn btn-warning btn-flat btn-block btn-sm"><i class="fa fa-pencil"></i> Isi Data Penunjang</a>';
                                        }elseif($tco['kait'] == 1){
                                          echo '<a href="#dpn'.$n.'" data-toggle="collapse" class="btn btn-success btn-flat btn-block btn-sm"><i class="fa fa-check-circle"></i> Data Sudah Terkait</a>';
                                        }else{
                                          echo '<a href="#dpn'.$n.'" data-toggle="collapse" class="btn btn-danger btn-flat btn-block btn-sm"><i class="fa fa-file-archive-o"></i> Kaitkan Data Penunjang</a>';
                                        }
                                      }elseif ($avldn == 1) {
                                        if ($tbdn == 0) {
                                          echo '<a href="#dpn'.$n.'" data-toggle="collapse" class="btn btn-warning btn-flat btn-block btn-sm"><i class="fa fa-pencil"></i> Isi Data Penunjang</a>';
                                        }elseif($tdn['kait'] == 1){
                                          echo '<a href="#dpn'.$n.'" data-toggle="collapse" class="btn btn-success btn-flat btn-block btn-sm"><i class="fa fa-check-circle"></i> Data Sudah Terkait</a>';
                                        }else{
                                          echo '<a href="#dpn'.$n.'" data-toggle="collapse" class="btn btn-danger btn-flat btn-block btn-sm"><i class="fa fa-file-archive-o"></i> Kaitkan Data Penunjang</a>';
                                        }
                                      }elseif ($avlps == 1) {
                                        if ($tpsn == 0) {
                                          echo '<a href="#dpn'.$n.'" data-toggle="collapse" class="btn btn-warning btn-flat btn-block btn-sm"><i class="fa fa-pencil"></i> Isi Data Penunjang</a>';
                                        }elseif($tps['kait'] == 1){
                                          echo '<a href="#dpn'.$n.'" data-toggle="collapse" class="btn btn-success btn-flat btn-block btn-sm"><i class="fa fa-check-circle"></i> Data Sudah Terkait</a>';
                                        }else{
                                          echo '<a href="#dpn'.$n.'" data-toggle="collapse" class="btn btn-danger btn-flat btn-block btn-sm"><i class="fa fa-file-archive-o"></i> Kaitkan Data Penunjang</a>';
                                        }
                                      }elseif ($avlag == 1) {
                                        if ($tagn == 0) {
                                          echo '<a href="#dpn'.$n.'" data-toggle="collapse" class="btn btn-warning btn-flat btn-block btn-sm"><i class="fa fa-pencil"></i> Isi Data Penunjang</a>';
                                        }elseif($tag['kait'] == 1){
                                          echo '<a href="#dpn'.$n.'" data-toggle="collapse" class="btn btn-success btn-flat btn-block btn-sm"><i class="fa fa-check-circle"></i> Data Sudah Terkait</a>';
                                        }else{
                                          echo '<a href="#dpn'.$n.'" data-toggle="collapse" class="btn btn-danger btn-flat btn-block btn-sm"><i class="fa fa-file-archive-o"></i> Kaitkan Data Penunjang</a>';
                                        }
                                      }
                                    }else{
                                      if ($tbc == 0 || $tbdn == 0 || $tpsn == 0 || $tagn == 0) {
                                        echo '<a href="#dpn'.$n.'" data-toggle="collapse" class="btn btn-warning btn-flat btn-block btn-sm"><i class="fa fa-pencil"></i> Isi Data Penunjang</a>';
                                      }elseif($tco['kait'] == 1 && $tdn['kait'] == 1 && $tps['kait'] == 1 && $tag['kait'] == 1){
                                        echo '<a href="#dpn'.$n.'" data-toggle="collapse" class="btn btn-success btn-flat btn-block btn-sm"><i class="fa fa-check-circle"></i> Data Sudah Terkait</a>';
                                      }else{
                                        echo '<a href="#dpn'.$n.'" data-toggle="collapse" class="btn btn-danger btn-flat btn-block btn-sm"><i class="fa fa-file-archive-o"></i> Kaitkan Data Penunjang</a>';
                                      }
                                    }
                                    
                                    echo '<a href="#cnp'.$n.'" data-toggle="collapse" class="btn btn-info btn-flat btn-block btn-sm"><i class="fa fa-flask"></i> Tambahkan Rancangan Baru</a>';

                                    echo '<div id="dpn'.$n.'" class="collapse"><br>
                                    <table class="table">';
                                    if ($avltc == 1) {
                                      if ($tco['kait'] == 0) {
                                        if ($tbc == 0) {
                                          echo '<a href="'.base_url('pages/view_target_corporate/'.$tco['kode_target']).'" class="btn btn-primary btn-flat btn-block btn-sm"><i class="fa fa-pencil"></i> Isi Data Target Corporate Dulu</a>';
                                        }else{
                                          echo '<a href="#tc'.$n.'" data-toggle="modal" class="btn btn-danger btn-flat btn-block btn-sm"><i class="fa fa-chain"></i> Kaitkan Data Target Corporate</a>';
                                        }
                                      }else{
                                        echo '<tr><th width="1%"><small><i class="fa fa-check-circle text-green"></i></small></th><td class="text-left"><small>Sudah Terkait Data '.$tco['nama_target'].' Semester '.$tco['semester'].'</small></td></tr>';
                                      }
                                    }else{
                                      echo '<tr><td><label class="label label-danger">Tidak Ada Target Corporate</label></td></tr>';
                                    }
                                    
                                    //denda
                                    if ($avldn == 1) {
                                      if ($tdn['kait'] == 0) {
                                        if ($tbdn == 0) {
                                          echo '<a href="'.base_url('pages/view_denda/'.$tdn['kode_denda']).'" class="btn btn-primary btn-flat btn-block btn-sm"><i class="fa fa-pencil"></i> Isi Data Denda Dulu</a>';
                                        }else{
                                          echo '<a href="#dnd'.$n.'" data-toggle="modal" class="btn btn-danger btn-flat btn-block btn-sm"><i class="fa fa-chain"></i> Kaitkan Data Denda</a>';
                                        }
                                      }else{
                                        echo '<tr><th width="1%"><small><i class="fa fa-check-circle text-green"></i></small></th><td class="text-left"><small>Sudah Terkait Data '.$tdn['nama_denda'].' Semester '.$tdn['semester'].'</small></td></tr>';
                                      }
                                    }else{
                                      echo '<tr><td><label class="label label-danger">Tidak Ada Data Denda</label></td></tr>';
                                    }
                                    
                                    //presensi
                                    if ($avlps == 1) {
                                      if ($tps['kait'] == 0) {
                                        if ($tpsn == 0) {
                                          echo '<a href="'.base_url('pages/view_presensi/'.$tps['kode_presensi']).'" class="btn btn-primary btn-flat btn-block btn-sm"><i class="fa fa-pencil"></i> Isi Data Presensi Dulu</a>';
                                        }else{
                                          echo '<a href="#ps'.$n.'" data-toggle="modal" class="btn btn-danger btn-flat btn-block btn-sm"><i class="fa fa-chain"></i> Kaitkan Data Presensi</a>';
                                        }
                                      }else{
                                        echo '<tr><th width="1%"><small><i class="fa fa-check-circle text-green"></i></small></th><td class="text-left"><small>Sudah Terkait Data '.$tps['nama_presensi'].' Semester '.$tps['semester'].'</small></td></tr>';
                                      }
                                    }else{
                                      echo '<tr><td><label class="label label-danger">Tidak Ada Data Presensi</label></td></tr>';
                                    }
                                    
                                    //anggaran
                                    if ($avlag == 1) {
                                      if ($tag['kait'] == 0) {
                                        if ($tagn == 0) {
                                          echo '<a href="'.base_url('pages/view_anggaran/'.$tag['kode_anggaran']).'" class="btn btn-primary btn-flat btn-block btn-sm"><i class="fa fa-pencil"></i> Isi Data Anggaran Dulu</a>';
                                        }else{
                                          echo '<a href="#ag'.$n.'" data-toggle="modal" class="btn btn-danger btn-flat btn-block btn-sm"><i class="fa fa-chain"></i> Kaitkan Data Anggaran</a>';
                                        }
                                      }else{
                                        echo '<tr><th width="1%"><small><i class="fa fa-check-circle text-green"></i></small></th><td class="text-left"><small>Sudah Terkait Data '.$tag['nama_anggaran'].' Semester '.$tag['semester'].'</small></td></tr>';
                                      }
                                    }else{
                                      echo '<tr><td><label class="label label-danger">Tidak Ada Data Anggaran</label></td></tr>';
                                    }
                                    
                                    echo '</table>
                                    </div>';
                                    if ($avltc == 1) {
                                      echo '<div id="tc'.$n.'" class="modal fade" role="dialog">
                                        <div class="modal-dialog modal-md">
                                          <div class="modal-content">
                                            <div class="modal-header">
                                              <button type="button" class="close" data-dismiss="modal">&times;</button>
                                              <h4 class="modal-title text-center">Konfirmasi Kaitkan Target Corporate</h4>
                                            </div>
                                            <div class="modal-body text-center">
                                              <p>Apakah anda yakin akan mengaitkan data Target Corporate dengan nama <b>'.$tco['nama_target'].'</b> tersebut?</p>
                                            </div>
                                            <div class="modal-footer">
                                            '.form_open('target/chain_target').'
                                              <input type="hidden" name="kode" value="'.$kd.'">
                                              <button type="submit" class="btn btn-primary" id="savagdtc'.$n.'"><i class="fa fa-chain"></i> Kaitkan</button>
                                              <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
                                            '.form_close().'
                                            </div>
                                          </div>
                                        </div>
                                      </div>';
                                    }

                                    if ($avldn == 1) {
                                      echo '<div id="dnd'.$n.'" class="modal fade" role="dialog">
                                        <div class="modal-dialog modal-md">
                                          <div class="modal-content">
                                            <div class="modal-header">
                                              <button type="button" class="close" data-dismiss="modal">&times;</button>
                                              <h4 class="modal-title text-center">Konfirmasi Kaitkan Denda</h4>
                                            </div>
                                            <div class="modal-body text-center">
                                            '.form_open('denda/chain_denda').'
                                              <p>Pilih Indikator denda pada Agenda Penilaian <b>'.$l->nama_agenda.'</b></p>';
                                              echo '<div class="row">
                                              <div class="col-md-12">';
                                              $tbagd=$l->tabel_agenda;
                                              $ts=$this->db->query("SELECT kode_indikator FROM $tbagd WHERE kaitan = '1'")->result();
                                              foreach ($ts as $t) {
                                                $ind[$t->kode_indikator]=$t->kode_indikator;
                                              }
                                              if (isset($ind)) {
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
                                                $ex1 = array('class'=>'form-control select2','multiple'=>'multiple','data-placeholder'=>'Pilih Indikator Denda','required'=>'required','style'=>'width:100%;');
                                                echo form_dropdown('indi_denda[]',$op12,$sel1,$ex1);
                                              }else{
                                                echo '<div class="callout callout-danger"><label><i class="fa fa-info-circle"></i> Data Kosong</label><br>Tidak Ada Data yang ditampilkan</div>';
                                              }
                                              
                                            echo '</div>
                                            </div>
                                            </div>
                                            <div class="modal-footer">';
                                            if (isset($ind)) {
                                              echo '<input type="hidden" name="kode" value="'.$kd.'">
                                              <button type="submit" class="btn btn-primary" id="savagddnd'.$n.'"><i class="fa fa-chain"></i> Kaitkan</button>';
                                            }
                                              echo '<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
                                            '.form_close().'
                                            </div>
                                          </div>
                                        </div>
                                      </div>';
                                    }
                                    
                                    if ($avlps == 1) {
                                      echo '<div id="ps'.$n.'" class="modal fade" role="dialog">
                                        <div class="modal-dialog modal-md">
                                          <div class="modal-content">
                                            <div class="modal-header">
                                              <button type="button" class="close" data-dismiss="modal">&times;</button>
                                              <h4 class="modal-title text-center">Konfirmasi Kaitkan Presensi</h4>
                                            </div>
                                            <div class="modal-body text-center">
                                              <p>Apakah anda yakin akan mengaitkan data Presensi dengan nama <b>'.$tps['nama_presensi'].'</b> tersebut?</p>
                                            </div>
                                            <div class="modal-footer">
                                            '.form_open('presensi/chain_presensi').'
                                              <input type="hidden" name="kode" value="'.$tps['kode_presensi'].'">
                                              <button type="submit" class="btn btn-primary" id="savagdps'.$n.'"><i class="fa fa-chain"></i> Kaitkan</button>
                                              <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
                                            '.form_close().'
                                            </div>
                                          </div>
                                        </div>
                                      </div>';
                                    }
                                    
                                    if ($avlag == 1) {
                                      echo '<div id="ag'.$n.'" class="modal fade" role="dialog">
                                        <div class="modal-dialog modal-md">
                                          <div class="modal-content">
                                            <div class="modal-header">
                                              <button type="button" class="close" data-dismiss="modal">&times;</button>
                                              <h4 class="modal-title text-center">Konfirmasi Kaitkan Anggaran</h4>
                                            </div>
                                            <div class="modal-body text-center">
                                              <p>Apakah anda yakin akan mengaitkan data Perbandingan Anggaran dengan nama <b>'.$tag['nama_anggaran'].'</b> tersebut?</p>
                                            </div>
                                            <div class="modal-footer">
                                            '.form_open('anggaran/chain_anggaran').'
                                              <input type="hidden" name="kode" value="'.$tag['kode_anggaran'].'">
                                              <button type="submit" class="btn btn-primary" id="savagdag'.$n.'"><i class="fa fa-chain"></i> Kaitkan</button>
                                              <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
                                            '.form_close().'
                                            </div>
                                          </div>
                                        </div>
                                      </div>';
                                    }
                                    
                                    echo '<div id="cnp'.$n.'" class="collapse"><br>
                                    '.form_open('agenda/add_new_concept_task');'
                                    <div class="input-group  input-sm">';
                                    $agdx1=$this->db->query("SELECT * FROM concept_kpi WHERE status = 'aktif' AND nama_tabel != 'NULL'")->result();
                                    foreach ($agdx1 as $ax1) {
                                      $opx12[$ax1->kode_c_kpi]=$ax1->nama;
                                    }
                                    $selx1 = array();
                                    $exx1 = array('class'=>'form-control select2','placeholder'=>'Indikator','required'=>'required','style'=>'width:100%;');
                                    echo form_dropdown('concept',$opx12,$selx1,$exx1);
                                    echo '<span class="input-group-btn">

                                    <button type="submit" id="savagdk'.$n.'" class="btn btn-primary btn-flat"><i class="fa fa-plus"></i> Tambahkan</button>
                                    </span>
                                    <input type="hidden" name="kode_agenda" value="'.$l->kode_agenda.'"> 
                                    '.form_close().'
                                    </div>';
                                  }


                                  echo '</td>
                                  <td width="25%" class="text-center">';
                                  if ($l->keterangan == "not_entry") {
                                    echo '<label class="label label-danger">Rancangan Output Belum Dimasukkan</label>';
                                  }else{
                                    $nmtb1=$l->tabel_agenda;
                                    $dto=$this->db->query("SELECT * FROM $nmtb1 WHERE nilai_out != 0")->num_rows();
                                    $dtt1=$this->db->query("SELECT * FROM $nmtb1")->num_rows();
                                    $tdto=$dto;
                                    $jm1= ($tdto/($dtt1))*100;
                                    $jm=number_format($jm1,2);
                                    if ($jm == 100) {
                                      $dtt=array('keterangan'=>"done");
                                      $this->db->where('kode_agenda',$l->kode_agenda);
                                      $this->db->update('agenda',$dtt);
                                      $this->db->where('kode_agenda',$l->kode_agenda);
                                      $this->db->update('log_agenda',$dtt);
                                    }
                                    /*
                                    else{
                                      $dtt=array('keterangan'=>"progress");
                                      $this->db->where('kode_agenda',$l->kode_agenda);
                                      $this->db->update('agenda',$dtt);
                                      $this->db->where('kode_agenda',$l->kode_agenda);
                                      $this->db->update('log_agenda',$dtt);
                                    }*/
                                    echo '<div class="progress active">
                                        <div class="progress-bar progress-bar-primary progress-bar-striped" role="progressbar" aria-valuenow="'.$jm.'" aria-valuemin="0" aria-valuemax="100" style="width: '.$jm.'%">
                                          <b class="text-black">'.$jm.'%</b>
                                        </div>
                                      </div>';
                                  }
                                  echo '</td>
                                  <td class="text-center" width="10%"><label class="label label-success" data-toggle="tooltip" title="Dimulai Tanggal"><i class="fa fa-check"></i> '.$this->formatter->getDateTimeMonthFormatUser($l->tgl_mulai).' WIB</label><br><label class="label label-danger" data-toggle="tooltip" title="Berakhir Tanggal"><i class="fa fa-times"></i> '.$this->formatter->getDateTimeMonthFormatUser($l->tgl_selesai).' WIB</label></td>
                                  <td>';
                                  if ($l->keterangan == "not_entry") {
                                    echo '<label class="label label-danger">Rancangan Output Belum Dimasukkan</label>';
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
                                  echo '<br>';
                                  if ($l->validasi == 0) {
                                    echo '<label class="label label-danger"><i class="fa fa-times-circle"></i> Nilai Belum Tervalidasi</label>';
                                  }else{
                                    echo '<label class="label label-success"><i class="fa fa-check-circle"></i> Nilai Tervalidasi</label>';
                                  }
                                  echo '</td>
                                  <td class="text-center" width="10%">';

                                  if ($l->status == "aktif") {
                                    echo form_open('agenda/status_agenda').'
                                    <input type="hidden" name="id" value="'.$l->id_agenda.'">
                                    <input type="hidden" name="act" value="nonaktif">
                                    <button '.$access['b_stt'].' class="stat scc"><i class="fa fa-toggle-on"></i></button>'
                                    .form_close();
                                  }else{
                                    echo form_open('agenda/status_agenda').'
                                    <input type="hidden" name="id" value="'.$l->id_agenda.'">
                                    <input type="hidden" name="act" value="aktif">
                                    <button '.$access['b_stt'].' class="stat err"><i class="fa fa-toggle-off"></i></button>'
                                    .form_close();
                                  } 
                                  echo '
                                  <label class="label label-warning" data-toggle="tooltip" title="Dibuat Tanggal"><i class="fa fa-pencil"></i> '.$this->formatter->getDateTimeMonthFormatUser($l->create_date).' WIB</label><br><label class="label label-primary" data-toggle="tooltip" title="Diupdate Tanggal"><i class="fa fa-edit"></i> '.$this->formatter->getDateTimeMonthFormatUser($l->update_date).' WIB</label></td>
                                  <td class="text-center" width="10%">';
                                    if (in_array($access['l_ac']['edt'], $access['access'])) {
                                      echo '<a href="#edt'.$n.'" data-toggle="modal" class="btn btn-info" onclick="date_range('.$n.')"><i class="fa fa-edit" data-toggle="tooltip" title="Edit Data"></i></a> ';
                                    }
                                    if (in_array($access['l_ac']['del'], $access['access'])) {
                                      echo '<a href="#del'.$n.'" data-toggle="modal" class="btn btn-danger"><i class="fa fa-trash" data-toggle="tooltip" title="Hapus Data"></i></a> ';
                                    }
                                    if (in_array($access['l_ac']['apr'], $access['access'])) {
                                      if ($l->validasi == 0) {
                                        echo '<a href="#apr'.$n.'" data-toggle="modal" class="btn btn-success"><i class="fa fa-eye" data-toggle="tooltip" title="Validasi Nilai"></i></a> ';
                                      }else{
                                        echo '<a href="#unapr'.$n.'" data-toggle="modal" class="btn btn-warning"><i class="fa fa-eye-slash" data-toggle="tooltip" title="Batalkan Validasi Nilai"></i></a> ';
                                      }
                                    }
                                    if (in_array($access['l_ac']['stt'], $access['access'])) {
                                      if ($l->status_open == 0) {
                                        echo '<a href="#open'.$n.'" data-toggle="modal" class="btn btn-primary"><i class="fa fa-unlock" data-toggle="tooltip" title="Buka Agenda"></i></a> ';
                                      }else{
                                        echo '<a href="#close'.$n.'" data-toggle="modal" class="btn btn-warning"><i class="fa fa-lock" data-toggle="tooltip" title="Tutup Agenda"></i></a> ';
                                      }
                                    }
                                    echo $access['n_all'];
                                  echo '</td>
                                </tr>';
                                if (in_array($access['l_ac']['edt'], $access['access'])) {
                                  echo ' <div id="edt'.$n.'" class="modal fade" role="dialog">
                                    <div class="modal-dialog modal-md">
                                      <div class="modal-content">
                                        <div class="modal-header">
                                          <button type="button" class="close" data-dismiss="modal">&times;</button>
                                          <h4 class="modal-title text-center">Edit Data '.$l->nama_agenda.'</h4>
                                        </div>
                                        <div class="modal-body">
                                        <div class="row">
                                          <div class="col-md-12">
                                          <p class="text-danger">Semua data harus diisi!</p>
                                          '.form_open('agenda/edt_agenda').'
                                            <input type="hidden" name="id" value="'.$l->id_agenda.'">
                                            <div class="form-group">
                                              <label>Nama Agenda</label>
                                              <input type="text" placeholder="Masukkan Nama Agenda" name="nama" class="form-control" value="'.$l->nama_agenda.'" required="required">
                                            </div>
                                            <div class="form-group">
                                              <label>Tanggal Mulai dan Selesai:</label>

                                              <div class="input-group">
                                                <div class="input-group-addon">
                                                  <i class="fa fa-clock-o"></i>
                                                </div>
                                                <input type="text" class="form-control pull-right" id="drange'.$n.'" name="date" data-start="'. date('d/m/Y H:i:s',strtotime($l->tgl_mulai)).'" data-end="'. date('d/m/Y H:i:s',strtotime($l->tgl_selesai)).'" required="required">
                                              </div>
                                            </div>
                                            <div class="form-group">
                                              <div class="col-md-6">
                                                <label>Pilih Tahun</label>';
                                                $years =  range(date("Y",strtotime("+3 Year")), 2015);
                                                for ($i=0; $i < count($years) ; $i++) { 
                                                  $y[$years[$i]]=$years[$i];
                                                }
                                                $sel = array($l->tahun);
                                                $ex = array('class'=>'form-control','placeholder'=>'Tahun','required'=>'required');
                                                echo form_dropdown('tahun',$y,$sel,$ex);
                                              echo '</div>
                                              <div class="col-md-6">
                                                <label>Pilih Semester</label>';
                                                $op1=array('1'=>'Semester 1','2'=>'Semester 2');
                                                $sel1 = array($l->semester);
                                                $ex1 = array('class'=>'form-control','placeholder'=>'Pelaporan','required'=>'required');
                                                echo form_dropdown('semester',$op1,$sel1,$ex1);
                                              echo '</div>
                                            </div>
                                          </div>
                                          </div>
                                        </div>
                                        <div class="modal-footer">
                                          <input type="hidden" name="id" value="'.$l->id_agenda.'">
                                          <input type="hidden" name="kode" value="'.$l->kode_agenda.'">
                                          <button type="submit" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
                                          <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
                                          '.form_close().'
                                          
                                        </div>
                                        </div>
                                      </div>

                                    </div>
                                  </div>';
                                }
                                if (in_array($access['l_ac']['stt'], $access['access'])) {
                                  if ($l->status_open == 0) {
                                    echo '<div id="open'.$n.'" class="modal fade" role="dialog">
                                      <div class="modal-dialog modal-sm modal-primary">
                                        <div class="modal-content">
                                          <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title text-center">Konfirmasi Buka Agenda</h4>
                                          </div>
                                          <div class="modal-body text-center">
                                            <p>Apakah anda yakin akan membuka agenda dengan nama <b>'.$l->nama_agenda.'</b> dan melakukan edit data pada agenda tersebut?</p>
                                          </div>
                                          <div class="modal-footer">
                                          '.form_open('agenda/open_agenda').'
                                            <input type="hidden" name="id" value="'.$l->id_agenda.'">
                                            <button type="submit" class="btn btn-primary"><i class="fa fa-unlock"></i> Buka</button>
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
                                          '.form_close().'
                                          </div>
                                        </div>
                                      </div>
                                    </div>';
                                  }else{
                                    echo '<div id="close'.$n.'" class="modal fade" role="dialog">
                                    <div class="modal-dialog modal-sm modal-warning">
                                      <div class="modal-content">
                                        <div class="modal-header">
                                          <button type="button" class="close" data-dismiss="modal">&times;</button>
                                          <h4 class="modal-title text-center">Konfirmasi Tutup Agenda</h4>
                                        </div>
                                        <div class="modal-body text-center">
                                          <p>Apakah anda yakin akan menutup agenda dengan nama <b>'.$l->nama_agenda.'</b> ?</p>
                                        </div>
                                        <div class="modal-footer">
                                        '.form_open('agenda/close_agenda').'
                                          <input type="hidden" name="id" value="'.$l->id_agenda.'">
                                          <button type="submit" class="btn btn-warning"><i class="fa fa-lock"></i> Tutup</button>
                                          <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
                                        '.form_close().'
                                        </div>
                                      </div>
                                    </div>
                                  </div>';
                                  }
                                  
                                }
                                if (in_array($access['l_ac']['del'], $access['access'])) {
                                  echo '<div id="del'.$n.'" class="modal fade" role="dialog">
                                    <div class="modal-dialog modal-sm modal-danger">
                                      <div class="modal-content">
                                        <div class="modal-header">
                                          <button type="button" class="close" data-dismiss="modal">&times;</button>
                                          <h4 class="modal-title text-center">Konfirmasi Hapus</h4>
                                        </div>
                                        <div class="modal-body text-center">
                                          <p>Apakah anda yakin akan menghapus data agenda dengan nama <b>'.$l->nama_agenda.'</b> dan seluruh data penilaian pada agenda tersebut?</p>
                                        </div>
                                        <div class="modal-footer">
                                        '.form_open('agenda/del_agenda').'
                                          <input type="hidden" name="id" value="'.$l->id_agenda.'">
                                          <button type="submit" class="btn btn-primary"><i class="fa fa-trash"></i> Hapus</button>
                                          <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
                                        '.form_close().'
                                        </div>
                                      </div>
                                    </div>
                                  </div>';
                                }
                                if (in_array($access['l_ac']['apr'], $access['access'])) {
                                  echo '<div id="apr'.$n.'" class="modal fade" role="dialog">
                                    <div class="modal-dialog modal-sm modal-success">
                                      <div class="modal-content">
                                        <div class="modal-header">
                                          <button type="button" class="close" data-dismiss="modal">&times;</button>
                                          <h4 class="modal-title text-center">Validasi Nilai</h4>
                                        </div>
                                        <div class="modal-body text-center">
                                          <p>Apakah Anda yakin akan memvalidasi data nilai agenda dengan nama <b>'.$l->nama_agenda.'</b> dan memunculkan <b class="text-red">Nilai Rapor '.$l->nama_agenda.'</b> pada Front Office</p>
                                        </div>
                                        <div class="modal-footer">
                                        '.form_open('agenda/validate_value').'
                                          <input type="hidden" name="id" value="'.$l->kode_agenda.'">
                                          <input type="hidden" name="agd" value="aktif">
                                          <input type="hidden" name="val" value="1">
                                          <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Validasi</button>
                                          <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
                                        '.form_close().'
                                        </div>
                                      </div>
                                    </div>
                                  </div>';
                                  echo '<div id="unapr'.$n.'" class="modal fade" role="dialog">
                                    <div class="modal-dialog modal-sm modal-warning">
                                      <div class="modal-content">
                                        <div class="modal-header">
                                          <button type="button" class="close" data-dismiss="modal">&times;</button>
                                          <h4 class="modal-title text-center">Batal Validasi Nilai</h4>
                                        </div>
                                        <div class="modal-body text-center">
                                          <p>Apakah Anda akan membatalkan validasi data nilai agenda dengan nama <b>'.$l->nama_agenda.'</b> dan menyembunyikan<b class="text-red">Nilai Rapor '.$l->nama_agenda.'</b> pada Front Office</p>
                                        </div>
                                        <div class="modal-footer">
                                        '.form_open('agenda/unvalidate_value').'
                                          <input type="hidden" name="id" value="'.$l->kode_agenda.'">
                                          <input type="hidden" name="agd" value="aktif">
                                          <input type="hidden" name="val" value="0">
                                          <button type="submit" class="btn btn-danger"><i class="fa fa-times"></i> Batal Validasi</button>
                                          <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
                                        '.form_close().'
                                        </div>
                                      </div>
                                    </div>
                                  </div>';
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
      <div class="row">
        <div class="col-md-12">
          <div class="box box-danger">
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-calendar"></i> Daftar Agenda Sikap (360°)</h3>
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
                      <div class="pull-left">
                        <?php 
                        if (in_array($access['l_ac']['add'], $access['access'])) {
                          echo '<button class="btn btn-success btn-flat" type="button" data-toggle="collapse" data-target="#add_attd"><i class="fa fa-calendar"></i> Buat Agenda Baru</button>';
                        }?>
                      </div>
                      <div class="pull-right" style="font-size: 8pt;">
                        <i class="fa fa-toggle-on stat scc"></i> Aktif<br>
                        <i class="fa fa-toggle-off stat err"></i> Tidak Aktif
                      </div>
                    </div>
                  </div>
                  <?php if (in_array($access['l_ac']['add'], $access['access'])) {?>
                  <div class="collapse" id="add_attd">
                    <br>
                    <div class="col-md-2"></div>
                    <div class="col-md-8">
                      <?php echo form_open('agenda/add_attd_agenda');?>
                      <p class="text-danger">Semua data harus diisi!
                        <div class="form-group">
                          <label>Nama Agenda Sikap (360°)</label>
                          <input type="text" placeholder="Masukkan Nama Agenda" name="nama" class="form-control" required="required">
                        </div>
                        <div class="form-group">
                          <label>Tanggal Mulai dan Selesai:</label>

                          <div class="input-group">
                            <div class="input-group-addon">
                              <i class="fa fa-clock-o"></i>
                            </div>
                            <input type="text" class="form-control pull-right" id="attd_date" name="date" required="required">
                          </div>
                        </div>
                        <div class="form-group">
                          <div class="col-md-6">
                            <label>Pilih Tahun</label>
                            <?php
                            $yearsx =  range(date("Y",strtotime("+3 Year")), 2015);
                            for ($ix=0; $ix < count($yearsx) ; $ix++) { 
                              $yx[$yearsx[$ix]]=$yearsx[$ix];
                            }
                            $selx = array(date("Y"));
                            $exx = array('class'=>'form-control select2','style'=>'width:100%;','placeholder'=>'Tahun','required'=>'required');
                            echo form_dropdown('tahun',$yx,$selx,$exx);
                            ?>
                          </div>
                          <div class="col-md-6">
                            <label>Pilih Semester</label>
                            <?php
                            $opx1=array('1'=>'Semester 1','2'=>'Semester 2');
                            $selx1 = array('1');
                            $exx1 = array('class'=>'form-control select2','style'=>'width:100%;','placeholder'=>'Pelaporan','required'=>'required');
                            echo form_dropdown('semester',$opx1,$selx1,$exx1);
                            ?>
                          </div>
                        </div>
                        <br>
                        <br>
                        <br>
                        <br>
                        <div class="form-group">
                          <button type="submit" class="btn btn-success" id="save2"><i class="fa fa-floppy-o"></i> Simpan</button>
                        </div>
                        <?php echo form_close();?>
                    </div>
                  </div>
                  <?php }?>
                </div>
              </div><br>
              <div class="row">
                <div class="col-md-12">
                  <?php 
                  if (count($attd) == 0) {
                    echo '<div class="callout callout-danger"><label><i class="fa fa-info-circle"></i> Data Kosong</label><br>Tidak ada data yang ditampilkan</div>';
                  }else{
                  ?>
                  <table id="example2" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>No.</th>
                        <th>Nama Agenda</th>
                        <th>Kaitkan Data</th>
                        <th>Progress</th>
                        <th>Tanggal Agenda</th>
                        <th>Keterangan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php 
                        $nn=1;
                        foreach ($attd as $att) {
                          echo '<tr>
                            <td width="3%">'.$nn.'.</td>
                            <td>'.$att->nama_agenda.'<br>
                            <label class="label label-primary">'.$att->tahun.'</label>';
                              if ($att->semester == 1) {
                                echo '<label class="label label-info">Semester '.$att->semester.'</label>';
                              }else{
                                echo '<label class="label label-success">Semester '.$att->semester.'</label>';
                              }
                            echo '</td>
                            <td>';
                            if ($att->keterangan == 'not_entry') {
                              echo '<a href="#rca'.$nn.'" data-toggle="collapse" class="btn btn-info btn-flat btn-block btn-sm"><i class="fa fa-gear"></i> Kaitkan Rancangan Sikap</a>
                              <div id="rca'.$nn.'" class="collapse"><br>';
                              echo form_open('agenda/create_attd_task');
                              echo '<div class="input-group">';
                              $attx1=$this->db->query("SELECT * FROM concept_sikap WHERE status = 'aktif' AND nama_tabel != 'NULL'")->result();
                              foreach ($attx1 as $tx1) {
                                $opax12[$tx1->kode_c_sikap]=$tx1->nama;
                              }
                              $selax1 = array(NULL);
                              $exax1 = array('class'=>'form-control select2','placeholder'=>'Indikator','required'=>'required','style'=>'width:100%;');
                              echo form_dropdown('attd_concept',$opax12,$selax1,$exax1);
                              echo '<span class="input-group-btn">
                              <button type="submit" id="savagdatt'.$nn.'" class="btn btn-success btn-flat"><i class="fa fa-chain"></i> Kaitkan</button>
                              </span>
                              <input type="hidden" name="kode" value="'.$att->kode_agenda.'"> 
                              '.form_close().'
                              </div>';
                            }else{
                              echo '<table class="table"><tr><td><i class="fa fa-check-circle text-green"></i></td><td>Sudah Terkait Rancangan Sikap</td></table>';
                            }
                            echo '</td>
                            <td width="30%">';
                            if ($att->keterangan == 'not_entry') {
                              echo '<label class="label label-danger">Rancangan Sikap Belum Dimasukkan</label>';
                            }else{
                              $tbe=$att->tabel_agenda;
                              $tt=$this->db->get($tbe)->num_rows();
                              $tt1=$this->db->query("SELECT nilai_akhir FROM $tbe WHERE nilai_akhir != '0'")->num_rows();
                              $jmx1= ($tt1/($tt))*100;
                              $jmx=number_format($jmx1,2);
                              if ($jmx == 100) {
                                $dttx=array('keterangan'=>"done");
                                $this->db->where('kode_agenda',$att->kode_agenda);
                                $this->db->update('attd_agenda',$dttx);
                                $this->db->where('kode_agenda',$att->kode_agenda);
                                $this->db->update('log_attd_agenda',$dttx);
                              }
                              echo '<div class="progress active">
                              <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="'.$jmx.'" aria-valuemin="0" aria-valuemax="100" style="width: '.$jmx.'%">
                              <b class="text-black">'.$jmx.'%</b>
                              </div>
                              </div>';
                            }
                            echo '</td>
                            <td class="text-center" width="10%"><label class="label label-success" data-toggle="tooltip" title="Dimulai Tanggal"><i class="fa fa-check"></i> '.$this->formatter->getDateTimeMonthFormatUser($att->tgl_mulai).' WIB</label><br><label class="label label-danger" data-toggle="tooltip" title="Berakhir Tanggal"><i class="fa fa-times"></i> '.$this->formatter->getDateTimeMonthFormatUser($att->tgl_selesai).' WIB</label></td>
                            <td>';
                            if ($att->keterangan == "not_entry") {
                              echo '<label class="label label-danger">Rancangan Sikap Belum Dimasukkan</label>';
                            }elseif ($att->keterangan == "progress") {
                              echo '<label class="label label-warning">Proses Entry Data</label>';
                            }else{
                              echo '<label class="label label-success">Semua Data Selesai Diisi</label>';
                            }
                            echo '<br>';
                            if (date("Y-m-d H:i:s",strtotime($att->tgl_selesai)) < date("Y-m-d H:i:s",strtotime($tgl))) {
                              echo '<label class="label label-danger">Waktu Agenda Sudah Habis, Agenda Ditutup</label>';
                            }elseif ((date("Y-m-d H:i:s",strtotime($att->tgl_mulai)) <= date("Y-m-d H:i:s",strtotime($tgl))) && (date("Y-m-d H:i:s",strtotime($att->tgl_selesai)) >= date("Y-m-d H:i:s",strtotime($tgl)))) {
                              echo '<label class="label label-info">Agenda Sedang Berlangsung</label>';
                            }
                            echo '<br>';
                            if ($att->validasi == 0) {
                              echo '<label class="label label-danger"><i class="fa fa-times-circle"></i> Nilai Belum Tervalidasi</label>';
                            }else{
                              echo '<label class="label label-success"><i class="fa fa-check-circle"></i> Nilai Tervalidasi</label>';
                            }
                            echo '</td>
                            <td class="text-center" width="10%">';
                            echo form_open('agenda/status_attd_agenda');
                            if ($att->status == "aktif") {
                              echo '<input type="hidden" name="id" value="'.$att->id_agenda.'">
                              <input type="hidden" name="act" value="nonaktif">
                              <button '.$access['b_stt'].' class="stat scc"><i class="fa fa-toggle-on"></i></button>';
                            }else{
                              echo '<input type="hidden" name="id" value="'.$att->id_agenda.'">
                              <input type="hidden" name="act" value="aktif">
                              <button '.$access['b_stt'].' class="stat err"><i class="fa fa-toggle-off"></i></button>';
                            }
                            echo form_close().'
                            <label class="label label-warning" data-toggle="tooltip" title="Dibuat Tanggal"><i class="fa fa-pencil"></i> '.$this->formatter->getDateTimeMonthFormatUser($att->create_date).' WIB</label><br><label class="label label-primary" data-toggle="tooltip" title="Diupdate Tanggal"><i class="fa fa-edit"></i> '.$this->formatter->getDateTimeMonthFormatUser($att->update_date).' WIB</label></td>
                            <td class="text-center">';
                              if (in_array($access['l_ac']['edt'], $access['access'])) {
                                echo '<a href="#edta'.$nn.'" data-toggle="modal" class="btn btn-info"><i class="fa fa-edit" data-toggle="tooltip" title="Edit Data"></i></a> ';
                              }
                              if (in_array($access['l_ac']['del'], $access['access'])) {
                                echo '<a href="#dela'.$nn.'" data-toggle="modal" class="btn btn-danger"><i class="fa fa-trash" data-toggle="tooltip" title="Hapus Data"></i></a> ';
                              }
                              if (in_array($access['l_ac']['apr'], $access['access'])) {
                                if ($att->validasi == 0) {
                                  echo '<a href="#attapr'.$nn.'" data-toggle="modal" class="btn btn-success"><i class="fa fa-eye" data-toggle="tooltip" title="Validasi Nilai"></i></a> ';
                                }else{
                                  echo '<a href="#attunapr'.$nn.'" data-toggle="modal" class="btn btn-warning"><i class="fa fa-eye-slash" data-toggle="tooltip" title="Batalkan Validasi Nilai"></i></a>';
                                }
                              }
                              if (in_array($access['l_ac']['stt'], $access['access'])) {
                                if ($att->status_open == 0) {
                                  echo '<a href="#open_att'.$n.'" data-toggle="modal" class="btn btn-primary"><i class="fa fa-unlock" data-toggle="tooltip" title="Buka Agenda"></i></a> ';
                                }else{
                                  echo '<a href="#close_att'.$n.'" data-toggle="modal" class="btn btn-warning"><i class="fa fa-lock" data-toggle="tooltip" title="Tutup Agenda"></i></a> ';
                                }
                              }
                              echo $access['n_all'];
                            echo '</td>
                          </tr>';
                          if (in_array($access['l_ac']['edt'], $access['access'])) {
                            echo '<div id="edta'.$nn.'" class="modal fade" role="dialog">
                                  <div class="modal-dialog modal-md">
                                    <div class="modal-content">
                                      <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title text-center">Edit Data '.$att->nama_agenda.'</h4>
                                      </div>
                                      <div class="modal-body">
                                      <div class="row">
                                        <div class="col-md-12">
                                        <p class="text-danger">Semua data harus diisi!</p>
                                        '.form_open('agenda/edt_attd_agenda').'
                                          <input type="hidden" name="id" value="'.$att->id_agenda.'">
                                          <div class="form-group">
                                            <label>Nama Agenda</label>
                                            <input type="text" placeholder="Masukkan Nama Agenda" name="nama" class="form-control" value="'.$att->nama_agenda.'" required="required">
                                          </div>
                                          <div class="form-group">
                                            <label>Tanggal Mulai dan Selesai:</label>

                                            <div class="input-group">
                                              <div class="input-group-addon">
                                                <i class="fa fa-clock-o"></i>
                                              </div>
                                              <input type="text" class="form-control pull-right" id="reserv_a_'.$nn.'" name="date" value="'.$att->tgl_mulai.'" required="required">
                                            </div>
                                          </div>
                                          <div class="form-group">
                                            <div class="col-md-6">
                                              <label>Pilih Tahun</label>';
                                              $yearsa =  range(date("Y",strtotime("+3 Year")), 2015);
                                              for ($ia=0; $ia < count($yearsa) ; $ia++) { 
                                                $ya[$yearsa[$ia]]=$yearsa[$ia];
                                              }
                                              $sela = array($att->tahun);
                                              $exa = array('class'=>'form-control','placeholder'=>'Tahun','required'=>'required');
                                              echo form_dropdown('tahun',$ya,$sela,$exa);
                                            echo '</div>
                                            <div class="col-md-6">
                                              <label>Pilih Semester</label>';
                                              $op1a=array('1'=>'Semester 1','2'=>'Semester 2');
                                              $sel1a = array($att->semester);
                                              $ex1a = array('class'=>'form-control','placeholder'=>'Pelaporan','required'=>'required');
                                              echo form_dropdown('semester',$op1a,$sel1a,$ex1a);
                                            echo '</div>
                                          </div>
                                        </div>
                                        </div>
                                      </div>
                                      <div class="modal-footer">
                                        <input type="hidden" name="id" value="'.$att->id_agenda.'">
                                        <input type="hidden" name="kode" value="'.$att->kode_agenda.'">
                                        <button type="submit" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
                                        '.form_close().'
                                        
                                      </div>
                                      </div>
                                    </div>

                                  </div>
                                </div>';
                          }
                          if (in_array($access['l_ac']['stt'], $access['access'])) {
                                  if ($att->status_open == 0) {
                                    echo '<div id="open_att'.$n.'" class="modal fade" role="dialog">
                                      <div class="modal-dialog modal-sm modal-primary">
                                        <div class="modal-content">
                                          <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title text-center">Konfirmasi Buka Agenda</h4>
                                          </div>
                                          <div class="modal-body text-center">
                                            <p>Apakah anda yakin akan membuka agenda dengan nama <b>'.$att->nama_agenda.'</b> dan melakukan edit data pada agenda tersebut?</p>
                                          </div>
                                          <div class="modal-footer">
                                          '.form_open('agenda/open_att_agenda').'
                                            <input type="hidden" name="id" value="'.$att->id_agenda.'">
                                            <button type="submit" class="btn btn-primary"><i class="fa fa-unlock"></i> Buka</button>
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
                                          '.form_close().'
                                          </div>
                                        </div>
                                      </div>
                                    </div>';
                                  }else{
                                    echo '<div id="close_att'.$n.'" class="modal fade" role="dialog">
                                    <div class="modal-dialog modal-sm modal-warning">
                                      <div class="modal-content">
                                        <div class="modal-header">
                                          <button type="button" class="close" data-dismiss="modal">&times;</button>
                                          <h4 class="modal-title text-center">Konfirmasi Tutup Agenda</h4>
                                        </div>
                                        <div class="modal-body text-center">
                                          <p>Apakah anda yakin akan menutup agenda dengan nama <b>'.$att->nama_agenda.'</b> ?</p>
                                        </div>
                                        <div class="modal-footer">
                                        '.form_open('agenda/close_att_agenda').'
                                          <input type="hidden" name="id" value="'.$att->id_agenda.'">
                                          <button type="submit" class="btn btn-warning"><i class="fa fa-lock"></i> Tutup</button>
                                          <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
                                        '.form_close().'
                                        </div>
                                      </div>
                                    </div>
                                  </div>';
                                  }
                                  
                                }
                          if (in_array($access['l_ac']['del'], $access['access'])) {
                            echo '<div id="dela'.$nn.'" class="modal fade" role="dialog">
                                  <div class="modal-dialog modal-sm modal-danger">
                                    <div class="modal-content">
                                      <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title text-center">Konfirmasi Hapus</h4>
                                      </div>
                                      <div class="modal-body text-center">
                                        <p>Apakah anda yakin akan menghapus data agenda dengan nama <b>'.$att->nama_agenda.'</b> dan seluruh data penilaian pada agenda tersebut?</p>
                                      </div>
                                      <div class="modal-footer">
                                      '.form_open('agenda/del_attd_agenda').'
                                        <input type="hidden" name="id" value="'.$att->id_agenda.'">
                                        <button type="submit" class="btn btn-primary"><i class="fa fa-trash"></i> Hapus</button>
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
                                      '.form_close().'
                                      </div>
                                    </div>
                                  </div>
                                </div>';
                          }
                          if (in_array($access['l_ac']['apr'], $access['access'])) {
                                  echo '<div id="attapr'.$nn.'" class="modal fade" role="dialog">
                                    <div class="modal-dialog modal-sm modal-success">
                                      <div class="modal-content">
                                        <div class="modal-header">
                                          <button type="button" class="close" data-dismiss="modal">&times;</button>
                                          <h4 class="modal-title text-center">Validasi Nilai</h4>
                                        </div>
                                        <div class="modal-body text-center">
                                          <p>Apakah Anda yakin akan memvalidasi data nilai Agenda Sikap dengan nama <b>'.$att->nama_agenda.'</b> dan memunculkan <b class="text-red">Nilai Rapor '.$att->nama_agenda.'</b> pada Front Office</p>
                                        </div>
                                        <div class="modal-footer">
                                        '.form_open('agenda/validate_value_attd').'
                                          <input type="hidden" name="id" value="'.$att->kode_agenda.'">
                                          <input type="hidden" name="agd" value="aktif">
                                          <input type="hidden" name="val" value="1">
                                          <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Validasi</button>
                                          <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
                                        '.form_close().'
                                        </div>
                                      </div>
                                    </div>
                                  </div>';
                                  echo '<div id="attunapr'.$nn.'" class="modal fade" role="dialog">
                                    <div class="modal-dialog modal-sm modal-warning">
                                      <div class="modal-content">
                                        <div class="modal-header">
                                          <button type="button" class="close" data-dismiss="modal">&times;</button>
                                          <h4 class="modal-title text-center">Batal Validasi Nilai</h4>
                                        </div>
                                        <div class="modal-body text-center">
                                          <p>Apakah Anda akan membatalkan validasi data nilai Agenda Sikap dengan nama <b>'.$att->nama_agenda.'</b> dan menyembunyikan<b class="text-red">Nilai Rapor '.$att->nama_agenda.'</b> pada Front Office</p>
                                        </div>
                                        <div class="modal-footer">
                                        '.form_open('agenda/unvalidate_value_attd').'
                                          <input type="hidden" name="id" value="'.$att->kode_agenda.'">
                                          <input type="hidden" name="agd" value="aktif">
                                          <input type="hidden" name="val" value="0">
                                          <button type="submit" class="btn btn-danger"><i class="fa fa-times"></i> Batal Validasi</button>
                                          <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
                                        '.form_close().'
                                        </div>
                                      </div>
                                    </div>
                                  </div>';
                                }
                          $nn++;
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