  <div class="login-box-body" >
    <p class="login-box-msg"><b class="fnt">Validasi Izin/Cuti</b></p>
    <?php
      if ($validasi!=2) { 
        echo '<div class="alert alert-success">Anda Telah Memvalidasi Data Izin/Cuti ini</div>';
        echo '
        <a class="btn btn-flat btn-primary text-center" href="'.base_url('auth').'"><i class="fa fa-chevron-circle-left"></i> Login</a> 
        <a class="btn btn-flat btn-warning pull-right" href="'.base_url().'"><i class="fa fa-home"></i> Home</a><br>';
      }else{
    ?>
    <div class="row">
      <div class="col-xs-12">
        <h4>Mohon Validasi Izin/Cuti Karyawan berikut:</h4>
        <table width="100%" align='left'>  
          <tr>
            <th width="25%" align='left'>Nama</th>
            <td><?php echo $nama;?></td>
          </tr>
          <tr>
            <th width="25%" align='left'>Alamat</th>
            <td><?php echo $alamat;?></td>
          </tr>
          <tr>
            <th width="25%" align='left'>NIK</th>
            <td><?php echo $nik;?></td>
          </tr>
          <tr>
            <th width="25%" align='left'>Jabatan</th>
            <td><?php echo $jabatan;?></td>
          </tr>
          <tr>
            <th width="25%" align='left'>Bagian</th>
            <td><?php echo $bagian;?></td>
          </tr>
          <tr>
            <th width="25%" align='left'>Lokasi Kerja</th>
            <td><?php echo $loker;?></td>
          </tr>
          <tr>
            <th width="25%" align='left'>Tanggal</th>
            <td><?php echo $tanggal;?></td>
          </tr>
          <tr>
            <th width="25%" align='left'>Lama Izin/Cuti</th>
            <td><?php echo $lama_izin;?></td>
          </tr>
          <tr>
            <th width="25%" align='left'>Jenis Izin/Cuti</th>
            <td><?php echo $jenis_izin_cuti;?></td>
          </tr>
          <tr>
            <th width="25%" align='left'>Alasan</th>
            <td><?php echo $alasan;?></td>
          </tr>
          <tr>
            <th width="25%" align='left'>Keterangan</th>
            <td><?php echo $keterangan;?></td>
          </tr> 
        </table>
        <br>
        <br>
        <br>
        <br>
        <h4>Terimakasih.</h4>
        <br>
      </div>
       <div class="col-md-6 text-center">
          <input type="hidden" id="id" name="id" value="<?php echo $id ?>">
          <input type="hidden" id="idk" name="idk" value="<?php echo $idk ?>">
          <input type="hidden" id="jenis" name="jenis" value="<?php echo $jenis ?>">
          <input type="hidden" id="create_date" name="create_date" value="<?php echo $create_date ?>">
          <input type="hidden" id="token" name="token" value="<?php echo $token ?>">
				<button type="button" onclick="do_validasi(2,0)" class="btn btn-danger"><i class="fa fa-times-circle"></i> Tidak Diizinkan</button>
      </div>
      <div class="col-md-6 text-center">
          <button type="button" onclick="do_validasi(2,1)" class="btn btn-success"><i class="fa fa-check-circle"></i> Diizinkan</button>
      </div>
    </div>
  </div>
  <?php } ?>
  <br>
<script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
<script type="text/javascript">
	$(document).ready(function(){
  });
  	function do_validasi(data,val){
			var id = $('#id').val();
			var idk = $('#idk').val();
			var jenis = $('#jenis').val();
			var create_date = $('#create_date').val();
			var token = $('#token').val();
		  var datax={id_izin_cuti:id,id_k:idk,validasi_db:data,validasi:val,jenis_db:jenis,create_date:create_date,token:token};
		  submitAjax("<?php echo base_url('auth/change_validasi_izin')?>",null,datax,null,null,'status');
      setTimeout(function () {
        window.location.href = "<?php echo site_url('auth')?>";
      },3000); 
  	}
</script>