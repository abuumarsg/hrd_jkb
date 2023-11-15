<meta http-equiv="cache-control" content="no-cache" />
<meta http-equiv="pragma" content="no-cache" />
<?php
 // $color = $this->otherfunctions->getSkinColorText($adm['skin']);
?>
<style type="text/css">
  .wordwrap { 
     white-space: pre-wrap;      /* CSS3 */   
     white-space: -moz-pre-wrap; /* Firefox */    
     white-space: -pre-wrap;     /* Opera <7 */   
     white-space: -o-pre-wrap;   /* Opera 7 */    
     word-wrap: break-word;      /* IE */
  }
</style>
<div class="row">
   <div class="col-md-12">
      <fieldset>
         <legend class="legend"><i class="fa fa-users"></i> Data Keluarga</legend>
      </fieldset>
      <table class='table table-bordered table-striped table-hover'>
         <tr>
            <th width="20%">Ayah</th>
            <td>
               <table class="table table-striped">
                  <?php if ($profile['nama_ayah'] == NULL || $profile['nama_ayah'] == "" ){
                     echo '<label class="label label-danger"><i class="fa fa-info-circle"></i> Tidak Ada Data Ayah</label>';
                  }else{ ?>
                  <tr>
                     <th width="20%">Nama</th>
                     <td><?php echo ucwords($profile['nama_ayah']);?></td>
                  </tr>
                  <tr>
                     <th>Tempat, Tanggal Lahir</th>
                     <td><?php if($profile['tempat_lahir_ayah'] != NULL){echo $profile['tempat_lahir_ayah'].', ';} echo $this->formatter->getDateMonthFormatUser($profile['tanggal_lahir_ayah']);?>
                     </td>
                  </tr>
                  <tr>
                     <th>Alamat</th>
                     <td><?php
                        if ($profile['alamat_ayah'] == NULL || $profile['alamat_ayah'] == "") {
                           echo '<label class="label label-danger">Alamat Belum Diinput</label>';
                        }else{
                           echo ucwords($profile['alamat_ayah']).', '.ucwords($profile['desa_ayah']).', '.ucwords($profile['kecamatan_ayah']).', '.ucwords($profile['kabupaten_ayah']).', '.ucwords($profile['provinsi_ayah']).' <br>Kode Pos : ';
                           if ($profile['kode_pos_ayah'] == 0) {
                              echo '<label class="label label-warning">Tidak Ada Kode Pos</label>';
                           }else{
                              echo ucwords($profile['kode_pos_ayah']);
                           }
                        } ?>
                     </td>
                  </tr>
                  <tr>
                     <th>No. Telp</th>
                     <td><?php
                        if ($profile['no_hp_ayah'] == NULL) {
                           echo '<label class="label label-danger">Nomor Handphone Tidak Ada</label>';
                        }else{
                           echo ucwords($profile['no_hp_ayah']);
                        }?>
                     </td>
                  </tr>
                  <tr>
                     <th>Pendidikan Terakhir</th>
                     <td><?php
                        if ($profile['pendidikan_terakhir_ayah'] == NULL) {
                           echo '<label class="label label-danger">Pendidikan Tidak Ada</label>';
                        }else{
                           echo ucwords($profile['pendidikan_terakhir_ayah']);
                        }?>
                     </td>
                  </tr>
               <?php } ?>
               </table>
            </td>
         </tr>
         <tr>
         <th>Ibu</th>
            <td>
               <table class="table table-striped">
               <?php if ($profile['nama_ibu'] == NULL || $profile['nama_ibu'] == "" ){
                  echo '<label class="label label-danger"><i class="fa fa-info-circle"></i> Tidak Ada Data Ibu</label>';
               }else{ ?>
                  <tr>
                     <th width="20%">Nama</th>
                     <td><?php echo ucwords($profile['nama_ibu']);?></td>
                  </tr>
                  <tr>
                     <th>Tempat, Tanggal Lahir</th>
                     <td><?php if($profile['tempat_lahir_ibu'] != NULL){echo $profile['tempat_lahir_ibu'].', ';} echo $this->formatter->getDateMonthFormatUser($profile['tanggal_lahir_ibu']);?>
                     </td>
                  </tr>
                  <tr>
                     <th>Alamat</th>
                     <td><?php
                        if ($profile['alamat_ibu'] == NULL || $profile['alamat_ibu'] == "") {
                           echo '<label class="label label-danger">Alamat Belum Diinput</label>';
                        }else{
                           echo ucwords($profile['alamat_ibu']).', '.ucwords($profile['desa_ibu']).', '.ucwords($profile['kecamatan_ibu']).', '.ucwords($profile['kabupaten_ibu']).', '.ucwords($profile['provinsi_ibu']).' <br>Kode Pos : ';
                           if ($profile['kode_pos_ibu'] == 0) {
                              echo '<label class="label label-warning">Tidak Ada Kode Pos</label>';
                           }else{
                              echo ucwords($profile['kode_pos_ibu']);
                           }
                        }  ?>
                     </td>
                  </tr>
                  <tr>
                     <th>No. Telp</th>
                     <td><?php
                        if ($profile['no_hp_ibu'] == NULL) {
                           echo '<label class="label label-danger">Nomor Handphone Tidak Ada</label>';
                        }else{
                           echo ucwords($profile['no_hp_ibu']);
                        }?>
                     </td>
                  </tr>
                  <tr>
                     <th>Pendidikan Terakhir</th>
                     <td><?php
                        if ($profile['pendidikan_terakhir_ibu'] == NULL) {
                           echo '<label class="label label-danger">Pendidikan Tidak Ada</label>';
                        }else{
                           echo ucwords($profile['pendidikan_terakhir_ibu']);
                        }?>
                     </td>
                  </tr>
               <?php } ?>
               </table>
            </td>
         </tr>
         <tr>
            <th>Suami / Istri</th>
            <td>
               <table class="table table-striped">
                  <?php if ($profile['nama_pasangan'] == NULL || $profile['nama_pasangan'] == "" ){
                     echo '<label class="label label-danger"><i class="fa fa-info-circle"></i> Tidak Ada Data Suami / Istri</label>';
                  }else{ ?>
                  <tr>
                     <th width="20%">Nama</th>
                     <td><?php echo ucwords($profile['nama_pasangan']);?></td>
                  </tr>
                  <tr>
                     <th>Tempat, Tanggal Lahir</th>
                     <td><?php if($profile['tempat_lahir_pasangan'] != NULL){echo $profile['tempat_lahir_pasangan'].', ';} echo $this->formatter->getDateMonthFormatUser($profile['tanggal_lahir_pasangan']);?>
                     </td>
                  </tr>
                  <tr>
                     <th>Alamat</th>
                     <td><?php
                        if ($profile['alamat_pasangan'] == NULL || $profile['alamat_pasangan'] == "") {
                           echo '<label class="label label-danger">Alamat Belum Diinput</label>';
                        }else{
                           echo ucwords($profile['alamat_pasangan']).', '.ucwords($profile['desa_pasangan']).', '.ucwords($profile['kecamatan_pasangan']).', '.ucwords($profile['kabupaten_pasangan']).', '.ucwords($profile['provinsi_pasangan']).' <br>Kode Pos : ';
                           if ($profile['kode_pos_pasangan'] == 0) {
                              echo '<label class="label label-warning">Tidak Ada Kode Pos</label>';
                           }else{
                              echo ucwords($profile['kode_pos_pasangan']);
                           }
                        }  ?>
                     </td>
                  </tr>
                  <tr>
                     <th>No. Telp</th>
                     <td><?php
                        if ($profile['no_hp_pasangan'] == NULL) {
                           echo '<label class="label label-danger">Nomor Handphone Tidak Ada</label>';
                        }else{
                           echo ucwords($profile['no_hp_pasangan']);
                        }?>
                     </td>
                  </tr>
                  <tr>
                     <th>Pendidikan Terakhir</th>
                        <td><?php
                           if ($profile['pendidikan_terakhir_pasangan'] == NULL) {
                              echo '<label class="label label-danger">Pendidikan Tidak Ada</label>';
                           }else{
                              echo ucwords($profile['pendidikan_terakhir_pasangan']);
                           } 
                        }?>
                        </td>
                  </tr>
               </table>
            </td>
         </tr>
      </table>
   </div>
