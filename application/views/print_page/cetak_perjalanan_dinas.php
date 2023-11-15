<style type = "text/css">
    @import url("https://fonts.googleapis.com/css?family=Signika");
    @import url("https://fonts.googleapis.com/css?family=Suez+One "); 
    @import url("https://fonts.googleapis.com/css?family=Salsa");
    @import url("https://fonts.googleapis.com/css?family=Times+New+Roman");
    @media print,screen {
        .box-id {
            position:relative;
            background:white;
            margin-left: 3px;
            margin-bottom: 3px;
            /* width: 794px;
            height: 560px; */
            width: 794px;
            height: 1096px;
            padding: 6px 6px 6px 6px;
            float:left;
            border: 1px solid white;
            background-image: linear-gradient(#8fecff, white, white, white);
			page-break-inside: avoid;
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
            top: 7px;
            position: center;
            max-width:50px;
            /* max-height:113.38px; */
            max-height:50px;
            object-fit:cover;
        }
		.pot{
			color:red;
		}
        .font{
            font-size:12pt;            
        }
        .font8{
            font-size:8pt;            
        }
        .font9{
            font-size:9pt;            
        }
		.col-print-1 {width:8%;  float:left;}
		.col-print-2 {width:16%; float:left;}
		.col-print-3 {width:25%; float:left;}
		.col-print-4 {width:33%; float:left;}
		.col-print-5 {width:42%; float:left;}
		.col-print-6 {width:50%; float:left;}
		.col-print-7 {width:58%; float:left;}
		.col-print-8 {width:66%; float:left;}
		.col-print-9 {width:75%; float:left;}
		.col-print-10{width:83%; float:left;}
		.col-print-11{width:92%; float:left;}
		.col-print-12{width:100%; float:left;}
	}
</style>
<style type="text/css">
	.parent-div{
		border-style: solid;border-color: red;border-width: 1px;padding: 10px;
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
<?php 
// echo '<pre>';
// print_r($datax);
	// if($datax['tgl_mulai'] == $datax['tgl_selesai']){
	// 	$hari = $this->formatter->getNameOfDay($datax['tgl_mulai']);
	// 	$tanggal = $this->formatter->getDateMonthFormatUser($datax['tgl_mulai']);
	// }else{
	// 	$hari = $this->formatter->getNameOfDay($datax['tgl_mulai']).' sampai '.$this->formatter->getNameOfDay($datax['tgl_selesai']);
	// 	$tanggal = $this->formatter->getDateMonthFormatUser($datax['tgl_mulai']).' sampai '.$this->formatter->getDateMonthFormatUser($datax['tgl_selesai']);
	// }
	// $jam = $this->formatter->timeFormatUser($datax['jam_mulai']).' WIB sampai '.$this->formatter->timeFormatUser($datax['jam_selesai']).' WIB';
    $tujuan=($datax['plant']=='plant')?$datax['nama_plant_tujuan']:$datax['lokasi_tujuan'];
    $kendaraan=($datax['kendaraan']=='KPD0001') ? $datax['nama_kendaraan_j'].' ('.$this->otherfunctions->getKendaraanUmum($datax['nama_kendaraan']).')' : $datax['nama_kendaraan_j'];
    $nama_penginapan = (empty($datax['nama_penginapan'])?null:$this->otherfunctions->getPenginapan($datax['nama_penginapan']));
	$valKendaraan='';
	if($datax['validasi_ac'] == 1 || $datax['validasi_ac'] == 0){
		$valKendaraan.=($datax['val_kendaraan']=='KPD0001') ? $datax['val_nama_kendaraan_j'].' ('.$this->otherfunctions->getKendaraanUmum($datax['val_kendaraan_umum']).')' : $datax['val_nama_kendaraan_j'];
	}
	$data_akun='';
	$nAkun=0;
	// $dataKodeAkun=$this->model_karyawan->getKodeAkunNoSK($noPerDin);
	if(count($dataKodeAkun) > 0){
		foreach ($dataKodeAkun as $aa) {
			$nAkun=$nAkun+$aa->nominal;
		}
		$data_akun.=$this->otherfunctions->getKodeAKunViewPrint($dataKodeAkun);
	}
	$nama_penginapan = (empty($datax['nama_penginapan'])?null:$this->otherfunctions->getPenginapan($datax['nama_penginapan']));
	$val_nama_penginapan = (empty($datax['val_penginapan'])?null:$this->otherfunctions->getPenginapan($datax['val_penginapan']));
?>
	<div class="box-id">
		<table width="100%">
			<tr>
				<th class="center"><img class="photo" src="<?= $this->otherfunctions->companyClientProfile()['logo'];?>" alt="Logo Perusahaan"></th>
			</tr>
			<tr>
				<th class="center border-buttom"><b class="nama_pt">SURAT PERJALANAN DINAS<br>
				<?=$this->otherfunctions->companyClientProfile()['name_office'];?></b><br>
				NO. : <?=$datax['no_sk'];?>
				</th>
			</tr>
			<tr>
				<td class="font">Dengan ini karyawan ditugaskan untuk menjalankan perjalanan dinas pada :</td>
			</tr>
		</table>
		<div class="row">
			<div class="col-md-1"></div>
			<div class="col-md-8">
				<br>
				<table width="100%">
					<tr>
						<th class="font9" style="width: 15%;">Tanggal</th>
						<td style="width: 5%;"></td>
						<td class="font9" style="width: 40%;"><?=$this->formatter->getDateTimeMonthFormatUser($datax['tgl_berangkat']).' sampai '.$this->formatter->getDateTimeMonthFormatUser($datax['tgl_pulang'])?></td>
					</tr>
					<tr>
						<th class="font9" style="width: 15%;">Plant Asal</th>
						<td style="width: 5%;"></td>
						<td class="font9" style="width: 40%;"><?=$datax['nama_plant_asal']?></td>
					</tr>
					<tr>
						<th class="font9" style="width: 15%;">Plant Tujuan</th>
						<td style="width: 5%;"></td>
						<td class="font9" style="width: 40%;"><?=$tujuan?></td>
					</tr>
					<tr>
						<th class="font9" style="width: 15%;">Kendaraan</th>
						<td style="width: 5%;"></td>
						<td class="font9" style="width: 40%;"><?=$kendaraan?></td>
					</tr>
					<tr>
						<th class="font9" style="width: 15%;">Menginap</th>
						<td style="width: 5%;"></td>
						<td class="font9" style="width: 40%;"><?=$nama_penginapan?></td>
					</tr>
					<tr>
						<th class="font9" style="width: 15%;">Tugas</th>
						<td style="width: 5%;"></td>
						<td class="font9" style="width: 40%;"><?=$datax['tugas']?></td>
					</tr>
				</table>
			</div>
		</div><br>
		<table width="100%">
            <tr>
                <td class="font">Karyawan yang ikut perjalanan dinas :</td>
            </tr>
		</table>
		<div class="col-md-12">
			<table width="100%" border="1" cellpadding="10">
				<tr>
					<th rowspan="2" class="font8" style="width: 5%;padding: 3px 3px 3px 3px;">No</th>
					<th rowspan="2" class="font8" style="width: 30%;padding: 3px 3px 3px 3px;">Nama</th>
					<th rowspan="2" class="font8" style="width: 5%;padding: 3px 3px 3px 3px;">Grade</th>
					<?php 
						if($datax['plant'] == 'plant'){
							echo '<th colspan="4" class="font8 text-center" style="width: 15%;padding: 3px 3px 3px 3px;">Tunjangan Makan</th>';
						}else{
							echo '<th colspan="3" class="font8 text-center" style="width: 15%;padding: 3px 3px 3px 3px;">Tunjangan Makan</th>';
						}
						if(empty($datax['val_tunjangan']) && empty($datax['val_besar_tunjangan'])){
							$tunJa=$this->otherfunctions->getDataTunjanganPerdin($datax['tunjangan'],$datax['besar_tunjangan']);
						}else{
							$tunJa=$this->otherfunctions->getDataTunjanganPerdin($datax['val_tunjangan'],$datax['val_besar_tunjangan']);
						}
						// $tunJa=$this->otherfunctions->getDataTunjanganPerdin($datax['val_tunjangan'],$datax['val_besar_tunjangan']);
						if(!empty($tunJa)){
							foreach ($tunJa as $keys => $val) {
								if($keys == 'UM'){
									echo '<th rowspan="2" class="font8" style="width: 10%;padding: 3px 3px 3px 3px;">JUMLAH UM<br>Perjalanan</th>';
								}elseif($keys == 'KAPD0002'){
									echo '<th rowspan="2" class="font8" style="width: 10%;padding: 3px 3px 3px 3px;">Insentif Bantuan<br>Plant/Non Plant</th>';
								}elseif($keys == 'NONPLANT'){
									echo '<th rowspan="2" class="font8" style="width: 10%;padding: 3px 3px 3px 3px;">UM Non Plant</th>';
								}else{
									echo '<th rowspan="2" class="font8" style="width: 10%;padding: 3px 3px 3px 3px;">'.$this->model_master->getKategoriDinasKode($keys)['nama'].'</th>';
								}
							}
						}
					?>
					<th rowspan="2" class="font8" style="width: 15%;padding: 3px 3px 3px 3px;">TOTAL</th>
				</tr>
				<tr class="bg-green">
					<th class="font8" style="width: 5%;padding: 3px 3px 3px 3px;">Pagi</th>
					<th class="font8" style="width: 5%;padding: 3px 3px 3px 3px;">Siang</th>
					<th class="font8" style="width: 5%;padding: 3px 3px 3px 3px;">Malam</th>
					<?php if($datax['plant'] == 'plant'){
						echo '<th class="font8" style="width: 5%;padding: 3px 3px 3px 3px;">Tambahan</th>';
					} ?>
				</tr>
			<?php $no = 1;
				$nilai_akhir = 0;
				foreach ($perdin as $d) {
					if($d->driver != '1'){
						$gradeKar=$this->model_karyawan->getEmpID($d->id_karyawan)['grade'];
						$namaGrade=$this->model_master->getGradeKode($gradeKar)['nama'];
						$tunjangan_uang_makan = (empty($d->val_tunjangan_um) || $d->val_tunjangan_um == null)?$d->tunjangan_um:$d->val_tunjangan_um;
						$tun_um=$this->otherfunctions->getTunjanganUMPerdin($tunjangan_uang_makan);
						echo '<tr>
							<td class="font8" style="width: 5%;padding: 3px 3px 3px 3px;">'.$no.'</td>
							<td class="font8" style="width: 30%;padding: 3px 3px 3px 3px;">'.$d->nama_karyawan.'</td>
							<td class="font8" style="width: 5%;padding: 3px 3px 3px 3px;">'.$namaGrade.'</td>';
						if(!empty($tun_um)){
							if($d->plant == 'plant'){
							echo '<td class="font8" style="width: 5%;padding: 3px 3px 3px 3px;">'.$this->formatter->getFormatMoneyUserReq($tun_um['pagi']).'</td>
								<td class="font8" style="width: 5%;padding: 3px 3px 3px 3px;">'.$this->formatter->getFormatMoneyUserReq($tun_um['siang']).'</td>
								<td class="font8" style="width: 5%;padding: 3px 3px 3px 3px;">'.$this->formatter->getFormatMoneyUserReq($tun_um['malam']).'</td>
								<td class="font8" style="width: 5%;padding: 3px 3px 3px 3px;">'.$this->formatter->getFormatMoneyUserReq($tun_um['lembur']).'</td>';
								$nominal_pd = $tun_um['pagi']+$tun_um['siang']+$tun_um['malam']+$tun_um['lembur'];
							}else{
								echo '<td class="font8" style="width: 5%;padding: 3px 3px 3px 3px;">'.$this->formatter->getFormatMoneyUserReq($tun_um['pagi']).'</td>
									<td class="font8" style="width: 5%;padding: 3px 3px 3px 3px;">'.$this->formatter->getFormatMoneyUserReq($tun_um['siang']).'</td>
									<td class="font8" style="width: 5%;padding: 3px 3px 3px 3px;">'.$this->formatter->getFormatMoneyUserReq($tun_um['malam']).'</td>';
									$nominal_pd = $tun_um['pagi']+$tun_um['siang']+$tun_um['malam'];
							}
						}
						if(empty($d->val_tunjangan) && empty($d->val_besar_tunjangan)){
							$tunJax=$this->otherfunctions->getDataTunjanganPerdin($d->tunjangan,$d->besar_tunjangan);
						}else{
							$tunJax=$this->otherfunctions->getDataTunjanganPerdin($d->val_tunjangan,$d->val_besar_tunjangan);
						}
						$na=0;
						if(!empty($tunJax)){
							foreach ($tunJax as $key => $valx) {
								echo '<td class="font8" style="width: 10%;padding: 3px 3px 3px 3px;">'.$this->formatter->getFormatMoneyUserReq($valx).'</td>'; 
								$na=$na+$valx;
							}
						}
						if(!empty($tun_um)){
							echo '<td class="font8" style="data_total: 15%;padding: 3px 3px 3px 3px;">'.$this->formatter->getFormatMoneyUserReq($na).'</td>';
						}
						echo '</tr>';
					}else{
						$gradeKar='GRD201908141604';
						$namaGrade=$this->model_master->getGradeKode($gradeKar)['nama'];
						$tunjangan_uang_makan = (empty($d->val_tunjangan_um) || $d->val_tunjangan_um == null)?$d->tunjangan_um:$d->val_tunjangan_um;
						$tun_um=$this->otherfunctions->getTunjanganUMPerdin($tunjangan_uang_makan);
						echo '<tr>
							<td class="font8" style="width: 5%;padding: 3px 3px 3px 3px;">'.$no.'</td>
							<td class="font8" style="width: 30%;padding: 3px 3px 3px 3px;">'.$d->id_karyawan.' - <b><i>Driver</i></b></td>
							<td class="font8" style="width: 5%;padding: 3px 3px 3px 3px;">'.$namaGrade.'</td>';
						if(!empty($tun_um)){
							if($d->plant == 'plant'){
							echo '<td class="font8" style="width: 5%;padding: 3px 3px 3px 3px;">'.$this->formatter->getFormatMoneyUserReq($tun_um['pagi']).'</td>
								<td class="font8" style="width: 5%;padding: 3px 3px 3px 3px;">'.$this->formatter->getFormatMoneyUserReq($tun_um['siang']).'</td>
								<td class="font8" style="width: 5%;padding: 3px 3px 3px 3px;">'.$this->formatter->getFormatMoneyUserReq($tun_um['malam']).'</td>
								<td class="font8" style="width: 5%;padding: 3px 3px 3px 3px;">'.$this->formatter->getFormatMoneyUserReq($tun_um['lembur']).'</td>';
								$nominal_pd = $tun_um['pagi']+$tun_um['siang']+$tun_um['malam']+$tun_um['lembur'];
							}else{
								echo '<td class="font8" style="width: 5%;padding: 3px 3px 3px 3px;">'.$this->formatter->getFormatMoneyUserReq($tun_um['pagi']).'</td>
									<td class="font8" style="width: 5%;padding: 3px 3px 3px 3px;">'.$this->formatter->getFormatMoneyUserReq($tun_um['siang']).'</td>
									<td class="font8" style="width: 5%;padding: 3px 3px 3px 3px;">'.$this->formatter->getFormatMoneyUserReq($tun_um['malam']).'</td>';
									$nominal_pd = $tun_um['pagi']+$tun_um['siang']+$tun_um['malam'];
							}
						}
						if(empty($d->val_tunjangan) && empty($d->val_besar_tunjangan)){
							$tunJax=$this->otherfunctions->getDataTunjanganPerdin($d->tunjangan,$d->besar_tunjangan);
						}else{
							$tunJax=$this->otherfunctions->getDataTunjanganPerdin($d->val_tunjangan,$d->val_besar_tunjangan);
						}
						$na=0;
						if(!empty($tunJax)){
							foreach ($tunJax as $key => $valx) {
								echo '<td class="font8" style="width: 10%;padding: 3px 3px 3px 3px;">'.$this->formatter->getFormatMoneyUserReq($valx).'</td>'; 
								$na=$na+$valx;
							}
						}
						if(!empty($tun_um)){
							echo '<td class="font8" style="data_total: 15%;padding: 3px 3px 3px 3px;">'.$this->formatter->getFormatMoneyUserReq($na).'</td>';
						}
						echo '</tr>';

					}
					$no++; 
					$nilai_akhir = $nilai_akhir+$na;
				}
				?>
			</table>
			<table  width="100%" border="1" cellpadding="10">
				<tr>
					<td class="font8" style="width: 80%;padding: 3px 3px 3px 3px;">TOTAL</td>
					<td class="font" style="width: 20%;padding: 3px 3px 3px 3px;"><b><?=$this->formatter->getFormatMoneyUserReq($nilai_akhir);?></b></td>
				</tr>
		</table>
		</div><br>
		<table width="100%">
            <tr>
                <td class="font">Data setelah divalidasi	:</td>
            </tr>
		</table>
		<div class="row">
			<div class="col-md-12">
				<div class="col-print-6">
					<table width="100%">
						<tr>
							<th class="font9" style="width: 55%;"></th>
							<td style="width: 2%;"></td>
							<td class="font9" style="width: 40%;"></td>
						</tr>
						<tr>
							<th class="font9" style="width: 55%;">Tanggal Berangkat</th>
							<td style="width: 2%;">:</td>
							<td class="font9" style="width: 40%;"><?=$this->formatter->getDateTimeMonthFormatUser($d->val_tgl_berangkat,1).' WIB'?></td>
						</tr>
						<tr>
							<th class="font9" style="width: 55%;">Tanggal Sampai</th>
							<td style="width: 2%;">:</td>
							<td class="font9" style="width: 40%;"><?=$this->formatter->getDateTimeMonthFormatUser($d->val_tgl_sampai,1).' WIB'?></td>
						</tr>
						<tr>
							<th class="font9" style="width: 55%;">Tanggal Pulang</th>
							<td style="width: 2%;">:</td>
							<td class="font9" style="width: 40%;"><?=$this->formatter->getDateTimeMonthFormatUser($d->val_tgl_pulang,1).' WIB'?></td>
						</tr>
						<tr>
							<th class="font9" style="width: 55%;">Kendaraan</th>
							<td style="width: 2%;">:</td>
							<td class="font9" style="width: 40%;"><?=$valKendaraan?></td>
						</tr>
						<?php 
							$jumlah_kendaraan = (empty($datax['val_jum_kendaraan']) || $datax['val_jum_kendaraan'] == 0)?'1':$datax['val_jum_kendaraan'];
						?>
						<tr>
							<th class="font9" style="width: 55%;">Nominal Transport</th>
							<td style="width: 2%;">:</td>
							<td class="font9" style="width: 40%;"><?=$this->formatter->getFormatMoneyUser($datax['val_nominal_bbm']/$jumlah_kendaraan)?></td>
						</tr>
						<tr>
							<th class="font9" style="width: 55%;">Jumlah Kendaraan</th>
							<td style="width: 2%;">:</td>
							<td class="font9" style="width: 40%;"><?=$jumlah_kendaraan?></td>
						</tr>
						<tr>
							<th class="font9" style="width: 55%;">Total Transport</th>
							<td style="width: 2%;">:</td>
							<td class="font9" style="width: 40%;"><?=$this->formatter->getFormatMoneyUser($datax['val_nominal_bbm'])?></td>
						</tr>
						<!-- <tr>
							<th class="font9" style="width: 55%;">Kode Akun Perjalanan dinas</th>
							<td style="width: 2%;">:</td>
							<td class="font9" style="width: 40%;"><?=$data_akun?></td>
						</tr> -->
					</table>
				</div>
				<div class="col-print-6">
					<table width="100%">
						<tr>
							<th class="font9" style="width: 55%;">Penginapan</th>
							<td style="width: 2%;">:</td>
							<td class="font9" style="width: 40%;"><?=$val_nama_penginapan?></td>
						</tr>
						<tr>
							<th class="font9" style="width: 55%;">Nominal Penginapan</th>
							<td style="width: 2%;">:</td>
							<td class="font9" style="width: 40%;"><?=$this->formatter->getFormatMoneyUser($datax['val_nominal_penginapan'])?></td>
						</tr>
						<tr>
							<th class="font9" style="width: 55%;">Jumlah Kamar</th>
							<td style="width: 2%;">:</td>
							<td class="font9" style="width: 40%;"><?=$datax['val_jumlah_kamar']?></td>
						</tr>
						<tr>
							<th class="font9" style="width: 55%;">Jumlah Hari</th>
							<td style="width: 2%;">:</td>
							<td class="font9" style="width: 40%;"><?=$datax['val_jumlah_hari']?> Hari</td>
						</tr>
						<tr>
							<th class="font9" style="width: 55%;">Total Biaya Penginapan</th>
							<td style="width: 2%;">:</td>
							<td class="font9" style="width: 40%;"><?=$this->formatter->getFormatMoneyUser($datax['val_total_penginapan'])?></td>
						</tr>
						<tr>
							<th class="font9" style="width: 55%;">Total Akomodasi</th>
							<td style="width: 2%;">:</td>
							<td class="font" style="width: 40%;"><b><?=$this->formatter->getFormatMoneyUser($datax['val_nominal_bbm']+$datax['val_total_penginapan'])?></b></td>
						</tr>
						<!-- <tr>
							<th class="font9" style="width: 55%;">Total Nilai Kode Akun</th>
							<td style="width: 2%;">:</td>
							<td class="font9" style="width: 40%;"><?=$this->formatter->getFormatMoneyUser($nAkun)?></td>
						</tr> -->
					</table>
				</div>
			</div>
		</div>
		<?php $totalAk = $nilai_akhir+$datax['val_nominal_bbm']+$datax['val_total_penginapan'];?>
		<!-- <table width="100%">
            <tr>
                <td class="font" style="float:right;"><b>Total Seluruh Biaya	:	<?=$this->formatter->getFormatMoneyUser($totalAk)?></b></td>
            </tr>
		</table> -->
		<?php $totalKodeAkun=0; 
		if(!empty($dataKodeAkun)){ ?>
			<br>
			<table width="100%">
				<tr>
					<td class="font">Rincian Kode Akun	:</td>
				</tr>
			</table>
			<div class="col-md-12">
				<table width="100%" border="1" cellpadding="10">
					<tr>
						<th class="font8" style="width: 5%;padding: 3px 3px 3px 3px;">No</th>
						<th class="font8" style="width: 30%;padding: 3px 3px 3px 3px;">Nama</th>
						<th class="font8" style="width: 10%;padding: 3px 3px 3px 3px;">Kode Akun</th>
						<th class="font8 text-center" style="width: 15%;padding: 3px 3px 3px 3px;">Nominal</th>
						<th class="font8" style="width: 15%;padding: 3px 3px 3px 3px;">Keterangan</th>
					</tr>
				<?php $nox = 1;$tKodeAkun=0;
					foreach ($dataKodeAkun as $dx) {
						echo '<tr>
							<td class="font8" style="padding: 3px 3px 3px 3px;">'.$nox.'</td>
							<td class="font8" style="padding: 3px 3px 3px 3px;">'.$dx->nama_akun.'</td>
							<td class="font8" style="padding: 3px 3px 3px 3px;">'.$dx->kode_akun.'</td>
							<td class="font8" style="padding: 3px 3px 3px 3px;">'.$this->formatter->getFormatMoneyUser($dx->nominal).'</td>
							<td class="font8" style="padding: 3px 3px 3px 3px;">'.$dx->keterangan.'</td>';
						echo '</tr>';
						$nox++; 
						$tKodeAkun = $tKodeAkun+$dx->nominal;
						$totalKodeAkun = $totalKodeAkun+$dx->nominal;
					}
					echo '<tr>
						<td class="font8" style="padding: 3px 3px 3px 3px;"></td>
						<td class="font" style="padding: 3px 3px 3px 3px;">TOTAL</td>
						<td class="font8" style="padding: 3px 3px 3px 3px;"></td>
						<td class="font" colspan="2" style="padding: 3px 3px 3px 3px;"><b>'.$this->formatter->getFormatMoneyUser($tKodeAkun).'</b></td>';
					echo '</tr>';
				?>
				</table>
			</div>
		<?php } ?>
		<?php $totalAk = $nilai_akhir+$datax['val_nominal_bbm']+$datax['val_total_penginapan']+$totalKodeAkun;?>
		<table width="100%">
            <tr>
                <td class="font" style="float:right;"><b>Total Seluruh Biaya	:	<?=$this->formatter->getFormatMoneyUser($totalAk)?></b></td>
            </tr>
		</table>
		<table width="100%" style="margin-top: 10px;">
			<tr>
				<td class="font">Demikian surat Perjalanan Dinas ini dibuat untuk digunakan sebagaimana mestinya, terima kasih.</td>
			</tr>
		</table>
		<table width="100%" style="margin-top: 20px;">
			<tr width="33%">
				<td style="text-align: center;;width:33%">Mengetahui</td>
				<td style="text-align: center;;width:33%">Menyetujui</td>
				<td style="text-align: center;;width:33%">Dibuat Oleh</td>
			</tr>
			<tr width="33%">
				<td style="padding-top: 80px;"></td>
				<td style="padding-top: 80px;"></td>
				<td style="padding-top: 80px;"></td>
			</tr>
			<tr width="33%">
				<td style="text-align: center;"><b><u><?=$datax['nama_mengetahui']?></u><b></td>
				<td style="text-align: center;"><b><u><?=$datax['nama_menyetujui']?></u><b></td>
				<td style="text-align: center;"><b><u><?=$datax['nama_buat']?></u><b></td>
			</tr>
			<tr>
				<td style="text-align: center;"><?=$datax['jbt_mengetahui']?></td>
				<td style="text-align: center;"><?=$datax['jbt_menyetujui']?></td>
				<td style="text-align: center;"><?//=$datax['jbt_buat']?></td>
			</tr>
		</table>
	</div>
<?php 
	// echo '<pre>';
	// print_r($koreksiOne);
	// print_r($koreksiAll);
if(!empty($koreksiAll)){
?>
	<div class="box-id">
		<table width="100%">
			<tr>
				<th class="center"><img class="photo" src="<?= $this->otherfunctions->companyClientProfile()['logo'];?>" alt="Logo Perusahaan"></th>
			</tr>
			<tr>
				<th class="center border-buttom"><b class="nama_pt">SURAT PERJALANAN DINAS<br>
				<?=$this->otherfunctions->companyClientProfile()['name_office'];?></b><br>
				NO. : <?=$datax['no_sk'];?>
				</th>
			</tr>
			<tr>
				<td class="font">Dengan ini karyawan ditugaskan untuk menjalankan perjalanan dinas pada :</td>
			</tr>
		</table>
		<div class="row">
			<div class="col-md-1"></div>
			<div class="col-md-8">
				<br>
				<table width="100%">
					<tr>
						<th class="font9" style="width: 15%;">Tanggal</th>
						<td style="width: 5%;"></td>
						<td class="font9" style="width: 40%;"><?=$this->formatter->getDateTimeMonthFormatUser($datax['tgl_berangkat']).' sampai '.$this->formatter->getDateTimeMonthFormatUser($datax['tgl_pulang'])?></td>
					</tr>
					<tr>
						<th class="font9" style="width: 15%;">Plant Asal</th>
						<td style="width: 5%;"></td>
						<td class="font9" style="width: 40%;"><?=$datax['nama_plant_asal']?></td>
					</tr>
					<tr>
						<th class="font9" style="width: 15%;">Plant Tujuan</th>
						<td style="width: 5%;"></td>
						<td class="font9" style="width: 40%;"><?=$tujuan?></td>
					</tr>
					<tr>
						<th class="font9" style="width: 15%;">Kendaraan</th>
						<td style="width: 5%;"></td>
						<td class="font9" style="width: 40%;"><?=$kendaraan?></td>
					</tr>
					<tr>
						<th class="font9" style="width: 15%;">Menginap</th>
						<td style="width: 5%;"></td>
						<td class="font9" style="width: 40%;"><?=$nama_penginapan?></td>
					</tr>
					<tr>
						<th class="font9" style="width: 15%;">Tugas</th>
						<td style="width: 5%;"></td>
						<td class="font9" style="width: 40%;"><?=$datax['tugas']?></td>
					</tr>
				</table>
			</div>
		</div><br>
		<table width="100%">
			<tr>
				<td class="font">Karyawan yang ikut perjalanan dinas :</td>
			</tr>
		</table>
		<div class="col-md-12">
			<table width="100%" border="1" cellpadding="10">
				<tr>
					<th rowspan="2" class="font8" style="width: 5%;padding: 3px 3px 3px 3px;">No</th>
					<th rowspan="2" class="font8" style="width: 30%;padding: 3px 3px 3px 3px;">Nama</th>
					<th rowspan="2" class="font8" style="width: 5%;padding: 3px 3px 3px 3px;">Grade</th>
					<?php 
						if($datax['plant'] == 'plant'){
							echo '<th colspan="4" class="font8 text-center" style="width: 15%;padding: 3px 3px 3px 3px;">Tunjangan Makan</th>';
						}else{
							echo '<th colspan="3" class="font8 text-center" style="width: 15%;padding: 3px 3px 3px 3px;">Tunjangan Makan</th>';
						}
						if(empty($datax['val_tunjangan']) && empty($datax['val_besar_tunjangan'])){
							$tunJax=$this->otherfunctions->getDataTunjanganPerdin($datax['tunjangan'],$datax['besar_tunjangan']);
						}else{
							$tunJax=$this->otherfunctions->getDataTunjanganPerdin($datax['val_tunjangan'],$datax['val_besar_tunjangan']);
						}
						// $tunJa=$this->otherfunctions->getDataTunjanganPerdin($datax['val_tunjangan'],$datax['val_besar_tunjangan']);
						if(!empty($tunJax)){
							foreach ($tunJax as $keys => $val) {
								if($keys == 'UM'){
									echo '<th rowspan="2" class="font8" style="width: 10%;padding: 3px 3px 3px 3px;">JUMLAH UM<br>Perjalanan</th>';
								}elseif($keys == 'KAPD0002'){
									echo '<th rowspan="2" class="font8" style="width: 10%;padding: 3px 3px 3px 3px;">Insentif Bantuan<br>Plant/Non Plant</th>';
								}elseif($keys == 'NONPLANT'){
									echo '<th rowspan="2" class="font8" style="width: 10%;padding: 3px 3px 3px 3px;">UM Non Plant</th>';
								}else{
									echo '<th rowspan="2" class="font8" style="width: 10%;padding: 3px 3px 3px 3px;">'.$this->model_master->getKategoriDinasKode($keys)['nama'].'</th>';
								}
							}
						}
					?>
					<th rowspan="2" class="font8" style="width: 15%;padding: 3px 3px 3px 3px;">TOTAL</th>
				</tr>
				<tr class="bg-green">
					<th class="font8" style="width: 5%;padding: 3px 3px 3px 3px;">Pagi</th>
					<th class="font8" style="width: 5%;padding: 3px 3px 3px 3px;">Siang</th>
					<th class="font8" style="width: 5%;padding: 3px 3px 3px 3px;">Malam</th>
					<?php if($datax['plant'] == 'plant'){
						echo '<th class="font8" style="width: 5%;padding: 3px 3px 3px 3px;">Tambahan</th>';
					} ?>
				</tr>
				<?php $no = 1;
					$nilai_akhirx = 0;
					foreach ($koreksiAll as $w) {
						if($w->driver != '1'){
							$gradeKar=$this->model_karyawan->getEmpID($w->id_karyawan)['grade'];
							$namaGrade=$this->model_master->getGradeKode($gradeKar)['nama'];
							$tunjangan_uang_makan = (empty($w->val_tunjangan_um) || $w->val_tunjangan_um == null)?$w->tunjangan_um:$w->val_tunjangan_um;
							$tun_umx=$this->otherfunctions->getTunjanganUMPerdin($tunjangan_uang_makan);
							echo '<tr>
								<td class="font8" style="width: 5%;padding: 3px 3px 3px 3px;">'.$no.'</td>
								<td class="font8" style="width: 30%;padding: 3px 3px 3px 3px;">'.$w->nama_karyawan.'</td>
								<td class="font8" style="width: 5%;padding: 3px 3px 3px 3px;">'.$namaGrade.'</td>';
							$nominal_pd = 0;
							if(!empty($tun_umx)){
								if($w->plant == 'plant'){
								echo '<td class="font8" style="width: 5%;padding: 3px 3px 3px 3px;">'.$this->formatter->getFormatMoneyUserReq($tun_umx['pagi']).'</td>
									<td class="font8" style="width: 5%;padding: 3px 3px 3px 3px;">'.$this->formatter->getFormatMoneyUserReq($tun_umx['siang']).'</td>
									<td class="font8" style="width: 5%;padding: 3px 3px 3px 3px;">'.$this->formatter->getFormatMoneyUserReq($tun_umx['malam']).'</td>
									<td class="font8" style="width: 5%;padding: 3px 3px 3px 3px;">'.$this->formatter->getFormatMoneyUserReq($tun_umx['lembur']).'</td>';
									$nominal_pd = $tun_umx['pagi']+$tun_umx['siang']+$tun_umx['malam']+$tun_umx['lembur'];
								}else{
									echo '<td class="font8" style="width: 5%;padding: 3px 3px 3px 3px;">'.$this->formatter->getFormatMoneyUserReq($tun_umx['pagi']).'</td>
										<td class="font8" style="width: 5%;padding: 3px 3px 3px 3px;">'.$this->formatter->getFormatMoneyUserReq($tun_umx['siang']).'</td>
										<td class="font8" style="width: 5%;padding: 3px 3px 3px 3px;">'.$this->formatter->getFormatMoneyUserReq($tun_umx['malam']).'</td>';
										$nominal_pd = $tun_umx['pagi']+$tun_umx['siang']+$tun_umx['malam'];
								}
							}
							if(empty($w->val_tunjangan) && empty($w->val_besar_tunjangan)){
								$tunJax=$this->otherfunctions->getDataTunjanganPerdin($w->tunjangan,$w->besar_tunjangan);
							}else{
								$tunJax=$this->otherfunctions->getDataTunjanganPerdin($w->val_tunjangan,$w->val_besar_tunjangan);
							}
							// echo '<pre>';
							// print_r($nominal_pd);
							// print_r($tunJax);
							$nax=0;
							if(!empty($tunJax)){
								foreach ($tunJax as $key => $valx) {
									$valx = (!empty($valx)) ? $valx : 0;
									if($key == 'UM'){
										echo '<td class="font8" style="width: 10%;padding: 3px 3px 3px 3px;">'.$this->formatter->getFormatMoneyUserReq($nominal_pd).'</td>'; 
										$nax=$nax+$nominal_pd;
									}else{
										echo '<td class="font8" style="width: 10%;padding: 3px 3px 3px 3px;">'.$this->formatter->getFormatMoneyUserReq($valx).'</td>'; 
										$nax=$nax+$valx;
									}
								}
							}
							if(!empty($tun_umx)){
								echo '<td class="font8" style="data_total: 15%;padding: 3px 3px 3px 3px;">'.$this->formatter->getFormatMoneyUserReq($nax).'</td>';
							}
							echo '</tr>';
						}else{
							$gradeKar='GRD201908141604';
							$namaGrade=$this->model_master->getGradeKode($gradeKar)['nama'];
							$tunjangan_uang_makan = (empty($w->val_tunjangan_um) || $w->val_tunjangan_um == null)?$w->tunjangan_um:$w->val_tunjangan_um;
							$tun_umx=$this->otherfunctions->getTunjanganUMPerdin($tunjangan_uang_makan);
							echo '<tr>
								<td class="font8" style="width: 5%;padding: 3px 3px 3px 3px;">'.$no.'</td>
								<td class="font8" style="width: 30%;padding: 3px 3px 3px 3px;">'.$w->id_karyawan.' - <b><i>Driver</i></b></td>
								<td class="font8" style="width: 5%;padding: 3px 3px 3px 3px;">'.$namaGrade.'</td>';
							if(!empty($tun_umx)){
								if($w->plant == 'plant'){
								echo '<td class="font8" style="width: 5%;padding: 3px 3px 3px 3px;">'.$this->formatter->getFormatMoneyUserReq($tun_umx['pagi']).'</td>
									<td class="font8" style="width: 5%;padding: 3px 3px 3px 3px;">'.$this->formatter->getFormatMoneyUserReq($tun_umx['siang']).'</td>
									<td class="font8" style="width: 5%;padding: 3px 3px 3px 3px;">'.$this->formatter->getFormatMoneyUserReq($tun_umx['malam']).'</td>
									<td class="font8" style="width: 5%;padding: 3px 3px 3px 3px;">'.$this->formatter->getFormatMoneyUserReq($tun_umx['lembur']).'</td>';
									$nominal_pd = $tun_umx['pagi']+$tun_umx['siang']+$tun_umx['malam']+$tun_umx['lembur'];
								}else{
									echo '<td class="font8" style="width: 5%;padding: 3px 3px 3px 3px;">'.$this->formatter->getFormatMoneyUserReq($tun_umx['pagi']).'</td>
										<td class="font8" style="width: 5%;padding: 3px 3px 3px 3px;">'.$this->formatter->getFormatMoneyUserReq($tun_umx['siang']).'</td>
										<td class="font8" style="width: 5%;padding: 3px 3px 3px 3px;">'.$this->formatter->getFormatMoneyUserReq($tun_umx['malam']).'</td>';
										$nominal_pd = $tun_umx['pagi']+$tun_umx['siang']+$tun_umx['malam'];
								}
							}
							if(empty($w->val_tunjangan) && empty($w->val_besar_tunjangan)){
								$tunJax=$this->otherfunctions->getDataTunjanganPerdin($w->tunjangan,$w->besar_tunjangan);
							}else{
								$tunJax=$this->otherfunctions->getDataTunjanganPerdin($w->val_tunjangan,$w->val_besar_tunjangan);
							}
							// $na=0;
							if(!empty($tunJax)){
								foreach ($tunJax as $key => $valx) {
									if($key == 'UM'){
										echo '<td class="font8" style="width: 10%;padding: 3px 3px 3px 3px;">'.$this->formatter->getFormatMoneyUserReq($nominal_pd).'</td>'; 
										$nax=$nax+$nominal_pd;
									}else{
										echo '<td class="font8" style="width: 10%;padding: 3px 3px 3px 3px;">'.$this->formatter->getFormatMoneyUserReq($valx).'</td>'; 
										$nax=$nax+$valx;
									}
								}
							}
							if(!empty($tun_umx)){
								echo '<td class="font8" style="data_total: 15%;padding: 3px 3px 3px 3px;">'.$this->formatter->getFormatMoneyUserReq($nax).'</td>';
							}
							echo '</tr>';

						}
					$no++; 
					$nilai_akhirx = $nilai_akhirx+$nax;
				}
				?>
				</table>
			<table  width="100%" border="1" cellpadding="10">
				<tr>
					<td class="font8" style="width: 80%;padding: 3px 3px 3px 3px;">TOTAL</td>
					<td class="font" style="width: 20%;padding: 3px 3px 3px 3px;"><b><?=$this->formatter->getFormatMoneyUserReq($nilai_akhirx);?></b></td>
				</tr>
			</table>
		</div><br>
		<table width="100%">
			<tr>
				<td class="font">Data setelah divalidasi	:</td>
			</tr>
		</table>
		<div class="row">
			<div class="col-md-12">
				<div class="col-print-6">
					<table width="100%">
						<tr>
							<th class="font9" style="width: 55%;"></th>
							<td style="width: 2%;"></td>
							<td class="font9" style="width: 40%;"></td>
						</tr>
						<tr>
							<th class="font9" style="width: 55%;">Tanggal Berangkat</th>
							<td style="width: 2%;">:</td>
							<td class="font9" style="width: 40%;"><?=$this->formatter->getDateTimeMonthFormatUser($koreksiOne['val_tgl_berangkat'],1).' WIB'?></td>
						</tr>
						<tr>
							<th class="font9" style="width: 55%;">Tanggal Sampai</th>
							<td style="width: 2%;">:</td>
							<td class="font9" style="width: 40%;"><?=$this->formatter->getDateTimeMonthFormatUser($koreksiOne['val_tgl_sampai'],1).' WIB'?></td>
						</tr>
						<tr>
							<th class="font9" style="width: 55%;">Tanggal Pulang</th>
							<td style="width: 2%;">:</td>
							<td class="font9" style="width: 40%;"><?=$this->formatter->getDateTimeMonthFormatUser($koreksiOne['val_tgl_pulang'],1).' WIB'?></td>
						</tr>
						<tr>
							<th class="font9" style="width: 55%;">Kendaraan</th>
							<td style="width: 2%;">:</td>
							<td class="font9" style="width: 40%;"><?=$valKendaraan?></td>
						</tr>
						<?php 
							$jumlah_kendaraan = (empty($koreksiOne['val_jum_kendaraan']) || $koreksiOne['val_jum_kendaraan'] == 0)?'1':$koreksiOne['val_jum_kendaraan'];
						?>
						<tr>
							<th class="font9" style="width: 55%;">Nominal Transport</th>
							<td style="width: 2%;">:</td>
							<td class="font9" style="width: 40%;"><?=$this->formatter->getFormatMoneyUser($koreksiOne['val_nominal_bbm']/$jumlah_kendaraan)?></td>
						</tr>
						<tr>
							<th class="font9" style="width: 55%;">Jumlah Kendaraan</th>
							<td style="width: 2%;">:</td>
							<td class="font9" style="width: 40%;"><?=$jumlah_kendaraan?></td>
						</tr>
						<tr>
							<th class="font9" style="width: 55%;">Total Transport</th>
							<td style="width: 2%;">:</td>
							<td class="font9" style="width: 40%;"><?=$this->formatter->getFormatMoneyUser($koreksiOne['val_nominal_bbm'])?></td>
						</tr>
					</table>
				</div>
				<div class="col-print-6">
					<table width="100%">
						<tr>
							<th class="font9" style="width: 55%;">Penginapan</th>
							<td style="width: 2%;">:</td>
							<td class="font9" style="width: 40%;"><?=$val_nama_penginapan?></td>
						</tr>
						<tr>
							<th class="font9" style="width: 55%;">Nominal Penginapan</th>
							<td style="width: 2%;">:</td>
							<td class="font9" style="width: 40%;"><?=$this->formatter->getFormatMoneyUser($koreksiOne['val_nominal_penginapan'])?></td>
						</tr>
						<tr>
							<th class="font9" style="width: 55%;">Jumlah Kamar</th>
							<td style="width: 2%;">:</td>
							<td class="font9" style="width: 40%;"><?=$koreksiOne['val_jumlah_kamar']?></td>
						</tr>
						<tr>
							<th class="font9" style="width: 55%;">Jumlah Hari</th>
							<td style="width: 2%;">:</td>
							<td class="font9" style="width: 40%;"><?=$koreksiOne['val_jumlah_hari']?> Hari</td>
						</tr>
						<tr>
							<th class="font9" style="width: 55%;">Total Biaya Penginapan</th>
							<td style="width: 2%;">:</td>
							<td class="font9" style="width: 40%;"><?=$this->formatter->getFormatMoneyUser($koreksiOne['val_total_penginapan'])?></td>
						</tr>
						<tr>
							<th class="font9" style="width: 55%;">Total Akomodasi</th>
							<td style="width: 2%;">:</td>
							<td class="font" style="width: 40%;"><b><?=$this->formatter->getFormatMoneyUser($koreksiOne['val_nominal_bbm']+$koreksiOne['val_total_penginapan'])?></b></td>
						</tr>
					</table>
				</div>
			</div>
		</div>
		<?php $totalAk = $nilai_akhirx+$koreksiOne['val_nominal_bbm']+$koreksiOne['val_total_penginapan'];?>
		<?php $totalKodeAkun=0; if(!empty($dataKodeAkun)){ ?>
		<br>
		<table width="100%">
			<tr>
				<td class="font">Rincian Kode Akun	:</td>
			</tr>
		</table>
		<div class="col-md-12">
			<table width="100%" border="1" cellpadding="10">
				<tr>
					<th class="font8" style="width: 5%;padding: 3px 3px 3px 3px;">No</th>
					<th class="font8" style="width: 30%;padding: 3px 3px 3px 3px;">Nama</th>
					<th class="font8" style="width: 10%;padding: 3px 3px 3px 3px;">Kode Akun</th>
					<th class="font8 text-center" style="width: 15%;padding: 3px 3px 3px 3px;">Nominal</th>
					<th class="font8" style="width: 15%;padding: 3px 3px 3px 3px;">Keterangan</th>
				</tr>
			<?php $nox = 1;$tKodeAkun=0;
				foreach ($dataKodeAkun as $dx) {
					echo '<tr>
						<td class="font8" style="padding: 3px 3px 3px 3px;">'.$nox.'</td>
						<td class="font8" style="padding: 3px 3px 3px 3px;">'.$dx->nama_akun.'</td>
						<td class="font8" style="padding: 3px 3px 3px 3px;">'.$dx->kode_akun.'</td>
						<td class="font8" style="padding: 3px 3px 3px 3px;">'.$this->formatter->getFormatMoneyUser($dx->nominal).'</td>
						<td class="font8" style="padding: 3px 3px 3px 3px;">'.$dx->keterangan.'</td>';
					echo '</tr>';
					$nox++; 
					$tKodeAkun = $tKodeAkun+$dx->nominal;
					$totalKodeAkun = $totalKodeAkun+$dx->nominal;
				}
				echo '<tr>
					<td class="font8" style="padding: 3px 3px 3px 3px;"></td>
					<td class="font" style="padding: 3px 3px 3px 3px;">TOTAL</td>
					<td class="font8" style="padding: 3px 3px 3px 3px;"></td>
					<td class="font" colspan="2" style="padding: 3px 3px 3px 3px;"><b>'.$this->formatter->getFormatMoneyUser($tKodeAkun).'</b></td>';
				echo '</tr>';
			?>
			</table>
		</div>
		<?php } ?>
		<?php $totalAk = $nilai_akhirx+$koreksiOne['val_nominal_bbm']+$koreksiOne['val_total_penginapan']+$totalKodeAkun;?>
		<table width="100%">
			<tr>
				<td class="font" style="float:right;"><b>Total Seluruh Biaya	:	<?=$this->formatter->getFormatMoneyUser($totalAk)?></b></td>
			</tr>
		</table>
		<table width="100%" style="margin-top: 10px;">
			<tr>
				<td class="font">Demikian surat Perjalanan Dinas ini dibuat untuk digunakan sebagaimana mestinya, terima kasih.</td>
			</tr>
		</table>
		<table width="100%" style="margin-top: 20px;">
			<tr width="33%">
				<td style="text-align: center;;width:33%">Mengetahui</td>
				<td style="text-align: center;;width:33%">Menyetujui</td>
				<td style="text-align: center;;width:33%">Dikoreksi Oleh</td>
			</tr>
			<tr width="33%">
				<td style="padding-top: 80px;"></td>
				<td style="padding-top: 80px;"></td>
				<td style="padding-top: 80px;"></td>
			</tr>
			<tr width="33%">
				<td style="text-align: center;"><b><u><?=$koreksiOne['nama_mengetahui']?></u><b></td>
				<td style="text-align: center;"><b><u><?=$koreksiOne['nama_menyetujui']?></u><b></td>
				<td style="text-align: center;"><b><u><?=$koreksiOne['nama_buat']?></u><b></td>
			</tr>
			<tr>
				<td style="text-align: center;"><?=$koreksiOne['jbt_mengetahui']?></td>
				<td style="text-align: center;"><?=$koreksiOne['jbt_menyetujui']?></td>
				<td style="text-align: center;"><?//=$datax['jbt_buat']?></td>
			</tr>
		</table>
	</div>
<?php } ?>