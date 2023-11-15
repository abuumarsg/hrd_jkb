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
</style>
<div class="content-wrapper">
	<section class="content-header">
		<h1>
			<i class="fas fa-car"></i> Data
			<small>Perjalanan Dinas</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fa fas fa-tachometer-alt"></i> Dashboard</a></li>
			<li class="active"><i class="fas fa-car"></i> Perjalanan Dinas</li>
		</ol>
	</section>
	<section class="content">
		<div class="row"><div class="col-md-12">
				<div class="box box-info">
					<div class="box-header with-border">
						<h3 class="box-title"><i class="fas fa-car"></i> Data Perjalanan Dinas</h3>
						<div class="box-tools pull-right">
							<button class="btn btn-box-tool" onclick="reload_table('table_data_trans')" data-toggle="tooltip" title="Refresh Table"><i class="fas fa-sync"></i></button>
							<button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
							<button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i class="fa fa-times"></i></button>
						</div>
					</div>
					<div class="box-body">
						<div class="nav-tabs-custom">
							<ul class="nav nav-tabs">
								<li class="active"><a onclick="per_trans('all')" href="#per_trans" data-toggle="tab"><i class="fa fa-edit"></i> Per Transaksi</a></li>
								<li><a onclick="tableData('all')" href="#per_kar" data-toggle="tab"><i class="fas fa-car"></i> Per Karyawan</a></li>
							</ul>
							<div class="tab-content">
								<div class="tab-pane active" id="per_trans">
									<div class="box-header with-border">
										<h3 class="box-title"><i class="fa fa-search"></i> Advance Filter Data</h3>
										<div class="box-tools pull-right">
											<button class="btn btn-box-tool" onclick="refreshData()" data-toggle="tooltip" title="Refresh Data"><i class="fas fa-sync"></i></button>
										</div>
									</div>
									<div style="padding-top: 10px;">
										<form id="form_filter_trans">
											<input type="hidden" name="param" value="all">
											<input type="hidden" name="mode" value="data">
											<div class="box-body">
												<div class="col-md-4">
													<div class="form-group">
														<label>Pilih Bagian</label>
														<select class="form-control select2" id="bagian_export_trans" name="bagian_export_trans" style="width: 100%;"></select>
													</div>
												</div>
												<div class="col-md-4">
													<div class="form-group">
														<label>Pilih Lokasi Kerja</label>
														<select class="form-control select2" id="unit_export_trans" name="unit_export_trans" style="width: 100%;"></select>
													</div>
												</div>
												<div class="col-md-4">
													<div class="">
														<label>Tanggal</label>
														<div class="has-feedback">
															<span class="fa fa-calendar form-control-feedback"></span>
															<input type="text" class="form-control date-range-notime" id="tanggal_filter_trans" name="tanggal_filter" placeholder="Tanggal">
														</div>
													</div>
												</div>
											</div>
											<div class="box-footer">
												<div class="col-md-12">
													<div class="pull-right">
														<button type="button" onclick="per_trans('search')" class="btn btn-success"><i class="fa fa-eye"></i> Lihat Data</button>
													</div>
												</div>
											</div>
										</form>
									</div>
									<div id="accordion">
										<div class="panel">
										<?php if (in_array($access['l_ac']['add'], $access['access'])) {
											echo '<a href="#tambah" data-toggle="collapse" id="btn_tambah" data-parent="#accordion" class="btn btn-success "><i class="fa fa-plus"></i> Tambah Perjalanan Dinas</a> ';}
											if (in_array($access['l_ac']['rkp'], $access['access'])) {
												echo '<button type="button" onclick="rekap_trans()" class="btn btn-warning" ><i class="fa fa-file-excel-o"></i> Export Data</button>';}
											if (in_array($access['l_ac']['add'], $access['access'])) { ?>
											<div id="tambah" class="collapse">
												<br>
												<div class="box box-success">
													<div class="box-header with-border">
														<h3 class="box-title"><i class="fa fa-plus"></i> Tambah Perjalanan Dinas</h3>
													</div>
													<form id="form_add" class="form-horizontal">
														<div class="box-body">
															<div class="callout callout-info"><label><i class="fa fa-info-circle"></i> Bantuan</label><br> Untuk meminimalisir Nomor Surat Sama, Silahkan klik <b><i class="fa fa-refresh"></i></b> disamping kolom nomor surat sebelum menyimpan</div>
															<div class="col-md-6">
																<div class="form-group">
																	<label class="col-sm-3 control-label">Nomor Perjalanan Dinas</label>
																	<div class="col-sm-8">
																		<input type="text" name="no_sk" id="no_sk_add" class="form-control" placeholder="Nomor SK" required="required">
																	</div>
																	<div class="col-sm-1">
																		<span class="control-label" style="vertical-align: middle;"><i class="fa fa-refresh" style="font-size: 16px;cursor:pointer;" onclick="refreshCode()"></i></span>
																	</div>
																</div>
																<div class="form-group">
																	<label class="col-sm-3 control-label">Pilih Karyawan</label>
																	<div class="col-sm-9">
																		<select class="form-control select2" name="id_karyawan[]" id="karyawan_add" required="required" style="width: 100%;" multiple="multiple"></select>
																	</div>
																</div>
																<div class="form-group">
																	<label class="col-sm-3 control-label">Berangkat -<br>Sampai Lokasi</label>
																	<div class="col-sm-9">
																		<div class="has-feedback">
																			<span class="fa fa-calendar form-control-feedback"></span>
																			<input type="text" name="tanggal" class="form-control pull-right date-range-30" placeholder="Tanggal Berangkat" readonly="readonly">
																			<!-- <input type="text" name="tanggal" class="form-control pull-right dateRangeNoSecond" placeholder="Tanggal Berangkat" readonly="readonly"> -->
																		</div>
																	</div>
																</div>
																<div class="form-group">
																	<label class="col-sm-3 control-label">Tanggal Pulang <br>(Sampai Lokasi)</label>
																	<div class="col-sm-9">
																		<div class="has-feedback">
																			<span class="fa fa-calendar form-control-feedback"></span>
																			<input type="text" name="tanggal_pulang" class="form-control pull-right datetimepicker" placeholder="Tanggal Pulang" readonly="readonly">
																		</div>
																	</div>
																</div>
																<div class="form-group">
																	<label class="col-sm-3 control-label">Pilih Plant Asal</label>
																	<div class="col-sm-9">
																		<select class="form-control select2" name="plant_asal" id="data_plant_asal_add" style="width: 100%;"></select>
																	</div>
																</div>
																<div class="form-group">
																	<label class="col-sm-3 control-label">Tujuan</label>
																	<div class="col-sm-9">
																		<?php
																		$tujuan[null] = 'Pilih Data';
																		$sel1 = [null];
																		$exsel1 = array('class'=>'form-control select2','placeholder'=>'Tujuan','id'=>'tujuan','required'=>'required','style'=>'width:100%','onchange'=>'tujuanPD(this.value)');
																		echo form_dropdown('tujuan',$tujuan,$sel1,$exsel1);
																		?>
																	</div>
																</div>
																<div class="form-group" id="tujuan_plant" style="display:none;">
																	<label class="col-sm-3 control-label">Pilih Plant Tujuan</label>
																	<div class="col-sm-9">
																		<select class="form-control select2" name="plant_tujuan" id="data_plant_tujuan_add" style="width: 100%;"></select>
																	</div>
																</div>
																<div id="tujuan_non_plant" style="display:none;">
																	<div class="form-group">
																		<label class="col-sm-3 control-label">Lokasi Tujuan</label>
																		<div class="col-sm-9">
																			<input type="text" name="lokasi_tujuan" id="data_lokasi_tujuan_add" class="form-control pull-right" placeholder="Lokasi Tujuan">
																		</div>
																	</div>
																	<div class="form-group">
																		<label class="col-sm-3 control-label">Estimasi Jarak PP</label>
																		<div class="col-sm-9">
																			<input type="number" nim="0" name="jarak" id="data_jarak_add" class="form-control pull-right" placeholder="Estimasi Jarak PP">
																		</div>
																	</div>
																</div>
																<div class="form-group">
																	<label class="col-sm-3 control-label">Kendaraan</label>
																	<div class="col-sm-9">
																		<select class="form-control select2" name="kendaraan" id="data_kendaraan_add" onchange="kendaraanPD(this.value)" style="width: 100%;"></select>
																	</div>
																</div>
																<div class="form-group">
																	<label class="col-sm-3 control-label">Jumlah Kendaraan</label>
																	<div class="col-sm-9">
																		<input type="number" nim="0" name="jum_kendaraan" id="data_jum_ken_add" class="form-control pull-right" placeholder="Jumlah Kendaraan">
																	</div>
																</div>
																<div class="form-group" id="nama_kendaraan_umum" style="display:none;">
																	<label class="col-sm-3 control-label">Nama Kendaraan Umum</label>
																	<div class="col-sm-9">
																		<?php
																		$kendaraan_umum[null] = 'Pilih Data';
																		$sel = [null];
																		$exsel = array('class'=>'form-control select2','placeholder'=>'Kendaraan Umum','id'=>'data_kendaraan_umum_add','required'=>'required','style'=>'width:100%');
																		echo form_dropdown('kendaraan_umum',$kendaraan_umum,$sel,$exsel);
																		?>
																	</div>
																</div>
																<div class="form-group">
																	<label class="col-sm-3 control-label">Biaya Transport / Nominal BBM PP</label>
																	<div class="col-sm-9">
																		<input type="text" name="nominal_bbm" id="data_bbm_add" class="form-control pull-right input-money" placeholder="Biaya Transport / Nominal BBM PP">
																	</div>
																</div>
																<div class="form-group">
																	<label class="col-sm-3 control-label">Total Transport / Nominal BBM PP</label>
																	<div class="col-sm-9">
																		<input type="text" name="total_nominal_bbm" id="data_total_bbm_add" class="form-control pull-right input-money" placeholder="TOTAL Biaya Transport / Nominal BBM PP" readonly="readonly">
																	</div>
																</div>
																<!-- <div id="nominal_bbm" style="display:none;">
																	<div class="form-group">
																		<label class="col-sm-3 control-label">Biaya Transport PP</label>
																		<div class="col-sm-9">
																			<input type="text" name="nominal_bbm" id="data_bbm_add" class="form-control pull-right input-money" placeholder="Biaya Transport PP">
																		</div>
																	</div> -->
																	<!-- <div class="form-group">
																		<label class="col-sm-3 control-label">Diberikan Kepada</label>
																		<div class="col-sm-9">
																			<select class="form-control select2" name="diberi_kepada" id="diberi_kepada_karyawan" style="width: 100%;"></select>
																		</div>
																	</div>
																</div> -->
															</div>
															<div class="col-md-6">
																<div class="form-group">
																	<label class="col-sm-3 control-label" style="vertical-align: middle;">Menginap</label>
																	<div class="col-sm-9">
																		<span id="menginap_off" style="font-size: 20px;" onclick="menginapKlik();"><i class="far fa-square" aria-hidden="true"></i></span>
																		<span id="menginap_on" style="display: none; font-size: 20px;" onclick="menginapKlik();"><i class="far fa-check-square" aria-hidden="true"></i></span>&nbsp;&nbsp;
																		<span style="padding-bottom: 9px;vertical-align: middle;"><b>(Ceklist Jika Menginap)</b></span>
																		<input type="hidden" name="menginap">
																	</div>
																</div>
																<div id="penginapan_add" style="display: none;">
																	<div class="form-group">
																		<label class="col-sm-3 control-label">Penginapan</label>
																		<div class="col-sm-9">
																			<?php
																			$penginapan[null] = 'Pilih Data';
																			$sel2 = [null];
																			$exsel2 = array('class'=>'form-control select2','placeholder'=>'penginapan','id'=>'data_penginapan_add','style'=>'width:100%','onchange'=>'cekPenginapan(this.value)');
																			echo form_dropdown('penginapan',$penginapan,$sel2,$exsel2);
																			?>
																		</div>
																	</div>
																	<div class="form-group" id="kelas_hotel" style="display:none;">
																		<label class="col-sm-3 control-label">Kelas Hotel</label>
																		<div class="col-sm-9">
																			<select class="form-control select2" name="kelas_hotel" id="data_kelas_hotel_add" style="width: 100%;" onchange=cekTipeHotel(this.value)></select>
																			<span id="div_span_ket"></span>
																		</div>
																	</div>
																	<div class="form-group" id="nominal_penginapan" style="display:none;">
																		<label class="col-sm-3 control-label">Biaya Penginapan</label>
																		<div class="col-sm-9">
																			<input type="text" name="nominal_penginapan" id="data_nominal_penginapan_add" class="form-control pull-right input-money" placeholder="Nominal Penginapan">
																			<span id="div_span_nominal"></span>
																		</div>
																	</div>
																	<div class="form-group" id="jumlah_kamar" style="display:none;">
																		<label class="col-sm-3 control-label">Jumlah Kamar</label>
																		<div class="col-sm-9">
																			<input type="number" name="jumlah_kamar" id="data_jumlah_kamar_add" class="form-control pull-right" placeholder="Jumlah Kamar">
																		</div>
																	</div>
																	<div class="form-group" id="jumlah_hari" style="display:none;">
																		<label class="col-sm-3 control-label">Jumlah Hari</label>
																		<div class="col-sm-9">
																			<input type="number" name="jumlah_hari" id="data_jumlah_hari_add" class="form-control pull-right" placeholder="Jumlah Hari">
																		</div>
																	</div>
																	<div class="form-group" id="total_penginapan" style="display:none;">
																		<label class="col-sm-3 control-label">Total Biaya Penginapan</label>
																		<div class="col-sm-9">
																			<input type="text" name="total_penginapan" id="data_total_penginapan_add" class="form-control pull-right input-money" placeholder="Total Biaya Penginapan" readonly="readonly">
																		</div>
																	</div>
																</div>
																<!-- <div class="form-group">
																	<label class="col-sm-3 control-label">Tunjangan</label>
																	<div class="col-sm-9">
																		<select class="form-control select2" multiple="multiple" name="tunjangan[]" id="data_tunjangan_add" style="width: 100%;"></select>
																	</div>
																</div> -->
																<div class="form-group">
																	<label class="col-sm-3 control-label" style="vertical-align: middle;">Driver</label>
																	<div class="col-sm-9">
																		<span id="driver_off" style="font-size: 20px;"><i class="far fa-square" aria-hidden="true"></i></span>
																		<span id="driver_on" style="display: none; font-size: 20px;"><i class="far fa-check-square" aria-hidden="true"></i></span>&nbsp;&nbsp;
																		<span style="padding-bottom: 9px;vertical-align: middle;"><b>(Ceklist jika dengan Driver lain selain karyawan)</b></span>
																		<input type="hidden" name="driver">
																	</div>
																</div>
																<div class="form-group" id="div_driver" style="display:none;">
																	<label class="col-sm-3 control-label">Nama Driver</label>
																	<div class="col-sm-9">
																		<input type="text" name="nama_driver" class="form-control pull-right" placeholder="Masukkan Nama Driver">
																	</div>
																</div>
																<div class="form-group">
																	<label class="col-sm-3 control-label">Jenis Perdin</label>
																	<div class="col-sm-9">
																		<select class="form-control select2" name="jenis_perdin" id="data_jenis_perdin_add" required="required" style="width: 100%;"></select>
																	</div>
																</div>
																<div class="form-group">
																	<label class="col-sm-3 control-label">Tugas</label>
																	<div class="col-sm-9">
																		<textarea name="tugas" class="form-control" required="required" placeholder="Keterangan Tugas Perjalanan Dinas"></textarea>
																	</div>
																</div>
																<div class="form-group">
																	<label class="col-sm-3 control-label">Mengetahui</label>
																	<div class="col-sm-9">
																		<select class="form-control select2" name="mengetahui" id="data_mengetahui_add" required="required" style="width: 100%;"></select>
																	</div>
																</div>
																<div class="form-group">
																	<label class="col-sm-3 control-label">Menyetujui</label>
																	<div class="col-sm-9">
																		<select class="form-control select2" name="menyetujui" id="data_menyetujui_add" required="required" style="width: 100%;"></select>
																	</div>
																</div>
																<!-- <div class="form-group">
																	<label class="col-sm-3 control-label">Dibuat Oleh</label>
																	<div class="col-sm-9">
																		<select class="form-control select2" name="dibuat" id="data_dibuat_add" required="required" style="width: 100%;"></select>
																	</div>
																</div> -->
																<div class="form-group">
																	<label class="col-sm-3 control-label">Keterangan </label>
																	<div class="col-sm-9">
																		<textarea name="keterangan" class="form-control" placeholder="Keterangan"></textarea>
																	</div>
																</div>
															</div>
															<div class="col-md-12">
																<button type="button" class="btn btn-warning" id="btn_tunjangan" href="javascript:void(0)" onclick="view_tunjangan()"><i class="fa fa-eye"></i> Lihat Detail Tunjangan</button>
															</div>
														</div>
														<div class="box-footer">
															<div class="pull-right">
																<button type="submit" id="btn_add" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
															</div>
														</div>
													</form>
												</div>
											</div>
										<?php } ?>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<div class="callout callout-info"><label><i class="fa fa-info-circle"></i> Bantuan</label><br>
											Data yang ditampilkan adalah per transaksi perjalanan dinas.<br>Pilih <b>NIK</b> (Nomor Induk Karyawan) untuk melihat detail Peringatan maupun melakukan update pada data Perjalanan Dinas</div>
											<table id="table_data_trans" class="table table-bordered table-striped" width="100%">
												<thead>
													<tr>
														<th>No</th>
														<th>Nomor</th>
														<th>Tanggal Perjalanan Dinas</th>
														<th>Dari</th>
														<th>Tujuan</th>
														<th>Jumlah</th>
														<th>Status Validasi</th>
														<th>Status Perjalanan</th>
														<th>Aksi</th>
													</tr>
												</thead>
												<tbody>
												</tbody>
											</table>
										</div>
									</div>
								</div>
								<div class="tab-pane" id="per_kar">
									<div class="box-header with-border">
										<h3 class="box-title"><i class="fa fa-search"></i> Advance Filter Data</h3>
										<div class="box-tools pull-right">
											<button class="btn btn-box-tool" onclick="refreshDataKar()" data-toggle="tooltip" title="Refresh Data"><i class="fas fa-sync"></i></button>
										</div>
									</div>
									<div style="padding-top: 10px;">
										<form id="form_filter">
											<input type="hidden" name="param" value="all">
											<input type="hidden" name="mode" value="data">
											<div class="box-body">
												<div class="col-md-4">
													<div class="form-group">
														<label>Pilih Bagian</label>
														<select class="form-control select2" id="bagian_export" name="bagian_export" style="width: 100%;"></select>
													</div>
												</div>
												<div class="col-md-4">
													<div class="form-group">
														<label>Pilih Lokasi Kerja</label>
														<select class="form-control select2" id="unit_export" name="unit_export" style="width: 100%;"></select>
													</div>
												</div>
												<div class="col-md-4">
													<div class="form-group">
														<label>Tanggal</label>
															<span class="fa fa-calendar form-control-feedback"></span>
															<input type="text" name="tanggal_filter" id="tanggal_filter" class="form-control pull-right date-range-notime" placeholder="Tanggal" readonly="readonly">
													</div>									
												</div>
											</div>
											<div class="box-footer">
												<div class="col-md-12">
													<div class="pull-right">
														<button type="button" onclick="tableData('search')" class="btn btn-success"><i class="fa fa-eye"></i> Lihat Data</button>
													</div>
												</div>
											</div>
										</form>
									</div>
									<div id="accordion_kar">
										<div class="panel">
											<?php 
												if (in_array($access['l_ac']['rkp'], $access['access'])) {
													echo '<input type="hidden" name="param" value="all">';
													echo '<button type="button" onclick="rekap()" id="btn_print_excel" class="btn btn-warning" ><i class="fa fa-file-excel-o"></i> Export Data</button>';
												}
											?>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<div class="callout callout-info"><label><i class="fa fa-info-circle"></i> Bantuan</label><br>Pilih <b>NIK</b> (Nomor Induk Karyawan) untuk melihat detail Peringatan maupun melakukan update pada data Perjalanan Dinas</div>
											<table id="table_data" class="table table-bordered table-striped" width="100%">
												<thead>
													<tr>
														<th>No</th>
														<th>NIK</th>
														<th>Nama</th>
														<th>Nomor</th>
														<th>Tanggal Perjalanan Dinas</th>
														<th>Tujuan</th>
														<th>Jumlah Data</th>
														<th>Aksi</th>
													</tr>
												</thead>
												<tbody>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>	
