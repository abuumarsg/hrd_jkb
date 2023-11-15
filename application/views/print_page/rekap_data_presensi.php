<style type = "text/css">
    @import url("https://fonts.googleapis.com/css?family=Signika");
    @import url("https://fonts.googleapis.com/css?family=Suez+One "); 
    @import url("https://fonts.googleapis.com/css?family=Salsa");
    @import url("https://fonts.googleapis.com/css?family=Times+New+Roman");
    @media print,screen {
		@page {
			size: Legal landscape;
			margin: 4mm;
		}
		th{
			padding: 1px;
			text-align: center;
			white-space: pre;
			/* font-size:6pt;  */
		}
		td{
			padding: 1px;
			white-space: pre; 
			/* font-size:6pt;  */
			height: 15px;          
		}
        .box-id {
            position:relative;
            background:white;
            margin-left: 3px;
            margin-bottom: 3px;
            /* width: 483px;
            height: 718px; */
            /* width: 320px; */
            /* height: 483px; */
            padding: 6px 6px 6px 6px;
            float:left;
            border: 1px solid white;
            /* background-image: linear-gradient(#8fecff, white, white, white); */
			page-break-inside: avoid;
        }
        .box-landscape {
            width: 1247px;
            height: 794px;
            /* background-image: linear-gradient(#8fecff, white, white, #8fecff); */
            /* background-image: linear-gradient(#8fecff, white, white, #8fecff); */
        }
        .page-break {
			page-break-inside: avoid;
		}
		.pagebreak { 
			clear: both;
			page-break-before: always; 
		}
        .nama_pt{
            font-size:11pt;
            color:#00129c;
            -ms-transform: scaleY(1.5); /* IE 9 */
            -webkit-transform: scaleY(1.5); /* Safari 3-8 */
            transform: scaleY(1.5);
            position:relative;
            font-family: 'Suez One', sans-serif;            
        }
        .photo{
            top: 1px;
            position: center;
            max-width:35px;
            /* max-height:113.38px; */
            max-height:35px;
            object-fit:cover;
        }
		.pot{
			color:red;
		}
        .font10{
            font-size:10pt;            
        }
        .font8{
            font-size:8pt;            
        }
        .font7{
            font-size:7pt;            
        }
        .font6{
            font-size:6pt;            
        }
        .font{
            font-size:10pt;            
        }
		.col-print-1 {width:8%;  float:left;}
		.col-print-2 {width:16%; float:left;}
		.col-print-3 {width:25%; float:left;padding: 5px;}
		.col-print-4 {width:33%; float:left;}
		.col-print-5 {width:42%; float:left;}
		.col-print-6 {width:50%; float:left;}
		.col-print-7 {width:58%; float:left;}
		.col-print-8 {width:66%; float:left;}
		.col-print-9 {width:75%; float:left;}
		.col-print-10{width:83%; float:left;}
		.col-print-11{width:92%; float:left;}
		.col-print-12{width:100%; float:left;}
		.redColor {
			/* -webkit-print-color-adjust: exact; */
			/* background-color: #F70000 !important; */
			background-color: #FA7E89 !important;
			-webkit-print-color-adjust:exact !important;
			print-color-adjust:exact !important;
		}
		.orangeColor {
			/* background-color: #F78100 !important; */
			background-color: #FAC47E !important;
			-webkit-print-color-adjust:exact !important;
			print-color-adjust:exact !important;
		}
		.yellowColor {
			/* background-color: #F7E000 !important; */
			background-color: #FAFA7E !important;
			-webkit-print-color-adjust:exact !important;
			print-color-adjust:exact !important;
		}
		.greenColor {
			/* background-color: #0DF700 !important; */
			background-color: #95FA7E !important;
			-webkit-print-color-adjust:exact !important;
			print-color-adjust:exact !important;
		}
		.blueColor {
			/* background-color: #005FF7 !important; */
			background-color: #7EA7FA !important;
			-webkit-print-color-adjust:exact !important;
			print-color-adjust:exact !important;
		}
		.blueSkyColor {
			/* background-color: #00CEF7 !important; */
			background-color: #7EF5FA !important;
			-webkit-print-color-adjust:exact !important;
			print-color-adjust:exact !important;
		}
		.unguColor {
			/* background-color: #7900F7 !important; */
			background-color: #BF7EFA !important;
			-webkit-print-color-adjust:exact !important;
			print-color-adjust:exact !important;
		}
		.pinkColor {
			/* background-color: #7900F7 !important; */
			background-color: #F779F7 !important;
			-webkit-print-color-adjust:exact !important;
			print-color-adjust:exact !important;
		}
		.grayColor {
			background-color: #C1C1C1 !important;
			-webkit-print-color-adjust:exact !important;
			print-color-adjust:exact !important;
		}
		.unguMudaColor {
			background-color: #E0B9FA !important;
			-webkit-print-color-adjust:exact !important;
			print-color-adjust:exact !important;
		}
		.hijauMudaColor {
			background-color: #D6FAB9 !important;
			-webkit-print-color-adjust:exact !important;
			print-color-adjust:exact !important;
		}
		.navyColor {
			background-color: #EAEAEA !important;
			-webkit-print-color-adjust:exact !important;
			print-color-adjust:exact !important;
		}
	}
