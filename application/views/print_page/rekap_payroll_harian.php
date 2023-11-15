<div style="padding: 10px;">
<div style="font-weight: bold;text-align: center;">DAFTAR GAJI HARIAN CV JATI KENCANA</div>
<div style="font-weight: bold;text-align: center;">
	BULAN: <?php echo strtoupper($this->formatter->getMonth()[date('m',strtotime($periode['tgl_selesai']))]); ?> <?php echo date('Y',strtotime($periode['tgl_selesai'])); ?>
	<br>
	PERIODE : <?php echo $this->formatter->getDateMonthFormatUser($periode['tgl_mulai']).' - '.$this->formatter->getDateMonthFormatUser($periode['tgl_selesai']); ?>
</div>
<style type="text/css">
	th{
		padding: 5px;
		text-align: center;
     	white-space: pre;
	}
	td{
		padding: 5px;
      	white-space: pre;
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
			font-size:8pt; 
		}
	}
</style>
<br>
	<?php 
		$tb_1 = '';
		$jum_kolom = 1;
		if(!empty($periode['tgl_mulai']) && $periode['tgl_selesai']){
			$tgl_mulai = $periode['tgl_mulai'];
			$tgl_selesai = $periode['tgl_selesai'];
			while ($periode['tgl_mulai'] <= $periode['tgl_selesai'])
			{
				$tb_1 .= '<th class="font">'.$this->formatter->getNameOfDay($periode['tgl_mulai'])."\n".$this->formatter->getDateFormatUser($periode['tgl_mulai']).'</th>';
				$periode['tgl_mulai'] = date('Y-m-d', strtotime($periode['tgl_mulai'] . ' +1 day'));
				$jum_kolom++;
			}
		}
	?>
<table class="border" width="100%" border="3">
	<tr>
		<th class="font">No.</th>
		<th class="font">Nama</th>
		<th class="font">NIK</th>
		<?=$tb_1?>
		<th class="font">Hari Kerja</th>
		<th class="font">Gaji Harian</th>
		<th class="font">BPJS TK - JHT,<br>JKK, JKM</th>
		<th class="font">BPJS KES</th>
		<th class="font">Jaminan<br>Pensiun</th>
		<th class="font">Nominal & Nama <br> Lainnya</th>
		<!-- <th class="font">Ekuivalen</th> -->
		<th class="font">Jam Hari Libur</th>
		<th class="font">Gaji Hari Libur</th>
		<th class="font">Gaji Harian</th>
		<th class="font">Gaji Di terima</th>
	</tr>
	<?php
	$no = 1;
	$total_gaji=0;
	$total_gaji_lembur=0;
	$total_gaji_harian=0;
	$ekuivalen=0;
	$total_jam_lembur=0;
	$total_tk=0;
	$total_kes=0;
	$total_pen=0;
	$total_gapok=0;
	foreach ($emp_gaji as $d) { ?>
	<tr>
		<td class="font"><?php echo $no; ?></td>
		<td class="font"><?php echo $d->nama_karyawan; ?></td>
		<td class="font"><?php echo $d->nik; ?></td>
			<?php
				$rw_1 = '';
				$hadir = 0;
				while ($d->tgl_mulai <= $d->tgl_selesai)
				{
					$d_lembur=$this->model_karyawan->getDataLemburDate($d->id_karyawan, $d->tgl_mulai);
					$d_pre=$this->model_karyawan->checkPresensiEmpDate($d->id_karyawan, $d->tgl_mulai);
					// $presensi = ((!empty($d_pre['jam_mulai']) || !empty($d_pre['jam_selesai'])) && ($d_pre['jam_mulai'] != "00:00:00" || $d_pre['jam_selesai'] != "00:00:00"))?'Hadir':null;
					// if(((!empty($d_pre['jam_mulai']) || !empty($d_pre['jam_selesai'])) && ($d_pre['jam_mulai'] != "00:00:00" || $d_pre['jam_selesai'] != "00:00:00"))){
					if(((!empty($d_pre['jam_mulai']) && !empty($d_pre['jam_selesai'])) && ($d_pre['jam_mulai'] != "00:00:00" && $d_pre['jam_selesai'] != "00:00:00"))){
						$libur =  $this->otherfunctions->checkHariLiburActive($d->tgl_mulai);
						if(isset($libur) || !empty($libur)){
							$presensi_libur = $d->tgl_mulai;
							$countLibur = 1;
							$presensi=null;
						}else{
							$presensi="Hadir";
							$hadir+=1;
						}
					}else{
						$presensi=null;
					}	
					if (count($d_lembur) > 0) {
						$max_lem = count($d_lembur);
						$row_lem = 1;
						$data_lembur='';
						foreach ($d_lembur as $dl) {
							$jam_lembur=$this->formatter->convertJamtoDecimal($dl->jumlah_lembur);
							if($d->tgl_mulai = $dl->tgl_mulai){
								$nominalLembur=$this->payroll->getNominalLemburDateHarian($d->id_karyawan, $d->tgl_mulai, $dl->jenis_lembur, $dl->val_jumlah_lembur, $dl->val_potong_jam);
								$nl=$this->otherfunctions->nonPembulatan($nominalLembur);
								$data_lembur.=$presensi.' | '.$jam_lembur." Jam | ".$this->formatter->getFormatMoneyUser($nl).(($max_lem > ($row_lem))?"\n":'');
							}
							$row_lem++;
						}
						$rw_1.='<td class="font">'.$data_lembur.'</td>';
					}else{
						$rw_1.='<td class="font">'.$presensi.'</td>';
					}			
					$d->tgl_mulai = date('Y-m-d', strtotime($d->tgl_mulai . ' +1 day'));
				}
			?>
		<?=$rw_1;?>
		<td class="font"><?=$hadir?> Hari</td>
		<td class="font"><?php echo $this->formatter->getFormatMoneyUser(($d->gaji_pokok)); ?></td>
		<td class="font"><?php echo $this->formatter->getFormatMoneyUser(($d->jht+$d->jkk+$d->jkm)); ?></td>
		<td class="font"><?php echo $this->formatter->getFormatMoneyUser(($d->jkes)); ?></td>
		<td class="font"><?php echo $this->formatter->getFormatMoneyUser(($d->jpen)); ?></td>
		<td class="font"><?php echo $this->payroll->getDataLainNamaNominalView($d->data_lain, $d->keterangan_lain, $d->nominal_lain)?></td>
		<!-- <td class="font"><?=$d->ekuivalen?></td> -->
		<td class="font"><?=$d->total_jam?></td>
		<td class="font"><?=$this->formatter->getFormatMoneyUser($this->otherfunctions->nonPembulatan($d->gaji_lembur))?></td>
		<td class="font"><?=$this->formatter->getFormatMoneyUser($this->otherfunctions->nonPembulatan($d->presensi*$d->gaji_pokok))?></td>
		<td class="font"><?=$this->formatter->getFormatMoneyUser($this->otherfunctions->nonPembulatan($d->gaji_bersih))?></td>
	</tr>
	<?php
		$no++;
		$total_tk+=($d->jht+$d->jkk+$d->jkm);
		$total_gapok+=($d->gaji_pokok);
		$total_kes+=($d->jkes);
		$total_pen+=($d->jpen);
		$total_gaji+=$d->gaji_bersih;
		$total_gaji_lembur+=$d->gaji_lembur;
		$total_gaji_harian+=($d->presensi*$d->gaji_pokok);
		$ekuivalen+=$d->ekuivalen;
		$total_jam_lembur+=$d->total_jam;
	}
