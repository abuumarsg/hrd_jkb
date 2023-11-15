<table id="view_info" class="table table-bordered table-striped table-responsive" width="100%">
   <tr>
      <th>Nomor Induk Karyawan</th>
      <td id="view_nik"><?php echo ucwords($profile['nik']);?> <label class="label label-info">Username</label></td>
   </tr>
   <tr>
      <th>ID Finger</th>
      <td id="view_finger">
         <?php
         if ($profile['id_finger'] == NULL) {
            echo '<label class="label label-danger">ID Finger Tidak Ada</label>';
         }else{
            echo $profile['id_finger'];
         }
         ?>
      </td>
   </tr>
   <tr>
      <th>Nomor KTP</th>
      <td id="view_ktp">
         <?php
         if ($profile['no_ktp'] == NULL) {
            echo '<label class="label label-danger">Nomor KTP Tidak Ada</label>';
         }else{
            echo $profile['no_ktp'];
         }
         ?>
      </td>
   </tr>
   <tr>
      <th>Nama Lengkap</th>
      <td id="view_nama"><?php echo ucwords($profile['nama']);?></td>
   </tr>
   <tr>
      <th>Alamat Asli</th>
      <td><?php
      if ($profile['alamat_asal_jalan'] == NULL || $profile['alamat_asal_jalan'] == "") {
         echo '<label class="label label-danger">Alamat Belum Diinput</label>';
      }else{
         $alamat_skrg=(!empty($profile['alamat_asal_jalan'])?$profile['alamat_asal_jalan'].', ':null).(!empty($profile['alamat_asal_desa'])?$profile['alamat_asal_desa'].', ':null).(!empty($profile['alamat_asal_kecamatan'])?$profile['alamat_asal_kecamatan'].', ':null).(!empty($profile['alamat_asal_kabupaten'])?$profile['alamat_asal_kabupaten'].', ':null).(!empty($profile['alamat_asal_provinsi'])?$profile['alamat_asal_provinsi'].', ':null);
         echo ucwords($alamat_skrg).' <br>Kode Pos : ';
         if ($profile['alamat_asal_pos'] == 0) {
            echo '<label class="label label-warning">Tidak Ada Kode Pos</label>';
         }else{
            echo ucwords($profile['alamat_asal_pos']);
         }
      }  ?>
      </td>
   </tr>
   <tr>
      <th>Alamat Sekarang</th>
      <td><?php
      if ($profile['alamat_sekarang_jalan'] == NULL || $profile['alamat_sekarang_jalan'] == "") {
         echo '<label class="label label-danger">Alamat Belum Diinput</label>';
      }else{
         $alamat_skrg=(!empty($profile['alamat_sekarang_jalan'])?$profile['alamat_sekarang_jalan'].', ':null).(!empty($profile['alamat_sekarang_desa'])?$profile['alamat_sekarang_desa'].', ':null).(!empty($profile['alamat_sekarang_kecamatan'])?$profile['alamat_sekarang_kecamatan'].', ':null).(!empty($profile['alamat_sekarang_kabupaten'])?$profile['alamat_sekarang_kabupaten'].', ':null).(!empty($profile['alamat_sekarang_provinsi'])?$profile['alamat_sekarang_provinsi'].', ':null);
         echo ucwords($alamat_skrg).' <br>Kode Pos : ';
         if ($profile['alamat_sekarang_pos'] == 0) {
            echo '<label class="label label-warning">Tidak Ada Kode Pos</label>';
         }else{
            echo ucwords($profile['alamat_sekarang_pos']);
         }
      }  ?>
      </td>
   </tr>
   <tr>
      <th>Agama</th>
      <td id="view_agama"><?php echo ucwords($profile['agama']);?></td>
   </tr>
   <tr>
      <th>Golongan Darah</th>
      <td id="view_goldar">
         <?php
         if ($profile['gol_darah'] == NULL) {
            echo '<label class="label label-danger">Golongan Darah Tidak Ada</label>';
         }else{
            echo ucwords($profile['gol_darah']);
         }
         ?>
      </td>
   </tr>  
   <tr>
      <th>Tinggi | Berat Badan</th>
      <td id="view_bbtb">
         <?php
         if ($profile['tinggi_badan'] == 0) {
            echo '<label class="label label-danger">Tinggi Badan Tidak Ada</label>';
         }else{
            echo $profile['tinggi_badan'].' Cm';
         }
         echo ' | ';
         if ($profile['berat_badan'] == 0) {
            echo '<label class="label label-danger">Berat Badan Tidak Ada</label>';
         }else{
            echo $profile['berat_badan'].' Kg';
         }
         ?>

      </td>
   </tr>  
   <tr>
      <th>Email</th>
      <td id="view_email">
         <?php
         if ($profile['email'] == NULL) {
            echo '<label class="label label-danger">Email Tidak Ada</label>';
         }else{
            if ($profile['email_verified'] == 0) {
               echo '<a data-toggle="tooltip" title="Email Belum Diverifikasi">'.$profile['email'].'</a>';
               echo ' <i style="color:red;" class="fa fa-warning"></i>';
            }else{
               echo $profile['email'].' <i style="color:green;" data-toggle="tooltip" title="Terverifikasi" class="fa fa-check-circle"></i>';
            }
         }
         ?>

      </td>
   </tr>
   <tr>
      <th>Jenis Kelamin</th>
      <td id="view_jk">
         <?php
         if($profile['kelamin'] == 'l'){
            echo '<i class="fa fa-male" style="color:blue"></i> Laki-laki';
         }else{
            echo '<i class="fa fa-female" style="color:#ff00a5"></i> Perempuan';
         }?>
      </td>
   </tr>
   <tr>
      <th>Tempat Tanggal Lahir</th>
      <td id="view_tgllahir">
         <?php 
         if($profile['tempat_lahir']!='' || $profile['tgl_lahir']!=''){
            if($profile['tempat_lahir'] != NULL){
               echo $profile['tempat_lahir'].', ';
            } 
            echo $this->formatter->getDateMonthFormatUser($profile['tgl_lahir']);
         }else{
            echo '<label class="label label-danger">Tempat Tanggal Lahir Tidak Ada</label>';
         }
         ?>
      </td>
   </tr>
   <tr>
      <th>Nomor Ponsel</th>
      <td id="view_ponsel">
         <?php
         if ($profile['no_hp'] == NULL) {
            echo '<label class="label label-danger">Nomor Ponsel Tidak Ada</label>';
         }else{
            echo ucwords($profile['no_hp']);
         }
         ?>

      </td>
   </tr>
   <tr>
      <th>Nomor Penting</th>
      <td>
         <table class="table table-striped">  
            <tr>
               <th width="20%">Rekening</th>
               <td id="view_rek"><?php echo ucwords($profile['rekening']);?></td>
            </tr>  
            <tr>
               <th>NPWP</th>
               <td id="view_npwp">
                  <?php
                  if ($profile['npwp'] == NULL) {
                     echo '<label class="label label-danger">Tidak Memiliki NPWP</label>';
                  }else{
                     echo ucwords($profile['npwp']);
                  }
                  echo ' <label class="label label-success">'.$profile['status_pajak'].'</label>';
                  ?>
               </td>
            </tr>  
            <tr>
               <th>BPJS Kesehatan</th>
               <td id="view_bpkes">
                  <?php
                  if ($profile['bpjskes'] == NULL) {
                     echo '<label class="label label-danger">Tidak Memiliki BPJS Kesehatan</label>';
                  }else{
                     echo ucwords($profile['bpjskes']);
                  }
                  ?>
               </td>
            </tr>  
            <tr>
               <th>BPJS Tenaga Kerja</th>
               <td id="view_bptek">
                  <?php
                  if ($profile['bpjstk'] == NULL) {
                     echo '<label class="label label-danger">Tidak Memiliki BPJS Tenaga Kerja</label>';
                  }else{
                     echo ucwords($profile['bpjstk']);
                  }
                  ?>
               </td>
            </tr>  
            <tr>
               <th>Nomor Ponsel Ibu</th>
               <td class="view_hpibu">
                  <?php
                  echo (!empty($profile['no_hp_ibu'])) ? $profile['no_hp_ibu']: $this->otherfunctions->getCustomMark($profile['no_hp_ibu'],'<label class="label label-danger">Nomor Ponsel Ibu Tidak Ada</label>');
                  ?>
               </td>
            </tr>  
            <tr>
               <th>Nomor Ponsel Ayah</th>
               <td class="view_hpayah">
                  <?php
                  echo (!empty($profile['no_hp_ayah'])) ? $profile['no_hp_ayah']: $this->otherfunctions->getCustomMark($profile['no_hp_ayah'],'<label class="label label-danger">Nomor Ponsel Ayah Tidak Ada</label>');
                  ?>
               </td>
            </tr>  
            <tr>
               <th>Nomor Ponsel Pasangan</th>
               <td class="view_hppasang">
                  <?php
                  echo (!empty($profile['no_hp_pasangan'])) ? $profile['no_hp_pasangan']: $this->otherfunctions->getCustomMark($profile['no_hp_pasangan'],'<label class="label label-danger">Nomor Ponsel Pasangan Tidak Ada</label>');
                  ?>
               </td>
            </tr>  
         </table>
      </td>
   </tr>  
   <tr>
      <th>Pendidikan</th>
      <td>
         <table class="table table-striped">
            <tr>
               <th width="20%">Jenjang</th>
               <td class="view_pddk">
               </td>
            </tr>
            <tr>
               <th>Universitas/Sekolah</th>
               <td class="view_skl">
               </td>
            </tr>
            <tr>
               <th>Jurusan</th>
               <td class="view_jurusan">
               </td>
            </tr>
         </table>
      </td>
   </tr>  
   <tr>
      <th>Data Keluarga</th>
      <td>
         <table class="table table-striped">
            <tr>
               <th width="20%">Ibu Kandung</th>
               <td class="view_ibu">
                  <?php
                  echo (!empty($profile['nama_ibu'])) ? $profile['nama_ibu']: $this->otherfunctions->getCustomMark($profile['nama_ibu'],'<label class="label label-danger">Data Ibu Kandung Tidak Ada</label>');
                  ?>
               </td>
            </tr>
            <tr>
               <th>Ayah Kandung</th>
               <td class="view_ayah">
                  <?php
                  echo (!empty($profile['nama_ayah'])) ? $profile['nama_ayah']: $this->otherfunctions->getCustomMark($profile['nama_ayah'],'<label class="label label-danger">Data Ayah Kandung Tidak Ada</label>');
                  ?>
               </td>
            </tr>
            <tr>
               <th>Status Menikah</th>
               <td id="view_nikah">
                  <?php
                  echo (!empty($profile['status_nikah'])) ? $profile['status_nikah']: $this->otherfunctions->getCustomMark($profile['status_nikah'],'<label class="label label-danger">Data Status Nikah Tidak Ada</label>');
                  ?>
               </td>
            </tr>
            <tr>
               <th>Nama Pasangan</th>
               <td class="view_pasang">
                  <?php
                  echo (!empty($profile['nama_pasangan'])) ? $profile['nama_pasangan']: $this->otherfunctions->getCustomMark($profile['nama_pasangan'],'<label class="label label-danger">Data Nama Pasangan Tidak Ada</label>');
                  ?>
               </td>
            </tr>
            <tr>
               <th>Jumlah Anak</th>
               <td class="view_jml_anak">
               </td>
            </tr>
         </table>
      </td>
   </tr>  
   <tr>
      <th>Tanggal Daftar</th>
      <td id="view_tgldaf">
         <?php
         echo $this->formatter->getDateTimeMonthFormatUser($profile['create_date']);
         ?>
      </td>
   </tr>
   <tr>
      <th>Tanggal Update</th>
      <td id="view_tglupd">
         <?php
         echo $this->formatter->getDateTimeMonthFormatUser($profile['update_date']);
         ?>
      </td>
   </tr>
   <tr>
      <th>Terakhir Login</th>
      <td id="view_laslog">
         <label class="label label-primary">
            <?php
            if ($profile['last_login'] == "0000-00-00 00:00:00") {
               echo 'Belum Pernah Login';
            }else{
               echo date('d/m/Y H:i:s',strtotime($profile['last_login']));
            }
            ?>
         </label>
      </td>
   </tr>
</table>
<script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
<script type="text/javascript">
  $(document).ready(function(){
    data_info_non();//form_property();all_property();set_interval()reset_interval();
  })
  function data_info_non(){
    var nik = '<?php echo $profile['nik']; ?>';
    var data={nik:nik};
    var callback=getAjaxData("<?php echo base_url('employee/emppribadi')?>",data);
    $('.view_jml_anak').html(callback['jumlah_anak']);
    $('.view_pddk').html(callback['maxJenjang']);
    $('.view_skl').html(callback['maxSekolah']);
    $('.view_jurusan').html(callback['MaxJurusan']);
  }
</script>