</style>
<style type="text/css">
	.parent-div{
		border-style: solid;border-color: black;border-width: 2px;padding: 10px;height:20px;
	}
	.center{
		text-align: center;
	}
	.border-buttom{
		border-bottom-style: solid;
		border-width: 1px;
	}
	.border-top{
		border-top-style: solid;
	}
	.border_dot{
		border-bottom-style: dotted;
		border-width: 1px;
	}
</style>
	<!-- <?php if($header != '1'){ } ?> -->
	<div class="col-print-12">
		<table width="100%">
			<tr>
				<th class="center"><img class="photo" src="<?= $this->otherfunctions->companyClientProfile()['logo'];?>" alt="Logo Perusahaan"></th>
			</tr>
			<tr>
				<th class="center border-buttom"><b class="nama_pt">Data Presensi <?=$this->otherfunctions->companyClientProfile()['name_office'];?></b></th>
			</tr>
		</table>
		<table width="100%">
			<tr>
				<td class="center" style="text-align: left;">Bagian : <?=$nama_bagian;?></td>
				<td class="center" style="text-align: right;">Dicetak : <?=$admin;?></td>
			</tr>
			<tr>
				<td class="center border-buttom" style="text-align: left;"><?=$this->formatter->getDateMonthFormatUser($tgl_mulai).' - '.$this->formatter->getDateMonthFormatUser($tgl_selesai);?></td>
				<td class="center border-buttom" style="text-align: right;">Tanggal : <?=$time;?></td>
			</tr>
		</table>
		<br>
	</div>
