<style type="text/css">
.col-sm-9{
   padding-right: 0px;
}
</style>
<div class="content-wrapper">
   <?php 
   if (!empty($this->session->flashdata('msgsc'))) {
      echo '<div class="alert alert-success" id="alert-success">'.$this->session->flashdata('msgsc').'</div>';
   }elseif (!empty($this->session->flashdata('msgerr'))) {
      echo '<div class="alert alert-danger" id="alert-success">'.$this->session->flashdata('msgerr').'</div>';
   }
   if($skin=='dark-mode'){
      $font = 'white';
   }else{
      $font = 'black';
   } ?>
   <section class="content-header">
      <h1>
         <a class="back" href="<?php echo base_url('pages/data_karyawan'); ?>"><i class="fa fa-chevron-circle-left "></i></a>
         <i class="fa fa-user"></i> Tambah
         <small>Karyawan</small>
      </h1>
      <ol class="breadcrumb">
         <li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
         <li><a href="<?php echo base_url('pages/data_karyawan');?>"><i class="fa fa-users"></i> Data Karyawan</a></li>
         <li class="active">Tambah Karyawan</li>
      </ol>
   </section>
   <section class="content">
      <div class="row">
         <div class="col-md-12">
            <div class="box box-success">
               <div class="box-header with-border">
                  <h3 class="box-title"><i class="fa fa-user"></i> Tambah Karyawan</h3>
               </div>
               <form id="form_add">
                  <div class="box-body">
                     <div class="col-md-6">
                        <div class="row">
                           <div class="col-md-3">
                              <p class="text-center text-blue" style="font-size: 9pt;"><i class="fa fa-info-circle"></i> Foto Ideal 3x3</p>
                              <div id="frame_foto">
                                 <img id="fotok" class="profile-user-img img-responsive img-circle" src="<?php echo base_url('asset/img/user-photo/user.png');?>" alt="Foto" />
                              </div>
                              <div class="form-group clearfix">
                                 <span class="input-group-btn"> 
                                    <div class="fileUpload btn btn-warning btn-flat btn-xs btn-block">
                                       <span><i class="fa fa-folder-open"></i> Pilih Foto</span>
                                       <input id="uploadBtn1" type="file" onchange="checkFile('#uploadBtn1',null,null,event)" class="upload" name="foto"/>
                                    </div>
                                    <button id="resf" type="button" class="btn btn-block btn-xs btn-danger" onclick="rest_foto()"><i class="fa fa-camera"></i> Foto Default</button>
                                 </span>
                              </div>
                           </div>
                           <div class="col-md-9">
                              <div class="form-group clearfix">
                                 <label for="id_finger" id="id_finger_label" class="col-sm-3 control-label">ID Finger</label>
                                 <div class="col-sm-9">
                                    <input type="text" name="id_finger" id="id_finger" class="form-control" placeholder="ID Finger Karyawan" onblur="cekfinger(this.value)" required="required">
                                    <div class="text-danger" id="notif_finger" style="font-size: 9pt;"></div>
                                 </div>
                              </div>
                              <div class="form-group clearfix">
                                 <label for="nik" class="col-sm-3 control-label">NIK</label>
                                 <div class="col-sm-9">
                                    <input type="text" name="nik" id="nik" class="form-control" placeholder="Nomor Induk Karyawan" required="required" readonly="readonly" data-toggle="tooltip" title="NIK akan otomatis terisi berdasarkan tanggal lahir dan tanggal masuk karyawan">
                                    <span id="errmsg"></span>
                                 </div>
                              </div>
                              <div class="form-group clearfix">
                                 <label for="nama" class="col-sm-3 control-label">Nama</label>
                                 <div class="col-sm-9">
                                    <input type="text" class="form-control" name="nama" placeholder="Nama Lengkap Karyawan" required="required">
                                 </div>
                              </div>
                              <div class="form-group clearfix">
                                 <label for="nama" class="col-sm-3 control-label">Nomor Id</label>
                                 <div class="col-sm-9">
                                    <input type="number" name="no_id" class="form-control" placeholder="Nomor Identitas" required="required">
                                    <div class="has-feedback">
                                       <span class="fa fa-calendar form-control-feedback"></span>
                                       <input type="text" name="tgl_berlaku" class="form-control pull-right date-picker" placeholder="Tanggal Berlaku">
                                    </div><br><br>
                                    <div>
                                       <label style="vertical-align: middle;">
                                          <span id="berlaku_slm_off" style="font-size: 20px;"><i class="far fa-square" aria-hidden="true"></i></span>
                                          <span id="berlaku_slm_on" style="display: none; font-size: 20px;"><i class="far fa-check-square" aria-hidden="true"></i></span> <span style="padding-bottom: 9px;vertical-align: middle;"><b>Berlaku Selamanya</b></span>
                                          <input type="hidden" name="berlaku_slm">
                                       </label>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div class="form-group clearfix">
                           <label for="nama" class="col-sm-3 control-label">Jenis Kelamin</label>
                           <div class="col-sm-9">
                              <select class="form-control select2" id="kelamin" name="kelamin" required="required">
                                 <option></option>
                                 <?php
                                 foreach ($gender as $g => $vg) { ?>
                                    <option value="<?php echo $g; ?>"><?php echo $vg; ?></option>
                                 <?php } ?>
                              </select>
                           </div>
                        </div>
                        <div class="form-group clearfix">
                           <label for="nama" class="col-sm-3 control-label">Tempat, Tanggal Lahir</label>
                           <div class="col-sm-9">
                              <input type="text" class="form-control" name="tempat_lahir" placeholder="Tempat Lahir" required="required">
                              <div class="has-feedback">
                                 <span class="fa fa-calendar form-control-feedback"></span>
                                 <input type="text" name="tgl_lahir" id="tgl_lahir" class="form-control pull-right date-picker" placeholder="Tanggal Lahir" required="required">
                              </div>
                           </div>
                        </div>
                        <div class="form-group clearfix">
                           <label for="email" class="col-sm-3 control-label">Email</label>
                           <div class="col-sm-9">
                              <input type="email" class="form-control" name="email" placeholder="Email Karyawan">
                           </div>
                        </div>
                        <div class="form-group clearfix">
                           <label for="nama" class="col-sm-3 control-label">Agama</label>
                           <div class="col-sm-9">
                              <select class="form-control select2" id="agama" name="agama" required="required">
                                 <option></option>
                                 <?php
                                 foreach ($agama as $g => $vg) { ?>
                                    <option value="<?php echo $g; ?>"><?php echo $vg; ?></option>
                                 <?php } ?>
                              </select>
                           </div>
                        </div>
                        <div class="form-group clearfix">
                           <label for="nama" class="col-sm-3 control-label">Status Pernikahan</label>
                           <div class="col-sm-9">
                              <?php
                              $nikah[null] = 'Pilih Data';
                              $sel3 = [null];
                              $exsel3 = array('class'=>'form-control select2','placeholder'=>'Status Nikah','id'=>'status_nikah','required'=>'required','style'=>'width:100%');
                              echo form_dropdown('nikah',$nikah,$sel3,$exsel3);
                              ?>
                           </div>
                        </div>
                        <div class="form-group clearfix">
                           <label for="nama" class="col-sm-3 control-label">Golongan Darah</label>
                           <div class="col-sm-9">
                              <?php
                              $darah[null] = 'Pilih Data';
                              $sel4 = array(NULL);
                              $exsel4 = array('class'=>'form-control select2','placeholder'=>'Golongan Darah','id'=>'darah','style'=>'width:100%');
                              echo form_dropdown('darah',$darah,$sel4,$exsel4); ?>
                           </div>
                        </div>
                        <div class="form-group clearfix">
                           <label for="nama" class="col-sm-3 control-label">Status Pajak</label>
                           <div class="col-sm-9">
                              <?php
                              $status_pajak[null] = 'Pilih Data';
                              $sel2 = [null];
                              $exsel2 = array('class'=>'form-control select2','placeholder'=>'Status pajak','id'=>'status_pajak','required'=>'required','style'=>'width:100%');
                              echo form_dropdown('status_pajak',$status_pajak,$sel2,$exsel2);
                              ?>
                           </div>
                        </div>
                     </div>
                     <div class="col-md-6">
                        <fieldset>
                           <legend style="color: <?php echo $font; ?>;"><i class="fa fa-fw fa-book"></i> Nomor Penting</legend>
                           <div class="form-group clearfix">
                              <label class="col-sm-3 control-label">Handphone</label>
                              <div class="col-sm-9">
                                 <input type="text" pattern="\d*" maxlength="15" class="form-control" name="no_hp" placeholder="Nomor HP Karyawan">
                              </div>
                           </div>
                           <div class="form-group clearfix">
                              <label class="col-sm-3 control-label">NPWP</label>
                              <div class="col-sm-9">
                                 <input type="text" maxlength="20" class="form-control" name="no_npwp" placeholder="Nomor NPWP Karyawan">
                              </div>
                           </div>
                           <div class="form-group clearfix">
                              <label class="col-sm-3 control-label">BPJS-TK</label>
                              <div class="col-sm-9">
                                 <input type="text" maxlength="15" class="form-control" name="no_bpjstk" placeholder="Nomor BPJS Tenaga Kerja">
                              </div>
                           </div>
                           <div class="form-group clearfix">
                              <label class="col-sm-3 control-label">BPJS-KES</label>
                              <div class="col-sm-9">
                                 <input type="text" maxlength="15" class="form-control" name="no_bpjskes" placeholder="Nomor BPJS Kesehatan">
                              </div>
                           </div>
                        </fieldset>
                        <fieldset>
                           <legend style="color: <?php echo $font; ?>;"><i class="fas fa-fw fa-briefcase"></i>Jabatan</legend>
                           <div class="form-group clearfix">
                              <label class="col-sm-3 control-label">Tanggal Masuk</label>
                              <div class="col-sm-9">
                                 <div class="has-feedback">
                                    <span class="fa fa-calendar form-control-feedback"></span>
                                    <input type="text" name="tgl_masuk" id="tgl_masuk" class="form-control pull-right date-picker" placeholder="Tanggal Masuk" required="required">
                                 </div>
                              </div>
                           </div>
                           <div class="form-group clearfix">
                              <label class="col-sm-3 control-label">Jabatan</label>
                              <div class="col-sm-9">
                                 <select class="form-control select2" id="jabatan" name="jabatan" style="width: 100%;" required="required"></select>
                              </div>
                           </div>
                           <div class="form-group clearfix">
                              <label class="col-sm-3 control-label"></label>
                              <div class="col-sm-9">
                                 <label style="vertical-align: middle;">
                                    <span id="jabSecond_off" style="font-size: 20px;" onclick="jabSecond();"><i class="far fa-square" aria-hidden="true"></i></span>
                                    <span id="jabSecond_on" style="display: none; font-size: 20px;" onclick="jabSecond();"><i class="far fa-check-square" aria-hidden="true"></i></span>
                                    <span style="padding-bottom: 9px;vertical-align: middle;"><b> Ceklist Jika ada Jabatan Lain</b></span>
                                    <input type="hidden" name="jabatan_second">
                                 </label>
                              </div>
                           </div>
                           <div class="form-group" id="div_jabatan_sekunder" style="display:none;">
                              <div class="col md-12">
                                 <label class="col-sm-3 control-label">Pilih Jabatan</label>
                                 <div class="col-sm-9">
                                    <select name="jabatan_sekunder[]" id="jabatan_sekunder" multiple="multiple" class="form-control select2" style="width: 100%;"></select>
                                 </div>
                              </div>
                           </div>
                           <div class="form-group clearfix">
                              <label class="col-sm-3 control-label">Lokasi kerja</label>
                              <div class="col-sm-9">
                                 <select class="form-control select2" id="loker" name="loker" style="width: 100%;" required="required"></select>
                              </div>
                           </div>
                           <div class="form-group clearfix">
                              <label class="col-sm-3 control-label">Grade</label>
                              <div class="col-sm-9">
                                 <select class="form-control select2" id="grade" name="grade" style="width: 100%;"></select>
                              </div>
                           </div>
                           <div class="form-group clearfix">
                              <label class="col-sm-3 control-label">Sistem Penggajian</label>
                              <div class="col-sm-9">
                               <select class="form-control select2" id="sistem_penggajian" name="sistem_penggajian" style="width: 100%;" required="required"></select>
                              </div>
                           </div>
                           <div class="form-group clearfix">
                              <label class="col-sm-3 control-label">Perjanjian Karyawan</label>
                              <div class="col-sm-9">
                                 <select class="form-control select2" id="perjanjian_kerja" name="status_perjanjian" onchange="cekKodePerjanjian(this.value)" style="width: 100%;" required="required">
                                    <option></option>
                                 </select>
                              </div>
                           </div>
                           <div class="form-group clearfix">
                              <label class="col-sm-3 control-label">Status Karyawan</label>
                              <div class="col-sm-9">
                                 <select class="form-control select2" id="status_karyawan" name="status_karyawan" style="width: 100%;" required="required"></select>
                              </div>
                           </div>
                           <!-- <div class="form-group clearfix">
                              <label class="col-sm-3 control-label">Berlaku Sampai</label>
                              <div class="col-sm-9">
                                 <div class="has-feedback">
                                    <span class="fa fa-calendar form-control-feedback"></span>
                                    <input type="text" name="berlaku_sampai" id="berlaku_sampai_add" class="form-control pull-right date-picker" placeholder="Tanggal Berlaku" required="required">
                                 </div>
                              </div>
                           </div> -->
                           <input type="hidden" name="berlaku_sampai" id="berlaku_sampai_add">
                           <input type="hidden" name="no_sk_baru" id="no_sk_baru_add" value="<?php echo $this->codegenerator->kodePerjanjianKerja(); ?>">
                        </fieldset>
                     </div>
                     <div class="row">
                        <div class="col-md-12">
                           <fieldset>
                              <legend class="legend"><i class="far fa-credit-card"></i> Presensi dan Penggajian</legend>
                                 <div class="row">
                                    <div class="col-md-12">
                                       <div class="col-md-6">
                                          <div class="row form-box-group">
                                             <span class="form-box-group-title">Detail Jadwal Kerja</span>
                                             <div class="col-md-12">
                                                <div class="form-group">
                                                   <label>Shift</label>
                                                   <select class="form-control select2" name="kode_master_shift[]" id="kode_master_shift_add" multiple="multiple" style="width: 100%;" required="required"></select>
                                                </div>
                                                <div class="form-group clearfix">
                                                   <label>Tanggal Berlaku</label>
                                                   <div class="has-feedback">
                                                      <span class="fa fa-calendar form-control-feedback"></span>
                                                      <input type="text" name="tgl_berlaku_shift" id="data_tgl_berlaku_add" class="form-control pull-right date-range-notime" placeholder="Tanggal Berlaku" required="required" readonly>
                                                   </div>
                                                </div>
                                             </div>
                                          </div>
                                       </div>
                                       <div class="col-md-6">
                                          <div class="row form-box-group">
                                             <span class="form-box-group-title">Detail Penggajian, Lembur, dan PPh</span>
                                             <div class="col-md-12">
                                                <div class="form-group clearfix">
                                                   <label>Gaji Pokok</label>
                                                      <?php
                                                      $gaji_pokok[null] = 'Pilih Data';
                                                      // $gaji_pokok[] = $this->otherfunctions->getJenisGajiList();
                                                      $sel8 = array(NULL);
                                                      $exsel8 = array('class'=>'form-control select2','placeholder'=>'Gaji Pokok','id'=>'gaji_pokok','onchange'=>'cekgaji(this.value)','style'=>'width:100%');
                                                      echo form_dropdown('gaji_pokok',$gaji_pokok,$sel8,$exsel8);
                                                      ?>
                                                </div>
                                                <div class="form-group clearfix" id="shw_lok">
                                                   <label>Besaran Gaji</label>
                                                      <input type="text" name="besaran_gaji" id="besaran_gaji" class="form-control input-money" placeholder="Masukkan Besaran Gaji" value="" readonly="readonly">
                                                </div>
                                                <div class="form-group">
                                                   <label>Petugas Payroll</label>
                                                   <select class="form-control select2" name="petugas_payroll[]" multiple="multiple" id="petugas_payroll_add" style="width: 100%;" required="required"></select>
                                                </div>
                                                <div class="form-group">
                                                   <label>Petugas Lembur</label>
                                                   <select class="form-control select2" name="petugas_lembur[]" multiple="multiple" id="petugas_lembur_add" style="width: 100%;" required="required"></select>
                                                </div>
                                                <div class="form-group">
                                                   <label>Petugas PPH</label>
                                                   <select class="form-control select2" name="petugas_pph[]" multiple="multiple" id="petugas_pph_add" style="width: 100%;" required="required"></select>
                                                </div>
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                 </div>
                           </fieldset>
                        </div>
                     </div><hr>
                     <div class="row">
                        <div class="col-md-12">
                           <fieldset>
                              <legend class="legend"><i class="fa fa-info-circle"></i> Informasi Tambahan</legend>
                              <div class="nav-tabs-custom">
                                 <ul class="nav nav-tabs">
                                    <li class="active"><a href="#me" data-toggle="tab">Data Pribadi</a></li>
                                    <li><a href="#family" data-toggle="tab">Data Keluarga</a></li>
                                    <li><a href="#edu" data-toggle="tab">Pendidikan</a></li>
                                    <li><a href="#acv" data-toggle="tab">Penghargaan</a></li>
                                    <li><a href="#org" data-toggle="tab">Organisasi</a></li>
                                    <li><a href="#lang" data-toggle="tab">Bahasa</a></li>
                                 </ul>
                                 <div class="tab-content">
                                    <div class="tab-pane active" id="me">
                                       <div class="nav-tabs-custom">
                                          <ul class="nav nav-tabs">
                                             <li class="active"><a href="#me1" data-toggle="tab">Info Umum</a></li>
                                             <li><a href="#old_address" data-toggle="tab">Alamat Asal</a></li>
                                             <li><a href="#new_address" data-toggle="tab">Alamat Sekarang</a></li>
                                          </ul>
                                          <div class="tab-content">
                                             <div class="tab-pane active" id="me1">
                                                <div class="form-group clearfix">
                                                   <label class="col-sm-3 control-label">Berat Badan</label>
                                                   <div class="col-sm-9">
                                                      <input type="number" name="berat_badan" class="form-control" min="0" max="400" placeholder="Berat Badan Karyawan">
                                                   </div>
                                                </div>
                                                <div class="form-group clearfix">
                                                   <label class="col-sm-3 control-label">Tinggi Badan</label>
                                                   <div class="col-sm-9">
                                                      <input type="number" name="tinggi_badan" class="form-control" min="0" max="250" placeholder="Tinggi Badan Karyawan">
                                                   </div>
                                                </div>
                                                <div class="form-group clearfix">
                                                   <label class="col-sm-3 control-label">Nomor Rekening</label>
                                                   <div class="col-sm-9">
                                                      <input type="text" name="rekening" pattern="\d*" maxlength="15" class="form-control" placeholder="Nomor Rekening Karyawan">
                                                   </div>
                                                </div>
                                                <div class="form-group clearfix">
                                                   <label class="col-sm-3 control-label">Bank</label>
                                                   <div class="col-sm-9">
                                                      <select class="form-control select2" id="bank" name="bank" style="width: 100%;"></select>
                                                   </div>
                                                </div>
                                                <div class="form-group clearfix">
                                                   <label for="nama" class="col-sm-3 control-label">Ukuran Baju</label>
                                                   <div class="col-sm-9">
                                                      <?php
                                                      $baju[null] = 'Pilih Data';
                                                      $sel9 = array(NULL);
                                                      $exsel9 = array('class'=>'form-control select2','placeholder'=>'Ukuran Baju','id'=>'baju','style'=>'width:100%');
                                                      echo form_dropdown('baju',$baju,$sel9,$exsel9);
                                                      ?>
                                                   </div>
                                                </div>
                                                <div class="form-group clearfix">
                                                   <label class="col-sm-3 control-label">Nomor Sepatu</label>
                                                   <div class="col-sm-9">
                                                      <input type="number" name="sepatu" max="100" class="form-control" placeholder="Nomor Sepatu Karyawan">
                                                   </div>
                                                </div>
                                                <div class="form-group clearfix">
                                                   <label for="nama" class="col-sm-3 control-label">Metode Perhitungan PPh 21</label>
                                                   <div class="col-sm-9">
                                                      <?php
                                                      $metode_pph[null] = 'Pilih Data';
                                                      $sel10 = array(NULL);
                                                      $exsel10 = array('class'=>'form-control select2','placeholder'=>'Metode Perhitungan PPh 21','id'=>'metode_pph','style'=>'width:100%');
                                                      echo form_dropdown('metode_pph',$metode_pph,$sel10,$exsel10);
                                                      ?>
                                                   </div>
                                                </div>
                                             </div>
                                             <div class="tab-pane" id="old_address">
                                                <div class="form-group clearfix">
                                                   <label class="col-sm-3 control-label">Alamat</label>
                                                   <div class="col-sm-9">
                                                      <input type="text" name="alamat_asal_jalan" class="form-control" placeholder="Alamat"  >
                                                   </div>
                                                </div>
                                                <div class="form-group clearfix">
                                                   <label class="col-sm-3 control-label">Desa/Kelurahan</label>
                                                   <div class="col-sm-9">
                                                      <input type="text" name="alamat_asal_desa" class="form-control" placeholder="Nama Desa/Kelurahan"  >
                                                   </div>
                                                </div>
                                                <div class="form-group clearfix">
                                                   <label class="col-sm-3 control-label">Kecamatan</label>
                                                   <div class="col-sm-9">
                                                      <input type="text" name="alamat_asal_kecamatan" class="form-control" placeholder="Nama Kecamatan"  >
                                                   </div>
                                                </div>
                                                <div class="form-group clearfix">
                                                   <label class="col-sm-3 control-label">Kabupaten/Kota</label>
                                                   <div class="col-sm-9">
                                                      <input type="text" name="alamat_asal_kabupaten" class="form-control" placeholder="Nama Kabupaten/Kota">
                                                   </div>
                                                </div>
                                                <div class="form-group clearfix">
                                                   <label class="col-sm-3 control-label">Provinsi</label>
                                                   <div class="col-sm-9">
                                                      <input type="text" name="alamat_asal_provinsi" class="form-control" placeholder="Nama Provinsi">
                                                   </div>
                                                </div>
                                                <div class="form-group clearfix">
                                                   <label class="col-sm-3 control-label">Kode POS</label>
                                                   <div class="col-sm-9">
                                                      <input type="number" name="alamat_asal_pos" pattern="\d*" maxlength="6" class="form-control" placeholder="Kode POS">
                                                   </div>
                                                </div>
                                             </div>
                                             <div class="tab-pane" id="new_address">
                                                <div class="form-group clearfix">
                                                   <label class="col-sm-3 control-label"></label>
                                                   <div class="col-sm-9">
                                                      <label style="vertical-align: middle;">
                                                         <span id="samakandgnasal_off" style="font-size: 20px;" onclick="SamakandgnAsal1();"><i class="far fa-square" aria-hidden="true"></i></span>
                                                         <span id="samakandgnasal_on" style="display: none; font-size: 20px;" onclick="SamakandgnAsal1();"><i class="far fa-check-square" aria-hidden="true"></i></span>
                                                         <span style="padding-bottom: 9px;vertical-align: middle;"><b> Samakan Dengan Alamat Asal</b></span>
                                                         <input type="hidden" name="alamat_baru">
                                                      </label>
                                                   </div>
                                                </div>
                                                <div class="form-group clearfix">
                                                   <label class="col-sm-3 control-label">Alamat</label>
                                                   <div class="col-sm-9">
                                                      <input type="text" name="alamat_sekarang_jalan" class="form-control" placeholder="Alamat Sekarang">
                                                   </div>
                                                </div>
                                                <div class="form-group clearfix">
                                                   <label class="col-sm-3 control-label">Desa/Kelurahan</label>
                                                   <div class="col-sm-9">
                                                      <input type="text" name="alamat_sekarang_desa" class="form-control" placeholder="Nama Desa/Kelurahan Sekarang" >
                                                   </div>
                                                </div>
                                                <div class="form-group clearfix">
                                                   <label class="col-sm-3 control-label">Kecamatan</label>
                                                   <div class="col-sm-9">
                                                      <input type="text" name="alamat_sekarang_kecamatan" class="form-control" placeholder="Nama Kecamatan Sekarang" >
                                                   </div>
                                                </div>
                                                <div class="form-group clearfix">
                                                   <label class="col-sm-3 control-label">Kabupaten/Kota</label>
                                                   <div class="col-sm-9">
                                                      <input type="text" name="alamat_sekarang_kabupaten" class="form-control" placeholder="Nama Kabupaten/Kota Sekarang" >
                                                   </div>
                                                </div>
                                                <div class="form-group clearfix">
                                                   <label class="col-sm-3 control-label">Provinsi</label>
                                                   <div class="col-sm-9">
                                                      <input type="text" name="alamat_sekarang_provinsi" class="form-control" placeholder="Nama Provinsi Sekarang" >
                                                   </div>
                                                </div>
                                                <div class="form-group clearfix">
                                                   <label class="col-sm-3 control-label">Kode POS</label>
                                                   <div class="col-sm-9">
                                                      <input type="number" name="alamat_sekarang_pos" pattern="\d*" maxlength="6" class="form-control" placeholder="Kode POS Sekarang" >
                                                   </div>
                                                </div>
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                    <div class="tab-pane" id="family">
                                       <div class="nav-tabs-custom">
                                          <ul class="nav nav-tabs">
                                             <li class="active"><a href="#father" data-toggle="tab">Data Ayah</a></li>
                                             <li><a href="#mother" data-toggle="tab">Data Ibu</a></li>
                                             <li><a href="#child" data-toggle="tab">Data Anak</a></li>
                                             <li><a href="#brother" data-toggle="tab">Data Saudara</a></li>
                                             <li><a href="#husband_wife" data-toggle="tab">Data Pasangan</a></li>
                                          </ul>
                                          <div class="tab-content">
                                             <div class="tab-pane active" id="father">
                                                <div class="form-group clearfix">
                                                   <label class="col-sm-3 control-label">Nama Ayah</label>
                                                   <div class="col-sm-9">
                                                      <input type="text" name="nama_ayah" class="form-control" placeholder="Nama Ayah">
                                                   </div>
                                                </div>
                                                <div class="form-group clearfix">
                                                   <label for="nama" class="col-sm-3 control-label">Tempat Tanggal Lahir</label>
                                                   <div class="col-sm-9">
                                                      <input type="text" class="form-control" name="tempat_lahir_ayah" placeholder="Tempat Lahir">
                                                      <div class="has-feedback">
                                                         <span class="fa fa-calendar form-control-feedback"></span>
                                                         <input type="text" name="tanggal_lahir_ayah" class="form-control pull-right date-picker" placeholder="Tanggal Lahir">
                                                      </div>
                                                   </div>
                                                </div>
                                                <div class="form-group clearfix">
                                                   <label class="col-sm-3 control-label">Nomor Telp. Ayah</label>
                                                   <div class="col-sm-9">
                                                      <input type="text" name="no_telp_ayah" maxlength="15" class="form-control" placeholder="Nomor Telpon Ayah">
                                                   </div>
                                                </div>
                                                <div class="form-group clearfix">
                                                   <label for="nama" class="col-sm-3 control-label">Pendidikan Terakhir</label>
                                                   <div class="col-sm-9">
                                                      <?php
                                                      $pendidikan[null] = 'Pilih Data';
                                                      $sel5 = array(NULL);
                                                      $exsel5 = array('class'=>'form-control select2','placeholder'=>'Pendidikan','id'=>'pendidikan_terakhir_ayah','style'=>'width:100%');
                                                      echo form_dropdown('pendidikan_terakhir_ayah',$pendidikan,$sel5,$exsel5);
                                                      ?>
                                                   </div>
                                                </div>
                                                <div class="form-group clearfix">
                                                   <label class="col-sm-3 control-label"></label>
                                                   <div class="col-sm-9">
                                                      <label style="vertical-align: middle;">
                                                         <span id="samakansaya1_off" style="font-size: 20px;" onclick="SamakandgnSaya();"><i class="far fa-square" aria-hidden="true"></i></span>
                                                         <span id="samakansaya1_on" style="display: none; font-size: 20px;" onclick="SamakandgnSaya();"><i class="far fa-check-square" aria-hidden="true"></i></span>
                                                         <span style="padding-bottom: 9px;vertical-align: middle;"><b> Samakan Dengan Alamat Saya</b></span>
                                                         <input type="hidden" name="alamat_ayahq">
                                                      </label>
                                                   </div>
                                                </div>
                                                <div class="form-group clearfix">
                                                   <label class="col-sm-3 control-label">Alamat</label>
                                                   <div class="col-sm-9">
                                                      <input type="text" name="alamat_ayah" class="form-control" placeholder="Alamat Sekarang" >
                                                   </div>
                                                </div>
                                                <div class="form-group clearfix">
                                                   <label class="col-sm-3 control-label">Desa/Kelurahan</label>
                                                   <div class="col-sm-9">
                                                      <input type="text" name="desa_ayah" class="form-control" placeholder="Nama Desa/Kelurahan Sekarang" >
                                                   </div>
                                                </div>
                                                <div class="form-group clearfix">
                                                   <label class="col-sm-3 control-label">Kecamatan</label>
                                                   <div class="col-sm-9">
                                                      <input type="text" name="kecamatan_ayah" class="form-control" placeholder="Nama Kecamatan Sekarang" >
                                                   </div>
                                                </div>
                                                <div class="form-group clearfix">
                                                   <label class="col-sm-3 control-label">Kabupaten/Kota</label>
                                                   <div class="col-sm-9">
                                                      <input type="text" name="kabupaten_ayah" class="form-control" placeholder="Nama Kabupaten/Kota Sekarang" >
                                                   </div>
                                                </div>
                                                <div class="form-group clearfix">
                                                   <label class="col-sm-3 control-label">Provinsi</label>
                                                   <div class="col-sm-9">
                                                      <input type="text" name="provinsi_ayah" class="form-control" placeholder="Nama Provinsi Sekarang" >
                                                   </div>
                                                </div>
                                                <div class="form-group clearfix">
                                                   <label class="col-sm-3 control-label">Kode POS</label>
                                                   <div class="col-sm-9">
                                                      <input type="number" name="kode_pos_ayah" pattern="\d*" maxlength="6" class="form-control" placeholder="Kode POS">
                                                   </div>
                                                </div>
                                             </div>
                                             <div class="tab-pane" id="mother">
                                                <div class="form-group clearfix">
                                                   <label class="col-sm-3 control-label">Nama Ibu</label>
                                                   <div class="col-sm-9">
                                                      <input type="text" name="nama_ibu" class="form-control" placeholder="Nama Ibu"  >
                                                   </div>
                                                </div>
                                                <div class="form-group clearfix">
                                                   <label for="nama" class="col-sm-3 control-label">Tempat Tanggal Lahir</label>
                                                   <div class="col-sm-9">
                                                      <input type="text" class="form-control" name="tempat_lahir_ibu" placeholder="Tempat Lahir">
                                                      <div class="has-feedback">
                                                         <span class="fa fa-calendar form-control-feedback"></span>
                                                         <input type="text" name="tanggal_lahir_ibu" class="date-picker form-control pull-right" id="date1" placeholder="Tanggal Lahir">
                                                      </div>
                                                   </div>
                                                </div>
                                                <div class="form-group clearfix">
                                                   <label class="col-sm-3 control-label">Nomor Telp. Ibu</label>
                                                   <div class="col-sm-9">
                                                      <input type="text" name="no_telp_ibu" maxlength="15" class="form-control" placeholder="Nomor Telpon Ibu">
                                                   </div>
                                                </div>
                                                <div class="form-group clearfix">
                                                   <label for="nama" class="col-sm-3 control-label">Pendidikan Terakhir</label>
                                                   <div class="col-sm-9">
                                                      <?php
                                                      $pendidikan[null] = 'Pilih Data';
                                                      $sel6 = array(NULL);
                                                      $exsel6 = array('class'=>'form-control select2','placeholder'=>'Pendidikan','id'=>'pendidikan_terakhir_ibu','style'=>'width:100%');
                                                      echo form_dropdown('pendidikan_terakhir_ibu',$pendidikan,$sel6,$exsel6);
                                                      ?>
                                                   </div>
                                                </div>
                                                <div class="form-group clearfix">
                                                   <label class="col-sm-3 control-label"></label>
                                                   <div class="col-sm-9">
                                                      <label style="vertical-align: middle;">
                                                         <span id="samakansaya2_off" style="font-size: 20px;" onclick="SamakandgnSaya2();"><i class="far fa-square" aria-hidden="true"></i></span>
                                                         <span id="samakansaya2_on" style="display: none; font-size: 20px;" onclick="SamakandgnSaya2();"><i class="far fa-check-square" aria-hidden="true"></i></span>
                                                         <span style="padding-bottom: 9px;vertical-align: middle;"><b> Samakan Dengan Alamat Saya</b></span>
                                                         <input type="hidden" name="alamat_ibuq">
                                                      </label>
                                                   </div>
                                                </div>
                                                <div class="form-group clearfix">
                                                   <label class="col-sm-3 control-label">Alamat</label>
                                                   <div class="col-sm-9">
                                                      <input type="text" name="alamat_ibu" class="form-control" placeholder="Alamat Sekarang">
                                                   </div>
                                                </div>
                                                <div class="form-group clearfix">
                                                   <label class="col-sm-3 control-label">Desa/Kelurahan</label>
                                                   <div class="col-sm-9">
                                                      <input type="text" name="desa_ibu" class="form-control" placeholder="Nama Desa/Kelurahan Sekarang">
                                                   </div>
                                                </div>
                                                <div class="form-group clearfix">
                                                   <label class="col-sm-3 control-label">Kecamatan</label>
                                                   <div class="col-sm-9">
                                                      <input type="text" name="kecamatan_ibu" class="form-control" placeholder="Nama Kecamatan Sekarang">
                                                   </div>
                                                </div>
                                                <div class="form-group clearfix">
                                                   <label class="col-sm-3 control-label">Kabupaten/Kota</label>
                                                   <div class="col-sm-9">
                                                      <input type="text" name="kabupaten_ibu" class="form-control" placeholder="Nama Kabupaten/Kota Sekarang">
                                                   </div>
                                                </div>
                                                <div class="form-group clearfix">
                                                   <label class="col-sm-3 control-label">Provinsi</label>
                                                   <div class="col-sm-9">
                                                      <input type="text" name="provinsi_ibu" class="form-control" placeholder="Nama Provinsi Sekarang">
                                                   </div>
                                                </div>
                                                <div class="form-group clearfix">
                                                   <label class="col-sm-3 control-label">Kode POS</label>
                                                   <div class="col-sm-9">
                                                      <input type="number" name="kode_pos_ibu" pattern="\d*" maxlength="6" class="form-control" placeholder="Kode POS">
                                                   </div>
                                                </div>
                                             </div>
                                             <!-- ANAK -->
                                             <div class="tab-pane" id="child">
                                                <div id="main">
                                                   <button type="button" class="btn btn-success" id="btAdd"><i class="fa fa-plus"></i> Tambah Anak</button>
                                                   <button type="button" id="btRemove" class="btn btn-warning"/>Hapus Form</button>
                                                   <button type="button" id="btRemoveAll" class="btn btn-danger"/>Hapus Semua Form</button><br><br>
                                                </div>
                                             </div>
                                             <!-- SAUDARA -->
                                             <div class="tab-pane" id="brother">
                                                <div id="saudara">
                                                   <button type="button" class="btn btn-success" id="btSaudara"><i class="fa fa-plus"></i> Tambah Saudara</button>
                                                   <button type="button" id="btHapusSaudara" class="btn btn-warning"/>Hapus Form</button>
                                                   <button type="button" id="btHapusSemuaSaudara" class="btn btn-danger"/>Hapus Semua Form</button><br><br>
                                                </div>
                                             </div>
                                             <!-- PASANGAN -->
                                             <div class="tab-pane" id="husband_wife">
                                                <div class="form-group clearfix">
                                                   <label class="col-sm-3 control-label">Nama Suami/Istri</label>
                                                   <div class="col-sm-9">
                                                      <input type="text" name="nama_pasangan" class="form-control" placeholder="Nama Suami/Istri">
                                                   </div>
                                                </div>
                                                <div class="form-group clearfix">
                                                   <label for="nama" class="col-sm-3 control-label">Tempat Tanggal Lahir</label>
                                                   <div class="col-sm-9">
                                                      <input type="text" class="form-control" name="tempat_lahir_pasangan" placeholder="Tempat Lahir">
                                                      <div class="has-feedback">
                                                         <span class="fa fa-calendar form-control-feedback"></span>
                                                         <input type="text" name="tanggal_lahir_pasangan" class="date-picker form-control pull-right" placeholder="Tanggal Lahir">
                                                      </div>
                                                   </div>
                                                </div>
                                                <div class="form-group clearfix">
                                                   <label class="col-sm-3 control-label">Nomor Telp. Suami/Istri</label>
                                                   <div class="col-sm-9">
                                                      <input type="text" name="no_telp_pasangan" maxlength="15" class="form-control" placeholder="Nomor Telpon Suami/Istri">
                                                   </div>
                                                </div>
                                                <div class="form-group clearfix">
                                                   <label for="nama" class="col-sm-3 control-label">Pendidikan Terakhir</label>
                                                   <div class="col-sm-9">
                                                      <?php
                                                      $pendidikan[null] = 'Pilih Data';
                                                      $sel7 = array(NULL);
                                                      $exsel7 = array('class'=>'form-control select2','placeholder'=>'Pendidikan','id'=>'pendidikan_terakhir_pasangan','style'=>'width:100%');
                                                      echo form_dropdown('pendidikan_terakhir_pasangan',$pendidikan,$sel7,$exsel7);
                                                      ?>
                                                   </div>
                                                </div>
                                                <div class="form-group clearfix">
                                                   <label class="col-sm-3 control-label"></label>
                                                   <div class="col-sm-9">
                                                      <label style="vertical-align: middle;">
                                                         <span id="samakansaya3_off" style="font-size: 20px;" onclick="SamakandgnSaya3();"><i class="far fa-square" aria-hidden="true"></i></span>
                                                         <span id="samakansaya3_on" style="display: none; font-size: 20px;" onclick="SamakandgnSaya3();"><i class="far fa-check-square" aria-hidden="true"></i></span>
                                                         <span style="padding-bottom: 9px;vertical-align: middle;"><b> Samakan Dengan Alamat Saya</b></span>
                                                         <input type="hidden" name="alamat_pasanganq">
                                                      </label>
                                                   </div>
                                                </div>
                                                <div class="form-group clearfix">
                                                   <label class="col-sm-3 control-label">Alamat</label>
                                                   <div class="col-sm-9">
                                                      <input type="text" name="alamat_pasangan" class="form-control" placeholder="Alamat">
                                                   </div>
                                                </div>
                                                <div class="form-group clearfix">
                                                   <label class="col-sm-3 control-label">Desa/Kelurahan</label>
                                                   <div class="col-sm-9">
                                                      <input type="text" name="desa_pasangan" class="form-control" placeholder="Nama Desa/Kelurahan">
                                                   </div>
                                                </div>
                                                <div class="form-group clearfix">
                                                   <label class="col-sm-3 control-label">Kecamatan</label>
                                                   <div class="col-sm-9">
                                                      <input type="text" name="kecamatan_pasangan" class="form-control" placeholder="Nama Kecamatan">
                                                   </div>
                                                </div>
                                                <div class="form-group clearfix">
                                                   <label class="col-sm-3 control-label">Kabupaten/Kota</label>
                                                   <div class="col-sm-9">
                                                      <input type="text" name="kabupaten_pasangan" class="form-control" placeholder="Nama Kabupaten/Kota">
                                                   </div>
                                                </div>
                                                <div class="form-group clearfix">
                                                   <label class="col-sm-3 control-label">Provinsi</label>
                                                   <div class="col-sm-9">
                                                      <input type="text" name="provinsi_pasangan" class="form-control" placeholder="Nama Provinsi">
                                                   </div>
                                                </div>
                                                <div class="form-group clearfix">
                                                   <label class="col-sm-3 control-label">Kode POS</label>
                                                   <div class="col-sm-9">
                                                      <input type="number" name="kode_pos_pasangan" pattern="\d*" maxlength="6" class="form-control" placeholder="Kode POS">
                                                   </div>
                                                </div>
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                    <!-- PENDIDIKAN -->
                                    <div class="tab-pane" id="edu">
                                       <div class="nav-tabs-custom">
                                          <ul class="nav nav-tabs">
                                             <li class="active"><a href="#formal" data-toggle="tab">Formal</a></li>
                                             <li><a href="#n_formal" data-toggle="tab">Non-Fromal</a></li>
                                          </ul>
                                          <div class="tab-content">
                                             <!-- FORMAL -->
                                             <div class="tab-pane active" id="formal">
                                                <div id=formal_1>
                                                   <button type="button" class="btn btn-success" id="btFormal"><i class="fa fa-plus"></i> Tambah Data Pendidikan Formal</button>
                                                   <button type="button" id="btHapusFormal" class="btn btn-warning"/>Hapus Form</button>
                                                   <button type="button" id="btHapusSemuaFormal" class="bt btn btn-danger"/>Hapus Semua Form</button><br><br>
                                                </div>
                                             </div>
                                             <!-- NON-FORMAL -->
                                             <div class="tab-pane" id="n_formal">
                                                <div id=non_formal>
                                                   <button type="button" class="btn btn-success" id="btNonFormal"><i class="fa fa-plus"></i> Tambah Data Pendidikan Non Formal</button>
                                                   <button type="button" id="btNonHapusFormal" class="bt btn btn-warning"/>Hapus Form</button>
                                                   <button type="button" id="btNonHapusSemuaFormal" class="bt btn btn-danger"/>Hapus Semua Form</button><br><br>
                                                </div>
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                    <!-- PENGHARGAAN -->
                                    <div class="tab-pane" id="acv">
                                       <div id=penghargaan>
                                          <button type="button" class="btn btn-success" id="btPenghargaan"><i class="fa fa-plus"></i> Tambah Data Penghargaan</button>
                                          <button type="button" id="btHapusPenghargaan" class="bt btn btn-warning"/>Hapus Form</button>
                                          <button type="button" id="btHapusSemuaPenghargaan" class="bt btn btn-danger"/>Hapus Semua Form</button><br><br>
                                       </div>
                                    </div>
                                    <!-- ORGANISASI -->
                                    <div class="tab-pane" id="org">
                                       <div id=organisasi>
                                          <button type="button" class="btn btn-success" id="btOrganisasi"><i class="fa fa-plus"></i> Tambah Data Organisasi</button>
                                          <button type="button" id="btHapusOrg" class="bt btn btn-warning"/>Hapus Form</button>
                                          <button type="button" id="btHapusSemuaOrg" class="bt btn btn-danger"/>Hapus Semua Form</button><br><br>
                                       </div>
                                    </div>
                                    <!-- BAHASA -->
                                    <div class="tab-pane" id="lang">
                                       <div id=bahasa>
                                          <button type="button" class="btn btn-success" id="btBahasa"><i class="fa fa-plus"></i> Tambah Data Bahasa</button>
                                          <button type="button" id="btHapusBahasa" class="bt btn btn-warning"/>Hapus Form</button>
                                          <button type="button" id="btHapusSemuaBahasa" class="bt btn btn-danger"/>Hapus Semua Form</button><br><br>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </fieldset>
                        </div>
                     </div>
                  </div>
                  <div class="box-footer">
                     <div class="pull-right">
                        <button type="submit" id="simpan_add" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
                     </div>
                  </div>
               </form>
            </div>
         </div>
      </div>
   </section>