?>
	<tr>
		<td></td>
		<td><b>TOTAL</b></td>
		<?php for ($i=0; $i < $jum_kolom; $i++) { 
			echo '<td></td>';
		}?>
		<td></td>
		<td><b><?=$this->formatter->getFormatMoneyUser($total_gapok)?></b></td>
		<td><b><?=$this->formatter->getFormatMoneyUser($total_tk)?></b></td>
		<td><b><?=$this->formatter->getFormatMoneyUser($total_kes)?></b></td>
		<td><b><?=$this->formatter->getFormatMoneyUser($total_pen)?></b></td>
		<td><b>0</b></td>
		<!-- <td><b><?=$ekuivalen?></b></td> -->
		<td><b><?=$total_jam_lembur?></b></td>
		<td><b><?=$this->formatter->getFormatMoneyUser($total_gaji_lembur)?></b></td>
		<td><b><?=$this->formatter->getFormatMoneyUser($total_gaji_harian)?></b></td>
		<td><b><?=$this->formatter->getFormatMoneyUser($total_gaji)?></b></td>
	</tr>
</table>

<table width="100%" style="margin-top: 20px;">
	<tr>
		<td width="30%"></td>
		<td width="40%"></td>
		<td width="30%" colspan="2" style="text-align: center;">Karangjati, <?php echo date('d').' '.$this->formatter->getMonth()[date('m')].' '.date('Y'); ?></td>
	</tr>
	<?php if($jenis == '1'){ ?>
		<tr width="50%">
			<td style="text-align: center;">Menyetujui</td>
			<td style="text-align: center;padding-right: 10px;">Mengetahui</td>
			<td style="text-align: center;">Dibuat Oleh</td>
		</tr>
		<tr width="50%">
			<td></td>
			<td></td>
			<td colspan="2" style="padding-top: 70px;"></td>
			<!-- <td colspan="2" style="padding-top: 50px;"></td> -->
		</tr>
		<tr width="50%">
			<td style="text-align: center;"><?=$menyetujui?></td>
			<td style="text-align: center;"><?=$mengetahui?></td>
			<td style="text-align: center;"><?=$dibuat?></td>
		</tr>
	<?php }else{ ?>
		<tr width="50%">
			<td></td>
			<td></td>
			<td colspan="2" style="text-align: center;">Menyetujui</td>
			<!-- <td colspan="2" style="text-align: right;padding-right: 10px;"></td> -->
			<!-- <td style="text-align: center;">Dibuat Oleh</td> -->
		</tr>
		<tr width="50%">
			<td></td>
			<td></td>
			<td colspan="2" style="padding-top: 70px;"></td>
			<!-- <td colspan="2" style="padding-top: 50px;"></td> -->
		</tr>
		<tr width="50%">
			<td></td>
			<td></td>
			<td colspan="2" style="text-align: center;"><?=$menyetujui?></td>
			<!-- <td colspan="2" style="text-align: center;"></td> -->
			<!-- <td style="text-align: center;"><?//=$nama_buat?></td> -->
		</tr>
	<?php } ?>
</table>
</div>