</div>
<div id="view_modal_tunjangan" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close all_btn_compare" data-dismiss="modal">&times;</button>
				<h2 class="modal-title">Detail Tunjangan Perjalanan Dinas</h2>
			</div>
			<div class="modal-body">
				<div class="row" id="total_tunjangan" style="display:none;">
					<div class="col-md-12">
						<div class="col-md-6">
							<div class="form-group col-md-12">
								<label class="col-md-6 control-label">Kendaraan</label>
								<div class="col-md-6" id="data_kendaraan_all"></div>
							</div>
							<div class="form-group col-md-12">
								<label class="col-md-6 control-label">Nominal Per Kendaraan</label>
								<div class="col-md-6" id="nominal_bbm_per_ken"></div>
							</div>
							<div class="form-group col-md-12">
								<label class="col-md-6 control-label">Jumlah Kendaraan</label>
								<div class="col-md-6" id="jumlah_kendaraan"></div>
							</div>
							<div class="form-group col-md-12">
								<label class="col-md-6 control-label">Biaya Transport PP</label>
								<div class="col-md-6" id="nominal_bbm_all"></div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group col-md-12">
								<label class="col-md-6 control-label">Penginapan</label>
								<div class="col-md-6" id="data_tunjangan_all"></div>
							</div>
							<div class="form-group col-md-12">
								<label class="col-md-6 control-label">Nominal Penginapan</label>
								<div class="col-md-6" id="nominal_tunjangan_view_tun"></div>
							</div>
							<div class="form-group col-md-12">
								<label class="col-md-6 control-label">Jumlah Kamar</label>
								<div class="col-md-6" id="jumlah_kamar_view_tunjangan"></div>
							</div>
							<div class="form-group col-md-12">
								<label class="col-md-6 control-label">Jumlah Hari</label>
								<div class="col-md-6" id="jumlah_hari_view_tunjangan"></div>
							</div>
							<div class="form-group col-md-12">
								<label class="col-md-6 control-label">Total Nominal Penginapan</label>
								<div class="col-md-6" id="nominal_tunjangan_all"></div>
							</div>
						</div>
					</div>
					<hr>
					<div class="col-md-12">
						<h4 align="right">Total Dana Untuk Akomodasi <b id="total_akomodasi_add"></b></h4>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div id="data_tabel_tunjangan"></div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
			</div>
		</div>
	</div>
</div>
<!-- view -->
<div id="view" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h2 class="modal-title">Detail Data <b class="text-muted header_data"></b></h2>
				<input type="hidden" name="data_id_view">
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">NIK</label>
							<div class="col-md-6" id="data_nik_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Nama</label>
							<div class="col-md-6" id="data_nama_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Lokasi</label>
							<div class="col-md-6" id="data_loker_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Jabatan</label>
							<div class="col-md-6" id="data_jabatan_view"></div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Status</label>
							<div class="col-md-6" id="data_status_view">
							</div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Dibuat Tanggal</label>
							<div class="col-md-6" id="data_create_date_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Diupdate Tanggal</label>
							<div class="col-md-6" id="data_update_date_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Dibuat Oleh</label>
							<div class="col-md-6" id="data_create_by_view">
							</div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Diupdate Oleh</label>
							<div class="col-md-6" id="data_update_by_view">
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div id="data_tabel_view"></div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
			</div>
		</div>
	</div>
</div>
<div id="view_u" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h2 class="modal-title">Detail Data <b class="text-muted header_data"></b></h2>
				<input type="hidden" name="data_kode_view">
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Nomor Perjalanan Dinas</label>
							<div class="col-md-6" id="data_nosk_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Mengetahui</label>
							<div class="col-md-6" id="data_mengetahui_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Menyetujui</label>
							<div class="col-md-6" id="data_menyetujui_view"></div>
						</div>
						<!-- <div class="form-group col-md-12">
							<label class="col-md-6 control-label">Dibuat</label>
							<div class="col-md-6" id="data_dibuat_view"></div>
						</div> -->
					</div>
					<div class="col-md-6">
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Status</label>
							<div class="col-md-6" id="data_status_trans"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Dibuat Tanggal</label>
							<div class="col-md-6" id="data_create_date_trans"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Diupdate Tanggal</label>
							<div class="col-md-6" id="data_update_date_trans"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Dibuat Oleh</label>
							<div class="col-md-6" id="data_create_by_trans">
							</div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Diupdate Oleh</label>
							<div class="col-md-6" id="data_update_by_trans">
							</div>
						</div>
					</div>
				</div>
				<hr>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Tanggal Berangkat</label>
							<div class="col-md-6" id="data_berangkat_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Tanggal Sampai</label>
							<div class="col-md-6" id="data_sampai_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Tanggal Pulang</label>
							<div class="col-md-6" id="data_pulang_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Plant Asal</label>
							<div class="col-md-6" id="data_asal_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Tujuan</label>
							<div class="col-md-6" id="data_tujuan_view"></div>
						</div>
						<div class="form-group col-md-12" id="jarak_view">
							<label class="col-md-6 control-label">Jarak</label>
							<div class="col-md-6" id="data_jarak_view"></div>
						</div>	
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Kendaraan</label>
							<div class="col-md-6" id="data_kedaraan_view"></div>
						</div>				
					</div>
					<div class="col-md-6">
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Jumlah Kendaraan</label>
							<div class="col-md-6" id="data_jumlah_kendaraan_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Biaya Transport / Nominal BBM Per Kendaraan</label>
							<div class="col-md-6" id="data_nominal_per_ken_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Biaya Transport / Nominal BBM PP</label>
							<div class="col-md-6" id="data_nominal_bbm_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Nominal Penginapan</label>
							<div class="col-md-6" id="data_nominal_penginapan_view"></div>
						</div>
						<!-- <div class="form-group col-md-12">
							<label class="col-md-6 control-label">Menginap</label>
							<div class="col-md-6" id="data_menginap_view"></div>
						</div> -->
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Penginapan</label>
							<div class="col-md-6" id="data_nama_penginapan_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Jumlah Kamar</label>
							<div class="col-md-6" id="data_jumlah_kamar_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Jumlah Hari</label>
							<div class="col-md-6" id="data_jumlah_hari_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Total Biaya Penginapan</label>
							<div class="col-md-6" id="data_total_penginapan_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Tugas</label>
							<div class="col-md-6" id="data_tugas_view"></div>
						</div>	
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Keterangan</label>
							<div class="col-md-6" id="data_keterangan_view"></div>
						</div>						
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<h4 align="right">Total Dana Untuk Akomodasi <b id="total_akomodasi_view"></b></h4>
					</div>
				</div>
				<div class="row" id="div_data_after_val" style="display:none;">
					<div class="col-md-12">
						<!-- <div class="data_detail" id="data_after_val"></div> -->
						<div id="data_after_val"></div>
					</div>
				</div>
				<div id="tabel_tunjangan"></div>
				<div class="row" id="div_endProses_val" style="display:none;">
					<div class="col-md-12">
						<div id="endProses_val"></div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<?php 
					if (in_array($access['l_ac']['val_perdin'], $access['access'])) {
						echo '<button id="btn_proses" type="button" class="btn btn-warning" onclick="modal_proses()" style="display:none:"><i class="fa fa-refresh fa-spin"></i> Selesaikan Perjalanan</button>';
					}
				?>
				<?php if (in_array($access['l_ac']['edt'], $access['access'])) {
					echo '<button type="button" id="tombol_edit" class="btn btn-info" onclick="edit_modal_u()" style="display:none;"><i class="fa fa-edit"></i> Edit</button>';
				}?>
				<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
			</div>
		</div>
	</div>
