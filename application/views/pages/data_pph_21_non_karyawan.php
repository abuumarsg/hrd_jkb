<style type="text/css">
	table#table_data thead tr th, table#table_data tbody tr td, table.DTFC_Cloned thead tr th{
		white-space: pre;
	}
	table.DTFC_Cloned tbody{
		overflow: hidden;
	}
</style><div class="content-wrapper">
	<section class="content-header">
		<h1>
			<i class="fa far fa-credit-card"></i> Data PPH-21 Non Karyawan
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
			<li class="active">Data PPH-21 Non Karyawan</li>
		</ol>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title"><i class="fas fa-search fa-fw"></i> Filter Pencarian</h3>
						<div class="box-tools pull-right">
							<button class="btn btn-box-tool" onclick="refreshData()" data-toggle="tooltip" title="Refresh Data"><i class="fas fa-sync"></i></button>
							<button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
							<button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i class="fa fa-times"></i></button>
						</div>
					</div>
					<div style="padding-top: 20px;">
						<div class="box-body">
							<form id="form_filter">
								<div class="col-md-2">
								</div>
								<div class="col-md-8">
									<div class="col-md-4">
										<div class="form-group">
											<label>Pilih Bulan</label>
											<select class="form-control select2" name="bulan" id="search_bulan" style="width: 100%;" required="required">
												<?php
												$bulan_copy = $this->formatter->getMonth();
												echo '<option value="">Pilih Data</option>';
												foreach ($bulan_copy as $buf => $valf) {
													echo '<option value="'.$buf.'">'.$valf.'</option>';
												}
												?>
											</select>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label>Pilih Tahun</label>
											<?php
												$tahun_copy = $this->formatter->getYear();
												$sels = array(date('Y'));
												$exs = array('class'=>'form-control select2', 'id'=>'search_tahun', 'style'=>'width:100%;','required'=>'required');
												echo form_dropdown('tahun',$tahun_copy,$sels,$exs);
											?>
										</div>
									</div>
									<div class="col-md-4">
										<div class="form-group">
											<label>Pembetulan</label>
											<?php
												$koreksi_copy = $this->otherfunctions->getNumberToAbjadList();
												$selsx = array('Pilih Data');
												$exsx = array('class'=>'form-control select2', 'id'=>'search_koreksi', 'style'=>'width:100%;','required'=>'required');
												echo form_dropdown('koreksi',$koreksi_copy,$selsx,$exsx);
											?>
										</div>
									</div>
									<!-- <div class="col-md-6">
										<div class="">
											<label>Pilih Periode</label>
											<select class="form-control select2" name="periode" id="data_periode_filter" style="width: 100%;">
												<?php
											$periode = $this->model_payroll->getListDataPenggajianPph(['a.create_by'=>$id_admin],0,'a.kode_periode');
											echo '<option></option>';
											foreach ($periode as $p) {
												echo '<option value="'.$p->kode_periode.'">'.$p->nama_periode.' ('.$p->nama_sistem_penggajian.') - '.date("Y",strtotime($p->tgl_selesai)).'</option>';
											}
											?>
											</select>
										</div>
									</div>
									<div class="col-md-6">
										<div class="">
											<label>Pilih Sistem Penggajian</label>
											<select class="form-control select2-notclear" name="sistem_penggajian" id="data_sistem_penggajian_filter" style="width: 100%;"></select>
										</div>
									</div> -->
								</div>
							</form>
						</div>
						<div class="box-footer">
							<div class="col-md-12">
								<div class="pull-right">
									<button type="button" onclick="table_filter()" class="btn btn-success"><i class="fa fa-eye"></i> Lihat Data</button>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="box box-success">
					<div class="box-header with-border">
						<h3 class="box-title"><i class="fa fa-list"></i> Daftar Data PPH-21 Non Karyawan</h3>
						<div class="box-tools pull-right">
							<button class="btn btn-box-tool" onclick="reload_table('table_data')" data-toggle="tooltip" title="Refresh Table"><i class="fas fa-sync"></i></button>
							<button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
							<button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i class="fa fa-times"></i></button>
						</div>
					</div>
					<div class="box-body">
						<div class="row">
							<div class="col-md-12">
								<div id="accordion">
									<div class="panel">
										<div class="row">
											<div class="col-md-12">
												<div class="pull-left">
													<?php
													if (in_array($access['l_ac']['add'], $access['access'])) {
														echo '<button href="#add" data-toggle="collapse" id="btn_tambah" data-parent="#accordion" class="btn btn-success" style="float: left;"><i class="fa fa-plus"></i> Tambah PPh 21 Non Karyawan</button> ';
													}
													// if (in_array($access['l_ac']['exp'], $access['access'])) {
													// 	echo '<button type="button" class="btn btn-warning" onclick=do_rekap("excel") style="margin-left: 5px;float: left;"><i class="fas fa-file-excel-o"></i> Export</button>';
													// }
													if (in_array('EXP', $access['access'])) {
														echo '<div class="btn-group">
															<button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown" style="margin-left: 5px;float: left;"><i class="fas fa-file-excel-o"></i> Export
															<span class="fa fa-caret-down"></span></button>
															<ul class="dropdown-menu">
															<li><a onclick=model_export("excel")>Export Data</a></li>
															<li><a onclick=do_rekap_bp_final("bp_final")>Export Data BP Final</a></li>
															</ul>
														</div>';
													}
													if (in_array($access['l_ac']['add'], $access['access'])) {
														echo '<button href="#insert" data-toggle="collapse" id="btn_penunjang" data-parent="#accordion" onclick="insertPenunjang()" class="btn btn-info" style="margin-left: 5px;float: right;"><i class="fa fa-plus"></i> Insert Penunjang</button> ';
													}
													?>
												</div>
												<div class="pull-right" style="font-size: 8pt;">
													<i class="fa fa-toggle-on stat scc"></i> Aktif<br>
													<i class="fa fa-toggle-off stat err"></i> Tidak Aktif
												</div>
											</div>
										</div>
										<div id="add" class="collapse"><br>
											<div class="box">
												<div class="box-header with-border">
													<h3 class="box-title"><i class="fas fa-plus"></i> Tambah Data PPh 21</h3>
												</div>
												<form id="form_add" class="form-horizontal">
													<div class="box-body">
														<input type="hidden" name="usage" value="insert">
														<div class="row">
															<div class="col-md-12">
																<div class="col-md-1"></div>
																<div class="col-md-10">
																	<div class="col-md-4">
																		<div class="form-group">
																			<label>Pilih Bulan</label>
																			<select class="form-control select2" name="bulan" id="search_bulan" style="width: 100%;" required="required">
																				<?php
																				$bulan_copy = $this->formatter->getMonth();
																				echo '<option value="">Pilih Data</option>';
																				foreach ($bulan_copy as $buf => $valf) {
																					echo '<option value="'.$buf.'">'.$valf.'</option>';
																				}
																				?>
																			</select>
																		</div>
																	</div>
																	<div class="col-md-4">
																		<div class="form-group">
																			<label>Pilih Tahun</label>
																			<?php
																				$tahun_copy = $this->formatter->getYear();
																				$sels = array(date('Y'));
																				$exs = array('class'=>'form-control select2', 'id'=>'search_tahun', 'style'=>'width:100%;','required'=>'required');
																				echo form_dropdown('tahun',$tahun_copy,$sels,$exs);
																			?>
																		</div>
																	</div>
																	<div class="col-md-4">
																		<div class="form-group">
																			<label>Pembetulan</label>
																			<?php
																				$koreksi_copy = $this->otherfunctions->getNumberToAbjadList();
																				$selsx = array('Pilih Data');
																				$exsx = array('class'=>'form-control select2', 'id'=>'add_koreksi', 'style'=>'width:100%;','required'=>'required');
																				echo form_dropdown('koreksi',$koreksi_copy,$selsx,$exsx);
																			?>
																		</div>
																	</div>
																</div>
															</div>
														</div>
													</div>
													<div class="box-footer">
														<div id="progress2" style="float: left;"></div>
														<div class="pull-right">
															<button type="button" onclick="do_add()" class="btn btn-success"><i class="fa fa-floppy-o"></i> Tambah Data</button>
														</div>
													</div>
												</form>
											</div>
										</div>
										<div id="insert" class="collapse"><br>
											<div class="box">
												<div class="box-header with-border">
													<h3 class="box-title"><i class="fas fa-plus"></i> Insert Penunjang Data PPh 21 Non Karyawan</h3>
												</div>
												<form id="form_penunjang">
													<div class="row">
														<div class="col-md-12">
															<div class="col-md-2"></div>
															<div class="col-md-8">
																<div class="col-md-6">
																	<div class="form-group">
																		<label>Pilih Bulan</label>
																		<select class="form-control select2" name="bulan" id="pen_bulanx" style="width: 100%;" required="required">
																			<?php
																			$bulan_p = $this->formatter->getMonth();
																			echo '<option value="">Pilih Data</option>';
																			echo '<option value="all_month">Semua Bulan</option>';
																			foreach ($bulan_p as $bup => $valp) {
																				echo '<option value="'.$bup.'">'.$valp.'</option>';
																			}
																			?>
																		</select>
																	</div>
																</div>
																<div class="col-md-6">
																	<div class="form-group">
																		<label>Pilih Tahun</label>
																		<?php
																			$tahun_p = $this->formatter->getYear();
																			$selpx = array(date('Y'));
																			$expx = array('class'=>'form-control select2', 'id'=>'pen_tahunx', 'style'=>'width:100%;','required'=>'required');
																			echo form_dropdown('tahun',$tahun_p,$selpx,$expx);
																		?>
																	</div>
																</div>
																<div class="col-md-12">
																	<div class="form-group">
																		<label>Karyawan</label>
																		<select class="form-control select2" id="karyawan_pen" name="karyawan[]" multiple="multiple" style="width: 100%;"></select>
																	</div>
																	<div id="tabel_end_proses"> </div>
																	<br>
																	<button type="button" class="btn btn-success" onclick="myFunction()"><i class="fa fa-plus"></i> Add</button>
																	<button type="button" class="btn btn-danger" onclick="deleterow()"><i class="fa fa-trash"></i> Delete</button>
																</div>
															</div>
														</div>
													</div>
													<div class="box-footer">
														<button type="button" onclick="do_add_penunjang()" id="btn_edit_end" class="btn btn-success pull-right"><i class="fa fa-floppy-o"></i> Simpan</button>
													</div>
												</form>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<?php $this->date = gmdate("Y-m-d H:i:s", time() + 3600*(7));
									$days = $this->formatter->getDateMonthFormatUser($this->date);
								?>
								<div class="callout callout-info"><label><i class="fa fa-info-circle"></i> Bantuan</label><br>
								<ul>
									<li>List Default menampilkan Data PPh 21 Non Karyawan Pada Bulan <b><?=substr($days,3); ?></b>,</li>
								</div>
								<table id="table_data" class="table table-bordered table-striped table-responsive" width="100%">
									<thead>
										<tr>
											<th>No.</th>
											<th>NIK</th>
											<th>Nama</th>
											<th>Jenis</th>
											<th>Bulan</th>
											<th>Pembetulan</th>
											<th>Status Pajak</th>
											<th>No. NPWP</th>
											<th>Perhitungan Pajak</th>
											<th>Biaya (Rp)</th>
											<th>Premi (Rp)</th>
											<th>Tunjangan PPh (Rp)</th>
											<th>THR (Rp)</th>
											<th>Bruto Sebulan (Rp)</th>
											<th>PKP (Rp)</th>
											<th>PPh Sebulan (Rp)</th>
											<!-- <th>Bruto Setahun (Rp)</th>
											<th>Netto Sebulan (Rp)</th>
											<th>Netto Setahun (Rp)</th>
											<th>Pajak Setahun (Rp)</th>
											<th>PPH Setahun (Rp)</th>
											<th>PPH Sebulan (Rp)</th>
											<th>PPH NPWP (Rp)</th> -->
											<th>Tanggal</th>
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
	</section>
