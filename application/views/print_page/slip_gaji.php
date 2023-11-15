<style type = "text/css">
    @import url("https://fonts.googleapis.com/css?family=Signika");
    @import url("https://fonts.googleapis.com/css?family=Suez+One "); 
    @import url("https://fonts.googleapis.com/css?family=Salsa");
    @import url("https://fonts.googleapis.com/css?family=Times+New+Roman");
    @media print,screen {
		@page {
			/* size: A5 landscape; */
			size: A5 portrait;
			margin: 7mm;
		}
        .box-id {
            position:left;
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
            font-size:10pt;            
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
foreach ($emp_gaji as $d) {
	?>
	<div class="box-id">
		<table width="100%">
			<tr>
				<th class="center"><img class="photo" src="<?= $this->otherfunctions->companyClientProfile()['logo'];?>" alt="Logo Perusahaan"></th>
			</tr>
			<tr>
				<th class="center border-buttom"><b class="nama_pt">Slip Gaji <?=$this->otherfunctions->companyClientProfile()['name_office'];?> - <?php echo $d->nama_loker; ?></b></th>
			</tr>
			<tr>
				<td class="center font">Tanggal : <?php echo $this->formatter->getDateMonthFormatUser($periode['tgl_mulai']); ?> - <?php echo $this->formatter->getDateMonthFormatUser($periode['tgl_selesai']); ?></td>
			</tr>
		</table>
		<br>
		<!-- <table width="100%">
			<tr>
				<th class="center border-buttom">Slip Gaji CV Jati Kencana - <?php echo $d->nama_bagian; ?></th>
			</tr>
			<tr>
				<td>Bulan : <?php echo $this->formatter->getMonth()[date('m',strtotime($d->tgl_selesai))]; ?>-<?php echo date('Y',strtotime($d->tgl_selesai)); ?></td>
			</tr>
		</table>
		<br> -->
		<table width="100%">
			<tr class="border_dot">
				<th class="font" style="width: 35%;">No</th>
				<td style="width: 30%;"></td>
				<!-- <td class="font" style="width: 20%;"></td> -->
				<td class="font" style="width: 35%;"><?php echo $no;?></td>
			</tr>
			<tr class="border_dot">
				<th class="font">Nama</th>
				<td></td>
				<td class="font"><?php echo $d->nama_karyawan;?></td>
			</tr>
			<tr class="border_dot">
				<th class="font">Bagian</th>
				<td></td>
				<td class="font"><?php echo $d->nama_bagian;?></td>
			</tr>
			<tr class="border_dot">
				<th class="font">Masa Kerja</th>
				<td></td>
				<td class="font"><?php echo $d->masa_kerja;?></td>
			</tr>
			<tr class="border_dot">
				<th class="font">Gaji Pokok</th>
				<td></td>
				<td class="font"><?php echo $this->formatter->getFormatMoneyUser($d->gaji_pokok);?></td>
			</tr>
			<tr class="border_dot">
				<th class="font">Insentif</th>
				<td></td>
				<td class="font"><?php echo $this->formatter->getFormatMoneyUser($d->insentif);?></td>
			</tr>
			<tr class="border_dot">
				<th class="font">Ritasi</th>
				<!-- <td></td> -->
				<td class="font"><?php echo ($d->jumlah_ritasi!=0)?$d->jumlah_ritasi:null?></td>
				<td class="font"><?php echo $this->formatter->getFormatMoneyUser($d->ritasi);?></td>
			</tr>
			<tr class="border_dot">
				<th class="font">Uang Makan</th>
				<td></td>
				<td class="font"><?php echo $this->formatter->getFormatMoneyUser($d->uang_makan);?></td>
			</tr>
			<tr class="border_dot">
				<th class="font">Tunjangan Tetap</th>
				<td></td>
				<td class="font"><?php echo $this->formatter->getFormatMoneyUser($d->tunjangan_tetap);?></td>
			</tr>
			<tr class="border_dot">
				<th class="font">Tunjangan Tidak Tetap</th>
				<td></td>
				<td class="font"><?php echo $this->formatter->getFormatMoneyUser($d->tunjangan_non);?></td>
			</tr>
			<tr class="border-buttom border-top">
				<th></th>
				<th>TANGGAL</th>
				<th></th>
				<!-- <th class="font">HK</th> -->
				<!-- <td></td> -->
			</tr>
			<tr class="border_dot">
				<th class="font">Alpha</th>
				<td class="font" colspan="2"><?php if(!empty($d->alpha)){echo $this->payroll->getTanggalBulan($d->alpha);}?></td>
			</tr>
			<tr class="border_dot">
				<th class="font">Tanggal Terlambat</th>
				<td class="font" colspan="2"><?php if(!empty($d->tanggal_terlambat)){echo $this->payroll->getTanggalBulan($d->tanggal_terlambat);}?></td>
			</tr>
			<tr class="border_dot">
				<th class="font">Terlambat</th>
				<td class="font"><?php if(!empty($d->terlambat)){echo $d->terlambat;}?></td>
				<td class="font"><?php echo $this->formatter->getFormatMoneyUser($d->n_terlambat);?></td>
			</tr>
			<!-- <tr class="border_dot">
				<th class="font">Izin</th>
				<td class="font"><?php if(!empty($d->ijin)){echo count(explode(";",$d->ijin));}else{echo 0;}?></td>
				<td><?php echo implode("<br>",explode(";",$d->ijin));?></td>
				<td></td>
			</tr>
			<tr class="border_dot">
				<th class="font">Sakit Dengan Surat Dokter</th>
				<td class="font"><?php if(!empty($d->sakit_skd)){echo count(explode(";",$d->sakit_skd));}else{echo 0;}?></td>
				<td><?php echo implode("<br>",explode(";",$d->sakit_skd));?></td>
				<td></td>
			</tr>
			<tr class="border_dot">
				<th class="font">Meninggalkan Jam Kerja</th>
				<td class="font"><?php echo $d->meninggalkan_jam_kerja;?></td>
				<td class="font"><?php echo $this->formatter->getFormatMoneyUser($d->meninggalkan_jam_kerja_n);?></td>
			</tr> -->
			<tr class="border_dot">
				<th class="font">Izin</th>
				<!-- <td class="font"><?php echo $d->izin;?> Hari</td> -->
				<td class="font"><?php if(!empty($d->val_izin)){echo $this->payroll->getTanggalBulan($d->val_izin);}?></td>
				<td class="font"><?php echo $this->formatter->getFormatMoneyUser($d->n_izin);?></td>
			</tr>
			<tr class="border_dot">
				<th class="font">Izin Tanpa SDR</th>
				<!-- <td class="font"><?php echo $d->iskd;?> Hari</td> -->
				<td class="font"><?php if(!empty($d->val_iskd)){echo $this->payroll->getTanggalBulan($d->val_iskd);}?></td>
				<td class="font"><?php echo $this->formatter->getFormatMoneyUser($d->n_iskd);?></td>
			</tr>
			<tr class="border_dot">
				<th class="font">Meninggalkan Jam Kerja</th>
				<!-- <td class="font"><?php echo $d->imp;?> Jam</td> -->
				<td class="font"><?php if(!empty($d->val_imp)){echo $this->payroll->getTanggalBulan($d->val_imp);}?></td>
				<td class="font"><?php echo $this->formatter->getFormatMoneyUser($d->n_imp);?></td>
			</tr>
			<tr class="border_dot">
				<th class="font">Potongan Tidak Masuk</th>
				<td class="font"><?php $n_p_h = $d->pot_tidak_masuk/count(explode(";",$d->alpha));
					$d_p = (!empty($d->alpha)?'@ '.$this->formatter->getFormatMoneyUser($this->otherfunctions->nonPembulatan($n_p_h)):null);
					echo $d_p;?></td>
				<td class="font"><?php echo $this->formatter->getFormatMoneyUser($d->pot_tidak_masuk);?></td>
			</tr>
			<tr class="border_dot">
				<th class="font">BPJS Ketenagakerjaan</th>
				<td></td>
				<td class="pot font"><?php echo $this->formatter->getFormatMoneyUser($d->bpjs_jkk+$d->bpjs_jht+$d->bpjs_jkm);?></td>
			</tr>
			<tr class="border_dot">
				<th class="font">Jaminan Pensiun</th>
				<td></td>
				<td class="pot font"><?php echo $this->formatter->getFormatMoneyUser($d->bpjs_pen);?></td>
			</tr>
			<tr class="border_dot">
				<th class="font">BPJS Kesehatan</th>
				<td></td>
				<td class="pot font"><?php echo $this->formatter->getFormatMoneyUser($d->bpjs_kes);?></td>
			</tr>
			<tr class="border_dot">
				<th class="font">Pinjaman | Angsuran Ke</th>
				<td class="font"><?php echo $d->angsuran_ke;?></td>
				<td class="pot font"><?php echo $this->formatter->getFormatMoneyUser($d->angsuran);?></td>
			</tr>
			<tr class="border_dot">
				<th class="font">Denda | Angsuran Ke</th>
				<td class="font"><?php echo $d->angsuran_ke_denda;?></td>
				<td class="pot font"><?php echo $this->formatter->getFormatMoneyUser($d->nominal_denda);?></td>
			</tr>
			<?php
			if(!empty($d->data_lain)){
				$dtx=$this->otherfunctions->getDataExplode($d->data_lain,';','all');
				$n_lain=$this->otherfunctions->getDataExplode($d->nominal_lain,';','all');
				$ket_lain=$this->otherfunctions->getDataExplode($d->data_lain_nama,';','all');
				foreach ($dtx as $key => $value) {
					echo '<tr class="border_dot">
						<th class="font">'.$value.'</th>
						<td class="font">'.$ket_lain[$key].'</td>
						<td class="font">'.$this->formatter->getFormatMoneyUser($n_lain[$key]).'</td>
					</tr>';
				}
			}?>
			<tr class="border_dot">
				<th style="font-size:12pt;">Penerimaan</th>
				<td></td>
				<td style="font-size:12pt;"><b><?php echo $this->formatter->getFormatMoneyUser($d->gaji_bersih);?></b></td>
			</tr>
			<tr class="border-buttom">
				<td></td>
				<td></td>
				<td></td>
			</tr>
			<tr>
				<th></th>
				<td class="font" colspan="2" style="text-align: right;">
					<?php
					$loker = $this->otherfunctions->convertResultToRowArray($this->model_master->getLokerKode($d->kode_loker));
					echo $loker['kota'].', '.date('d').' '.$this->formatter->getMonth()[date('m')].' '.date('Y');
					?>
				</td>
			</tr>
			<tr>
				<th class="center font" colspan="2">Yang Menerima</th>
				<th class="center font" colspan="2">Dibuat Oleh</th>
			</tr>
			<tr>
				<th colspan="2" style="height: 40px;"></th>
				<th colspan="2"></th>
			</tr>
			<tr>
				<th class="center font" colspan="2"><?php echo $d->nama_karyawan;?></th>
				<th class="center font" colspan="2"><?php echo $d->nama_buat;?></th>
			</tr>
		</table>
	</div>
	<?php
	$no++;
}
?>