</div>
<?php if (in_array($access['l_ac']['val_perdin'], $access['access'])) { ?>
	<div id="proses_end" class="modal fade" role="dialog">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h2 class="modal-title">Proses End Data <b class="text-muted header_data"></b></h2>
				</div>
				<form id="form_proses_end">
					<div class="modal-body">
						<div class="row">
							<div class="col-md-12">
								<div class="form-group clearfix">
									<label>Kode Transaksi</label>
									<input type="text" placeholder="Masukkan Kode Transaksi" id="data_kode_trans" name="kode_trans" value="" class="form-control" required="required" readonly="readonly">
								</div>
								<div id="tabel_end_proses">
								</div>
								<br>
								<button type="button" class="btn btn-success" onclick="myFunction()"><i class="fa fa-plus"></i> Add</button>
								<button type="button" class="btn btn-danger" onclick="deleterow()"><i class="fa fa-trash"></i> Delete</button>
							</div>
						</div>
						<br>
					</div>
					<div class="modal-footer">
						<button type="button" onclick="do_end_proses()" id="btn_edit_end" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
						<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
					</div>
				</form>
			</div>
		</div>
	</div>
<?php } ?>
<?php if (in_array($access['l_ac']['val_perdin'], $access['access'])) { ?>
	<div id="edit_kode_akun" class="modal fade" role="dialog">
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h2 class="modal-title">Edit Kode Akun <b class="text-muted header_data"></b></h2>
				</div>
				<form id="form_edit_kode_akun">
					<div class="modal-body">
						<div class="row">
							<input type="hidden" id="data_id_kode_akun" name="id">
							<div class="col-md-12">
								<div class="form-group">
									<label>Kode Perjalanan Dinas</label>
									<input type="text" class="form-control" id="data_kode_perdin_akun" name="kode_perdin" readonly="readonly">
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<label>Pilih Kode Akun</label>
									<select class="form-control select2" id="data_kode_akun_edit" name="kode_akun" style="width: 100%;"></select>
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<label>Nominal</label>
									<input type="text" class="form-control input-money" id="data_nominal_akun_edit" name="nominal">
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
									<label>Keterangan</label>
									<textarea class="form-control" id="data_keterangan_akun_edit" name="keterangan"></textarea>
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" onclick="do_edit_kode_akun()" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
						<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
					</div>
				</form>
			</div>
		</div>
	</div>
<?php } ?>
<?php if (in_array($access['l_ac']['edt'], $access['access'])) { ?>
	<div id="edit_u" class="modal fade" role="dialog">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h2 class="modal-title">Edit Data <b class="text-muted header_data"></b></h2>
				</div>
				<form id="form_edit">
					<div class="modal-body">
						<div class="row">
							<div class="col-md-12">
								<div class="col-md-6">
									<input type="hidden" id="data_id_edit" name="id" value="">
									<input type="hidden" name="flag" value="edit">
									<!-- <input type="hidden" id="data_idk_edit" name="id_karyawan[]" value=""> -->
									<div class="form-group clearfix">
										<label>NO Surat</label>
										<input type="text" placeholder="Masukkan NO Surat" id="data_nosk_edit" name="no_sk" value="" class="form-control" required="required">
									</div>
									<div class="form-group clearfix">
										<label>Tanggal Berangkat - Tanggal Sampai</label>
										<input type="text" name="tanggal" id="data_tanggal_edit" class="form-control dateRangeNoSecond" placeholder="Tanggal" readonly="readonly" required="required">
									</div>
									<div class="form-group clearfix">
										<label>Tanggal Pulang</label>
										<div class="has-feedback">
											<span class="fa fa-calendar form-control-feedback"></span>
											<input type="text" name="tanggal_pulang" id="data_tanggal_pulang_edit" class="form-control datetimepicker" placeholder="Tanggal Pulang" readonly="readonly">
										</div>
									</div>
									<div class="form-group clearfix">
										<label>Karyawan</label>
										<select class="form-control select2" name="id_karyawan[]" id="karyawan_edit_trans" required="required" style="width: 100%;" multiple="multiple"></select>
									</div>
									<!-- <select class="form-control select2" type="hidden" name="karyawan_old[]" id="karyawan_old" style="width: 100%;" multiple="multiple"></select> -->
									<input type="hidden" name="karyawan_old" id="karyawan_old">
									<input type="hidden" name="no_sk_old" id="no_sk_old">
									<div class="form-group clearfix">
										<label>Pilih Plant Asal</label>
										<select class="form-control select2" name="plant_asal" id="data_plant_asal_edit" style="width: 100%;"></select>
									</div>
									<div class="form-group clearfix">
										<label>Tujuan</label>
										<?php
										$tujuan[null] = 'Pilih Data';
										$sel1 = [null];
										$exsel1 = array('class'=>'form-control select2','placeholder'=>'Tujuan','id'=>'tujuan_edit','required'=>'required','style'=>'width:100%','onchange'=>'tujuanPDEdit(this.value)');
										echo form_dropdown('tujuan',$tujuan,$sel1,$exsel1);
										?>
									</div>
									<div class="form-group clearfix" id="tujuan_plant_edit" style="display:none;">
										<label>Pilih Plant Tujuan</label>
										<select class="form-control select2" name="plant_tujuan" id="data_plant_tujuan_edit" style="width: 100%;"></select>
									</div>
									<div id="tujuan_non_plant_edit" style="display:none;">
										<div class="form-group clearfix">
											<label>Lokasi Tujuan</label>
											<input type="text" name="lokasi_tujuan" id="data_lokasi_tujuan_edit" class="form-control pull-right" placeholder="Lokasi Tujuan">
										</div>
										<div class="form-group clearfix">
											<label>Estimasi Jarak PP</label>
											<input type="number" nim="0" name="jarak" id="data_jarak_edit" class="form-control pull-right" placeholder="Estimasi Jarak PP">
										</div>
									</div>
									<div class="form-group clearfix">
										<label>Kendaraan</label>
										<select class="form-control select2" name="kendaraan" id="data_kendaraan_edit" onchange="kendaraanPDEdit(this.value)" style="width: 100%;"></select>
									</div>
									<div class="form-group clearfix">
										<label>Jumlah Kendaraan</label>
											<input type="number" nim="0" name="jum_kendaraan" id="data_jum_ken_edit" class="form-control pull-right" placeholder="Jumlah Kendaraan">
									</div>
									<div class="form-group" id="nama_kendaraan_umum_edit" style="display:none;">
										<label>Nama Kendaraan Umum</label>
										<?php
										$kendaraan_umum[null] = 'Pilih Data';
										$sel2 = [null];
										$exsel2 = array('class'=>'form-control select2','placeholder'=>'Kendaraan Umum','id'=>'data_kendaraan_umum_edit','required'=>'required','style'=>'width:100%');
										echo form_dropdown('kendaraan_umum',$kendaraan_umum,$sel2,$exsel2);
										?>
									</div>
									<div class="form-group">
										<label>Biaya Transport / Nominal BBM PP</label>
										<input type="text" name="nominal_bbm" id="data_bbm_edit" class="form-control pull-right input-money" placeholder="Biaya Transport / Nominal BBM PP">
									</div>
									<div class="form-group">
										<label>Total Transport / Nominal BBM PP</label>
										<input type="text" name="total_nominal_bbm" id="data_total_bbm_edit" class="form-control pull-right input-money" placeholder="TOTAL Biaya Transport / Nominal BBM PP" readonly="readonly">
									</div>
									<div id="nominal_bbm_edit" style="display:none;">
										<!-- <div class="form-group">
											<label>Biaya Transport PP</label>
												<input type="text" name="nominal_bbm" id="data_nominal_bbm_edit" class="form-control pull-right input-money" placeholder="Biaya Transport PP">
										</div> -->
										<!-- <div class="form-group">
											<label>Diberikan Kepada</label>
											<div class="col-sm-9">
												<select class="form-control select2" name="diberi_kepada" id="diberi_kepada_karyawan" style="width: 100%;"></select>
											</div>
										</div> -->
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group clearfix">
										<label style="vertical-align: middle;">Menginap</label><br>
										<span id="menginap_off_edit" style="font-size: 20px;"><i class="far fa-square" aria-hidden="true"></i></span>
										<span id="menginap_on_edit" style="display: none; font-size: 20px;"><i class="far fa-check-square" aria-hidden="true"></i></span>&nbsp;&nbsp;
										<span style="padding-bottom: 9px;vertical-align: middle;"><b>(Ceklist Jika Menginap)</b></span>
										<input type="hidden" name="menginap" id="menginap_edit">
									</div>
									<div class="form-group" id="penginapan_edit_div" style="display: none;">
										<label>Penginapan</label>
										<?php
										$penginapan[null] = 'Pilih Data';
										$sel2 = [null];
										$exsel2 = array('class'=>'form-control select2','placeholder'=>'penginapan','id'=>'data_penginapan_edit','onchange'=>'cekPenginapanEdit(this.value)','style'=>'width:100%');
										echo form_dropdown('penginapan',$penginapan,$sel2,$exsel2);
										?>
										<!-- <div id="nominal_penginapan_edit" style="display:none;"> -->
											<div class="form-group" id="kelas_hotel_edt" style="display:none;">
												<label>Kelas Hotel</label>
												<select class="form-control select2" name="kelas_hotel" id="data_kelas_hotel_edit" style="width: 100%;" onchange=cekTipeHotelEdit(this.value)></select>
												<span id="div_span_ket_edit"></span>
											</div>
											<div class="form-group" id="nominal_penginapan_edt" style="display:none;">
												<label>Biaya Penginapan</label>
												<input type="text" name="nominal_penginapan" id="data_nominal_penginapan_edit" class="form-control pull-right input-money" placeholder="Nominal Penginapan">
												<span id="div_span_nominal"></span>
											</div>
											<div class="form-group" id="jumlah_kamar_edt" style="display:none;">
												<label>Jumlah Kamar</label>
												<input type="number" name="jumlah_kamar" id="data_jumlah_kamar_edit" class="form-control pull-right" placeholder="Jumlah Kamar">
											</div>
											<div class="form-group" id="jumlah_hari_edt" style="display:none;">
												<label>Jumlah Hari</label>
												<input type="number" name="jumlah_hari" id="data_jumlah_hari_edit" class="form-control pull-right" placeholder="Jumlah Hari">
											</div>
											<div class="form-group" id="total_penginapan_edt" style="display:none;">
												<label>Total Biaya Penginapan</label>
												<input type="text" name="total_penginapan" id="data_total_penginapan_edit" class="form-control pull-right input-money" placeholder="Total Biaya Penginapan" readonly="readonly">
											</div>
											<!-- <div class="form-group">
												<label>Nominal Penginapan</label>
													<input type="text" name="nominal_penginapan" id="data_nominal_penginapan_edit" class="form-control pull-right input-money" placeholder="Nominal Penginapan">
											</div> -->
										<!-- </div> -->
									</div>
									<div class="form-group">
										<label style="vertical-align: middle;">Driver</label><br>
										<span id="driver_off_edit" style="font-size: 20px;"><i class="far fa-square" aria-hidden="true"></i></span>
										<span id="driver_on_edit" style="display: none; font-size: 20px;"><i class="far fa-check-square" aria-hidden="true"></i></span>&nbsp;&nbsp;
										<span style="padding-bottom: 9px;vertical-align: middle;"><b>(Ceklist jika dengan Driver lain selain karyawan)</b></span>
										<input type="hidden" name="driver" id="driver_edit">
									</div>
									<div class="form-group" id="div_driver_edit" style="display:none;">
										<label>Nama Driver</label>
										<input type="text" name="nama_driver" id="nama_driver_edit" class="form-control pull-right" placeholder="Masukkan Nama Driver">
									</div>
									<div class="form-group">
										<label>Jenis Perdin</label>
											<select class="form-control select2" name="jenis_perdin" id="data_jenis_perdin_edit" required="required" style="width: 100%;"></select>
									</div>
									<div class="form-group clearfix">
										<label>Mengetahui</label>
										<select class="form-control select2" name="mengetahui" id="data_mengetahui_edit" required="required" style="width: 100%;"></select>
									</div>
									<div class="form-group clearfix">
										<label>Menyetujui</label>
										<select class="form-control select2" name="menyetujui" id="data_menyetujui_edit" required="required" style="width: 100%;"></select>
									</div>
									<!-- <div class="form-group clearfix">
										<label>Dibuat</label>
										<select class="form-control select2" name="dibuat" id="data_dibuat_edit" required="required" style="width: 100%;"></select>
									</div> -->
									<div class="form-group clearfix">
										<label>Tugas</label>
										<textarea class="form-control" name="tugas" id="data_tugas_edit" required="required" placeholder="Pelanggaran"></textarea>
									</div>
									<div class="form-group clearfix">
										<label>Keterangan (Sanksi)</label>
										<textarea class="form-control" name="keterangan" id="data_keterangan_edit" placeholder="Keterangan" required="required"></textarea>
									</div>
								</div>
							</div>
						</div>
						<br>
						<div class="row">
							<div class="col-md-12">
								<div class="col-md-6">
									<button type="button" class="btn btn-warning" id="btn_tunjangan_edit" onclick="view_tunjangan_edit()"><i class="fa fa-eye"></i> Lihat Detail Tunjangan</button>
								</div>
							</div>
							<br><hr>
							<div id="total_tunjangan_edit" style="display:none;">
								<div class="row">
									<div class="col-md-6">
										<div class="form-group col-md-12">
											<label class="col-md-6 control-label">Kendaraan</label>
											<div class="col-md-6" id="data_kendaraan_all_edit"></div>
										</div><br>
										<div class="form-group col-md-12">
											<label class="col-md-6 control-label">Jumlah Kendaraan</label>
											<div class="col-md-6" id="nominal_jum_ken_edit"></div>
										</div><br>
										<div class="form-group col-md-12">
											<label class="col-md-6 control-label">Biaya Transport PP Per Kendaraan</label>
											<div class="col-md-6" id="nominal_per_ken_edit"></div>
										</div><br>
										<div class="form-group col-md-12">
											<label class="col-md-6 control-label">Biaya Transport PP</label>
											<div class="col-md-6" id="nominal_bbm_all_edit"></div>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group col-md-12">
											<label class="col-md-6 control-label">Penginapan</label>
											<div class="col-md-6" id="data_tunjangan_all_edit"></div>
										</div><br>
										<div class="form-group col-md-12">
											<label class="col-md-6 control-label">Nominal Penginapan</label>
											<div class="col-md-6" id="nominal_tunjangan_all_edit"></div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-12">
								<div class="data_detail" id="tabel_tunjangan_edit"></div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="submit" id="btn_edit" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
						<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
					</div>
				</form>
			</div>
		</div>
	</div>
<?php } ?>
<?php if (in_array($access['l_ac']['val_perdin'], $access['access'])) { ?>
	<div id="m_validasi" class="modal fade" role="dialog">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h2 class="modal-title">Validasi Data <b class="text-muted header_data"></b></h2>
				</div>
				<form id="form_validasi">
					<div class="modal-body">
						<div class="row">
							<div class="col-md-12">
								<div class="panel panel-success">
									<div class="panel-heading bg-green"><h4>Data Pengajuan Perjalanan Dinas</h4></div>
									<div class="panel-body">
										<div class="row">
											<div class="col-md-12">
												<div class="col-md-6">
													<div class="form-group col-md-12">
														<label class="col-md-6 control-label">Nomor Perjalanan Dinas</label>
														<div class="col-md-6" id="data_nosk_vali"></div>
													</div><br>
													<div class="form-group col-md-12">
														<label class="col-md-6 control-label">Tanggal Berangkat</label>
														<div class="col-md-6" id="data_berangkat_vali"></div>
													</div><br>
													<div class="form-group col-md-12">
														<label class="col-md-6 control-label">Tanggal Sampai</label>
														<div class="col-md-6" id="data_sampai_vali"></div>
													</div><br>
													<div class="form-group col-md-12">
														<label class="col-md-6 control-label">Tanggal Pulang</label>
														<div class="col-md-6" id="data_pulang_vali"></div>
													</div><br>
													<div class="form-group col-md-12">
														<label class="col-md-6 control-label">Plant Asal</label>
														<div class="col-md-6" id="data_asal_vali"></div>
													</div><br><br>
													<div class="form-group col-md-12">
														<label class="col-md-6 control-label">Tujuan</label>
														<div class="col-md-6" id="data_tujuan_vali"></div>
													</div><br>
													<div class="form-group col-md-12" id="jarak_vali">
														<label class="col-md-6 control-label">Jarak</label>
														<div class="col-md-6" id="data_jarak_vali"></div>
													</div><br>
													<div class="form-group col-md-12">
														<label class="col-md-6 control-label">Kendaraan</label>
														<div class="col-md-6" id="data_kendaraan_vali"></div>
													</div><br>
												</div>
												<div class="col-md-6">
													<div class="form-group col-md-12">
														<label class="col-md-6 control-label">Jumlah Kendaraan</label>
														<div class="col-md-6" id="data_jumlah_kendaraan_vali"></div>
													</div><br>
													<div class="form-group col-md-12">
														<label class="col-md-6 control-label">Biaya Transport / Nominal BBM Per Kendaraan</label>
														<div class="col-md-6" id="data_nominal_per_ken_vali"></div>
													</div><br><br>
													<div class="form-group col-md-12">
														<label class="col-md-6 control-label">Biaya Transport / Nominal BBM PP</label>
														<div class="col-md-6" id="data_nominal_bbm_vali"></div>
													</div><br><br>
													<div class="form-group col-md-12">
														<label class="col-md-6 control-label">Penginapan</label>
														<div class="col-md-6" id="data_nama_penginapan_vali"></div>
													</div><br>
													<div class="form-group col-md-12">
														<label class="col-md-6 control-label">Nominal Penginapan</label>
														<div class="col-md-6" id="data_nominal_penginapan_vali"></div>
													</div><br>
													<div class="form-group col-md-12">
														<label class="col-md-6 control-label">Jumlah Kamar</label>
														<div class="col-md-6" id="data_jumlah_kamar_vali"></div>
													</div><br>
													<div class="form-group col-md-12">
														<label class="col-md-6 control-label">Jumlah Hari</label>
														<div class="col-md-6" id="data_jumlah_hari_vali"></div>
													</div><br>
													<div class="form-group col-md-12">
														<label class="col-md-6 control-label">Total Biaya Penginapan</label>
														<div class="col-md-6" id="data_total_penginapan_vali"></div>
													</div><br>
													<div class="form-group col-md-12">
														<label class="col-md-6 control-label">Tugas</label>
														<div class="col-md-6" id="data_tugas_vali"></div>
													</div><br>	
													<div class="form-group col-md-12">
														<label class="col-md-6 control-label">Keterangan</label>
														<div class="col-md-6" id="data_keterangan_vali"></div>
													</div><br>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<h4 align="right">Total Dana Untuk Akomodasi <b id="total_akomodasi_vali"></b></h4>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-12">
								<div class="panel panel-success">
									<div class="panel-heading bg-yellow"><h4>Data Yang Harus Di Validasi</h4></div>
									<div class="panel-body">
										<div class="row">
											<div class="col-md-12">
												<label style="vertical-align: middle;">
													<span id="samakandgn_off" style="font-size: 30px;" onclick="samakandgnpengajuan('1');"><i class="far fa-square" aria-hidden="true"></i></span>
													<span id="samakandgn_on" style="display: none; font-size: 30px;" onclick="samakandgnpengajuan('0');"><i class="far fa-check-square" aria-hidden="true"></i></span>
													<span style="padding-bottom: 14pt;vertical-align: middle;"><b> Samakan Dengan Data Pengajuan</b></span>
													<input type="hidden" name="val_samakan" id="val_samakan">
												</label>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<div class="col-md-6">
												<input type="hidden" id="data_id_nosk_vali" name="id">
												<input type="hidden" id="data_kendaraan_kode" name="data_kendaraan_kode">
												<input type="hidden" id="data_kendaraan_umum_kode" name="data_kendaraan_umum_kode">
												<input type="hidden" id="menginap_val_old" name="menginap_val_old">
												<input type="hidden" id="data_jum_ken_val_old" name="data_jum_ken_val_old">
												<input type="hidden" id="data_nominal_bbm_per_val_old" name="data_nominal_bbm_per_val_old">
												<input type="hidden" id="data_nominal_bbm_val_old" name="data_nominal_bbm_val_old">
												<input type="hidden" id="data_penginapan_val_old" name="data_penginapan_val_old">
												<input type="hidden" id="nominal_penginapan_val_old" name="nominal_penginapan_val_old">
												<input type="hidden" id="tgl_berangkat_val_old" name="tgl_berangkat_val_old">
												<input type="hidden" id="tgl_pulang_val_old" name="tgl_pulang_val_old">
												<input type="hidden" id="data_kelas_hotel_valid_old" name="data_kelas_hotel_valid">
												<input type="hidden" id="data_nominal_penginapan_valid_old" name="data_nominal_penginapan_valid">
												<input type="hidden" id="data_jumlah_kamar_valid_old" name="data_jumlah_kamar_valid">
												<input type="hidden" id="data_jumlah_hari_valid_old" name="data_jumlah_hari_valid">
												<input type="hidden" id="data_total_penginapan_valid_old" name="data_total_penginapan_valid">
												<input type="hidden" id="plant_asal_val_old" name="plant_asal">
												<input type="hidden" id="plant_tujuan_val_old" name="plant_tujuan">
												<input type="hidden" id="tujuan_val_old" name="tujuan">
												<input type="hidden" id="jarak_val_old" name="jarak">
													<div class="form-group">
														<label>Tanggal Berangkat - Tanggal Sampai</label>
														<div class="has-feedback">
															<span class="fa fa-calendar form-control-feedback"></span>
															<input type="text" name="tanggal" id="tgl_berangkat_val" class="form-control dateRangeNoSecond" placeholder="Tanggal Berangkat" readonly="readonly">
														</div>
													</div>
													<div class="form-group">
														<label>Tanggal & Jam Pulang</label><br>
                                    					<div class="col-md-12">
																<!-- <span class="fa fa-calendar form-control-feedback"></span>
																<input type="text" name="tanggal_pulang" id="tgl_pulang_val" class="form-control" placeholder="Tanggal Pulang"> -->
															<input type="text" name="tanggal_pulang" id="data_tgl_pulang_val" class="date-picker form-control" placeholder="Tanggal Pulang">
															<div class="input-group bootstrap-timepicker">
																<div class="input-group-addon">
																	<i class="fa fa-clock-o"></i>
																</div>
																<input type="text" name="jam_pulang" id="data_jam_pulang_val" class="time-picker form-control field" placeholder="Tetapkan Jam">
															</div>
														</div>
													</div><br><br>
													<div class="form-group">
														<label>Kendaraan</label>
														<select class="form-control select2" name="kendaraan" id="data_kendaraan_val" onchange="kendaraanPDVal(this.value)" style="width: 100%;"></select>
													</div>
													<div class="form-group" id="nama_kendaraan_umum_vali" style="display:none;">
														<label>Nama Kendaraan Umum</label>
															<?php
															$kendaraan_umum[null] = 'Pilih Data';
															$sel = [null];
															$exsel = array('class'=>'form-control select2','placeholder'=>'Kendaraan Umum','id'=>'data_kendaraan_umum_val','required'=>'required','style'=>'width:100%');
															echo form_dropdown('kendaraan_umum',$kendaraan_umum,$sel,$exsel);
															?>
													</div>
													<div class="form-group">
														<label>Biaya Transport/BBM PP Per Kendaraan</label>
														<input type="text" name="nominal_bbm_per" id="data_nominal_bbm_per_val" class="form-control pull-right input-money" placeholder="Biaya Transport PP" readonly="readonly">
													</div>
													<div class="form-group">
														<label>Jumlah Kendaraan</label>
														<input type="number" nim="0" name="jum_kendaraan" id="data_jum_ken_val" class="form-control pull-right" placeholder="Jumlah Kendaraan"> 
														<!-- onblur="jumlahKendaraanVal(this.value)"> -->
													</div>
													<div class="form-group">
														<label>Total Biaya Transport / Nominal BBM</label>
														<input type="text" name="nominal_bbm" id="data_nominal_bbm_val" class="form-control pull-right input-money" placeholder="Biaya Transport PP">
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label style="vertical-align: middle;">Menginap</label><br>
														<span id="menginap_val_off" style="font-size: 20px;" onclick="menginapKlikVal();"><i class="far fa-square" aria-hidden="true"></i></span>
														<span id="menginap_val_on" style="display: none; font-size: 20px;" onclick="menginapKlikVal();"><i class="far fa-check-square" aria-hidden="true"></i></span>&nbsp;&nbsp;
														<span style="padding-bottom: 9px;vertical-align: middle;"><b>(Ceklist Jika Menginap)</b></span>
														<input type="hidden" name="menginap_val" id="menginap_val">
													</div>
													<div id="penginapan_val" style="display: none;">
														<div class="form-group">
															<label>Penginapan</label>
															<?php
																$penginapan[null] = 'Pilih Data';
																$sel2 = [null];
																$exsel2 = array('class'=>'form-control select2','placeholder'=>'penginapan','id'=>'data_penginapan_val','style'=>'width:100%','onchange'=>'cekPenginapanValid(this.value)');
																echo form_dropdown('penginapan',$penginapan,$sel2,$exsel2);
															?>
														</div>
														<div class="form-group" id="kelas_hotel_val" style="display:none;">
															<label>Kelas Hotel</label>
															<select class="form-control select2" name="kelas_hotel" id="data_kelas_hotel_valid" style="width: 100%;" onchange=cekTipeHotelValid(this.value)></select>
															<span id="div_span_ket_valid"></span>
														</div>
														<div class="form-group" id="nominal_penginapan_val" style="display:none;">
															<label>Biaya Penginapan</label>
															<input type="text" name="nominal_penginapan" id="data_nominal_penginapan_valid" class="form-control pull-right input-money" placeholder="Nominal Penginapan">
															<span id="div_span_nominal_valid"></span>
														</div>
														<div class="form-group" id="jumlah_kamar_val" style="display:none;">
															<label>Jumlah Kamar</label>
															<input type="number" name="jumlah_kamar" id="data_jumlah_kamar_valid" class="form-control pull-right" placeholder="Jumlah Kamar">
														</div>
														<div class="form-group" id="jumlah_hari_val" style="display:none;">
															<label>Jumlah Hari</label>
															<input type="number" name="jumlah_hari" id="data_jumlah_hari_valid" class="form-control pull-right" placeholder="Jumlah Hari">
														</div>
														<div class="form-group" id="total_penginapan_val" style="display:none;">
															<label>Total Biaya Penginapan</label>
															<input type="text" name="total_penginapan" id="data_total_penginapan_valid" class="form-control pull-right input-money" placeholder="Total Biaya Penginapan" readonly="readonly">
														</div>
														<!-- <div class="form-group">
															<label>Biaya Penginapan</label>
															<input type="text" name="nominal_penginapan" id="nominal_penginapan_val" class="form-control pull-right input-money" placeholder="Nominal Penginapan">
														</div> -->
													</div>
												</div>
											</div>
										</div>
										<hr>
										<div class="row">
											<div class="col-md-12">
												<div class="data_detail" id="tabel_tunjangan_val"></div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" onclick="do_validasi(2,0,'m_validasi')" class="btn btn-danger"><i class="fa fa-times-circle"></i> Tidak Diizinkan</button>
						<button type="button" onclick="do_validasi(2,1,'m_validasi')" class="btn btn-success"><i class="fa fa-check-circle"></i> Diizinkan</button>
						<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<div id="m_yes" class="modal fade" role="dialog">
		<div class="modal-dialog modal-sm modal-default">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title text-center">Validasi Perjalanan Dinas</h4>
				</div>
				<form id="form_yes">
					<div class="modal-body text-center">
						<input type="hidden" id="data_idk_yes" name="id_kar">
						<input type="hidden" id="data_id_yes" name="id">
						<input type="hidden" id="data_jenis_yes" name="jenis">
						<p>Apakah Anda yakin akan mengubah status Perjalanan Dinas dari <b class="text-green">DiIzinkan</b> menjadi <b class="text-red">Tidak Diizinkan</b></b> dengan Nomor <b id="data_name_yes" class="header_data"></b> ??</p>
					</div>
				</form>
				<div class="modal-footer">
					<button type="button" onclick="do_validasi(1,0,'m_yes')" class="btn btn-danger"><i class="fa fa-times-circle"></i> Tidak Diizinkan</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
				</div>
			</div>
		</div>
	</div>
	<div id="m_no" class="modal fade" role="dialog">
		<div class="modal-dialog modal-sm modal-default">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title text-center">Validasi Perjalanan Dinas</h4>
				</div>
				<form id="form_no">
					<div class="modal-body text-center">
						<input type="hidden" id="data_idk_no" name="id_kar">
						<input type="hidden" id="data_id_no" name="id">
						<input type="hidden" id="data_jenis_no" name="jenis">
						<p>Apakah Anda yakin akan mengubah status Perjalanan Dinas dari <b class="text-red">Tidak Diizinkan</b> menjadi <b class="text-green">DiIzinkan</b></b> dengan Nomor <b id="data_name_no" class="header_data"></b> ??</p>
					</div>
				</form>
				<div class="modal-footer">
					<button type="button" onclick="modal_need('id')" class="btn btn-success"><i class="fa fa-check-circle"></i> Diizinkan</button>
					<!-- <button type="button" onclick="do_validasi(0,1,'m_no')" class="btn btn-success"><i class="fa fa-check-circle"></i> Diizinkan</button> -->
					<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
				</div>
			</div>
		</div>
	</div>
<?php } ?>
<!-- delete -->
<div id="modal_delete_partial"></div>
<script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
<script type="text/javascript">
	var url_select="<?php echo base_url('global_control/select2_global');?>";
	var table="data_perjalanan_dinas";
	var column="id_pd";
	$(document).ready(function(){
		per_trans('all');
		tableData('all');
		refreshCode();
		refreshData();
		submitForm('form_add');
		submitForm('form_edit');
		$('#menginap_off').click(function(){
			$('#menginap_off').hide();
			$('#menginap_on').show();
			$('input[name="menginap"]').val('1');
		})
		$('#menginap_on').click(function(){
			$('#menginap_off').show();
			$('#menginap_on').hide();
			$('input[name="menginap"]').val('0');
		})
		$('#driver_off').click(function(){
			$('#driver_off').hide();
			$('#driver_on').show();
			$('input[name="driver"]').val('1');
			$('#div_driver').show();
		})
		$('#driver_on').click(function(){
			$('#driver_off').show();
			$('#driver_on').hide();
			$('input[name="driver"]').val('0');
			$('#div_driver').hide();
		})
		select_data('data_plant_asal_add',url_select,'master_loker','kode_loker','nama');
		select_data('data_plant_tujuan_add',url_select,'master_loker','kode_loker','nama');
		select_data('data_kendaraan_add',url_select,'master_pd_kendaraan','kode','nama');
		getSelect2("<?php echo base_url('employee/mutasi_jabatan/employee')?>",'data_mengetahui_add,#data_menyetujui_add,#data_dibuat_add,#karyawan_add');
		getSelect2("<?php echo base_url('presensi/data_perjalanan_dinas/getJenisPerdin')?>",'data_jenis_perdin_add');
		// getSelect2("<?php //echo base_url('presensi/data_presensi/employee')?>",'karyawan_add');
		// getSelect2("<?php //echo base_url('presensi/data_perjalanan_dinas/pilihtunjangan')?>",'data_tunjangan_add');
    	$('#karyawan_add').change(function(){
         	var kar = $('#karyawan_add').val();
			var data={kary:kar};
			var callback=getAjaxData("<?php echo base_url('presensi/data_perjalanan_dinas/cek_jumlah_karyawan')?>",data);
			// var cb=getAjaxData("<?php //echo base_url('presensi/data_perjalanan_dinas/diberikan_karyawan')?>",data);
			if (callback['val'] == 1) {
				$('#nominal_bbm').hide();
				// $('#nominal_penginapan').hide();
				// $('#kelas_hotel').hide();
			}else{
				$('#nominal_bbm').show();
				// $('#nominal_penginapan').show();
				// $('#kelas_hotel').show();
				// $('#diberi_kepada_karyawan').val(cb).trigger('change');
			}
      	})
    	$('#karyawan_edit_trans').change(function(){
         	var kr = $('#karyawan_edit_trans').val();
			var data={kary:kr};
			var callback=getAjaxData("<?php echo base_url('presensi/data_perjalanan_dinas/cek_jumlah_karyawan')?>",data);
			if (callback['val'] == 1) {
				$('#nominal_bbm_edit').hide();
				$('#nominal_penginapan_edit').hide();
			}else{
				$('#nominal_bbm_edit').show();
				$('#nominal_penginapan_edit').show();
			}
      	})
		$('#menginap_val_off').click(function(){
			$('#menginap_val_off').hide();
			$('#menginap_val_on').show();
			$('input[name="menginap_val"]').val('1');
		})
		$('#menginap_val_on').click(function(){
			$('#menginap_val_off').show();
			$('#menginap_val_on').hide();
			$('input[name="menginap_val"]').val('0');
		})
		$('#samakandgn_off').click(function(){
			$('#samakandgn_off').hide();
			$('#samakandgn_on').show();
			$('input[name="val_samakan"]').val('1');
		})
		$('#samakandgn_on').click(function(){
			$('#samakandgn_off').show();
			$('#samakandgn_on').hide();
			$('input[name="val_samakan"]').val('0');
		});
    	$('#data_kendaraan_add, #data_jum_ken_add, #data_kendaraan_umum_add,#data_bbm_add').change(function(){
			var data=$('#form_add').serialize();
			var cxv=getAjaxData("<?php echo base_url('presensi/data_perjalanan_dinas/view_tunjangan_nominal')?>",{search:data}); 
			$('#data_bbm_add').val(cxv['nominal_bbm']);
			var umum = $('#data_kendaraan_umum_add').val();
			if(umum == ''){
				var nominal = cxv['nominal_val'];
				if(nominal > 0){
					$('#data_bbm_add').prop('readonly', true);
				}else{
					$('#data_bbm_add').prop('readonly', false);
				}
			}else{
				$('#data_bbm_add').prop('readonly', false);
			}
			var nominalall = cxv['nominal_bbm_all'];
			$('#data_total_bbm_add').val(nominalall).prop('readonly', true);
		});
    	$('#data_kendaraan_edit, #data_jum_ken_edit, #nama_kendaraan_umum_edit,#data_bbm_edit').change(function(){
			var data=$('#form_edit').serialize();
			var cxv=getAjaxData("<?php echo base_url('presensi/data_perjalanan_dinas/view_tunjangan_nominal')?>",{search:data}); 
			$('#data_bbm_edit').val(cxv['nominal_bbm']);
			var umum = $('#data_kendaraan_umum_edit').val();
			if(umum == ''){
				var nominal = cxv['nominal_val'];
				if(nominal > 0){
					$('#data_bbm_edit').prop('readonly', true);
				}else{
					$('#data_bbm_edit').prop('readonly', false);
				}
			}else{
				$('#data_bbm_edit').prop('readonly', false);
			}
			var nominalall = cxv['nominal_bbm_all'];
			$('#data_total_bbm_edit').val(nominalall).prop('readonly', true);
		});
    	// $('#data_bbm_add').change(function(){
		// 	var data=$('#form_add').serialize();
		// 	var cxv=getAjaxData("<?php //echo base_url('presensi/data_perjalanan_dinas/view_tunjangan_nominal')?>",{search:data}); 
		// 	var nominalall = cxv['nominal_bbm_all'];
		// 	$('#data_total_bbm_add').val(nominalall).prop('readonly', true);
		// });
		// $('#data_nominal_penginapan_add').keyup(function(){ 
		// 	var nominal = $('#data_nominal_penginapan_add').val();
		// 	var kelas = $('#data_kelas_hotel_add').val();
		// 	cekInputNominalPenginapan(nominal,kelas);
		// });
		$('#data_nominal_penginapan_add').blur(function(){ 
			var nominal = $('#data_nominal_penginapan_add').val();
			var kelas = $('#data_kelas_hotel_add').val();
			cekInputNominalPenginapan(nominal,kelas); 
		});
		$('#data_nominal_penginapan_add, #data_jumlah_kamar_add, #data_jumlah_hari_add').blur(function(){ 
			var data=$('#form_add').serialize();
			var cxv=getAjaxData("<?php echo base_url('presensi/data_perjalanan_dinas/view_nominal_penginapan')?>",{search:data}); 
			$('#data_total_penginapan_add').val(cxv['biaya_penginapan']);
		});
		$('#data_nominal_penginapan_valid, #data_jumlah_kamar_valid, #data_jumlah_hari_valid').blur(function(){ 
			var data=$('#form_validasi').serialize();
			var cxv=getAjaxData("<?php echo base_url('presensi/data_perjalanan_dinas/view_nominal_penginapan')?>",{search:data}); 
			$('#data_total_penginapan_valid').val(cxv['biaya_penginapan']);
		});
		$('#data_jum_ken_val').blur(function(){ 
			var data=$('#form_validasi').serialize();
			var cxv=getAjaxData("<?php echo base_url('presensi/data_perjalanan_dinas/view_nominal_penginapan')?>",{search:data}); 
			$('#data_nominal_bbm_val').val(cxv['biaya_bbm']);
		});
    	$('#data_kendaraan_val, #data_kendaraan_umum_val').change(function(){
			var data=$('#form_validasi').serialize();
			var cxv=getAjaxData("<?php echo base_url('presensi/data_perjalanan_dinas/view_tunjangan_nominal')?>",{search:data}); 
			$('#data_nominal_bbm_per_val').val(cxv['nominal_bbm']); 
			$('#data_nominal_bbm_val').val(cxv['nominal_bbm_all']);
			var nominal =cxv['nominal_val'];
			if(nominal > 0){
				$('#data_nominal_bbm_per_val').prop('readonly', true);
			}else{
				$('#data_nominal_bbm_per_val').prop('readonly', false);
			}
		});
		$('#data_nominal_penginapan_edit, #data_jumlah_kamar_edit, #data_jumlah_hari_edit').blur(function(){ 
			var data=$('#form_edit').serialize();
			var cxv=getAjaxData("<?php echo base_url('presensi/data_perjalanan_dinas/view_nominal_penginapan')?>",{search:data}); 
			$('#data_total_penginapan_edit').val(cxv['biaya_penginapan']);
		});
	});
	function view_tunjangan() {
		var data=$('#form_add').serialize();
		var callback=getAjaxData("<?php echo base_url('presensi/data_perjalanan_dinas/view_tunjangan')?>",{search:data});  
		$('#view_modal_tunjangan').modal('show');
		$('#data_tabel_tunjangan').html(callback['tabel']);
		$('#jumlah_kendaraan').html(callback['jum_ken']+' Kendaraan');
		$('#jumlah_kamar_view_tunjangan').html(callback['jum_kamar']+' Kamar');
		$('#nominal_bbm_per_ken').html(callback['nominal_bbm_per_ken']);
		$('#nominal_bbm_all').html(callback['nominal_bbm_all']);
		$('#data_kendaraan_all').html(callback['nama_kendaraan_all']);
		$('#nominal_tunjangan_view_tun').html(callback['nominal_tunjangan']);
		$('#jumlah_hari_view_tunjangan').html(callback['jum_hari']+' Hari');
		$('#nominal_tunjangan_all').html(callback['nominal_tunjangan_all']);
		$('#data_tunjangan_all').html(callback['nama_tunjangan_all']);
		$('#total_akomodasi_add').html(callback['total_akomodasi']);
		var jumlah = callback['jumlah'];
		if (jumlah == 1){
			$('#total_tunjangan').hide();
		}else{
			$('#total_tunjangan').show();
		}
	}
	function menginapKlik(){		
		var name = $('input[name="menginap"]').val();
		if(name == 0) {
			$('#penginapan_add').show();
			$('#data_penginapan_add').attr('required','required');
		}else {
			$('#penginapan_add').hide();
			$('#data_penginapan_add').removeAttr('required');
			$('#data_penginapan_add').val('').trigger('change');
		}
	}
	function refreshData() {
		getSelect2("<?php echo base_url('master/master_jabatan/nama_bagian')?>",'bagian_export');
		select_data('unit_export',url_select,'master_loker','kode_loker','nama','placeholder');
		unsetoption('bagian_export',['BAG001','BAG002']);
		getSelect2("<?php echo base_url('master/master_jabatan/nama_bagian')?>",'bagian_export_trans');
		select_data('unit_export_trans',url_select,'master_loker','kode_loker','nama','placeholder');
		unsetoption('bagian_export_trans',['BAG001','BAG002']);
	}
	function resetselectAdd() {
		getSelect2("<?php echo base_url('employee/mutasi_jabatan/refreshkaryawan')?>",'karyawan_add');
		$('#tujuan').val('').trigger('change');
		$('#data_plant_asal_add').val('').trigger('change');
		$('#data_plant_tujuan_add').val('').trigger('change');
		$('#data_lokasi_tujuan_add').val('').trigger('change');
		$('#data_kendaraan_add').val('').trigger('change');
		$('#data_kendaraan_umum_add').val('').trigger('change');
		$('#data_penginapan_add').val('').trigger('change');
		// getSelect2("<?php //echo base_url('presensi/data_perjalanan_dinas/refreshtunjangan')?>",'data_tunjangan_add');
		$('#data_mengetahui_add').val('').trigger('change');
		$('#data_menyetujui_add').val('').trigger('change');
		$('#data_dibuat_add').val('').trigger('change');
	}
	function tableData(kode) {
		$('input[name="param"').val(kode);
		$('#table_data').DataTable().destroy();
		if(kode=='all'){
			var datax = {param:'all',access:"<?php echo $this->codegenerator->encryptChar($access);?>"};
		}else{
			var bagian = $('#bagian_export').val();
			var unit = $('#unit_export').val();
			var tanggal = $('#tanggal_filter').val();
			var datax = {param:'search',bagian:bagian,unit:unit,tanggal:tanggal,access:"<?php echo $this->codegenerator->encryptChar($access);?>"};
		}
		$('#table_data').DataTable( {
			ajax: {
				url: "<?php echo base_url('presensi/data_perjalanan_dinas/view_all/')?>",
				type: 'POST',
				data: datax
			},
			scrollX: true,
			columnDefs: [
			{   targets: 0, 
				width: '5%',
				render: function ( data, type, full, meta ) {
					return '<center>'+(meta.row+1)+'.</center>';
				}
			},
			{   targets: 1,
				width: '10%',
				render: function ( data, type, full, meta ) {
					return '<a href="<?php base_url()?>view_perjalanan_dinas/'+full[8]+'">' +data+'</a>';
				}
			},
			{   targets: 6,
				width: '15%',
				render: function ( data, type, full, meta ) {
					return ''+data+' Data Perjalanan Dinas';
				}
			},
			{   targets: 7, 
				width: '7%',
				render: function ( data, type, full, meta ) {
					return '<center>'+data+'</center>';
				}
			},
			]
		});		
	}
	function pilih_karyawan() {
		$('#modal_pilih_karyawan').modal('toggle');
		$('#modal_pilih_karyawan .header_data').html('Pilih Karyawan');
		$('#table_pilih').DataTable( {
			ajax: "<?php echo base_url('employee/pilih_k_mutasi')?>",
			scrollX: true,
			destroy: true,
			columnDefs: [
			{   targets: 0, 
				width: '10%',
				render: function ( data, type, full, meta ) {
					return '<center>'+(meta.row+1)+'.</center>';
				}
			},
			]
		});
	}
	function refreshCode() {
		kode_generator("<?php echo base_url('presensi/data_perjalanan_dinas/kode');?>",'no_sk_add');
	}
	function view_modal(id) {
		var data={id_pd:id};
		var callback=getAjaxData("<?php echo base_url('presensi/data_perjalanan_dinas/view_one')?>",data);  
		$('#view').modal('show');
		$('.header_data').html(callback['nama']);
		$('#data_nik_view').html(callback['nik']);
		$('#data_nama_view').html(callback['nama']);
		$('#data_peringatanasal_view').html(callback['status_lama']);
		$('#data_peringatanbaru_view').html(callback['status_baru']);
		$('#data_keterangan_view').html(callback['keterangan']);
		var status = callback['status'];
		if(status==1){
			var statusval = '<b class="text-success">Aktif</b>';
		}else{
			var statusval = '<b class="text-danger">Tidak Aktif</b>';
		}
		$('#data_status_view').html(statusval);
		$('#data_create_date_view').html(callback['create_date']+' WIB');
		$('#data_update_date_view').html(callback['update_date']+' WIB');
		$('input[name="data_id_view"]').val(callback['id']);
		$('#data_create_by_view').html(callback['nama_buat']);
		$('#data_update_by_view').html(callback['nama_update']);
		$('#data_tabel_view').html(callback['tabel']);
		$('#data_jabatan_view').html(callback['jabatan']);
		$('#data_loker_view').html(callback['loker']);
	}
	function delete_modal(id) {
		var data={id_pd:id};
		var callback=getAjaxData("<?php echo base_url('presensi/data_perjalanan_dinas/view_one')?>",data);
		var datax={table:table,column:'id_karyawan',id:callback['id_karyawan'],nama:callback['nama']};
		loadModalAjax("<?php echo base_url('pages/load_modal_delete')?>",'modal_delete_partial',datax,'delete');
	}
	function submitForm(form) {
		$('#'+form).validator().on('submit', function (e) {
			if (e.isDefaultPrevented()) {
				notValidParamx();
			}else{
				e.preventDefault(); 
				if(form=='form_add'){
					do_add()
				}else{
					do_edit()
				}
			}
		})
	}
	function do_add(){
		if($("#form_add")[0].checkValidity()) {
			submitAjax("<?php echo base_url('presensi/add_perjalanan_dinas')?>",null,'form_add',null,null);
			$('#table_data').DataTable().ajax.reload(function(){
				Pace.restart();
			});
			$('#table_data_trans').DataTable().ajax.reload(function(){
				Pace.restart();
			});
			$('#form_add')[0].reset();
			refreshCode();
			resetselectAdd();
		}else{
			notValidParamx();
		} 
	}
	function do_edit(){
		if($("#form_edit")[0].checkValidity()) {
			submitAjax("<?php echo base_url('presensi/edit_perjalanan_dinas')?>",'edit_u','form_edit',null,null);
			$('#table_data').DataTable().ajax.reload();
			$('#table_data_trans').DataTable().ajax.reload();
		}else{
			refreshCode();
			notValidParamx();
		} 
	}
	function rekap() {
		var data=$('#form_filter').serialize();
		window.location.href = "<?php echo base_url('rekap/data_perjalanan_dinas')?>?"+data;
	}
	function rekap_trans() {
		var data=$('#form_filter_trans').serialize();
		window.location.href = "<?php echo base_url('rekap/data_perjalanan_dinas_trans')?>?"+data;
	}
	function kendaraanPD(f) {
		setTimeout(function () {
			var name = $('#data_kendaraan_add').val();
			if(name == 'KPD0001') {
				$('#nama_kendaraan_umum').show();
				$('#data_kendaraan_umum_add').attr('required','required');
			}else {
				$('#nama_kendaraan_umum').hide();
				$('#data_kendaraan_umum_add').removeAttr('required');
				$('#data_kendaraan_umum_add').val('');
			}
		},100);
	}
	function tujuanPD(f) {
		setTimeout(function () {
			var name = $('#tujuan').val();
			if(name == 'plant') {
				$('#tujuan_plant').show();
				$('#tujuan_non_plant').hide();
				$('#data_plant_tujuan_add').attr('required','required');
				$('#data_lokasi_tujuan_add').removeAttr('required');
				$('#data_jarak_add').removeAttr('required');
				$('#data_jarak_add').val('');
			}else if(name == 'non_plant') {
				$('#tujuan_plant').hide();
				$('#tujuan_non_plant').show();
				$('#data_plant_tujuan_add').removeAttr('required');
				$('#data_lokasi_tujuan_add').attr('required','required');
				$('#data_jarak_add').attr('required','required');
			}else{
				$('#tujuan_plant').hide();
				$('#tujuan_non_plant').hide();
				$('#data_plant_tujuan_add').removeAttr('required');
				$('#data_lokasi_tujuan_add').removeAttr('required');
				$('#data_jarak_add').removeAttr('required');
			}
		},100);
	}
	$(document).on('click', '.pilih', function (e1) {
		$("#id_karyawan").val($(this).data('id_karyawan'));
		$("#nik").val($(this).data('nik'));
		$("#nama").val($(this).data('nama'));
		$("#jabatan").val($(this).data('jabatan'));
		$("#nama_jabatan").val($(this).data('nama_jabatan'));
		$('#modal_pilih_karyawan').modal('hide');
	});
	function per_trans(kode){
		$('input[name="param"').val(kode);
		if(kode=='all'){
			var datax = {param:'all',access:"<?php echo $this->codegenerator->encryptChar($access);?>"};
		}else{
			var bagian = $('#bagian_export_trans').val();
			var unit = $('#unit_export_trans').val();
			var tanggal = $('#tanggal_filter_trans').val();
			var datax = {param:'search',bagian:bagian,unit:unit,tanggal:tanggal,access:"<?php echo $this->codegenerator->encryptChar($access);?>"};
		}
		$('#table_data_trans').DataTable().destroy();
		$('#table_data_trans').DataTable( {
			ajax: {
				url: "<?php echo base_url('presensi/data_perjalanan_dinas/view_all_trans/')?>",
				type: 'POST',
				data: datax
			},
			fixedColumns:   {
				leftColumns: 3,
				rightColumns: 1
			},
			scrollX: true,
			autoWidth: false,
			columnDefs: [
			{   targets: 0, 
				width: '5%',
				render: function ( data, type, full, meta ) {
					return '<center>'+(meta.row+1)+'.</center>';
				}
			},
			{   targets: 1,
				width: '10%',
				render: function ( data, type, full, meta ) {
					return data;
				}
			},
			{   targets: [6,7], 
				width: '10%',
				render: function ( data, type, full, meta ) {
					return '<center>'+data+'</center>';
				}
			},
			{   targets: 8, 
				width: '10%',
				render: function ( data, type, full, meta ) {
					return '<center>'+data+'</center>';
				}
			},
			]
		});		
	}
	function view_modal_u(kode) {
		var data={no_sk:kode};
		var callback=getAjaxData("<?php echo base_url('presensi/data_perjalanan_dinas/view_one_trans')?>",data);
		$('#view_u').modal('show');
		$('.header_data').html(callback['no_sk']);
		$('input[name="data_kode_view"]').val(callback['no_sk']);
		$('#data_nosk_view').html(callback['no_sk']);
		$('#data_nik_view').html(callback['nik']);
		$('#data_nama_view').html(callback['nama']);
		$('#data_berangkat_view').html(callback['tanggal_berangkat']);
		$('#data_sampai_view').html(callback['tanggal_sampai']);
		$('#data_pulang_view').html(callback['tanggal_pulang']);
		$('#data_asal_view').html(callback['asal']);
		$('#data_tujuan_view').html(callback['tujuan']);
		$('#data_kedaraan_view').html(callback['kendaraan']);
		$('#data_mengetahui_view').html(callback['mengetahui']);
		$('#data_menyetujui_view').html(callback['menyetujui']);
		$('#data_dibuat_view').html(callback['dibuat']);
		$('#data_tugas_view').html(callback['tugas']);
		$('#data_keterangan_view').html(callback['keterangan']);
		$('#data_nama_penginapan_view').html(callback['nama_penginapan']);
		$('#data_jumlah_kamar_view').html(callback['jumlah_kamar']);
		$('#data_jumlah_hari_view').html(callback['jumlah_hari']);
		$('#data_total_penginapan_view').html(callback['total_penginapan']);
		$('#data_jarak_view').html(callback['jarak']);
		$('#data_nominal_bbm_view').html(callback['nominal_bbm']);
		$('#data_nominal_penginapan_view').html(callback['nominal_penginapan']);
		$('#data_jumlah_kendaraan_view').html(callback['jumlah_kendaraan']);
		$('#data_nominal_per_ken_view').html(callback['nominal_per_ken']);
		var menginap = callback['menginap'];
		var mengipal_val=(menginap==1)?'<b class="text-success">Menginap</b>':'<b class="text-danger">Tidak Menginap</b>';
		$('#data_menginap_view').html(mengipal_val);
		$('#tabel_tunjangan').html(callback['tabel_tunjangan']);
		$('#data_after_val').html(callback['data_after_val']);
		var val_ac = callback['validasi_ac'];
		if (val_ac == 2){
			$('#div_data_after_val').hide();
			$('#tombol_edit').show();
		}else{
			$('#div_data_after_val').show();
			$('#tombol_edit').hide();
		}
		var sttpd = callback['status_pd'];
		if (sttpd == 2){
			$('#btn_proses').show();
		}else{
			$('#btn_proses').hide();
		}
		$('#endProses_val').html(callback['data_end_proses']);
		var sttdd = callback['status_pd'];
		if (sttdd == 3){
			$('#div_endProses_val').show();
		}else{
			$('#div_endProses_val').hide();
		}
		var status = callback['status'];
		var statusval =(status==1)?'<b class="text-success">Aktif</b>':'<b class="text-danger">Tidak Aktif</b>';
		$('#data_status_trans').html(statusval);
		$('#data_create_date_trans').html(callback['create_date']+' WIB');
		$('#data_update_date_trans').html(callback['update_date']+' WIB');
		$('input[name="data_id_trans"]').val(callback['id']);
		$('#data_create_by_trans').html(callback['nama_buat']);
		$('#data_update_by_trans').html(callback['nama_update']);
		$('#total_akomodasi_view').html(callback['total_akomodasi']);
	}
	function edit_modal_u() {
		// refreshCode();
		$('#menginap_off_edit').click(function(){
			$('#menginap_off_edit').hide();
			$('#menginap_on_edit').show();
			$('#menginap_edit').val('1');
			$('#penginapan_edit_div').show();
			$('#data_penginapan_edit').attr('required','required');
		});
		$('#menginap_on_edit').click(function(){
			$('#menginap_off_edit').show();
			$('#menginap_on_edit').hide();
			$('#menginap_edit').val('0');
			$('#penginapan_edit_div').hide();
			$('#data_penginapan_edit').removeAttr('required');
			$('#data_penginapan_edit').val('').trigger('change');
		});
		$('#driver_off_edit').click(function(){
			$('#driver_off_edit').hide();
			$('#driver_on_edit').show();
			$('#div_driver_edit').show();
			$('#driver_edit').val('1');
		})
		$('#driver_on_edit').click(function(){
			$('#driver_off_edit').show();
			$('#driver_on_edit').hide();
			$('#div_driver_edit').hide();
			$('#driver_edit').val('0');
		})
		select_data('data_plant_asal_edit',url_select,'master_loker','kode_loker','nama');
		select_data('data_plant_tujuan_edit',url_select,'master_loker','kode_loker','nama');
		select_data('data_kendaraan_edit',url_select,'master_pd_kendaraan','kode','nama');
		// getSelect2("<?php //echo base_url('presensi/data_perjalanan_dinas/pilihtunjangan')?>",'data_tunjangan_edit');
		getSelect2("<?php echo base_url('employee/view_mutasi_jabatan/employee')?>",'data_mengetahui_edit,#data_menyetujui_edit,#data_dibuat_edit,#karyawan_edit_trans');
		getSelect2("<?php echo base_url('presensi/data_perjalanan_dinas/getJenisPerdin')?>",'data_jenis_perdin_edit');
		var kode = $('input[name="data_kode_view"]').val();
		var data={no_sk:kode};
		var callback=getAjaxData("<?php echo base_url('presensi/data_perjalanan_dinas/view_one_trans')?>",data); 
		$('#view_u').modal('toggle');
		setTimeout(function () {
			$('#edit_u').modal('show');
		},600); 
		$('.header_data').html(callback['no_sk']);
		$('#data_id_edit').val(callback['id']);
		// $('#data_idk_edit').val(callback['id_karyawan']);
		$('#karyawan_edit_trans').val(callback['e_karyawan']).trigger('change');
		$('#data_nosk_edit').val(callback['no_sk']);
		$('#karyawan_old').val(callback['e_karyawan']).trigger('change');
		$('#no_sk_old').val(callback['no_sk']);
		$('#data_tglsk_edit').val(callback['etgl_sk']);
		$('#tujuan_edit').val(callback['plant']).trigger('change');
		$('#data_kendaraan_edit').val(callback['e_kendaraan']).trigger('change');
		$('#data_plant_asal_edit').val(callback['e_plant_asal']).trigger('change');
		$('#data_plant_tujuan_edit').val(callback['e_plant_tujuan']).trigger('change');
		$('#data_lokasi_tujuan_edit').val(callback['e_lokasi']);
		$('#data_jarak_edit').val(callback['e_jarak']);
		$('#data_kendaraan_umum_edit').val(callback['e_kendaraan_umum']).trigger('change');
		$('#data_bbm_edit').val(callback['e_nominal_bbm']);
		$('#data_total_bbm_edit').val(callback['e_total_bbm']);
		// $('#data_tunjangan_edit').val(callback['e_tunjangan']).trigger('change');
		$("#data_tanggal_edit").data('daterangepicker').setStartDate(callback['e_tanggal_mulai']);
		$("#data_tanggal_edit").data('daterangepicker').setEndDate(callback['e_tanggal_selesai']);
		$('#data_tanggal_pulang_edit').val(callback['e_tanggal_pulang']);
		$('#data_mengetahui_edit').val(callback['e_mengetahui']).trigger('change');
		$('#data_menyetujui_edit').val(callback['e_menyetujui']).trigger('change');
		$('#data_dibuat_edit').val(callback['e_dibuat']).trigger('change');
		$('#data_jenis_perdin_edit').val(callback['jenis_perdin']).trigger('change');
		$('#data_tugas_edit').val(callback['e_tugas']);
		$('#data_keterangan_edit').val(callback['e_keterangan']);
		$('#data_nominal_bbm_edit').val(callback['nominal_bbm']);
		$('#data_jum_ken_edit').val(callback['jumlah_kendaraan_edit']);
		$('#data_nominal_penginapan_edit').val(callback['nominal_penginapan_edit']);
		$('#tabel_tunjangan_edit').val(callback['tabel_tunjangan_edit']);
		$('#data_kelas_hotel_edit').val(callback['kelas_hotel_edit']).trigger('change');
		$('#data_nominal_penginapan_edit').val(callback['nominal_penginapan_edit']);
		$('#data_jumlah_kamar_edit').val(callback['jumlah_kamar_edit']);
		$('#data_jumlah_hari_edit').val(callback['jumlah_hari_edit']);
		$('#data_total_penginapan_edit').val(callback['total_penginapan_edit']);
		var menginap = callback['menginap'];
		if(menginap==1){
			$('#menginap_on_edit').show();
			$('#menginap_off_edit').hide();
			$('#penginapan_edit_div').show();
			$('#data_penginapan_edit').attr('required','required');
			$('#data_penginapan_edit').val(callback['e_nama_penginapan']).trigger('change');
		}else{
			$('#menginap_on_edit').hide();
			$('#menginap_off_edit').show();
			$('#penginapan_edit_div').hide();
			$('#data_penginapan_edit').removeAttr('required');
			$('#data_penginapan_edit').val('').trigger('change');
			// $('#nominal_penginapan_edit').hide();
		}
		var adaDriver = callback['adaDriver'];
		if(adaDriver > 0){
			$('#driver_off_edit').hide();
			$('#driver_on_edit').show();
			$('#div_driver_edit').show();
			$('#nama_driver_edit').val(callback['namaDriver']);
			$('#driver_edit').val('1');
		}else{
			$('#driver_off_edit').show();
			$('#driver_on_edit').hide();
			$('#div_driver_edit').hide();
			$('#nama_driver_edit').val('');
			$('#driver_edit').val('0');
		}
		// $('#nominal_bbm_all_edit').html(callback['nominal_bbm']);
		// $('#data_kendaraan_all_edit').html(callback['kendaraan']);
		// $('#nominal_tunjangan_all_edit').html(callback['nominal_penginapan']);
		// $('#data_tunjangan_all_edit').html(callback['nama_penginapan']);
		// var jumlah = callback['jumlah'];
		// if (jumlah == 2){
		// 	$('#total_tunjangan_edit').hide();
		// }else{
		// 	$('#total_tunjangan_edit').show();
		// }
	}
	function kendaraanPDEdit(f) {
		setTimeout(function () {
			var name = $('#data_kendaraan_edit').val();
			if(name == 'KPD0001') {
				$('#nama_kendaraan_umum_edit').show();
				$('#data_kendaraan_umum_edit').attr('required','required');
			}else {
				$('#nama_kendaraan_umum_edit').hide();
				$('#data_kendaraan_umum_edit').removeAttr('required');
			}
		},100);
	}
	function tujuanPDEdit(f) {
		setTimeout(function () {
			var name = $('#tujuan_edit').val();
			if(name == 'plant') {
				$('#tujuan_plant_edit').show();
				$('#tujuan_non_plant_edit').hide();
				$('#data_plant_tujuan_edit').attr('required','required');
				$('#data_lokasi_tujuan_edit').removeAttr('required');
				$('#data_jarak_edit').removeAttr('required');
				$('#data_jarak_edit').val('');
			}else if(name == 'non_plant') {
				$('#tujuan_plant_edit').hide();
				$('#tujuan_non_plant_edit').show();
				$('#data_plant_tujuan_edit').removeAttr('required');
				$('#data_lokasi_tujuan_edit').attr('required','required');
				$('#data_jarak_edit').attr('required','required');
			}else{
				$('#tujuan_plant_edit').hide();
				$('#tujuan_non_plant_edit').hide();
				$('#data_plant_tujuan_edit').removeAttr('required');
				$('#data_lokasi_tujuan_edit').removeAttr('required');
				$('#data_jarak_edit').removeAttr('required');
			}
		},100);
	}
	function jstreeLoad(datax,usage) {
		$.each(datax, function (index, value) {
			$('#'+value).on('ready.jstree', function () {
				$("#"+value).jstree("open_all");
			});
			$('#'+value).on("changed.jstree", function (e, data) {			
				var checked_ids = [data.selected];
				var undetermined=$('#'+value).jstree().get_undetermined();
				undetermined = jQuery.grep(undetermined, function( a ) {
					return a != 0;
				});
				if (undetermined.length > 0) {
					checked_ids.push(undetermined);
				}
				$('#'+value+'_'+usage).val(checked_ids);
			});
		});
	}
	function view_tunjangan_edit() {
		var data=$('#form_edit').serialize();
		var callback=getAjaxData("<?php echo base_url('presensi/data_perjalanan_dinas/view_tunjangan')?>",{search:data});  
		$('#tabel_tunjangan_edit').html(callback['tabel']);
		$('#tabel_tunjangan_edit').slideToggle('slow');
		$('#nominal_bbm_all_edit').html(callback['nominal_bbm_all']);
		$('#data_kendaraan_all_edit').html(callback['nama_kendaraan_all']);
		$('#nominal_tunjangan_all_edit').html(callback['nominal_tunjangan_all']);
		$('#data_tunjangan_all_edit').html(callback['nama_tunjangan_all']);
		$('#nominal_jum_ken_edit').html(callback['jum_ken']);
		$('#nominal_per_ken_edit').html(callback['nominal_bbm_per_ken']);
		var jumlah = callback['jumlah'];
		if (jumlah == 1){
			$('#total_tunjangan_edit').hide();
		}else{
			$('#total_tunjangan_edit').show();
		}
	}
	function delete_modal_u(id) {
		var data={no_sk:id};
		var callback=getAjaxData("<?php echo base_url('presensi/data_perjalanan_dinas/view_one_trans')?>",data);
		var datax={table:table,column:'no_sk',id:callback['no_sk'],nama:callback['no_sk'],table_view:'#table_data_trans'};
		loadModalAjax("<?php echo base_url('pages/load_modal_delete')?>",'modal_delete_partial',datax,'delete');
	}
	function kendaraanPDVal(f) {
		setTimeout(function () {
			var name = $('#data_kendaraan_val').val();
			if(name == 'KPD0001') {
				$('#nama_kendaraan_umum_vali').show();
				$('#data_kendaraan_umum_val').attr('required','required');
			}else {
				$('#nama_kendaraan_umum_vali').hide();
				$('#data_kendaraan_umum_val').removeAttr('required');
				$('#data_kendaraan_umum_val').val('');
			}
		},100);
	}
	function menginapKlikVal(){		
		var name = $('input[name="menginap_val"]').val();
		if(name == 0) {
			$('#penginapan_val').show();
			$('#data_penginapan_val').attr('required','required');
			$('#nominal_penginapan_val').attr('required','required');
		}else {
			$('#penginapan_val').hide();
			$('#nominal_penginapan_val').removeAttr('required');
			$('#nominal_penginapan_val').val('');
			$('#data_penginapan_val').removeAttr('required');
			$('#data_penginapan_val').val('').trigger('change');
		}
	}
	function modal_need(id) {
		select_data('data_kendaraan_val',url_select,'master_pd_kendaraan','kode','nama');
		// getSelect2("<?php //echo base_url('presensi/data_perjalanan_dinas/pilihtunjangan')?>",'data_tunjangan_vali');
		if(id=='id'){
			var nosk = $('#data_id_no').val();
			var data={no_sk:nosk};
			var callback=getAjaxData("<?php echo base_url('presensi/data_perjalanan_dinas/view_one_trans')?>",data); 
			$('#m_no').modal('toggle');
			setTimeout(function () {
				$('#m_validasi').modal('show');
			},600); 
		}else{
			var data={no_sk:id};
			var callback=getAjaxData("<?php echo base_url('presensi/data_perjalanan_dinas/view_one_trans')?>",data); 
			$('#m_validasi').modal('show');
		}
		$('#samakandgn_off').hide();
		$('#samakandgn_on').show();
		$('.header_data').html(callback['no_sk']);
		$('#data_id_nosk_vali').val(callback['no_sk']);
		$('#data_nosk_vali').html(callback['no_sk']);
		$('#data_berangkat_vali').html(callback['tanggal_berangkat']);
		$('#data_sampai_vali').html(callback['tanggal_sampai']);
		$('#data_pulang_vali').html(callback['tanggal_pulang']);
		$('#data_asal_vali').html(callback['asal']);
		$('#data_tujuan_vali').html(callback['tujuan']);
		$('#data_kendaraan_vali').html(callback['kendaraan']);
		$('#data_kendaraan_kode').val(callback['e_kendaraan']);
		$('#data_kendaraan_umum_kode').val(callback['e_kendaraan_umum']);
		$('#menginap_val_old').val(callback['menginap']);
		$('#data_jum_ken_val_old').val(callback['jumlah_kendaraan_edit']);
		$('#data_nominal_bbm_val_old').val(callback['e_nominal_bbm']);
		$('#data_nominal_bbm_per_val_old').val(callback['nominal_per_ken']);
		$('#data_penginapan_val_old').val(callback['e_nama_penginapan']);
		$('#nominal_penginapan_val_old').val(callback['e_nominal_penginapan']);
		$('#data_mengetahui_vali').html(callback['mengetahui']);
		$('#data_menyetujui_vali').html(callback['menyetujui']);
		$('#data_dibuat_vali').html(callback['dibuat']);
		$('#data_tugas_vali').html(callback['tugas']);
		$('#data_keterangan_vali').html(callback['keterangan']);
		$('#data_nama_penginapan_vali').html(callback['nama_penginapan']);
		$('#data_jarak_vali').html(callback['jarak']);
		$('#data_nominal_bbm_vali').html(callback['nominal_bbm']);
		$('#data_nominal_penginapan_vali').html(callback['nominal_penginapan']);
		$('#data_jumlah_kamar_vali').html(callback['jumlah_kamar']);
		$('#data_jumlah_hari_vali').html(callback['jumlah_hari']);
		$('#data_total_penginapan_vali').html(callback['total_penginapan']);
		$('#data_jumlah_kendaraan_vali').html(callback['jumlah_kendaraan']);
		$('#data_nominal_per_ken_vali').html(callback['nominal_per_ken']);
		$('#tabel_tunjangan_val').html(callback['tabel_tunjangan']);
		$('#total_akomodasi_vali').html(callback['total_akomodasi']);
		// $('#tgl_berangkat_val_old').data('daterangepicker').setStartDate(callback['e_tanggal_berangkat_val']);
		// $('#tgl_berangkat_val_old').data('daterangepicker').setEndDate(callback['e_tanggal_sampai_val']);
		// $('#tgl_berangkat_val').data('daterangepicker').setStartDate(callback['e_tanggal_berangkat_val']);
		// $('#tgl_berangkat_val').data('daterangepicker').setEndDate(callback['e_tanggal_sampai_val']);
		// $('#tgl_pulang_val_old').val(callback['e_tanggal_pulang_val']);
		// $('#tgl_pulang_val').val(callback['e_tanggal_pulang_val']);
		$('#tgl_berangkat_val').val(callback['e_tanggal_berangkat_val']);
		$('#tgl_berangkat_val_old').val(callback['e_tanggal_berangkat_val']);
		$('#tgl_pulang_val_old').val(callback['e_tanggal_pulang_val']);
		$('#data_tgl_pulang_val').val(callback['tanggal_pulang_val_aja']);
		$('#data_jam_pulang_val').val(callback['jam_pulang_val']);
		$('#data_kelas_hotel_valid').val(callback['e_kelas_hotel']).trigger('change');
		$('#data_nominal_penginapan_valid').val(callback['e_nominal_penginapan']);
		$('#data_jumlah_kamar_valid').val(callback['e_jumlah_kamar']);
		$('#data_jumlah_hari_valid').val(callback['e_jumlah_hari']);
		$('#data_total_penginapan_valid').val(callback['e_total_penginapan']);
		$('#data_kelas_hotel_valid_old').val(callback['e_kelas_hotel']);
		$('#data_nominal_penginapan_valid_old').val(callback['e_nominal_penginapan']);
		$('#data_jumlah_kamar_valid_old').val(callback['e_jumlah_kamar']);
		$('#data_jumlah_hari_valid_old').val(callback['e_jumlah_hari']);
		$('#data_total_penginapan_valid_old').val(callback['e_total_penginapan']);
		$('#plant_asal_val_old').val(callback['e_plant_asal']);
		$('#plant_tujuan_val_old').val(callback['e_plant_tujuan']);
		$('#tujuan_val_old').val(callback['plant']);
		$('#jarak_val_old').val(callback['e_jarak']);
		samakandgnpengajuan('1');
	}
	function modal_yes(id) {
		var data={no_sk:id};
		$('#m_yes').modal('toggle');
		var callback=getAjaxData("<?php echo base_url('presensi/data_perjalanan_dinas/view_one_trans')?>",data);
		$('#m_yes #data_id_yes').val(callback['no_sk']);
		$('#m_yes .header_data').html(callback['no_sk']);
	}
	function modal_no(id) {
		var data={no_sk:id};
		$('#m_no').modal('toggle');
		var callback=getAjaxData("<?php echo base_url('presensi/data_perjalanan_dinas/view_one_trans')?>",data);
		$('#m_no #data_id_no').val(callback['no_sk']);
		$('#m_no .header_data').html(callback['no_sk']);
	}
  	function do_validasi(data,val,form){
		if(data==2){
			var formx=$('#form_validasi').serialize();
			var id = $('#data_id_nosk_vali').val();
			// var ken = $('#data_kendaraan_val').val();
			// var ken_u = $('#data_kendaraan_umum_val').val();
			// var j_ken = $('#data_jum_ken_val').val();
			// var n_bbm = $('#data_nominal_bbm_val').val();
			// var mng = $('#menginap_val').val();
			// var peng = $('#data_penginapan_val').val();
			// var n_peng = $('#nominal_penginapan_val').val();
			// var datax={no_sk:id,validasi_db:data,validasi:val,kendaraan:ken,kendaraan_umum:ken_u,jumlah_kendaraan:j_ken,nominal_bbm:n_bbm,menginap:mng,penginapan:peng,nominal_penginapan:n_peng,form:form,};
			var datax={no_sk:id,validasi_db:data,validasi:val,form:formx,};
		}else if(data==1){
			var id = $('#data_id_yes').val();
			var datax={no_sk:id,validasi_db:data,validasi:val,};
		}else if(data==0){
			var id = $('#data_id_no').val();
			var datax={no_sk:id,validasi_db:data,validasi:val,};
		}
		submitAjax("<?php echo base_url('presensi/change_status_perjalanan_dinas')?>",form,datax,null,null,'status');
		$('#table_data_trans').DataTable().ajax.reload();
  	}
   	function samakandgnpengajuan(name) {
		// var name = $('input[name="val_samakan"]').val();
		if(name == '1') {
			$('#data_kendaraan_val').val($('#data_kendaraan_kode').val()).trigger('change');
			$('#data_kendaraan_umum_val').val($('#data_kendaraan_umum_kode').val()).trigger('change');
			$('#menginap_val').val($('#menginap_val_old').val());
			$('#data_jum_ken_val').val($('#data_jum_ken_val_old').val());
			$('#data_nominal_bbm_val').val($('#data_nominal_bbm_val_old').val());
			$('#data_nominal_bbm_per_val').val($('#data_nominal_bbm_per_val_old').val());
			$('#data_penginapan_val').val($('#data_penginapan_val_old').val()).trigger('change');
			$('#nominal_penginapan_val').val($('#nominal_penginapan_val_old').val());
			$('#tgl_berangkat_val').val($('#tgl_berangkat_val_old').val());
			$('#tgl_pulang_val').val($('#tgl_pulang_val_old').val());
			$('#data_kelas_hotel_valid').val($('#data_kelas_hotel_valid_old').val()).trigger('change');
		}else {
			$('#data_kendaraan_val').val('').trigger('change');
			$('#data_kendaraan_umum_val').val('').trigger('change');
			$('#menginap_val').val('');
			$('#data_jum_ken_val').val('');
			$('#data_nominal_bbm_val').val('');
			$('#data_nominal_bbm_per_val').val('');
			$('#data_penginapan_val').val('');
			$('#nominal_penginapan_val').val('');
			$('#tgl_berangkat_val').val('');
			$('#tgl_pulang_val').val('');
		}
		var val_p = $('#menginap_val').val();
		if(val_p==1){
			$('#menginap_val_off').hide();
			$('#menginap_val_on').show();
			$('#penginapan_val').show();
			$('#data_penginapan_val').attr('required','required');
		}else{
			$('#menginap_val_off').show();
			$('#menginap_val_on').hide();
			$('#penginapan_val').hide();
			$('#data_penginapan_val').removeAttr('required');
			$('#data_penginapan_val').val('').trigger('change');
		}
   	}
	function modal_proses() {
		var kode = $('input[name="data_kode_view"]').val();
		var data={no_sk:kode};
		var callback=getAjaxData("<?php echo base_url('presensi/data_perjalanan_dinas/view_kode_akun')?>",data); 
		$('#view_u').modal('toggle');
		setTimeout(function () {
			$('#proses_end').modal('show');
		},600); 
		$('.header_data').html(callback['no_sk']);
		$('#data_kode_trans').val(callback['no_sk']);
		$('#tabel_end_proses').html(callback['tabel_end_proses']);
	}
	function myFunction() {
		var kode = $('input[name="data_kode_view"]').val();
		var data={no_sk:kode};
		var callback=getAjaxData("<?php echo base_url('presensi/data_perjalanan_dinas/view_kode_akun')?>",data);
		var table = document.getElementById("myTable");
		var row = table.insertRow(1);
		var cell1 = row.insertCell(0);
		var cell2 = row.insertCell(1);
		var cell3 = row.insertCell(2);
		cell1.innerHTML = callback['select'];
		cell2.innerHTML = callback['nominal'];
		cell3.innerHTML = callback['keterangan'];
	}
	function deleterow() {
		var table = document.getElementById("myTable");
		var row = table.deleteRow(1);
		var cell1 = row.deleteCell(0);
		var cell2 = row.deleteCell(1);
		var cell3 = row.deleteCell(2);
	}
  	function do_end_proses(){
		if($("#form_proses_end")[0].checkValidity()) {
			submitAjax("<?php echo base_url('presensi/add_end_proses_perdin')?>",'proses_end','form_proses_end',null,null);
			$('#table_data_trans').DataTable().ajax.reload();
		}else{
			notValidParamx();
		}
  	}
	function edit_modal_ka(id) {
		getSelect2("<?php echo base_url('presensi/data_perjalanan_dinas/pilihKodeAKun')?>",'data_kode_akun_edit');
		var data={id:id};
		var callback=getAjaxData("<?php echo base_url('presensi/data_perjalanan_dinas/edit_kode_akun')?>",data); 
		$('#view_u').modal('toggle');
		setTimeout(function () {
			$('#edit_kode_akun').modal('show');
		},600); 
		$('.header_data').html(callback['kode_akun']+' '+callback['nama_akun']);
		$('#data_kode_perdin_akun').val(callback['kode_perjalanan_dinas']);
		$('#data_id_kode_akun').val(callback['id']);
		$('#data_kode_akun_edit').val(callback['kode_akun']).trigger('change');
		$('#data_nominal_akun_edit').val(callback['nominal']);
		$('#data_keterangan_akun_edit').val(callback['keterangan']);
	}
	function do_edit_kode_akun(){
		if($("#form_edit_kode_akun")[0].checkValidity()) {
			submitAjax("<?php echo base_url('presensi/edit_kode_akun')?>",'edit_kode_akun','form_edit_kode_akun',null,null);
			$('#table_data').DataTable().ajax.reload();
			$('#table_data_trans').DataTable().ajax.reload();
		}else{
			refreshCode();
			notValidParamx();
		} 
	}
	function delete_modal_ka(id) {
		var data={id:id};
		var callback=getAjaxData("<?php echo base_url('presensi/data_perjalanan_dinas/edit_kode_akun')?>",data);
		var datax={table:'data_pd_kode_akun',column:'id',id:callback['id'],nama:callback['nama_akun'],table_view:'#table_data_trans'};
		$('#view_u').modal('toggle');
		loadModalAjax("<?php echo base_url('pages/load_modal_delete')?>",'modal_delete_partial',datax,'delete');
	}
	function do_print(id) {
		// alert(id);
		// window.location.href = "<?php echo base_url('cetak_word/cetak_perdin/')?>"+id;
		$.redirect("<?php echo base_url('pages/cetak_perjalanan_dinas'); ?>", {
				id: id
			},
			"POST", "_blank");
	}
	function cekPenginapan(kode) {
		if(kode == 'hotel'){
			$('#kelas_hotel').show();
			$('#jumlah_kamar').show();
			$('#jumlah_hari').show();
			$('#total_penginapan').show();
			$('#nominal_penginapan').show();
			select_data('data_kelas_hotel_add',url_select,'master_tipe_hotel','kode','nama');
		}else{
			$('#kelas_hotel').hide();
			$('#jumlah_kamar').hide();
			$('#jumlah_hari').hide();
			$('#total_penginapan').hide();
			$('#nominal_penginapan').hide();
		}
	}
	function cekPenginapanValid(kode) {
		if(kode == 'hotel'){
			$('#kelas_hotel_val').show();
			$('#jumlah_kamar_val').show();
			$('#jumlah_hari_val').show();
			$('#total_penginapan_val').show();
			$('#nominal_penginapan_val').show();
			select_data('data_kelas_hotel_valid',url_select,'master_tipe_hotel','kode','nama');
		}else{
			$('#kelas_hotel_val').hide();
			$('#jumlah_kamar_val').hide();
			$('#jumlah_hari_val').hide();
			$('#total_penginapan_val').hide();
			$('#nominal_penginapan_val').hide();
		}
	}
	function cekPenginapanEdit(kode) {
		if(kode == 'hotel'){
			$('#kelas_hotel_edt').show();
			$('#nominal_penginapan_edt').show();
			$('#jumlah_kamar_edt').show();
			$('#jumlah_hari_edt').show();
			$('#total_penginapan_edt').show();
			select_data('data_kelas_hotel_edit',url_select,'master_tipe_hotel','kode','nama');
		}else{
			$('#kelas_hotel_edt').hide();
			$('#nominal_penginapan_edt').hide();
			$('#jumlah_kamar_edt').hide();
			$('#jumlah_hari_edt').hide();
			$('#total_penginapan_edt').hide();
		}
	}
	function cekTipeHotel(kode) {
		var clx=getAjaxData("<?php echo base_url('presensi/data_perjalanan_dinas/get_tipe_hotel')?>",{kode:kode});
		$('#div_span_ket').html(clx['msg']).css('color','green');
	}
	function cekTipeHotelEdit(kode) {
		var clx=getAjaxData("<?php echo base_url('presensi/data_perjalanan_dinas/get_tipe_hotel')?>",{kode:kode});
		$('#div_span_ket_edit').html(clx['msg']).css('color','green');
	}
	function cekTipeHotelValid(kode) {
		var clx=getAjaxData("<?php echo base_url('presensi/data_perjalanan_dinas/get_tipe_hotel')?>",{kode:kode});
		$('#div_span_ket_valid').html(clx['msg']).css('color','green');
	}
	function cekInputNominalPenginapan(nominal,kelas) {
		var clxx=getAjaxData("<?php echo base_url('presensi/data_perjalanan_dinas/get_tipe_hotel_2')?>",{kode:kelas,nominal:nominal});
		if((clxx['nominal'] < clxx['minimal']) || (clxx['nominal'] > clxx['maksimal'])){
			$('#div_span_nominal').html('Nominal Yang Anda Input Harus Pada Rentang Nilai yang ditentukan').css('color','red');
		}else{
			$('#div_span_nominal').html('').css('color','green');
		}
	}
</script> 
