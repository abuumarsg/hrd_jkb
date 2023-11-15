<div style="padding: 10px;">
	<div style="font-weight: bold;text-align: center;">DAFTAR REKAPITULASI GAJI BULANAN CV JATI KENCANA</div>
	<div style="font-weight: bold;text-align: center;">
		Tanggal : <?php echo $this->formatter->getDateMonthFormatUser($periode['tgl_mulai']); ?> - <?php echo $this->formatter->getDateMonthFormatUser($periode['tgl_selesai']); ?>
	</div>
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
				font-size:8pt; 
			}
			.font9{
				font-size:9pt; 
			}
		}
	</style>
	<br>
	<div class="box-id">
		<table class="border" width="100%" border="1">
			<tr style="background-color: #F9F9F9;">
				<th class="font" rowspan="2">No.</th>
				<th class="font" rowspan="2">Bagian</th>
				<th class="font" rowspan="2">Lokasi</th>
				<th class="font" rowspan="2">Gaji Pokok</th>
				<th class="font" rowspan="2">Tunjangan<br> Tetap</th>
				<th class="font" rowspan="2">Tunjangan<br> Tidak Tetap</th>
				<th class="font" rowspan="2">Ritasi</th>
				<th class="font" rowspan="2">Uang Makan</th>
				<th class="font" rowspan="2">Potongan<br> Tidak Masuk</th>
				<th class="font" rowspan="2">BPJS TK</th>
				<th class="font" rowspan="2">Jaminan<br> Pensiun</th>
				<th class="font" rowspan="2">BPJS KES</th>
				<th class="font" rowspan="2">Angsuran</th>
				<th class="font" colspan="2">Lainnya</th>
				<th class="font" rowspan="2">Penerimaan</th>
			</tr>
			<tr style="background-color: #F9F9F9;">
				<th class="font">Penambah</th>
				<th class="font">Pengurang</th>
			</tr>
			<?php
			$no = 1;
			$total_gaji_pokok=0;
			$total_tunjangan_tetap=0;
			$total_tunjangan_non=0;
			$total_ritasi=0;
			$total_uang_makan=0;
			$total_pot_tidak_masuk=0;
			$total_bpjs_jht=0;
			$total_bpjs_pen=0;
			$total_bpjs_kes=0;
			$total_angsuran=0;
			$total_gaji_pokok=0;
			$total_penerimaan=0;
			$total_penambah=0;
			$total_pengurang=0;
			if ($emp_gaji){
				foreach ($emp_gaji as $d) {
					$data_gaji_all = $this->model_payroll->getRekapitulasiDataPayrollBulananAll(['a.kode_periode'=>$d->kode_periode,'a.kode_bagian'=>$d->kode_bagian]);
					$penambah = 0;
					$pengurang = 0;
					foreach ($data_gaji_all as $dd) {
						$dtx = $this->payroll->getDataLainRekapitulasi($dd->data_lain, $dd->data_lain_nama, $dd->nominal_lain);
						$penambah+=$dtx['penambah'];
						$pengurang+=$dtx['pengurang'];
					}
				?>
				<tr>
					<td class="font"><?php echo $no; ?></td>
					<td class="font"><?php echo $d->nama_bagian; ?></td>
					<td class="font"><?php echo $d->nama_loker; ?></td>
					<td class="font"><?php echo $this->formatter->getFormatMoneyUserReq($d->jumlah_gaji_pokok); ?></td>
					<td class="font"><?php echo $this->formatter->getFormatMoneyUserReq($d->jumlah_tunjangan_tetap); ?></td>
					<td class="font"><?php echo $this->formatter->getFormatMoneyUserReq($d->jumlah_tunjangan_non); ?></td>
					<td class="font"><?php echo $this->formatter->getFormatMoneyUserReq($d->jumlah_ritasi); ?></td>
					<td class="font"><?php echo $this->formatter->getFormatMoneyUserReq($d->jumlah_uang_makan); ?></td>
					<td class="font"><?php echo $this->formatter->getFormatMoneyUserReq(($d->jumlah_pot_tidak_masuk+$d->jumlah_terlambat+$d->jumlah_izin+$d->jumlah_iskd+$d->jumlah_imp)); ?></td>
					<td class="font"><?php echo $this->formatter->getFormatMoneyUserReq($d->jumlah_bpjs_jht); ?></td>
					<td class="font"><?php echo $this->formatter->getFormatMoneyUserReq($d->jumlah_bpjs_pen); ?></td>
					<td class="font"><?php echo $this->formatter->getFormatMoneyUserReq($d->jumlah_bpjs_kes); ?></td>
					<td class="font"><?php echo $this->formatter->getFormatMoneyUserReq($d->jumlah_angsuran); ?></td>
					<td class="font"><?php echo $this->formatter->getFormatMoneyUserReq($penambah); ?></td>
					<td class="font"><?php echo $this->formatter->getFormatMoneyUserReq($pengurang); ?></td>
					<td class="font"><?php echo $this->formatter->getFormatMoneyUserReq($d->jumlah_penerimaan); ?></td>
					<?php 
						$total_gaji_pokok+=$d->jumlah_gaji_pokok;
						$total_tunjangan_tetap+=$d->jumlah_tunjangan_tetap;
						$total_tunjangan_non+=$d->jumlah_tunjangan_non;
						$total_ritasi+=$d->jumlah_ritasi;
						$total_uang_makan+=$d->jumlah_uang_makan;
						$total_pot_tidak_masuk+=($d->jumlah_pot_tidak_masuk+$d->jumlah_terlambat+$d->jumlah_izin+$d->jumlah_iskd+$d->jumlah_imp);
						$total_bpjs_jht+=$d->jumlah_bpjs_jht;
						$total_bpjs_pen+=$d->jumlah_bpjs_pen;
						$total_bpjs_kes+=$d->jumlah_bpjs_kes;
						$total_angsuran+=$d->jumlah_angsuran;
						$total_penerimaan+=$d->jumlah_penerimaan;
						$total_penambah+=$penambah;
						$total_pengurang+=$pengurang;
					?>
				</tr>
				<?php
				$no++;
				}
			}
			?>
			<tr>
				<td class="font"></td>
				<td class="font-up"><b>TOTAL</b></td>
				<td class="font"></td>
				<td class="font-up"><b><?=$this->formatter->getFormatMoneyUserReq($total_gaji_pokok)?></b></td>
				<td class="font-up"><b><?=$this->formatter->getFormatMoneyUserReq($total_tunjangan_tetap)?></b></td>
				<td class="font-up"><b><?=$this->formatter->getFormatMoneyUserReq($total_tunjangan_non)?></b></td>
				<td class="font-up"><b><?=$this->formatter->getFormatMoneyUserReq($total_ritasi)?></b></td>
				<td class="font-up"><b><?=$this->formatter->getFormatMoneyUserReq($total_uang_makan)?></b></td>
				<td class="font-up"><b><?=$this->formatter->getFormatMoneyUserReq($total_pot_tidak_masuk)?></b></td>
				<td class="font-up"><b><?=$this->formatter->getFormatMoneyUserReq($total_bpjs_jht)?></b></td>
				<td class="font-up"><b><?=$this->formatter->getFormatMoneyUserReq($total_bpjs_pen)?></b></td>
				<td class="font-up"><b><?=$this->formatter->getFormatMoneyUserReq($total_bpjs_kes)?></b></td>
				<td class="font-up"><b><?=$this->formatter->getFormatMoneyUserReq($total_angsuran)?></b></td>
				<td class="font-up"><b><?=$this->formatter->getFormatMoneyUserReq($total_penambah)?></b></td>
				<td class="font-up"><b><?=$this->formatter->getFormatMoneyUserReq($total_pengurang)?></b></td>
				<td class="font-up"><b><?=$this->formatter->getFormatMoneyUserReq($total_penerimaan)?></b></td>
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
