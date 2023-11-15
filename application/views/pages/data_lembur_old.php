<style>
	.date-range-noreadonly {
		z-index: 1600 !important;
	}
</style>
<div class="content-wrapper">
	<section class="content-header">
		<h1>
			<i class="fa fas fa-calendar-plus fa-fw"></i> Data
			<small> Lembur</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fa fas fa-tachometer-alt"></i> Dashboard</a></li>
			<li class="active"><i class="fa fas fa-calendar-plus fa-fw"></i> Lembur</li>
		</ol>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-info">
					<div class="box-header with-border">
						<h3 class="box-title"><i class="fa fas fa-calendar-plus fa-fw"></i> Data Lembur</h3>
						<div class="box-tools pull-right">
							<button class="btn btn-box-tool" onclick="transaksi('all')" data-toggle="tooltip" title="Refresh Table"><i class="fas fa-sync"></i></button>
							<button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
							<button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i class="fa fa-times"></i></button>
						</div>
					</div>
					<div class="box-body">
						<div class="nav-tabs-custom">
							<ul class="nav nav-tabs">
								<li class="active"><a onclick="transaksi('all')" href="#transaksi" data-toggle="tab"><i class="fa fa-edit"></i> Data Per Transaksi</a></li>
								<li><a onclick="kar('tab')" href="#kar" data-toggle="tab"><i class="fa fa-user"></i> Data Per Karyawan</a></li>
							</ul>
							<div class="tab-content">
								<div class="tab-pane active" id="transaksi">
									<div class="box-header with-border">
										<h3 class="box-title"><i class="fa fa-search"></i> Advance Filter Data</h3>
										<div class="box-tools pull-right">
											<button class="btn btn-box-tool" onclick="refreshData()" data-toggle="tooltip" title="Refresh Data"><i class="fas fa-sync"></i></button>
										</div>
									</div>
									<div style="padding-top: 10px;">
										<form id="form_filter">
											<input type="hidden" name="param" value="all">
											<input type="hidden" name="mode" value="data">
											<div class="box-body">
												<div class="row">
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
														<div class="">
															<label>Tanggal</label>
															<div class="has-feedback">
																<span class="fa fa-calendar form-control-feedback"></span>
																<input type="text" class="form-control date-range-notime" id="tanggal_filter" name="tanggal_filter" placeholder="Tanggal">
															</div>
														</div>
													</div>
												</div>
												<div class="row">
													<div class="col-md-2"></div>
													<div class="col-md-4">
														<div class="form-group">
															<label>Status Validasi</label>
															<?php
															$status_validasi = $this->otherfunctions->getStatusIzinListRekap();
															$sel6 = array(2);
															$exsel6 = array('class'=>'form-control select2','placeholder'=>'Status Validasi','id'=>'status_validasi','style'=>'width:100%');
															echo form_dropdown('status_validasi',$status_validasi,$sel6,$exsel6);
															?>
														</div>
													</div>
													<?php if (in_array($access['l_ac']['adjust_lembur'], $access['access'])) { ?>
													<div class="col-md-4">
														<div class="">
															<label>Status Penyesuaian</label>
															<?php
															$potong_jam[null] = 'Pilih Data';
															$potong_jam = $this->otherfunctions->getStatusPotongJam();
															$sel1 = [null];
															$exsel1 = array('class'=>'form-control select2','placeholder'=>'Status Potong Jam','id'=>'potong_jam','style'=>'width:100%');
															echo form_dropdown('potong_jam',$potong_jam,$sel1,$exsel1);
															?>
														</div>
													</div>
													<?php } ?>
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
									<div id="accordion">
										<div class="panel">
											<?php 
												if (in_array($access['l_ac']['add'], $access['access'])) {
													echo '<a href="#tambah" data-toggle="collapse" id="btn_tambah" data-parent="#accordion" class="btn btn-success "><i class="fa fa-plus"></i> Tambah Lembur</a> '; }
												if (in_array($access['l_ac']['rkp'], $access['access'])) {
													// echo '<input type="hidden" name="param" value="all">';
													// echo '<button type="button" onclick="rekap()" id="btn_print_excel"class="btn btn-warning" ><i class="fa fa-file-excel-o"></i> Export Data</button> ';
													echo '<div class="btn-group">
                                                         <button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown"><i class="fa fa-file-excel-o"></i> Export Data 
                                                         <span class="fa fa-caret-down"></span></button> 
                                                         <ul class="dropdown-menu">
                                                            <li><a onclick="rekap()" style="margin-right: 6px;"> Export Data</a></li>';
															if (in_array($access['l_ac']['rekap_req'], $access['access'])) {
                                                            	echo '<li><a onclick="rekap_req()" style="margin-right: 6px;"> Export Data Lembur & Perhitungan</a></li>';
															}
                                                        echo '</ul>
                                                      </div> ';
												}
												if (in_array($access['l_ac']['adjust_lembur'], $access['access'])) {
													echo '<button type="button" onclick="modal_massal()" id="btn_print_excel" class="btn btn-primary" ><i class="fa fa-check-circle"></i> Validasi Masal</button> '; 
												}
												if (in_array($access['l_ac']['adjust_lembur'], $access['access'])) {
													echo '<button type="button" onclick="modal_reset_nomor()" id="btn_reset_nomor" class="btn btn-danger" ><i class="fa fa-refresh"></i> Reset Nomor Lembur</button>'; 
												}
												if (in_array($access['l_ac']['add'], $access['access'])) { 
											?>
											<div id="tambah" class="collapse">
												<br>
												<div class="box box-success">
													<div class="box-header with-border">
														<h3 class="box-title"><i class="fa fa-plus"></i> Tambah Lembur</h3>
													</div>
													<form id="form_add" class="form-horizontal">
														<div class="box-body">
														<div class="callout callout-info"><label><i class="fa fa-info-circle"></i> Bantuan</label><br> Untuk meminimalisir Nomor Surat Sama, Silahkan klik <b><i class="fa fa-refresh"></i></b> disamping kolom nomor surat sebelum menyimpan</div>
															<div class="row">
																<div class="col-md-12">
																	<div class="col-md-6">
																		<div class="form-group">
																			<label class="col-sm-3 control-label">Nomor SPL</label>
																			<div class="col-sm-8">
																				<input type="text" name="kode_spl" id="kode_spl_add" class="form-control" required="required" readonly="readonly" placeholder="Nomor SPL">
																			</div>
																			<div class="col-sm-1">
																				<span class="control-label" style="vertical-align: middle;"><i class="fa fa-refresh" style="font-size: 16px;cursor:pointer;" onclick="refreshCode()"></i></span>
																			</div>
																		</div>
																		<div class="form-group">
																			<label class="col-sm-3 control-label">Tanggal Mulai - Selesai</label>
																			<div class="col-sm-9">
																				<div class="has-feedback">
																					<span class="fa fa-calendar form-control-feedback"></span>
																					<input type="text" name="tanggal" class="form-control pull-right date-range" placeholder="Tanggal Lembur" readonly="readonly">
																				</div>
																			</div>
																		</div>
																		<div class="form-group">
																			<label class="col-sm-3 control-label">Dibuat Oleh</label>
																			<div class="col-sm-9">
																				<select class="form-control select2" name="id_dibuat" id="id_dibuat_add" required="required" style="width: 100%;"></select>
																			</div>
																		</div>
																		<div class="form-group">
																			<label class="col-sm-3 control-label">Diperiksa Oleh</label>
																			<div class="col-sm-9">
																				<select class="form-control select2" name="id_diperiksa" id="id_diperiksa_add" required="required" style="width: 100%;"></select>
																			</div>
																		</div>
																		<div class="form-group">
																			<label class="col-sm-3 control-label">Diketahui Oleh</label>
																			<div class="col-sm-9">
																				<select class="form-control select2" name="id_diketahui" id="id_diketahui_add" required="required" style="width: 100%;"></select>
																			</div>
																		</div>
																	</div>
																	<div class="col-md-6">
																		<div class="form-group">
																			<label class="col-sm-3 control-label">Jenis Lembur</label>
																			<div class="col-sm-9">
																				<?php
																				$jenisLembur[null] = 'Pilih Data';
																				$selw = [null];
																				$exselw = array('class'=>'form-control select2','placeholder'=>'Jenis Lembur','required'=>'required','id'=>'data_jenis_lembur_add','style'=>'width:100%');
																				echo form_dropdown('jenis_lembur',$jenisLembur,$selw,$exselw);
																				?>
																			</div>
																		</div>
																		<div class="form-group">
																			<label class="col-sm-3 control-label">Tanggal Buat</label>
																			<div class="col-sm-9">
																				<div class="has-feedback">
																					<span class="fa fa-calendar form-control-feedback"></span>
																					<input type="text" name="tgl_buat" id="tgl_buat_add" class="form-control pull-right date-picker" placeholder="Tanggal Buat" readonly="readonly">
																				</div>
																			</div>
																		</div>
																		<!-- <div class="form-group">
																			<label class="col-sm-3 control-label">Potong Jam Istirahat</label>
																			<div class="col-sm-9">
																				<a id="potong_no" style="font-size: 16pt;"><i class="far fa-square"></i></a>
																				<a id="potong_yes" style="font-size: 16pt;display: none;"><i class="fa fa-check-square"></i></a>
																				<input type="hidden" name="jam_istirahat" id="potong_add" class="form-control" placeholder="Potong Jam Istirahat" readonly>
																			</div>
																		</div> -->
																		<div class="form-group">
																			<label class="col-sm-3 control-label">Kode Customer</label>
																			<div class="col-sm-9">
																				<input type="text" name="kode_customer" id="kode_customer_add" class="form-control" placeholder="Kode Customer">
																			</div>
																		</div>
																		<div class="form-group">
																			<label class="col-sm-3 control-label">Keterangan (Kegiatan)</label>
																			<div class="col-sm-9">
																				<textarea name="keterangan" id="keterangan_add" class="form-control" placeholder="Keterangan"></textarea>
																			</div>
																		</div>
																	</div>
																</div>
															</div>
															<div class="row">
																<div class="col-md-10">
																	<h3 class="box-title">Pilih Karyawan</h3>
																	<div class="form-group">
																		<label class="col-sm-3 control-label">Pilih Bagian</label>
																		<div class="col-sm-9">
																			<select class="form-control select2" name="bagian" id="bagian_add" style="width: 100%;" onchange="get_selet_emp(this.value)"></select>
																		</div>
																	</div>
																	<div class="form-group">
																		<label class="col-sm-3 control-label">Pilih Karyawan</label>
																		<div class="col-sm-9">
																			<select class="form-control select2" name="karyawan[]" id="karyawan_add" required="required" style="width: 100%;" multiple="multiple"></select>
																		</div>
																	</div>
																</div>
															</div>
														</div>
														<div class="box-footer">
															<div class="pull-right">
																<button type="submit" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
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
											<?php $this->date = gmdate("Y-m-d H:i:s", time() + 3600*(7));
												$days = $this->formatter->getDateMonthFormatUser($this->date);
											?>
											<div class="callout callout-info"><label><i class="fa fa-info-circle"></i> Bantuan</label><br>
											<ul>
												<li>List Default menampilkan Data Lembur Pada Bulan <b><?=substr($days,3); ?></b>,</li>
												<li>Pilih dikolom <b>Status Validasi</b> Untuk memvalidasi data lembur,</li>
												<?php if (in_array($access['l_ac']['adjust_lembur'], $access['access'])) {
													echo '<li>Jika pada kolom NO SPL bertanda <i class="fa fa-check-circle" style="color:green;"></i>, maka data lembur sudah disesuikan potongan lembur, dan begitu juga sebaliknya jika bertanda <i class="fa fa-times-circle" style="color:red;"></i> berarti belum di sesuaikan.</li>';
												} ?>
											</ul>
											</div>
											<table id="table_data" class="table table-bordered table-striped" width="100%">
												<thead>
													<tr>
														<th>No</th>
														<th>NO SPL</th>
														<th>Tanggal Lembur</th>
														<th>Diajukan Oleh</th>
														<th>Diajukan Tanggal</th>
														<th>Jumlah Karyawan</th>
														<th>Status Validasi</th>
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
								<div class="tab-pane" id="kar">
									<div class="box-header with-border">
										<h3 class="box-title"><i class="fa fa-search"></i> Advance Filter Data</h3>
										<div class="box-tools pull-right">
											<button class="btn btn-box-tool" onclick="refreshDataKar()" data-toggle="tooltip" title="Refresh Data"><i class="fas fa-sync"></i></button>
										</div>
									</div>
									<div style="padding-top: 10px;">
										<form id="form_filter_kar">
											<input type="hidden" name="param" value="all">
											<input type="hidden" name="mode" value="data">
											<div class="box-body">
												<div class="col-md-4">
													<div class="form-group">
														<label>Pilih Bagian</label>
														<select class="form-control select2" id="bagian_export_kar" name="bagian_export_kar" style="width: 100%;"></select>
													</div>
												</div>
												<div class="col-md-4">
													<div class="form-group">
														<label>Pilih Lokasi Kerja</label>
														<select class="form-control select2" id="unit_export_kar" name="unit_export_kar" style="width: 100%;"></select>
													</div>
												</div>
												<div class="col-md-4">
													<div class="">
														<label>Tanggal</label>
														<div class="has-feedback">
															<span class="fa fa-calendar form-control-feedback"></span>
															<input type="text" class="form-control date-range-notime" id="tanggal_filter_kar" name="tanggal_filter_kar" placeholder="Tanggal">
														</div>
													</div>
												</div>
											</div>
											<div class="box-footer">
												<div class="col-md-12">
													<div class="pull-right">
														<button type="button" onclick="tableDataKar('search')" class="btn btn-success"><i class="fa fa-eye"></i> Lihat Data</button>
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
													echo '<button type="button" onclick="rekap_kar()" id="btn_print_excel" class="btn btn-warning" ><i class="fa fa-file-excel-o"></i> Export Data</button>';
												}
											?>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<div class="callout callout-info"><label><i class="fa fa-info-circle"></i> Bantuan</label><br>Pilih <b>NIK</b> (Nomor Induk Karyawan) Untuk melihat detail data Lembur karyawan yang Anda pilih</div>
											<table id="table_data_kar" class="table table-bordered table-striped" width="100%">
												<thead>
													<tr>
														<th>No</th>
														<th>NIK</th>
														<th>Nama</th>
														<th>Jabatan</th>
														<th>Bagian</th>
														<th>Lokasi Kerja</th>
														<th>Tanggal Lembur</th>
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
<!-- view -->
<div id="view" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h2 class="modal-title">Detail Data Lembur <b class="text-muted header_data"></b></h2>
				<input type="hidden" name="data_id_view">
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">No Lembur</label>
							<div class="col-md-6" id="data_no_lembur_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Tanggal Mulai</label>
							<div class="col-md-6" id="data_tanggal_m_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Tanggal Selesai</label>
							<div class="col-md-6" id="data_tanggal_s_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Jenis Lembur</label>
							<div class="col-md-6" id="data_jenis_lembur_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Lama Lembur</label>
							<div class="col-md-6" id="data_lama_lembur_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Potong Jam Istirahat</label>
							<div class="col-md-6" id="data_potong_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Keterangan (Kegiatan)</label>
							<div class="col-md-6" id="data_keterangan_view"></div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Status</label>
							<div class="col-md-6" id="data_status_val_view"></div>
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
				<div class="row" id="div_data_after_val" style="display:none;">
					<div class="col-md-12">
						<div class="data_detail" id="data_after_val"></div>
					</div>
				</div>
				<hr>
				<div class="row">
					<div class="col-md-12">
						<div class="col-md-12" style="overflow: auto;">
							<div id="data_tabel_view"></div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<?php if (in_array($access['l_ac']['edit_spl'], $access['access'])) {
					echo '<button type="button" id="btn_edit_spl" class="btn btn-danger" onclick="edit_spl()"><i class="fa fa-edit"></i> Edit No SPL</button>';
				}?>
				<?php if (in_array($access['l_ac']['edt'], $access['access'])) {
					echo '<button type="button" id="btn_edit_view" class="btn btn-info" onclick="edit_modal()"><i class="fa fa-edit" style="display:none:"></i> Edit</button>';
				}?>
				<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
			</div>
		</div>
	</div>
