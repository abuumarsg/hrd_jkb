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
	 /* $get_lembur = $this->otherfunctions->convertResultToRowArray($this->model_karyawan->getDataPayrollLembur(['a.kode_periode'=>$d->kode_periode,'a.id_karyawan'=>$d->id_karyawan])); */
	 $get_lembur = [
		'jam_biasa'=>$d->jam_biasa,
		'nominal_biasa'=>$d->nominal_biasa,
		'jam_libur'=>$d->jam_libur,
		'nominal_libur'=>$d->nominal_libur,
		'jam_libur_pendek'=>$d->jam_libur_pendek,
		'nominal_libur_pendek'=>$d->nominal_libur_pendek,
		'gaji_lembur'=>$d->gaji_lembur,
	];
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
				<!-- <td class="font">Tanggal : <?php //echo $this->formatter->getDateMonthFormatUser($periode['tgl_mulai']); ?> - <?php //echo $this->formatter->getDateMonthFormatUser($periode['tgl_selesai']); ?></td> -->
				<td class="font"><?php echo $periode; ?></td>
			</tr>
		</table>
		<!-- <table width="100%">
			<tr>
				<th class="center border-buttom">Slip Gaji CV Jati Kencana - <?php echo $d->nama_bagian; ?></th>
			</tr>
			<tr>
				<td>Bulan : <?php echo $this->formatter->getMonth()[date('m',strtotime($d->tgl_selesai))]; ?>-<?php echo date('Y',strtotime($d->tgl_selesai)); ?></td>
			</tr>
		</table> -->
		<br>
		<table width="100%">
			<tr class="border_dot">
				<th style="width: 40%;">No</th>
				<td style="width: 10%;"></td>
				<td style="width: 0%;"></td>
				<td style="width: 50%;"><?php echo $no;?></td>
			</tr>
			<tr class="border_dot">
				<th>Nama</th>
				<td></td>
				<td></td>
				<td><?php echo $d->nama_karyawan;?></td>
			</tr>
			<tr class="border_dot">
				<th>Bagian</th>
				<td></td>
				<td></td>
				<td><?php echo $d->nama_bagian;?></td>
			</tr>
			<tr class="border_dot">
				<th>Masa Kerja</th>
				<td></td>
				<td></td>
				<td><?php echo $d->masa_kerja;?></td>
			</tr>
			<tr class="border-buttom border-top">
				<th colspan="4" style="text-align: center;">Besaran Gaji</th>
			</tr>
			<tr class="border_dot">
				<th>Gaji per hari</th>
				<td></td>
				<td></td>
				<td><?php echo $this->formatter->getFormatMoneyUser($d->gaji_pokok);?></td>
			</tr>
			<tr class="border_dot">
				<th>Presensi</th>
				<td></td>
				<td></td>
				<td><?php echo $d->presensi;?> Hari</td>
			</tr>
			<tr class="border_dot">
				<th class="font">BPJS Ketenagakerjaan</th>
				<td></td>
				<td></td>
				<td class="pot font"><?php echo $this->formatter->getFormatMoneyUser($d->jkk+$d->jht+$d->jkm);?></td>
			</tr>
			<tr class="border_dot">
				<th class="font">Jaminan Pensiun</th>
				<td></td>
				<td></td>
				<td class="pot font"><?php echo $this->formatter->getFormatMoneyUser($d->jpen);?></td>
			</tr>
			<tr class="border_dot">
				<th class="font">BPJS Kesehatan</th>
				<td></td>
				<td></td>
				<td class="pot font"><?php echo $this->formatter->getFormatMoneyUser($d->jkes);?></td>
			</tr>
			<?php
			if(!empty($d->data_lain)){
				$dtx=$this->otherfunctions->getDataExplode($d->data_lain,';','all');
				$n_lain=$this->otherfunctions->getDataExplode($d->nominal_lain,';','all');
				$ket_lain=$this->otherfunctions->getDataExplode($d->keterangan_lain,';','all');
				foreach ($dtx as $key => $value) {
					echo '<tr class="border_dot">
						<th>'.$value.'</th>
						<td colspan="2">'.$ket_lain[$key].'</td>
						<td>'.$this->formatter->getFormatMoneyUser($n_lain[$key]).'</td>
					</tr>';
				}
			}?>
			<tr class="border_dot">
				<th>Total Gaji</th>
				<td></td>
				<td></td>
				<td><?php echo $this->formatter->getFormatMoneyUser($d->presensi*$d->gaji_pokok);?></td>
			</tr>
			<!-- <tr class="border_dot">
				<th>Insentif</th>
				<td></td>
				<td></td>
				<td><?php //echo $this->formatter->getFormatMoneyUser($d->insentif);?></td>
			</tr>
			<tr class="border_dot">
				<th>Ritasi</th>
				<td></td>
				<td></td>
				<td><?php //echo $this->formatter->getFormatMoneyUser($d->ritasi);?></td>
			</tr>
			<tr class="border_dot">
				<th>Uang Makan</th>
				<td></td>
				<td></td>
				<td><?php //echo $this->formatter->getFormatMoneyUser($d->uang_makan);?></td>
			</tr>
			<tr class="border_dot">
				<th>Tunjangan</th>
				<td></td>
				<td></td>
				<td><?php //echo $this->formatter->getFormatMoneyUser($d->tunjangan);?></td>
			</tr>
			<tr class="border-buttom border-top">
				<th></th>
				<td>HK</td>
				<td>TGL</td>
				<td></td>
			</tr> -->
			<!-- <tr class="border_dot">
				<th>Alpha</th>
				<td><?php //echo count(explode(";",$d->alpha));?></td>
				<td><?php //echo implode("<br>",explode(";",$d->alpha));?></td>
				<td></td>
			</tr>
			<tr class="border_dot">
				<th>Ijin</th>
				<td><?php //echo count(explode(";",$d->ijin));?></td>
				<td><?php //echo implode("<br>",explode(";",$d->ijin));?></td>
				<td></td>
			</tr>
			<tr class="border_dot">
				<th>Sakit Dengan Surat Dokter</th>
				<td><?php //echo count(explode(";",$d->sakit_skd));?></td>
				<td><?php //echo implode("<br>",explode(";",$d->sakit_skd));?></td>
				<td></td>
			</tr>
			<tr class="border_dot">
				<th>Meninggalkan Jam Kerja</th>
				<td><?php //echo $d->meninggalkan_jam_kerja;?></td>
				<td></td>
				<td><?php //echo $this->formatter->getFormatMoneyUser($d->meninggalkan_jam_kerja_n);?></td>
			</tr>
			<tr class="border_dot">
				<th>Potongan Tidak Masuk</th>
				<td></td>
				<td></td>
				<td><?php //echo $this->formatter->getFormatMoneyUser($d->pot_tidak_masuk);?></td>
			</tr>
			<tr class="border_dot">
				<th>BPJS Ketenagakerjaan</th>
				<td></td>
				<td></td>
				<td><?php //echo $this->formatter->getFormatMoneyUser($d->bpjs_jkk+$d->bpjs_jht+$d->bpjs_jkm);?></td>
			</tr>
			<tr class="border_dot">
				<th>Jaminan Pensiun</th>
				<td></td>
				<td></td>
				<td><?php //echo $this->formatter->getFormatMoneyUser($d->bpjs_pen);?></td>
			</tr>
			<tr class="border_dot">
				<th>BPJS Kesehatan</th>
				<td></td>
				<td></td>
				<td><?php //echo $this->formatter->getFormatMoneyUser($d->bpjs_kes);?></td>
			</tr>
			<tr class="border_dot">
				<th>Angsuran Ke</th>
				<td></td>
				<td></td>
				<td><?php //echo $this->formatter->getFormatMoneyUser($d->angsuran);?></td>
			</tr> -->
			<tr class="border-buttom border-top">
				<th colspan="4" style="text-align: center;">Hari Libur</th>
			</tr>
			<tr class="border_dot">
				<th class="font">Total Jam Hari Libur</th>
				<td></td>
				<td></td>
				<td class="font"><?php echo $d->total_jam;?></td>
			</tr>
			<tr class="border_dot">
				<th class="font">Perhitungan</th>
				<td></td>
				<td></td>
				<td class="font"><?php echo $d->ekuivalen;?></td>
			</tr>
			<!-- <tr class="border_dot">
				<th>Lembur Hari Biasa</th>
				<td><?php //echo $get_lembur['jam_biasa']; ?></td>
				<td></td>
				<td><?php //echo $this->formatter->getFormatMoneyUser($get_lembur['nominal_biasa']);?></td>
			</tr>
			<tr class="border_dot">
				<th>Lembur Hari Libur</th>
				<td><?php //echo $get_lembur['jam_libur']; ?></td>
				<td></td>
				<td><?php //echo $this->formatter->getFormatMoneyUser($get_lembur['nominal_libur']);?></td>
			</tr>
			<tr class="border_dot">
				<th>Lembur Jam Istirahat</th>
				<td><?php //echo $get_lembur['jam_libur_pendek']; ?></td>
				<td></td>
				<td><?php //echo $this->formatter->getFormatMoneyUser($get_lembur['nominal_libur_pendek']);?></td>
			</tr> -->
			<tr class="border_dot">
				<th>Total Gaji Hari Libur</th>
				<td></td>
				<td></td>
				<td><?php echo $this->formatter->getFormatMoneyUser($get_lembur['gaji_lembur']);?></td>
			</tr>
			<tr class="border_dot">
				<th>Penerimaan</th>
				<td></td>
				<td></td>
				<th><?php echo $this->formatter->getFormatMoneyUser($d->gaji_bersih);?></th>
			</tr>
			<tr class="border-buttom">
				<th></th>
				<td></td>
				<td></td>
				<td></td>
			</tr>
			<tr>
				<th></th>
				<td></td>
				<td colspan="2" style="text-align: right;">
					<?php
					$loker = $this->otherfunctions->convertResultToRowArray($this->model_master->getLokerKode($d->kode_loker));
					echo $loker['kota'].', '.date('d').' '.$this->formatter->getMonth()[date('m')].' '.date('Y');
					?>
				</td>
			</tr>
			<tr>
				<th class="center" colspan="2">Yang Menerima</th>
				<th class="center" colspan="2">Dibuat Oleh</th>
			</tr>
			<tr>
				<th colspan="2" style="height: 50px;"></th>
				<th colspan="2"></th>
			</tr>
			<tr>
				<th class="center" colspan="2"><?php echo $d->nama_karyawan;?></th>
				<th class="center" colspan="2"><?php echo $d->nama_buat;?></th>
			</tr>
		</table>
	</div>
	<?php
	$no++;
}
?>