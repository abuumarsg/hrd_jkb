  <div class="content-wrapper">
    <?php 
    if (!empty($this->session->flashdata('msgsc'))) {
      echo '<div class="alert alert-success" id="alert-success">'.$this->session->flashdata('msgsc').'</div>';
    }elseif (!empty($this->session->flashdata('msgerr'))) {
      echo '<div class="alert alert-danger" id="alert-success">'.$this->session->flashdata('msgerr').'</div>';
    }elseif (!empty($this->session->flashdata('msgwr'))) {
      echo '<div class="alert alert-warning" id="alert-success">'.$this->session->flashdata('msgwr').'</div>';
    }
    ?>
    <section class="content-header">
      <h1>
        <i class="fa fa-users"></i> Data
        <small>Karyawan</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
        <li class="active"><i class="fa fa-users"></i> Data Karyawan</li>
      </ol>
    </section>
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-users"></i> Data Seluruh Karyawan</h3>
              <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
                <div class="row">
                  <div class="col-md-12">
                    <?php
                      if (in_array($access['l_ac']['add'], $access['access'])) {
                        echo '<a href="'.base_url('pages/add_employee').'" class="btn btn-success btn-flat" ><i class="fa fa-user"></i> Tambah Karyawan</a> ';
                      }
                      if (in_array($access['l_ac']['imp'], $access['access'])) {
                        echo '<button class="btn btn-primary btn-flat" type="button" data-toggle="collapse" data-target="#import" aria-expanded="false" aria-controls="import"><i class="fa fa-cloud-upload"></i> Import</button> ';
                      }
                      if (in_array($access['l_ac']['exp'], $access['access'])) {
                        echo '<a href="'.base_url('employee/export_employee').'" class="btn btn-warning btn-flat"><i class="fa fa-cloud-download"></i> Export Template</a> ';
                      }
                      if (in_array($access['l_ac']['imp'], $access['access'])) {
                    ?>
                    <div class="row">
                      <div class="col-md-2"></div>
                      <div class="col-md-8">
                        <div class="collapse" id="import">
                          <br>
                          <div class="col-md-12">
                            <div class="callout callout-info"><label><i class="fa fa-info-circle"></i> Bantuan</label><p style="color:red;">File harus bertipe *.xls, *.xlsx, *.csv, *.ods dan *.ots</p></div>
                            
                            <?php echo form_open_multipart('employee/import');?>
                            
                            <input id="uploadFile" placeholder="Nama File" disabled="disabled" class="form-control" required="required">
                            <span class="input-group-btn">
                              <div class="fileUpload btn btn-warning btn-flat">
                                <span><i class="fa fa-folder-open"></i> Pilih File</span>
                                <input id="uploadBtn" type="file" class="upload" name="file"/>
                              </div>
                              <button class="btn btn-info btn-flat" id="save" type="submit" disabled><i class="fa fa-check-circle"></i> Upload</button>
                            </span>
                            <?php echo form_close();?>
                          </div>
                        </div>
                      </div>
                    </div><?php } ?>
                  </div>
                </div>
                <br>
                <div class="row">
                  <div class="col-md-12">
                    <?php 
                      if (count($employ) == 0) {
                        echo '<div class="callout callout-danger"><label><i class="fa fa-info-circle"></i> Data Kosong</label><br>Tidak ada data yang ditampilkan</div>';
                      }else{
                    ?>
                    <div class="callout callout-info"><label><i class="fa fa-info-circle"></i> Bantuan</label><br>Pilih Nomor Induk Karyawan untuk melihat maupun melakukan update pada data karyawan</div>
                  <table id="example1" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>No.</th>
                        <th>Nomor Induk</th>
                        <th>Nama Karyawan</th>
                        <th>Jabatan</th>
                        <th>Unit</th>
                        <th>Grade</th>
                        <th>Email</th>
                        <th>Tanggal Masuk</th>
                        <th>Status</th>
                        <th>Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php 
                      $no=1;
                        foreach ($employ as $e) {
                          echo '<tr>
                                  <td width="2%">'.$no.'.</td>
                                  <td><a href="'.base_url('pages/view_employee/'.$e->nik).'">'.$e->nik.'</a></td>
                                  <td width="15%">'.ucwords($e->nama).'</td>
                                  <td>';
                                  $dtj=$this->db->get_where('master_jabatan',array('kode_jabatan'=>$e->jabatan))->row_array();
                                  if ($dtj == "") {
                                    echo '<label class="label label-danger">Tidak Memiliki Jabatan</label>';
                                  }else{
                                    echo ucwords($dtj['jabatan']);
                                  }
                                  if ($e->kode_sub != NULL) {
                                    $sub=$this->db->get_where('master_sub_jabatan',array('kode_sub'=>$e->kode_sub))->row_array();
                                    if ($sub != "") {
                                      echo '<br><label class="label label-info">Sub Jabatan <b style="color:red;">'.$sub['nama_sub'].'</b></label>';
                                    }
                                  }
                                  echo '</td>
                                  <td>';
                                  $dtj1=$this->db->get_where('master_loker',array('kode_loker'=>$e->unit))->row_array();
                                  if ($dtj1 == "") {
                                    echo '<label class="label label-danger">Tidak Memiliki Lokasi Kerja</label>';
                                  }else{
                                    echo ucwords($dtj1['nama']);
                                  }
                                  echo '</td>
                                  <td>';
                                  if ($e->grade == "") {
                                    echo '<label class="label label-danger">Grade Tidak Ada</label>';
                                  }else{
                                    echo ucwords($e->grade);
                                  }
                                  echo '</td>
                                  <td>';
                                  if ($e->email == "") {
                                    echo '<label class="label label-danger">Email Tidak Ada</label>';
                                  }else{
                                    echo $e->email;
                                  }
                                  echo '</td>
                                  <td>'.$this->formatter->getDateMonthFormatUser($e->tgl_masuk).'</td>
                                  <td class="text-center">';
                                  if ($e->status == "offline") {
                                    echo '<label class="label label-default">Offline</label>';
                                  }else{
                                    echo '<label class="label label-success">Online</label>';
                                  }
                                  echo '</td>
                                  <td width="6%" class="text-center">';
                                    if (in_array($access['l_ac']['del'], $access['access'])) {
                                      echo '<a href="#del'.$no.'" data-toggle="modal" class="btn btn-danger"><i class="fa fa-trash" data-toggle="tooltip" title="Hapus Data"></i></a>';
                                    }else{
                                      echo '<label class="label label-danger">Tidak Diizinkan</label>';
                                    }
                                 echo '</td>
                                </tr>';
                                if (in_array($access['l_ac']['del'], $access['access'])) {
                                  echo '<div id="del'.$no.'" class="modal fade" role="dialog">
                                  <div class="modal-dialog modal-sm modal-danger">
                                    <div class="modal-content">
                                      <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title text-center">Konfirmasi Hapus</h4>
                                      </div>
                                      <div class="modal-body text-center">
                                        <p>Apakah anda yakin akan menghapus seluruh data karyawan dengan nama <b>'.$e->nama.'</b> ?</p>
                                      </div>
                                      <div class="modal-footer">
                                      '.form_open('employee/del_employee').'
                                        <input type="hidden" name="id" value="'.$e->id_karyawan.'">
                                        <button type="submit" class="btn btn-primary"><i class="fa fa-trash"></i> Hapus</button>
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
                                      '.form_close().'
                                      </div>
                                    </div>
                                  </div>
                                </div>';
                                }

                          /*
                          $birthDt = new DateTime($e->tgl_lahir);
                          $today = new DateTime('today');
                          $y = $today->diff($birthDt)->y;
                          $m = $today->diff($birthDt)->m;
                          $d = $today->diff($birthDt)->d;
                          
                          $in1 = new DateTime($e->tgl_masuk);
                          $today1 = new DateTime('today');
                          $yy1 = $today1->diff($in1);
                          $my1 = $today1->diff($in1);
                          $dy1 = $today1->diff($in1);
                          echo '<tr>
                                  <td>'.$no.'.</td>
                                  <td><a href="'.base_url('pages/view_employee/'.$e->nik).'">'.$e->nik.'</a></td>
                                  <td>'.ucwords($e->nama).'</td>
                                  <td>'.ucwords($e->tempat_lahir).', '.date('d/m/Y',strtotime($e->tgl_lahir)).'</td>
                                  <td>'.ucwords($e->no_hp).'</td>
                                  <td>'.ucwords($e->status_pajak).'</td>
                                  <td>'.ucwords($e->agama).'</td>
                                  <td>'.ucwords($e->kelamin).'</td>
                                  <td>';
                                  $nn=$e->status_pegawai;
                                  $data=$this->db->query("SELECT masa_habis FROM master_status_pegawai WHERE nama = ? AND status = 'aktif'",array($nn))->row_array();
                                  if ($data == "") {
                                    echo '<label class="label label-danger">Status Pegawai Tidak Ditemukan</label>';
                                  }else{
                                    $data1=explode(" ", $data['masa_habis']);
                                    if ($data1[0] == 'Umur') {
                                      $tahun = $data1[1]-$y;
                                      $tt='+'.$tahun.' years';
                                      $now=gmdate("Y-m-d", time() + 3600*(7));
                                      echo date('d F Y', strtotime($tt, strtotime($now)));
                                      echo $tt,$now;
                                    }
                                  }
                                  echo '</td>
                                  <td>'.$y.' Tahun '.$m.' Bulan</td>
                                  <td>'.$yy1->format('%y').' Tahun '.$my1->format('%m').' Bulan</td>
                                  <td>'.ucwords($e->jabatan).'</td>
                                  <td>'.ucwords($e->kode_unit).'</td>
                                  <td>'.ucwords($e->unit).'</td>
                                  <td>'.ucwords($e->grade).'</td>
                                  <td>'.ucwords($e->npwp).'</td>
                                  <td>'.$e->email.'</td>
                                  <td>'.ucwords($e->gol_darah).'</td>
                                  <td>'.ucwords($e->rekening).'</td>
                                  <td>'.ucwords($e->bpjskes).'</td>
                                  <td>'.ucwords($e->bpjstk).'</td>
                                  <td>'.ucwords($e->no_ktp).'</td>
                                  <td>'.ucwords($e->berat_badan).' cm</td>
                                  <td>'.ucwords($e->tinggi_badan).' cm</td>
                                  <td>'.ucwords($e->pendidikan).'</td>
                                  <td>'.ucwords($e->universitas).'</td>
                                  <td>'.ucwords($e->jurusan).'</td>
                                  <td>'.ucwords($e->status_pegawai).'</td>
                                  <td>'.date('d/m/Y',strtotime($e->tgl_masuk)).'</td>
                                  <td>';
                                  if ($e->alamat_asal_jalan == NULL || $e->alamat_asal_jalan == "") {
                                    echo '<label class="label label-danger">Alamat Belum Diinput</label>';
                                  }else{
                                    echo ucwords($e->alamat_asal_jalan).', '.ucwords($e->alamat_asal_desa).', '.ucwords($e->alamat_asal_kecamatan).', '.ucwords($e->alamat_asal_kabupaten).', '.ucwords($e->alamat_asal_provinsi).'<br>Kode Pos : '.ucwords($e->alamat_asal_pos);
                                  }
                                  echo '</td>
                                  <td>';
                                  if ($e->alamat_sekarang_jalan == NULL || $e->alamat_sekarang_jalan == "") {
                                    echo '<label class="label label-danger">Alamat Belum Diinput</label>';
                                  }else{
                                    echo ucwords($e->alamat_sekarang_jalan).', '.ucwords($e->alamat_sekarang_desa).', '.ucwords($e->alamat_sekarang_kecamatan).', '.ucwords($e->alamat_sekarang_kabupaten).', '.ucwords($e->alamat_sekarang_provinsi).' <br>Kode Pos : '.ucwords($e->alamat_sekarang_pos);
                                  }
                                  echo '</td>
                                  <td>'.ucwords($e->nik1).'</td>
                                  <td>'.ucwords($e->ibu_kandung).'</td>
                                  <td>'.ucwords($e->no_hp_ibu).'</td>
                                  <td>'.ucwords($e->ayah_kandung).'</td>
                                  <td>'.ucwords($e->no_hp_ayah).'</td>
                                  <td>'.ucwords($e->status_nikah).'</td>
                                  <td>'.ucwords($e->nama_pasangan).'</td>
                                  <td>'.date('d/m/Y',strtotime($e->ttl_pasangan)).'</td>
                                  <td>'.ucwords($e->no_hp_pasangan).'</td>
                                  <td>'.ucwords($e->jumlah_anak).'</td>
                                  <td>'.ucwords($e->anak_pertama).'</td>
                                  <td>'.date('d/m/Y',strtotime($e->ttl_anak_pertama)).'</td>
                                  <td>'.ucwords($e->anak_kedua).'</td>
                                  <td>'.date('d/m/Y',strtotime($e->ttl_anak_kedua)).'</td>
                                  <td>'.ucwords($e->anak_ketiga).'</td>
                                  <td>'.date('d/m/Y',strtotime($e->ttl_anak_ketiga)).'</td>
                                  <td>'.date('d/m/Y',strtotime($e->create_date)).'</td>
                                  <td>'.date('d/m/Y',strtotime($e->update_date)).'</td>
                                  <td>';
                                  if ($e->status == "offline") {
                                    echo '<label class="label label-default">Offline</label>';
                                  }else{
                                    echo '<label class="label label-success">Online</label>';
                                  }
                                  echo '</td>
                                  
                                </tr>';*/
                          $no++;
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