</div>
<!-- edit -->
<div id="edit" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h2 class="modal-title">Detail Data Lembur <b class="text-muted header_data"></b></h2>
			</div>
			<form id="form_edit">
				<div class="modal-body">
					<div class="row">
						<div class="col-md-6">
							<input type="hidden" id="data_id_edit" name="id" value="">
							<input type="hidden" name="karyawan_old" id="karyawan_old">
							<input type="hidden" name="validasi_old" id="validasi_edit">
							<div class="form-group clearfix">
								<label>Nomor SPL</label>
								<input type="text" name="kode_spl" id="kode_spl_edit" class="form-control" placeholder="Nomor SPL" readonly>
							</div>
							<div class="form-group clearfix">
								<label>Karyawan</label>
								<select class="form-control select2" name="id_karyawan[]" id="karyawan_edit_trans" required="required" style="width: 100%;" multiple="multiple"></select>
							</div>
							<div class="form-group clearfix">
								<label>Tanggal Mulai - Selesai</label>
								<input type="text" name="tanggal" id="data_tanggal_edit" class="form-control date-range" value="" placeholder="Tanggal Lembur" readonly="readonly" required="required">
							</div>
							<div class="form-group clearfix">
								<label>Dibuat Oleh</label>
								<select class="form-control select2" name="id_dibuat" id="id_dibuat_edit" style="width: 100%;"></select>
							</div>
							<div class="form-group clearfix">
								<label>Diperiksa Oleh</label>
								<select class="form-control select2" name="id_diperiksa" id="id_diperiksa_edit" style="width: 100%;"></select>
							</div>
							<div class="form-group clearfix">
								<label>Diketahui Oleh</label>
								<select class="form-control select2" name="id_diketahui" id="id_diketahui_edit" style="width: 100%;"></select>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group clearfix">
								<label>Jenis Lembur</label>
								<?php
									$jenisLembur[null] = 'Pilih Data';
									$selwe = [null];
									$exselwe = array('class'=>'form-control select2','placeholder'=>'Jenis Lembur','required'=>'required','id'=>'data_jenis_lembur_edit','style'=>'width:100%');
									echo form_dropdown('jenis_lembur',$jenisLembur,$selwe,$exselwe);
								?>
							</div>
							<div class="form-group clearfix">
								<label>Tanggal Buat</label>
								<div class="has-feedback">
									<span class="fa fa-calendar form-control-feedback"></span>
									<input type="text" name="tgl_buat" id="tgl_buat_edit" class="form-control date-picker" placeholder="Tanggal Buat" readonly="readonly">
								</div>
							</div>
							<div class="form-group clearfix">
								<label>Kode Customer</label>
								<input type="text" name="kode_customer" id="kode_customer_edit" class="form-control" placeholder="Kode Customer">
							</div>
							<div class="form-group clearfix">
								<label>Keterangan (Kegiatan)</label>
								<textarea name="keterangan" id="keterangan_edit" class="form-control" placeholder="Keterangan"></textarea>
							</div>
						</div>
					</div>
					<hr>
					<div class="row">
						<div class="col-md-12">
							<div class="col-md-12" style="overflow: auto;">
								<div id="data_tabel_view"></div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
				</div>
			</form>
		</div>
	</div>
