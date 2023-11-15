<div style="padding: 10px;">
	<div style="font-weight: bold;text-align: center;">DAFTAR GAJI LEMBUR CV JATI KENCANA</div>
	<div style="font-weight: bold;text-align: center;">
		Tanggal : <?php echo $this->formatter->getDateMonthFormatUser($periode['tgl_mulai']); ?> - <?php echo $this->formatter->getDateMonthFormatUser($periode['tgl_selesai']); ?>
	</div>
	<div style="font-weight: bold;text-align: center;"><?=$nama_loker;?></div>
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
		@media print,screen {
			@page {
				size: Legal landscape;
				margin: 10mm;
			}
			.box-id {
				/* page-break-inside: avoid; */
				/* transform: scale(0.9, 0.9); */
				page-break-after: always;
			}
			.font{
				font-size:6pt; 
			}
			.font{
				font-size:8pt; 
			}
		}
	</style>
	<br>
	<?php 
		$header = '';
		$tgl_mulai = $periode['tgl_mulai'];
		$tgl_selesai = $periode['tgl_selesai'];
		$prd_mulai = $periode['tgl_mulai'];
		$prd_selesai = $periode['tgl_selesai'];
		$bulanM=$this->otherfunctions->getDataExplode($prd_mulai,'-','end');
		$bulanDepan=$this->otherfunctions->getDataExplode($prd_selesai,'-','end');
		// print_r($bulanM);
		// echo '<br>';
		// print_r($bulanDepan);
		// while ($prd_mulai <= $prd_selesai)
		// {
		// 	$bulanM=$this->otherfunctions->getDataExplode($prd_mulai,'-','end');
		// 	$bulanM=($bulanM < 10)?str_replace('0','',$bulanM):$bulanM;
		// 	$header .= '<th class="font">Jumlah'."\n".$this->formatter->getNameOfMonth($bulanM).'</th>';
		// 	$prd_mulai = date('Y-m-d', strtotime($prd_mulai . ' +1 month'));
		// }
	?>
	<div class="box-id">
		<table class="table-bordered table-responsive" width="100%" border="1">
			<tr style="background-color: #F9F9F9;">
				<th class="font">No.</th>
				<th class="font">Bagian</th>
				<th class="font">Lokasi</th>
				<th class="font">TOTAL</th>
				<?='<th class="font">Jumlah'."\n".$this->formatter->getNameOfMonth($bulanM).'</th>'?>
				<?='<th class="font">Jumlah'."\n".$this->formatter->getNameOfMonth($bulanDepan).'</th>'?>
			</tr>
			<?php
			$no = 1;
			$total = 0;
			$tPer=[];
			foreach ($emp_gaji as $d) {
				$emp = $this->model_karyawan->getEmployeeId($d->id_karyawan);
			?>
			<tr>
				<td class="font"><?php echo $no; ?></td>
				<td class="font"><?php echo $this->model_master->getBagianKode($d->kode_bagian)['nama']; ?></td>
				<td class="font"><?php echo $this->model_master->getLokerKodeArray($d->kode_loker)['nama']; ?></td>
				<td class="font"><?php echo $this->formatter->getFormatMoneyUser($d->jumlah); ?></td>
				<td class="font"></td>
				<td class="font"></td>
				<?php
					// $tgl_mulai=$d->tgl_mulai;
					// $tgl_selesai=$d->tgl_selesai;
					// while ($d->tgl_mulai <= $d->tgl_selesai)
					// {
					// 	$bulanM=$this->otherfunctions->getDataExplode($d->tgl_mulai,'-','end');
					// 	$d_lembur=$this->model_karyawan->getDataLemburBagianDate($d->kode_bagian, $bulanM);
					// 	if(!empty($d->kode_bagian)){
					// 		if (count($d_lembur) > 0) {
					// 			$totalPer=0;
					// 			foreach ($d_lembur as $dl) {
					// 				$bulanML=$this->otherfunctions->getDataExplode($dl->tgl_mulai,'-','end');
					// 				if($bulanM == $bulanML && $d->kode_bagian == $dl->kode_bagian){
					// 					$nominalLembur=$this->payroll->getNominalLemburDate($dl->id_karyawan, $dl->tgl_mulai, $dl->jenis_lembur, $dl->val_jumlah_lembur, $dl->val_potong_jam);
					// 					$totalPer+=$nominalLembur;
					// 				}
					// 			}
					// 			$nl=$this->otherfunctions->nonPembulatan($totalPer);
					// 			echo '<td class="font">'.$this->formatter->getFormatMoneyUser($nl).'</td>';
					// 			$tPer[]=$totalPer;
					// 		}else{
					// 			echo '<td class="font"></td>';
					// 		}
					// 	}
					// 	$d->tgl_mulai = date('Y-m-d', strtotime($d->tgl_mulai . ' +1 month'));
					// }
					// $total+=$d->jumlah;
				?>
			</tr>
			<?php
			$no++;
			}
			?>
			<tr>
				<td class="font"></td>
				<td class="font-up"><b>TOTAL</b></td>
				<td class="font"></td>
				<td class="font-up"><b><?=$this->formatter->getFormatMoneyUser($this->otherfunctions->nonPembulatan($total))?></b></td>
				<?php 
					// $grand_tot = 0;
					// foreach ($tPer as $key => $nil) {
					// 	$grand_tot += $nil;
					// 	echo '<td class="font-up"><b>'.$this->formatter->getFormatMoneyUser($this->otherfunctions->nonPembulatan($nil)).'</b></td>';
					// }
				?>
				<?php
					$start=$periode['tgl_mulai'];
					$end=$periode['tgl_selesai'];
					$grand_total = [];
					while ($start <= $end)
					{
						// $bulanM=$this->otherfunctions->getDataExplode($start,'-','end');
						// $d_lembur=$this->model_karyawan->getDataLemburBagianDate($d->kode_bagian, $bulanM);
						// if(!empty($d->kode_bagian)){
						// 	if (count($d_lembur) > 0) {
						// 		$totalPer=0;
						// 		foreach ($d_lembur as $dl) {
						// 			$bulanML=$this->otherfunctions->getDataExplode($dl->tgl_mulai,'-','end');
						// 			if($bulanM == $bulanML && $d->kode_bagian == $dl->kode_bagian){
						// 				$nominalLembur=$this->payroll->getNominalLemburDate($dl->id_karyawan, $dl->tgl_mulai, $dl->jenis_lembur, $dl->val_jumlah_lembur, $dl->val_potong_jam);
						// 				$totalPer=$totalPer+$nominalLembur;
						// 			}
						// 		}
						// 		$grand_total[] = $totalPer;
						// 	}
						// }
						// foreach ($tPer as $key => $nil) {
						// 	echo '<td class="font-up"><b>'.$this->formatter->getFormatMoneyUser($this->otherfunctions->nonPembulatan(array_sum($nil))).'</b></td>';
						// }
						echo '<td class="font-up"><b>'.$this->formatter->getFormatMoneyUser($this->otherfunctions->nonPembulatan(array_sum($tPer))).'</b></td>';
						$start = date('Y-m-d', strtotime($start . ' +1 month'));
					}
					// print_r($tPer);
				?>
			</tr>
		</table>
		<table width="100%" style="margin-top: 20px;">
			<tr>
				<td colspan="3" style="text-align: right;padding-right: 10px;">Karangjati, <?php echo date('d').' '.$this->formatter->getMonth()[date('m')].' '.date('Y'); ?></td>
			</tr>
			<tr width="50%">
				<td style="text-align: center;">Menyetujui</td>
				<td style="text-align: center;">Mengetahui</td>
				<td style="text-align: center;">Dibuat Oleh</td>
			</tr>
			<tr width="50%">
				<td style="padding-top: 50px;"></td>
				<td style="padding-top: 50px;"></td>
				<td style="padding-top: 50px;"></td>
			</tr>
			<tr width="50%">
				<td style="text-align: center;"><?=$menyetujui?></td>
				<td style="text-align: center;"><?=$mengetahui?></td>
				<td style="text-align: center;"><?=$nama_buat?></td>
			</tr>
		</table>
	</div>
</div>