</div>
<div class="row">
   <div class="col-md-2">
      <p><b>Data Anak</b></p>
   </div>
   <div class="col-md-10">
      <div class="col-md-12">
         <div id="data_tabel_anak"></div>
      </div>
   </div>
</div>
<div class="row">
   <div class="col-md-2">
      <p><b>Data Saudara</b></p>
   </div>
   <div class="col-md-10">
      <div class="col-md-12">
         <div id="data_tabel_saudara"></div>
      </div>
   </div>
</div>

<fieldset>
   <legend class="legend"><i class="fa fa-graduation-cap"></i> Data Pendidikan</legend>
</fieldset>
<div class="row">
   <div class="col-md-12">
      <p><b>Pendidikan Formal</b></p>
      <div id="data_tabel_formal"></div>
   </div>
</div>
<div class="row">
   <div class="col-md-12">
      <p><b>Pendidikan Non Formal</b></p>
      <div id="data_tabel_nformal"></div>
   </div>
</div>

<fieldset>
   <legend class="legend"><i class="fa fa-group"></i> Data Organisasi</legend>
</fieldset>
<div class="row">
   <div class="col-md-12">
      <div id="data_tabel_organisasi"></div>
   </div>
</div>

<fieldset>
   <legend class="legend"><i class="fa fa-trophy"></i> Data Penghargaan</legend>
