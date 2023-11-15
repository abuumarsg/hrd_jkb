<script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>

<table class='table table-bordered table-striped table-hover'>
	<fieldset>
		<legend><i class="fas fa-exchange-alt"></i> Data Mutasi Jabatan</legend>
	</fieldset>
	<tr>
		<th width="20%">Mutasi Jabatan</th>
		<td>
			<?php if (count($mutasi) > 0) { ?>
			<?php $no6=1; foreach ($mutasi as $mut) {
			$kar  =$this->model_karyawan->emp_nik($mut->nik);
			$kar_m  =$this->model_karyawan->emp_nik($mut->yg_menetapkan);
			$lkr_a  =$this->model_master->k_loker($mut->lokasi_asal);
			$loker  =$this->model_master->k_loker($mut->lokasi);
			$jab  =$this->model_master->k_jabatan($mut->jabatan_asal);
			$jabb   =$this->model_master->k_jabatan($mut->jabatan_baru);
			$jab_m  =$this->model_master->k_jabatan($kar_m['jabatan']); ?>
			<table class="table">
				<th width="25%">Mutasi Jabatan ke - <?php echo $no6; ?></th>
					<tr>
					<th width="20%">Nomor Sk</th>
					<td><?php echo ucwords($mut->no_sk);?></td>
				</tr>
				<tr>
					<th>Tanggal SK</th>
					<td><?php
					if ($mut->tgl_sk == NULL) {
					echo '<label class="label label-danger">Nomor SK Tidak Ada</label>';
					}else{
					echo ucwords($this->formatter->getDateMonthFormatUser($mut->tgl_sk));
					}?>
					</td>
				</tr>
				<tr>
					<th>Tanggal Berlaku</th>
					<td><?php
					if ($mut->tgl_berlaku == NULL) {
					echo '<label class="label label-danger">Tanggal Tidak Diinputkan</label>';
					}else{
					echo ucwords($this->formatter->getDateMonthFormatUser($mut->tgl_berlaku));
					}?>
					</td>
				</tr>
				<tr>
					<th>Status Mutasi</th>
					<td><?php
					if ($mut->status_mutasi == NULL) {
					echo '<label class="label label-danger">Status Mutasi Tidak Ada</label>';
					}else{
					echo ucwords($mut->status_mutasi);
					}?>
					</td>
				</tr>
				<tr>
					<th>Jabatan Lama</th>
					<td><?php
					if ($mut->jabatan_asal == NULL) {
					echo '<label class="label label-danger">Jabatan Lama Tidak Ada</label>';
					}else{
					echo ucwords($mut->jabatan_asal.' - '.$jab['jabatan']);
					}?>
					</td>
				</tr>
				<tr>
					<th>Jabatan Baru</th>
					<td><?php
					if ($mut->jabatan_baru == NULL) {
					echo '<label class="label label-danger">Jabatan Baru Tidak Ada</label>';
					}else{
					echo ucwords($mut->jabatan_baru.' - '.$jabb['jabatan']);
					}?>
					</td>
				</tr>
				<tr>
					<th>Unit Kerja Lama</th>
					<td><?php
					if ($mut->lokasi_asal == NULL) {
					echo '<label class="label label-danger">Unit Kerja Lama Tidak Ada</label>';
					}else{
					echo ucwords($mut->lokasi_asal.' - '.$lkr_a['nama']);
					}?>
					</td>
				</tr>
				<tr>
					<th>Unit Kerja Baru</th>
					<td><?php
					if ($mut->lokasi == NULL) {
					echo '<label class="label label-danger">Unit Kerja Baru Tidak Ada</label>';
					}else{
					echo ucwords($mut->lokasi.' - '.$loker['nama']);
					}?>
					</td>
				</tr>
				<tr>
					<th>Yang Menetapkan</th>
					<td><?php
					if ($mut->yg_menetapkan == NULL) {
					echo '<label class="label label-danger">Yang menetapkan Tidak Ada</label>';
					}else{
					echo ucwords($kar_m['nama'].' - '.$jab_m['jabatan']);
					}?>
					</td>
				</tr>
				<tr>
					<th>Tembusan</th>
					<td><?php
					if ($mut->keterangan == NULL) {
					echo '<label class="label label-danger">Tembusan Tidak Ada</label>';
					}else{
					echo ucwords($mut->keterangan);
					}?>
					</td>
				</tr>
			</table>
			<?php $no6++; } }else{ ?>
				<label class="label label-danger"><i class="fa fa-info-circle"></i> Tidak Ada Data Mutasi Jabatan</label>
			<?php } ?>
		</td>
	</tr>
</table>

<br>
<fieldset>
  <legend><i class="fas fa-exchange-alt"></i> Data Mutasi Grade</legend>
