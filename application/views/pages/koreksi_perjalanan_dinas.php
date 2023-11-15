<style type="text/css">
th, td { 
	white-space: nowrap; 
}
table#table_data thead tr th, table#table_data tbody tr td, table.DTFC_Cloned thead tr th, table.DTFC_Cloned tbody tr td{
	white-space: pre;
}
table.DTFC_Cloned tbody{
	overflow: hidden;
}
/*.DTFC_LeftBodyLiner{overflow-y:unset !important}*/
.DTFC_RightBodyLiner{overflow-y:unset !important}

.dark-mode .DTFC_RightBodyWrapper,.dark-mode .DTFC_LeftBodyWrapper{
	border-style: solid;
	border-width: 1px;
}
input, select, textarea{
    color: #030303;
}

textarea:focus, input:focus {
    color: #030303;
}
</style>
<div class="content-wrapper">
	<section class="content-header">
		<h1>
			<i class="fas fa-car"></i> Koreksi <small>Perjalanan Dinas</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fa fas fa-tachometer-alt"></i> Dashboard</a></li>
			<li><a href="<?php echo base_url('pages/data_perjalanan_dinas');?>"><i class="fas fa-car"></i> Data Perjalanan Dinas</a></li>
			<li class="active"><i class="fas fa-car"></i> <?=$nomor?></li>
		</ol>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-info">
					<div class="box-header with-border">
						<h3 class="box-title"><i class="fas fa-car"></i> Koreksi Perjalanan Dinas <b><i><?=$nomor?></i></b></h3>
						<div class="box-tools pull-right">
							<button class="btn btn-box-tool" onclick="reload_table('table_data_trans')" data-toggle="tooltip" title="Refresh Table"><i class="fas fa-sync"></i></button>
							<button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
							<button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i class="fa fa-times"></i></button>
						</div>
					</div>
					<?php
					//pages
					function koreksi_perjalanan_dinas(){
						$noPerdin = $this->codegenerator->decryptChar($this->uri->segment(3));
						$dataPerKar = $this->model_karyawan->getPerjalananDinasKodeSKGroup($noPerdin);
						$dataPerKarAll = $this->model_karyawan->getPerjalananDinasKodeSK($noPerdin);
						// echo '<pre>';
						// print_r($dataPerKar);
						$data=[
							'access'=>$this->access,
							'nomor'=>$noPerdin,
							'data'=>$dataPerKar,
							'dataAll'=>$dataPerKarAll,
						];
						$this->load->view('admin_tem/headerx',$this->dtroot);
						$this->load->view('admin_tem/sidebarx',$this->dtroot);
						$this->load->view('pages/koreksi_perjalanan_dinas',$data);
						$this->load->view('admin_tem/footerx');	
					} 
					?>
					<div class="box-body">
						<div class="row">
							<div class="col-md-12">
								<div class="panel panel-success">
									<div class="panel-heading bg-green"><h4>Data Validasi Perjalanan Dinas</h4></div>
									<?php echo form_open('presensi/addNewKoreksi'); ?>
										<div class="panel-body">
											<div class="row">
												<div class="col-md-12">
													<div class="col-md-6">
														<div class="form-group col-md-12">
															<label class="col-md-6 control-label">Tanggal Berangkat</label>
															<div class="col-md-6"><?php echo $this->formatter->getDateTimeMonthFormatUser($data['val_tgl_berangkat'],1) ?> WIB</div>
														</div><br>
														<div class="form-group col-md-12">
															<label class="col-md-6 control-label">Tanggal Sampai</label>
															<div class="col-md-6"><?php echo $this->formatter->getDateTimeMonthFormatUser($data['val_tgl_sampai'],1) ?> WIB</div>
														</div><br>
														<div class="form-group col-md-12">
															<label class="col-md-6 control-label">Tanggal Pulang</label>
															<div class="col-md-6"><?php echo $this->formatter->getDateTimeMonthFormatUser($data['val_tgl_pulang'],1) ?> WIB</div>
														</div><br>
														<div class="form-group col-md-12">
															<label class="col-md-6 control-label">Kendaraan</label>
															<div class="col-md-6"><?php echo (!empty($data['val_nama_kendaraan_j'])?$data['val_nama_kendaraan_j']:'-') ?></div>
														</div><br>
														<div class="form-group col-md-12">
															<label class="col-md-6 control-label">Nama Kendaraan Umum</label>
															<div class="col-md-6"><?php echo $this->otherfunctions->getKendaraanUmum($data['val_kendaraan_umum']) ?></div>
														</div><br>
														<div class="form-group col-md-12">
															<label class="col-md-6 control-label">Nominal BBM Per Kendaraan</label>
															<div class="col-md-6"><?php echo $this->formatter->getFormatMoneyUser($data['val_nominal_bbm']) ?></div>
														</div><br>
														<div class="form-group col-md-12">
															<label class="col-md-6 control-label">Jumlah Kendaraan</label>
															<div class="col-md-6"><?php echo (!empty($data['val_jum_kendaraan'])?$data['val_jum_kendaraan'].' Kendaraan': '-')?></div>
														</div><br>
														<div class="form-group col-md-12">
															<label class="col-md-6 control-label">Total Nominal Transport</label>
															<div class="col-md-6"><?php echo $this->formatter->getFormatMoneyUser($data['val_nominal_bbm']*$data['val_jum_kendaraan'])?></div>
														</div><br>
													</div>
													<div class="col-md-6">
														<div class="form-group col-md-12">
															<label class="col-md-6 control-label">Menginap</label>
															<div class="col-md-6"><?php echo (!empty($data['val_menginap'])?$val_menginap:'-')?></div>
														</div><br>
														<div class="form-group col-md-12">
															<label class="col-md-6 control-label">Penginapan</label>
															<div class="col-md-6"><?php echo (!empty($data['val_penginapan'])?$this->otherfunctions->getPenginapan($data['val_penginapan']):null)?></div>
														</div><br>
														<div class="form-group col-md-12">
															<label class="col-md-6 control-label">Kelas Hotel</label>
															<div class="col-md-6"><?php echo (!empty($data['val_jenis_hotel'])?$this->model_master->getTipeHotelWhere(['a.kode'=>$data['val_jenis_hotel']],true)['nama']:null)?></div>
														</div><br>
														<div class="form-group col-md-12">
															<label class="col-md-6 control-label">Biaya Penginapan</label>
															<div class="col-md-6"><?php echo $this->formatter->getFormatMoneyUser($data['val_nominal_penginapan'])?></div>
														</div><br>
														<div class="form-group col-md-12">
															<label class="col-md-6 control-label">Jumlah Kamar</label>
															<div class="col-md-6"><?php //echo $val_jumlah_kamar?> Kamar</div>
														</div><br>
														<div class="form-group col-md-12">
															<label class="col-md-6 control-label">Jumlah Hari</label>
															<div class="col-md-6"><?php //echo $val_jumlah_hari?> Hari</div>
														</div><br>
														<div class="form-group col-md-12">
															<label class="col-md-6 control-label">Total Biaya Penginapan</label>
															<div class="col-md-6"><?php // echo $this->formatter->getFormatMoneyUser($d->val_nominal_penginapan*$val_jumlah_kamar*$val_jumlah_hari)?></div>
														</div><br>
													</div>
												</div>
											</div>
											<input type="hidden" name="kode_perdin" value="<?=$data['no_sk']?>">
											<div class="row">
												<div class="col-md-2"></div>
												<div class="col-md-8">
													<table class="table table-bordered table-striped data-table">
														<thead>
															<tr>
																<th colspan="2" class="bg-yellow text-center">KOREKSI</th>
															</tr>
															<tr>
																<td><b>Total Transport</b></td>
																<td><b>Total Penginapan</b></td>
															</tr>
														</thead>
														<tbody>
															<?php
																$total_transport = ((isset($dKoreksiRow['val_nominal_bbm']) && !empty($dKoreksiRow['val_nominal_bbm'])) ? $this->formatter->getFormatMoneyUser($dKoreksiRow['val_nominal_bbm']) : '');
																$total_penginapan = ((isset($dKoreksiRow['val_total_penginapan']) && !empty($dKoreksiRow['val_total_penginapan'])) ? $this->formatter->getFormatMoneyUser($dKoreksiRow['val_total_penginapan']) : '');

															?>
															<tr>
																<td><input type="text" name="total_transport" class="form-control input-money" style="width:100%;" value="<?=$total_transport?>"></td>
																<td><input type="text" name="total_penginapan" class="form-control input-money" style="width:100%;" value="<?=$total_penginapan?>"></td>
															</tr>
														</tbody>
													</table>
												</div>
											</div>
											<style>
												table {
												border-collapse: collapse;
												width: 100%;
												}

												th, td {
												text-align: left;
												padding: 8px;
												}

												tr:nth-child(even) {
												background-color: #D6EEEE;
												}
											</style>
											<div class="row">
												<div class="col-md-12">
												<?php 
													$kode = $data['no_sk'];
													$adaDriver = 0;
													$namaDriver = '';
													if(empty($data['driver'] && $data['driver'] != '1')){
														$jbt_mengetahui=($data['jbt_mengetahui'] != null) ? ' - '.$data['jbt_mengetahui'] : null;
														$jbt_menyetujui=($data['jbt_menyetujui'] != null) ? ' - '.$data['jbt_menyetujui'] : null;
														$tujuan=($data['plant']=='plant')?$data['nama_plant_tujuan']:$data['lokasi_tujuan'];
														$kendaraan=($data['kendaraan']=='KPD0001')?$data['nama_kendaraan_j'].' ('.$this->otherfunctions->getKendaraanUmum($data['nama_kendaraan']).')':$data['nama_kendaraan_j'];
														$jarak=($data['kendaraan']=='KPD0001')?$data['jarak'].' km':$this->otherfunctions->getMark();
														$tabel='';
														$tabel.='<h4 align="center"><b>Data Tunjangan Perjalanan Dinas</b></h4>
															<div style="max-height: 700px; overflow: auto;">
															<table class="table table-bordered table-striped data-table">
																<thead>
																	<tr class="bg-blue">
																		<th rowspan="2">No.</th>
																		<th rowspan="2">Nama Karyawan</th>
																		<th rowspan="2">Grade</th>';
																		if($data['plant']=='plant'){
																			$tabel.='<th colspan="4" class="text-center">TUNJANGAN MAKAN</th>';
																		}else{
																			$tabel.='<th colspan="3" class="text-center">TUNJANGAN MAKAN</th>';
																		}
																		$val_tunjangan = (!empty($data['val_tunjangan'])?$data['val_tunjangan']:$data['tunjangan']);
																		$val_besar_tunjangan = (!empty($data['val_tunjangan'])?$data['val_besar_tunjangan']:$data['besar_tunjangan']);
																		$tunJa=$this->otherfunctions->getDataTunjanganPerdin($val_tunjangan,$val_besar_tunjangan);
																		if(!empty($tunJa)){
																			foreach ($tunJa as $keys => $val) {
																				if($keys == 'UM'){
																					$tabel.='<th rowspan="2" class="text-center">JUMLAH<br>UM Perjalanan</th>';
																				}elseif($keys == 'KAPD0002'){
																					$tabel.='<th rowspan="2" class="text-center">'.$this->model_master->getKategoriDinasKode('KAPD0002')['nama'].'</th>';
																				}else{
																					$tabel.='<th rowspan="2">'.$this->model_master->getKategoriDinasKode($keys)['nama'].'</th>';
																				}
																			}
																		}
																		$tabel.='<th rowspan="2">TOTAL</th>
																	</tr>
																	<tr class="bg-green">
																		<th class="text-center">Pagi</th>
																		<th class="text-center">Siang</th>
																		<th class="text-center">Malam</th>';
																		if($data['plant']=='plant'){
																			$tabel.='<th class="text-center">Tambahan</th>';
																		}
																	$tabel.='</tr>
																</thead>
																<tbody>';
																	$no=1;
																	$total=0;
																	$totalKoreksi=0;
																	$dataw=$this->model_karyawan->getEmpNoSKTransaksi($kode);
																	foreach($dataw as $w){
																		if(empty($w->driver) && $w->driver != '1'){
																			$namaKar=$this->model_karyawan->getEmpID($w->id_karyawan)['nama'];
																			$gradeKar=$this->model_karyawan->getEmpID($w->id_karyawan)['grade'];
																			$namaGrade=$this->model_master->getGradeKode($gradeKar)['nama'];
																			$jarak=(!empty($data['jarak'])?$data['jarak']:null);
																			$penginapan=(!empty($data['nama_penginapan'])?$data['nama_penginapan']:null);
																			$nominal_penginapan=$this->model_karyawan->getTunjanganGradePenginapan($gradeKar,$penginapan)['nominal'];
																			$nama_penginapan=$this->otherfunctions->getPenginapan($penginapan);
																			$na=0;
																			$tabel.='<tr>
																			<td>'.$no.'</td>
																			<td>'.$namaKar.'</td>
																			<td>'.$namaGrade.'</td>';
																			$tunjangan_uang_makan = (!empty($w->val_tunjangan_um)?$w->val_tunjangan_um:$w->tunjangan_um);
																			$tun_um=$this->otherfunctions->getTunjanganUMPerdin($tunjangan_uang_makan);
																			$jarakMin=$this->model_master->getGeneralSetting('MJPD')['value_int'];
																			$nominal_dasar=$this->model_karyawan->getTunjanganGradeKetegori($gradeKar,'KAPD0003')['nominal'];
																			$nominal_grade=$this->model_karyawan->getTunjanganGradeKetegori($gradeKar,'KAPD0004')['nominal'];
																			if(!empty($tun_um)){
																				$tabel.='<td>'.$this->formatter->getFormatMoneyUser($tun_um['pagi']).'</td>'; 
																				$tabel.='<td>'.$this->formatter->getFormatMoneyUser($tun_um['siang']).'</td>'; 
																				$tabel.='<td>'.$this->formatter->getFormatMoneyUser($tun_um['malam']).'</td>'; 
																				if($data['plant']=='plant' && isset($tun_um['lembur'])){
																					$tabel.='<td>'.$this->formatter->getFormatMoneyUser($tun_um['lembur']).'</td>'; 
																					$nominal_pd = $tun_um['pagi']+$tun_um['siang']+$tun_um['malam']+$tun_um['lembur'];
																				}else{
																					$nominal_pd = $tun_um['pagi']+$tun_um['siang']+$tun_um['malam'];
																				}
																			}
																			$kode_tunjangan = (!empty($w->val_tunjangan)?$w->val_tunjangan:$w->tunjangan);
																			$basar_tunjangan = (!empty($w->val_besar_tunjangan)?$w->val_besar_tunjangan:$w->besar_tunjangan);
																			$tunJax=$this->otherfunctions->getDataTunjanganPerdin($kode_tunjangan,$basar_tunjangan);
																			$datTunj = $this->codegenerator->encryptChar($tunJax);
																			echo '<input type="hidden" name="tunjangan" value="'.$datTunj.'">';
																			if(!empty($tunJax)){
																				foreach ($tunJax as $key => $valx) {
																					$tabel.='<td>'.$this->formatter->getFormatMoneyUser($valx).'</td>'; 
																					$na=$na+$valx;
																				}
																			}
																			$tabel.='<td>'.$this->formatter->getFormatMoneyUser($na).'</td>
																			</tr>';
																			$no++;
																			$total+=$na;
																			$dKor = $this->model_karyawan->getKoreksiPerdin(['no_sk'=>$kode, 'id_karyawan'=>$w->id_karyawan], true);
																			$tunUM = explode(';', $dKor['val_tunjangan_um']);
																			$tunUMPagi = (isset($tunUM[0]) ? $tunUM[0] : 0);
																			$tunUMSiang = (isset($tunUM[1]) ? $tunUM[1] : 0);
																			$tunUMMalam = (isset($tunUM[2]) ? $tunUM[2] : 0);
																			$tunUMLembur = (isset($tunUM[3]) ? $tunUM[3] : 0);
																			$umPagix = str_replace('pagi:', '', $tunUMPagi);
																			$umPagi = $this->formatter->getFormatMoneyUser($umPagix);
																			$umSiangx = str_replace('siang:', '', $tunUMSiang);
																			$umSiang = $this->formatter->getFormatMoneyUser($umSiangx);
																			$umMalamx = str_replace('malam:', '', $tunUMMalam);
																			$umMalam = $this->formatter->getFormatMoneyUser($umMalamx);
																			$umLemburx = str_replace('lembur:', '', $tunUMLembur);
																			$umLembur = $this->formatter->getFormatMoneyUser($umLemburx);
																			$umPagix = (!empty($umPagix)) ? $umPagix : 0;
																			$umSiangx = (!empty($umSiangx)) ? $umSiangx : 0;
																			$umMalamx = (!empty($umMalamx)) ? $umMalamx : 0;
																			$umLemburx = (!empty($umLemburx)) ? $umLemburx : 0;
																			$totalUMKoreksi = $umPagix+$umSiangx+$umMalamx+$umLemburx;
																			$tabel.='<tr>
																				<td></td>
																				<td colspan="2"><b>KOREKSI</b></td>';
																				if(!empty($tun_um)){
																					$tabel.='<td>
																					<input type="hidden" name="id_karyawan[]" value="'.$w->id_karyawan.'">
																					<input type="text" name="um_pagi[]" class="input-money" value="'.$umPagi.'">
																					</td>'; 
																					$tabel.='<td>
																					<input type="text" name="um_siang[]" class="input-money" value="'.$umSiang.'">
																					</td>'; 
																					$tabel.='<td>
																					<input type="text" name="um_malam[]" class="input-money" value="'.$umMalam.'">
																					</td>'; 
																				} 
																				if($data['plant']=='plant' && isset($tun_um['lembur'])){
																					$tabel.='<td>
																					<input type="text" name="um_tambahan[]" class="input-money" value="'.$umLembur.'">
																					</td>'; 
																				}
																				$totalAkhir = 0;
																				$totalAkhir += $totalUMKoreksi;
																				if(!empty($tunJax)){
																					$dTunjangan = explode(';', $dKor['val_tunjangan']);
																					$nominalTunjangan = explode(';', $dKor['val_besar_tunjangan']);
																					$dtn=[];
																					foreach ($dTunjangan as $k => $v) {
																						$dtn[$v] = $nominalTunjangan[$k];
																					}
																					foreach ($tunJax as $key => $valx) {
																						$readonly = ($key == 'UM') ? ' readonly="readonly"' : null;
																						if($key == 'UM'){
																							$nominalTUnjangan = $this->formatter->getFormatMoneyUser($totalUMKoreksi);
																						}else{
																							$nominalTUnjangan = (isset($dtn[$key])) ? $this->formatter->getFormatMoneyUser($dtn[$key]) : null;
																						}
																						$totalAkhir=$totalAkhir+((isset($dtn[$key]) && !empty($dtn[$key])) ? $dtn[$key] : 0);
																						$tabel.='<td>
																						<input type="text" name="'.$key.'[]" class="input-money" '.$readonly.' value="'.$nominalTUnjangan.'">
																						</td>'; 
																					}
																				}
																				$tabel.='<td><b>'.$this->formatter->getFormatMoneyUser($totalAkhir).'</b></td>'; 
																				$totalKoreksi += $totalAkhir;
																			$tabel.='</tr>';
																		}else{
																			$adaDriver += 1;
																			$namaDriver = $data['id_karyawan'];
																			$namaKar=$w->id_karyawan;
																			$gradeKar='GRD201908141604';
																			$namaGrade=$this->model_master->getGradeKode($gradeKar)['nama'];
																			$jarak=(!empty($data['jarak'])?$data['jarak']:null);
																			$penginapan=(!empty($data['nama_penginapan'])?$data['nama_penginapan']:null);
																			$nominal_penginapan=$this->model_karyawan->getTunjanganGradePenginapan($gradeKar,$penginapan)['nominal'];
																			$nama_penginapan=$this->otherfunctions->getPenginapan($penginapan);
																			$na=0;
																			$tabel.='<tr>
																			<td>'.$no.'</td>
																			<td>'.$namaKar.' - <b><i>Driver</i></b></td>
																			<td>'.$namaGrade.'</td>';
																			$tunjangan_uang_makan = (!empty($w->val_tunjangan_um)?$w->val_tunjangan_um:$w->tunjangan_um);
																			$tun_um=$this->otherfunctions->getTunjanganUMPerdin($tunjangan_uang_makan);
																			$jarakMin=$this->model_master->getGeneralSetting('MJPD')['value_int'];
																			$nominal_dasar=$this->model_karyawan->getTunjanganGradeKetegori($gradeKar,'KAPD0003')['nominal'];
																			$nominal_grade=$this->model_karyawan->getTunjanganGradeKetegori($gradeKar,'KAPD0004')['nominal'];
																			if(!empty($tun_um)){
																				$tabel.='<td>'.$this->formatter->getFormatMoneyUser($tun_um['pagi']).'</td>'; 
																				$tabel.='<td>'.$this->formatter->getFormatMoneyUser($tun_um['siang']).'</td>'; 
																				$tabel.='<td>'.$this->formatter->getFormatMoneyUser($tun_um['malam']).'</td>'; 
																				if($data['plant']=='plant' && isset($tun_um['lembur'])){
																					$tabel.='<td>'.$this->formatter->getFormatMoneyUser($tun_um['lembur']).'</td>'; 
																					$nominal_pd = $tun_um['pagi']+$tun_um['siang']+$tun_um['malam']+$tun_um['lembur'];
																				}else{
																					$nominal_pd = $tun_um['pagi']+$tun_um['siang']+$tun_um['malam'];
																				}
																			}
																			$kode_tunjangan = (!empty($w->val_tunjangan)?$w->val_tunjangan:$w->tunjangan);
																			$basar_tunjangan = (!empty($w->val_besar_tunjangan)?$w->val_besar_tunjangan:$w->besar_tunjangan);
																			$tunJax=$this->otherfunctions->getDataTunjanganPerdin($kode_tunjangan,$basar_tunjangan);
																			if(!empty($tunJax)){
																				foreach ($tunJax as $key => $valx) {
																					$tabel.='<td>'.$this->formatter->getFormatMoneyUser($valx).'</td>'; 
																					$na=$na+$valx;
																				}
																			}
																			$tabel.='<td>'.$this->formatter->getFormatMoneyUser($na).'</td>
																			</tr>';
																			$no++;
																			$total+=$na;
																			$dKor = $this->model_karyawan->getKoreksiPerdin(['no_sk'=>$kode, 'id_karyawan'=>$w->id_karyawan], true);
																			$tunUM = explode(';', $dKor['val_tunjangan_um']);
																			$tunUMPagi = (isset($tunUM[0]) ? $tunUM[0] : 0);
																			$tunUMSiang = (isset($tunUM[1]) ? $tunUM[1] : 0);
																			$tunUMMalam = (isset($tunUM[2]) ? $tunUM[2] : 0);
																			$tunUMLembur = (isset($tunUM[3]) ? $tunUM[3] : 0);
																			$umPagix = str_replace('pagi:', '', $tunUMPagi);
																			$umPagi = $this->formatter->getFormatMoneyUser($umPagix);
																			$umSiangx = str_replace('siang:', '', $tunUMSiang);
																			$umSiang = $this->formatter->getFormatMoneyUser($umSiangx);
																			$umMalamx = str_replace('malam:', '', $tunUMMalam);
																			$umMalam = $this->formatter->getFormatMoneyUser($umMalamx);
																			$umLemburx = str_replace('lembur:', '', $tunUMLembur);
																			$umLembur = $this->formatter->getFormatMoneyUser($umLemburx);
																			$umPagix = (!empty($umPagix)) ? $umPagix : 0;
																			$umSiangx = (!empty($umSiangx)) ? $umSiangx : 0;
																			$umMalamx = (!empty($umMalamx)) ? $umMalamx : 0;
																			$umLemburx = (!empty($umLemburx)) ? $umLemburx : 0;
																			$totalUMKoreksix = $umPagix+$umSiangx+$umMalamx+$umLemburx;
																			$totalAkhirx = 0;
																			$totalAkhirx=$totalAkhirx+$totalUMKoreksix;
																			$tabel.='<tr>
																				<td></td>
																				<td colspan="2"><b>KOREKSI</b></td>';
																				if(!empty($tun_um)){
																					$tabel.='<td>
																					<input type="hidden" name="id_karyawan[]" value="'.$w->id_karyawan.'">
																					<input type="text" name="um_pagi[]" class="input-money" value="'.$umPagi.'">
																					</td>'; 
																					$tabel.='<td>
																					<input type="text" name="um_siang[]" class="input-money" value="'.$umSiang.'">
																					</td>'; 
																					$tabel.='<td>
																					<input type="text" name="um_malam[]" class="input-money" value="'.$umMalam.'">
																					</td>'; 
																				}
																				if($data['plant']=='plant' && isset($tun_um['lembur'])){
																					$tabel.='<td>
																					<input type="text" name="um_tambahan[]" class="input-money" value="'.$umLembur.'">
																					</td>'; 
																				}
																				$dTunjangan = explode(';', $dKor['val_tunjangan']);
																				$nominalTunjangan = explode(';', $dKor['val_besar_tunjangan']);
																				$dtn=[];
																				foreach ($dTunjangan as $k => $v) {
																					$dtn[$v] = $nominalTunjangan[$k];
																				}
																				if(!empty($tunJax)){
																					foreach ($tunJax as $key => $valx) {
																						$readonly = ($key == 'UM') ? ' readonly="readonly"' : null;
																						if($key == 'UM'){
																							$nominalTUnjangan = $this->formatter->getFormatMoneyUser($totalUMKoreksix);
																						}else{
																							$nominalTUnjangan = (isset($dtn[$key])) ? $this->formatter->getFormatMoneyUser($dtn[$key]) : null;
																						}
																						$totalAkhirx=$totalAkhirx+((isset($dtn[$key])) ? $dtn[$key] : 0);
																						$tabel.='<td>
																						<input type="text" name="'.$key.'[]" class="input-money" '.$readonly.' value="'.$nominalTUnjangan.'">
																						</td>'; 
																					}
																				}
																				$tabel.='<td><b>'.$this->formatter->getFormatMoneyUser($totalAkhirx).'</b></td>'; 
																			$tabel.='</tr>';
																			$totalKoreksi+=$totalAkhirx;
																		}
																	}
																$tabel.='</tbody>
																<tbody>
																	<tr>
																		<td colspan="3"><h4><b>TOTAL SEBELUM DI KOREKSI</b></h4></td>
																		<td colspan="8"><h4><b>'.$this->formatter->getFormatMoneyUser($total).'</b></h4></td>
																	</tr>
																	<tr>
																		<td colspan="3"><h4><b>TOTAL SETELAH DI KOREKSI</b></h4></td>
																		<td colspan="8"><h4><b>'.$this->formatter->getFormatMoneyUser($totalKoreksi).'</b></h4></td>
																	</tr>
																</tbody>
															</table>';
														// $tabel.='<h4 align="right">Total Dana Untuk Karyawan <b>'.$this->formatter->getFormatMoneyUser($total).'</b></h4>';
													}else{
														$adaDriver += 1;
														$namaDriver = $data['id_karyawan'];
													}
													echo $tabel;
													// }
												?>
												</div>
											</div>
											<div class="col-md-12">
												<hr>
												<button type="submit" class="btn btn-success pull-right"><i class="fa fa-floppy-o"> </i> Simpan</button>
											</div>
										</div>
									<?php echo form_close(); ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>	
</div>
<script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
<script type="text/javascript">
	var url_select="<?php echo base_url('global_control/select2_global');?>";
	var table="data_perjalanan_dinas";
	var column="id_pd";
	$(document).ready(function(){
	});
</script> 
