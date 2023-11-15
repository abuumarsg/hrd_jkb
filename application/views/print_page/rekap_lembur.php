<div style="padding: 10px;">
	<div style="font-weight: bold;text-align: center;">DAFTAR PENGGAJIAN LEMBUR CV JATI KENCANA</div>
	<div style="font-weight: bold;text-align: center;">
		Tanggal : <?php echo $this->formatter->getDateMonthFormatUser($periode['tgl_mulai']); ?> - <?php echo $this->formatter->getDateMonthFormatUser($periode['tgl_selesai']); ?>
	</div>
	<div style="font-weight: bold;text-align: center;"><?=$nama_bagian.' - '.$nama_loker;?></div>
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
				margin: 7mm;
			}
			.box-id {
				/* page-break-inside: avoid; */
				/* transform: scale(0.9, 0.9); */
				page-break-after: always;
			}
			.font{
				font-size:8pt; 
			}
			.border{
				border: 1px solid black;
			}
			.vendorListHeading {
        		/* -webkit-print-color-adjust: exact; */
				background-color: #F7F701 !important;
				-webkit-print-color-adjust:exact !important;
				print-color-adjust:exact !important;
			}
			.grayColor {
				background-color: #C1C1C1 !important;
				-webkit-print-color-adjust:exact !important;
				print-color-adjust:exact !important;
			}
		}
		/* table, th, td {
			border: 1px solid black;
		} */
	</style>
	<br>
	<?php 
	// print_r($jenis);
		$nn=1;
		$tb_1 = '';
		$tb_2 = '';
		$jum_row = 0;
		$tgl_mulai = $periode['tgl_mulai'];
		$tgl_selesai = $periode['tgl_selesai'];
		while ($periode['tgl_mulai'] <= $periode['tgl_selesai'])
		{
			if($nn < 16){
				$tb_1 .= '<th class="font">'.$this->formatter->getNameOfDay($periode['tgl_mulai'])."\n".$this->formatter->getDateFormatUser($periode['tgl_mulai']).'</th>';
			}elseif($nn > 15){
				$jum_row += 1;
				$tb_2 .= '<th class="font">'.$this->formatter->getNameOfDay($periode['tgl_mulai'])."\n".$this->formatter->getDateFormatUser($periode['tgl_mulai']).'</th>';
			}
			$periode['tgl_mulai'] = date('Y-m-d', strtotime($periode['tgl_mulai'] . ' +1 day'));
			$nn++;
		}
	?>
	<div class="box-id">
		<table class="border" width="100%" border="3">
			<tr class="grayColor"> 
				<th class="font">No.</th>
				<th class="font">Nama</th>
				<th class="font">NIK</th>
				<?=$tb_1?>
			</tr>
			<?php
			$no = 1;
			if(!empty($emp_gaji)) {
				foreach ($emp_gaji as $e) {
					$emp = $this->model_karyawan->getEmployeeId($e->id_karyawan);
					// if($e->gaji_terima != 0){
					if($e->total_jam != 0){
						$total_jam_lembur = 0;
					?>
					<tr>
						<td class="font"><?php echo $no; ?></td>
						<td class="font"><?php echo $e->nama_karyawan; ?></td>
						<td class="font"><?php echo $emp['nik']; ?></td>
						<?php
							$mm=1;
							$rw_1 = '';
							$start= $tgl_mulai;
							while ($start <= $tgl_selesai)
							{
								if($mm <= 15){
									$d_lembur=$this->model_karyawan->getDataLemburDate($e->id_karyawan, $start);
									if (count($d_lembur) > 0) {
										$lembur_karb='';
										$row_lemb=1;
										foreach ($d_lembur as $dl) {
											$max_lem=count($d_lembur);
											$jam_lembur_real=$this->formatter->convertJamtoDecimal($dl->val_jumlah_lembur);
											$jam_potong_lembur=$this->formatter->convertJamtoDecimal($dl->val_potong_jam);
											$jam_lembur			= ($jam_lembur_real - $jam_potong_lembur);
											$total_jam_lembur	+= $jam_lembur;
											// $jam_lembur=$this->formatter->convertJamtoDecimal($dl->jumlah_lembur);
											$lama_lembur = 14;//$this->formatter->convertDecimaltoJam(14);
											$lama_lembur_real = $this->formatter->convertJamtoDecimal($dl->val_jumlah_lembur);
											if($start == $dl->tgl_mulai && $lama_lembur_real < $lama_lembur){
												$nominalLembur=$this->payroll->getNominalLemburDate($e->id_karyawan, $start, $dl->jenis_lembur, $dl->val_jumlah_lembur, $dl->val_potong_jam);
												$nl=$this->otherfunctions->nonPembulatan($nominalLembur);
												if($jenis == 1){
													$lembur_karb.=$jam_lembur." Jam | ".$this->formatter->getFormatMoneyUserReq($nl).(($max_lem > ($row_lemb))?"\n":'');
												}else{
													$lembur_karb.=$jam_lembur." Jam".(($max_lem > ($row_lemb))?"\n":'');
												}
											}elseif($start == $dl->tgl_mulai && $lama_lembur_real >= $lama_lembur){
												$date_loop=$this->formatter->dateLoopFull($dl->tgl_mulai,$dl->tgl_selesai);
												// if(in_array($start, $date_loop)){
													$nominalLemburx=$this->payroll->getNominalLemburDate($e->id_karyawan, $dl->tgl_mulai, $dl->jenis_lembur, $dl->val_jumlah_lembur, $dl->val_potong_jam);
													$nlx=$this->otherfunctions->nonPembulatan($nominalLemburx);
													if($jenis == 1){
														$lembur_karb.=$jam_lembur." Jam | ".$this->formatter->getFormatMoneyUserReq($nlx)."\n";
														$lembur_karb.='('.$this->formatter->getDateFormatUser($dl->tgl_mulai).' - '.$this->formatter->getDateFormatUser($dl->tgl_selesai).')';
													}else{
														$lembur_karb.=$jam_lembur." Jam"."\n";
														$lembur_karb.='('.$this->formatter->getDateFormatUser($dl->tgl_mulai).' - '.$this->formatter->getDateFormatUser($dl->tgl_selesai).')';
													}
												// }
											}
											$row_lemb++;
										}
										$rw_1.='<td class="font">'.$lembur_karb.'</td>';
									}else{
										$rw_1.='<td class="font"></td>';
									}
								}
								$start = date('Y-m-d', strtotime($start. ' +1 day'));
								$mm++;
							}
						?>
						<?=$rw_1;?>
					</tr>
					<?php
					$no++;
					}
				}
			}
		?>
		</table>
	</div>
	<div class="box-id">
		<table class="border" width="100%" border="3">
			<tr class="grayColor"> 
				<!-- style="background-color: #7C7C71;"> -->
				<th class="font">No.</th>
				<th class="font">Nama</th>
				<th class="font">NIK</th>
				<?=$tb_2?>
				<th class="font">Ekuivalen</th>
				<th class="font">Jam Lembur</th>
				<?php if($jenis == 1){
					echo '<th class="font">Total Gaji</th>';
				}?>				
			</tr>
			<?php
			$nox = 1;
			$grand_total = 0;
			$total_ekuivalen = 0;
			$total_total_jam = 0;
			if(!empty($emp_gaji)) {
				foreach ($emp_gaji as $e) {
					$empx = $this->model_karyawan->getEmployeeId($e->id_karyawan);
					// if($e->gaji_terima != 0){
					if($e->total_jam != 0){
						$total_jam_lemburx = 0;
						$mm=1;
						$rw_2 = '';
						// $jum_row = 0;
						$startx= $tgl_mulai;
						while ($startx <= $tgl_selesai)
						{
							if($mm >= 16){
								// $jum_row += 1;
								$d_lemburx=$this->model_karyawan->getDataLemburDate($e->id_karyawan, $startx);
								if (count($d_lemburx) > 0) {
									$lembur_kar='';
									$row_lemx=1;
									foreach ($d_lemburx as $dlx) {
										$max_lemx=count($d_lemburx);
										$jam_lembur_realx=$this->formatter->convertJamtoDecimal($dlx->val_jumlah_lembur);
										// $jam_lembur_realx=$this->formatter->convertJamtoDecimal($dlx->jumlah_lembur);
										$jam_potong_lemburx=$this->formatter->convertJamtoDecimal($dlx->val_potong_jam);
										$jam_lemburx			= ($jam_lembur_realx - $jam_potong_lemburx);
										$total_jam_lemburx	+= $jam_lemburx;
										// $jam_lemburx=$this->formatter->convertJamtoDecimal($dlx->jumlah_lembur);
										// $lama_lemburx = $this->formatter->convertDecimaltoJam(14);
										// $lama_lembur_realx = $dlx->val_jumlah_lembur;
										$lama_lemburx = 14;//$this->formatter->convertDecimaltoJam(14);
										$lama_lembur_realx = $this->formatter->convertJamtoDecimal($dlx->val_jumlah_lembur);
										// $lama_lembur_realx = $this->formatter->convertDecimaltoJam($dlx->val_jumlah_lembur);
										if($startx == $dlx->tgl_mulai && $lama_lembur_realx < $lama_lemburx){
											$nominalLemburx=$this->payroll->getNominalLemburDate($e->id_karyawan, $dlx->tgl_mulai, $dlx->jenis_lembur, $dlx->val_jumlah_lembur, $dlx->val_potong_jam);
											$nlx=$this->otherfunctions->nonPembulatan($nominalLemburx);
											if($jenis == 1){
												$lembur_kar.=$jam_lemburx." Jam | ".$this->formatter->getFormatMoneyUserReq($nlx).(($max_lemx > ($row_lemx))?"\n":'');
											}else{
												$lembur_kar.=$jam_lemburx." Jam".(($max_lemx > ($row_lemx))?"\n":'');
											}
										}elseif($startx == $dlx->tgl_mulai && $lama_lembur_realx >= $lama_lemburx){
											$date_loop=$this->formatter->dateLoopFull($dlx->tgl_mulai,$dlx->tgl_selesai);
											// if(in_array($startx, $date_loop)){
												$nominalLemburx=$this->payroll->getNominalLemburDate($e->id_karyawan, $dlx->tgl_mulai, $dlx->jenis_lembur, $dlx->val_jumlah_lembur, $dlx->val_potong_jam);
												$nlx=$this->otherfunctions->nonPembulatan($nominalLemburx);
												if($jenis == 1){
													$lembur_kar.=$jam_lemburx." Jam | ".$this->formatter->getFormatMoneyUserReq($nlx)."\n";
													$lembur_kar.='('.$this->formatter->getDateFormatUser($dlx->tgl_mulai).' - '.$this->formatter->getDateFormatUser($dlx->tgl_selesai).')';
												}else{
													$lembur_kar.=$jam_lemburx." Jam"."\n";
													$lembur_kar.='('.$this->formatter->getDateFormatUser($dlx->tgl_mulai).' - '.$this->formatter->getDateFormatUser($dlx->tgl_selesai).')';
												}
											// }
										}
										$row_lemx++;
									}
									$rw_2.='<td class="font">'.$lembur_kar.'</td>';
								}else{
									$rw_2.='<td class="font"></td>';
								}
							}
							$startx = date('Y-m-d', strtotime($startx. ' +1 day'));
							$mm++;
						}
						?>
						<tr>
							<td class="font"><?php echo $nox; ?></td>
							<td class="font"><?php echo $e->nama_karyawan; ?></td>
							<td class="font"><?php echo $empx['nik']; ?></td>
							<?php echo $rw_2;?>
							<td class="font" style="text-align: center;"><?php echo $e->ekuivalen; ?></td>
							<td class="font" style="text-align: center;"><?php echo $e->total_jam; ?></td>
							<?php if($jenis == 1){
								echo '<td class="font">'.$this->formatter->getFormatMoneyUser($e->gaji_terima).'</td>';
							}?>				
							
						</tr>
						<?php
						$total_ekuivalen+=$e->ekuivalen;
						$total_total_jam+=$e->total_jam;
						$grand_total+=$e->gaji_terima;
						$nox++;
					}
				}
			}
			?>
			<?php if($jenis == 1){ ?>
			<tr>
				<td class="font"></td>
				<td class="font" style="font-size:12pt;"><b>TOTAL</b></td>
				<?php //$jum_row = 0;
					for ($i=0; $i <= $jum_row; $i++) { 
						echo '<td class="font"></td>';
					}
				?>
				<td class="font vendorListHeading" style="text-align: center;"><?=$total_ekuivalen?></td>
				<td class="font vendorListHeading" style="text-align: center;"><?=$total_total_jam?></td>
				<!-- <td class="font vendorListHeading" colspan="3" style="font-size:12pt;background-color: #F7F701;"><b><?php // echo $this->formatter->getFormatMoneyUser($grand_total);?></b></td> -->
				<td class="font vendorListHeading" style="font-size:12pt;"><b><?php echo $this->formatter->getFormatMoneyUser($grand_total);?></b></td>
			</tr>
			<?php } ?>
		</table>
		<table width="100%" style="margin-top: 20px;">
			<tr>
				<td colspan="2" style="text-align: right;padding-right: 10px;">Karangjati, <?php echo date('d').' '.$this->formatter->getMonth()[date('m')].' '.date('Y'); ?></td>
			</tr>
			<tr width="50%">
				<td style="text-align: center;">Menyetujui</td>
				<td style="text-align: center;">Dibuat Oleh</td>
			</tr>
			<tr width="50%">
				<td style="padding-top: 50px;"></td>
				<td style="padding-top: 50px;"></td>
			</tr>
			<tr width="50%">
				<td style="text-align: center;"><?=$menyetujui?></td>
				<td style="text-align: center;"><?=$nama_buat?></td>
			</tr>
		</table>
	</div>
</div>