<div class="wrapper">
	<div class="row">
		
		<div class="col-md-12 text-center">
			<div class="col-md-3">
				<img src="<?php echo base_url('asset/img/logo.png');?>" width="200px">
			</div>
			<div class="col-md-9">
				<h3 class="text-blue">Hasil Nilai KPI Output</h3>
				<p>Periode : <b><?php echo $periode;?></b></p>
			</div>
		</div>
	</div>
	
	
<table class="table table-stripped table-condensed" style="font-size: 9pt;">
	<thead>
		<tr>
			<th>No.</th>
			<th>Nomor Induk</th>
			<th>Nama Karyawan</th>
			<th>Jabatan</th>
			<th>Lokasi Kerja</th>
			<th>Nilai Target</th>
		</tr>
	</thead>
	<tbody>
		<?php
		$n=1; 
		foreach ($data as $k => $v) {
			echo '<tr>
			<td width="3%">'.$n.'.</td>
			<td>'.$v['nik'].'</td>
			<td>'.$v['nama'].'</td>
			<td>'.$v['jabatan'].'</td>
			<td>'.$v['loker'].'</td>
			<td>'.number_format($v['nilai'],2,',',',').'</td>
			</tr>';
			$n++;
		}
		?>
	</tbody>
</table>
</div>