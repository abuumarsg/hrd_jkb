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
		<b><h3 style="padding-top: 0px;margin-top: 0px;">Rekap Insentif Karyawan</h3></b>
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
			<th>Nama Insentif</th>
			<th>Nominal Insentif</th>
			<th>Tahun</th>
		</tr>
		<?php
		foreach ($data_emp as $key => $value) {
			$no = 1;
			$total = 0;
			$total_all = 0;
			foreach ($data as $dkey => $dvalue) {
				if($key == $dvalue['id_karyawan']){
					?>
					<tr>
						<td><?php echo $no; ?></td>
						<td><?php echo $dvalue['nik']; ?></td>
						<td><?php echo $dvalue['nama_karyawan']; ?></td>
						<td><?php echo $dvalue['jabatan']; ?></td>
						<td><?php echo $dvalue['bagian']; ?></td>
						<td><?php echo $dvalue['loker']; ?></td>
						<td><?php echo $dvalue['grade']; ?></td>
						<td><?php echo $dvalue['nama_insentif']; ?></td>
						<td><?php echo $dvalue['nominal_insentif']; ?></td>
						<td><?php echo $dvalue['tahun_insentif']; ?></td>
					</tr>
					<?php
					$total += $dvalue['nominal_insentif'];
					$no++;
				}
				$total_all += $dvalue['nominal_insentif'];
			}
				echo '<tr style="background-color: #F9F9F9;">
							<td colspan="8"><b>Total Insentif</b></td>
							<td colspan="2"><b>'.$this->formatter->getFormatMoneyUser($total).'</b></td>
						</tr>';
		}
		echo '<tr style="background-color: #F9F9F9;">
					<td colspan="10" style="height: 50px;"></td>
				</tr>';
		echo '<tr style="background-color: #F9F9F9;">
					<td colspan="8"><b>Total Semua Insentif</b></td>
					<td colspan="2"><b>'.$this->formatter->getFormatMoneyUser($total_all).'</b></td>
				</tr>';
		?>
	</table>
</div>