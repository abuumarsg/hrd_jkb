<style type = "text/css">
    @import url("https://fonts.googleapis.com/css?family=Signika");
    @import url("https://fonts.googleapis.com/css?family=Suez+One "); 
    @import url("https://fonts.googleapis.com/css?family=Salsa");
    @import url("https://fonts.googleapis.com/css?family=Times+New+Roman");
    @media print,screen {
		@page {
			size: Legal portrait;
			margin: 7mm;
		}
        .box-id {
            position:relative;
            background:white;
            margin-left: 3px;
            margin-bottom: 3px;
            width: 718px;
            height: 1133px;
            padding: 6px 6px 6px 6px;
            float:left;
            border: 1px solid white;
            background-image: none;
			page-break-inside: avoid;
        }
		th{
			padding: 2px;
			text-align: center;
			white-space: pre;
		}
		td{
			padding: 2px;
			white-space: pre; 
			height: 35px;          
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
            font-size:10pt;            
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
<div class="box-id">
	<div style="padding: 10px;">
		<div style="font-weight: bold;text-align: center;">TANDA TERIMA SLIP LEMBUR CV JATI KENCANA</div>
		<div style="font-weight: bold;text-align: center;">
			Tanggal : <?php echo $this->formatter->getDateMonthFormatUser($periode['tgl_mulai']); ?> - <?php echo $this->formatter->getDateMonthFormatUser($periode['tgl_selesai']); ?>
		</div><br>
		<table class="border" width="100%" border="1">
			<tr style="background-color: #7C7C71;">
				<th class="center">NO</th>
				<th class="center">NIK</th>
				<th class="center">NAMA</th>
				<th class="center">JABATAN</th>
				<th class="center" colspan="2" style="width:400px">TANDA TERIMA</th>
			</tr>
			<?php
			$no = 1;
			foreach ($emp_gaji as $d) {
				if($d->gaji_terima != 0){
					echo '<tr>
						<td class="font">'.$no.'</td>
						<td class="font">'.$d->nik.'</td>
						<td class="font">'.$d->nama_karyawan.'</td>
						<td class="font">'.$d->nama_jabatan.'</td>
						<td class="font">'.(($no % 2 != 0)?$no.'.':null).'</td>
						<td class="font">'.(($no % 2 == 0)?$no.'.':null).'</td>';					
					$no++;
				}
			} ?>
		</table>
	</div>
</div>
