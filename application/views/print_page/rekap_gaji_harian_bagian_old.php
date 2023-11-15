<div style="padding: 10px;">
	<div style="font-weight: bold;text-align: center;">DAFTAR GAJI HARIAN CV JATI KENCANA</div>
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
	echo '<pre>';
	print_r($emp_gaji);
	?>
	<div class="box-id">
		<table class="table-bordered table-responsive" width="100%" border="1">
			<tr style="background-color: #F9F9F9;">
				<th class="font">No.</th>
				<th class="font">Bagian</th>
				<th class="font">Lokasi</th>
				<th class="font">TOTAL</th>
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
				<?php $total+=$d->jumlah; ?>
			</tr>
			<?php
			$no++;
			}
			?>
			<tr>
				<td class="font"></td>
				<td class="font-up"><b>TOTAL</b></td>
				<td class="font"></td>
				<td class="font-up"><b><?=$this->formatter->getFormatMoneyUser($this->otherfunctions->pembulatanDepanKoma($total))?></b></td>
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