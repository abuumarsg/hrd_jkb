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
	if($datax['tgl_mulai'] == $datax['tgl_selesai']){
		$hari = $this->formatter->getNameOfDay($datax['tgl_mulai']);
		$tanggal = $this->formatter->getDateMonthFormatUser($datax['tgl_mulai']);
	}else{
		$hari = $this->formatter->getNameOfDay($datax['tgl_mulai']).' sampai '.$this->formatter->getNameOfDay($datax['tgl_selesai']);
		$tanggal = $this->formatter->getDateMonthFormatUser($datax['tgl_mulai']).' sampai '.$this->formatter->getDateMonthFormatUser($datax['tgl_selesai']);
	}
	$jam = $this->formatter->timeFormatUser($datax['jam_mulai']).' WIB sampai '.$this->formatter->timeFormatUser($datax['jam_selesai']).' WIB';
?>
	<div class="box-id">
		<table width="100%">
			<tr>
				<th class="center"><img class="photo" src="<?= $this->otherfunctions->companyClientProfile()['logo'];?>" alt="Logo Perusahaan"></th>
			</tr>
			<tr>
				<th class="center border-buttom"><b class="nama_pt">SURAT PERINTAH LEMBUR<br>
				<?=$this->otherfunctions->companyClientProfile()['name_office'];?></b><br>
				NO. SPL : <?=$datax['no_lembur'];?>
				</th>
			</tr>
			<tr>
				<td class="font">Kepada karyawan yang bersangkutan dibawah ini mendapatkan perintah lembur :</td>
			</tr>
		</table>
		<br>
		<div class="col-md-1"></div>
		<div class="col-md-11">
			<table width="100%" border="1" cellpadding="10">
				<tr>
					<th class="font8" style="width: 5%;padding: 3px 3px 3px 3px;">No</th>
					<th class="font8" style="width: 30%;padding: 3px 3px 3px 3px;">Nama</th>
					<th class="font8" style="width: 25%;padding: 3px 3px 3px 3px;">Jabatan</th>
					<th class="font8" style="width: 25%;padding: 3px 3px 3px 3px;">Lokasi Kerja</th>
					<th class="font8" style="width: 15%;padding: 3px 3px 3px 3px;">Jam Presensi</th>
				</tr>
			<?php $no = 1;
				foreach ($data_lembur as $d) {
				echo '<tr>
					<td class="font8" style="width: 5%;padding: 3px 3px 3px 3px;">'.$no.'</td>
					<td class="font8" style="width: 30%;padding: 3px 3px 3px 3px;">'.$d->nama_karyawan.'</td>
					<td class="font8" style="width: 25%;padding: 3px 3px 3px 3px;">'.$d->nama_jabatan.'</td>
					<td class="font8" style="width: 25%;padding: 3px 3px 3px 3px;">'.$d->nama_loker.'</td>
					<td class="font8" style="width: 15%;padding: 3px 3px 3px 3px;">'.$d->jam_mulai_pre.' - '.$d->jam_selesai_pre.'</td>
				</tr>';
			$no++; } ?>
			</table>
		</div>
		<div class="row">
			<div class="col-md-1"></div>
			<div class="col-md-8">
				<br>
				<table width="100%">
					<tr>
						<th class="font9" style="width: 15%;"></th>
						<td style="width: 5%;"></td>
						<td class="font9" style="width: 40%;"></td>
					</tr>
					<tr>
						<th class="font9" style="width: 15%;">Hari</th>
						<td style="width: 5%;"></td>
						<td class="font9" style="width: 40%;"><?=$hari?></td>
					</tr>
					<tr>
						<th class="font9" style="width: 15%;">Jam</th>
						<td style="width: 5%;"></td>
						<td class="font9" style="width: 40%;"><?=$jam?></td>
					</tr>
					<tr>
						<th class="font9" style="width: 15%;">Tanggal</th>
						<td style="width: 5%;"></td>
						<td class="font9" style="width: 40%;"><?=$tanggal?></td>
					</tr>
					<tr>
						<th class="font9" style="width: 15%;">Kegiatan</th>
						<td style="width: 5%;"></td>
						<td class="font9" style="width: 40%;"><?=$datax['keterangan']?></td>
					</tr>
					<tr>
						<th class="font9" style="width: 15%;">Jenis Lembur</th>
						<td style="width: 5%;"></td>
						<td class="font9" style="width: 40%;"><?=$this->otherfunctions->getJenisLemburKey($datax['jenis_lembur'])?></td>
					</tr>
					<tr>
						<th class="font9" style="width: 15%;">Kode Customer</th>
						<td style="width: 5%;"></td>
						<td class="font9" style="width: 40%;"><?=$datax['kode_customer']?></td>
					</tr>
					<tr>
						<th class="font9" style="width: 15%;">Status</th>
						<td style="width: 5%;"></td>
						<td class="font9" style="width: 40%;"><?=$this->otherfunctions->getStatusIzinRekap($datax['validasi'])?></td>
					</tr>
				</table>
			</div>
		</div>
		<table width="100%" style="margin-top: 10px;">
			<tr>
				<td class="font">Demikian surat perintah lembur ini dibuat untuk digunakan sebagaimana mestinya, terima kasih.</td>
			</tr>
		</table>
		<table width="100%" style="margin-top: 20px;">
			<tr width="33%">
				<td style="text-align: center;;width:33%">Menyetujui</td>
				<td style="text-align: center;;width:33%">Diperiksa</td>
				<td style="text-align: center;;width:33%">Dibuat Oleh</td>
			</tr>
			<tr width="33%">
				<td style="padding-top: 80px;"></td>
				<td style="padding-top: 80px;"></td>
				<td style="padding-top: 80px;"></td>
			</tr>
			<tr width="33%">
				<td style="text-align: center;"><b><u><?=$datax['nama_ketahui']?></u><b></td>
				<td style="text-align: center;"><b><u><?=$datax['nama_periksa']?></u><b></td>
				<td style="text-align: center;"><b><u><?=$datax['nama_buat_trans']?></u><b></td>
			</tr>
			<tr>
				<td style="text-align: center;"><?=$datax['jbt_ketahui']?></td>
				<td style="text-align: center;"><?=$datax['jbt_periksa']?></td>
				<td style="text-align: center;"><?=$datax['jbt_buat']?></td>
			</tr>
		</table>
	</div>