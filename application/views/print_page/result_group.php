<div class="wrapper">
	<div class="row">
		
		<div class="col-md-12 text-center">
			<div class="col-md-3">
				<img src="<?php echo base_url('asset/img/logo.png');?>" width="200px">
			</div>
			<div class="col-md-9">
				<h3 class="text-blue">Hasil Nilai Akhir</h3>
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
			<th>Bagian</th>
			<th>Lokasi Kerja</th>
			<th>Nilai Target</th>
			<th>Nilai Sikap</th>
			<th>Nilai OS</th>
			<th>Nilai Total</th>
			<th>Huruf</th>
		</tr>
	</thead>
	<tbody>
		<?php
		$n=1; 
		foreach ($data as $k => $v) {
			if ($loker == "ALL" && $bagi == $v['kode_bagian']) {
				echo '<tr>
				<td width="3%">'.$n.'.</td>
				<td>'.$v['nik'].'</td>
				<td>'.$v['nama'].'</td>
				<td>'.$v['jabatan'].'</td>
				<td>'.$v['bagian'].'</td>
				<td>'.$v['loker'].'</td>
				<td>';
				if (isset($v['nilai_target'])) {
					$nat[$k]=$v['nilai_target']*($bobot[$k]['bobot_out']/100);
					echo number_format($nat[$k],2,',',',');

				}else{
					echo number_format(0,2,',',',');
					$nat[$k]=0;
				}
				echo '</td>
				<td>';
				if (isset($v['nilai_sikap'])) {
					if ($v['nilai_kalibrasi'] != 0) {
						$n_sikap=$v['nilai_kalibrasi'];
					}else{
						$n_sikap=$v['nilai_sikap'];
					}
					$nas[$k]=$n_sikap*($bobot[$k]['bobot_skp']/100);
					echo number_format($nas[$k],2,',',',');
				}else{
					echo number_format(0,2,',',',');
					$nas[$k]=0;

				}
				echo '</td>
				<td>';
				if (isset($v['nilai_corp'])) {
					$nac[$k]=$v['nilai_corp']*($bobot[$k]['bobot_tc']/100);
					echo number_format($nac[$k],2,',',',');

				}else{
					echo number_format(0,2,',',',');
					$nac[$k]=0;
				}
				$nil[$k]=$nat[$k]+$nas[$k]+$nac[$k];
				$nilx[$k]=number_format(($nat[$k]+$nas[$k]+$nac[$k]),2,',',',');
				echo '</td>
				<td>'.$nilx[$k].'</td>';
				$con=$this->db->get_where('master_konversi_nilai',array('status'=>'aktif'))->result();
				foreach ($con as $c) {
					if (number_format(($nat[$k]+$nas[$k]+$nac[$k]),2) >= $c->awal && number_format(($nat[$k]+$nas[$k]+$nac[$k]),2) <= $c->akhir) {
						echo '<td style="background-color:'.$c->warna.';color:#fff;" class="text-center">'.$c->huruf.'</td>';
					}
				}
				echo '
				</tr>';
				$n++;
			}elseif ($bagi == "ALL" && $loker == $v['kode_loker']) {
				echo '<tr>
				<td width="3%">'.$n.'.</td>
				<td>'.$v['nik'].'</td>
				<td>'.$v['nama'].'</td>
				<td>'.$v['jabatan'].'</td>
				<td>'.$v['bagian'].'</td>
				<td>'.$v['loker'].'</td>
				<td>';
				if (isset($v['nilai_target'])) {
					$nat[$k]=$v['nilai_target']*($bobot[$k]['bobot_out']/100);
					echo number_format($nat[$k],2,',',',');

				}else{
					echo number_format(0,2,',',',');
					$nat[$k]=0;
				}
				echo '</td>
				<td>';
				if (isset($v['nilai_sikap'])) {
					if ($v['nilai_kalibrasi'] != 0) {
						$n_sikap=$v['nilai_kalibrasi'];
					}else{
						$n_sikap=$v['nilai_sikap'];
					}
					$nas[$k]=$n_sikap*($bobot[$k]['bobot_skp']/100);
					echo number_format($nas[$k],2,',',',');
				}else{
					echo number_format(0,2,',',',');
					$nas[$k]=0;

				}
				echo '</td>
				<td>';
				if (isset($v['nilai_corp'])) {
					$nac[$k]=$v['nilai_corp']*($bobot[$k]['bobot_tc']/100);
					echo number_format($nac[$k],2,',',',');

				}else{
					echo number_format(0,2,',',',');
					$nac[$k]=0;
				}
				$nil[$k]=$nat[$k]+$nas[$k]+$nac[$k];
				$nilx[$k]=number_format(($nat[$k]+$nas[$k]+$nac[$k]),2,',',',');
				echo '</td>
				<td>'.$nilx[$k].'</td>';
				$con=$this->db->get_where('master_konversi_nilai',array('status'=>'aktif'))->result();
				foreach ($con as $c) {
					if (number_format(($nat[$k]+$nas[$k]+$nac[$k]),2) >= $c->awal && number_format(($nat[$k]+$nas[$k]+$nac[$k]),2) <= $c->akhir) {
						echo '<td style="background-color:'.$c->warna.';color:#fff;" class="text-center">'.$c->huruf.'</td>';
					}
				}
				echo '
				</tr>';
				$n++;
			}elseif ($bagi == "ALL" && $loker == 'ALL') {
				echo '<tr>
				<td width="3%">'.$n.'.</td>
				<td>'.$v['nik'].'</td>
				<td>'.$v['nama'].'</td>
				<td>'.$v['jabatan'].'</td>
				<td>'.$v['bagian'].'</td>
				<td>'.$v['loker'].'</td>
				<td>';
				if (isset($v['nilai_target'])) {
					$nat[$k]=$v['nilai_target']*($bobot[$k]['bobot_out']/100);
					echo number_format($nat[$k],2,',',',');

				}else{
					echo number_format(0,2,',',',');
					$nat[$k]=0;
				}
				echo '</td>
				<td>';
				if (isset($v['nilai_sikap'])) {
					if ($v['nilai_kalibrasi'] != 0) {
						$n_sikap=$v['nilai_kalibrasi'];
					}else{
						$n_sikap=$v['nilai_sikap'];
					}
					$nas[$k]=$n_sikap*($bobot[$k]['bobot_skp']/100);
					echo number_format($nas[$k],2,',',',');
				}else{
					echo number_format(0,2,',',',');
					$nas[$k]=0;

				}
				echo '</td>
				<td>';
				if (isset($v['nilai_corp'])) {
					$nac[$k]=$v['nilai_corp']*($bobot[$k]['bobot_tc']/100);
					echo number_format($nac[$k],2,',',',');

				}else{
					echo number_format(0,2,',',',');
					$nac[$k]=0;
				}
				$nil[$k]=$nat[$k]+$nas[$k]+$nac[$k];
				$nilx[$k]=number_format(($nat[$k]+$nas[$k]+$nac[$k]),2,',',',');
				echo '</td>
				<td>'.$nilx[$k].'</td>';
				$con=$this->db->get_where('master_konversi_nilai',array('status'=>'aktif'))->result();
				foreach ($con as $c) {
					if (number_format(($nat[$k]+$nas[$k]+$nac[$k]),2) >= $c->awal && number_format(($nat[$k]+$nas[$k]+$nac[$k]),2) <= $c->akhir) {
						echo '<td style="background-color:'.$c->warna.';color:#fff;" class="text-center">'.$c->huruf.'</td>';
					}
				}
				echo '
				</tr>';
				$n++;
			}elseif ($bagi == $v['kode_bagian'] && $loker == $v['kode_loker']) {
				echo '<tr>
				<td width="3%">'.$n.'.</td>
				<td>'.$v['nik'].'</td>
				<td>'.$v['nama'].'</td>
				<td>'.$v['jabatan'].'</td>
				<td>'.$v['bagian'].'</td>
				<td>'.$v['loker'].'</td>
				<td>';
				if (isset($v['nilai_target'])) {
					$nat[$k]=$v['nilai_target']*($bobot[$k]['bobot_out']/100);
					echo number_format($nat[$k],2,',',',');

				}else{
					echo number_format(0,2,',',',');
					$nat[$k]=0;
				}
				echo '</td>
				<td>';
				if (isset($v['nilai_sikap'])) {
					if ($v['nilai_kalibrasi'] != 0) {
						$n_sikap=$v['nilai_kalibrasi'];
					}else{
						$n_sikap=$v['nilai_sikap'];
					}
					$nas[$k]=$n_sikap*($bobot[$k]['bobot_skp']/100);
					echo number_format($nas[$k],2,',',',');
				}else{
					echo number_format(0,2,',',',');
					$nas[$k]=0;

				}
				echo '</td>
				<td>';
				if (isset($v['nilai_corp'])) {
					$nac[$k]=$v['nilai_corp']*($bobot[$k]['bobot_tc']/100);
					echo number_format($nac[$k],2,',',',');

				}else{
					echo number_format(0,2,',',',');
					$nac[$k]=0;
				}
				$nil[$k]=$nat[$k]+$nas[$k]+$nac[$k];
				$nilx[$k]=number_format(($nat[$k]+$nas[$k]+$nac[$k]),2,',',',');
				echo '</td>
				<td>'.$nilx[$k].'</td>';
				$con=$this->db->get_where('master_konversi_nilai',array('status'=>'aktif'))->result();
				foreach ($con as $c) {
					if (number_format(($nat[$k]+$nas[$k]+$nac[$k]),2) >= $c->awal && number_format(($nat[$k]+$nas[$k]+$nac[$k]),2) <= $c->akhir) {
						echo '<td style="background-color:'.$c->warna.';color:#fff;" class="text-center">'.$c->huruf.'</td>';
					}
				}
				echo '
				</tr>';
				$n++;
			}
		}
		$mm=array();
		if (isset($nat)) {
			$minnat=number_format(min($nat),2,',',',');
			$maxnat=number_format(max($nat),2,',',',');
			$avgnat=number_format((array_sum($nat)/count($nat)),2,',',',');
			array_push($mm, 1);
		}else{
			$minnat=number_format(0,2,',',',');
			$maxnat=number_format(0,2,',',',');
			$avgnat=number_format(0,2,',',',');
		}
		if (isset($nas)) {
			$minnas=number_format(min($nas),2,',',',');
			$maxnas=number_format(max($nas),2,',',',');
			$avgnas=number_format((array_sum($nas)/count($nas)),2,',',',');
			array_push($mm, 1);
		}else{
			$minnas=number_format(0,2,',',',');
			$maxnas=number_format(0,2,',',',');
			$avgnas=number_format(0,2,',',',');
		}
		if (isset($nac)) {
			$minnac=number_format(min($nac),2,',',',');
			$maxnac=number_format(max($nac),2,',',',');
			$avgnac=number_format((array_sum($nac)/count($nac)),2,',',',');
			array_push($mm, 1);
		}else{
			$minnac=number_format(0,2,',',',');
			$maxnac=number_format(0,2,',',',');
			$avgnac=number_format(0,2,',',',');
		}
		if (isset($nil)) {
			$minnil=number_format(min($nil),2,',',',');
			$maxnil=number_format(max($nil),2,',',',');
			$avgnil=number_format((array_sum($nil)/count($nil)),2,',',',');
			array_push($mm, 1);
		}else{
			$minnil=number_format(0,2,',',',');
			$maxnil=number_format(0,2,',',',');
			$avgnil=number_format(0,2,',',',');
		}
		if (count($mm) > 0) {
			echo '<tr style="font-size:12pt;">
			<td colspan="6" class="text-center"><b>Nilai Terendah</b></td>
			<td>'.$minnat.'</td>
			<td>'.$minnas.'</td>
			<td>'.$minnac.'</td>
			<td>'.$minnil.'</td>
			<td></td>
			<tr>
			<tr style="font-size:12pt;">
			<td colspan="6" class="text-center"><b>Nilai Tertinggi</b></td>
			<td>'.$maxnat.'</td>
			<td>'.$maxnas.'</td>
			<td>'.$maxnac.'</td>
			<td>'.$maxnil.'</td>
			<td></td>
			<tr>
			<tr style="font-size:12pt;">
			<td colspan="6" class="text-center"><b>Nilai Rata - Rata</b></td>
			<td>'.$avgnat.'</td>
			<td>'.$avgnas.'</td>
			<td>'.$avgnac.'</td>
			<td>'.$avgnil.'</td>
			<td></td>
			<tr>';
		}
		?>
	</tbody>
</table>
</div>