</div>
<?php if (in_array($access['l_ac']['edit_spl'], $access['access'])) { ?>
<div id="edit_spl" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h2 class="modal-title">Edit No Lembur <b class="text-muted header_data"></b></h2>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">No Lembur</label>
							<div class="col-md-6" id="data_no_lembur_spl"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Tanggal Mulai</label>
							<div class="col-md-6" id="data_tanggal_m_spl"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Tanggal Selesai</label>
							<div class="col-md-6" id="data_tanggal_s_spl"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Jenis Lembur</label>
							<div class="col-md-6" id="data_jenis_lembur_spl"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Lama Lembur</label>
							<div class="col-md-6" id="data_lama_lembur_spl"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Potong Jam Istirahat</label>
							<div class="col-md-6" id="data_potong_spl"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Keterangan (Kegiatan)</label>
							<div class="col-md-6" id="data_keterangan_spl"></div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Status</label>
							<div class="col-md-6" id="data_status_val_spl"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Dibuat Tanggal</label>
							<div class="col-md-6" id="data_create_date_spl"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Diupdate Tanggal</label>
							<div class="col-md-6" id="data_update_date_spl"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Dibuat Oleh</label>
							<div class="col-md-6" id="data_create_by_spl">
							</div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Diupdate Oleh</label>
							<div class="col-md-6" id="data_update_by_spl">
							</div>
						</div>
					</div>
				</div>
				<hr>
				<div class="row">
					<div class="col-md-12">
						<div class="col-md-12" style="overflow: auto;">
							<div id="data_tabel_spl"></div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" onclick="do_edit_spl()" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
			</div>
		</div>
	</div>
</div>
<?php } ?>
<?php if (in_array($access['l_ac']['val_lembur'], $access['access'])) { ?>
	<div id="m_validasi" class="modal fade" role="dialog" tabindex="-1">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h2 class="modal-title">Validasi Data <b class="text-muted header_data"></b></h2>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12">
							<div class="panel panel-success">
								<div class="panel-heading bg-green"><h4>Data Pengajuan Lembur</h4></div>
								<div class="panel-body">
									<div class="row">
										<div class="col-md-12">
											<div class="col-md-6">
												<div class="form-group col-md-12">
													<label class="col-md-6 control-label">Nomor SPL</label>
													<div class="col-md-6" id="data_no_lembur_vali"></div>
												</div><br>
												<div class="form-group col-md-12">
													<label class="col-md-6 control-label">Tanggal Mulai</label>
													<div class="col-md-6" id="data_tgl_m_vali"></div>
												</div><br>
												<div class="form-group col-md-12">
													<label class="col-md-6 control-label">Tanggal Selesai</label>
													<div class="col-md-6" id="data_tgl_s_vali"></div>
												</div><br>
												<div class="form-group col-md-12">
													<label class="col-md-6 control-label">Jenis Lembur</label>
													<div class="col-md-6" id="data_jenis_vali"></div>
												</div><br>
												<div class="form-group col-md-12">
													<label class="col-md-6 control-label">Lama Lembur</label>
													<div class="col-md-6" id="data_lama_vali"></div>
												</div><br>
												<div class="form-group col-md-12">
													<label class="col-md-6 control-label">Kode Customer</label>
													<div class="col-md-6" id="data_kode_customer_vali"></div>
												</div><br>
												<div class="form-group col-md-12">
													<label class="col-md-6 control-label">Keterangan (Kegiatan)</label>
													<div class="col-md-6" id="data_keterangan_vali"></div>
												</div><br>
											</div>
											<div class="col-md-6">
												<div class="form-group col-md-12">
													<label class="col-md-6 control-label">Dibuat Tanggal</label>
													<div class="col-md-6" id="data_dibuat_tgl_vali"></div>
												</div><br>
												<div class="form-group col-md-12">
													<label class="col-md-6 control-label">Diajukan Oleh</label>
													<div class="col-md-6" id="data_diajukan_vali"></div>
												</div><br>
												<div class="form-group col-md-12">
													<label class="col-md-6 control-label">Diperiksa</label>
													<div class="col-md-6" id="data_diperiksa_vali"></div>
												</div><br>
												<div class="form-group col-md-12">
													<label class="col-md-6 control-label">Diketahui</label>
													<div class="col-md-6" id="data_diketahui_vali"></div>
												</div><br>
											</div>
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
												<span id="samakandgn_off" style="font-size: 30px;" onclick="samakandgnpengajuan('off');"><i class="far fa-square" aria-hidden="true"></i></span>
												<span id="samakandgn_on" style="display: none; font-size: 30px;" onclick="samakandgnpengajuan('on');"><i class="far fa-check-square" aria-hidden="true"></i></span>
												<span style="padding-bottom: 14pt;vertical-align: middle;"><b> Samakan Dengan Data Pengajuan</b></span>
												<input type="hidden" name="val_samakan" id="val_samakan" value="1">
											</label>
										</div>
									</div>
									<hr>
									<div class="row">
										<div class="col-md-12">
											<div class="col-md-6">
												<input type="hidden" id="data_no_lembur_val" name="id">
												<input type="hidden" name="karyawan_old" id="data_karyawan_val_old">
												<input type="hidden" name="tanggal" id="tgl_val_old" class="form-control pull-right date-range">
												<input type="hidden" name="potong" id="potong_add_val_old">
												<input type="hidden" name="jenis_lembur" id="jenis_lembur_add_val_old">
												<div class="form-group">
													<label>Tanggal Lembur</label>
													<div class="has-feedback">
														<span class="fa fa-calendar form-control-feedback"></span>
														<input type="text" name="tanggal" id="tgl_val" class="form-control pull-right date-range-noreadonly" placeholder="Tanggal Lembur">
													</div>
												</div><br><br>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label>Catatan Validasi</label>
														<textarea name="catatan" id="catatan_val" class="form-control" placeholder="Catatan"></textarea>
												</div>
											</div>
										</div>
									</div>
									<hr>
									<div class="row">
										<div class="col-md-12">
											<div id="data_tabel_kar_val"></div>
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
			</div>
		</div>
	</div>
	<div id="m_yes" class="modal fade" role="dialog">
		<div class="modal-dialog modal-sm modal-default">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title text-center">Validasi Lembur</h4>
				</div>
				<form id="form_yes">
					<div class="modal-body text-center">
						<input type="hidden" id="data_idk_yes" name="id_kar">
						<input type="hidden" id="data_id_yes" name="id">
						<input type="hidden" id="data_jenis_yes" name="jenis">
						<p>Apakah Anda yakin akan mengubah status Lembur dari <b class="text-green">DiIzinkan</b> menjadi <b class="text-red">Tidak Diizinkan</b></b> dengan Nomor <b id="data_name_yes" class="header_data"></b> ??</p>
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
					<h4 class="modal-title text-center">Validasi Lembur</h4>
				</div>
				<form id="form_no">
					<div class="modal-body text-center">
						<input type="hidden" id="data_idk_no" name="id_kar">
						<input type="hidden" id="data_id_no" name="id">
						<input type="hidden" id="data_jenis_no" name="jenis">
						<p>Apakah Anda yakin akan mengubah status Lembur dari <b class="text-red">Tidak Diizinkan</b> menjadi <b class="text-green">DiIzinkan</b></b> dengan Nomor <b id="data_name_no" class="header_data"></b> ??</p>
					</div>
				</form>
				<div class="modal-footer">
					<button type="button" onclick="modal_need('id')" class="btn btn-success"><i class="fa fa-check-circle"></i> Diizinkan</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
				</div>
			</div>
		</div>
	</div>
	<div id="pLembur" class="modal fade" role="dialog">
		<div class="modal-dialog modal-sm modal-default">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title text-center">Sesuaikan Potong Jam Istirahat Lembur</h4>
				</div>
				<form id="form_pLembur">
					<div class="modal-body text-center">
						<input type="hidden" id="no_spl_pLembur" name="no_spl">
						<p>Potong Jam Istirahat Lembur yang di generate Sistem Untuk Nomor Lembur <b class="header_data"></b> adalah <b class="text-green" id="val_pLembur"></b></p><hr>
						<div class="form-group">
							<label>Tanggal Presensi</label>
							<div id="tgl_presensi"></div>
						</div>
						<div class="form-group">
							<label>Sesuaikan Potong Jam Istirahat Lembur</label>
							<div class="input-group bootstrap-timepicker">
								<div class="input-group-addon">
									<i class="fa fa-clock-o"></i>
								</div>
								<input type="text" name="potong_jam" id="data_pLembur" class="time-picker form-control field" placeholder="Tetapkan Jam Selesai" required="required">
							</div>
						</div>
					</div>
				</form>
				<div class="modal-footer">
					<button type="button" onclick="savePLembur()" class="btn btn-success"><i class="fa fa-check-circle"></i> Diizinkan</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
				</div>
			</div>
		</div>
	</div>
<?php } ?>
<!-- ======================================================== PER KARYAWAN ============================================================-->
<div id="view_kar" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h2 class="modal-title">Detail Data Lembur <b class="text-muted header_data"></b></h2>
				<input type="hidden" name="data_id_kar">
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-2">
						<img class="profile-user-img img-responsive img-circle view_photo" id="data_foto_kar" data-source-photo="" src="" alt="User profile picture" style="width: 100%;">
					</div>
					<div class="col-md-5">
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">NIK</label>
							<div class="col-md-6" id="data_nik_kar"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Nama</label>
							<div class="col-md-6" id="data_nama_kar"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Lokasi</label>
							<div class="col-md-6" id="data_loker_kar"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Jabatan</label>
							<div class="col-md-6" id="data_jabatan_kar"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Bagian</label>
							<div class="col-md-6" id="data_bagian_kar"></div>
						</div>
					</div>
					<div class="col-md-5">
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Status</label>
							<div class="col-md-6" id="data_status_kar"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Dibuat Tanggal</label>
							<div class="col-md-6" id="data_create_date_kar"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Diupdate Tanggal</label>
							<div class="col-md-6" id="data_update_date_kar"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Dibuat Oleh</label>
							<div class="col-md-6" id="data_create_by_kar">
							</div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Diupdate Oleh</label>
							<div class="col-md-6" id="data_update_by_kar">
							</div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Status Karyawan</label>
							<div class="col-md-6" id="data_status_emp_kar"></div>
						</div>
					</div>
				</div>
				<hr>
				<div class="row">
					<div class="col-md-12">
						<label class="col-md-12 control-label">Daftar Lembur</label>
						<div class="col-md-12" style="overflow: auto; max-height:400px">
							<div id="data_tabel_kar"></div>
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
<div id="validasi" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h2 class="modal-title">Data Validasi Lembur Massal<b class="text-muted header_data"></b></h2>
				<input type="hidden" name="data_id_kar">
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<label class="col-md-12 control-label">Daftar Lembur yang belum di validasi</label>
						<div class="col-md-12" style="overflow: auto;">
							<form id="validasi_masal">
							<div id="tabel_validasi_all"></div>
								<!-- <table class="table table-bordered data-table" id="table_validasi_masal" style="width:100%;">
									<thead>
										<tr class="bg-blue">
											<th>No.</th>
											<th>No Lembur</th>
											<th>Tanggal Lembur</th>
											<th>Dibuat Oleh</th>
											<th>Tanggal Pengajuan</th>
											<th>
												<span id="status_off_all" style="font-size: 20px;"><i class="fa fa-square" aria-hidden="true"></i></span>
												<span id="status_on_all" style="display: none; font-size: 20px;"><i class="fa fa-check-square" aria-hidden="true"></i></span>&nbsp;&nbsp;
												<span> Pilih Semua</span>
												<input type="hidden" id="status_id_all" name="status_all" value="">
											</th>
										</tr>
									</thead>
									<tbody>
									</tbody>
								</table> -->
							</form>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<div id="progress2" style="float: left;"></div>
				<button type="button" onclick="do_validasi_masal(0)" class="btn btn-danger"><i class="fa fa-times-circle"></i> Tidak Diizinkan</button>
				<button type="button" onclick="do_validasi_masal(1)" class="btn btn-success"><i class="fa fa-check-circle"></i> Diizinkan</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
			</div>
		</div>
	</div>
