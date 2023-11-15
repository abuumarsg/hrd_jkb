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
            /* width: 470px;
            height: 610px; */
            width: 320px;
            height: 483px;
            padding: 6px 6px 6px 6px;
            float:left;
            border: 1px solid white;
            background-image: linear-gradient(#8fecff, white, white, white);
			page-break-inside: avoid;
        }
        .nama_pt{
            font-size:9pt;
            color:#00129c;
            -ms-transform: scaleY(1.5); /* IE 9 */
            -webkit-transform: scaleY(1.5); /* Safari 3-8 */
            transform: scaleY(1.5);
            position:relative;
            font-family: 'Suez One', sans-serif;            
        }
        .font{
            font-size:9pt;            
        }
        .photo{
            top: 9px;
            position: center;
            max-width:30px;
            /* max-height:113.38px; */
            max-height:30px;
            object-fit:cover;
        }
  		.footer {
			  page-break-after: always;
		}
  		.header {
			  page-break-before: always;
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
$no = 1;
foreach ($emp_gaji as $d) { ?>
		<div class="box-id">
			<table width="100%">
				<tr>
					<th class="center"><img class="photo" src="<?= $this->otherfunctions->companyClientProfile()['logo'];?>" alt="User profile picture"></th>
				</tr>
				<tr class="border_dot">
					<th class="center border-buttom"><b class="nama_pt">Slip Gaji Lembur <?=$this->otherfunctions->companyClientProfile()['name_office'];?> <br> <?php echo $d->nama_loker; ?></b></th>
				</tr>
				<tr>
					<td>Tgl : <?php echo $this->formatter->getDateMonthFormatUser($periode['tgl_mulai']); ?> - <?php echo $this->formatter->getDateMonthFormatUser($periode['tgl_selesai']); ?></td>
				</tr>
			</table>
			<table width="100%">
				<tr class="border_dot">
					<th class="font" style="width: 40%;">No</th>
					<td class="font" style="width: 10%;"></td>
					<td class="font" style="width: 50%;"><?php echo $no;?></td>
				</tr>
				<tr class="border_dot">
					<th class="font">Nama</th>
					<td></td>
					<td class="font"><?php echo $d->nama_karyawan;?></td>
				</tr>
				<tr class="border_dot">
					<th class="font">Jabatan</th>
					<td></td>
					<td class="font"><?php echo $d->nama_jabatan;?></td>
				</tr>
				<tr class="border_dot">
					<th class="font">Total Jam Lembur</th>
					<td></td>
					<td class="font"><?php echo $d->total_jam;?></td>
				</tr>
				<tr class="border_dot">
					<th class="font">Perhitungan</th>
					<td></td>
					<td class="font"><?php echo $d->ekuivalen;?></td>
				</tr>
				<!-- <tr class="border_dot">
					<th></th>
					<td></td>
					<td class="font"><?php //echo $this->formatter->getFormatMoneyUser($d->nominal_biasa);?></td>
				</tr> -->
				<!-- <tr class="border_dot">
					<th class="font">Jam Lembur Biasa</th>
					<td></td>
					<td class="font"><?php //echo $d->jam_biasa;?></td>
				</tr>
				<tr class="border_dot">
					<th></th>
					<td></td>
					<td class="font"><?php //echo $this->formatter->getFormatMoneyUser($d->nominal_biasa);?></td>
				</tr>
				<tr class="border_dot">
					<th class="font">Jam Lembur Libur</th>
					<td></td>
					<td class="font"><?php //echo $d->jam_libur;?></td>
				</tr>
				<tr class="border_dot">
					<th></th>
					<td></td>
					<td class="font"><?php //echo $this->formatter->getFormatMoneyUser($d->nominal_libur);?></td>
				</tr>
				<tr class="border_dot">
					<th class="font">Jam Istirahat</th>
					<td></td>
					<td class="font"><?php //echo $d->jam_libur_pendek;?></td>
				</tr>
				<tr class="border_dot">
					<th></th>
					<td></td>
					<td class="font"><?php //echo $this->formatter->getFormatMoneyUser($d->nominal_libur_pendek);?></td>
				</tr> -->
				<tr class="border_dot">
					<th style="font-size:10pt;"><b>Diterima</b></th>
					<td></td>
					<td style="font-size:10pt;"><b><?php echo $this->formatter->getFormatMoneyUser(floor($d->gaji_terima));?></b></td>
				</tr>
				<tr class="border-buttom">
					<th></th>
					<td></td>
					<td></td>
				</tr>
				<br><br>
				<tr>
					<th></th>
					<td class="font" colspan="2" style="text-align: right;">
						<?php
						$loker = $this->otherfunctions->convertResultToRowArray($this->model_master->getLokerKode($d->kode_loker));
						// echo $loker['kota'].', '.date('d').' '.$this->formatter->getMonth()[date('m')].' '.date('Y');
						echo 'Karangjati, '.date('d').' '.$this->formatter->getMonth()[date('m')].' '.date('Y');
						?>
					</td>
				</tr>
				<tr>
					<th class="center font" colspan="2">Yang Menerima</th>
					<th class="center font" colspan="2">Dibuat Oleh</th>
				</tr>
				<tr>
					<th colspan="2" style="height: 50px;"></th>
					<th colspan="2"></th>
				</tr>
				<tr>
					<th class="center font" colspan="2"><?php echo $d->nama_karyawan;?></th>
					<th class="center font" colspan="2"><?php echo $d->nama_buat;?></th>
				</tr>
			</table>
		</div>
	<!-- <div class="footer"></div> -->
	<?php $no++; }
// echo '<pre>';
// 	print_r($emp_gaji);
// echo '</pre>';

?>