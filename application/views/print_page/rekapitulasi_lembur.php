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
		<th>Keterangan</th>
		<th>Gaji Pokok</th>
		<th>Upah</th>
		<th>Jam Hari Biasa</th>
		<th>Nominal Hari Biasa</th>
		<th>Jam Hari Libur</th>
		<th>Nominal Hari Libur</th>
		<th>Jam Libur Pendek</th>
		<th>Nominal Libur Pendek</th>
		<th>Gaji Diterima</th>
	</tr>
	<?php
		$loker = $this->model_karyawan->getDataLogPayrollLembur(['a.create_by'=>$id_admin,'a.kode_periode'=>$kode_periode], 0, 'a.kode_loker');
		$get_loker = [];
		$datax = [];
		foreach ($loker as $l) {
			$get_loker[] = $l->kode_loker;
		}
		foreach ($get_loker as $gk => $gv) {
			$data = $this->model_karyawan->getDataLogPayrollLembur(['a.kode_loker'=>$gv,'a.create_by'=>$id_admin,'a.kode_periode'=>$kode_periode]);
			$nama_loker = $this->otherfunctions->convertResultToRowArray($this->model_master->getLokerKode($gv));
			$keterangan = $nama_loker['nama'];
			$gaji_pokok = 0;
			$upah = 0;
			$jam_biasa = 0;
			$nominal_biasa = 0;
			$jam_libur = 0;
			$nominal_libur = 0;
			$jam_libur_pendek = 0;
			$nominal_libur_pendek = 0;
			$penerima = 0;
			foreach ($data as $d) {
				$gaji_pokok += $d->gaji_pokok;
				$upah += $d->upah;
				$jam_biasa += $d->jam_biasa;
				$nominal_biasa += $d->nominal_biasa;
				$jam_libur += $d->jam_libur;
				$nominal_libur += $d->nominal_libur;
				$jam_libur_pendek += $d->jam_libur_pendek;
				$nominal_libur_pendek += $d->nominal_libur_pendek;
				$penerima += $d->gaji_terima;
			}
			$datax[$gv] = [
				'keterangan'=>$keterangan,
				'gaji_pokok'=>$gaji_pokok,
				'upah'=>$upah,
				'jam_biasa'=>$jam_biasa,
				'nominal_biasa'=>$nominal_biasa,
				'jam_libur'=>$jam_libur,
				'nominal_libur'=>$nominal_libur,
				'jam_libur_pendek'=>$jam_libur_pendek,
				'nominal_libur_pendek'=>$nominal_libur_pendek,
				'penerima'=>$penerima
			];
		}

		foreach ($datax as $dk => $dv) {
	?>
	<tr>
		<td><?php echo $dv['keterangan']; ?></td>
		<td><?php echo $this->formatter->getFormatMoneyUser($dv['gaji_pokok']); ?></td>
		<td><?php echo $this->formatter->getFormatMoneyUser($dv['upah']); ?></td>
		<td><?php echo $dv['jam_biasa']; ?></td>
		<td><?php echo $this->formatter->getFormatMoneyUser($dv['nominal_biasa']); ?></td>
		<td><?php echo $dv['jam_libur']; ?></td>
		<td><?php echo $this->formatter->getFormatMoneyUser($dv['nominal_libur']); ?></td>
		<td><?php echo $dv['jam_libur_pendek']; ?></td>
		<td><?php echo $this->formatter->getFormatMoneyUser($dv['nominal_libur_pendek']); ?></td>
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