</div> 
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
							<div class="col-md-6" id="data_name_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Alamat</label>
							<div class="col-md-6" id="data_alamat_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">No. Telp</label>
							<div class="col-md-6" id="data_notelp_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Bulan</label>
							<div class="col-md-6" id="data_bulan_view"></div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Kode Pajak</label>
							<div class="col-md-6" id="data_kode_pajak_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Jenis Pajak</label>
							<div class="col-md-6" id="data_jenis_pajak_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Status Pajak</label>
							<div class="col-md-6" id="data_status_pajak_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">No NPWP</label>
							<div class="col-md-6" id="data_npwp_view"></div>
						</div>
					</div>
					<div class="col-md-12"><hr></div>
					<div class="col-md-6">
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Bruto Sebulan</label>
							<div class="col-md-6" id="data_bruto_sebulan_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Bruto Setahun</label>
							<div class="col-md-6" id="data_bruto_setahun_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Netto Sebulan</label>
							<div class="col-md-6" id="data_netto_sebulan_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Netto Setahun</label>
							<div class="col-md-6" id="data_netto_setahun_view"></div>
						</div>

						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Pajak Setahun</label>
							<div class="col-md-6" id="data_pajak_setahun_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">PPH Setahun</label>
							<div class="col-md-6" id="data_pph_setahun_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">PPH Sebulan</label>
							<div class="col-md-6" id="data_pph_sebulan_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">PPH NPWP</label>
							<div class="col-md-6" id="data_pph_npwp_view"></div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Status</label>
							<div class="col-md-6" id="data_status_view"></div>
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
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
			</div>
		</div>
	</div>
