<style type="text/css">
	th{
		text-align: center;
	}
	@media print,screen {
		@page {
			size: Legal landscape;
			margin: 10mm;
		}
	}
</style>
<div class="col-md-12 parent-div" style="padding-top: 20px;par">
	<div class="col-md-12" style="text-align: center;">
		<b><h3 style="padding: 0px;margin: 0px;">CV Jati Kencana</h3></b>
		<b><h3 style="padding-top: 0px;margin-top: 0px;">Rekap Data PPH 21</h3></b>
		<b><h3 style="padding-top: 0px;margin-top: 0px;">Periode <?php echo $nama_periode.' ( '.$nama_sistem_penggajian.' )'; ?></h3></b>
	</div>
	<table class="table table-bordered table-responsive" width="100%" border="1" style="margin-right: 20px;">
		<tr style="background-color: #F9F9F9;">
			<th>No</th>
			<th>NIK</th>
			<th>Nama</th>
			<th>Jabatan</th>
			<th>Bagian</th>
			<th>Lokasi</th>
			<th>Grade</th>
			<th>Gaji Pokok</th>
			<th>Tanggal Masuk</th>
			<th>Masa Kerja</th>
			<th>Status PTKP</th>
			<th>Tunjangan</th>
			<th>BPJS JKK Perusahaan</th>
			<th>BPJS JKM Perusahaan</th>
			<th>BPJS JKES Perusahaan</th>
			<th>Bruto Sebulan</th>
			<th>Bruto Setahun</th>
			<th>Biaya Jabatan</th>
			<th>BPJS JHT Perusahaan</th>
			<th>BPJS JHT Pekerja</th>
			<th>Iuaran Pensiun Perusahaan</th>
			<th>Iuaran Pensiun Pekerja</th>
			<th>Netto Sebulan</th>
			<th>Netto Setahun</th>
			<th>Pajak Setahun</th>
			<th>PPH Setahun</th>
			<th>PPH Sebulan</th>
		</tr>
		<?php
		$no = 1;
		foreach ($data as $d) {
			echo '
			<tr>
			<td>'.$no.'</td>
			<td>'.$d->nik.'</td>
			<td>'.$d->nama_karyawan.'</td>
			<td>'.$d->nama_jabatan.'</td>
			<td>'.$d->nama_bagian.'</td>
			<td>'.$d->nama_loker.'</td>
			<td>'.$d->nama_grade.'</td>
			<td>'.$this->formatter->getFormatMoneyUser($d->gaji_pokok).'</td>
			<td>'.$this->formatter->getDateMonthFormatUser($d->tgl_masuk).'</td>
			<td>'.$d->masa_kerja.'</td>
			<td>'.$d->status_ptkp.'</td>
			<td>'.$this->formatter->getFormatMoneyUser($d->tunjangan).'</td>
			<td>'.$this->formatter->getFormatMoneyUser($d->bpjs_jkk_perusahaan).'</td>
			<td>'.$this->formatter->getFormatMoneyUser($d->bpjs_jkm_perusahaan).'</td>
			<td>'.$this->formatter->getFormatMoneyUser($d->bpjs_kes_perusahaan).'</td>
			<td>'.$this->formatter->getFormatMoneyUser($d->bruto_sebulan).'</td>
			<td>'.$this->formatter->getFormatMoneyUser($d->bruto_setahun).'</td>
			<td>'.$this->formatter->getFormatMoneyUser($d->biaya_jabatan).'</td>
			<td>'.$this->formatter->getFormatMoneyUser($d->bpjs_jht_perusahaan).'</td>
			<td>'.$this->formatter->getFormatMoneyUser($d->bpjs_jht_pekerja).'</td>
			<td>'.$this->formatter->getFormatMoneyUser($d->iuran_pensiun_perusahaan).'</td>
			<td>'.$this->formatter->getFormatMoneyUser($d->iuran_pensiun_pekerja).'</td>
			<td>'.$this->formatter->getFormatMoneyUser($d->netto_sebulan).'</td>
			<td>'.$this->formatter->getFormatMoneyUser($d->netto_setahun).'</td>
			<td>'.$this->formatter->getFormatMoneyUser($d->pajak_setahun).'</td>
			<td>'.$this->formatter->getFormatMoneyUser($d->pph_setahun).'</td>
			<td>'.$this->formatter->getFormatMoneyUser($d->pph_sebulan).'</td>
			</tr>';
			$no++;
		}
		?>
	</table>
</div>