<div class="box-landscape">
	<?php
		foreach ($dateloop as $key => $date) {
			$tahun=$this->otherfunctions->getDataExplode($date,'-','start');
			$bulan=$this->otherfunctions->getDataExplode($date,'-','end');
			$tgl=$this->otherfunctions->getDataExplode($date,'-','3');
			$tanggal[$bulan.'-'.$tahun][] = $tgl;
		}
		$bulantgl = [];
		foreach ($tanggal as $bln => $tg) {
			$bulantgl[$bln] = count($tg);
		}
	?>
	<div class="col-print-12">
		<table width="100%" class="parent-div" border="2">
			<tr class="grayColor">
				<th class="center" rowspan="3">No</th>
				<th rowspan="3">Nama</th>
				<!-- <th class="center" rowspan="3">Jabatan</th> -->
				<?php
					foreach ($bulantgl as $bulan => $count) {
						$month=$this->otherfunctions->getDataExplode($bulan,'-','start');
						$year=$this->otherfunctions->getDataExplode($bulan,'-','end');
						echo '<th class="center" colspan="'.($count*2).'">'.$this->formatter->getNameOfMonth($month).' '.$year.'</th>';
					}
				?>
			</tr>
			<tr class="grayColor">
				<?php
					foreach ($dateloop as $key => $date) {
						$libur = $this->otherfunctions->checkHariLiburActive($date);
						$libur = (!empty($libur)) ? ' redColor' : null;
						$tgl=$this->otherfunctions->getDataExplode($date,'-','3');
						echo '<th class="center '.$libur.'" colspan="2">'.$tgl.'</th>';
					}
				?>
			</tr>
			<tr class="grayColor">
				<?php
					foreach ($dateloop as $key => $date) {
						$libur = $this->otherfunctions->checkHariLiburActive($date);
						$libur = (!empty($libur)) ? ' redColor' : null;
						echo '<th class="center font8'.$libur.'">&nbsp;IN&nbsp;</th>';
						echo '<th class="center font8'.$libur.'">OUT</th>';
					}
				?>
			</tr>
			<tr>
				<?php
					$no = 1;
					foreach ($data as $nik => $tanggal) {
						$nama_karyawan = $this->model_karyawan->getEmployeeNik($nik)['nama'];
						$namaEmp = $this->otherfunctions->cutText($nama_karyawan, 15, 2);
						echo '<tr>
							<td class="center">'.$no.'</td>
							<td class="font8">'.$namaEmp.'</td>';
							asort($tanggal);
							foreach ($tanggal as $key => $value) {
								$color = null;
								$jam_mulaix=(($value['jam_mulai'] != '00:00:00') ? substr($value['jam_mulai'],0,5) : null);
								$jam_selesaix=(($value['jam_mulai'] != '00:00:00') ? substr($value['jam_selesai'],0,5) : null);
								// echo '<td class="center font7 '.$color.'"><b>'.$jam_mulaix.'</b></td>';
								// echo '<td class="center font7 '.$color.'"><b>'.$jam_selesaix.'</b></td>';
								if($value['alpa'] == 1){
									$color = 'redColor';
									echo '<td class="center font7 '.$color.'" colspan="2"><b>Alpa</b></td>';
								}elseif($value['terlambat'] == 1){
									$color = 'orangeColor';
									echo '<td class="center font7 '.$color.'"><b>'.$jam_mulaix.'</b></td>';
									echo '<td class="center font7 '.$color.'"><b>'.$jam_selesaix.'</b></td>';
								}elseif($value['dinasLuar'] == 1){
									$color = 'yellowColor';
									echo '<td class="center font7 '.$color.'"><b>'.$jam_mulaix.'</b></td>';
									echo '<td class="center font7 '.$color.'"><b>'.$jam_selesaix.'</b></td>';
								}elseif($value['cuti'] == 1){
									$color = 'greenColor';
									echo '<td class="center font7 '.$color.'" colspan="2"><b>Cuti</b></td>';
								}elseif($value['sdr'] == 1){
									$color = 'blueColor';
									echo '<td class="center font7 '.$color.'" colspan="2"><b>SDR</b></td>';
								}elseif($value['izin'] == 1){
									$color = 'blueSkyColor';
									echo '<td class="center font7 '.$color.'" colspan="2"><b>Izin</b></td>';
								}elseif($value['imp'] == 1){
									$color = 'unguColor';
									echo '<td class="center font7 '.$color.'"><b>'.$jam_mulaix.'</b></td>';
									echo '<td class="center font7 '.$color.'"><b>'.$jam_selesaix.'</b></td>';
								}elseif($value['cutiLahir'] == 1){
									$color = 'pinkColor';
									echo '<td class="center font7 '.$color.'" colspan="2"><b>C. Lahir</b></td>';
								}elseif($value['izinTerlambat'] == 1){
									echo '<td class="center font7 unguMudaColor"><b>'.$jam_mulaix.'</b></td>';
									echo '<td class="center font7 unguMudaColor"><b>'.$jam_selesaix.'</b></td>';
								}elseif($value['izinLain'] == 1){
									echo '<td class="center font7 hijauMudaColor" colspan="2"><b>Izin Lain</b></td>';
									// echo '<td class="center font7 hijauMudaColor"><b>'.$jam_mulaix.'</b></td>';
									// echo '<td class="center font7 hijauMudaColor"><b>'.$jam_selesaix.'</b></td>';
								}elseif($value['libur'] == 1){
									if(empty($jam_mulaix) || empty($jam_selesaix)){
										$color = 'navyColor';
										echo '<td class="center font7 '.$color.'" colspan="2"><b>Libur</b></td>';
									}else{
										echo '<td class="center font7"><b>'.$jam_mulaix.'</b></td>';
										echo '<td class="center font7"><b>'.$jam_selesaix.'</b></td>';
									}
								}else{
									echo '<td class="center font7"><b>'.$jam_mulaix.'</b></td>';
									echo '<td class="center font7"><b>'.$jam_selesaix.'</b></td>';
								}
							}
							echo '</tr>';
						$no++;
					}
				?>
			</tr>
		</table>
	</div>
	<div class="col-print-12">
		<div class="col-print-8"><br>
		<p><b>Keterangan : </b></p>
			<table width="100%" class="parent-div" border="1">
				<tr>
					<td width="10%" class="font10 redColor"><b>Alpa</b></td>
					<td width="10%" class="font10 orangeColor"><b>Terlambat</b></td>
					<td width="10%" class="font10 yellowColor"><b>Dinas Luar</b></td>
					<td width="10%" class="font10 greenColor"><b>Cuti Tahun</b></td>
					<td width="10%" class="font10 blueColor"><b>Surat Dokter</b></td>
					<td width="10%" class="font10 blueSkyColor"><b>Izin 1 Hari Kerja</b></td>
					<td width="10%" class="font10 unguColor"><b>Izin Meninggalkan Kerja</b></td>
					<td width="10%" class="font10 pinkColor"><b>Cuti Melahirkan</b></td>
					<td width="10%" class="font10 unguMudaColor"><b>Izin Terlambat</b></td>
				</tr>
			</table>
		</div>
	</div>