</fieldset>
<div class="row">
   <div class="col-md-12" style="padding-top: 10px;">
      <div id="data_tabel_penghargaan"></div>
   </div>
</div>

<fieldset>
   <legend class="legend"><i class="fa fa-trophy"></i> Data Penguasaan Bahasa</legend>
</fieldset>
<div class="row">
   <div class="col-md-12" style="padding-top: 10px;">
      <div id="data_tabel_bahasa"></div>
   </div>
</div>                

<!-- view anak-->
<div id="view_anak" class="modal fade" role="dialog">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h2 class="modal-title">Detail Data <b class="text-muted header_data"></b></h2>
            <input type="hidden" name="data_id_view">
         </div>
         <div class="modal-body">
            <div class="row">
               <div class="col-md-6">
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Nama Anak</label>
                     <div class="col-md-6" id="data_nama_view"></div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Jenis Kelamin</label>
                     <div class="col-md-6" id="data_jk_view"></div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Tempat, Tanggal Lahir</label>
                     <div class="col-md-6" id="data_ttl_view"></div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Pendidikan</label>
                     <div class="col-md-6" id="data_ptnd_view"></div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">No. telp</label>
                     <div class="col-md-6" id="data_telp_view"></div>
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Status</label>
                     <div class="col-md-6" id="data_status_view">

                     </div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Dibuat Tanggal</label>
                     <div class="col-md-6" id="data_create_date_view"></div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Diupdate Tanggal</label>
                     <div class="col-md-6" id="data_update_date_view"></div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Dibuat Oleh</label>
                     <div class="col-md-6" id="data_create_by_view">
                     </div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Diupdate Oleh</label>
                     <div class="col-md-6" id="data_update_by_view">
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
         </div>
      </div>
   </div>
</div>

