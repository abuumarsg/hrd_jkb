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
		<th class="font">Bagian</th>
		<th class="font">Lokasi</th>
		<th class="font">Gaji Harian</th>
		<th class="font">Gaji Hari Libur</th>
		<th class="font">BPJS TK - JHT,<br>JKK, JKM</th>
		<th class="font">BPJS KES</th>
		<th class="font">Penambah Lain</th>
		<th class="font">Pengurang Lain</th>
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
	$total_gapok=0;
	$total_tambah=0;
	$total_kurang=0;
	foreach ($emp_gaji as $d) {
		// echo '<pre>';
		// print_r($d->nominal_lain);
		$other = $this->payroll->getDataLainRekapitulasi($d->data_lain, $d->keterangan_lain, $d->nominal_lain);
		$total_tk+=($d->jht+$d->jkk+$d->jkm);
		$total_gapok+=($d->gaji_pokok);
		$total_kes+=($d->jkes);
		$total_gaji+=$d->gaji_bersih;
		$total_gaji_lembur+=$d->gaji_lembur;
		$total_gaji_harian+=($d->presensi*$d->gaji_pokok);
		$ekuivalen+=$d->ekuivalen;
		$total_jam_lembur+=$d->total_jam;
		$total_tambah+=$other['penambah'];
		$total_kurang+=$other['pengurang'];
	}
?>
	<tr>
		<td class="font">1.</td>
		<td class="font"><?=$nama_bagian?></td>
		<td class="font"><?=$nama_loker?></td>
		<td class="font"><?=$this->formatter->getFormatMoneyUser($total_gaji_harian)?></td>
		<td class="font"><?=$this->formatter->getFormatMoneyUser($total_gaji_lembur)?></td>
		<td class="font"><?=$this->formatter->getFormatMoneyUser($total_tk)?></td>
		<td class="font"><?=$this->formatter->getFormatMoneyUser($total_kes)?></td>
		<td class="font"><?=$this->formatter->getFormatMoneyUser($total_tambah)?></td>
		<td class="font"><?=$this->formatter->getFormatMoneyUser($total_kurang)?></td>
		<td class="font"><?=$this->formatter->getFormatMoneyUser($total_gaji)?></td>
	</tr>
	<tr>
		<td class="font">`</td>
		<td class="font"></td>
		<td class="font"></td>
		<td class="font"></td>
		<td class="font"></td>
		<td class="font"></td>
		<td class="font"></td>
		<td class="font"></td>
		<td class="font"></td>
		<td class="font"></td>
	</tr>
	<tr>
		<td class="font"></td>
		<td class="font"><b>TOTAL</b></td>
		<td class="font"></td>
		<td class="font"><b><?=$this->formatter->getFormatMoneyUser($total_gaji_harian)?></b></td>
		<td class="font"><b><?=$this->formatter->getFormatMoneyUser($total_gaji_lembur)?></b></td>
		<td class="font"><b><?=$this->formatter->getFormatMoneyUser($total_tk)?></b></td>
		<td class="font"><b><?=$this->formatter->getFormatMoneyUser($total_kes)?></b></td>
		<td class="font"><b><?=$this->formatter->getFormatMoneyUser($total_tambah)?></b></td>
		<td class="font"><b><?=$this->formatter->getFormatMoneyUser($total_kurang)?></b></td>
		<td class="font"><b><?=$this->formatter->getFormatMoneyUser($total_gaji)?></b></td>
	</tr>
</table>
<table width="100%" style="margin-top: 20px;">
	<tr>
		<td width="30%"></td>
		<td width="40%"></td>
		<td width="30%" colspan="2" style="text-align: center;">Karangjati, <?php echo date('d').' '.$this->formatter->getMonth()[date('m')].' '.date('Y'); ?></td>
	</tr>
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
</table>
</div>