</div>
<div id="modal_delete_partial"></div>
<div id="delete_modal_kar" class="modal fade" role="dialog">
	<div class="modal-dialog modal-sm modal-danger">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title text-center">Konfirmasi Hapus</h4>
			</div>
			<form id="form_delete_kar">
				<div class="modal-body text-center">
					<input type="hidden" id="data_column_delete" name="column" value="id_karyawan">
					<input type="hidden" id="data_id_delete" name="id">
					<input type="hidden" id="data_table_delete" name="table" value="data_lembur">
					<p>Apakah anda yakin akan menghapus data dengan nama <b id="data_name_delete" class="header_data"></b> ?</p>
				</div>
			</form>
			<div class="modal-footer">
				<button type="button" onclick="do_delete_kar()" class="btn btn-primary"><i class="fa fa-trash"></i>
					Hapus</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
			</div>
		</div>
	</div>
</div>
<div id="slip_mode" class="modal fade" role="dialog">
	<div class="modal-dialog modal-sm modal-warning">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title text-center"><i class="fas fa-file-export"></i> Cetak SPL</h4>
			</div>
			<input type="hidden" id="data_id_lembur" name="data_id_lembur">
			<div class="modal-body text-center">
				<div class="col-md-6" style="text-align: center;">
					<button type="button" class="btn btn-primary" onclick="do_print_slip('word')"><i class="fas fa-file-word fa-fw"></i>WORD</button>
				</div>
				<div class="col-md-6" style="text-align: center;">
					<button type="button" class="btn btn-danger" onclick="do_print_slip('pdf')"><i class="fas fa-file-pdf fa-fw"></i>PDF</button>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="modal-footer">
			</div>
		</div>
	</div>