</div>
<script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
<script type="text/javascript">
   var url_select="<?php echo base_url('global_control/select2_global');?>";
   $(document).ready(function(){
      $('#berlaku_slm_off').click(function(){
         $('#berlaku_slm_off').hide();
         $('#berlaku_slm_on').show();
         $('input[name="berlaku_slm"]').val('1');
      })
      $('#berlaku_slm_on').click(function(){
         $('#berlaku_slm_off').show();
         $('#berlaku_slm_on').hide();
         $('input[name="berlaku_slm"]').val('0');
      })

      $('#samakandgnasal_off').click(function(){
         $('#samakandgnasal_off').hide();
         $('#samakandgnasal_on').show();
         $('input[name="alamat_baru"]').val('1');
      })
      $('#samakandgnasal_on').click(function(){
         $('#samakandgnasal_off').show();
         $('#samakandgnasal_on').hide();
         $('input[name="alamat_baru"]').val('0');
      })
      $('#samakansaya1_off').click(function(){
         $('#samakansaya1_off').hide();
         $('#samakansaya1_on').show();
         $('input[name="alamat_ayahq"]').val('1');
      })
      $('#samakansaya1_on').click(function(){
         $('#samakansaya1_off').show();
         $('#samakansaya1_on').hide();
         $('input[name="alamat_ayahq"]').val('0');
      })
      $('#samakansaya2_off').click(function(){
         $('#samakansaya2_off').hide();
         $('#samakansaya2_on').show();
         $('input[name="alamat_ibuq"]').val('1');
      })
      $('#samakansaya2_on').click(function(){
         $('#samakansaya2_off').show();
         $('#samakansaya2_on').hide();
         $('input[name="alamat_ibuq"]').val('0');
      })
      $('#samakansaya3_off').click(function(){
         $('#samakansaya3_off').hide();
         $('#samakansaya3_on').show();
         $('input[name="alamat_pasanganq"]').val('1');
      })
      $('#samakansaya3_on').click(function(){
         $('#samakansaya3_off').show();
         $('#samakansaya3_on').hide();
         $('input[name="alamat_pasanganq"]').val('0');
      })
      $('#jabSecond_off').click(function(){
         $('#jabSecond_off').hide();
         $('#jabSecond_on').show();
         $('input[name="jabatan_second"]').val('1');
      })
      $('#jabSecond_on').click(function(){
         $('#jabSecond_off').show();
         $('#jabSecond_on').hide();
         $('input[name="jabatan_second"]').val('0');
      })
      getSelect2("<?php echo base_url('master/master_jabatan/nama_jabatan')?>",'jabatan,#jabatan_sekunder');
      getSelect2("<?php echo base_url('master/master_petugas_payroll/select2')?>",'petugas_payroll_add');
      getSelect2("<?php echo base_url('master/master_petugas_lembur/select2')?>",'petugas_lembur_add');
      getSelect2("<?php echo base_url('master/master_petugas_pph/select2')?>",'petugas_pph_add');
      select_data('loker',url_select,'master_loker','kode_loker','nama','asd');
      select_data('status',url_select,'master_status_karyawan','kode_status','nama');
      select_data('perjanjian',url_select,'master_surat_perjanjian','kode_perjanjian','nama');
      select_data('sistem_penggajian',url_select,'master_sistem_penggajian','kode_master_penggajian','nama');
      select_data('perjanjian_kerja',url_select,'master_surat_perjanjian','kode_perjanjian','nama');
      select_data('bank',url_select,'master_bank','kode','nama');
      getSelect2("<?php echo base_url('employee/emp_part_jabatan_grade/grade');?>",'grade'); 
      getSelect2("<?php echo base_url('presensi/data_jadwal_kerja/shift')?>",'kode_master_shift_add');
      unsetoption('data_kode_master_shift_add',['CSTM']);
      $('#btn_add').click(function(){
         if($("#form_add")[0].checkValidity()) {
            $('#btn_addx').click();
         }else{
            notValidParamx();
         }
      })
      $('#form_add').validator().on('submit', function (e) {
         if (e.isDefaultPrevented()) {
            notValidParamx();
         } else {
            e.preventDefault(); 
            var data_add = new FormData(this);
            var urladd = "<?php echo base_url('employee/add_new');?>";
            submitAjaxFile(urladd,data_add,null,null,null);
            $('#form_add')[0].reset();
            refreshCode();
            resetselectAdd();
         }
      })
      $('#tgl_lahir, #tgl_masuk').change(function(){
         var ij = $('#tgl_lahir').val();
         var ik = $('#tgl_masuk').val();
         $.ajax({
            method : "POST",
            url   : '<?php echo base_url('employee/generate_nik')?>',
            async : false,
            dataType : 'json',
            data: { tgl_lahir: ij,
               tgl_masuk: ik
            },
            success : function(data){
               $('#nik').val(data['nik']);
               if (data['status_data']) {
                  $('#errmsg').html(data['msg']).css('color','red');  
                  $('#btn_add').attr('disabled','disabled'); 
               }else{
                  $('#errmsg').html(data['msg']).css('color','green');
                  $('#btn_add').removeAttr('disabled','disabled');   
               }
            }
         });
      })
      $('#perjanjian_kerja, #tgl_masuk').change(function(){
         var ij = $('#perjanjian_kerja').val();
         var ik = $('#tgl_masuk').val();
         $.ajax({
            method : "POST",
            url   : '<?php echo base_url('employee/tanggal_janji')?>',
            async : false,
            dataType : 'json',
            data: { tgl_berlaku: ik,
               status: ij },
               success : function(data){
                  $('#berlaku_sampai_add').val(data);
               }
            });
      })
      $('[data-mask]').inputmask()
      form_property();all_property();
   })
   function refreshCode() {
      kode_generator("<?php echo base_url('employee/perjanjian_kerja/kode');?>",'no_sk_baru_add');
   }
   function resetselectAdd() {
      $('#kelamin').val('').trigger('change');
      $('#agama').val('').trigger('change');
      $('#status_pajak').val('').trigger('change');
      $('#status_nikah').val('').trigger('change');
      $('#jabatan').val('').trigger('change');
      $('#loker').val('').trigger('change');
      $('#bank').val('').trigger('change');
      $('#sistem_penggajian').val('').trigger('change');
      $('#perjanjian_kerja').val('').trigger('change');
      $('#grade').val('').trigger('change');
      $('#status_karyawan').val('').trigger('change');
      $('#darah').val('').trigger('change');
      $('#pendidikan_terakhir_ayah').val('').trigger('change');
      $('#pendidikan_terakhir_ibu').val('').trigger('change');
      $('#pendidikan_terakhir_pasangan').val('').trigger('change');
      $('#baju').val('').trigger('change');
      $('#metode_pph').val('').trigger('change');
      $('#gaji_pokok').val('').trigger('change');
      $('#petugas_payroll_add').val('').trigger('change');
      $('#petugas_pph_add').val('').trigger('change');
      getSelect2('<?php echo base_url('employee/employee/refresh_employee'); ?>','jabatan_sekunder');
		getSelect2("<?php echo base_url('presensi/data_jadwal_kerja/refresh_shift')?>",'kode_master_shift_add');
   }
   function do_add(){
      if($("#form_add")[0].checkValidity()) {
         submitAjax("<?php echo base_url('employee/add_new')?>",null,'form_add',null,null);
         $('#form_add')[0].reset();
         refreshCode();
         resetselectAdd();
      }else{
         notValidParamx();
      } 
   }
   function checkFile(idf,idt,btnx,event) {
      var fext = ['jpg', 'png', 'jpeg', 'gif'];
      var output = document.getElementById('fotok');
      output.src = URL.createObjectURL(event.target.files[0]);
      pathFile(idf,idt,fext,btnx);
      var valfoto = $(idf).val();
      if(valfoto==''){
         output.src = "<?php echo base_url('asset/img/user-photo/user.png');?>";
      }
   }
   function rest_foto() {
      var output = document.getElementById('fotok');
      output.src = "<?php echo base_url('asset/img/user-photo/user.png');?>";
      $('#uploadBtn1').val('');
   }
   function jabSecond(f) {
      setTimeout(function () {
         var name = $('input[name="jabatan_second"]').val();
         if(name == 1) {
            $('#div_jabatan_sekunder').show();
            $('#jabatan_sekunder').attr('required','required');
         }else {
            $('#div_jabatan_sekunder').hide();
            $('#jabatan_sekunder').removeAttr('required');
            getSelect2('<?php echo base_url('employee/employee/refresh_employee'); ?>','jabatan_sekunder');
         }
      },100);
   }
   function SamakandgnAsal1(f) {
      setTimeout(function () {
         var name = $('input[name="alamat_baru"]').val();
         if(name == 1) {
            $('#new_address input[name="alamat_sekarang_jalan"]').val($('#old_address input[name="alamat_asal_jalan"]').val())
            $('#new_address input[name="alamat_sekarang_desa"]').val($('#old_address input[name="alamat_asal_desa"]').val())
            $('#new_address input[name="alamat_sekarang_kecamatan"]').val($('#old_address input[name="alamat_asal_kecamatan"]').val())
            $('#new_address input[name="alamat_sekarang_kabupaten"]').val($('#old_address input[name="alamat_asal_kabupaten"]').val())
            $('#new_address input[name="alamat_sekarang_provinsi"]').val($('#old_address input[name="alamat_asal_provinsi"]').val())
            $('#new_address input[name="alamat_sekarang_pos"]').val($('#old_address input[name="alamat_asal_pos"]').val())
         }else {
            $('#new_address input[name="alamat_sekarang_jalan"]').val('');
            $('#new_address input[name="alamat_sekarang_desa"]').val('');
            $('#new_address input[name="alamat_sekarang_kecamatan"]').val('');
            $('#new_address input[name="alamat_sekarang_kabupaten"]').val('');
            $('#new_address input[name="alamat_sekarang_provinsi"]').val('');
            $('#new_address input[name="alamat_sekarang_pos"]').val('');
         }
      },100);
   }
   function SamakandgnSaya(f) {
      setTimeout(function () {
         var name = $('input[name="alamat_ayahq"]').val();
         if(name == 1) {
            $('#father input[name="alamat_ayah"]').val($('#old_address input[name="alamat_asal_jalan"]').val())
            $('#father input[name="desa_ayah"]').val($('#old_address input[name="alamat_asal_desa"]').val())
            $('#father input[name="kecamatan_ayah"]').val($('#old_address input[name="alamat_asal_kecamatan"]').val())
            $('#father input[name="kabupaten_ayah"]').val($('#old_address input[name="alamat_asal_kabupaten"]').val())
            $('#father input[name="provinsi_ayah"]').val($('#old_address input[name="alamat_asal_provinsi"]').val())
            $('#father input[name="kode_pos_ayah"]').val($('#old_address input[name="alamat_asal_pos"]').val())
         }else {
            $('#father input[name="alamat_ayah"]').val('');
            $('#father input[name="desa_ayah"]').val('');
            $('#father input[name="kecamatan_ayah"]').val('');
            $('#father input[name="kabupaten_ayah"]').val('');
            $('#father input[name="provinsi_ayah"]').val('');
            $('#father input[name="kode_pos_ayah"]').val('');
         }
      },100);
   }
   function SamakandgnSaya2(f) {
      setTimeout(function () {
         var name = $('input[name="alamat_ibuq"]').val();
         if(name == 1) {
            $('#mother input[name="alamat_ibu"]').val($('#old_address input[name="alamat_asal_jalan"]').val())
            $('#mother input[name="desa_ibu"]').val($('#old_address input[name="alamat_asal_desa"]').val())
            $('#mother input[name="kecamatan_ibu"]').val($('#old_address input[name="alamat_asal_kecamatan"]').val())
            $('#mother input[name="kabupaten_ibu"]').val($('#old_address input[name="alamat_asal_kabupaten"]').val())
            $('#mother input[name="provinsi_ibu"]').val($('#old_address input[name="alamat_asal_provinsi"]').val())
            $('#mother input[name="kode_pos_ibu"]').val($('#old_address input[name="alamat_asal_pos"]').val())
         }else {
            $('#mother input[name="alamat_ibu"]').val('');
            $('#mother input[name="desa_ibu"]').val('');
            $('#mother input[name="kecamatan_ibu"]').val('');
            $('#mother input[name="kabupaten_ibu"]').val('');
            $('#mother input[name="provinsi_ibu"]').val('');
            $('#mother input[name="kode_pos_ibu"]').val('');
         }
      },100);
   }
   function SamakandgnSaya3(f) {
      setTimeout(function () {
         var name = $('input[name="alamat_pasanganq"]').val();
         if(name == 1) {
            $('#husband_wife input[name="alamat_pasangan"]').val($('#old_address input[name="alamat_asal_jalan"]').val())
            $('#husband_wife input[name="desa_pasangan"]').val($('#old_address input[name="alamat_asal_desa"]').val())
            $('#husband_wife input[name="kecamatan_pasangan"]').val($('#old_address input[name="alamat_asal_kecamatan"]').val())
            $('#husband_wife input[name="kabupaten_pasangan"]').val($('#old_address input[name="alamat_asal_kabupaten"]').val())
            $('#husband_wife input[name="provinsi_pasangan"]').val($('#old_address input[name="alamat_asal_provinsi"]').val())
            $('#husband_wife input[name="kode_pos_pasangan"]').val($('#old_address input[name="alamat_asal_pos"]').val())
         }else {
            $('#husband_wife input[name="alamat_pasangan"]').val('');
            $('#husband_wife input[name="desa_pasangan"]').val('');
            $('#husband_wife input[name="kecamatan_pasangan"]').val('');
            $('#husband_wife input[name="kabupaten_pasangan"]').val('');
            $('#husband_wife input[name="provinsi_pasangan"]').val('');
            $('#husband_wife input[name="kode_pos_pasangan"]').val('');
         }
      },100);
   }
   function cekgaji(gaji_x) {
      // var gaji_pokok = $('#gaji_pokok').val();
      var gaji = $('#besaran_gaji').val();
      if(gaji_x == 'matrix'){
         $('#besaran_gaji').attr('readonly', 'readonly');
         $('#besaran_gaji').val('');
         $('#simpan_add').removeAttr('disabled');
      }else{
         if(gaji==''){
            $('#besaran_gaji').removeAttr('readonly');
            $('#simpan_add').removeAttr('disabled');
         }
      }
   }