<!-- view saudara-->
<div id="view_saudara" class="modal fade" role="dialog">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h2 class="modal-title">Detail Data <b class="text-muted header_data"></b></h2>
            <input type="hidden" name="data_id_view">
         </div>
         <div class="modal-body">
            <div class="row">
               <div class="col-md-6">
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Nama saudara</label>
                     <div class="col-md-6" id="data_nama_view"></div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Jenis Kelamin</label>
                     <div class="col-md-6" id="data_jk_view"></div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Tempat, Tanggal Lahir</label>
                     <div class="col-md-6" id="data_ttl_view"></div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Pendidikan</label>
                     <div class="col-md-6" id="data_ptnd_view"></div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">No. telp</label>
                     <div class="col-md-6" id="data_telp_view"></div>
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Status</label>
                     <div class="col-md-6" id="data_status_view">

                     </div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Dibuat Tanggal</label>
                     <div class="col-md-6" id="data_create_date_view"></div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Diupdate Tanggal</label>
                     <div class="col-md-6" id="data_update_date_view"></div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Dibuat Oleh</label>
                     <div class="col-md-6" id="data_create_by_view">
                     </div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Diupdate Oleh</label>
                     <div class="col-md-6" id="data_update_by_view">
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
         </div>
      </div>
   </div>
</div>
<!-- view formal-->
<div id="view_formal" class="modal fade" role="dialog">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h2 class="modal-title">Detail Data <b class="text-muted header_data"></b></h2>
            <input type="hidden" name="data_id_view">
         </div>
         <div class="modal-body">
            <div class="row">
               <div class="col-md-6">
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Jenjang Pendidikan</label>
                     <div class="col-md-6" id="data_jenjang_view"></div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Nama Sekolah</label>
                     <div class="col-md-6" id="data_nama_view"></div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Jurusan</label>
                     <div class="col-md-6" id="data_jurusan_view"></div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Fakultas</label>
                     <div class="col-md-6" id="data_fakultas_view"></div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Tahun Masuk</label>
                     <div class="col-md-6" id="data_masuk_view"></div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Tahun Keluar</label>
                     <div class="col-md-6" id="data_keluar_view"></div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Alamat Sekolah</label>
                     <div class="col-md-6" id="data_alsekolah_view"></div>
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Status</label>
                     <div class="col-md-6" id="data_status_view">

                     </div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Dibuat Tanggal</label>
                     <div class="col-md-6" id="data_create_date_view"></div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Diupdate Tanggal</label>
                     <div class="col-md-6" id="data_update_date_view"></div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Dibuat Oleh</label>
                     <div class="col-md-6" id="data_create_by_view">
                     </div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Diupdate Oleh</label>
                     <div class="col-md-6" id="data_update_by_view">
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
         </div>
      </div>
   </div>
</div>
<!-- view non formal-->
<div id="view_nformal" class="modal fade" role="dialog">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h2 class="modal-title">Detail Data <b class="text-muted header_data"></b></h2>
            <input type="hidden" name="data_id_view">
         </div>
         <div class="modal-body">
            <div class="row">
               <div class="col-md-6">
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Nama Pendidikan</label>
                     <div class="col-md-6" id="nama_pnf"></div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Tanggal Masuk</label>
                     <div class="col-md-6" id="tanggal_masuk_pnf"></div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Sertifikat</label>
                     <div class="col-md-6" id="sertifikat_pnf"></div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Nama Lembaga</label>
                     <div class="col-md-6" id="nama_lembaga_pnf"></div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Alamat</label>
                     <div class="col-md-6" id="alamat_pnf"></div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Keterangan</label>
                     <div class="col-md-6" id="keterangan_pnf"></div>
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Status</label>
                     <div class="col-md-6" id="data_status_view">

                     </div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Dibuat Tanggal</label>
                     <div class="col-md-6" id="data_create_date_view"></div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Diupdate Tanggal</label>
                     <div class="col-md-6" id="data_update_date_view"></div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Dibuat Oleh</label>
                     <div class="col-md-6" id="data_create_by_view">
                     </div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Diupdate Oleh</label>
                     <div class="col-md-6" id="data_update_by_view">
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
         </div>
      </div>
   </div>
