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
		.col-print-1 {width:8%;  float:left;}
		.col-print-2 {width:16%; float:left;}
		.col-print-3 {width:24.7%; float:left;padding: 3px;}
		.col-print-4 {width:33%; float:left;}
		.col-print-5 {width:42%; float:left;}
		.col-print-6 {width:50%; float:left;}
		.col-print-7 {width:58%; float:left;}
		.col-print-8 {width:66%; float:left;}
		.col-print-9 {width:75%; float:left;}
		.col-print-10{width:83%; float:left;}
		.col-print-11{width:92%; float:left;}
		.col-print-12{width:100%; float:left;}
		.grayColor {
			background-color: #C1C1C1 !important;
			-webkit-print-color-adjust:exact !important;
			print-color-adjust:exact !important;
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
		.font-14{
			font-size:13pt;
		}
		.font-16{
			font-size:16pt;
		}
		.border-top{
			border-top-style: solid;
		}
		.border-bottom{
			border-bottom-style: solid;
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
    $alamat = ucwords(strtolower($data['alamat_asal_jalan'])).', Desa '.ucwords(strtolower($data['alamat_asal_desa'])).', Kec. '.ucwords(strtolower($data['alamat_asal_kecamatan'])).', Kab. '.ucwords(strtolower($data['alamat_asal_kabupaten']));
    if($data['jenis_gaji'] == 'matrix'){
        $gaji_pokok = $data['gapok'];
    }else{
        $gaji_pokok = $data['gaji_non_matrix'];
    }
    $masa_kerja = $this->formatter->getCountDateRange($data['tgl_berlaku_baru'], $data['berlaku_sampai_baru'])['bulan'];
    $uangKompensasi = ($masa_kerja/12)*$gaji_pokok;
?>
<div class="box-id">
	<div class="col-print-1">&nbsp;</div>
	<div class="col-print-10">
		<table width="100%" style="margin-top: 5px;">
			<tr>
				<td width="100%" style="text-align: center;"><b class="font-16 border-bottom">Perhitungan Uang Kompensasi Berakhirnya PKWT</b></td>
			</tr>
		</table>
		<br>
		<table width="100%" style="margin-top: 5px;">
			<tr>
				<td width="40%" class="font-14"><b class="font-16 border-bottom">Biodata tenaga kerja</b></td>
				<td width="2%" class="font-14"> : </td>
				<td style="text-align: left;" class="font-14"></td>
			</tr>
			<tr>
				<td width="40%" class="font-14">Nama</td>
				<td width="2%" class="font-14"> : </td>
				<td width="58%" style="text-align: left;" class="font-14"><?=ucwords(strtolower($data['nama_karyawan']))?></td>
			</tr>
			<tr>
				<td width="40%" class="font-14">Tempat & tanggal lahir</td>
				<td width="2%" class="font-14"> : </td>
				<td width="58%" style="text-align: left;" class="font-14"><?=ucwords(strtolower($data['tempat_lahir'])).', '.$this->formatter->getDateMonthFormatUser($data['tgl_lahir'])?></td>
			</tr>
			<tr>
				<td width="40%" class="font-14">Alamat</td>
				<td width="2%" class="font-14"> : </td>
				<td width="58%" style="text-align: left;" class="font-14"><?=$alamat?></td>
			</tr>
			<tr>
				<td width="40%" class="font-14">Tanggal masuk bekerja</td>
				<td width="2%" class="font-14"> : </td>
				<td width="58%" style="text-align: left;" class="font-14"><?=$this->formatter->getDateMonthFormatUser($data['tgl_berlaku_baru'])?></td>
			</tr>
			<tr>
				<td width="40%" class="font-14">Jabatan</td>
				<td width="2%" class="font-14"> : </td>
				<td width="58%" style="text-align: left;" class="font-14"><?=ucwords(strtolower($data['nama_jabatan']))?></td>
			</tr>
			<tr>
				<td width="40%" class="font-14">Plant</td>
				<td width="2%" class="font-14"> : </td>
				<td width="58%" style="text-align: left;" class="font-14"><?=ucwords(strtolower($data['nama_loker']))?></td>
			</tr>
			<tr>
				<td width="40%" class="font-14">Gaji pokok</td>
				<td width="2%" class="font-14"> : </td>
				<td width="58%" style="text-align: left;" class="font-14"><?=$this->formatter->getFormatMoneyUser($gaji_pokok)?></td>
			</tr>
			<tr>
				<td width="40%" class="font-14">Tanggal terakhir kerja</td>
				<td width="2%" class="font-14"> : </td>
				<td width="58%" style="text-align: left;" class="font-14"><?=$this->formatter->getDateMonthFormatUser($data['berlaku_sampai_baru'])?></td>
			</tr>
			<tr>
				<td width="40%" class="font-14">Masa kerja</td>
				<td width="2%" class="font-14"> : </td>
				<td width="58%" style="text-align: left;" class="font-14"><?=$masa_kerja?> Bulan</td>
			</tr>
			<tr>
				<td width="40%" class="font-14">Rekening</td>
				<td width="2%" class="font-14"> : </td>
				<td width="58%" style="text-align: left;" class="font-14"><?=$data['rekening']?></td>
			</tr>
			<tr>
				<td width="40%" class="font-14">Status</td>
				<td width="2%" class="font-14"> : </td>
				<td width="58%" style="text-align: left;" class="font-14"><?php echo $status; echo (($status == 'Diperpanjang' || $status == 'DIPERPANJANG') ? ' '.$lama_perjanjian_baru.' Bulan' : null)?></td>
			</tr>
		</table>
		<br>
		<table width="100%" style="margin-top: 5px;">
			<tr>
				<td class="font-14" colspan="2"><b class="font-16 border-bottom">Perhitungan :</b></td>
			</tr>
			<tr>
				<td class="font-14" colspan="2">Terhitung dari 02 Februari 2021 Sejak ditetapkannya Peraturan Pemerintah  No.35 Uang Kompensasi sesuai ketentuan pasal 16 ayat 1 :</td>
			</tr>
			<tr>
				<td class="font-14">= ( Masa Kerja : 12 ) x 1 ( Satu ) Bulan Upah</td>
			</tr>
			<tr>
				<td class="font-14">= (<?=$masa_kerja?> bulan : 12 bulan) x <?=$this->formatter->getFormatMoneyUser($gaji_pokok)?></td>
				<td class="font-14"><b>= <?=$this->formatter->getFormatMoneyUser($uangKompensasi)?></b></td>
			</tr>
			<tr>
				<td class="font-14" colspan="2">&nbsp;</td>
			</tr>
			<tr>
				<td class="font-14" colspan="2" style="text-align: center;"><b># <?=$this->formatter->kataTerbilang($uangKompensasi)?> Rupiah #</b></td>
			</tr>
		</table>
		<table width="93%" style="margin-top: 10px;">
			<tr>
				<td class="font pull-right">Karangjati, <?=$this->formatter->getDateMonthFormatUser(date('y-m-d'));?></td>
			</tr>
		</table>
		<table width="100%" style="margin-top: 20px;">
			<tr width="25%">
				<td style="text-align: center;;width:25%">Menyetujui 1</td>
				<td style="text-align: center;;width:25%">Menyetujui 2</td>
				<td style="text-align: center;;width:25%">Mengetahui</td>
				<td style="text-align: center;;width:25%">Di Buat Oleh</td>
			</tr>
			<tr width="25%">
				<td style="padding-top: 80px;"></td>
				<td style="padding-top: 80px;"></td>
				<td style="padding-top: 80px;"></td>
				<td style="padding-top: 80px;"></td>
			</tr>
			<tr width="25%">
				<td style="text-align: center;"><b><u><?=ucwords(strtolower($menyetujui_1['nama']))?></u><b></td>
				<td style="text-align: center;"><b><u><?=ucwords(strtolower($menyetujui_2['nama']))?></u><b></td>
				<td style="text-align: center;"><b><u><?=ucwords(strtolower($mengetahui['nama']))?></u><b></td>
				<td style="text-align: center;"><b><u><?=ucwords(strtolower($dibuat['nama']))?></u><b></td>
			</tr>
			<tr>
				<td style="text-align: center;"><?=ucwords(strtolower($menyetujui_1['nama_jabatan']))?></td>
				<td style="text-align: center;"><?=ucwords(strtolower($menyetujui_2['nama_jabatan']))?></td>
				<td style="text-align: center;"><?=ucwords(strtolower($mengetahui['nama_jabatan']))?></td>
				<td style="text-align: center;"><?=ucwords(strtolower($dibuat['nama_jabatan']))?></td>
			</tr>
		</table>
	</div>
</div>