</script>
<script>
   // ANAK
   $(document).ready(function() {
      var iCnt = 0;
      var container = $(document.createElement('div')).css({
      });
      $('#btAdd').click(function() {
         if (iCnt <= 19) {
            iCnt = iCnt + 1;
      // ADD TEXTBOX.
      $(container).append('<div id=tb' + iCnt + '>'+
         '<fieldset>'+
         '<legend class="legend"><i class="fa fa-child"></i> Data Anak Ke - '+iCnt+'</legend>'+
         '<div class="row">'+

         '<div class="col-md-6">'+
         '<div class="form-group clearfix">'+
         '<label for="nama" class="col-sm-3 control-label">Nama</label>'+
         '<div class="col-sm-9">'+
         '<input type=text class="form-control keyup" name="nama_anak['+iCnt+']" placeholder="Nama Anak Ke - '+iCnt+'" />'+
         '</div></div>'+

         '<div class="form-group clearfix">'+
         '<label for="nama" class="col-sm-3 control-label">Jenis Kelamin</label>'+
         '<div class="col-sm-9">'+
         '<select class="form-control select2" name="kelamin_anak['+iCnt+']" style="width:100%;">'+
         '<option value="NULL">Pilih Jenis Kelamin</option>'+
         '<?php foreach ($gender as $kel => $k) { ?>'+
         '<option value="<?php echo $kel?>"><?php echo $k?></option>'+
         '<?php } ?>'+
         '</select>'+
         '</div></div>'+

         '<div class="form-group clearfix">'+
         '<label for="nama" class="col-sm-3 control-label">Tempat Tanggal Lahir</label>'+
         '<div class="col-sm-9">'+
         '<input type=text class="form-control keyup" name="tempat_lahir_anak['+iCnt+']" placeholder="Tempat Lahir" />'+
         '<div class="has-feedback">'+
         '<span class="fa fa-calendar form-control-feedback"></span>'+
         '<input type="text" name="tanggal_lahir_anak['+iCnt+']" class="form-control pull-right date-picker" placeholder="Tanggal Lahir">'+
         '</div>'+
         '</div></div>'+

         '<div class="form-group clearfix">'+
         '<label for="nama" class="col-sm-3 control-label">Pendidikan</label>'+
         '<div class="col-sm-9">'+
         '<select class="form-control select2" name="pendidikan_anak['+iCnt+']" style="width:100%;">'+
         '<option value="NULL">Pilih Pendidikan</option>'+
         '<?php foreach ($pendidikan as $pend => $p) { ?>'+
         '<option value="<?php echo $pend?>">'+
         '<?php echo $p?>'+
         '</option>'+
         '<?php } ?>'+
         '</select>'+
         '</div></div>'+

         '<div class="form-group clearfix">'+
         '<label for="nama" class="col-sm-3 control-label">No. HP</label>'+
         '<div class="col-sm-9">'+
         '<input type=text class="form-control" name="nohp_anak['+iCnt+']" placeholder="Nomor HP Anak Ke - '+iCnt+'" />'+
         '</div>'+

         '</div><div>'+
         '</fieldset><div>');

      // SHOW SUBMIT BUTTON IF ATLEAST "1" ELEMENT HAS BEEN CREATED.
      if (iCnt == 1) {
         var divSubmit = $(document.createElement('div'));
      }

      // ADD BOTH THE DIV ELEMENTS TO THE "main" CONTAINER.
      $('#main').after(container, divSubmit);
      }
      // AFTER REACHING THE SPECIFIED LIMIT, DISABLE THE "ADD" BUTTON.
      // (20 IS THE LIMIT WE HAVE SET)
      else {      
         $(container).append('<label>Reached the limit</label>'); 
         $('#btAdd').attr('class', 'bt-disable'); 
         $('#btAdd').attr('disabled', 'disabled');
      }
      form_property();all_property();
      });

      // REMOVE ONE ELEMENT PER CLICK.
      $('#btRemove').click(function() {
         if (iCnt != 0) { $('#tb' + iCnt).remove(); iCnt = iCnt - 1; }
         if (iCnt == 0) { 
            $(container)
            .empty() 
            .remove(); 

            $('#btSubmit').remove(); 
            $('#btAdd')
            .removeAttr('disabled') 
            .attr('class', 'btn btn-success btn-flat');
         }
      });
      // REMOVE ALL THE ELEMENTS IN THE CONTAINER.
      $('#btRemoveAll').click(function() {
         $(container)
         .empty()
         .remove();
         $('#btSubmit').remove(); 
         iCnt = 0;
         $('#btAdd')
         .removeAttr('disabled') 
         .attr('class', 'btn btn-flat btn-success');
      });
   });
   // SAUDARA
   $(document).ready(function() {
      var iCnt = 0;
      var container = $(document.createElement('div')).css({
      });
      $('#btSaudara').click(function() {
         if (iCnt <= 19) {
            iCnt = iCnt + 1;
      // ADD TEXTBOX.
      $(container).append('<div id=tb' + iCnt + '>'+
         '<fieldset>'+
         '<legend class="legend"><i class="fa fa-child"></i> Data Saudara '+iCnt+'</legend>'+
         '<div class="row">'+

         '<div class="col-md-6">'+
         '<div class="form-group clearfix">'+
         '<label for="nama" class="col-sm-3 control-label">Nama</label>'+
         '<div class="col-sm-9">'+
         '<input type=text class="form-control keyup" name="nama_saudara['+iCnt+']" placeholder="Nama Saudara - '+iCnt+'" />'+
         '</div></div>'+

         '<div class="form-group clearfix">'+
         '<label for="nama" class="col-sm-3 control-label">Jenis Kelamin</label>'+
         '<div class="col-sm-9">'+
         '<select class="form-control select2" name="jenis_kelamin_saudara['+iCnt+']" style="width:100%;">'+
         '<option value="NULL">Pilih Jenis Kelamin</option>'+
         '<?php foreach ($gender as $kel => $k) { ?>'+
         '<option value="<?php echo $kel?>"><?php echo $k?></option>'+
         '<?php } ?>'+
         '</select>'+
         '</div></div>'+

         '<div class="form-group clearfix">'+
         '<label for="nama" class="col-sm-3 control-label">Tempat Tanggal Lahir</label>'+
         '<div class="col-sm-9">'+
         '<input type="text" class="form-control keyup" name="tempat_lahir_saudara['+iCnt+']" placeholder="Tempat Lahir" />'+
         '<div class="has-feedback">'+
         '<span class="fa fa-calendar form-control-feedback"></span>'+
         '<input type="text" name="tanggal_lahir_saudara['+iCnt+']" class="form-control pull-right date-picker" placeholder="Tanggal Lahir">'+
         '</div>'+
         '</div></div>'+

         '<div class="form-group clearfix">'+
         '<label for="nama" class="col-sm-3 control-label">Pendidikan</label>'+
         '<div class="col-sm-9">'+
         '<select class="form-control select2" name="pendidikan_saudara['+iCnt+']" style="width:100%;">'+
         '<option value="NULL">Pilih Pendidikan</option>'+
         '<?php foreach ($pendidikan as $pend => $p) { ?>'+
         '<option value="<?php echo $pend?>">'+
         '<?php echo $p?>'+
         '</option>'+
         '<?php } ?>'+
         '</select>'+
         '</div></div>'+

         '<div class="form-group clearfix">'+
         '<label for="nama" class="col-sm-3 control-label">Nomor HP</label>'+
         '<div class="col-sm-9">'+
         '<input type=text class="form-control" name="no_telp_saudara['+iCnt+']" placeholder="Nomor Telpon Saudara '+iCnt+'" />'+
         '</div></div>'+

         '</div><div>'+
         '</fieldset><div>');
      if (iCnt == 1) {
         var divSubmit = $(document.createElement('div'));
      }
      $('#saudara').after(container, divSubmit);
      }
      else {      
         $(container).append('<label>Reached the limit</label>'); 
         $('#btSaudara').attr('class', 'bt-disable'); 
         $('#btSaudara').attr('disabled', 'disabled');
      }
      form_property();all_property();
      });
      // REMOVE ONE ELEMENT PER CLICK.
      $('#btHapusSaudara').click(function() {
         if (iCnt != 0) { $('#tb' + iCnt).remove(); iCnt = iCnt - 1; }
         if (iCnt == 0) { 
            $(container)
            .empty() 
            .remove(); 

            $('#btSubmit').remove(); 
            $('#btSaudara')
            .removeAttr('disabled') 
            .attr('class', 'btn btn-success btn-flat');
         }
      });
      // REMOVE ALL THE ELEMENTS IN THE CONTAINER.
      $('#btHapusSemuaSaudara').click(function() {
         $(container)
         .empty()
         .remove();
         $('#btSubmit').remove(); 
         iCnt = 0;
         $('#btSaudara')
         .removeAttr('disabled') 
         .attr('class', 'btn btn-flat btn-success');
      });
      });
      // PENDIDIKAN FORMAL
   $(document).ready(function() {
         var iCnt = 0;
         var container = $(document.createElement('div')).css({
         });
         $('#btFormal').click(function() {
            if (iCnt <= 19) {
               iCnt = iCnt + 1;
         // ADD TEXTBOX.
         $(container).append('<div id=tb' + iCnt + '>'+
         '<fieldset>'+
         '<legend class="legend"><i class="fa fa-graduation-cap"></i> Data Pendidikan Formal '+iCnt+'</legend>'+
         '<div class="row">'+
         '<div class="col-md-6">'+

         '<div class="form-group clearfix">'+
         '<label for="nama" class="col-sm-3 control-label">Pendidikan</label>'+
         '<div class="col-sm-9">'+
         '<select class="form-control select2" name="jenjang_pendidikan['+iCnt+']" style="width:100%;">'+
         '<option value="NULL">Pilih Pendidikan</option>'+
         '<?php foreach ($pendidikan as $pend => $p) { ?>'+
         '<option value="<?php echo $pend?>">'+
         '<?php echo $p?>'+
         '</option>'+
         '<?php } ?>'+
         '</select>'+
         '</div></div>'+

         '<div class="form-group clearfix">'+
         '<label for="nama" class="col-sm-3 control-label">Nama Sekolah / Universitas</label>'+
         '<div class="col-sm-9">'+
         '<input type=text class="form-control keyup" name="nama_sekolah['+iCnt+']" placeholder="Nama Sekolah/Universitas '+iCnt+'" />'+
         '</div></div>'+

         '<div class="form-group clearfix">'+
         '<label for="nama" class="col-sm-3 control-label">Jurusan</label>'+
         '<div class="col-sm-9">'+
         '<input type=text class="form-control keyup" name="jurusan['+iCnt+']" placeholder="Jurusan Pendidikan '+iCnt+'" />'+
         '</div></div>'+

         '<div class="form-group clearfix">'+
         '<label for="nama" class="col-sm-3 control-label">Fakultas</label>'+
         '<div class="col-sm-9">'+
         '<input type=text class="form-control keyup" name="fakultas['+iCnt+']" placeholder="Fakultas Pendidikan '+iCnt+'" />'+
         '</div></div>'+

         '<div class="form-group clearfix">'+
         '<label for="nama" class="col-sm-3 control-label">Tanggal Masuk</label>'+
         '<div class="col-sm-9">'+
         '<div class="has-feedback">'+
         '<span class="fa fa-calendar form-control-feedback"></span>'+
         '<input type="text" name="tahun_masuk['+iCnt+']" class="form-control pull-right date-picker" placeholder="Tanggal Masuk">'+
         '</div>'+
         '</div></div>'+

         '<div class="form-group clearfix">'+
         '<label for="nama" class="col-sm-3 control-label">Tanggal Keluar</label>'+
         '<div class="col-sm-9">'+
         '<div class="has-feedback">'+
         '<span class="fa fa-calendar form-control-feedback"></span>'+
         '<input type="text" name="tahun_keluar['+iCnt+']" class="form-control pull-right date-picker" placeholder="Tanggal Keluar">'+
         '</div>'+
         '</div></div>'+

         '<div class="form-group clearfix">'+
         '<label for="nama" class="col-sm-3 control-label">Alamat</label>'+
         '<div class="col-sm-9">'+
         '<input type=text class="form-control keyup" name="alamat_sekolah['+iCnt+']" placeholder="Alamat Pendidikan '+iCnt+'" />'+
         '</div></div>'+

         '</div><div>'+
         '</fieldset><div>');
      if (iCnt == 1) {
         var divSubmit = $(document.createElement('div'));
      }
      $('#formal_1').after(container, divSubmit);
      }
      else {      
         $(container).append('<label>Reached the limit</label>'); 
         $('#btFormal').attr('class', 'bt-disable'); 
         $('#btFormal').attr('disabled', 'disabled');
      }
      form_property();all_property();
      });
      $('#btHapusFormal').click(function() {
         if (iCnt != 0) { $('#tb' + iCnt).remove(); iCnt = iCnt - 1; }
         if (iCnt == 0) { 
            $(container)
            .empty() 
            .remove(); 
            $('#btSubmit').remove(); 
            $('#btFormal')
            .removeAttr('disabled') 
            .attr('class', 'btn btn-success btn-flat');
         }
      });
      $('#btHapusSemuaFormal').click(function() {
         $(container)
         .empty()
         .remove();
         $('#btSubmit').remove(); 
         iCnt = 0;
         $('#btFormal')
         .removeAttr('disabled') 
         .attr('class', 'btn btn-flat btn-success');
      });
   });
   // PENDIDIKAN NON FORMAL
   $(document).ready(function() {
      var iCnt = 0;
      var container = $(document.createElement('div')).css({
      });
      $('#btNonFormal').click(function() {
         if (iCnt <= 19) {
            iCnt = iCnt + 1;
            $(container).append('<div id=tb' + iCnt + '>'+
               '<fieldset>'+
               '<legend class="legend"><i class="fa fa-child"></i> Data Pendidikan Non Formal '+iCnt+'</legend>'+
               '<div class="row">'+

               '<div class="col-md-6">'+
               '<div class="form-group clearfix">'+
               '<label for="nama" class="col-sm-3 control-label">Nama Kursus/Pelatihan</label>'+
               '<div class="col-sm-9">'+
               '<input type=text class="form-control keyup" name="nama_pnf['+iCnt+']" placeholder="Nama Kursus/Pelatihan '+iCnt+'" />'+
               '</div></div>'+

               '<div class="form-group clearfix">'+
               '<label for="nama" class="col-sm-3 control-label">Tanggal Masuk</label>'+
               '<div class="col-sm-9">'+
               '<div class="has-feedback">'+
               '<span class="fa fa-calendar form-control-feedback"></span>'+
               '<input type="text" name="tanggal_masuk_pnf['+iCnt+']" class="form-control pull-right date-picker" placeholder="Tanggal Masuk">'+
               '</div>'+
               '</div></div>'+

               '<div class="form-group clearfix">'+
               '<label for="nama" class="col-sm-3 control-label">Sertifikat</label>'+
               '<div class="col-sm-9">'+
               '<input type=text class="form-control keyup" name="sertifikat_pnf['+iCnt+']" placeholder="Sertifikat '+iCnt+'" />'+
               '</div></div>'+

               '<div class="form-group clearfix">'+
               '<label for="nama" class="col-sm-3 control-label">Nama Lembaga</label>'+
               '<div class="col-sm-9">'+
               '<input type=text class="form-control keyup" name="nama_lembaga_pnf['+iCnt+']" placeholder="Nama Lembaga '+iCnt+'" />'+
               '</div></div>'+

               '<div class="form-group clearfix">'+
               '<label for="nama" class="col-sm-3 control-label">Alamat</label>'+
               '<div class="col-sm-9">'+
               '<input type=text class="form-control keyup" name="alamat_pnf['+iCnt+']" placeholder="Alamat '+iCnt+'" />'+
               '</div></div>'+

               '<div class="form-group clearfix">'+
               '<label for="nama" class="col-sm-3 control-label">Keterangan</label>'+
               '<div class="col-sm-9">'+
               '<input type=text class="form-control keyup" name="keterangan_pnf['+iCnt+']" placeholder="Keterangan '+iCnt+'" />'+
               '</div></div>'+

               '</div><div>'+
               '</fieldset><div>');
            if (iCnt == 1) {
               var divSubmit = $(document.createElement('div'));
            }
            $('#non_formal').after(container, divSubmit);
         }
         else {      
            $(container).append('<label>Reached the limit</label>'); 
            $('#btNonFormal').attr('class', 'bt-disable'); 
            $('#btNonFormal').attr('disabled', 'disabled');
         }
         form_property();all_property();
      });
      // REMOVE ONE ELEMENT PER CLICK.
      $('#btNonHapusFormal').click(function() {
         if (iCnt != 0) { $('#tb' + iCnt).remove(); iCnt = iCnt - 1; }
         if (iCnt == 0) { 
            $(container)
            .empty() 
            .remove();
            $('#btSubmit').remove(); 
            $('#btNonFormal')
            .removeAttr('disabled') 
            .attr('class', 'btn btn-success btn-flat');
         }
      });
      // REMOVE ALL THE ELEMENTS IN THE CONTAINER.
      $('#btNonHapusSemuaFormal').click(function() {
         $(container)
         .empty()
         .remove();
         $('#btSubmit').remove(); 
         iCnt = 0;
         $('#btNonFormal')
         .removeAttr('disabled') 
         .attr('class', 'btn btn-flat btn-success');
      });
   });

   // PENGHARGAAN
   $(document).ready(function() {
      var iCnt = 0;
      var container = $(document.createElement('div')).css({
      });
      $('#btPenghargaan').click(function() {
         if (iCnt <= 19) {
            iCnt = iCnt + 1;
            $(container).append('<div id=tb' + iCnt + '>'+
               '<fieldset>'+
               '<legend class="legend"><i class="fa fa-trophy"></i> Data Penghargaan '+iCnt+'</legend>'+
               '<div class="row">'+

               '<div class="col-md-6">'+
               '<div class="form-group clearfix">'+
               '<label for="nama" class="col-sm-3 control-label">Nama Penghargaan</label>'+
               '<div class="col-sm-9">'+
               '<input type=text class="form-control keyup" name="nama_penghargaan['+iCnt+']" placeholder="Nama Penghargaan '+iCnt+'" />'+
               '</div></div>'+

               '<div class="form-group clearfix">'+
               '<label for="nama" class="col-sm-3 control-label">Tanggal</label>'+
               '<div class="col-sm-9">'+
               '<div class="has-feedback">'+
               '<span class="fa fa-calendar form-control-feedback"></span>'+
               '<input type="text" name="tahun['+iCnt+']" class="form-control pull-right date-picker" placeholder="Tanggal">'+
               '</div>'+
               '</div></div>'+

               '<div class="form-group clearfix">'+
               '<label for="nama" class="col-sm-3 control-label">Peringkat</label>'+
               '<div class="col-sm-9">'+
               '<input type=text class="form-control keyup" name="peringkat['+iCnt+']" placeholder="Peringkat" />'+
               '</div></div>'+

               '<div class="form-group clearfix">'+
               '<label for="nama" class="col-sm-3 control-label">Yang Menetapkan</label>'+
               '<div class="col-sm-9">'+
               '<input type=text class="form-control keyup" name="yg_menetapkan['+iCnt+']" placeholder="Yang Menetapkan '+iCnt+'" />'+
               '</div></div>'+

               '<div class="form-group clearfix">'+
               '<label for="nama" class="col-sm-3 control-label">Penyelenggara</label>'+
               '<div class="col-sm-9">'+
               '<input type=text class="form-control keyup" name="penyelenggara['+iCnt+']" placeholder="Penyelenggara '+iCnt+'" />'+
               '</div></div>'+

               '<div class="form-group clearfix">'+
               '<label for="nama" class="col-sm-3 control-label">Keterangan</label>'+
               '<div class="col-sm-9">'+
               '<input type=text class="form-control keyup" name="keterangan['+iCnt+']" placeholder="Keterangan '+iCnt+'" />'+
               '</div></div>'+

               '</div><div>'+
               '</fieldset><div>');
            if (iCnt == 1) {
               var divSubmit = $(document.createElement('div'));
            }
            $('#penghargaan').after(container, divSubmit);
         }
         else {      
            $(container).append('<label>Reached the limit</label>'); 
            $('#btPenghargaan').attr('class', 'bt-disable'); 
            $('#btPenghargaan').attr('disabled', 'disabled');
         }
         form_property();all_property();
      });
      // REMOVE ONE ELEMENT PER CLICK.
      $('#btHapusPenghargaan').click(function() {
         if (iCnt != 0) { $('#tb' + iCnt).remove(); iCnt = iCnt - 1; }
         if (iCnt == 0) { 
            $(container)
            .empty() 
            .remove();
            $('#btSubmit').remove(); 
            $('#btPenghargaan')
            .removeAttr('disabled') 
            .attr('class', 'btn btn-success btn-flat');
         }
      });
      // REMOVE ALL THE ELEMENTS IN THE CONTAINER.
      $('#btHapusSemuaPenghargaan').click(function() {
         $(container)
         .empty()
         .remove();
         $('#btSubmit').remove(); 
         iCnt = 0;
         $('#btPenghargaan')
         .removeAttr('disabled') 
         .attr('class', 'btn btn-flat btn-success');
      });
   });

   // ORGANISASI
   $(document).ready(function() {
      var iCnt = 0;
      var container = $(document.createElement('div')).css({
      });
      $('#btOrganisasi').click(function() {
         if (iCnt <= 19) {
            iCnt = iCnt + 1;
      // ADD TEXTBOX.
      $(container).append('<div id=tb' + iCnt + '>'+
         '<fieldset>'+
         '<legend class="legend"><i class="fa fa-child"></i> Data Organisasi '+iCnt+'</legend>'+
         '<div class="row">'+

         '<div class="col-md-6">'+
         '<div class="form-group clearfix">'+
         '<label for="nama" class="col-sm-3 control-label">Nama Organisasi</label>'+
         '<div class="col-sm-9">'+
         '<input type=text class="form-control keyup" name="nama_organisasi['+iCnt+']" placeholder="Nama Organisasi '+iCnt+'" />'+
         '</div></div>'+

         '<div class="form-group clearfix">'+
         '<label for="nama" class="col-sm-3 control-label">Tanggal Masuk</label>'+
         '<div class="col-sm-9">'+
         '<div class="has-feedback">'+
         '<span class="fa fa-calendar form-control-feedback"></span>'+
         '<input type="text" name="tahun_masuk['+iCnt+']" class="form-control pull-right date-picker" placeholder="Tanggal Masuk">'+
         '</div>'+
         '</div></div>'+

         '<div class="form-group clearfix">'+
         '<label for="nama" class="col-sm-3 control-label">Tanggal Keluar</label>'+
         '<div class="col-sm-9">'+
         '<div class="has-feedback">'+
         '<span class="fa fa-calendar form-control-feedback"></span>'+
         '<input type="text" name="tahun_keluar['+iCnt+']" class="form-control pull-right date-picker" placeholder="Tanggal Masuk">'+
         '</div>'+
         '</div></div>'+

         '<div class="form-group clearfix">'+
         '<label for="nama" class="col-sm-3 control-label">Jabatan</label>'+
         '<div class="col-sm-9">'+
         '<input type=text class="form-control keyup" name="jabatan_org['+iCnt+']" placeholder="Jabatan Dalam Organisasi '+iCnt+'" />'+
         '</div></div>'+

         '</div><div>'+
         '</fieldset><div>');

      // SHOW SUBMIT BUTTON IF ATLEAST "1" ELEMENT HAS BEEN CREATED.
      if (iCnt == 1) {
         var divSubmit = $(document.createElement('div'));                
      }
      // ADD BOTH THE DIV ELEMENTS TO THE "main" CONTAINER.
      $('#organisasi').after(container, divSubmit);
      }
      else {      
         $(container).append('<label>Reached the limit</label>'); 
         $('#btOrganisasi').attr('class', 'bt-disable'); 
         $('#btOrganisasi').attr('disabled', 'disabled');
      }
      form_property();all_property();
      });
      // REMOVE ONE ELEMENT PER CLICK.
      $('#btHapusOrg').click(function() {
         if (iCnt != 0) { $('#tb' + iCnt).remove(); iCnt = iCnt - 1; }
         if (iCnt == 0) { 
            $(container)
            .empty() 
            .remove();
            $('#btSubmit').remove(); 
            $('#btOrganisasi')
            .removeAttr('disabled') 
            .attr('class', 'btn btn-success btn-flat');
         }
      });
      // REMOVE ALL THE ELEMENTS IN THE CONTAINER.
      $('#btHapusSemuaOrg').click(function() {
         $(container)
         .empty()
         .remove();
         $('#btSubmit').remove(); 
         iCnt = 0;
         $('#btOrganisasi')
         .removeAttr('disabled') 
         .attr('class', 'btn btn-flat btn-success');
      });
   });
   // BAHASA
   $(document).ready(function() {
      var iCnt = 0;
      var container = $(document.createElement('div')).css({
      });
      $('#btBahasa').click(function() {
         if (iCnt <= 19) {
            iCnt = iCnt + 1;
      // ADD TEXTBOX.
      $(container).append('<div id=tb' + iCnt + '>'+
         '<fieldset>'+
         '<legend class="legend"><i class="fa fa-child"></i> Data Penguasaan Bahasa '+iCnt+'</legend>'+
         '<div class="row">'+
         '<div class="col-md-8">'+

         '<div class="form-group clearfix">'+
         '<label for="nama" class="col-sm-3 control-label">Pilih Bahasa</label>'+
         '<div class="col-sm-9">'+
         '<select class="form-control select2" name="bahasa['+iCnt+']" style="width:100%;">'+
         '<option value="NULL">Pilih Bahasa</option>'+
         '<?php foreach ($bahasa as $ba => $b) { ?>'+
         '<option value="<?php echo $ba?>">'+
         '<?php echo $b?>'+
         '</option>'+
         '<?php } ?>'+
         '</select>'+
         '</div></div>'+

         '<div class="form-group clearfix">'+
         '<label for="nama" class="col-sm-3 control-label">Membaca</label>'+
         '<div class="col-sm-9">'+
         '<div class="radio">'+
         '<label class="jarak">'+
         '<input type="radio" name="membaca['+iCnt+']" id="membaca1" value="5">Sangat Baik</label> '+
         '<label class="jarak">'+
         '<input type="radio" name="membaca['+iCnt+']" id="membaca2" value="4">Baik</label> '+
         '<label class="jarak">'+
         '<input type="radio" name="membaca['+iCnt+']" id="membaca3" value="3">Cukup</label> '+
         '<label class="jarak">'+
         '<input type="radio" name="membaca['+iCnt+']" id="membaca4" value="2">Kurang</label> '+
         '<label class="jarak">'+
         '<input type="radio" name="membaca['+iCnt+']" id="membaca5" value="1">Sangat Kurang</label> '+
         '</div>'+
         '</div></div>'+

         '<div class="form-group clearfix">'+
         '<label for="nama" class="col-sm-3 control-label">Menulis</label>'+
         '<div class="col-sm-9">'+'<div class="radio">'+
         '<label class="jarak">'+
         '<input type="radio" name="menulis['+iCnt+']" id="menulis1" value="5">Sangat Baik</label> '+
         '<label class="jarak">'+
         '<input type="radio" name="menulis['+iCnt+']" id="menulis2" value="4">Baik</label> '+
         '<label class="jarak">'+
         '<input type="radio" name="menulis['+iCnt+']" id="menulis3" value="3">Cukup</label> '+
         '<label class="jarak">'+
         '<input type="radio" name="menulis['+iCnt+']" id="menulis4" value="2">Kurang</label> '+
         '<label class="jarak">'+
         '<input type="radio" name="menulis['+iCnt+']" id="menulis5" value="1">Sangat Kurang</label> '+
         '</div>'+
         '</div></div>'+

         '<div class="form-group clearfix">'+
         '<label for="nama" class="col-sm-3 control-label">Berbicara</label>'+
         '<div class="col-sm-9">'+'<div class="radio">'+
         '<label class="jarak">'+
         '<input type="radio" name="berbicara['+iCnt+']" id="berbicara1" value="5">Sangat Baik</label> '+
         '<label class="jarak">'+
         '<input type="radio" name="berbicara['+iCnt+']" id="berbicara2" value="4">Baik</label> '+
         '<label class="jarak">'+
         '<input type="radio" name="berbicara['+iCnt+']" id="berbicara3" value="3">Cukup</label> '+
         '<label class="jarak">'+
         '<input type="radio" name="berbicara['+iCnt+']" id="berbicara4" value="2">Kurang</label> '+
         '<label class="jarak">'+
         '<input type="radio" name="berbicara['+iCnt+']" id="berbicara5" value="1">Sangat Kurang</label> '+
         '</div>'+
         '</div></div>'+

         '<div class="form-group clearfix">'+
         '<label for="nama" class="col-sm-3 control-label">Mendengar</label>'+
         '<div class="col-sm-9">'+'<div class="radio">'+
         '<label class="jarak">'+
         '<input type="radio" name="mendengar['+iCnt+']" id="mendengar1" value="5">Sangat Baik</label> '+
         '<label class="jarak">'+
         '<input type="radio" name="mendengar['+iCnt+']" id="mendengar2" value="4">Baik</label> '+
         '<label class="jarak">'+
         '<input type="radio" name="mendengar['+iCnt+']" id="mendengar3" value="3">Cukup</label> '+
         '<label class="jarak">'+
         '<input type="radio" name="mendengar['+iCnt+']" id="mendengar4" value="2">Kurang</label> '+
         '<label class="jarak">'+
         '<input type="radio" name="mendengar['+iCnt+']" id="mendengar5" value="1">Sangat Kurang</label> '+
         '</div>'+
         '</div></div>'+


         '</div><div>'+
         '</fieldset><div>');

      // SHOW SUBMIT BUTTON IF ATLEAST "1" ELEMENT HAS BEEN CREATED.
      if (iCnt == 1) {
         var divSubmit = $(document.createElement('div'));
      }
      $('#bahasa').after(container, divSubmit);
      }
      else {      
         $(container).append('<label>Reached the limit</label>'); 
         $('#btBahasa').attr('class', 'bt-disable'); 
         $('#btBahasa').attr('disabled', 'disabled');
      }
      form_property();all_property();
      });
      // REMOVE ONE ELEMENT PER CLICK.
      $('#btHapusBahasa').click(function() {
         if (iCnt != 0) { $('#tb' + iCnt).remove(); iCnt = iCnt - 1; }
         if (iCnt == 0) { 
            $(container)
            .empty() 
            .remove();
            $('#btSubmit').remove(); 
            $('#btBahasa')
            .removeAttr('disabled') 
            .attr('class', 'btn btn-success btn-flat');
         }
      });
      // REMOVE ALL THE ELEMENTS IN THE CONTAINER.
      $('#btHapusSemuaBahasa').click(function() {
         $(container)
         .empty()
         .remove();
         $('#btSubmit').remove(); 
         iCnt = 0;
         $('#btBahasa')
         .removeAttr('disabled') 
         .attr('class', 'btn btn-flat btn-success');
      });
   });
   function cekfinger(id_finger) {
      if(id_finger==''){
      // $('#id_finger').css('border-color','#DD4B39');
      }else{
         var data={id_finger:id_finger};
         var callback=getAjaxData("<?php echo base_url('employee/cek_id_finger')?>",data);
         if(callback['val']=='false'){
            $('#id_finger').css('border-color','#DD4B39');
            $('#notif_finger').html('ID Finger Sudah Ada.');
            $('#id_finger_label').css('color','#DD4B39');
         }else{
            $('#id_finger').css('border-color','#00A65A');
            $('#notif_finger').html('');
            $('#id_finger_label').css('color','#00A65A');
         }
      }
   }
   function cekKodePerjanjian(kode_perjanjian) {
      var status = $('#perjanjian_kerja').val();
      var data={kode:status};
      var callback=getAjaxData("<?php echo base_url('employee/cek_kode_perjanjian')?>",data);
      $('#status_karyawan').html(callback['status_karyawan']);
   }
</script>