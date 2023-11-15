<div class="content-wrapper">
    <section class="content-header">
      <h1>
        <i class="fa fa-gears"></i> General
        <small>Setting</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
        <li class="active">Setting Root Password</li>
      </ol>
    </section>
    <section class="content">
      <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-6">
          <div class="box box-solid">
            <div class="box-header with-border">
              <i class="fa fa-gears"></i>
              <h3 class="box-title">General Setting</h3>
            </div>
            <div class="box-body">
              <div class="row">
                <div class="col-md-12">
                  <div class="box box-widget widget-user-2">
                    <div class="widget-user-header bg-yellow">
                      <div class="widget-user-image" style="position:absolute">
                        <i class="far fa-money-bill-alt fa-4x"></i>
                      </div>
                      <h3 class="widget-user-username">Setting</h3>
                      <h5 class="widget-user-desc">Setting Untuk Data Payroll</h5>
                    </div>
                    <div class="box-footer no-padding">
                      <ul class="nav nav-stacked">
                        <li><a data-toggle="collapse" href="#setting_um" onclick="reload_data('setting_um')">Setting Uang Makan<span class="pull-right"><i id="icon_setting_um" class="fa fa-chevron-down"></i></span></a>
                          <div id="setting_um" class="collapse">
                            <div style="margin:15px">
                              <div class="row">
                                <div class="col-md-12">
                                  <div class="panel panel-warning">
                                    <div class="panel-body">
                                      <p>Setting Uang Makan "<b id="setting_um_value">0</b>"</p>
                                      <form id="form_setting_um">
                                        <input type="hidden" value="setting_um">
                                        <div class="form-group">
                                          <label>Jenis Uang Makan</label>
                                            <?php
                                            $yesno[null] = 'Pilih Data';
                                            $yesno[null] = $this->otherfunctions->getAktifUangMakan();
                                            $sel3 = array(null);
                                            $ex3 = array('class'=>'form-control select2','style'=>'width:100%;','required'=>'required','id'=>'data_setting_um_edit');
                                            echo form_dropdown('setting_um',$yesno,$sel3,$ex3);
                                            ?>
                                        </div>
                                        <div class="form-group">
                                          <button type="button" class="btn btn-success btn-flat" onclick="do_save('setting_um')"><i class="fa fa-floppy-o"></i> Simpan</button>
                                        </div>
                                      </form>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div> 
                        </li>
                        <li><a data-toggle="collapse" href="#uang_makan" onclick="reload_data('uang_makan')">Setting Jumlah Uang Makan Dalam Sehari<span class="pull-right"><i id="icon_uang_makan" class="fa fa-chevron-down"></i></span></a>
                          <div id="uang_makan" class="collapse">
                            <div style="margin:15px">
                              <div class="row">
                                <div class="col-md-12">
                                  <div class="panel panel-warning">
                                    <div class="panel-body">
                                      <p>Jumlah uang makan dalam sehari Jika Tidak Sesuai Grade adalah <b id="uang_makan_value">0</b></p>
                                      <form id="form_uang_makan">
                                        <div class="form-group">
                                          <input type="hidden" value="uang_makan">
                                          <input type="text" class="form-control input-money" id="data_uang_makan_edit" name="nominal" placeholder="Masukkan Nominal Uang Makan" required="required">
                                        </div>
                                        <div class="form-group">
                                          <button type="button" class="btn btn-success btn-flat" onclick="do_save('uang_makan')"><i class="fa fa-floppy-o"></i> Simpan</button>
                                        </div>
                                      </form>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div> 
                        </li>
                        <li><a data-toggle="collapse" href="#jam_kerja" onclick="reload_data('jam_kerja')">Setting Jumlah jam Kerja dalam 1 bulan<span class="pull-right"><i id="icon_jam_kerja" class="fa fa-chevron-down"></i></span></a>
                          <div id="jam_kerja" class="collapse">
                            <div style="margin:15px">
                              <div class="row">
                                <div class="col-md-12">
                                  <div class="panel panel-warning">
                                    <div class="panel-body">
                                      <p>Jumlah jam Kerja dalam 1 bulan adalah <b id="jam_kerja_value">0</b></p>
                                      <form id="form_jam_kerja">
                                        <div class="form-group">
                                          <input type="hidden" value="jam_kerja">
                                          <input type="number" class="form-control" id="data_jam_kerja_edit" name="jam_kerja" placeholder="Masukkan Jumlah Jam Kerja" required="required">
                                        </div>
                                        <div class="form-group">
                                          <button type="button" class="btn btn-success btn-flat" onclick="do_save('jam_kerja')"><i class="fa fa-floppy-o"></i> Simpan</button>
                                        </div>
                                      </form>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div> 
                        </li>
                        <li><a data-toggle="collapse" href="#jam_kerja_s" onclick="reload_data('jam_kerja_s')">Setting Jumlah jam Kerja dalam 1 bulan di KANTOR KARANGJATI 2 SEMARANG<span class="pull-right"><i id="icon_jam_kerja_s" class="fa fa-chevron-down"></i></span></a>
                          <div id="jam_kerja_s" class="collapse">
                            <div style="margin:15px">
                              <div class="row">
                                <div class="col-md-12">
                                  <div class="panel panel-warning">
                                    <div class="panel-body">
                                      <p>Jumlah jam Kerja dalam 1 bulan di KANTOR KARANGJATI 2 SEMARANG adalah <b id="jam_kerja_s_value">0</b></p>
                                      <form id="form_jam_kerja_s">
                                        <div class="form-group">
                                          <input type="hidden" value="jam_kerja_s">
                                          <input type="number" class="form-control" id="data_jam_kerja_s_edit" name="jam_kerja_s" placeholder="Masukkan Jumlah Jam Kerja" required="required">
                                        </div>
                                        <div class="form-group">
                                          <button type="button" class="btn btn-success btn-flat" onclick="do_save('jam_kerja_s')"><i class="fa fa-floppy-o"></i> Simpan</button>
                                        </div>
                                      </form>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div> 
                        </li>
                        <li><a data-toggle="collapse" href="#tahun_kar" onclick="reload_data('tahun_kar')">Setting Tahun Diperlakukannya Potongan Yang Baru<span class="pull-right"><i id="icon_tahun_kar" class="fa fa-chevron-down"></i></span></a>
                          <div id="tahun_kar" class="collapse">
                            <div style="margin:15px">
                              <div class="row">
                                <div class="col-md-12">
                                  <div class="panel panel-warning">
                                    <div class="panel-body">
                                      <p>Tahun Diperlakukannya Potongan Yang Baru adalah <b id="tahun_kar_value">0</b></p>
                                      <form id="form_tahun_kar">
                                        <div class="form-group">
                                          <input type="hidden" value="tahun_kar">
                                          <input type="number" class="form-control" id="data_tahun_kar_edit" name="tahun_kar" placeholder="Masukkan Tahun" required="required">
                                        </div>
                                        <div class="form-group">
                                          <button type="button" class="btn btn-success btn-flat" onclick="do_save('tahun_kar')"><i class="fa fa-floppy-o"></i> Simpan</button>
                                        </div>
                                      </form>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div> 
                        </li>
                        <li><a data-toggle="collapse" href="#kar_new" onclick="reload_data('kar_new')">Setting Potongan Tidak Masuk Karyawan Baru<span class="pull-right"><i id="icon_kar_new" class="fa fa-chevron-down"></i></span></a>
                          <div id="kar_new" class="collapse">
                            <div style="margin:15px">
                              <div class="row">
                                <div class="col-md-12">
                                  <div class="panel panel-warning">
                                    <div class="panel-body">
                                      <p>Setting Pembagi Potongan Tidak Masuk Karyawan Baru Per Hari (Gaji Pokok/<b id="kar_new_value"></b>) </p>
                                      <form id="form_kar_new">
                                        <div class="form-group">
                                          <input type="hidden" value="kar_new">
                                          <input type="number" class="form-control" id="data_kar_new_edit" name="kar_new" placeholder="Masukkan Jumlah Jam Kerja" required="required">
                                        </div>
                                        <div class="form-group">
                                          <button type="button" class="btn btn-success btn-flat" onclick="do_save('kar_new')"><i class="fa fa-floppy-o"></i> Simpan</button>
                                        </div>
                                      </form>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div> 
                        </li>
                        <li><a data-toggle="collapse" href="#kar_old" onclick="reload_data('kar_old')">Setting Potongan Tidak Masuk Karyawan Lama<span class="pull-right"><i id="icon_kar_old" class="fa fa-chevron-down"></i></span></a>
                          <div id="kar_old" class="collapse">
                            <div style="margin:15px">
                              <div class="row">
                                <div class="col-md-12">
                                  <div class="panel panel-warning">
                                    <div class="panel-body">
                                      <p>Setting Pembagi Potongan Tidak Masuk Karyawan Baru Per Hari (Gaji Pokok/<b id="kar_old_value"></b>) </p>
                                      <form id="form_kar_old">
                                        <div class="form-group">
                                          <input type="hidden" value="kar_old">
                                          <input type="number" class="form-control" id="data_kar_old_edit" name="kar_old" placeholder="Masukkan Jumlah Jam Kerja" required="required">
                                        </div>
                                        <div class="form-group">
                                          <button type="button" class="btn btn-success btn-flat" onclick="do_save('kar_old')"><i class="fa fa-floppy-o"></i> Simpan</button>
                                        </div>
                                      </form>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div> 
                        </li>
                        <li><a data-toggle="collapse" href="#umur_pensiun" onclick="reload_data('umur_pensiun')">Setting Umur Pensiun Karyawan<span class="pull-right"><i id="icon_umur_pensiun" class="fa fa-chevron-down"></i></span></a>
                          <div id="umur_pensiun" class="collapse">
                            <div style="margin:15px">
                              <div class="row">
                                <div class="col-md-12">
                                  <div class="panel panel-warning">
                                    <div class="panel-body">
                                      <p>Setting Umur Pensiun Karyawan <b id="umur_pensiun_value"></b> Tahun </p>
                                      <form id="form_umur_pensiun">
                                        <div class="form-group">
                                          <input type="hidden" value="umur_pensiun">
                                          <input type="number" step="1" class="form-control" id="data_umur_pensiun_edit" name="umur_pensiun" placeholder="Masukkan UMUR Pensiun" required="required">
                                        </div>
                                        <div class="form-group">
                                          <button type="button" class="btn btn-success btn-flat" onclick="do_save('umur_pensiun')"><i class="fa fa-floppy-o"></i> Simpan</button>
                                        </div>
                                      </form>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div> 
                        </li>
                        <li><a data-toggle="collapse" href="#potongan_terlambat" onclick="reload_data('potongan_terlambat')">Setting Apakah terlambat potong upah (1=ya, 0=tidak)<span class="pull-right"><i id="icon_potongan_terlambat" class="fa fa-chevron-down"></i></span></a>
                          <div id="potongan_terlambat" class="collapse">
                            <div style="margin:15px">
                              <div class="row">
                                <div class="col-md-12">
                                  <div class="panel panel-warning">
                                    <div class="panel-body">
                                      <p>Setting Apakah terlambat potong upah (1=ya, 0=tidak) <b id="potongan_terlambat_value"></b> Tahun </p>
                                      <form id="form_potongan_terlambat">
                                        <div class="form-group">
                                          <input type="hidden" value="potongan_terlambat">
                                          <input type="number" step="1" min="0" max="1" class="form-control" id="data_potongan_terlambat_edit" name="potongan_terlambat" placeholder="Masukkan 1 untuk ya, 0 untuk tidak" required="required">
                                        </div>
                                        <div class="form-group">
                                          <button type="button" class="btn btn-success btn-flat" onclick="do_save('potongan_terlambat')"><i class="fa fa-floppy-o"></i> Simpan</button>
                                        </div>
                                      </form>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div> 
                        </li>
                        <!-- <li><a data-toggle="collapse" href="#non_wfh" onclick="reload_data('non_wfh')">Persentase Potongan Gaji Karyawan Non WFH<span class="pull-right"><i id="icon_non_wfh" class="fa fa-chevron-down"></i></span></a>
                          <div id="non_wfh" class="collapse">
                            <div style="margin:15px">
                              <div class="row">
                                <div class="col-md-12">
                                  <div class="panel panel-warning">
                                    <div class="panel-body">
                                      <p>Gaji yang didapat karyawan dikurangi <b id="non_wfh_value"></b>% dari Gaji yang didapat </p>
                                      <form id="form_non_wfh">
                                        <div class="form-group">
                                          <input type="hidden" value="non_wfh">
                                          <input type="number" class="form-control" id="data_non_wfh_edit" name="non_wfh" placeholder="Masukkan Jumlah Jam Kerja" required="required">
                                        </div>
                                        <div class="form-group">
                                          <button type="button" class="btn btn-success btn-flat" onclick="do_save('non_wfh')"><i class="fa fa-floppy-o"></i> Simpan</button>
                                        </div>
                                      </form>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div> 
                        </li>
                        <li><a data-toggle="collapse" href="#wfh" onclick="reload_data('wfh')">Persentase Potongan Gaji Karyawan WFH<span class="pull-right"><i id="icon_wfh" class="fa fa-chevron-down"></i></span></a>
                          <div id="wfh" class="collapse">
                            <div style="margin:15px">
                              <div class="row">
                                <div class="col-md-12">
                                  <div class="panel panel-warning">
                                    <div class="panel-body">
                                      <p>Gaji yang didapat karyawan dikurangi <b id="wfh_value"></b>% dari Gaji yang didapat </p>
                                      <form id="form_wfh">
                                        <div class="form-group">
                                          <input type="hidden" value="wfh">
                                          <input type="number" class="form-control" id="data_wfh_edit" name="wfh" placeholder="Masukkan Jumlah Jam Kerja" required="required">
                                        </div>
                                        <div class="form-group">
                                          <button type="button" class="btn btn-success btn-flat" onclick="do_save('wfh')"><i class="fa fa-floppy-o"></i> Simpan</button>
                                        </div>
                                      </form>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div> 
                        </li>
                        <li><a data-toggle="collapse" href="#minp" onclick="reload_data('minp')">Besar Pesangon Minimal dikenakan PPh<span class="pull-right"><i id="icon_minp" class="fa fa-chevron-down"></i></span></a>
                          <div id="minp" class="collapse">
                            <div style="margin:15px">
                              <div class="row">
                                <div class="col-md-12">
                                  <div class="panel panel-warning">
                                    <div class="panel-body">
                                      <p>Nilai minimal untuk Pesangon dikenakan PPh sebesar <b id="minp_value"></b></p>
                                      <form id="form_minp">
                                        <div class="form-group">
                                          <input type="hidden" value="minp">
                                          <input type="text" class="form-control input-money" id="data_minp_edit" name="minp" placeholder="Masukkan Jumlah Jam Kerja" required="required">
                                        </div>
                                        <div class="form-group">
                                          <button type="button" class="btn btn-success btn-flat" onclick="do_save('minp')"><i class="fa fa-floppy-o"></i> Simpan</button>
                                        </div>
                                      </form>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div> 
                        </li>
                        <li><a data-toggle="collapse" href="#npwpp" onclick="reload_data('npwpp')">Persentase Potongan PPh Pesangon dengan NPWP<span class="pull-right"><i id="icon_npwpp" class="fa fa-chevron-down"></i></span></a>
                          <div id="npwpp" class="collapse">
                            <div style="margin:15px">
                              <div class="row">
                                <div class="col-md-12">
                                  <div class="panel panel-warning">
                                    <div class="panel-body">
                                      <p>Pesangon yang didapat karyawan dikurangi PPh dengan NPWP sebesar <b id="npwpp_value"></b>% dari Pesangon yang didapat </p>
                                      <form id="form_npwpp">
                                        <div class="form-group">
                                          <input type="hidden" value="npwpp">
                                          <input type="number" class="form-control" id="data_npwpp_edit" name="npwpp" placeholder="Masukkan Jumlah Jam Kerja" required="required">
                                        </div>
                                        <div class="form-group">
                                          <button type="button" class="btn btn-success btn-flat" onclick="do_save('npwpp')"><i class="fa fa-floppy-o"></i> Simpan</button>
                                        </div>
                                      </form>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div> 
                        </li>
                        <li><a data-toggle="collapse" href="#nnpwpp" onclick="reload_data('nnpwpp')">Persentase Potongan PPh Pesangon tanpa NPWP<span class="pull-right"><i id="icon_nnpwpp" class="fa fa-chevron-down"></i></span></a>
                          <div id="nnpwpp" class="collapse">
                            <div style="margin:15px">
                              <div class="row">
                                <div class="col-md-12">
                                  <div class="panel panel-warning">
                                    <div class="panel-body">
                                      <p>Pesangon yang didapat karyawan dikurangi PPh tanpa NPWP sebesar <b id="nnpwpp_value"></b>% dari Pesangon yang didapat </p>
                                      <form id="form_nnpwpp">
                                        <div class="form-group">
                                          <input type="hidden" value="nnpwpp">
                                          <input type="number" class="form-control" id="data_nnpwpp_edit" name="nnpwpp" placeholder="Masukkan Jumlah Jam Kerja" required="required">
                                        </div>
                                        <div class="form-group">
                                          <button type="button" class="btn btn-success btn-flat" onclick="do_save('nnpwpp')"><i class="fa fa-floppy-o"></i> Simpan</button>
                                        </div>
                                      </form>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div> 
                        </li> -->
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="box box-widget widget-user-2">
                    <div class="widget-user-header bg-primary">
                      <div class="widget-user-image" style="position:absolute">
                        <i class="fas fa-car fa-4x"></i>
                      </div>
                      <h3 class="widget-user-username">Setting</h3>
                      <h5 class="widget-user-desc">Setting Untuk Data Perjalanan Dinas</h5>
                    </div>
                    <div class="box-footer no-padding">
                      <ul class="nav nav-stacked">
                        <li><a data-toggle="collapse" href="#jarak_min" onclick="reload_data('jarak_min')">Setting Jarak Minimal untuk memperoleh Uang Saku & Uang Perjalanan Dinas<span class="pull-right"><i id="icon_jarak_min" class="fa fa-chevron-down"></i></span></a>
                          <div id="jarak_min" class="collapse">
                            <div style="margin:15px">
                              <div class="row">
                                <div class="col-md-12">
                                  <div class="panel panel-warning">
                                    <div class="panel-body">
                                      <p>Jarak Minimal untuk memperoleh Uang Saku & Uang Perjalanan Dinas adalah <b id="jarak_min_value">0</b></p>
                                      <form id="form_jarak_min">
                                        <div class="form-group">
                                          <input type="hidden" value="jarak_min">
                                          <input type="text" class="form-control" id="data_jarak_min_edit" name="jarak_min" placeholder="Masukkan Jarak Minimal" required="required">
                                        </div>
                                        <div class="form-group">
                                          <button type="button" class="btn btn-success btn-flat" onclick="do_save('jarak_min')"><i class="fa fa-floppy-o"></i> Simpan</button>
                                        </div>
                                      </form>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div> 
                        </li>
                      </ul>
                      <ul class="nav nav-stacked">
                        <li><a data-toggle="collapse" href="#jarak_min_non" onclick="reload_data('jarak_min_non')">Setting Jarak Minimal untuk memperoleh Uang Saku & Uang Perjalanan Dinas Non Plant<span class="pull-right"><i id="icon_jarak_min_non" class="fa fa-chevron-down"></i></span></a>
                          <div id="jarak_min_non" class="collapse">
                            <div style="margin:15px">
                              <div class="row">
                                <div class="col-md-12">
                                  <div class="panel panel-warning">
                                    <div class="panel-body">
                                      <p>Jarak Minimal untuk memperoleh Uang Saku & Uang Perjalanan Dinas Non Plant adalah <b id="jarak_min_non_value">0</b></p>
                                      <form id="form_jarak_min_non">
                                        <div class="form-group">
                                          <input type="hidden" value="jarak_min_non">
                                          <input type="text" class="form-control" id="data_jarak_min_non_edit" name="jarak_min_non" placeholder="Masukkan Jarak Minimal" required="required">
                                        </div>
                                        <div class="form-group">
                                          <button type="button" class="btn btn-success btn-flat" onclick="do_save('jarak_min_non')"><i class="fa fa-floppy-o"></i> Simpan</button>
                                        </div>
                                      </form>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div> 
                        </li>
                      </ul>
                      <ul class="nav nav-stacked">
                        <li><a data-toggle="collapse" href="#jarak_min_ibp" onclick="reload_data('jarak_min_ibp')">Jarak Minimal Insentif Bantuan Plant / non Plant<span class="pull-right"><i id="icon_jarak_min_ibp" class="fa fa-chevron-down"></i></span></a>
                          <div id="jarak_min_ibp" class="collapse">
                            <div style="margin:15px">
                              <div class="row">
                                <div class="col-md-12">
                                  <div class="panel panel-warning">
                                    <div class="panel-body">
                                      <p>Jarak Minimal Insentif Bantuan Plant / non Plant adalah <b id="jarak_min_ibp_value">0</b></p>
                                      <form id="form_jarak_min_ibp">
                                        <div class="form-group">
                                          <input type="hidden" value="jarak_min_ibp">
                                          <input type="text" class="form-control" id="data_jarak_min_ibp_edit" name="jarak_min_ibp" placeholder="Masukkan Jarak Minimal" required="required">
                                        </div>
                                        <div class="form-group">
                                          <button type="button" class="btn btn-success btn-flat" onclick="do_save('jarak_min_ibp')"><i class="fa fa-floppy-o"></i> Simpan</button>
                                        </div>
                                      </form>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div> 
                        </li>
                      </ul>
                      <ul class="nav nav-stacked">
                        <li><a data-toggle="collapse" href="#jarak_min_storing" onclick="reload_data('jarak_min_storing')">Jarak Minimal Insentif Storing<span class="pull-right"><i id="icon_jarak_min_storing" class="fa fa-chevron-down"></i></span></a>
                          <div id="jarak_min_storing" class="collapse">
                            <div style="margin:15px">
                              <div class="row">
                                <div class="col-md-12">
                                  <div class="panel panel-warning">
                                    <div class="panel-body">
                                      <p>Jarak Minimal Insentif Storing adalah <b id="jarak_min_storing_value">0</b></p>
                                      <form id="form_jarak_min_storing">
                                        <div class="form-group">
                                          <input type="hidden" value="jarak_min_storing">
                                          <input type="text" class="form-control" id="data_jarak_min_storing_edit" name="jarak_min_storing" placeholder="Masukkan Jarak Minimal" required="required">
                                        </div>
                                        <div class="form-group">
                                          <button type="button" class="btn btn-success btn-flat" onclick="do_save('jarak_min_storing')"><i class="fa fa-floppy-o"></i> Simpan</button>
                                        </div>
                                      </form>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div> 
                        </li>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="box box-widget widget-user-2">
                    <div class="widget-user-header bg-green">
                      <div class="widget-user-image" style="position:absolute">
                        <i class="fas fa-calendar-times fa-4x"></i>
                      </div>
                      <h3 class="widget-user-username">Setting</h3>
                      <h5 class="widget-user-desc">Setting Untuk Data Izin Cuti</h5>
                    </div>
                    <div class="box-footer no-padding">
                      <ul class="nav nav-stacked">
                        <li><a data-toggle="collapse" href="#hariMinCuti" onclick="reload_data('hariMinCuti')">Setting Minimal hari untuk mengajukan CUTI Tahunan Berikutnya<span class="pull-right"><i id="icon_hariMinCuti" class="fa fa-chevron-down"></i></span></a>
                          <div id="hariMinCuti" class="collapse">
                            <div style="margin:15px">
                              <div class="row">
                                <div class="col-md-12">
                                  <div class="panel panel-warning">
                                    <div class="panel-body">
                                      <p>Minimal hari untuk mengajukan CUTI Tahunan Berikutnya adalah <b id="hariMinCuti_value">0</b></p>
                                      <form id="form_hariMinCuti">
                                        <div class="form-group">
                                          <input type="hidden" value="hariMinCuti">
                                          <input type="text" class="form-control" id="data_hariMinCuti_edit" name="hariMinCuti" placeholder="Masukkan Jumlah Hari" required="required">
                                        </div>
                                        <div class="form-group">
                                          <button type="button" class="btn btn-success btn-flat" onclick="do_save('hariMinCuti')"><i class="fa fa-floppy-o"></i> Simpan</button>
                                        </div>
                                      </form>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div> 
                        </li>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section> 
</div>
<script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
<script type="text/javascript">
  function reload_data(id) {
    var data={id:id};
    var callback=getAjaxData("<?php echo base_url()?>master/setting_"+id+"/view_one",data); 
    $('#'+id+'_value').html(callback['plain']);
    $('#data_'+id+'_edit').val(callback['value']);
    if (id == 'rankup_max_work_time') {
      $('input[name=masa_kerja_satuan][value='+callback['value_secondary']+']').icheck('checked'); 
    }
    $('#'+id).on('shown.bs.collapse', function() {
      $("#icon_"+id).addClass('fa-chevron-up').removeClass('fa-chevron-down');
    });
    $('#'+id).on('hidden.bs.collapse', function() {
      $("#icon_"+id).addClass('fa-chevron-down').removeClass('fa-chevron-up');
    });
  }
  function do_save(id){
    if($("#form_"+id)[0].checkValidity()) {
      submitAjax("<?php echo base_url()?>master/setting_"+id+"_edit",null,'form_'+id,null,null);
      reload_data(id);
    }else{
      notValidParamx();
    } 
  }
</script>