</div>
<div id="confirm_ada_data" class="modal fade" role="dialog">
	<div class="modal-dialog modal-md modal-info">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title text-center">Data Sudah Ada</h4>
			</div>
			<div class="modal-body text-center">
				<form id="form_ada_data">
					<input type="hidden" name="usage" value="update">
					<input type="hidden" name="bulan" id="bulan_ada_data">
					<input type="hidden" name="tahun" id="tahun_ada_data">
					<input type="hidden" name="koreksi" id="koreksi_ada_data">
					<p>Data PPh 21 Pada Bulan & Tahun Tersebut Sudah Ada, <br>Apakah anda yakin ingin memperbarui data?
					</p>
				</form>
			</div>
			<div class="modal-footer">
				<div id="progressUpdate" style="float: left;"></div>
				<button type="button" onclick="do_update_ada_data()" class="btn btn-success"><i class="fas fa-chevron-circle-right"></i>
					Lanjutkan</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
			</div>
		</div>
	</div>
</div>
<div id="alert" class="modal fade" role="dialog">
	<div class="modal-dialog modal-sm modal-danger">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title text-center"><i class="fas fa-exclamation-triangle"></i> Alert!</h4>
			</div>
			<div class="modal-body text-center">
				<p>Silahkan Pilih Periode!</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
			</div>
		</div>
	</div>