</fieldset>
<table class='table table-bordered table-striped table-hover'>
  <tr>
    <th width="20%">Mutasi Grade</th>
    <td>
      <?php if (count($mutasi_g) > 0) {
      	$no7=1; foreach ($mutasi_g as $mut_g) {
        $kar_m  =$this->model_karyawan->emp_nik($mut_g->yg_menetapkan);
        $jab_m  =$this->model_master->k_jabatan($kar_m['jabatan']);
      ?>
      <table class="table">
      	<tr>
        	<th width="25%" colspan="2">Mutasi Grade ke - <?php echo $no7; ?></th>
      	</tr>
        <tr>
          <th width="20%">Nomor Sk</th>
          <td><?php echo ucwords($mut_g->no_sk);?></td>
        </tr>
        <tr>
          <th>Tanggal SK</th>
          <td>
          	<?php
		          if ($mut_g->tgl_sk == NULL) {
		            echo '<label class="label label-danger">Nomor SK Tidak Ada</label>';
		          }else{
		            echo ucwords($this->formatter->getDateMonthFormatUser($mut_g->tgl_sk));
		          }
	          ?>
          </td>
        </tr>
        <tr>
          <th>Tanggal Berlaku</th>
          <td><?php
	          if ($mut_g->tgl_berlaku == NULL) {
	            echo '<label class="label label-danger">Tanggal Tidak Diinputkan</label>';
	          }else{
	            echo ucwords($this->formatter->getDateMonthFormatUser($mut_g->tgl_berlaku));
	          }?>
	        </td>
	      </tr>
	      <tr>
	        <th>Status Mutasi</th>
	        <td><?php
		        if ($mut_g->jenis == NULL) {
		          echo '<label class="label label-danger">Status Mutasi Tidak Ada</label>';
		        }else{
		          echo ucwords($mut_g->jenis);
		        }?>
		      </td>
	    	</tr>
		    <tr>
		      <th>Grade Lama</th>
		      <td><?php
			      if ($mut_g->grade_asal == NULL) {
			        echo '<label class="label label-danger">Grade Lama Tidak Ada</label>';
			      }else{
			        echo ucwords($mut_g->grade_asal);
			      }?>
			    </td>
			  </tr>
			  <tr>
			    <th>Grade Baru</th>
			    <td><?php if ($mut_g->grade_baru == NULL) {
				      echo '<label class="label label-danger">Grade Baru Tidak Ada</label>';
				    }else{
				      echo ucwords($mut_g->grade_baru);
				    }?>
				  </td>
				</tr>
				<tr>
				  <th>Yang Menetapkan</th>
				  <td><?php
					  if ($mut_g->yg_menetapkan == NULL) {
					    echo '<label class="label label-danger">Yang menetapkan Tidak Ada</label>';
					  }else{
					    echo ucwords($kar_m['nama'].' - '.$jab_m['jabatan']);
					  }?>
					</td>
				</tr>
				<tr>
					<th>Tembusan</th>
					<td><?php
					if ($mut_g->keterangan == NULL) {
					  echo '<label class="label label-danger">Tembusan Tidak Ada</label>';
					}else{
					  echo ucwords($mut_g->keterangan);
					}?>
					</td>
				</tr>
			</table>
			<?php $no7++; }
			}else{
				echo '<label class="label label-danger"><i class="fa fa-info-circle"></i> Tidak Ada Data Mutasi Grade</label>';
			} ?>
		</td>
	</tr>
