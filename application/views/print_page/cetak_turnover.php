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
    @page {
        size: Legal;
        /* size: A4; */
        margin: 10mm;
    }
	p { font-family: Cambria,"Times New Roman",serif; }
	table { font-family: Cambria,"Times New Roman",serif; }
    @media print,screen {
        .box-id {
            page-break-after: always;
        }
        .font{
            font-size:8pt;
        }
        .bg-red{
            background-color:red;
        }
        .page-break {
			page-break-inside: avoid;
		}
		.pagebreak { 
			clear: both;
			page-break-before: always; 
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
        .font8{
            font-size:8pt;            
        }
        .font9{
            font-size:9pt;            
        }
		.font-12{
			font-size:12pt;
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
		.tdbreak {
			word-break: break-all
		}
		.width25 {
			width: 25%;
		}
    }
</style>
<?php
    // echo '<pre>';
    // print_r($data);
    // $alamat = $data['alamat_asal_jalan'].', Desa '.$data['alamat_asal_desa'].', KEC. '.$data['alamat_asal_kecamatan'].', KAB. '.$data['alamat_asal_kabupaten'];
    // if($data['jenis_gaji'] == 'matrix'){
    //     $gaji_pokok = $data['gapok'];
    // }else{
    //     $gaji_pokok = $data['gaji_non_matrix'];
    // }
    // $masa_kerja = $this->formatter->getCountDateRange($data['tgl_berlaku_baru'], $data['berlaku_sampai_baru'])['bulan'];
    // $uangKompensasi = ($masa_kerja/12)*$gaji_pokok;
?>
<div style="padding-top:30px;"></div>
<div class="col-print-12">
	<div class="col-print-1">&nbsp;</div>
	<div class="col-print-10">
        <table width="100%" style="margin-top: 5px;">
            <tr>
                <td width="100%" style="text-align: center;"><b class="font-16 border-bottom">CETAK TURNOVER KARYWAN</b></td>
            </tr>
            <tr>
                <td width="100%" style="text-align: center;">Bulan <?=$bulan?>, Tahun <?=$tahun?></td>
            </tr>
        </table>
		<br>
		<div class="box-id">
			<table width="100%" style="margin-top: 10px;margin-left: -50px;">
				<tr>
					<td class="font-16"><b>A.&nbsp;&nbsp;&nbsp;<u>Karyawan Masuk</u></b></td>
				</tr>
			</table>
			<div class="row">
				<div class="col-print-12">
					<table class="border" width="100%" style="margin-top: 5px;" border="1">
						<tr>
							<th class="font-12">No</th>
							<th class="font-12">Nama</th>
							<th class="font-12">Bagian</th>
							<th class="font-12">Lokasi Kerja</th>
							<th class="font-12">Tanggal Masuk</th>
						</tr>
						<?php
						if(!empty($dataIn)){
							$no = 1;
							foreach ($dataIn as $in) {
								echo '
								<tr>
									<td class="font-12">'.$no.'.</td>
									<td class="font-12">'.$in->nama.'</td>
									<td class="font-12 tdbreak width25">'.$in->nama_bagian.'</td>
									<td class="font-12 tdbreak">'.$in->nama_loker.'</td>
									<td class="font-12 tdbreak">'.$this->formatter->getDateMonthFormatUser($in->tgl_masuk).'</td>
								</tr>';
								$no++;
							}
						}
						?>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="col-print-12">
	<div class="col-print-1">&nbsp;</div>
	<div class="col-print-10">
		<div class="box-id">
			<table width="100%" style="margin-top: 10px;margin-left: -50px;">
				<tr>
					<td class="font-16"><b>B.&nbsp;&nbsp;&nbsp;<u>Karyawan Keluar</u></b></td>
				</tr>
			</table>
			<div class="row">
				<div class="col-print-12">
					<table class="border" width="100%" style="margin-top: 5px;" border="1">
						<tr>
							<th class="font-12">No</th>
							<th class="font-12">Nama</th>
							<th class="font-12">Bagian</th>
							<th class="font-12">Lokasi Kerja</th>
							<th class="font-12">Tanggal Keluar</th>
							<th class="font-12">Alasan</th>
						</tr>
						<?php
						if(!empty($dataOut)){
							$noQ = 1;
							foreach ($dataOut as $out) {
								$nama_bagian = $this->otherfunctions->cutText($out->nama_bagian, 30, 2);
								echo '
								<tr>
									<td class="font-12">'.$noQ.'.</td>
									<td class="font-12">'.$out->nama_karyawan.'</td>
									<td class="font-12">'.$nama_bagian.'</td>
									<td class="font-12">'.$out->nama_loker.'</td>
									<td class="font-12">'.$this->formatter->getDateMonthFormatUser($out->tgl_keluar).'</td>
									<td class="font-12">'.$out->keterangan.'</td>
								</tr>';
								$noQ++;
							}
						}
						?>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>