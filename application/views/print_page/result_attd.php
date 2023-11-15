<div class="wrapper">
	<div class="row">
		
		<div class="col-md-12 text-center">
			<div class="col-md-3">
				<img src="<?php echo base_url('asset/img/logo.png');?>" width="200px">
			</div>
			<div class="col-md-9">
				<h3 class="text-blue">Hasil Nilai Sikap (360°)</h3>
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
			<th>Nilai Sikap</th>
			<th>Nilai Kalibrasi</th>
		</tr>
	</thead>
	<tbody>
		<?php
		$n=1; 
		foreach ($data['kar'] as $k) {
			$na[$k]=$data['nats'][$k]+$data['nbwh'][$k]+$data['nrkn'][$k];
			$selisih[$k]=$kalibrasi[$k]-$na[$k];
			if ($kalibrasi[$k] != 0) {
				if (number_format($selisih[$k],2,',',',') > 0) {
					$partial[$k]=' <small class="text-success"><b>(+'.number_format($selisih[$k],2,',',',').')</b></small>';
				}elseif(number_format($selisih[$k],2,',',',') < 0){
					$partial[$k]=' <small class="text-danger"><b>('.number_format($selisih[$k],2,',',',').')</b></small>';
				}else{
					$partial[$k]='';
				}
			}else{
				$partial[$k]='';
			}
			echo '<tr>
			<td width="3%">'.$n.'.</td>
			<td>'.implode('', $data['nik'][$k]).'</td>
			<td>'.$data['nm'][$k].'</td>
			<td>'.$data['jbt'][$k].'</td>
			<td>'.$data['lok'][$k].'</td>
			<td>'.number_format($na[$k],2,',',',').$partial[$k].'</td>
			<td>';
			if ($kalibrasi[$k] == 0) {
				$kalibrasi[$k] = NULL;
				echo number_format($na[$k],2,',',',');

			}else{
				echo number_format($kalibrasi[$k],2,',',',');
			}
			echo '</td>
			</tr>';
			$n++;
		}
		?>
	</tbody>
</table>
</div>