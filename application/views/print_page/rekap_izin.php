<style type = "text/css">
    @import url("https://fonts.googleapis.com/css?family=Signika");
    @import url("https://fonts.googleapis.com/css?family=Suez+One "); 
    @import url("https://fonts.googleapis.com/css?family=Salsa");
    @import url("https://fonts.googleapis.com/css?family=Times+New+Roman");
    @media print,screen {
		@page {
			size: Legal portrait;
			margin: 10mm;
		}
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
            /* background-image: linear-gradient(#8fecff, white, white, white); */
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
            font-size:11pt;            
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
	$jam = '<b>'.$this->formatter->timeFormatUser($datax['jam_mulai']).' WIB </b>sampai<b> '.$this->formatter->timeFormatUser($datax['jam_selesai']).' WIB</b>';
	$lama_izin=$this->otherfunctions->hitungHari($datax['tgl_mulai'],$datax['tgl_selesai']);
    $lamaTerbilang=$this->formatter->kataTerbilang($lama_izin);
	$jenis_izin_cuti=$this->model_master->getMasterIzinJenis($datax['jenis'])['jenis'];
	$jenis = $this->otherfunctions->getIzinCuti($jenis_izin_cuti);
	$tanggal = '<b>'.$this->formatter->getDayDateFormatUserId($datax['tgl_mulai']).'</b> sampai <b>'.$this->formatter->getDayDateFormatUserId($datax['tgl_selesai']).'</b>';
?>
	<div class="box-id">
		<table width="100%">
			<tr>
				<th class="center"><img class="photo" src="<?= $this->otherfunctions->companyClientProfile()['logo'];?>" alt="Logo Perusahaan"></th>
			</tr>
			<tr>
				<th class="center border-buttom"><b class="nama_pt"><?=$this->otherfunctions->companyClientProfile()['name_office'];?><br>SURAT PERMOHONAN IZIN DAN CUTI KARYAWAN</b>
				</th>
			</tr>
			<tr>
				<td class="font">Yang bertanda tangan dibawah ini : </td>
			</tr>
		</table><br>
		<div class="row">
			<div class="col-md-12">
				<table width="100%" border="0" cellpadding="10">
					<tr>
						<th class="font9" style="width: 10%;padding: 3px 3px 3px 3px;"></th>
						<th class="font9" style="width: 20%;padding: 3px 3px 3px 3px;">NAMA</th>
						<th class="font9" style="width: 1%;padding: 3px 3px 3px 3px;">:</th>
						<td class="font9 border-buttom" style="width: 69%;padding: 3px 3px 3px 3px;"><?=$datax['nama_karyawan']?></td>
					</tr>
					<tr>
						<th class="font9" style="width: 10%;padding: 3px 3px 3px 3px;"></th>
						<th class="font9" style="width: 20%;padding: 3px 3px 3px 3px;">JABATAN</th>
						<th class="font9" style="width: 1%;padding: 3px 3px 3px 3px;">:</th>
						<td class="font9 border-buttom" style="width: 69%;padding: 3px 3px 3px 3px;"><?=$datax['nama_jabatan']?></td>
					</tr>
					<tr>
						<th class="font9" style="width: 10%;padding: 3px 3px 3px 3px;"></th>
						<th class="font9" style="width: 20%;padding: 3px 3px 3px 3px;">BAGIAN</th>
						<th class="font9" style="width: 1%;padding: 3px 3px 3px 3px;">:</th>
						<td class="font9 border-buttom" style="width: 69%;padding: 3px 3px 3px 3px;"><?=$datax['nama_bagian']?></td>
					</tr>
					<tr>
						<th class="font9" style="width: 10%;padding: 3px 3px 3px 3px;"></th>
						<th class="font9" style="width: 20%;padding: 3px 3px 3px 3px;">MOHON <?=$jenis?></th>
						<th class="font9" style="width: 1%;padding: 3px 3px 3px 3px;">:</th>
						<td class="font9 border-buttom" style="width: 69%;padding: 3px 3px 3px 3px;"><?=$lama_izin.' ('.$lamaTerbilang?>) Hari, terhitung mulai sejak  </td>
					</tr>
					<tr>
						<th class="font9" style="width: 10%;padding: 3px 3px 3px 3px;"></th>
						<th class="font9" style="width: 20%;padding: 3px 3px 3px 3px;"></th>
						<th class="font9" style="width: 1%;padding: 3px 3px 3px 3px;">:</th>
						<td class="font9 border-buttom" style="width: 69%;padding: 3px 3px 3px 3px;"><?=$tanggal?></td>
					</tr>
					<tr>
						<th class="font9" style="width: 10%;padding: 3px 3px 3px 3px;"></th>
						<th class="font9" style="width: 20%;padding: 3px 3px 3px 3px;"></th>
						<th class="font9" style="width: 1%;padding: 3px 3px 3px 3px;">:</th>
						<td class="font9 border-buttom" style="width: 69%;padding: 3px 3px 3px 3px;"><?=$jam?></td>
					</tr>
					<tr>
						<th class="font9" style="width: 10%;padding: 3px 3px 3px 3px;"></th>
						<th class="font9" style="width: 20%;padding: 3px 3px 3px 3px;">KEPERLUAN</th>
						<th class="font9" style="width: 1%;padding: 3px 3px 3px 3px;">:</th>
						<td class="font9 border-buttom" style="width: 69%;padding: 3px 3px 3px 3px;"><?=$datax['alasan']?></td>
					</tr>
					<tr>
						<th class="font9" style="width: 10%;padding: 3px 3px 3px 3px;"></th>
						<th class="font9" style="width: 20%;padding: 3px 3px 3px 3px;">Alamat Dijalankan</th>
						<th class="font9" style="width: 1%;padding: 3px 3px 3px 3px;">:</th>
						<td class="font9 border-buttom" style="width: 69%;padding: 3px 3px 3px 3px;"><?=$datax['keterangan']?></td>
					</tr>
				</table>
			</div>
		</div><br>
		<table width="98%" style="margin-top: 10px;">
			<tr>
				<td class="font">Kemudian atas terkabulnya permohonan ini saya mengucapkan terima kasih. </td>
			</tr>
			<tr>
				<td class="font pull-right">Karangjati, <?=$this->formatter->getDateMonthFormatUser($now);?></td>
			</tr>
		</table>
		<table width="100%" style="margin-top: 20px;">
			<tr width="25%">
				<td style="text-align: center;;width:25%">Mengetahui</td>
				<td style="text-align: center;;width:25%">Menyetujui 1</td>
				<td style="text-align: center;;width:25%">Menyetujui 2</td>
				<td style="text-align: center;;width:25%">Pemohon</td>
			</tr>
			<tr width="25%">
				<td style="padding-top: 80px;"></td>
				<td style="padding-top: 80px;"></td>
				<td style="padding-top: 80px;"></td>
				<td style="padding-top: 80px;"></td>
			</tr>
			<tr width="25%">
				<td style="text-align: center;"><b><u><?=$datax['nama_mengetahui']?></u><b></td>
				<td style="text-align: center;"><b><u><?=$datax['nama_menyetujui']?></u><b></td>
				<td style="text-align: center;"><b><u><?=$datax['nama_menyetujui_2']?></u><b></td>
				<td style="text-align: center;"><b><u><?=$datax['nama_karyawan']?></u><b></td>
			</tr>
			<tr>
				<td style="text-align: center;"><?=$datax['jbt_mengetahui']?></td>
				<td style="text-align: center;"><?=$datax['jbt_menyetujui']?></td>
				<td style="text-align: center;"><?=$datax['jbt_menyetujui_2']?></td>
				<td style="text-align: center;"><?=$datax['nama_jabatan']?></td>
			</tr>
		</table>
	</div>