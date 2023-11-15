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
        <i class="fa fa-gears"></i> Setting Aplikasi
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
        <li class="active">Setting</li>
      </ol>
    </section>
    <section class="content">
      <div class="row">

        <section class="col-md-6 connectedSortable">
          <div class="box box-danger">
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-edit"></i> Bobot Sikap</h3>
              <div class="box-tools pull-right">
                <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
              <?php 
              if (count($bbt_s) == 0) {
                echo '<div class="callout callout-danger"><label><i class="fa fa-info-circle"></i> Data Kosong</label><br>Tidak ada data yang ditampilkan</div>';
              }else{
                ?>
                <table id="example3" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>No.</th>
                      <th>Nama</th>
                      <th>Atasan</th>
                      <th>Bawahan</th>
                      <th>Rekan</th>

                      <th>Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
                    $n2=1;
                    foreach ($bbt_s as $b) {
                      echo '<tr>
                      <td width="5%">'.$n2.'.</td>
                      <td>'.$b->nama.'</td>
                      <td class="text-center ';
                      if ($b->atasan == 0) {
                        echo ' bg-gray';
                      }
                      echo '">'.$b->atasan.'</td>
                      <td class="text-center ';
                      if ($b->bawahan == 0) {
                        echo ' bg-gray';
                      }
                      echo '">'.$b->bawahan.'</td>
                      <td class="text-center ';
                      if ($b->rekan == 0) {
                        echo ' bg-gray';
                      }
                      echo '">'.$b->rekan.'</td>

                      <td class="text-center" width="10%">
                      <a href="#edt_b'.$n2.'" data-toggle="modal" class="btn btn-info"><i class="fa fa-edit" data-toggle="tooltip" title="Edit Data" data-placement="left"></i></a>
                      </td>
                      </tr>
                      <div id="edt_b'.$n2.'" class="modal fade" role="dialog">
                      <div class="modal-dialog modal-md"> 
                      <div class="modal-content">
                      <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                      <h4 class="modal-title text-center">Edit Data Tanggal</h4>
                      </div>
                      <div class="modal-body">
                      '.form_open('master/edt_bobot_s').'
                      <input type="hidden" name="id" value="'.$b->id_bobot.'">
                      <div class="form-group">
                      <label>Nama Kategori</label>
                      <input type="text" placeholder="Masukkan Nama" name="nama" value="'.$b->nama.'" class="form-control" required="required" >';
                      $ss=explode(':', $b->kode_bobot);
                      foreach ($ss as $s) {
                        if ($s == "ATS") {
                          echo '<label>Bobot Atasan</label>
                          <input type="text" placeholder="Masukkan Bobot Atasan" min="0" max="100" name="atasan" value="'.$b->atasan.'" class="form-control" required="required" >';
                        }
                        if ($s == "BWH") {
                          echo '<label>Bobot Bawahan</label>
                          <input type="text" placeholder="Masukkan Bobot Bawahan" min="0" max="100" name="bawahan" value="'.$b->bawahan.'" class="form-control" required="required" >';
                        }
                        if ($s == "RKN") {
                          echo '<label>Bobot Rekan</label>
                          <input type="text" placeholder="Masukkan Bobot Rekan" min="0" max="100" name="rekan" value="'.$b->rekan.'" class="form-control" required="required" >';
                        }
                      }
                      echo '</div>
                      </div>
                      <div class="modal-footer">
                      <button type="submit" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
                      <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
                      '.form_close().'
                      </div>
                      </div>

                      </div> 
                      </div>';
                      $n2++;
                    }
                    ?>
                  </tbody>
                </table>  
                <?php } ?>
              </div>
            </div>
          </section>
          <section class="col-md-6 connectedSortable">
            <div class="box box-success">
              <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-filter"></i> Master Konversi Nilai Output</h3>
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
                          <button class="btn btn-success btn-flat" type="button" data-toggle="collapse" data-target="#add"><i class="fa fa-plus"></i> Tambah Konversi</button>
                        </div>
                        <div class="pull-right" style="font-size: 8pt;">
                          <i class="fa fa-toggle-on stat scc"></i> Aktif<br>
                          <i class="fa fa-toggle-off stat err"></i> Tidak Aktif
                        </div>
                      </div>
                    </div>
                    <div class="collapse" id="add">
                      <br>
                      <div class="col-md-2"></div>
                      <div class="col-md-8">
                        <?php echo form_open('master/add_konversi');?>
                        <p class="text-danger">Semua data harus diisi!</p>
                        <div class="form-group">
                          <label>Nama Kategori</label>
                          <input type="text" placeholder="Masukkan Nama Kategori" name="nama" class="form-control" required="required" >
                          <label>Batas Awal</label>
                          <input type="number" max="100" min="0" step="0.001" placeholder="Masukkan Batas Awal" name="awal" class="form-control" required="required" >
                          <label>Batas Akhir</label>
                          <input type="number" max="100" min="0" step="0.001" placeholder="Masukkan Batas Akhir" name="akhir" class="form-control" required="required" >
                          <label>Huruf</label>
                          <input type="text" max-lenght="1" placeholder="Masukkan Huruf" name="huruf" class="form-control" required="required" >
                          <label>Warna</label>
                          <input type="text" name="warna" class="form-control my-colorpicker1">
                          <br>
                          <div class="form-group">
                            <button type="submit" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
                          </div>
                          <?php echo form_close();?>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <br>
                <div class="row">
                  <div class="col-md-12">


                    <?php 
                    if (count($konv) == 0) {
                      echo '<div class="callout callout-danger"><label><i class="fa fa-info-circle"></i> Data Kosong</label><br>Tidak ada data yang ditampilkan</div>';
                    }else{
                      ?>
                      <table id="example1" class="table table-bordered table-striped">
                        <thead>
                          <tr>
                            <th>No.</th>
                            <th>Kategori</th>
                            <th>Batas Awal</th>
                            <th>Batas Akhir</th>
                            <th>Huruf</th>
                            <th>Warna</th>
                            <th>Status</th>
                            <th>Aksi</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php 
                          $n=1;
                          foreach ($konv as $l) {
                            echo '<tr>
                            <td width="5%">'.$n.'.</td>
                            <td>'.$l->nama.'</td>
                            <td>'.$l->awal.'</td>
                            <td>'.$l->akhir.'</td>
                            <td>'.$l->huruf.'</td>
                            <td class="text-center">';
                            if ($l->warna == NULL) {
                              echo '<label class="label label-danger">Belum Ada</label>';
                            }else{
                              echo '<i class="fa fa-circle" style="color:'.$l->warna.'"></i>';
                            }
                            echo '</td>
                            <td class="text-center" width="10%">
                            '.form_open('master/status_konversi');
                            if ($l->status == "aktif") {
                              echo '
                              <input type="hidden" name="id" value="'.$l->id_konversi.'">
                              <input type="hidden" name="act" value="nonaktif">
                              <button type="submit" class="stat scc"><i class="fa fa-toggle-on"></i></button>';
                            }else{
                              echo '<input type="hidden" name="id" value="'.$l->id_konversi.'">
                              <input type="hidden" name="act" value="aktif">
                              <button type="submit" class="stat err"><i class="fa fa-toggle-off"></i></button>';
                            }
                            echo form_close().'</td>
                            <td class="text-center" width="10%">
                            <a href="#edt'.$n.'" data-toggle="modal" class="btn btn-info"><i class="fa fa-edit" data-toggle="tooltip" title="Edit Data" data-placement="left"></i></a>
                            <a href="#del'.$n.'" data-toggle="modal" class="btn btn-danger"><i class="fa fa-trash" data-toggle="tooltip" title="Hapus Data" data-placement="right"></i></a>
                            </td>
                            </tr>
                            <div id="edt'.$n.'" class="modal fade" role="dialog">
                            <div class="modal-dialog modal-md"> 
                            <div class="modal-content">
                            <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title text-center">Edit Data '.$l->nama.'</h4>
                            </div>
                            <div class="modal-body">
                            '.form_open('master/edt_konversi').'
                            <input type="hidden" name="id" value="'.$l->id_konversi.'">
                            <div class="form-group">
                            <label>Nama Kategori</label>
                            <input type="text" placeholder="Masukkan Nama Kategori" name="nama" value="'.$l->nama.'" class="form-control" required="required" >
                            <label>Batas Awal</label>
                            <input type="number" max="100" min="0" step="0.001" placeholder="Masukkan Batas Awal" name="awal" value="'.$l->awal.'" class="form-control" required="required" >
                            <label>Batas Akhir</label>
                            <input type="number" max="100" min="0" step="0.001" placeholder="Masukkan Batas Akhir" name="akhir" value="'.$l->akhir.'" class="form-control" required="required" >
                            <label>Huruf</label>
                            <input type="text" max-lenght="1" placeholder="Masukkan Huruf" name="huruf" value="'.$l->huruf.'" class="form-control" required="required" >
                            <label>Warna</label>
                            <input type="text" name="warna" class="form-control my-colorpicker1" value="'.$l->warna.'">
                            </div>
                            </div>
                            <div class="modal-footer">
                            <button type="submit" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
                            '.form_close().'
                            </div>
                            </div>

                            </div> 
                            </div>
                            <div id="del'.$n.'" class="modal fade" role="dialog">
                            <div class="modal-dialog modal-sm modal-danger">
                            <div class="modal-content">
                            <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title text-center">Konfirmasi Hapus</h4>
                            </div>
                            <div class="modal-body text-center">
                            <p>Apakah anda yakin akan menghapus data konversi nilai dengan nama <b>'.$l->nama.'</b> ?</p>
                            </div>
                            <div class="modal-footer">
                            '.form_open('master/del_konversi').'
                            <input type="hidden" name="id" value="'.$l->id_konversi.'">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-trash"></i> Hapus</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
                            '.form_close().'
                            </div>
                            </div>
                            </div>
                            </div>';
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

            </section>  
          </div>
          <div class="row">
            <section class="col-md-6 connectedSortable">
              <div class="row">
                <div class="col-md-12">
              <div class="box box-info">
                <div class="box-header with-border">
                  <h3 class="box-title"><i class="fa fa-edit"></i> Tanggal Update Data Karyawan</h3>
                  <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i class="fa fa-times"></i></button>
                  </div>
                </div>
                <div class="box-body">
                  <?php 
                  if (count($up_date) == 0) {
                    echo '<div class="callout callout-danger"><label><i class="fa fa-info-circle"></i> Data Kosong</label><br>Tidak ada data yang ditampilkan</div>';
                  }else{
                    ?>
                    <table id="example2" class="table table-bordered table-striped">
                      <thead>
                        <tr>
                          <th>No.</th>
                          <th>Tanggal Mulai</th>
                          <th>Tanggal Selesai</th>
                          <th>Status</th>
                          <th>Aksi</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php 
                        $n1=1;
                        foreach ($up_date as $u) {
                          echo '<tr>
                          <td width="5%">'.$n1.'.</td>
                          <td>'.date('d/m/Y H:i:s',strtotime($u->tgl_mulai)).'</td>
                          <td>'.date('d/m/Y H:i:s',strtotime($u->tgl_selesai)).'</td>
                          <td class="text-center" width="10%">
                          '.form_open('master/status_up_date_emp');
                          if ($u->status == "aktif") {
                            echo '
                            <input type="hidden" name="id" value="'.$u->id_date.'">
                            <input type="hidden" name="act" value="nonaktif">
                            <button type="submit" class="stat scc"><i class="fa fa-toggle-on"></i></button>';
                          }else{
                            echo '<input type="hidden" name="id" value="'.$u->id_date.'">
                            <input type="hidden" name="act" value="aktif">
                            <button type="submit" class="stat err"><i class="fa fa-toggle-off"></i></button>';
                          }
                          echo form_close().'</td>
                          <td class="text-center" width="10%">
                          <a href="#edt_d'.$n1.'" data-toggle="modal" class="btn btn-info"><i class="fa fa-edit" data-toggle="tooltip" title="Edit Data" data-placement="left"></i></a>
                          </td>
                          </tr>
                          <div id="edt_d'.$n1.'" class="modal fade" role="dialog">
                          <div class="modal-dialog modal-md"> 
                          <div class="modal-content">
                          <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal">&times;</button>
                          <h4 class="modal-title text-center">Edit Data Tanggal</h4>
                          </div>
                          <div class="modal-body">
                          '.form_open('master/edt_up_date_emp').'
                          <input type="hidden" name="id" value="'.$u->id_date.'">
                          <div class="form-group">
                          <label>Tanggal Aktif</label>
                          <div class="input-group">
                          <div class="input-group-addon">
                          <i class="fa fa-clock-o"></i>
                          </div>
                          <input type="text" class="form-control pull-right" id="reserv'.$n1.'" name="date" required="required">
                          </div>
                          </div>
                          </div>
                          <div class="modal-footer">
                          <button type="submit" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
                          <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
                          '.form_close().'
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
                <div class="row">
                  <div class="col-md-12">
                    <div class="box box-success">
                      <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-users"></i> User Group</h3>
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
                                  <button class="btn btn-success btn-flat" type="button" data-toggle="collapse" data-target="#add_ug"><i class="fa fa-plus"></i> Tambah User Group</button>
                                </div>
                                <div class="pull-right" style="font-size: 8pt;">
                                  <i class="fa fa-toggle-on stat scc"></i> Aktif<br>
                                  <i class="fa fa-toggle-off stat err"></i> Tidak Aktif
                                </div>
                              </div>
                            </div>
                            <div class="collapse" id="add_ug">
                              <br>
                              <div class="col-md-2"></div>
                              <div class="col-md-8">
                                <?php echo form_open('master/add_user_group');?>
                                <p class="text-danger">Semua data harus diisi!</p>
                                <div class="form-group">
                                  <label>Nama User Group</label>
                                  <input type="text" placeholder="Masukkan Nama User Group" name="nama" class="form-control" required="required" >
                                </div>
                                <div class="form-group">
                                  <label>Pilih Menu</label>
                                    <div style="height: 300px; overflow: auto;">
                                    <label class="contx">Pilih Semua
                                      <input type="checkbox" id="menu_all">
                                      <span class="checkmark"></span>
                                    </label><hr>
                                   <ul>
                                    <?php
                                      foreach ($menu_avl as $mavl) {
                                        if ($mavl->menu_id == NULL && $mavl->child == 0) {
                                          echo '<li style="list-style-type:none"><label class="contx"><i class="'.$mavl->icon.'"></i> '.$mavl->nama.'
                                          <input type="checkbox" id="ac_all" name="menu[]" value="'.$mavl->id_menu.'">
                                          <span class="checkmark"></span>
                                        </label></li>';
                                        }else{
                                          if ($mavl->menu_id == NULL && $mavl->child == 1) {
                                            $dtc2=$this->db->get_where('master_menu',array('menu_id'=>$mavl->id_menu,'status'=>'aktif','level'=>'2'))->result();
                                            echo '<li style="list-style-type:none"><label class="contx"><i class="'.$mavl->icon.'"></i> '.$mavl->nama.'
                                                    <input type="checkbox" id="ac_all" name="menu[]" value="'.$mavl->id_menu.'">
                                                    <span class="checkmark"></span>
                                                  </label>
                                            <ul>';
                                            foreach ($dtc2 as $lv2) {
                                                if ($lv2->child == 0) {
                                                  echo '<li style="list-style-type:none"><label class="contx"><i class="'.$lv2->icon.'"></i> '.$lv2->nama.'
                                                    <input type="checkbox" id="ac_all" name="menu[]" value="'.$lv2->id_menu.'">
                                                    <span class="checkmark"></span></li>';
                                                }else{
                                                  $dtc3=$this->db->get_where('master_menu',array('menu_id'=>$lv2->id_menu,'status'=>'aktif','level'=>'3'))->result();
                                                    echo '<li style="list-style-type:none"><label class="contx"><i class="'.$lv2->icon.'"></i> '.$lv2->nama.'
                                                    <input type="checkbox" id="ac_all" name="menu[]" value="'.$lv2->id_menu.'">
                                                    <span class="checkmark"></span>
                                                    <ul>';
                                                    foreach ($dtc3 as $lv3) {
                                                      echo '<li style="list-style-type:none"><label class="contx"><i class="'.$lv3->icon.'"></i> '.$lv3->nama.'
                                                    <input type="checkbox" id="ac_all" name="menu[]" value="'.$lv3->id_menu.'">
                                                    <span class="checkmark"></span></li>';
                                                    }
                                                    echo '</ul>';
                                                }
                                                
                                            }  
                                            echo '</ul>';
                                          }
                                        }
                                      }
                                    ?>
                                  </ul>
                                  </div>
                                </div>
                                <div class="form-group">
                                  <label>Pilih Hak Akses</label>
                                  <?php 
                                    $cna=1;
                                    echo '<label class="contx">Pilih Semua
                                          <input type="checkbox" id="ac_all">
                                          <span class="checkmark"></span>
                                        </label><hr>';
                                    foreach ($access_avl as $ac1) {
                                      echo '<label class="contx">'.$ac1->nama.'
                                          <input type="checkbox" id="ac'.$cna.'" name="hak_acc[]" value="'.$ac1->id_access.'">
                                          <span class="checkmark"></span>
                                        </label>';
                                        $cna++;
                                    }
                                  ?>
                                </div>
                                <div class="form-group">
                                  <button type="submit" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
                                </div>
                                <?php echo form_close();?>
                              </div>
                            </div>
                          </div>
                        </div>
                        <br>
                        <div class="row">
                          <div class="col-md-12">
                            <?php 
                            if (count($u_group) == 0) {
                              echo '<div class="callout callout-danger"><label><i class="fa fa-info-circle"></i> Data Kosong</label><br>Tidak ada data yang ditampilkan</div>';
                            }else{
                              ?>
                              <table id="example6" class="table table-bordered table-striped">
                                <thead>
                                  <tr>
                                    <th>No.</th>
                                    <th>Nama User Group</th>
                                    <th>List Menu</th>
                                    <th>Hak Akses</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                    <th>Aksi</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <?php 
                                  $nug=1;
                                  foreach ($u_group as $ug) {
                                    echo '<tr>
                                    <td width="5%">'.$nug.'.</td>
                                    <td>'.$ug->nama.'</td>
                                    <td class="text-center">';
                                    if ($ug->list_id_menu != "" || $ug->list_id_menu != NULL) {
                                      $idm=array_filter(explode(';', $ug->list_id_menu));
                                      if (count($menu_avl) == count($idm)) {
                                        echo '<label class="label label-primary">Semua Menu</label>';
                                      }else{
                                        echo count($idm).' Menu';
                                      }
                                      
                                      // foreach ($idm as $menul) {
                                      //   $mnuu=$this->db->get_where('master_menu',array('id_menu'=>$menul))->row_array();
                                      //   echo '<i class="'.$mnuu['icon'].'"></i> '.$mnuu['nama'].'<br>';
                                      // }
                                    }else{
                                      echo '<label class="label label-danger">Tidak Ada Daftar Menu</label>';
                                    }
                                    echo '</td>
                                    <td class="text-center">';
                                    if ($ug->list_access != "" || $ug->list_access != NULL) {
                                      $idacc=array_filter(explode(';', $ug->list_access));
                                      if (count($access_avl) == count($idacc)) {
                                        echo '<label class="label label-primary">Full Akses</label>';
                                      }else{
                                        echo '<ul>';
                                        foreach ($idacc as $iac) {
                                          $accs=$this->db->get_where('master_access',array('id_access'=>$iac))->row_array();
                                          echo '<li>'.$accs['nama'].'</li>';
                                        }
                                        echo '</ul>';
                                      }
                                    }else{
                                      echo '<label class="label label-danger">Tidak Ada Hak Akses</label>';
                                    }
                                    echo '</td>
                                    <td class="text-center" width="10%">
                                    '.form_open('master/status_user_group');
                                    if ($ug->status == "aktif") {
                                      echo '
                                      <input type="hidden" name="id" value="'.$ug->id_group.'">
                                      <input type="hidden" name="act" value="nonaktif">
                                      <button type="submit" class="stat scc"><i class="fa fa-toggle-on"></i></button>';
                                    }else{
                                      echo '<input type="hidden" name="id" value="'.$ug->id_group.'">
                                      <input type="hidden" name="act" value="aktif">
                                      <button type="submit" class="stat err"><i class="fa fa-toggle-off"></i></button>';
                                    }
                                    echo form_close().'</td>
                                    <td class="text-center"><label class="label label-warning" data-toggle="tooltip" title="Dibuat Tanggal"><i class="fa fa-pencil"></i> '.date("d/m/Y H:i:s",strtotime($ug->create_date)).'</label><br><label class="label label-primary" data-toggle="tooltip" title="Diupdate Tanggal"><i class="fa fa-edit"></i> '.date("d/m/Y H:i:s",strtotime($ug->update_date)).'</label></td>
                                    <td class="text-center" width="10%">
                                    <a href="#edt_ug'.$nug.'" data-toggle="modal" class="btn btn-info"><i class="fa fa-edit" data-toggle="tooltip" title="Edit Data" data-placement="left"></i></a>
                                    <a href="#del_ug'.$nug.'" data-toggle="modal" class="btn btn-danger"><i class="fa fa-trash" data-toggle="tooltip" title="Hapus Data" data-placement="right"></i></a>
                                    </td>
                                    </tr>
                                    <div id="edt_ug'.$nug.'" class="modal fade" role="dialog">
                                    <div class="modal-dialog modal-md"> 
                                    <div class="modal-content">
                                    <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title text-center">Edit Data '.$ug->nama.'</h4>
                                    </div>
                                    <div class="modal-body">
                                    '.form_open('master/edt_user_group').'
                                    <input type="hidden" name="id" value="'.$ug->id_group.'">
                                    <p class="text-danger">Semua data harus diisi!<br>Kode Akses TIDAK BOLEH SAMA</p>
                                    <div class="form-group">
                                    <label>Nama Akses</label>
                                    <input type="text" placeholder="Masukkan Nama Akses" name="nama" class="form-control" value="'.$ug->nama.'" required="required" >
                                    </div>
                                    </div>
                                    <div class="modal-footer">
                                    <button type="submit" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
                                    '.form_close().'
                                    </div>
                                    </div>

                                    </div> 
                                    </div>
                                    <div id="del_ug'.$nug.'" class="modal fade" role="dialog">
                                    <div class="modal-dialog modal-sm modal-danger">
                                    <div class="modal-content">
                                    <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title text-center">Konfirmasi Hapus</h4>
                                    </div>
                                    <div class="modal-body text-center">
                                    <p>Apakah anda yakin akan menghapus data Akses dengan nama <b>'.$ug->nama.'</b> ?</p>
                                    </div>
                                    <div class="modal-footer">
                                    '.form_open('master/del_user_group').'
                                    <input type="hidden" name="id" value="'.$ug->id_group.'">
                                    <button type="submit" class="btn btn-primary"><i class="fa fa-trash"></i> Hapus</button>
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
                                    '.form_close().'
                                    </div>
                                    </div>
                                    </div>
                                    </div>';
                                    $nug++;
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
              <?php 
              if ($level_adm == 1) {?>
              <section class="col-md-6 connectedSortable">
            <div class="box box-warning">
              <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-lock"></i> Master Akses</h3>
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
                          <button class="btn btn-success btn-flat" type="button" data-toggle="collapse" data-target="#add_acc"><i class="fa fa-plus"></i> Tambah Akses</button>
                        </div>
                        <div class="pull-right" style="font-size: 8pt;">
                          <i class="fa fa-toggle-on stat scc"></i> Aktif<br>
                          <i class="fa fa-toggle-off stat err"></i> Tidak Aktif
                        </div>
                      </div>
                    </div>
                    <div class="collapse" id="add_acc">
                      <br>
                      <div class="col-md-2"></div>
                      <div class="col-md-8">
                        <?php echo form_open('master/add_access');?>
                        <p class="text-danger">Semua data harus diisi!<br>Kode Akses TIDAK BOLEH SAMA</p>
                        <div class="form-group">
                          <label>Kode Akses</label>
                          <input type="text" placeholder="Masukkan Kode Akses" name="kode" class="form-control" required="required" >
                          <label>Nama Akses</label>
                          <input type="text" placeholder="Masukkan Nama Akses" name="nama" class="form-control" required="required" >
                          <br>
                          <div class="form-group">
                            <button type="submit" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
                          </div>
                          <?php echo form_close();?>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <br>
                <div class="row">
                  <div class="col-md-12">
                    <?php 
                    if (count($access) == 0) {
                      echo '<div class="callout callout-danger"><label><i class="fa fa-info-circle"></i> Data Kosong</label><br>Tidak ada data yang ditampilkan</div>';
                    }else{
                      ?>
                      <table id="example5" class="table table-bordered table-striped">
                        <thead>
                          <tr>
                            <th>No.</th>
                            <th>Kode Akses</th>
                            <th>Nama Akses</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php 
                          $nacc=1;
                          foreach ($access as $acc) {
                            echo '<tr>
                            <td width="5%">'.$nacc.'.</td>
                            <td>'.$acc->kode_access.'</td>
                            <td>'.$acc->nama.'</td>
                            <td class="text-center" width="10%">
                            '.form_open('master/status_access');
                            if ($acc->status == "aktif") {
                              echo '
                              <input type="hidden" name="id" value="'.$acc->id_access.'">
                              <input type="hidden" name="act" value="nonaktif">
                              <button type="submit" class="stat scc"><i class="fa fa-toggle-on"></i></button>';
                            }else{
                              echo '<input type="hidden" name="id" value="'.$acc->id_access.'">
                              <input type="hidden" name="act" value="aktif">
                              <button type="submit" class="stat err"><i class="fa fa-toggle-off"></i></button>';
                            }
                            echo form_close().'</td>
                            <td class="text-center"><label class="label label-warning" data-toggle="tooltip" title="Dibuat Tanggal"><i class="fa fa-pencil"></i> '.date("d/m/Y H:i:s",strtotime($acc->create_date)).'</label><br><label class="label label-primary" data-toggle="tooltip" title="Diupdate Tanggal"><i class="fa fa-edit"></i> '.date("d/m/Y H:i:s",strtotime($acc->update_date)).'</label></td>
                            <td class="text-center" width="10%">
                            <a href="#edt_acc'.$nacc.'" data-toggle="modal" class="btn btn-info"><i class="fa fa-edit" data-toggle="tooltip" title="Edit Data" data-placement="left"></i></a>
                            <a href="#del_acc'.$nacc.'" data-toggle="modal" class="btn btn-danger"><i class="fa fa-trash" data-toggle="tooltip" title="Hapus Data" data-placement="right"></i></a>
                            </td>
                            </tr>
                            <div id="edt_acc'.$nacc.'" class="modal fade" role="dialog">
                            <div class="modal-dialog modal-md"> 
                            <div class="modal-content">
                            <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title text-center">Edit Data '.$acc->nama.'</h4>
                            </div>
                            <div class="modal-body">
                            '.form_open('master/edt_access').'
                            <input type="hidden" name="id" value="'.$acc->id_access.'">
                            <input type="hidden" name="kode_old" value="'.$acc->kode_access.'">
                            <p class="text-danger">Semua data harus diisi!<br>Kode Akses TIDAK BOLEH SAMA</p>
                            <div class="form-group">
                            <label>Kode Akses</label>
                            <input type="text" placeholder="Masukkan Kode Akses" name="kode" class="form-control" value="'.$acc->kode_access.'" required="required" >
                            <label>Nama Akses</label>
                            <input type="text" placeholder="Masukkan Nama Akses" name="nama" class="form-control" value="'.$acc->nama.'" required="required" >
                            </div>
                            </div>
                            <div class="modal-footer">
                            <button type="submit" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
                            '.form_close().'
                            </div>
                            </div>

                            </div> 
                            </div>
                            <div id="del_acc'.$nacc.'" class="modal fade" role="dialog">
                            <div class="modal-dialog modal-sm modal-danger">
                            <div class="modal-content">
                            <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title text-center">Konfirmasi Hapus</h4>
                            </div>
                            <div class="modal-body text-center">
                            <p>Apakah anda yakin akan menghapus data Akses dengan nama <b>'.$acc->nama.'</b> ?</p>
                            </div>
                            <div class="modal-footer">
                            '.form_open('master/del_access').'
                            <input type="hidden" name="id" value="'.$acc->id_access.'">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-trash"></i> Hapus</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
                            '.form_close().'
                            </div>
                            </div>
                            </div>
                            </div>';
                            $nacc++;
                          }
                          ?>
                        </tbody>
                      </table>
                      <?php } ?>
                    </div>
                  </div>
                </div>
              </div>
              
            </section> 
            <?php } ?>
            </div>
            
              <?php 
              if ($level_adm == 1) {?>
              <div class="row">
              <section class="col-md-12 connectedSortable">
                <div class="box box-danger">
                  <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-navicon"></i> List Menu</h3>
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
                            <button class="btn btn-success btn-flat" type="button" data-toggle="collapse" data-target="#menu"><i class="fa fa-plus"></i> Tambah Menu</button>
                          </div>
                          <div class="pull-right" style="font-size: 8pt;">
                            <i class="fa fa-toggle-on stat scc"></i> Aktif<br>
                            <i class="fa fa-toggle-off stat err"></i> Tidak Aktif
                          </div>
                        </div>
                      </div>
                      <div class="collapse" id="menu">
                        <br>
                        <div class="col-md-2"></div>
                        <div class="col-md-8">
                          <?php echo form_open('master/add_menu');?>
                          <p class="text-danger">Semua data harus diisi!</p>
                          <div class="form-group">
                            <label>Nama Menu</label>
                            <input type="text" placeholder="Masukkan Nama Menu" name="nama" class="form-control" required="required" >
                            <label>URL</label>
                            <b>pages/</b> <input type="text" placeholder="Masukkan URL" name="url" class="form-control" required="required">
                            <label>URL Aktif</label>
                            <p class="text-danger">Pisahkan URL dengan ";" dan menambahkan "pages/" didepan url</p>
                            <textarea class="form-control" placeholder="Masukkan URL Aktif" name="url_active" required="required"></textarea>
                            <label>Level Menu</label>
                            <?php
                            $op=array(NULL=>'Pilih Level Menu','1'=>'1','2'=>'2','3'=>'3','4'=>'4');
                            $sel = array(NULL);
                            $ex = array('class'=>'form-control select2','style'=>'width:100%','id'=>'sel_lv','required'=>'required');
                            echo form_dropdown('level',$op,$sel,$ex);
                            echo '<label>Pilih Posisi Menu</label><p class="text-danger">Pilih posisi menu setelah menu apa</p>';
                            $op9[NULL]='Pilih Posisi';
                            $op9['FIRST']='Awal';
                            $op9['LAST']='Akhir';
                            $mn_ac=$this->db->get_where('master_menu',array('status'=>'aktif'))->result();
                            foreach ($mn_ac as $mac) {
                              $op9[$mac->id_menu]=$mac->nama;
                            }
                            $sel9 = array(NULL);
                            $ex9 = array('class'=>'form-control select2','style'=>'width:100%','required'=>'required');
                            echo form_dropdown('posisi',$op9,$sel9,$ex9);
                            ?>
                            <div id="show_sub">
                              <label>Punya Sub Menu</label>
                              <?php
                              $op1=array('0'=>'Tidak','1'=>'Ya');
                              $sel1 = array(0);
                              $ex1 = array('class'=>'form-control select2','style'=>'width:100%');
                              echo form_dropdown('sub',$op1,$sel1,$ex1);
                              ?>
                            </div>
                            <div id="show_header">
                              <label>Punya Header</label>
                              <?php
                              $op2=array('0'=>'Tidak','1'=>'Ya');
                              $sel2 = array(0);
                              $ex2 = array('class'=>'form-control select2','style'=>'width:100%','id'=>'sel_head');
                              echo form_dropdown('header',$op2,$sel2,$ex2);
                              ?>
                            </div>
                            <div id="head_shw">
                              <label>Header</label>
                              <input type="text" placeholder="Masukkan Header" name="header_name" class="form-control">
                            </div>
                            <div id="show_parent">
                              <label>Parent</label>
                              <?php
                              $dtp=$this->db->query('SELECT * FROM master_menu WHERE status="aktif" AND level != "4" AND child = "1"')->result();
                              $op3[NULL]="Pilih Parent";
                              foreach ($dtp as $par) {
                                $op3[$par->id_menu]=$par->nama;
                              }
                              $sel3 = array(NULL);
                              $ex3 = array('class'=>'form-control select2','style'=>'width:100%','id'=>'sel_head');
                              echo form_dropdown('parent',$op3,$sel3,$ex3);
                              ?>
                            </div>
                            <div id="icon_sel">
                              <label>Icon</label>
                              <div class="input-group">
                                <div class="input-group-btn">
                                  <button type="button" class="btn btn-info btn-flat" id="picker-button">Pilih Icon</button>
                                </div>
                                <input type="text" class="form-control" name="icon" id="icon-class-input" value="fa fa-music" />
                              </div>
                              <br>
                              <span style="font-size: 70pt;" id="demo-icon"></span>
                              <div id="iconPicker" class="modal fade">
                                <div class="modal-dialog">
                                  <div class="modal-content">
                                    <div class="modal-header">
                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                      <h4 class="modal-title text-center">Pilih Icon Menu</h4>
                                    </div>
                                    <div class="modal-body" style="height: 400px; overflow-y: scroll;">
                                      <div>
                                        <ul class="icon-picker-list">
                                          <li>
                                            <a data-class="{{item}} {{activeState}}" data-index="{{index}}">
                                              <span class="{{item}}"></span>
                                              <span class="name-class">{{item}}</span>
                                            </a>
                                          </li>
                                        </ul>
                                      </div>
                                    </div>
                                    <div class="modal-footer">
                                      <button type="button" id="change-icon" class="btn btn-success"><i class="fa fa-check-circle"></i> Pilih</button>
                                      <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            
                            
                          </div>
                          <div class="form-group">
                              <button type="submit" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
                            </div>
                          <?php echo form_close();?>
                        </div>
                      </div>
                    </div>
                  </div>
                  <br>
                  <div class="row">
                    <div class="col-md-12">
                      <?php 
                      if (count($menu) == 0) {
                        echo '<div class="callout callout-danger"><label><i class="fa fa-info-circle"></i> Data Kosong</label><br>Tidak ada data yang ditampilkan</div>';
                      }else{
                        ?>
                        <table id="example4" class="table table-bordered table-striped">
                          <thead>
                            <tr>
                              <th>No.</th>
                              <th>Nama Menu</th>
                              <th>URL</th>
                              <th>URL Aktif</th>
                              <th>Level</th>
                              <th>Punya Sub</th>
                              <th>Parent</th>
                              <th>Header</th>
                              <th>Status</th>
                              <th>Tanggal</th>
                              <th>Aksi</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                              $nom=1;
                              foreach ($menu as $mn) {
                                echo '<tr>
                                  <td>'.$nom.'.</td>
                                  <td><i class="'.$mn->icon.'"></i> '.$mn->nama.'</td>
                                  <td><a href="'.base_url($mn->url).'">'.$mn->url.'</a></td>
                                  <td>';
                                  $url_a=array_filter(explode(';', $mn->url_active_menu));
                                  foreach ($url_a as $ua) {
                                    echo '<a href="'.base_url($ua).'">'.$ua.'</a><br>';
                                  }
                                  echo '</td>
                                  <td>'.$mn->level.'</td>
                                  <td>';
                                  if ($mn->child == 0) {
                                    echo '<label class="label label-danger">Tidak Punya Sub Menu</label>';
                                  }else{
                                    echo '<label class="label label-success">Punya Sub Menu</label>';
                                  }
                                  echo '</td>
                                  <td>';
                                  if ($mn->menu_id == NULL) {
                                    echo '<label class="label label-info">Parent</label>';
                                  }else{
                                    $mnu=$this->db->get_where('master_menu',array('id_menu'=>$mn->menu_id))->row_array();
                                    if ($mnu == "") {
                                      echo '<label class="label label-danger">Tidak Punya Parent</label>';
                                    }else{
                                      echo '<i class="'.$mnu['icon'].'"></i> '.$mnu['nama'];
                                    }
                                  }
                                  echo '</td>
                                  <td>';
                                  if ($mn->header == NULL) {
                                    echo '<label class="label label-info">Tidak Punya Header</label>';
                                  }else{
                                    echo $mn->header;
                                  }
                                  echo '</td>
                                  <td class="text-center" width="10%">
                                  '.form_open('master/status_menu');
                                  if ($mn->status == "aktif") {
                                    echo '
                                    <input type="hidden" name="id" value="'.$mn->id_menu.'">
                                    <input type="hidden" name="act" value="nonaktif">
                                    <button type="submit" class="stat scc"><i class="fa fa-toggle-on"></i></button>';
                                  }else{
                                    echo '<input type="hidden" name="id" value="'.$mn->id_menu.'">
                                    <input type="hidden" name="act" value="aktif">
                                    <button type="submit" class="stat err"><i class="fa fa-toggle-off"></i></button>';
                                  }
                                  echo form_close().'</td>
                                  <td class="text-center"><label class="label label-warning" data-toggle="tooltip" title="Dibuat Tanggal"><i class="fa fa-pencil"></i> '.date("d/m/Y H:i:s",strtotime($mn->create_date)).'</label><br><label class="label label-primary" data-toggle="tooltip" title="Diupdate Tanggal"><i class="fa fa-edit"></i> '.date("d/m/Y H:i:s",strtotime($mn->update_date)).'</label></td>
                                  <td class="text-center" width="10%">
                                  <a href="#edt_mnu'.$nom.'" data-toggle="modal" class="btn btn-info"><i class="fa fa-edit" data-toggle="tooltip" title="Edit Data" data-placement="left"></i></a>
                                  <a href="#del_mnu'.$nom.'" data-toggle="modal" class="btn btn-danger"><i class="fa fa-trash" data-toggle="tooltip" title="Hapus Data" data-placement="right"></i></a>
                                  </td>
                                  </tr>
                                  <div id="edt_mnu'.$nom.'" class="modal fade" role="dialog">
                                  <div class="modal-dialog modal-md"> 
                                  <div class="modal-content">
                                  <div class="modal-header">
                                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                                  <h4 class="modal-title text-center">Edit Data '.$mn->nama.'</h4>
                                  </div>
                                  <div class="modal-body">
                                  '.form_open('master/edt_menu').'
                                  <input type="hidden" name="id" value="'.$mn->id_menu.'">
                                  <div class="form-group">
                                    <label>Nama Menu</label>
                                    <input type="text" placeholder="Masukkan Nama Menu" name="nama" class="form-control" value="'.$mn->nama.'" required="required" >
                                    <label>URL</label>
                                    <b>pages/</b> <input type="text" placeholder="Masukkan URL" name="url" class="form-control" value="'.str_replace('pages/', '', $mn->url).'" required="required">
                                    <label>URL Aktif</label>
                                    <p>Pisahkan URL dengan ";" dan menambahkan "pages/" didepan url</p>
                                    <textarea class="form-control" placeholder="Masukkan URL Aktif" name="url_active" required="required">'.$mn->url_active_menu.'</textarea>
                                    <label>Level Menu</label>';
                                    $opx=array(NULL=>'Pilih Level Menu','1'=>'1','2'=>'2','3'=>'3','4'=>'4');
                                    $selx = array($mn->level);
                                    $exx = array('class'=>'form-control select2','style'=>'width:100%','id'=>'sel_lv'.$nom,'required'=>'required');
                                    echo form_dropdown('level',$opx,$selx,$exx);
                                    echo '<label>Pilih Posisi Menu</label><p class="text-danger">Pilih posisi menu setelah menu apa</p>';
                                    $op10[NULL]='Pilih Posisi';
                                    $op10['FIRST']='Awal';
                                    $op10['LAST']='Akhir';
                                    $mn_ac1=$this->db->get_where('master_menu',array('status'=>'aktif'))->result();
                                    foreach ($mn_ac1 as $mac1) {
                                      $op10[$mac1->id_menu]=$mac1->nama;
                                    }
                                    $sel10 = array(NULL);
                                    $ex10 = array('class'=>'form-control select2','style'=>'width:100%','required'=>'required');
                                    echo form_dropdown('posisi',$op10,$sel10,$ex10);
                                    echo '<div id="show_sub'.$nom.'">
                                      <label>Punya Sub Menu</label>';
                                      $opx1=array('0'=>'Tidak','1'=>'Ya');
                                      $selx1 = array($mn->child);
                                      $exx1 = array('class'=>'form-control select2','style'=>'width:100%');
                                      echo form_dropdown('sub',$opx1,$selx1,$exx1);
                                    echo '</div>
                                    <div id="show_header'.$nom.'">
                                      <label>Punya Header</label>';
                                      $opx2=array('0'=>'Tidak','1'=>'Ya');
                                      if ($mn->header != NULL) {
                                        $ss1=1;
                                      }else{
                                        $ss1=0;
                                      }
                                      $selx2 = array($ss1);
                                      $exx2 = array('class'=>'form-control select2','style'=>'width:100%','id'=>'sel_head'.$nom);
                                      echo form_dropdown('header',$opx2,$selx2,$exx2);
                                    echo '</div>
                                    <div id="head_shw'.$nom.'">
                                      <label>Header</label>
                                      <input type="text" placeholder="Masukkan Header" name="header_name" value="'.$mn->header.'" class="form-control">
                                    </div>
                                    <div id="show_parent'.$nom.'">
                                      <label>Parent</label>';
                                      $dtpx=$this->db->query('SELECT * FROM master_menu WHERE status="aktif" AND level != "4" AND child = "1"')->result();
                                      $opx3[NULL]="Pilih Parent";
                                      foreach ($dtpx as $parx) {
                                        $opx3[$parx->id_menu]=$parx->nama;
                                      }
                                      $selx3 = array($mn->menu_id);
                                      $exx3 = array('class'=>'form-control select2','style'=>'width:100%','id'=>'sel_head');
                                      echo form_dropdown('parent',$opx3,$selx3,$exx3);
                                    echo '</div>
                                    <div id="icon_sel'.$nom.'">
                                      <label>Icon</label>
                                      <div class="input-group">
                                        <div class="input-group-btn">
                                          <button type="button" class="btn btn-info btn-flat" id="picker-button'.$nom.'">Pilih Icon</button>
                                        </div>
                                        <input type="text" class="form-control" name="icon" id="icon-class-input'.$nom.'" value="'.$mn->icon.'" />
                                      </div>
                                    </div>
                                  </div>
                                  </div>
                                  <div class="modal-footer">
                                  <button type="submit" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
                                  <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
                                  '.form_close().'
                                  </div>
                                  </div>

                                  </div> 
                                  </div>
                                  <div id="del_mnu'.$nom.'" class="modal fade" role="dialog">
                                  <div class="modal-dialog modal-sm modal-danger">
                                  <div class="modal-content">
                                  <div class="modal-header">
                                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                                  <h4 class="modal-title text-center">Konfirmasi Hapus</h4>
                                  </div>
                                  <div class="modal-body text-center">
                                  <p>Apakah anda yakin akan menghapus Menu dengan nama <b>'.$mn->nama.'</b> ?</p>
                                  </div>
                                  <div class="modal-footer">
                                  '.form_open('master/del_menu').'
                                  <input type="hidden" name="id" value="'.$mn->id_menu.'">
                                  <button type="submit" class="btn btn-primary"><i class="fa fa-trash"></i> Hapus</button>
                                  <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
                                  '.form_close().'
                                  </div>
                                  </div>
                                  </div>
                                  </div>';
                                $nom++;
                              }
                            ?>
                          </tbody>
                        </table>
                        <?php }?>
                      </div>
                    </div>
                  </div>
                </div>
              </section>
              </div>
              <?php } ?>
            
            <div class="row">
              <section class="col-md-12 connectedSortable">
                <div class="box box-success">
                  <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-user-secret"></i> Data Admin</h3>
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
                              <button class="btn btn-success btn-flat" type="button" data-toggle="collapse" data-target="#add_adm"><i class="fa fa-plus"></i> Tambah Admin</button>
                            </div>
                          </div>
                        </div>
                        <div class="collapse" id="add_adm">
                          <br>
                          <div class="col-md-2"></div>
                          <div class="col-md-8">
                            <?php echo form_open('admin/add_admin');?>
                            <p class="text-danger">Semua data harus diisi!</p>
                            <div class="form-group">
                              <label>Pilih Karyawan</label>
                              <?php 
                                foreach ($emp as $em) {
                                  if (isset($l_kar)) {
                                    if (!in_array($em->id_karyawan, $l_kar)) {
                                      $op6[$em->id_karyawan]=$em->nama;
                                    }
                                  }else{
                                    $op6[$em->id_karyawan]=$em->nama;
                                  }
                                  
                                }
                                $sel6 = array();
                                $ex6 = array('class'=>'form-control select2','multiple'=>'multiple','data-placeholder'=>'Pilih Karyawan','required'=>'required','style'=>'width:100%');
                                echo form_dropdown('employee[]',$op6,$sel6,$ex6);

                                echo '<label>Pilih User Group</label>';
                              
                                $op7[NULL]="Pilih User Group";
                                foreach ($u_group_avl as $uga) {
                                  $op7[$uga->id_group]=$uga->nama;
                                }
                                $sel7 = array(NULL);
                                $ex7 = array('class'=>'form-control select2','required'=>'required','style'=>'width:100%');
                                echo form_dropdown('u_group',$op7,$sel7,$ex7);
                                if ($level_adm == 1) {
                                  echo '<label>Pilih Level</label>';
                                  $op8=array(NULL=>"Pilih Level Admin",'1'=>'1','2'=>'2');
                                  $sel8 = array(NULL);
                                  $ex8 = array('class'=>'form-control select2','required'=>'required','style'=>'width:100%');
                                  echo form_dropdown('level',$op8,$sel8,$ex8);
                                }
                              ?>
                            </div>
                            <div class="form-group">
                              <button type="submit" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
                            </div>
                            <?php echo form_close();?>
                          </div>
                        </div>
                      </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-md-12">
                        <?php 
                        if (count($list_admin) == 0) {
                          echo '<div class="callout callout-danger"><label><i class="fa fa-info-circle"></i> Data Kosong</label><br>Tidak ada data yang ditampilkan</div>';
                        }else{
                          ?>
                          <table id="example7" class="table table-bordered table-striped">
                            <thead>
                              <tr>
                                <th>No.</th>
                                <th>Nama</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>User Group</th>
                                <th>Admin Level</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                                <th>Aksi</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php 
                              $nadm=1;
                              foreach ($list_admin as $ladm) {
                                echo '<tr>
                                <td width="5%">'.$nadm.'.</td>
                                <td>'.$ladm->nama.'</td>
                                <td>'.$ladm->username.'</td>
                                <td>'.$ladm->email.'</td>
                                <td>';
                                $dtug=$this->db->get_where('master_user_group',array('id_group'=>$ladm->id_group))->row_array();
                                if (count($dtug) > 0) {
                                  echo $dtug['nama'];
                                }else{
                                  echo '<label class="label label-danger">Tidak Masuk Group</label>';
                                }
                                echo '</td>
                                <td class="text-center">';
                                if ($ladm->level == 1) {
                                  echo '<label class="label label-success">Admin Level '.$ladm->level.'</label>';
                                }else{
                                  echo '<label class="label label-primary">Admin Level '.$ladm->level.'</label>';
                                }
                                echo '</td>
                                <td class="text-center"><label class="label label-warning" data-toggle="tooltip" title="Terdaftar Tanggal"><i class="fa fa-pencil"></i> '.date("d/m/Y H:i:s",strtotime($ladm->create_date)).'</label><br><label class="label label-primary" data-toggle="tooltip" title="Update Data Tanggal"><i class="fa fa-edit"></i> '.date("d/m/Y H:i:s",strtotime($ladm->update_date)).'</label><br><label class="label label-danger" data-toggle="tooltip" title="Terakhir Login"><i class="fa fa-sign-in"></i> '.date("d/m/Y H:i:s",strtotime($ladm->last_login)).'</label></td>
                                <td class="text-center">';
                                if ($ladm->status == "online") {
                                  echo '<label class="label label-success">Online</label>';
                                }else{
                                  echo '<label class="label label-default">Offline</label>';
                                }
                                echo '</td>
                                <td class="text-center" width="10%">';
                                if ($ladm->id_admin == 1) {
                                  if ($level_adm == 1) {
                                    echo '<a href="#edt_adm'.$nadm.'" data-toggle="modal" class="btn btn-info"><i class="fa fa-edit" data-toggle="tooltip" title="Edit Data" data-placement="left"></i></a>
                                  <a href="#del_adm'.$nadm.'" data-toggle="modal" class="btn btn-danger"><i class="fa fa-trash" data-toggle="tooltip" title="Hapus Data" data-placement="right"></i></a>';
                                  }else{
                                    echo '<label class="label label-danger">Anda Tidak Diizinkan</label>';
                                  }
                                }else{
                                  if ($level_adm == 1) {
                                    echo '<a href="#edt_adm'.$nadm.'" data-toggle="modal" class="btn btn-info"><i class="fa fa-edit" data-toggle="tooltip" title="Edit Data" data-placement="left"></i></a>
                                  <a href="#del_adm'.$nadm.'" data-toggle="modal" class="btn btn-danger"><i class="fa fa-trash" data-toggle="tooltip" title="Hapus Data" data-placement="right"></i></a>';
                                  }else{
                                    echo '<label class="label label-danger">Anda Tidak Diizinkan</label>';
                                  }
                                }
                                
                                
                                echo '</td>
                                </tr>
                                <div id="edt_adm'.$nadm.'" class="modal fade" role="dialog">
                                <div class="modal-dialog modal-md"> 
                                <div class="modal-content">
                                <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title text-center">Edit Data '.$ladm->nama.'</h4>
                                </div>
                                <div class="modal-body">
                                '.form_open('admin/edt_admin').'
                                <input type="hidden" name="id" value="'.$ladm->id_admin.'">
                                <div class="form-group">
                                <label>Nama Admin</label>
                                <input type="text" placeholder="Masukkan Nama Admin" name="nama" value="'.$ladm->nama.'" class="form-control" required="required" >
                                </div>
                                <div class="form-group">
                                <label>Username</label>
                                <input type="text" placeholder="Masukkan Username Admin" name="username" value="'.$ladm->username.'" class="form-control" required="required" >
                                </div>
                                <div class="form-group">
                                <label>Email</label>
                                <input type="email" placeholder="Masukkan Email Admin" name="email" value="'.$ladm->email.'" class="form-control" required="required" >
                                </div>
                                <div class="form-group">
                                <label>User Group</label>';
                                $op4=array(NULL=>'Pilih User Group');
                                foreach ($u_group_avl as $uavl) {
                                  $op4[$uavl->id_group]=$uavl->nama;
                                }
                                $sel4 = array($ladm->id_group);
                                $ex4 = array('class'=>'form-control select2','style'=>'width:100%');
                                echo form_dropdown('u_group',$op4,$sel4,$ex4);
                                echo '</div>
                                <div class="form-group">
                                <label>User Group</label>';
                                $op5=array(NULL=>'Pilih Level Admin','1'=>'Level 1','2'=>'Level 2');
                                $sel5 = array($ladm->level);
                                $ex5 = array('class'=>'form-control select2','style'=>'width:100%');
                                echo form_dropdown('level',$op5,$sel5,$ex5);
                                echo '</div>
                                </div>
                                <div class="modal-footer">
                                <button type="submit" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
                                <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
                                '.form_close().'
                                </div>
                                </div>

                                </div> 
                                </div>
                                <div id="del_adm'.$nadm.'" class="modal fade" role="dialog">
                                <div class="modal-dialog modal-sm modal-danger">
                                <div class="modal-content">
                                <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title text-center">Konfirmasi Hapus</h4>
                                </div>
                                <div class="modal-body text-center">
                                <p>Apakah anda yakin akan menghapus data konversi nilai dengan nama <b>'.$ladm->nama.'</b> ?</p>
                                </div>
                                <div class="modal-footer">
                                '.form_open('admin/del_admin').'
                                <input type="hidden" name="id" value="'.$ladm->id_admin.'">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-trash"></i> Hapus</button>
                                <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
                                '.form_close().'
                                </div>
                                </div>
                                </div>
                                </div>';
                                $nadm++;
                              }
                              ?>
                            </tbody>
                          </table>
                          <?php } ?>
                        </div>
                      </div>
                    </div>
                  </div>

                </section>  
            </div>
          </section>
        </div> 