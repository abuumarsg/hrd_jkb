  <div class="login-box-body" >
    <p class="login-box-msg"><b class="fnt">Validasi Perjalanan Dinas</b></p>
    <?php
      if ($validasi!=2) { 
        echo '<div class="alert alert-success">Anda Telah Memvalidasi Data Perjalanan Dinas ini</div>';
        echo '
        <a class="btn btn-flat btn-primary text-center" href="'.base_url('auth').'"><i class="fa fa-chevron-circle-left"></i> Login</a> 
        <a class="btn btn-flat btn-warning pull-right" href="'.base_url().'"><i class="fa fa-home"></i> Home</a><br>';
      }else{
    ?>
    <div class="row">
      <div class="col-xs-12">
        <h4>Mohon Validasi Perjalanan Dinas Karyawan berikut:</h4>
        <table width="100%" align='left'>  
          <tr>
            <th width="25%" align='left'>No Perjalanan Dinas</th>
            <td><?php echo $no_perdin;?></td>
          </tr>
          <tr>
            <th width="25%" align='left'>Tanggal Berangkat</th>
            <td><?php echo $tgl_berangkat;?></td>
          </tr>
          <tr>
            <th width="25%" align='left'>Tanggal Sampai</th>
            <td><?php echo $tgl_sampai;?></td>
          </tr>
          <tr>
            <th width="25%" align='left'>Tujuan</th>
            <td><?php echo $plant;?></td>
          </tr>
          <tr>
            <th width="25%" align='left'>Lokasi Awal</th>
            <td><?php echo $plant_asal;?></td>
          </tr>
          <tr>
            <th width="25%" align='left'>Lokasi Tujuan</th>
            <td><?php echo $tujuan;?></td>
          </tr>
          <tr>
            <th width="25%" align='left'>Jarak</th>
            <td><?php echo $jarak;?> KM</td>
          </tr>
          <tr>
            <th width="25%" align='left'>Kendaraan</th>
            <td><?php echo $kendaraan;?></td>
          </tr>
          <tr>
            <th width="25%" align='left'>Jumlah Kendaraan</th>
            <td><?php echo $jum_kendaraan;?> Kendaraan</td>
          </tr>
          <tr>
            <th width="25%" align='left'>Nominal per Kendaraan</th>
            <td><?php echo $nominal_per_kendaraan;?></td>
          </tr> 
          <tr>
            <th width="25%" align='left'>Nominal BBM</th>
            <td><?php echo $nominal_bbm;?></td>
          </tr> 
          <tr>
            <th width="25%" align='left'>Nominal Penginapan</th>
            <td><?php echo $nominal_penginapan;?></td>
          </tr> 
          <tr>
            <th width="25%" align='left'>Nama Penginapan</th>
            <td><?php echo $penginapan;?></td>
          </tr> 
          <tr>
            <th width="25%" align='left'>Tugas</th>
            <td><?php echo $tugas;?></td>
          </tr> 
          <tr>
            <th width="25%" align='left'>Keterangan</th>
            <td><?php echo $keterangan;?></td>
          </tr>
          <tr>
            <th width="25%" align='left'>Jumlah Karyawan</th>
            <td><?php echo $jumlah_karyawan;?> Karyawan</td>
          </tr>
          <tr>
            <th width="25%" align='left'>Karyawan</th>
            <td><?php echo $karyawan;?></td>
          </tr>
          <tr>
            <th width="25%" align='left'>Di buat</th>
            <td><?php echo $dibuat;?></td>
          </tr> 
          <tr>
            <th width="25%" align='left'>Mengetahui</th>
            <td><?php echo $mengetahui;?></td>
          </tr> 
          <tr>
            <th width="25%" align='left'>Menyetujui</th>
            <td><?php echo $menyetujui;?></td>
          </tr> 
        </table>
        <br>
        <br>
        <p class="login-box-msg"><b class="fnt">Terima Kasih</b></p>
        <br>
      </div>
      <div class="col-md-6 text-center">
          <input type="hidden" id="no_perdin" name="no_perdin" value="<?php echo $no_perdin;?>">
          <input type="hidden" id="date_now" name="date_noe" value="<?php echo $date_now;?>">
          <input type="hidden" id="token" name="token" value="<?php echo $token;?>">
				<button type="button" onclick="do_validasi_perdin(2,0)" class="btn btn-danger"><i class="fa fa-times-circle"></i> Tidak Diizinkan</button>
      </div>
      <div class="col-md-6 text-center">
          <button type="button" onclick="do_validasi_perdin(2,1)" class="btn btn-success"><i class="fa fa-check-circle"></i> Diizinkan</button>
      </div>
    </div>
    <hr>
    <div class="row">
        <p class="login-box-msg"><b class="fnt">Jika Ada Penyesuaian Waktu Perjalanan Dinas Atau Jumlah Karyawan, Silahkan Validasi Perjalanan Dinas Lewat Aplikasi</b><br><br>
        <a class="btn btn-primary text-center" href="<?=base_url('auth')?>"><i class="fa fa-chevron-circle-left"></i> Login Aplikasi</a> </p>
    </div>
  </div>
  <?php } ?>
  <br>
<script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
<script type="text/javascript">
  	function do_validasi_perdin(data,val){
			var no_perdin = $('#no_perdin').val();
			var date_now = $('#date_now').val();
			var token = $('#token').val();
		  var datax={no_perdin:no_perdin,validasi_db:data,validasi:val,date_now:date_now,token:token};
		  submitAjax("<?php echo base_url('auth/change_validasi_perdin')?>",null,datax,null,null,'status');
      setTimeout(function () {
        window.location.href = "<?php echo site_url('auth')?>";
      },3000); 
  	}
</script>