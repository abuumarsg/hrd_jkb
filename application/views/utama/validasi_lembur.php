  <div class="login-box-body" >
    <p class="login-box-msg"><b class="fnt">Validasi Lembur</b></p>
    <?php
      if ($validasi!=2) { 
        echo '<div class="alert alert-success">Anda Telah Memvalidasi Data Lembur ini</div>';
        echo '
        <a class="btn btn-flat btn-primary text-center" href="'.base_url('auth').'"><i class="fa fa-chevron-circle-left"></i> Login</a> 
        <a class="btn btn-flat btn-warning pull-right" href="'.base_url().'"><i class="fa fa-home"></i> Home</a><br>';
      }else{
    ?>
    <div class="row">
      <div class="col-xs-12">
        <h4>Mohon Validasi Lembur Karyawan berikut:</h4>
        <table width="100%" align='left'>  
          <tr>
            <th width="25%" align='left'>No Lembur</th>
            <td><?php echo $no_lembur;?></td>
          </tr>
          <tr>
            <th width="25%" align='left'>Tanggal Lembur</th>
            <td><?php echo $tanggal;?></td>
          </tr>
          <tr>
            <th width="25%" align='left'>Jumlah Lembur</th>
            <td><?php echo $jumlah_lembur;?></td>
          </tr>
          <tr>
            <th width="25%" align='left'>Dibuat Oleh</th>
            <td><?php echo $dibuat_oleh;?></td>
          </tr>
          <tr>
            <th width="25%" align='left'>Diperiksa Oleh</th>
            <td><?php echo $diperiksa_oleh;?></td>
          </tr>
          <tr>
            <th width="25%" align='left'>Diketahui Oleh</th>
            <td><?php echo $diketahui_oleh;?></td>
          </tr>
          <tr>
            <th width="25%" align='left'>Tanggal Dibuat</th>
            <td><?php echo $tgl_buat;?></td>
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
            <th width="25%" align='left'>Potong Jam</th>
            <td><?php echo $potong_jam;?></td>
          </tr>
          <tr>
            <th width="25%" align='left'>Keterangan</th>
            <td><?php echo $keterangan;?></td>
          </tr> 
        </table>
        <br>
        <br>
        <p class="login-box-msg"><b class="fnt">Terima Kasih</b></p>
        <br>
      </div>
      <div class="col-md-6 text-center">
          <input type="hidden" id="no_lembur" name="no_lembur" value="<?php echo $no_lembur ?>">
          <input type="hidden" id="date_now" name="date_noe" value="<?php echo $date_now ?>">
          <input type="hidden" id="token" name="token" value="<?php echo $token ?>">
				<button type="button" onclick="do_validasi_lembur(2,0)" class="btn btn-danger"><i class="fa fa-times-circle"></i> Tidak Diizinkan</button>
      </div>
      <div class="col-md-6 text-center">
          <button type="button" onclick="do_validasi_lembur(2,1)" class="btn btn-success"><i class="fa fa-check-circle"></i> Diizinkan</button>
      </div>
    </div>
    <hr>
    <div class="row">
        <p class="login-box-msg"><b class="fnt">Jika Ada Penyesuaian Waktu Lembur Atau Jumlah Karyawan, Silahkan Validasi Lembur Lewat Aplikasi</b><br><br>
        <a class="btn btn-primary text-center" href="<?=base_url('auth')?>"><i class="fa fa-chevron-circle-left"></i> Login Aplikasi</a> </p>
    </div>
  </div>
  <?php } ?>
  <br>
<script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
<script type="text/javascript">
  	function do_validasi_lembur(data,val){
			var no_lembur = $('#no_lembur').val();
			var date_now = $('#date_now').val();
			var token = $('#token').val();
		  var datax={no_lembur:no_lembur,validasi_db:data,validasi:val,date_now:date_now,token:token};
		  submitAjax("<?php echo base_url('auth/change_validasi_lembur')?>",null,datax,null,null,'status');
      setTimeout(function () {
        window.location.href = "<?php echo site_url('auth')?>";
      },3000); 
  	}
</script>