</div>

<div id="rekap_mode" class="modal fade" role="dialog">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title text-center"><i class="fas fa-file-export"></i> Rekap Mode</h4>
			</div>
			<div class="modal-body">
				<form id="form_print" class="form-horizontal">
					<div class="row">
						<div class="col-md-12">
							<h4 class="text-center"><b>Pilih Tipe Rekap</b></h4>
							<div class="clearfix text-center">
								<div class="col-md-6">
									<span id="bulan_off" style="font-size: 20px;"><i class="far fa-square" aria-hidden="true"></i></span>
									<span id="bulan_on" style="display: none; font-size: 20px;"><i class="far fa-check-square" aria-hidden="true"></i></span>&nbsp;&nbsp;
									<span style="padding-bottom: 9px;vertical-align: middle;"><b>BULANAN</b></span>
								</div>
								<div class="col-md-6">
									<span id="tahun_off" style="font-size: 20px;"><i class="far fa-square" aria-hidden="true"></i></span>
									<span id="tahun_on" style="display: none; font-size: 20px;"><i class="far fa-check-square" aria-hidden="true"></i></span>&nbsp;&nbsp;
									<span style="padding-bottom: 9px;vertical-align: middle;"><b>TAHUNAN</b></span>
								</div>
								<input type="hidden" name="tipe_rekap" id="tipe_rekapx">
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-1"></div>
						<div class="col-md-10">
							<div id="format_bulan" style="display:none">
								<div class="form-group">
									<label>Pilih Bulan</label>
									<select class="form-control select2" name="bulan" id="search_bulanx" style="width: 100%;" required="required">
										<?php
										$bulan_copyx = $this->formatter->getMonth();
										echo '<option value="">Pilih Data</option>';
										echo '<option value="all_month">Semua Bulan</option>';
										foreach ($bulan_copyx as $bufx => $valfx) {
											echo '<option value="'.$bufx.'">'.$valfx.'</option>';
										}
										?>
									</select>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-1"></div>
						<div class="col-md-10">
							<div class="form-group">
								<label>Pilih Tahun</label>
								<?php
									$tahun_copyx = $this->formatter->getYear();
									$selsx = array(date('Y'));
									$exsx = array('class'=>'form-control select2', 'id'=>'search_tahunx', 'style'=>'width:100%;','required'=>'required');
									echo form_dropdown('tahun',$tahun_copyx,$selsx,$exsx);
								?>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-1"></div>
						<div class="col-md-10">
							<div class="form-group">
								<label>Pembetulan</label>
								<?php
									$koreksi_exp = $this->otherfunctions->getNumberToAbjadList();
									$selsxp = array('Pilih Data');
									$exsxp = array('class'=>'form-control select2', 'id'=>'export_koreksi', 'style'=>'width:100%;','required'=>'required');
									echo form_dropdown('koreksi',$koreksi_exp,$selsxp,$exsxp);
								?>
							</div>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<div class="col-md-12 pull-right" style="text-align: right;">
					<button type="button" class="btn btn-primary" onclick="do_rekap('excel')"><i class="fas fa-file-excel fa-fw"></i>Excel</button>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="modal_delete_partial"></div>
