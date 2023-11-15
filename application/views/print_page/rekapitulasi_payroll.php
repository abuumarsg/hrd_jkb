<div style="padding: 10px;">
<div style="font-weight: bold;text-align: center;">REKAPITULASI GAJI CV JATI KENCANA</div>
<div style="font-weight: bold;text-align: center;">
	BULAN: <?php echo strtoupper($this->formatter->getMonth()[date('m',strtotime($periode['tgl_selesai']))]); ?> <?php echo date('Y',strtotime($periode['tgl_selesai'])); ?>
</div>
<style type="text/css">
	th{
		padding: 5px;
		text-align: center;
	}
	td{
		padding: 5px;
	}
	@media print,screen {
		@page {
			size: Legal landscape;
			margin: 10mm;
		}
	}
</style>
<br>
<table class=" table-bordered table-striped table-responsive" border="1" style="width: 100%;">
	<tr>
		<th>KETERANGAN</th>
		<th>GAJI POKOK</th>
		<th>INSENTIF</th>
		<th>RITASI</th>
		<th>UANG MAKAN</th>
		<th>POT TDK MASUK</th>
		<th>BPJS TK - JHT,JKK,JKM</th>
		<th>JAMINAN PENSIUN</th>
		<th>BPJS KES</th>
		<th>PIUTANG</th>
		<th>PENERIMAAN</th>
	</tr>
	<?php
		$loker = $this->model_karyawan->getDataLogPayroll(null, 0, 'a.kode_loker');
		$get_loker = [];
		$datax = [];
		foreach ($loker as $l) {
			$get_loker[] = $l->kode_loker;
		}
		foreach ($get_loker as $gk => $gv) {
			$data = $this->model_karyawan->getDataLogPayroll(['a.kode_loker'=>$gv]);
			$nama_loker = $this->otherfunctions->convertResultToRowArray($this->model_master->getLokerKode($gv));
			$keterangan = $nama_loker['nama'];
			$gaji_pokok = 0;
			$insentif = 0;
			$ritasi = 0;
			$uang_makan = 0;
			$pot = 0;
			$bpjs_tk = 0;
			$jam_pns = 0;
			$jam_kes = 0;
			$piutang = 0;
			$penerima = 0;
			foreach ($data as $d) {
				$gaji_pokok += $d->gaji_pokok;
				$insentif += $d->insentif;
				$ritasi += $d->ritasi;
				$uang_makan += $d->uang_makan;
				$pot += ($d->pot_tidak_masuk+$d->n_terlambat+$d->n_izin+$d->n_iskd+$d->n_imp);
				$bpjs_tk += ($d->bpjs_jht+$d->bpjs_jkk+$d->bpjs_jkm);
				$jam_pns += $d->bpjs_pen;
				$jam_kes += $d->bpjs_kes;
				$piutang += $d->angsuran;
				$penerima += $d->gaji_bersih;
			}
			$datax[$gv] = [
				'keterangan'=>$keterangan,
				'gaji_pokok'=>$gaji_pokok,
				'insentif'=>$insentif,
				'ritasi'=>$ritasi,
				'uang_makan'=>$uang_makan,
				'pot'=>$pot,
				'bpjs_tk'=>$bpjs_tk,
				'jam_pns'=>$jam_pns,
				'jam_kes'=>$jam_kes,
				'piutang'=>$piutang,
				'penerima'=>$penerima
			];
		}
		foreach ($datax as $dk => $dv) {
	?>
	<tr>
		<td><?php echo $dv['keterangan']; ?></td>
		<td><?php echo $this->formatter->getFormatMoneyUser($dv['gaji_pokok']); ?></td>
		<td><?php echo $this->formatter->getFormatMoneyUser($dv['insentif']); ?></td>
		<td><?php echo $this->formatter->getFormatMoneyUser($dv['ritasi']); ?></td>
		<td><?php echo $this->formatter->getFormatMoneyUser($dv['uang_makan']); ?></td>
		<td><?php echo $this->formatter->getFormatMoneyUser($dv['pot']); ?></td>
		<td><?php echo $this->formatter->getFormatMoneyUser($dv['bpjs_tk']); ?></td>
		<td><?php echo $this->formatter->getFormatMoneyUser($dv['jam_pns']); ?></td>
		<td><?php echo $this->formatter->getFormatMoneyUser($dv['jam_kes']); ?></td>
		<td><?php echo $this->formatter->getFormatMoneyUser($dv['piutang']); ?></td>
		<td><?php echo $this->formatter->getFormatMoneyUser($dv['penerima']); ?></td>
	</tr>
	<?php 
	}
?>
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
		<td style="padding-top: 100px;"></td>
		<td style="padding-top: 100px;"></td>
	</tr>
	<tr width="50%">
		<td style="text-align: center;">Ausonta Martono</td>
		<td style="text-align: center;">Fara Safitri</td>
	</tr>
</table>
</div>
