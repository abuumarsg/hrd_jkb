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
			<th class="center border-buttom"><b class="nama_pt">Data Kuota Lembur <?=$this->otherfunctions->companyClientProfile()['name_office'];?></b></th>
		</tr>
	</table>
	<table width="100%">
		<tr>
			<td class="center border-buttom" width="30%" style="text-align: left;"><?php echo $data['nama'];?></td>
			<td class="center border-buttom" width="30%" style="text-align: right;">Dicetak : <?php echo $admin;?></td>
		</tr>
		<tr>
			<td class="center border-buttom" width="30%" style="text-align: left;"><?php echo $this->formatter->getDateMonthFormatUser($data['tgl_mulai']).' - '.$this->formatter->getDateMonthFormatUser($data['tgl_selesai']);?></td>
			<td class="center border-buttom" width="30%" style="text-align: right;">Tanggal : <?php echo $time;?></td>
		</tr>
	</table>
	<br>
		<div class="col-print-12">
			<table width="100%" class="parent-div" border="2">
				<tr>
					<th rowspan="2">NO.</th>
					<th rowspan="2">Bagian</th>
					<!-- <th>Persentase</th> -->
					<th colspan="2">Kuota</th>
					<th colspan="2">Sisa Kuota</th>
					<th colspan="2">Kuota Digunakan</th>
				</tr>
				<tr>
					<th>Persentase</th>
					<th>Jam</th>
					<th>Jam</th>
					<th>Persentase</th>
					<th>Project<br>(Jam)</th>
					<th>Non Project<br>(Jam)</th>
				</tr>
				<?php 
					$no = 1;
					$t_persen = 0;
					$t_sisa = 0;
					$t_kuota = 0;
					$t_use_project = 0;
					$t_use_non_project = 0;
					foreach ($detail as $d) {
						$bagian = ($d->kode_bagian == 'buff') ? 'KUOTA BUFFER' : $d->nama_bagian;
						$sisaPersen = (($d->sisa_kuota/$d->kuota)*100);
						echo '<tr>
							<td>'.$no.'</td>
							<td>'.$bagian.'</td>
							<td class="right">'.$d->persen.'%</td>
							<td class="right">'.$d->kuota.'</td>
							<td class="right">'.$d->sisa_kuota.'</td>
							<td class="right">'.number_format($sisaPersen,1,'.','').'%</td>
							<td class="right">'.(!empty($d->use_project) ? $d->use_project : 0).'</td>
							<td class="right">'.(!empty($d->use_non_project) ? $d->use_non_project : 0).'</td>
						</tr>';
						$t_persen+=$d->persen;
						$t_kuota+=$d->kuota;
						$t_sisa+=$d->sisa_kuota;
						$t_use_project+=(!empty($d->use_project) ? $d->use_project : 0);
						$t_use_non_project+=(!empty($d->use_non_project) ? $d->use_non_project : 0);
						$no++;
					}
					echo '<tr>
						<td class="center" colspan="2"><b>TOTAL</b></td>
						<td class="right">'.$t_persen.'%</td>
						<td class="right">'.$t_kuota.'</td>
						<td class="right">'.$t_sisa.'</td>
						<td class="right"></td>
						<td class="right">'.$t_use_project.'</td>
						<td class="right">'.$t_use_non_project.'</td>
					</tr>';
				?>
			</table>
		</div>
</div>