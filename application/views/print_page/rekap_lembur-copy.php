<div style="padding: 10px;">
	<div style="font-weight: bold;text-align: center;">DAFTAR GAJI LEMBUR CV JATI KENCANA</div>
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
			.box-id {
				/* page-break-inside: avoid; */
				/* transform: scale(0.9, 0.9); */
				page-break-after: always;
			}
			.font{
				font-size:6pt; 
			}
		}
	</style>
	<br>
	<!-- <div class="box-id">
		<table class="table-bordered table-responsive" width="100%" border="1">
			<tr style="background-color: #F9F9F9;">
				<th class="font">No.</th>
				<th class="font">Nama</th>
				<th class="font">Jabatan</th>
				<th class="font">NIK</th>
				<th class="font">Lokasi Kerja</th>
				<th class="font">Tgl Masuk</th>
				<th class="font">Masa Kerja</th>
				<th class="font">Gaji Pokok</th>
				<th class="font">Upah</th>
				<th class="font">Jam Hari Biasa</th>
				<th class="font">Nominal Hari Biasa</th>
				<th class="font">Jam Hari Libur</th>
				<th class="font">Nominal Hari Libur</th>
				<th class="font">Jam Lembur Istirahat</th>
				<th class="font">Nominal Lembur Istirahat</th>
				<th class="font">Gaji Diterima</th>
				<th class="font">No. Rekening</th>
				<th>Tanggal</th>
			</tr>
			<?php
			// $no = 1;
			// foreach ($emp_gaji as $e) {
			// 	$emp = $this->model_karyawan->getEmployeeId($e->id_karyawan);
				?>
			<tr>
				<td class="font"><?php echo $no; ?></td>
				<td class="font"><?php echo $e->nama_karyawan; ?></td>
				<td class="font"><?php echo $e->nama_jabatan; ?></td>
				<td class="font"><?php echo $emp['nik']; ?></td>
				<td class="font"><?php echo $e->nama_loker; ?></td>
				<td class="font"><?php echo $e->tgl_masuk; ?></td>
				<td class="font"><?php echo $e->masa_kerja; ?></td>
				<td class="font"><?php echo $this->formatter->getFormatMoneyUser($e->gaji_pokok); ?></td>
				<td class="font"><?php echo $this->formatter->getFormatMoneyUser($e->upah); ?></td>
				<td class="font"><?php echo $e->jam_biasa; ?></td>
				<td class="font"><?php echo $this->formatter->getFormatMoneyUser($e->nominal_biasa); ?></td>
				<td class="font"><?php echo $e->jam_libur; ?></td>
				<td class="font"><?php echo $this->formatter->getFormatMoneyUser($e->nominal_libur); ?></td>
				<td class="font"><?php echo $e->jam_libur_pendek; ?></td>
				<td class="font"><?php echo $this->formatter->getFormatMoneyUser($e->nominal_libur_pendek); ?></td>
				<td class="font"><?php echo $this->formatter->getFormatMoneyUser($e->gaji_terima); ?></td>
				<td class="font"><?php echo $e->no_rekening; ?></td>
				<td><?php echo $this->formatter->getDateMonthFormatUser($periode['tgl_transfer']); ?></td>
			</tr>
			<?php
			// $no++;
			// }
		?>
		</table>
	</div> -->
	<?php 
			$nn=1;
			$tb_1 = '';
			$tb_2 = '';
			$tgl_mulai = $periode['tgl_mulai'];
			$tgl_selesai = $periode['tgl_selesai'];
			while ($periode['tgl_mulai'] <= $periode['tgl_selesai'])
			{
				if($nn < 16){
					$tb_1 .= '<th class="font">'.$this->formatter->getNameOfDay($periode['tgl_mulai']).', '.$this->formatter->getDateMonthFormatUser($periode['tgl_mulai'])."\n".'Jam Lembur | Rupiah |'."\n".'Kode Proyek | Keterangan</th>';
				}elseif($nn > 15){
					$tb_2 .= '<th class="font">'.$this->formatter->getNameOfDay($periode['tgl_mulai']).', '.$this->formatter->getDateMonthFormatUser($periode['tgl_mulai'])."\n".'Jam Lembur | Rupiah |'."\n".' Kode Proyek | Keterangan</th>';
				}
				$periode['tgl_mulai'] = date('Y-m-d', strtotime($periode['tgl_mulai'] . ' +1 day'));
				$nn++;
			}
	?>
	<div class="box-id">
		<table class="table-bordered table-responsive" width="100%" border="1">
			<tr style="background-color: #F9F9F9;">
				<th class="font">No.</th>
				<th class="font">Nama</th>
				<th class="font">NIK</th>
				<?=$tb_1?>
			</tr>
			<?php
			$no = 1;
			foreach ($emp_gaji as $e) {
				$emp = $this->model_karyawan->getEmployeeId($e->id_karyawan);
			?>
			<tr>
				<td class="font"><?php echo $no; ?></td>
				<td class="font"><?php echo $e->nama_karyawan; ?></td>
				<td class="font"><?php echo $emp['nik']; ?></td>
				<?php
					$mm=1;
					$rw_1 = '';
					$rw_2 = '';
					$start= $tgl_mulai;
					while ($start <= $tgl_selesai)
					{
						$d_lembur=$this->model_karyawan->getDataLemburDate($e->id_karyawan, $start);
						if($mm < 16){
							if (count($d_lembur) > 0) {
								$lembur_karb='';
								$row_lemb=1;
								foreach ($d_lembur as $dl) {
									$max_lem=count($d_lembur);
									$jam_lembur=$this->formatter->convertJamtoDecimal($dl->jumlah_lembur);
									if($start == $dl->tgl_mulai){
										$nominalLembur=$this->payroll->getNominalLemburDate($e->id_karyawan, $start, $dl->jenis_lembur, $dl->val_jumlah_lembur, $dl->val_potong_jam);
										$nl=$this->otherfunctions->pembulatanDepanKoma($nominalLembur);
										$lembur_karb.=$jam_lembur." Jam | ".$this->formatter->getFormatMoneyUser($nl).' | '.$dl->kode_customer.' | '.$dl->keterangan.(($max_lem > ($row_lemb))?"\n":'');
									}
									$row_lemb++;
								}
								$rw_1.='<td class="font">'.$lembur_karb.'</td>';
							}else{
								$rw_1.='<td class="font"></td>';
							}
							$jumTgl = $mm;
						}elseif($mm > 15){
							if (count($d_lembur) > 0) {
								$lembur_kar='';
								$row_lem=1;
								foreach ($d_lembur as $dl) {
									$max_lem=count($d_lembur);
									$jam_lembur=$this->formatter->convertJamtoDecimal($dl->jumlah_lembur);
									if($start == $dl->tgl_mulai){
										$nominalLembur=$this->payroll->getNominalLemburDate($e->id_karyawan, $e->tgl_mulai, $dl->jenis_lembur, $dl->val_jumlah_lembur, $dl->val_potong_jam);
										$nl=$this->otherfunctions->pembulatanDepanKoma($nominalLembur);
										$lembur_kar.=$jam_lembur." Jam | ".$this->formatter->getFormatMoneyUser($nl).' | '.$dl->kode_customer.' | '.$dl->keterangan.(($max_lem > ($row_lem))?"\n":'');
									}
									$row_lem++;
								}
								$rw_2.='<td class="font">'.$lembur_kar.'</td>';
							}else{
								$rw_2.='<td class="font"></td>';
							}
						}
						$start = date('Y-m-d', strtotime($start . ' +1 day'));
						$mm++;
					}
				?>
				<?=$rw_1;?>
			</tr>
			<?php
			$no++;
			}
		?>
		</table>
	</div>
	<div class="box-id">
		<table class="table-bordered table-responsive" width="100%" border="1">
			<tr style="background-color: #F9F9F9;">
				<th class="font">No.</th>
				<?=$tb_2?>
				<th class="font">Ekuivalen</th>
				<th class="font">Jam Lembur</th>
				<th class="font">Total Gaji</th>
			</tr>
			<?php
			$no = 1;
			foreach ($emp_gaji as $e) {
				$emp = $this->model_karyawan->getEmployeeId($e->id_karyawan);
				?>
			<tr>
				<td class="font"><?php echo $no; ?></td>
				<?=$rw_2;?>
				<td class="font"><?php echo $e->ekuivalen; ?></td>
				<td class="font"><?php echo $e->total_jam; ?></td>
				<td class="font"><?php echo $this->formatter->getFormatMoneyUser($e->gaji_terima); ?></td>
			</tr>
			<?php
			$no++;
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
				<td style="padding-top: 50px;"></td>
				<td style="padding-top: 50px;"></td>
			</tr>
			<tr width="50%">
				<td style="text-align: center;">Ausonta Martono</td>
				<td style="text-align: center;"><?=$nama_buat?></td>
			</tr>
		</table>
	</div>
</div>