</table>
<table class='table table-bordered table-striped table-hover'>
	<fieldset>
		<legend><i class="fa fa-refresh"></i> Data Perubahan Status</legend>
	</fieldset>
	<tr>
		<th width="20%">Perubahan Status</th>
		<td>
			<?php if (count($per_stt) > 0) { ?>
			<?php $no8=1; foreach ($per_stt as $ps) {
			$kar_m  =$this->model_karyawan->emp_nik($ps->yg_menetapkan);
			$jab_m  =$this->model_master->k_jabatan($kar_m['jabatan']); ?>
			<table class="table">
				<th width="25%">Perubahan Status ke - <?php echo $no8; ?></th>
				<tr>
					<th width="20%">Nomor Sk</th>
					<td><?php echo ucwords($ps->no_sk_baru);?></td>
				</tr>
				<tr>
					<th>Tanggal SK</th>
					<td><?php
					if ($ps->tgl_sk_baru == NULL) {
					echo '<label class="label label-danger">Tanggal SK Tidak Ada</label>';
					}else{
					echo ucwords($this->formatter->getDateMonthFormatUser($ps->tgl_sk_baru));
					}?>
					</td>
				</tr>
				<tr>
					<th>Tanggal Berlaku</th>
					<td><?php
					if ($ps->tgl_berlaku_baru == NULL) {
					echo '<label class="label label-danger">Tanggal Tidak Diinputkan</label>';
					}else{
					echo ucwords($this->formatter->getDateMonthFormatUser($ps->tgl_berlaku_baru));
					}?>
					</td>
				</tr>
				<tr>
					<th>Tanggal Berlaku Sampai</th>
					<td><?php
					if ($ps->berlaku_sampai_baru == NULL) {
					echo '<label class="label label-danger">Tanggal Tidak Diinputkan</label>';
					}else{
					echo ucwords($this->formatter->getDateMonthFormatUser($ps->berlaku_sampai_baru));
					}?>
					</td>
				</tr>
				<tr>
					<th>Yang Menetapkan</th>
					<td><?php
					if ($ps->yg_menetapkan == NULL) {
					echo '<label class="label label-danger">Yang menetapkan Tidak Ada</label>';
					}else{
					echo ucwords($kar_m['nama'].' - '.$jab_m['jabatan']);
					}?>
				</td>
				</tr>
				<tr>
					<th>Mengetahui</th>
					<td><?php
					if ($ps->mengetahui == NULL) {
					echo '<label class="label label-danger">Data Mengetahui Kosong</label>';
					}else{
					echo ucwords($ps->mengetahui);
					}?>
					</td>
				</tr>
				<tr>
					<th>Tembusan</th>
					<td><?php
					if ($ps->keterangan == NULL) {
					echo '<label class="label label-danger">Tembusan Tidak Ada</label>';
					}else{
					echo ucwords($ps->keterangan);
					}?>
					</td>
				</tr>
			</table>
			<?php $no8++; }
			}else{
			echo '<label class="label label-danger"><i class="fa fa-info-circle"></i> Tidak Ada Data Perubahan Status</label>';
			} ?>
		</td>
	</tr>
</table>
<table class='table table-bordered table-striped table-hover'>
	<fieldset>
	<legend><i class="fa fa-street-view"></i> Data Disiplin Karyawan</legend>
	</fieldset>
	<tr>
		<th width="20%">Disiplin Karyawan</th>
		<td>
			<?php if (count($dk_non) > 0) { ?>
			<?php $no9=1; foreach ($dk_non as $dk) {
			$kar_m  =$this->model_karyawan->emp_nik($dk->yg_menetapkan);
			$jab_m  =$this->model_master->k_jabatan($kar_m['jabatan']);
			$disip   = $this->model_karyawan->k_dk($dk->status_baru); ?>
			<table class="table">
				<th width="25%">Disiplin Karyawan ke - <?php echo $no9; ?></th>
				<tr>
					<th width="20%">Nomor Sk</th>
					<td><?php echo ucwords($dk->no_sk);?></td>
				</tr>
				<tr>
					<th>Tanggal SK</th>
					<td><?php
					if ($dk->tgl_sk == NULL) {
					echo '<label class="label label-danger">Tanggal SK Tidak Ada</label>';
					}else{
					echo ucwords($this->formatter->getDateMonthFormatUser($dk->tgl_sk));
					}?>
					</td>
				</tr>
				<tr>
					<th>Tanggal Berlaku</th>
					<td><?php
					if ($dk->tgl_berlaku == NULL) {
					echo '<label class="label label-danger">Tanggal Tidak Diinputkan</label>';
					}else{
					echo ucwords($this->formatter->getDateMonthFormatUser($dk->tgl_berlaku));
					}?>
					</td>
				</tr>
				<tr>
					<th>Status Disiplin Sebelumnya</th>
					<td><?php
					if ($dk->status_asal == NULL) {
					echo '<label class="label label-danger">Tidak Ada Status Sebelumnya</label>';
					}else{
					echo ucwords($dk->status_asal);
					}?>
					</td>
				</tr>
				<tr>
				<th>Status Disiplin Baru</th>
					<td><?php
					if ($disip['nama'] == NULL) {
					echo '<label class="label label-danger">Tidak Ada Status Baru</label>';
					}else{
					echo ucwords($disip['nama']);
					}?>
					</td>
				</tr>
				<tr>
					<th>Yang Menetapkan</th>
					<td><?php
					if ($dk->yg_menetapkan == NULL) {
					echo '<label class="label label-danger">Yang menetapkan Tidak Ada</label>';
					}else{
					echo ucwords($kar_m['nama'].' - '.$jab_m['jabatan']);
					}?>
					</td>
				</tr>
				<tr>
					<th>Tembusan</th>
					<td><?php
					if ($dk->keterangan == NULL) {
					echo '<label class="label label-danger">Tembusan Tidak Ada</label>';
					}else{
					echo ucwords($dk->keterangan);
					}?>
					</td>
				</tr>
			</table>
			<?php $no9++; }
			}else{
			echo '<label class="label label-danger"><i class="fa fa-info-circle"></i> Tidak Ada Data Perubahan Status</label>';
			} ?>
		</td>
	</tr>
</table>