<script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
<script type="text/javascript">
	$(document).ready(function(){
		var url_select="<?php echo base_url('global_control/select2_global');?>";
		select_data('data_sistem_penggajian_filter',url_select,'master_sistem_penggajian','kode_master_penggajian','nama');
		var bulan = "<?=date('m')?>";
		var tahun = "<?=date('Y')?>";
		var koreksi = "0";
		table_data(bulan, tahun, koreksi);
		$('#bulan_off').click(function(){
			$('#bulan_off').hide();
			$('#bulan_on').show();
			$('#tahun_on').hide();
			$('#tahun_off').show();
		    $('#format_bulan').show();
			$('input[name="tipe_rekap"]').val('1');
		})
		$('#bulan_on').click(function(){
			$('#bulan_off').show();
			$('#bulan_on').hide();
			$('#tahun_off').hide();
			$('#tahun_on').show();
		    $('#format_bulan').hide();
			$('input[name="tipe_rekap"]').val('0');
		});
		$('#tahun_off').click(function(){
			$('#tahun_off').hide();
			$('#tahun_on').show();
			$('#bulan_on').hide();
			$('#bulan_off').show();
		    $('#format_bulan').hide();
			$('input[name="tipe_rekap"]').val('0');
		});
		$('#tahun_on').click(function(){
			$('#tahun_off').show();
			$('#tahun_on').hide();
			$('#bulan_off').hide();
			$('#bulan_on').show();
		    $('#format_bulan').show();
			$('input[name="tipe_rekap"]').val('1');
		});
		$('#pen_bulanx, #pen_tahunx').change(function(){
			var bl = $('#pen_bulanx').val();
			var th = $('#pen_tahunx').val();
			var data = {bulan:bl,tahun:th};
			var callback = getAjaxData("<?php echo base_url('cpayroll/data_pph_21_non/karyawan/')?>", data);
			$('#karyawan_pen').html(callback['karyawan']);
		});
	});
	function table_filter() {
		var bulan = $('#search_bulan').val();
		var tahun = $('#search_tahun').val();
		var koreksi = $('#search_koreksi').val();
		table_data(bulan,tahun,koreksi);
	}
	function table_data(bulan, tahun, koreksi) {
		// alert(koreksi);
		$('#table_data').DataTable().destroy();
		setTimeout(function () {
			$('#table_data').DataTable( {
				ajax: {
					url: "<?php echo base_url('cpayroll/data_pph_21_non/view_all/')?>",
					type: 'POST',
					data:{access:"<?php echo $this->codegenerator->encryptChar($access);?>",bulan:bulan,tahun:tahun,koreksi:koreksi}
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
				{   targets: 16, 
					width: '10%',
					render: function ( data, type, full, meta ) {
						return '<center>'+data+'</center>';
					}
				},
				]
			});
		},600); 
	}
	function refreshData() {
		$('#data_periode_filter').val('').trigger('change');
		$('#data_sistem_penggajian_filter').val('').trigger('change');
		setTimeout(function () {
			table_filter()
		},600); 
	}
	function view_modal(id) {
		var data={id_pph21:id, mode: 'view'};
		var callback=getAjaxData("<?php echo base_url('cpayroll/data_pph_21_non/view_one')?>",data);  
		$('#view').modal('show');
		$('.header_data').html(callback['nama']);//+' - '+callback['nama_periode']+' ( '+callback['nama_sistem_penggajian']+' )');
		$('input[name="data_id_view"]').val(callback['id']);
		$('#data_nik_view').html(callback['nik']);
		$('#data_name_view').html(callback['nama']);
		$('#data_alamat_view').html(callback['alamat']);
		$('#data_notelp_view').html(callback['no_telp']);
		$('#data_bulan_view').html(callback['bulan']);
		$('#data_kode_pajak_view').html(callback['kode_pajak']);
		$('#data_jenis_pajak_view').html(callback['jenis_pajak']);
		$('#data_status_pajak_view').html(callback['status_pajak']);
		$('#data_npwp_view').html(callback['npwp']);
		$('#data_bruto_sebulan_view').html(callback['bruto_sebulan']);
		$('#data_bruto_setahun_view').html(callback['bruto_setahun']);
		$('#data_netto_sebulan_view').html(callback['netto_sebulan']);
		$('#data_netto_setahun_view').html(callback['netto_setahun']);
		$('#data_pajak_setahun_view').html(callback['pajak_setahun']);
		$('#data_pph_setahun_view').html(callback['pph_setahun']);
		$('#data_pph_sebulan_view').html(callback['pph_sebulan']);
		$('#data_pph_npwp_view').html(callback['pph_npwp']);
		var status = callback['status'];
		if(status==1){
			var statusval = '<b class="text-success">Aktif</b>';
		}else{
			var statusval = '<b class="text-danger">Tidak Aktif</b>';
		}
		$('#data_status_view').html(statusval);
		$('#data_create_date_view').html(callback['create_date']+' WIB');
		$('#data_update_date_view').html(callback['update_date']+' WIB');
		$('#data_create_by_view').html(callback['nama_buat']);
		$('#data_update_by_view').html(callback['nama_update']);
	}
	function do_add() {
		$('#progress2').html('<i class="fa fa-spinner fa-pulse fa-fw"></i> Mohon Tunggu, sedang memproses data....');
		$('#progress2').show();
		var data = $('#form_add').serialize();
		var cek_ready = getAjaxData("<?php echo base_url('cpayroll/cek_data_pph_non')?>", data);
		if (cek_ready['msg'] == 'true') {
			submitAjax("<?php echo base_url('cpayroll/ready_data_pph_non')?>", null, 'form_add', null, null);
			reload_table('table_data');
			$('#progress2').hide();
		} else if (cek_ready['msg'] == 'ada_data') {
			$('#progress2').hide();
			$('#confirm_ada_data').modal('show');
			$('#bulan_ada_data').val(cek_ready['bulan']);
			$('#tahun_ada_data').val(cek_ready['tahun']);
			$('#koreksi_ada_data').val(cek_ready['koreksi']);
		} else {
			$('#progress2').hide();
		}
	}
	function do_update_ada_data() {
		$('#progressUpdate').html('<i class="fa fa-spinner fa-pulse fa-fw"></i> Mohon Tunggu, sedang memproses data....');
		$('#progressUpdate').show();
		submitAjax("<?php echo base_url('Cpayroll/ready_data_pph_non')?>", 'confirm_ada_data', 'form_ada_data', null, null);
		$('#progressUpdate').hide();
		$('#table_data').DataTable().ajax.reload(function () {
			Pace.restart();
		});
	}
	// function model_export() {
	// 	$('#rekap_mode').modal('show');
	// }

	function do_rekap_bp_final(file) {
		var bulan = $('#search_bulan').val();
		var tahun = $('#search_tahun').val();
		var koreksi = $('#search_koreksi').val();
		if(file == 'excel'){
			$.redirect('<?php echo base_url()."rekap/rekap_data_pph_21_non/"; ?>', 
			{
				bulan:bulan,tahun:tahun,koreksi:koreksi
			}, "POST", "_blank");
		}else if(file == 'bp_final'){
			$.redirect('<?php echo base_url()."rekap/rekap_data_pph_21_bp_final/"; ?>', 
			{
				bulan:bulan,tahun:tahun,koreksi:koreksi
			}, "POST", "_blank");
		}
	}
	function insertPenunjang() {
		var data={no_sk:null};
		var callback=getAjaxData("<?php echo base_url('cpayroll/data_pph_21_non/insert_penunjang')?>",data);
		$('#tabel_end_proses').html(callback['tabel_end_proses']);
	}
	function myFunction() {
		var data={no_sk:null};
		var callback=getAjaxData("<?php echo base_url('cpayroll/data_pph_21_non/insert_penunjang')?>",data);
		var table = document.getElementById("myTable");
		var row = table.insertRow(1);
		var cell1 = row.insertCell(0);
		var cell2 = row.insertCell(1);
		cell1.innerHTML = callback['select'];
		cell2.innerHTML = callback['nominal'];
	}
	function deleterow() {
		var table = document.getElementById("myTable");
		var row = table.deleteRow(1);
		var cell1 = row.deleteCell(0);
		var cell2 = row.deleteCell(1);
	}
  	function do_add_penunjang(){
		if($("#form_penunjang")[0].checkValidity()) {
			submitAjax("<?php echo base_url('cpayroll/add_penunjang_non')?>",null,'form_penunjang',null,null);
			$('#table_data').DataTable().ajax.reload();
		}else{
			notValidParamx();
		}
  	}
	function model_export() {
		$('#rekap_mode').modal('show');
	}
	function do_rekap(file) {
		var tipe = $('#tipe_rekapx').val();
		var bulan = $('#search_bulanx').val();
		var tahun = $('#search_tahunx').val();
		var koreksi = $('#export_koreksi').val();
		if(tipe == '1'){
			if(bulan == 'all_month'){
				if(file == 'excel'){
					$.redirect('<?php echo base_url()."rekap/rekap_data_pph_21_non_all/"; ?>', 
					{
						tipe: tipe,
						bulan: bulan,
						tahun: tahun,
						koreksi: koreksi,
					}, "POST", "_blank");
				}
			}else{
				if(file == 'excel'){
					$.redirect('<?php echo base_url()."rekap/rekap_data_pph_21_non/"; ?>', 
					{
						tipe: tipe,
						bulan: bulan,
						tahun: tahun,
						koreksi: koreksi,
					}, "POST", "_blank");
				}
			}
		}else{
			if(file == 'excel'){
				$.redirect('<?php echo base_url()."rekap/rekap_data_pph_21_non_tahunan/"; ?>', 
				{
					tipe: tipe,
					bulan: bulan,
					tahun: tahun,
					koreksi: koreksi,
				}, "POST", "_blank");
			}
		}
	}
	function delete_modal(id) {
		var data={id_pph21:id};
		var callback=getAjaxData("<?php echo base_url('cpayroll/data_pph_21_non/view_one')?>",data);
		var datax={table:'data_penggajian_pph21_non',column:'id_pph21',id:callback['id_pph21'],nama:callback['nama']};
		loadModalAjax("<?php echo base_url('pages/load_modal_delete')?>",'modal_delete_partial',datax,'delete');
	}
</script>