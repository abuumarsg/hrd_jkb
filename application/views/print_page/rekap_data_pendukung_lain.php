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
<div class="col-md-12 parent-div" style="padding-top: 20px;">
	<div class="col-md-12" style="text-align: center;">
		<b><h3 style="padding: 0px;margin: 0px;">CV Jati Kencana</h3></b>
		<b><h3 style="padding-top: 0px;margin-top: 0px;">Rekap Data Pendukung Lain</h3></b>
	</div>
	<table class="table table-bordered table-responsive" width="100%" border="1">
		<tr style="background-color: #F9F9F9;">
			<th>No</th>
			<th>NIK</th>
			<th>Nama</th>
			<th>Jabatan</th>
			<th>Bagian</th>
			<th>Lokasi</th>
			<th>Grade</th>
			<th>Nominal</th>
			<th>Sifat</th>
			<th>Keterangan</th>
			<th>Tahun</th>
			<th>Periode Penggajian</th>
		</tr>
		<?php
		$no = 1;
		foreach ($data as $d) {
			$emp_data = $this->model_karyawan->getEmployeeId($d->id_karyawan);
			echo '
			<tr>
			<td>'.$no.'</td>
			<td>'.$emp_data['nik'].'</td>
			<td>'.$emp_data['nama'].'</td>
			<td>'.$emp_data['nama_jabatan'].'</td>
			<td>'.$emp_data['bagian'].'</td>
			<td>'.$emp_data['nama_loker'].'</td>
			<td>'.$emp_data['nama_grade'].'</td>
			<td>'.$this->formatter->getFormatMoneyUser($d->nominal).'</td>
			<td>'.ucwords($d->sifat).'</td>
			<td>'.ucwords($d->keterangan).'</td>
			<td>'.$d->tahun.'</td>
			<td>'.$d->nama_periode.'</td>
			</tr>';
			$no++;
		}
		?>
	</table>
</div>