</div>
<!-- view organisasi-->
<div id="modal_org_view" class="modal fade" role="dialog">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h2 class="modal-title">Detail Data <b class="text-muted header_data"></b></h2>
            <input type="hidden" name="data_id_view">
         </div>
         <div class="modal-body">
            <div class="row">
               <div class="col-md-6">
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Nama Organisasi</label>
                     <div class="col-md-6" id="nama_org"></div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Tanggal Masuk</label>
                     <div class="col-md-6" id="tanggal_masuk_org"></div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Tangga Keluar</label>
                     <div class="col-md-6" id="tanggal_keluar_org"></div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Jabatan</label>
                     <div class="col-md-6" id="jabatan"></div>
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Status</label>
                     <div class="col-md-6" id="data_status_view">

                     </div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Dibuat Tanggal</label>
                     <div class="col-md-6" id="data_create_date_view"></div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Diupdate Tanggal</label>
                     <div class="col-md-6" id="data_update_date_view"></div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Dibuat Oleh</label>
                     <div class="col-md-6" id="data_create_by_view">
                     </div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Diupdate Oleh</label>
                     <div class="col-md-6" id="data_update_by_view">
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
         </div>
      </div>
   </div>
</div>
<!-- view Penghargaan-->
<div id="modal_hrg_view" class="modal fade" role="dialog">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h2 class="modal-title">Detail Data <b class="text-muted header_data"></b></h2>
            <input type="hidden" name="data_id_view">
         </div>
         <div class="modal-body">
            <div class="row">
               <div class="col-md-6">
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Nama Penghargaan</label>
                     <div class="col-md-6" id="nama_penghargaan"></div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Tanggal</label>
                     <div class="col-md-6" id="tanggal"></div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Peringkat</label>
                     <div class="col-md-6" id="peringkat"></div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Yang Menetapkan</label>
                     <div class="col-md-6" id="yg_menetapkan"></div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Penyelenggara</label>
                     <div class="col-md-6" id="penyelenggara"></div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Keterangan</label>
                     <div class="col-md-6" id="keterangan"></div>
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Status</label>
                     <div class="col-md-6" id="data_status_view">

                     </div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Dibuat Tanggal</label>
                     <div class="col-md-6" id="data_create_date_view"></div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Diupdate Tanggal</label>
                     <div class="col-md-6" id="data_update_date_view"></div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Dibuat Oleh</label>
                     <div class="col-md-6" id="data_create_by_view">
                     </div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Diupdate Oleh</label>
                     <div class="col-md-6" id="data_update_by_view">
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
         </div>
      </div>
   </div>
</div>

<!-- view bahasa-->
<div id="view_bahasa" class="modal fade" role="dialog">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h2 class="modal-title">Detail Data <b class="text-muted header_data"></b></h2>
            <input type="hidden" name="data_id_view">
         </div>
         <div class="modal-body">
            <div class="row">
               <div class="col-md-6">
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Bahasa</label>
                     <div class="col-md-6" id="bahasa"></div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Membaca</label>
                     <div class="col-md-6" id="membaca"></div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Menulis</label>
                     <div class="col-md-6" id="menulis"></div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Berbicara</label>
                     <div class="col-md-6" id="berbicara"></div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Mendengar</label>
                     <div class="col-md-6" id="mendengar"></div>
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Status</label>
                     <div class="col-md-6" id="data_status_view">
                     </div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Dibuat Tanggal</label>
                     <div class="col-md-6" id="data_create_date_view"></div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Diupdate Tanggal</label>
                     <div class="col-md-6" id="data_update_date_view"></div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Dibuat Oleh</label>
                     <div class="col-md-6" id="data_create_by_view">
                     </div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Diupdate Oleh</label>
                     <div class="col-md-6" id="data_update_by_view">
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
         </div>
      </div>
   </div>
