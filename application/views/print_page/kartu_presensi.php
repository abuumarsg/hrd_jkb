<!-- <div style="padding: 10px;"> -->
	<style type="text/css">
		th{
			padding: 2px;
			text-align: center;
			white-space: pre;
			/* font-size:6pt;  */
		}
		td{
			padding: 2px;
			white-space: pre; 
			/* font-size:6pt;  */
			height: 15px;          
		}
		@page {
			size: Legal;
			margin: 10mm;
		}
		@media print,screen {
			.box-id {
				/* page-break-inside: avoid; */
				/* transform: scale(0.9, 0.9); */
				page-break-after: always;
			}
			.font{
				font-size:8pt;
			}
			.bg-red{
				background-color:red;
			}
			.page {
				/* width: 210mm;
				min-height: 330mm; */
				/* min-height: 297mm; */
				/* padding: 20mm; */
				/* margin: 10mm auto; */
				/* border: 1px #D3D3D3 solid; */
				/* border-radius: 5px; */
				/* background: white; */
				/* box-shadow: 0 0 5px rgba(0, 0, 0, 0.1); */
			}
		}
	</style>
    <div class="page">
		<?php
		$no = 1;
		foreach ($karyawan as $e =>$id) {
			$emp = $this->model_karyawan->getEmployeeId($id);
		?>
		<div style="font-weight: bold;text-align: center;">KARTU MONITOR ABSENSI KARYAWAN</div>
		<div style="font-weight: bold;text-align: center;">
			Nama : <?php echo $emp['nama']; ?>, Bagian : <?php echo $emp['nama_bagian']; ?>
		</div>
		<br>
		<div class="box-id">
			<table class="table-bordered table-responsive" width="100%" border="1">
				<tr style="background-color: #F9F9F9;">
					<th class="font" rowspan="2">BULAN</th>
					<th class="center font" colspan="31">TANGGAL, TAHUN : <?=$tahun?></th>
					<th class="center font" colspan="6">JUMLAH</th>
				</tr>
				<tr style="background-color: #F9F9F9;">
					<?php for($i=1;$i<32;$i++){
						echo '<th class="font">'.$i.'</th>';
					} ?>
					<th class="font">A</th>
					<th class="font">C</th>
					<th class="font">SD</th>
					<th class="font">I</th>
					<th class="font">IMP</th>
					<th class="font">CL</th>
				</tr>
				<?php
				$dataBulan = $this->formatter->getMonth();
				foreach ($dataBulan as $bul =>$vbul) {
					$bulan = $this->formatter->getNameOfMonth($bul);
				?>
					<tr>
						<td class="font"><?php echo $bulan; ?></td>
							<?php
							$liburx = 0;
							$alpha = 0;
							$cuti = 0;
							$sd = 0;
							$izn = 0;
							$imp = 0;
							$il = 0;
							for($i=1;$i<32;$i++){
								$hari = ($i < 10)?'0'.$i:$i;
								$tanggal=$tahun.'-'.$bul.'-'.$hari;
								$presensi = $this->model_karyawan->getListPresensiId(null, ['pre.id_karyawan'=>$id,'pre.tanggal'=>$tanggal],null,'row');
								$libur = $this->otherfunctions->checkHariLiburActive($tanggal);
								if(empty($presensi)){
									$cekTanggal = $this->formatter->cekValueTanggal($bul,$tahun);
									if($cekTanggal < $i){
										echo '<th class="font"></th>';
									}else{
										if(isset($libur)){
											echo '<th class="font">L</th>';
											$liburx += 1;
										}else{
											echo '<th class="font">A</th>';
											$alpha += 1;
										}
									}
								}else{
									if($presensi['kode_ijin'] != null){
										$dIzin = $this->model_karyawan->getIzinCuti(null,['kode_izin_cuti'=>$presensi['kode_ijin']],'row');
										$izin = $this->model_master->getMasterIzin(null,['a.kode_master_izin'=>$dIzin['jenis']],'row');
										if($izin['jenis']=='C'){
											echo '<td class="font">C</td>';
											$cuti += 1;
										}else{
											if($izin['kode_master_izin']=='ISKD'){
												echo '<td class="font">SD</td>';
												$sd += 1;
											}elseif($izin['kode_master_izin']=='IZIN'){
												echo '<td class="font">I</td>';
												$izn += 1;
											}elseif($izin['kode_master_izin']=='IMP'){
												echo '<td class="font">IMP</td>';
												$imp += 1;
											}else{
												echo '<td class="font">CL</td>';
												$il += 1;
											}
										}
									}else{
										echo '<td class="font"></td>';
									}
								}
							} ?>
						<td class="font"><?php echo $alpha; ?></td>
						<td class="font"><?php echo $cuti; ?></td>
						<td class="font"><?php echo $sd; ?></td>
						<td class="font"><?php echo $izn; ?></td>
						<td class="font"><?php echo $imp; ?></td>
						<td class="font"><?php echo $il; ?></td>
					</tr>
				<?php } ?>
			</table><br>
			<h4><u>KETERANGAN 	: </u></h4>
			<table width="100%">
				<tr>
					<td class="font">L</td>
					<td class="font">: Minggu / Libur</td>
				</tr>
				<tr>
					<td class="font">A</td>
					<td class="font">: Alpha / Mangkir</td>
				</tr>
				<tr>
					<td class="font">C</td>
					<td class="font">: Cuti</td>
				</tr>
				<tr>
					<td class="font">SD</td>
					<td class="font">: Surat Keterangan Dokter</td>
				</tr>
				<tr>
					<td class="font">I</td>
					<td class="font">: Izin</td>
				</tr>
				<tr>
					<td class="font">IMP</td>
					<td class="font">: Izin Meninggalkan Pekerjaan</td>
				</tr>
				<tr>
					<td class="font">CL</td>
					<td class="font">: Cuti Lainnya</td>
				</tr>
			</table>
		</div>
	<?php } ?>
	</div>
<!-- </div> -->