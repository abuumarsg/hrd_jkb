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
            width: 483px;
            height: 718px;
            /* width: 320px; */
            /* height: 483px; */
            padding: 6px 6px 6px 6px;
            float:left;
            border: 1px solid white;
            background-image: linear-gradient(#8fecff, white, white, white);
			page-break-inside: avoid;
        }
        .nama_pt{
            font-size:12pt;
            color:#00129c;
            -ms-transform: scaleY(1.5); /* IE 9 */
            -webkit-transform: scaleY(1.5); /* Safari 3-8 */
            transform: scaleY(1.5);
            position:relative;
            font-family: 'Suez One', sans-serif;            
        }
        .photo{
            top: 2px;
            position: center;
            max-width:25px;
            /* max-height:113.38px; */
            max-height:25px;
            object-fit:cover;
        }
		.pot{
			color:red;
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
		.yellowColor {
			/* -webkit-print-color-adjust: exact; */
			background-color: #F7F701 !important;
			-webkit-print-color-adjust:exact !important;
			print-color-adjust:exact !important;
		}
		.grayColor {
			background-color: #C1C1C1 !important;
			-webkit-print-color-adjust:exact !important;
			print-color-adjust:exact !important;
		}
	}
</style>
<style type="text/css">
	th{
		padding: 5px;
		text-align: center;
     	white-space: pre;
	}
	td{
		padding: 5px;
      	white-space: pre;
	}
	.parent-div{
		border-style: solid;border-color: black;border-width: 2px;padding: 10px;height:20px;
	}
	.center{
		text-align: center;
	}
	.right{
		text-align: right;
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
<div class="box-ids">
	<table width="100%">
		<tr>
			<th class="center"><img class="photo" src="<?= $this->otherfunctions->companyClientProfile()['logo'];?>" alt="Logo Perusahaan"></th>
		</tr>
		<tr>
			<th class="center"><b class="nama_pt">REKAP ANGSURAN PIUTANG <?=$this->otherfunctions->companyClientProfile()['name_office'];?></b></th>
		</tr>
		<tr>
			<!-- <th class="center"><b class="nama_pt">REKAP ANGSURAN PIUTANG <?=$this->otherfunctions->companyClientProfile()['name_office'];?></b></th> -->
			<th class="center border-buttom"><b class="nama_pt">Bulan <?=$this->formatter->getNameOfMonth($bulan).' '.$tahun;?></b></th>
		</tr>
	</table>
	<table width="100%">
		<tr>
			<td class="center border-buttom" width="30%" style="text-align: left;"><?php echo $admin;?></td>
			<td class="center border-buttom" width="30%" style="text-align: right;">Tanggal : <?php echo $time;?></td>
		</tr>
	</table>
	<br>
		<div class="col-print-12">
			<table width="100%" class="parent-div" border="2">
				<tr class="grayColor">
					<th>NO.</th>
					<th>Nama</th>
					<th>Jabatan</th>
					<th>Bagian</th>
					<th>Angsuran Ke</th>
					<th>Nominal</th>
				</tr>
				<?php 
					$no = 1;
					$total = 0;
					// echo '<pre>';
					// print_r($angsuran);
					foreach ($angsuran as $key => $d) {
						$nama_jabatan = $this->otherfunctions->cutText($d['nama_jabatan'], 30, 2);
						$nama_bagian = $this->otherfunctions->cutText($d['nama_bagian'], 30, 2);
						$nominalHutangLain = ($d['nominalHutangLain'] > 0) ? '<br>'.$this->formatter->getFormatMoneyUser($d['nominalHutangLain']) : null;
						$nominalHutangLainPlus = ($d['nominalHutangLain'] > 0) ? $d['nominalHutangLain'] : 0;
						$HutangLain = ($d['nominalHutangLain'] > 0) ? '<br>'. $d['namaHutangLain'] : null;
						echo '<tr>
							<td>'.$no.'</td>
							<td>'.$d['nama'].'</td>
							<td>'.$nama_jabatan.'</td>
							<td>'.$nama_bagian.'</td>
							<td>'.$d['angsuran_ke'].$HutangLain.'</td>
							<td>'.$this->formatter->getFormatMoneyUser($d['angsuran']).$nominalHutangLain.'</td>
						</tr>';
						$nominal = $d['angsuran']+$nominalHutangLainPlus;
						$total += $nominal;
						$no++;
					}
					echo '<tr>
						<td class="center" colspan="4"><b>TOTAL</b></td>
						<td class="right yellowColor" colspan="2" style="font-size:14pt;"><b>'.$this->formatter->getFormatMoneyUser($total).'</b></td>
					</tr>';
				?>
			</table>
		</div>
</div>