</div>
<div id="reset_nomor_lembur" class="modal fade" role="dialog">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h2 class="modal-title">Reset Nomor Lembur<b class="text-muted header_data"></b></h2>
				<input type="hidden" name="data_id_kar">
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12 text-center">
						<h4>Reset Nomor Lembur bisa digunakan jika ada anomali data waktu input data lembur (Gagal Input)</h4>
						<p>Jika Nomor Lembur pada Transaksi terakhir adalah <b>SPL202103110017</b> tapi pada tampilan Input Lembur Nomor Lembur Menampilkan angka <b>SPL202103110001</b> (Reset Ke 1, Seharusnya angka terakhir 18), maka disarankan untuk mereset nomor lembur.</p>
						<h4>Harap Berhati-hati sebelum melakukan reset</h4>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<div id="progress22" style="float: left;"></div>
				<button type="button" onclick="do_reset_nomor_lembur()" class="btn btn-danger"><i class="fa fa-check-circle"></i> Reset</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
			</div>
		</div>
	</div>
</div>
<script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
<script type="text/javascript">
	var url_select="<?php echo base_url('global_control/select2_global');?>";
	var table="data_lembur";
	$(document).ready(function(){
		transaksi('all');
		refreshCode();
		$("a[data-toggle='tab']").on("shown.bs.tab", function (e) {
			$($.fn.dataTable.tables(true)).DataTable()
			.columns.adjust()
			.responsive.recalc();
		});
	});
	function submitForm(form) {
		$('#'+form).validator().on('submit', function (e) {
			if (e.isDefaultPrevented()) {
				notValidParamx();
			} else {
				e.preventDefault(); 
				if(form=='form_add'){
					do_add()
				}else{
					do_edit()
				}
			}
		})
	}
	// ============================================================ DATA PER TRANSAKSI =======================================================
	function transaksi(kode) {
		refreshData();
		tableData(kode);
		submitForm('form_add');
		submitForm('form_edit');
		$('#samakandgn_off').hide();
		$('#samakandgn_on').show();
		// samakandgnpengajuan('on');
		// $('#tgl_val').val($('#tgl_val_old').val());
		$('#btn_export').click(function(){
			select_data('bagian_export',url_select,'master_bagian','kode_bagian','nama','placeholder');
			select_data('unit_export',url_select,'master_loker','kode_loker','nama','placeholder');
		});
		// getSelect2("<?php echo base_url('presensi/data_presensi/employee')?>",'karyawan_add,#id_dibuat_add');
		getSelect2("<?php echo base_url('presensi/data_presensi/employee')?>",'id_dibuat_add');
		getSelect2("<?php echo base_url('employee/mutasi_jabatan/employee')?>",'id_diperiksa_add,#id_diketahui_add');
		$('#potong_no').click(function(){
			$('#potong_no').hide();
			$('#potong_yes').show();
			$('#potong_add').val('1');
		})
		$('#potong_yes').click(function(){
			$('#potong_yes').hide();
			$('#potong_no').show();
			$('#potong_add').val('0');
		})
		$('#potong_no_val').click(function(){
			$('#potong_no_val').hide();
			$('#potong_yes_val').show();
			$('#potong_add_val').val('1');
		})
		$('#potong_yes_val').click(function(){
			$('#potong_yes_val').hide();
			$('#potong_no_val').show();
			$('#potong_add_val').val('0');
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
      })
		getSelect2("<?php echo base_url('master/master_bagian/get_select2')?>",'bagian_add');
		unsetoption('bagian_add',['BAG001','BAG002']);
	}
	function refreshData() {
		getSelect2("<?php echo base_url('master/master_bagian/get_select2')?>",'bagian_export');
		select_data('unit_export',url_select,'master_loker','kode_loker','nama','placeholder');
		unsetoption('bagian_export',['BAG001','BAG002']);
	}
	function resetselectAdd() {
		$('#id_dibuat_add').val([]).trigger('change');
		$('#id_diperiksa_add').val([]).trigger('change');
		$('#id_diketahui_add').val([]).trigger('change');
		$('#bagian_add').val([]).trigger('change');
		$('#karyawan_add').val([]).trigger('change');
	}
	function tableData(kode) {
		$('input[name="param"').val(kode);
		$('#table_data').DataTable().destroy();
		if(kode=='all'){
			var datax = {param:'all',access:"<?php echo $this->codegenerator->encryptChar($access);?>"};
		}else if(kode=='tab'){
			var bagian = $('#bagian_export').val();
			var datax = {param:'tab',bagian:bagian,access:"<?php echo $this->codegenerator->encryptChar($access);?>"};
		}else{
			var bagian = $('#bagian_export').val();
			var unit = $('#unit_export').val();
			var tanggal = $('#tanggal_filter').val();
			var status = $('#status_validasi').val();
			var potong_jam = $('#potong_jam').val();
			var datax = {param:'search',bagian:bagian,unit:unit,tanggal:tanggal,status_validasi:status,potong_jam:potong_jam,access:"<?php echo $this->codegenerator->encryptChar($access);?>"};
		}
		$('#table_data').DataTable( {
			ajax: {
				url: "<?php echo base_url('presensi/data_lembur_trans/view_all/')?>",
				type: 'POST',
				data:datax
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
				width: '12%',
				render: function ( data, type, full, meta ) {
					return data;
				}
			},
			{   targets: 2,
				width: '15%',
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
				width: '8%',
				render: function ( data, type, full, meta ) {
					return '<center>'+data+'</center>';
				}
			},
			]
		});
	}
	function refreshCode() {
		kode_generator("<?php echo base_url('presensi/data_lembur_trans/kode');?>",'kode_spl_add');
	}
	function view_modal(id) {
		var data={no_lembur:id};
		var callback=getAjaxData("<?php echo base_url('presensi/data_lembur_trans/view_one')?>",data);  
		$('#view').modal('show');
		$('.header_data').html(callback['no_lembur']);
		$('#data_id_view').html(callback['no_lembur']);
		// $('#data_foto_view').attr('src',callback['foto']);
		$('#data_no_lembur_view').html(callback['no_lembur']);
		$('#data_tanggal_m_view').html(callback['tanggal_mulai']);
		$('#data_tanggal_s_view').html(callback['tanggal_selesai']);
		$('#data_lama_lembur_view').html(callback['lama_lembur']);
		$('#data_jenis_lembur_view').html(callback['jenis_lembur']);
		$('#data_potong_view').html(callback['potong']);
		$('#data_kode_customer_view').html(callback['kode_customer']);
		$('#data_keterangan_view').html(callback['keterangan']);
		$('#data_tabel_view').html(callback['table_lembur_kary']);
		$('#data_after_val').html(callback['data_after_val']);
		var val_ac = callback['e_validasi'];
		if (val_ac == 2 || val_ac == 0){
			$('#div_data_after_val').hide();
			$('#btn_edit_view').show();
			$('#btn_edit_spl').show();
		}else{
			$('#div_data_after_val').show();
			$('#btn_edit_view').hide();
			$('#btn_edit_spl').hide();
		}
		$('#data_status_val_view').html(callback['status_val']);
		$('#data_create_date_view').html(callback['create_date']+' WIB');
		$('#data_update_date_view').html(callback['update_date']+' WIB');
		$('input[name="data_id_view"]').val(callback['no_lembur']);
		$('#data_create_by_view').html(callback['nama_buat']);
		$('#data_update_by_view').html(callback['nama_update']);
	}
	function edit_modal() {
		getSelect2("<?php echo base_url('employee/mutasi_jabatan/employee')?>",'id_dibuat_edit'); 
		getSelect2("<?php echo base_url('employee/mutasi_jabatan/employee')?>",'id_diperiksa_edit'); 
		getSelect2("<?php echo base_url('employee/mutasi_jabatan/employee')?>",'id_diketahui_edit');
		getSelect2("<?php echo base_url('presensi/data_presensi/employee')?>",'karyawan_edit_trans');
		$('#potong_no_edit').click(function(){
			$('#potong_no_edit').hide();
			$('#potong_yes_edit').show();
			$('#potong_edit').val('1');
		})
		$('#potong_yes_edit').click(function(){
			$('#potong_yes_edit').hide();
			$('#potong_no_edit').show();
			$('#potong_edit').val('0');
		})
		var id = $('input[name="data_id_view"]').val();
		var data={no_lembur:id};
		var callback=getAjaxData("<?php echo base_url('presensi/data_lembur_trans/view_one')?>",data); 
		$('#view').modal('toggle');
		setTimeout(function () {
			$('#edit').modal('show');
		},600); 
		$('.header_data').html(callback['no_lembur']);
		$('#data_id_edit').val(callback['id']);
		$('#kode_spl_edit').val(callback['no_lembur']);
		$('#id_dibuat_edit').val(callback['e_dibuat']).trigger('change');
		$('#id_diperiksa_edit').val(callback['e_diperiksa']).trigger('change');
		$('#id_diketahui_edit').val(callback['e_diketahui']).trigger('change');
		$('#data_jenis_lembur_edit').val(callback['e_jenis_lembur']).trigger('change');
		$('#tgl_buat_edit').val(callback['e_tgl_buat']);
		$('#kode_customer_edit').val(callback['kode_customer']);
		$('#keterangan_edit').val(callback['e_keterangan']);
		$('#karyawan_edit_trans').val(callback['e_karyawan']).trigger('change');
		$('#karyawan_old').val(callback['e_karyawan']).trigger('change');
		$('#validasi_edit').val(callback['e_validasi']);
		// addValueEditor('keterangan_edit',callback['e_keterangan']);
		$("#data_tanggal_edit").data('daterangepicker').setStartDate(callback['e_tanggal_mulai']);
		$("#data_tanggal_edit").data('daterangepicker').setEndDate(callback['e_tanggal_selesai']);
		var potong=callback['jam_istirahat_edit'];
		if (potong==1){
			$('#potong_no_edit').hide();
			$('#potong_yes_edit').show();
		}else{
			$('#potong_yes_edit').hide();
			$('#potong_no_edit').show();
		}
	}
	function edit_spl() {
		var id = $('input[name="data_id_view"]').val();
		var data={no_lembur:id};
		var callback=getAjaxData("<?php echo base_url('presensi/data_lembur_trans/view_one')?>",data); 
		$('#view').modal('toggle');
		setTimeout(function () {
			$('#edit_spl').modal('show');
		},600);
		$('#data_id_spl').html(callback['no_lembur']);
		$('#data_no_lembur_spl').html(callback['no_lembur']);
		$('#data_tanggal_m_spl').html(callback['tanggal_mulai']);
		$('#data_tanggal_s_spl').html(callback['tanggal_selesai']);
		$('#data_lama_lembur_spl').html(callback['lama_lembur']);
		$('#data_jenis_lembur_spl').html(callback['jenis_lembur']);
		$('#data_potong_spl').html(callback['potong']);
		$('#data_kode_customer_spl').html(callback['kode_customer']);
		$('#data_keterangan_spl').html(callback['keterangan']);
		$('#data_tabel_spl').html(callback['table_lembur_spl']);
		var val_ac = callback['e_validasi'];
		if (val_ac === 2 || val_ac === 0){
			$('#div_data_after_val').hide();
			$('#btn_edit_spl').show();
		}else{
			$('#div_data_after_val').show();
			$('#btn_edit_spl').hide();
		}
		$('#data_status_val_spl').html(callback['status_val']);
		$('#data_create_date_spl').html(callback['create_date']+' WIB');
		$('#data_update_date_spl').html(callback['update_date']+' WIB');
		$('input[name="data_id_spl"]').val(callback['no_lembur']);
		$('#data_create_by_spl').html(callback['nama_buat']);
		$('#data_update_by_spl').html(callback['nama_update']);
	}
	function cekNoSPL(kode){
		var data={no_spl:kode};
		var callback=getAjaxData("<?php echo base_url('presensi/data_lembur_trans/cek_spl')?>",data);
		$('#div_span_cek_spl').html(callback);
	}
	function do_edit_spl(){
		if($("#form_edit_spl")[0].checkValidity()) {
			submitAjax("<?php echo base_url('presensi/edit_no_spl')?>",'edit_spl','form_edit_spl',null,null);
			$('#table_data').DataTable().ajax.reload();
		}else{
			notValidParamx();
		} 
	}
	function get_selet_emp(kode) {
		var data={kode_bagian:kode};
		var callback=getAjaxData("<?php echo base_url('presensi/data_lembur_trans/get_select')?>",data);
		$('#karyawan_add').html(callback['options']);
		// var callback=getAjaxData("<?php //echo base_url('presensi/data_lembur_trans/view_select')?>",data);
		var text = "";
		var i;
		var selectedValues = new Array();
		for (i = 0; i < callback['select'].length; i++) {
			selectedValues[i] = callback['select'][i];
		} 
		$('#karyawan_add').val(selectedValues).trigger('change');
	}
	function delete_modal(id) {
		var data={no_lembur:id};
		var call=getAjaxData("<?php echo base_url('presensi/data_lembur_trans/view_one')?>",data);
		// console.log(call);
		var datax={table:'data_lembur',column:'no_lembur',id:call['no_lembur'],nama:call['no_lembur']};
		loadModalAjax("<?php echo base_url('pages/load_modal_delete')?>",'modal_delete_partial',datax,'delete');
	}
	function do_add(){
		if($("#form_add")[0].checkValidity()) {
			submitAjax("<?php echo base_url('presensi/add_lembur_masal')?>",null,'form_add',null,null);
			$('#table_data').DataTable().ajax.reload(function(){
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
			submitAjax("<?php echo base_url('presensi/edit_lembur_trans')?>",'edit','form_edit',null,null);
			$('#table_data').DataTable().ajax.reload();
		}else{
			notValidParamx();
		} 
	}
	function rekap() {
		var data=$('#form_filter').serialize();
		window.location.href = "<?php echo base_url('rekap/export_data_lembur')?>?"+data;
	}
	function rekap_req() {
		var data=$('#form_filter').serialize();
		window.location.href = "<?php echo base_url('rekap/export_data_lembur_perhitungan')?>?"+data;
	}
	function modal_need(id) {
		getSelect2("<?php echo base_url('presensi/data_presensi/employee')?>",'data_karyawan_val');
		if(id=='id'){
			var nosk = $('#data_id_no').val();
			var data={no_lembur:nosk};
			var callback=getAjaxData("<?php echo base_url('presensi/data_lembur_trans/view_one')?>",data); 
			$('#m_no').modal('toggle');
			setTimeout(function () {
				$('#m_validasi').modal('show');
			},600); 
		}else{
			var data={no_lembur:id};
			var callback=getAjaxData("<?php echo base_url('presensi/data_lembur_trans/view_one')?>",data); 
			$('#m_validasi').modal('show');
		}
		$('.header_data').html(callback['no_lembur']);
		$('#data_no_lembur_vali').html(callback['no_lembur']);
		$('#data_no_lembur_val').val(callback['no_lembur']);
		$('#data_tgl_m_vali').html(callback['tanggal_mulai']);
		$('#data_tgl_s_vali').html(callback['tanggal_selesai']);
		$('#data_lama_vali').html(callback['lama_lembur']);
		$('#data_jenis_vali').html(callback['jenis_lembur']);
		$('#data_potong_vali').html(callback['potong']);
		$('#data_keterangan_vali').html(callback['keterangan']);
		$('#data_kode_customer_vali').html(callback['kode_customer']);
		$('#data_dibuat_tgl_vali').html(callback['tgl_buat']);
		$('#data_diajukan_vali').html(callback['diajukan']);
		$('#data_diperiksa_vali').html(callback['diperiksa']);
		$('#data_diketahui_vali').html(callback['diketahui']);
		$('#data_karyawan_val').val(callback['e_karyawan']).trigger('change');
		$('#data_karyawan_val_old').val(callback['e_karyawan']);
		$("#tgl_val_old").data('daterangepicker').setStartDate(callback['e_tanggal_mulai']);
		$("#tgl_val_old").data('daterangepicker').setEndDate(callback['e_tanggal_selesai']);
		$("#tgl_val").data('daterangepicker').setStartDate(callback['e_tanggal_mulai']);
		$("#tgl_val").data('daterangepicker').setEndDate(callback['e_tanggal_selesai']);
		$('#potong_add_val_old').val(callback['potong']);
		$('#jenis_lembur_add_val_old').val(callback['e_jenis_lembur']);
		$('#data_tabel_kar_val').html(callback['table_lembur_kary']);
	}
	function modal_yes(id) {
		var data={no_lembur:id};
		$('#m_yes').modal('toggle');
		var callback=getAjaxData("<?php echo base_url('presensi/data_lembur_trans/view_one')?>",data);
		$('#m_yes #data_id_yes').val(callback['no_lembur']);
		$('#m_yes .header_data').html(callback['no_lembur']);
	}
	function modal_no(id) {
		var data={no_lembur:id};
		$('#m_no').modal('toggle');
		var callback=getAjaxData("<?php echo base_url('presensi/data_lembur_trans/view_one')?>",data);
		$('#m_no #data_id_no').val(callback['no_lembur']);
		$('#m_no .header_data').html(callback['no_lembur']);
	}
  	function do_validasi(data,val,form){
		if(data==2){
			var id = $('#data_no_lembur_val').val();
			var kar_old = $('#data_karyawan_val_old').val();
			var kar_new = $('#data_karyawan_val').val();
			var tgl = $('#tgl_val').val();
			var tgl_old = $('#tgl_val_old').val();
			var ptg = $('#potong_add_val').val();
			var ctt = $('#catatan_val').val();
			var samakan = $('#val_samakan').val();
			var jenis_lembur = $('#jenis_lembur_add_val_old').val();
			var datax={no_lembur:id,validasi_db:data,validasi:val,kar_old:kar_old,kar_new:kar_new,tgl_val:tgl,tgl_old:tgl_old,potong:ptg,catatan:ctt,samakan:samakan,jenis_lembur:jenis_lembur};
		}else if(data==1){
			var id = $('#data_id_yes').val();
			var datax={no_lembur:id,validasi_db:data,validasi:val,};
		}else if(data==0){
			var id = $('#data_id_no').val();
			var datax={no_lembur:id,validasi_db:data,validasi:val,};
		}
		submitAjax("<?php echo base_url('presensi/validasi_lembur')?>",form,datax,null,null,'status');
		$('#table_data').DataTable().ajax.reload();
  	}
   	function samakandgnpengajuan(kode) {
		// var name = $('input[name="val_samakan"]').val();
		// if(name == 0) {
		if(kode == 'off') {
			$('#tgl_val').val($('#tgl_val_old').val());
		}else {
			$('#tgl_val').val('');
		}
   	}
	function potongLembur(id) {
		var data={no_lembur:id};
		$('#pLembur').modal('show');
		var callback=getAjaxData("<?php echo base_url('presensi/data_lembur_trans/view_one')?>",data);
		$('#pLembur #no_spl_pLembur').val(callback['no_lembur']);
		$('#pLembur .header_data').html(callback['no_lembur']);
		$('#pLembur #val_pLembur').html(callback['val_pLembur']);
		$('#pLembur #data_pLembur').val(callback['val_pLembur']);
		$('#pLembur #tgl_presensi').html(callback['tgl_presensi']);
	}
	function savePLembur(){
		if($("#form_pLembur")[0].checkValidity()) {
			submitAjax("<?php echo base_url('presensi/editPotongJamIstirahat')?>",'pLembur','form_pLembur',null,null);
			$('#table_data').DataTable().ajax.reload();
		}else{
			notValidParamx();
		} 
	}
// ============================================================== PER KARYAWAN ==================================================================
	function kar(kode) {
		tableDataKar(kode);
		submitForm('form_add_kar');
			getSelect2("<?php echo base_url('master/master_bagian/get_select2')?>",'bagian_export_kar');
			select_data('unit_export_kar',url_select,'master_loker','kode_loker','nama','placeholder');
		getSelect2("<?php echo base_url('employee/mutasi_jabatan/employee')?>",'id_dibuat_add_kar,#id_diperiksa_add_kar,#id_diketahui_add_kar');
		getSelect2("<?php echo base_url('presensi/data_presensi/employee')?>",'karyawan_add_kar');
		$('#potong_no_kar').click(function(){
			$('#potong_no_kar').hide();
			$('#potong_yes_kar').show();
			$('#potong_add_kar').val('1');
		})
		$('#potong_yes_kar').click(function(){
			$('#potong_yes_kar').hide();
			$('#potong_no_kar').show();
			$('#potong_add_kar').val('0');
		})
		getSelect2("<?php echo base_url('master/master_bagian/get_select2')?>",'bagian_add_kar');
		unsetoption('bagian_add_kar',['BAG001','BAG002']);
	}
	function tableDataKar(kode) {
		$('input[name="param"').val(kode);
		$('#table_data_kar').DataTable().destroy();
		if(kode=='all'){
			var datax = {param:'all',access:"<?php echo $this->codegenerator->encryptChar($access);?>"};
		}else if(kode=='tab'){
			var datax = {param:kode,access:"<?php echo $this->codegenerator->encryptChar($access);?>"};
		}else{
			var bagian = $('#bagian_export_kar').val();
			var unit = $('#unit_export_kar').val();
			var tanggal = $('#tanggal_filter_kar').val();
			var datax = {param:'search',bagian:bagian,unit:unit,tanggal:tanggal,access:"<?php echo $this->codegenerator->encryptChar($access);?>"};
		}
		$('#table_data_kar').DataTable( {
			ajax: {
				url: "<?php echo base_url('presensi/data_lembur_kar/view_all/')?>",
				type: 'POST',
				data:datax
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
					return '<a href="<?php base_url()?>view_lembur/'+full[9]+'">' +data+'</a>';
				}
			},
			{   targets: 6,
				width: '10%',
				render: function ( data, type, full, meta ) {
					return data;
				}
			},
			{   targets: 7,
				width: '10%',
				render: function ( data, type, full, meta ) {
					return '<center>'+data+' Lembur</center>';
				}
			},
	      {   targets: 8, 
	      	width: '7%',
	      	render: function ( data, type, full, meta ) {
	      		return '<center>'+data+'</center>';
	      	}
	      },
	      ]
	  	});
	}
	function refreshDataKar() {
		getSelect2("<?php echo base_url('master/master_bagian/get_select2')?>",'bagian_export_kar');
		select_data('unit_export_kar',url_select,'master_loker','kode_loker','nama','placeholder');
		unsetoption('bagian_export_kar',['BAG001','BAG002']);
	}
	function resetselectAddKar() {
		$('#id_dibuat_add_kar').val([]).trigger('change');
		$('#id_diperiksa_add_kar').val([]).trigger('change');
		$('#id_diketahui_add_kar').val([]).trigger('change');
		$('#bagian_add_kar').val([]).trigger('change');
		$('#karyawan_add_kar').val([]).trigger('change');
	}
	function view_modal_kar(id) {
		var data={id_data_lembur:id};
		var callback=getAjaxData("<?php echo base_url('presensi/data_lembur_kar/view_one')?>",data);  
		$('#view_kar').modal('show');
		$('.header_data').html(callback['no_lembur']);
		$('#data_id_kar').html(callback['id']);
		$('#data_foto_kar').attr('src',callback['foto']);
		$('#data_nik_kar').html(callback['nik']);
		$('#data_nama_kar').html(callback['nama']);
		$('#data_jabatan_kar').html(callback['jabatan']);
		$('#data_bagian_kar').html(callback['bagian']);
		$('#data_loker_kar').html(callback['loker']);
		$('#data_tabel_kar').html(callback['table_lembur']);
		var status_emp = callback['status_emp'];
		if(status_emp==1){
			var statusval_emp = '<b class="text-success">Karyawan Aktif</b>';
		}else{
			var statusval_emp = '<b class="text-danger">Karyawan Tidak Aktif</b>';
		}
		$('#data_status_emp_kar').html(statusval_emp);
		var status = callback['status'];
		if(status==1){
			var statusval = '<b class="text-success">Aktif</b>';
		}else{
			var statusval = '<b class="text-danger">Tidak Aktif</b>';
		}
		$('#data_status_kar').html(statusval);
		$('#data_create_date_kar').html(callback['create_date']+' WIB');
		$('#data_update_date_kar').html(callback['update_date']+' WIB');
		$('input[name="data_id_kar"]').val(callback['id']);
		$('#data_create_by_kar').html(callback['nama_buat']);
		$('#data_update_by_kar').html(callback['nama_update']);
		$('#data_tabel_kar').html(callback['tabel_izin']);
	}
	function rekap_kar() {
		var data=$('#form_filter_kar').serialize();
		window.location.href = "<?php echo base_url('rekap/export_data_lembur_kar')?>"+data;
	}
	function delete_modal_kar(id) {
		var data={id_data_lembur:id};
		$('#delete_modal_kar').modal('show');
		var callback=getAjaxData("<?php echo base_url('presensi/view_lembur_kar/view_one')?>",data);
		$('#delete_modal_kar #data_id_delete').val(callback['id_karyawan']);
		$('#delete_modal_kar .header_data').html(callback['nama']);
	}
	function do_delete_kar(){
		submitAjax("<?php echo base_url('global_control/delete')?>",'delete_modal_kar','form_delete_kar',null,null);
		$('#table_data_kar').DataTable().ajax.reload(function (){
			Pace.restart();
		});
	}
	function do_print(id) { 
		$('#slip_mode').modal('show');
		$('input[name="data_id_lembur"]').val(id);
		// $.redirect("<?php echo base_url('pages/cetak_spl'); ?>", { kode_lembur: id, }, "POST", "_blank");
	}
	function do_print_slip(kode) {
		var id = $('input[name="data_id_lembur"]').val();
		if(kode == 'word'){
			var data={id:id};
			var callback=getAjaxData("<?php echo base_url('cetak_word/cek_template/lembur/cek')?>",data);
			if(callback['temp']=='false'){
				submitAjax("<?php echo base_url('cetak_word/cek_template/lembur/notif')?>",null,null,null,null,'status');
			} else {
				window.location.href = "<?php echo base_url('cetak_word/cetak_lembur/')?>"+id;
			}
		} else {
			$.redirect("<?php echo base_url('pages/cetak_spl'); ?>", { kode_lembur: id, }, "POST", "_blank");
		}
	} 
	function modal_massal(){
		$('#validasi').modal('show');
      	// var datax={id:null};
		// $('#table_validasi_masal').DataTable({
		// 	ajax: {
		// 		url: "<?php //echo base_url('presensi/data_lembur_validasi_massal/view_all/')?>",
		// 		type: 'POST',
		// 		data: datax,
		// 	},
		// 	scrollX: true,
		// 	bDestroy: true,
		// 	scrollCollapse: true,
		// 	columnDefs: [
		// 		{   targets: 0, 
		// 			width: '5%',
		// 			render: function ( data, type, full, meta ) {
        //           return '<center>'+(meta.row+1)+'.</center>';
		// 			}
		// 		},
		// 		{   targets: 1, 
		// 			width: '5%',
		// 			render: function ( data, type, full, meta ) {
		// 				return data;
		// 			}
		// 		},
		// 		{   targets: 5,
		// 			width: '7%',
		// 			render: function ( data, type, full, meta ) {
        //           		return '<center>'+data+'</center>';
		// 			}
		// 		},
		// 	]
		// });
		var tabel=getAjaxData("<?php echo base_url('presensi/data_lembur_validasi_massal/tabel')?>",null);
		$('#tabel_validasi_all').html(tabel['tabel']);
		var jum=getAjaxData("<?php echo base_url('presensi/data_lembur_validasi_massal/jum_data')?>",null);
		for(let i = 1; i <= jum['jumlah']; i++){
			$('#status_off'+i).click(function(){
				$('#status_off'+i).hide();
				$('#status_on'+i).show();
				$('#status_id'+i).val('1');
			});
			$('#status_on'+i).click(function(){
				$('#status_off'+i).show();
				$('#status_on'+i).hide();
				$('#status_id'+i).val('0');
			});
		}
		$('#status_off_all').click(function(){
			$('#status_off_all').hide();
			$('#status_on_all').show();
			$('#status_id_all').val('1');
			for(let j = 1; j <= jum['jumlah']; j++){
				$('#status_on'+j).show();
				$('#status_off'+j).hide();
			}
		});
		$('#status_on_all').click(function(){
			$('#status_off_all').show();
			$('#status_on_all').hide();
			$('#status_id_all').val('0');
			for(let k = 1; k <= jum['jumlah']; k++){
				$('#status_off'+k).show();
				$('#status_on'+k).hide();
			}
		});
	}
	function do_validasi_masal(kode){
		$('#progress2').html('<i class="fa fa-spinner fa-pulse fa-fw"></i> Mohon Tunggu, Sedang memproses data....');
		$('#progress2').show();
		var tabel = $('#validasi_masal').serialize();
		var datax={tabel:tabel,kode:kode};
		submitAjax("<?php echo base_url('presensi/do_validasi_lembur_massal')?>",'validasi',datax,null,null,'status');
		$('#progress2').hide();
		$('#table_data').DataTable().ajax.reload(function (){
			Pace.restart();
		});
	}
	function modal_reset_nomor(){
		$('#reset_nomor_lembur').modal('show');
	}
	function do_reset_nomor_lembur(){
		$('#progress22').html('<i class="fa fa-spinner fa-pulse fa-fw"></i> Mohon Tunggu, Sedang memproses data....');
		$('#progress22').show();
		// var tabel = $('#validasi_masal').serialize();
		// var datax={tabel:tabel,kode:kode};
		submitAjax("<?php echo base_url('presensi/do_reset_nomor_lembur')?>",'reset_nomor_lembur',null,null,null,'status');
		$('#progress22').hide();
		$('#table_data').DataTable().ajax.reload(function (){
			Pace.restart();
		});
	}
</script>