</div>
<div class="pagebreak"></div>
<div class="pagebreak"></div>
<div class="col-print-12">
	<table width="100%">
		<tr>
			<th class="center"><img class="photo" src="<?= $this->otherfunctions->companyClientProfile()['logo'];?>" alt="Logo Perusahaan"></th>
		</tr>
		<tr>
			<th class="center border-buttom"><b class="nama_pt">Data Presensi <?=$this->otherfunctions->companyClientProfile()['name_office'];?></b></th>
		</tr>
	</table>
	<table width="100%">
		<tr>
			<td class="center" style="text-align: left;">Bagian : <?=$nama_bagian;?></td>
			<td class="center" style="text-align: right;">Dicetak : <?=$admin;?></td>
		</tr>
		<tr>
			<td class="center border-buttom" style="text-align: left;"><?=$this->formatter->getDateMonthFormatUser($tgl_mulai).' - '.$this->formatter->getDateMonthFormatUser($tgl_selesai);?></td>
			<td class="center border-buttom" style="text-align: right;">Tanggal : <?=$time;?></td>
		</tr>
	</table>
	<br>
</div>
<div class="box-landscape">
	<div class="col-print-12">
		<table width="100%" class="parent-div" border="2">
			<tr class="grayColor">
				<th class="center">No</th>
				<th class="center">Nama</th>
				<th class="center">Jabatan</th>
				<th class="center">Alpa<br>(Hari)</th>
				<th class="center">Terlambat<br>(Hari)</th>
				<th class="center">Dinas Luar<br>(Hari)</th>
				<th class="center">Cuti Tahun<br>(Hari)</th>
				<th class="center">SDR<br>(Hari)</th>
				<th class="center">Izin 1 Hari<br> Kerja (Hari)</th>
				<th class="center">Izin Meninggalkan<br> Kerja (Hari)</th>
				<th class="center">Cuti Melahirkan<br>(Hari)</th>
			</tr>
			<tr>
				<?php
					$total_alpa = 0;
					$total_terlambat = 0;
					$total_dinasLuar = 0;
					$total_cuti = 0;
					$total_sdr = 0;
					$total_izin = 0;
					$total_imp = 0;
					$total_cutiLahir = 0;
					$nox = 1;
					foreach ($data as $nik => $tanggal) {
						$emp = $this->model_karyawan->getEmployeeNik($nik);
						echo '<tr>
							<td>'.$nox.'</td>
							<td>'.$emp['nama'].'</td>
							<td>'.$emp['nama_jabatan'].'</td>';
							$alpa = null;
							$terlambat = null;
							$dinasLuar = null;
							$cuti = null;
							$sdr = null;
							$izin = null;
							$imp = null;
							$cutiLahir = null;
							foreach ($tanggal as $key => $value) {
								if($value['alpa'] == 1){
									$alpa +=1;
								}elseif($value['terlambat'] == 1){
									$terlambat +=1;
								}elseif($value['dinasLuar'] == 1){
									$dinasLuar +=1;
								}elseif($value['cuti'] == 1){
									$cuti +=1;
								}elseif($value['sdr'] == 1){
									$sdr +=1;
								}elseif($value['izin'] == 1){
									$izin +=1;
								}elseif($value['imp'] == 1){
									$imp +=1;
								}elseif($value['cutiLahir'] == 1){
									$cutiLahir +=1;
								}
							}
							echo '<td class="center font10"><b>'.$alpa.'</b></td>
								<td class="center font10"><b>'.$terlambat.'</b></td>
								<td class="center font10"><b>'.$dinasLuar.'</b></td>
								<td class="center font10"><b>'.$cuti.'</b></td>
								<td class="center font10"><b>'.$sdr.'</b></td>
								<td class="center font10"><b>'.$izin.'</b></td>
								<td class="center font10"><b>'.$imp.'</b></td>
								<td class="center font10"><b>'.$cutiLahir.'</b></td>
							</tr>';
							$total_alpa += $alpa;
							$total_terlambat += $terlambat; 
							$total_dinasLuar += $dinasLuar;
							$total_cuti += $cuti;
							$total_sdr += $sdr;
							$total_izin += $izin;
							$total_imp += $imp;
							$total_cutiLahir += $cutiLahir;
						$nox++;
					}
				?>
			</tr>
			<tr class="yellowColor">
				<th class="center" colspan="3">TOTAL</th>
				<th class="center"><?=$total_alpa?><br>(Hari)</th>
				<th class="center"><?=$total_terlambat?><br>(Hari)</th>
				<th class="center"><?=$total_dinasLuar?><br>(Hari)</th>
				<th class="center"><?=$total_cuti?><br>(Hari)</th>
				<th class="center"><?=$total_sdr?><br>(Hari)</th>
				<th class="center"><?=$total_izin?><br>(Hari)</th>
				<th class="center"><?=$total_imp?><br>(Hari)</th>
				<th class="center"><?=$total_cutiLahir?><br>(Hari)</th>
			</tr>
		</table>
		<br>
	</div>
</div>