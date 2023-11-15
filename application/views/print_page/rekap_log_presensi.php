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
            width: 470px;
            height: 718px;
            /* width: 320px; */
            /* height: 483px; */
            padding: 4px 4px 4px 4px;
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
<div class="box-ids">
	<?php if($header != '1'){ ?>
	<table width="100%">
		<tr>
			<th class="center"><img class="photo" src="<?= $this->otherfunctions->companyClientProfile()['logo'];?>" alt="Logo Perusahaan"></th>
		</tr>
		<tr>
			<th class="center border-buttom"><b class="nama_pt">Data Log Presensi <?=$this->otherfunctions->companyClientProfile()['name_office'];?></b></th>
		</tr>
	</table>
	<table width="100%">
		<tr>
			<td class="center border-buttom" width="30%" style="text-align: left;"><?php echo $admin;?></td>
			<td class="center border-buttom" width="30%" style="text-align: right;"><?php echo $time;?></td>
		</tr>
	</table>
	<?php } ?>
	<br>
<?php
	$no = 1;
	foreach ($data as $nama => $pre) { 
		if ($no % 4 == 0){
			echo '<div class="row">';
		}
		?>
		<div class="col-print-3">
			<table width="100%" class="parent-div" border="2">
				<tr>
					<th class="center grayColor" colspan="2"><b><?=$nama?></b></th>
				</tr>
				<?php
					if(!empty($pre)){
						foreach ($pre as $key => $val) {
							echo '<tr>
								<th class="center">'.$this->formatter->getDateFormatUser($val['tanggal']).'</th>
								<th class="center">'.$val['jam'].'</th>
							</tr>';
						}
					}else{
						echo '<tr>
						<th class="center" colspan="2">Tidak Ada Data</th>
					</tr>';
					}
				?>
			</table>
		</div>
		<?php
		if ($no % 4 == 0){
			echo '</div>';
		}
		$no++;
	}
?>
</div>