</div>
<script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
<script type="text/javascript">
  function informasi_lain(){
    var id_karyawan=<?php echo $profile['nik'];?>;
    var data={id_karyawan:id_karyawan};
    var callback=getAjaxData("<?php echo base_url('employee/view_karyawan_tidak_aktif/view_anak')?>",data);
    $('#data_tabel_anak').html(callback['tabel_anak']);

    var callback2=getAjaxData("<?php echo base_url('employee/view_karyawan_tidak_aktif/view_saudara')?>",data);
    $('#data_tabel_saudara').html(callback2['tabel_saudara']);

    var callback3=getAjaxData("<?php echo base_url('employee/view_karyawan_tidak_aktif/view_formal')?>",data);
    $('#data_tabel_formal').html(callback3['tabel_formal']);

    var callback4=getAjaxData("<?php echo base_url('employee/view_karyawan_tidak_aktif/view_nformal')?>",data);
    $('#data_tabel_nformal').html(callback4['tabel_nformal']);

    var callback5=getAjaxData("<?php echo base_url('employee/view_karyawan_tidak_aktif/view_org')?>",data);
    $('#data_tabel_organisasi').html(callback5['tabel_org']);

    var callback6=getAjaxData("<?php echo base_url('employee/view_karyawan_tidak_aktif/view_penghargaan')?>",data);
    $('#data_tabel_penghargaan').html(callback6['tabel_penghargaan']);

    var callback7=getAjaxData("<?php echo base_url('employee/view_karyawan_tidak_aktif/view_bahasa')?>",data);
    $('#data_tabel_bahasa').html(callback7['tabel_bahasa']);
  }
  function view_modal_anak(id) {
    var data={id_anak:id};
    var callback=getAjaxData("<?php echo base_url().'employee/emp_anak/view_one/'.$profile['nik']; ?>",data);  
    $('#view_anak').modal('toggle');
    $('#view_anak input[name="data_id_view"]').val(callback['id']);
    $('#view_anak .header_data').html(callback['nama_anak']);
    $('#view_anak #data_nama_view').html(callback['nama_anak']);
    $('#view_anak #data_jk_view').html(callback['getkelamin_anak']);
    $('#view_anak #data_ttl_view').html(callback['getTTL']);
    $('#view_anak #data_ptnd_view').html(callback['getPendidikan']);
    $('#view_anak #data_telp_view').html(callback['no_telp']);
    var status = callback['status'];
    if(status==1){
      var statusval = '<b class="text-success">Aktif</b>';
    }else{
      var statusval = '<b class="text-danger">Tidak Aktif</b>';
    }
    $('#view_anak #data_status_view').html(statusval);
    $('#view_anak #data_create_date_view').html(callback['create_date']+' WIB');
    $('#view_anak #data_update_date_view').html(callback['update_date']+' WIB');
    $('#view_anak #data_create_by_view').html(callback['nama_buat']);
    $('#view_anak #data_update_by_view').html(callback['nama_update']);
  }
  function view_modal_saudara(id) {
    var data={id_saudara:id};
    var callback=getAjaxData("<?php echo base_url().'employee/empsaudara/view_one/'.$profile['nik']; ?>",data);  
    $('#view_saudara').modal('toggle');
    $('#view_saudara input[name="data_id_view"]').val(callback['id']);
    $('#view_saudara .header_data').html(callback['nama_saudara']);
    $('#view_saudara #data_nama_view').html(callback['nama_saudara']);
    $('#view_saudara #data_jk_view').html(callback['getkelamin_saudara']);
    $('#view_saudara #data_ttl_view').html(callback['getTTL']);
    $('#view_saudara #data_ptnd_view').html(callback['getPendidikan']);
    $('#view_saudara #data_telp_view').html(callback['no_telp_saudara']);
    var status = callback['status'];
    if(status==1){
      var statusval = '<b class="text-success">Aktif</b>';
    }else{
      var statusval = '<b class="text-danger">Tidak Aktif</b>';
    }
    $('#view_saudara #data_status_view').html(statusval);
    $('#view_saudara #data_create_date_view').html(callback['create_date']+' WIB');
    $('#view_saudara #data_update_date_view').html(callback['update_date']+' WIB');
    $('#view_saudara #data_create_by_view').html(callback['nama_buat']);
    $('#view_saudara #data_update_by_view').html(callback['nama_update']);
  }
  function view_modal_formal(id) {
    var data={id_k_pendidikan:id};
    var callback=getAjaxData("<?php echo base_url().'employee/emppendidikan/view_one/'.$profile['nik']; ?>",data);  
    $('#view_formal').modal('toggle');
    $('#view_formal input[name="data_id_view"]').val(callback['id']);
    $('#view_formal .header_data').html(callback['nama_sekolah']);
    $('#view_formal #data_nama_view').html(callback['nama_sekolah']);
    $('#view_formal #data_jenjang_view').html(callback['getjenjang_pendidikan']);
    $('#view_formal #data_jurusan_view').html(callback['jurusan']);
    $('#view_formal #data_fakultas_view').html(callback['fakultas']);
    $('#view_formal #data_masuk_view').html(callback['getvtahun_masuk']);
    $('#view_formal #data_keluar_view').html(callback['getvtahun_keluar']);
    $('#view_formal #data_alsekolah_view').html(callback['alamat_sekolah']);
    var status = callback['status'];
    if(status==1){
      var statusval = '<b class="text-success">Aktif</b>';
    }else{
      var statusval = '<b class="text-danger">Tidak Aktif</b>';
    }
    $('#view_formal #data_status_view').html(statusval);
    $('#view_formal #data_create_date_view').html(callback['create_date']+' WIB');
    $('#view_formal #data_update_date_view').html(callback['update_date']+' WIB');
    $('#view_formal #data_create_by_view').html(callback['nama_buat']);
    $('#view_formal #data_update_by_view').html(callback['nama_update']);
  }
  function view_modal_nformal(id) {
    var data={id_k_pnf:id};
    var callback=getAjaxData("<?php echo base_url().'employee/emp_nonformal/view_one/'.$profile['nik']; ?>",data);  
    $('#view_nformal').modal('toggle');
    $('#view_nformal input[name="data_id_view"]').val(callback['id']);
    $('#view_nformal .header_data').html(callback['nama_pnf']);
    $('#view_nformal #nama_pnf').html(callback['nama_pnf']);
    $('#view_nformal #tanggal_masuk_pnf').html(callback['getvtanggal_masuk_pnf']);
    $('#view_nformal #sertifikat_pnf').html(callback['sertifikat_pnf']);
    $('#view_nformal #nama_lembaga_pnf').html(callback['nama_lembaga_pnf']);
    $('#view_nformal #alamat_pnf').html(callback['alamat_pnf']);
    $('#view_nformal #keterangan_pnf').html(callback['keterangan_pnf']);
    var status = callback['status'];
    if(status==1){
      var statusval = '<b class="text-success">Aktif</b>';
    }else{
      var statusval = '<b class="text-danger">Tidak Aktif</b>';
    }
    $('#view_nformal #data_status_view').html(statusval);
    $('#view_nformal #data_create_date_view').html(callback['create_date']+' WIB');
    $('#view_nformal #data_update_date_view').html(callback['update_date']+' WIB');
    $('#view_nformal #data_create_by_view').html(callback['nama_buat']);
    $('#view_nformal #data_update_by_view').html(callback['nama_update']);
  }
  function view_modal_org(id) {
    var data={id_k_organisasi:id};
    var callback=getAjaxData("<?php echo base_url().'employee/emp_org/view_one/'.$profile['nik']; ?>",data);  
    $('#modal_org_view').modal('toggle');
    $('#modal_org_view input[name="data_id_view"]').val(callback['id']);
    $('#modal_org_view .header_data').html(callback['nama_organisasi']);
    $('#modal_org_view #nama_org').html(callback['nama_organisasi']);
    $('#modal_org_view #tanggal_masuk_org').html(callback['getvtahun_masuk']);
    $('#modal_org_view #tanggal_keluar_org').html(callback['getvtahun_keluar']);
    $('#modal_org_view #jabatan').html(callback['jabatan_org']);
    var status = callback['status'];
    if(status==1){
      var statusval = '<b class="text-success">Aktif</b>';
    }else{
      var statusval = '<b class="text-danger">Tidak Aktif</b>';
    }
    $('#modal_org_view #data_status_view').html(statusval);
    $('#modal_org_view #data_create_date_view').html(callback['create_date']+' WIB');
    $('#modal_org_view #data_update_date_view').html(callback['update_date']+' WIB');
    $('#modal_org_view #data_create_by_view').html(callback['nama_buat']);
    $('#modal_org_view #data_update_by_view').html(callback['nama_update']);
  }
  function view_modal_penghargaan(id) {
    var data={id_k_penghargaan:id};
    var callback=getAjaxData("<?php echo base_url().'employee/emp_hrg/view_one/'.$profile['nik']; ?>",data);  
    $('#modal_hrg_view').modal('toggle');
    $('#modal_hrg_view input[name="data_id_view"]').val(callback['id']);
    $('#modal_hrg_view .header_data').html(callback['nama_penghargaan']);
    $('#modal_hrg_view #nama_penghargaan').html(callback['nama_penghargaan']);
    $('#modal_hrg_view #tanggal').html(callback['tanggalv']);
    $('#modal_hrg_view #peringkat').html(callback['peringkat']);
    $('#modal_hrg_view #yg_menetapkan').html(callback['yg_menetapkan']);
    $('#modal_hrg_view #penyelenggara').html(callback['penyelenggara']);
    $('#modal_hrg_view #keterangan').html(callback['keterangan']);
    var status = callback['status'];
    if(status==1){
      var statusval = '<b class="text-success">Aktif</b>';
    }else{
      var statusval = '<b class="text-danger">Tidak Aktif</b>';
    }
    $('#modal_hrg_view #data_status_view').html(statusval);
    $('#modal_hrg_view #data_create_date_view').html(callback['create_date']+' WIB');
    $('#modal_hrg_view #data_update_date_view').html(callback['update_date']+' WIB');
    $('#modal_hrg_view #data_create_by_view').html(callback['nama_buat']);
    $('#modal_hrg_view #data_update_by_view').html(callback['nama_update']);
  }
  function view_modal_bahasa(id) {
    var data={id_k_bahasa:id};
    var callback=getAjaxData("<?php echo base_url().'employee/empbahasa/view_one/'.$profile['nik']; ?>",data);  
    $('#view_bahasa').modal('toggle');
    $('#view_bahasa input[name="data_id_view"]').val(callback['id']);
    $('#view_bahasa .header_data').html(callback['bahasav']);
    $('#view_bahasa #bahasa').html(callback['bahasav']);
    $('#view_bahasa #membaca').html(callback['membacav']);
    $('#view_bahasa #menulis').html(callback['menulisv']);
    $('#view_bahasa #berbicara').html(callback['berbicarav']);
    $('#view_bahasa #mendengar').html(callback['mendengarv']);
    var status = callback['status'];
    if(status==1){
      var statusval = '<b class="text-success">Aktif</b>';
    }else{
      var statusval = '<b class="text-danger">Tidak Aktif</b>';
    }
    $('#view_bahasa #data_status_view').html(statusval);
    $('#view_bahasa #data_create_date_view').html(callback['create_date']+' WIB');
    $('#view_bahasa #data_update_date_view').html(callback['update_date']+' WIB');
    $('#view_bahasa #data_create_by_view').html(callback['nama_buat']);
    $('#view_bahasa #data_update_by_view').html(callback['nama_update']);
  }
</script>