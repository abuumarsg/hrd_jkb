<div class="content-wrapper">
	<section class="content-header">
		<h1>
			<i class="fa fa-database"></i> Data
			<small> Perjalanan Dinas</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fa fas fa-tachometer-alt"></i> Dashboard</a></li>
			<li class="active"><i class="fa fa-database"></i> Perjalanan Dinas</li>
		</ol>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-info">
					<div class="box-header with-border">
						<h3 class="box-title"><i class="fa fa-database"></i> Master Perjalanan Dinas</h3>
						<div class="box-tools pull-right">
							<button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
							<button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i class="fa fa-times"></i></button>
						</div>
					</div>
					<div class="box-body">
						<div class="nav-tabs-custom">
							<ul class="nav nav-tabs">
								<li class="active"><a onclick="kendaraan()" href="#kendaraan" data-toggle="tab"><i class="fas fa-car"></i> Kendaraan Perjalanan Dinas</a></li>
								<li><a onclick="kategori_tunjangan()" href="#kategori" data-toggle="tab"><i class="fas fa-hand-holding-heart"></i> Kategori Tunjangan Perjalanan Dinas</a></li>
								<li><a onclick="jarak_antar_plant()" href="#antar_plant" data-toggle="tab"><i class="fas fa-text-width"></i> Jarak Antar Plant</a></li>
								<li><a onclick="kode_akun()" href="#kode_akun" data-toggle="tab"><i class="fas fa-file-code"></i> Kode Akun Perjalanan Dinas</a></li>
							</ul>
							<div class="tab-content">
								<div class="tab-pane active" id="kendaraan">
									<div class="row">
										<div class="col-md-12">
											<div id="accordion">
												<div class="panel">
													<div class="row">
														<div class="col-md-12">
															<div class="pull-left">
																<?php if (in_array($access['l_ac']['add'], $access['access'])) {
																	echo '<a href="#add_kendaraan" data-toggle="collapse"  data-parent="#accordion" class="btn btn-success"><i class="fa fa-plus"></i> Tambah Jenis Kendaraan</a>
																	<a href="#add_bbm" data-toggle="collapse" data-parent="#accordion" class="btn btn-info" onclick="refresh_kendaraan()"><i class="fa fa-plus"></i> Tambah Intensif Perjalanan Dinas</a>';
																}?>
															</div>
															<div class="pull-right" style="font-size: 8pt;">
																<i class="fa fa-toggle-on stat scc"></i> Aktif<br>
																<i class="fa fa-toggle-off stat err"></i> Tidak Aktif
															</div>
														</div>
													</div>
													<?php if(in_array($access['l_ac']['add'], $access['access'])){?>
														<div class="collapse" id="add_kendaraan">
															<br>
															<div class="col-md-2"></div>
															<div class="col-md-8">
																<form id="form_add_kendaraan">
																	<div class="form-group">
																		<label>Kode Kendaraan</label>
																		<input type="text" placeholder="Masukkan Kode Kendaraan" id="kode_pd_add" name="kode" class="form-control" required="required" value="" readonly="readonly">
																	</div>
																	<div class="form-group">
																		<label>Nama Kendaraan</label>
																		<input type="text" placeholder="Masukkan Nama Kendaraan" id="data_name_add" name="nama" class="form-control field" required="required">
																	</div>
																	<div class="form-group">
																		<label>Keterangan</label>
																		<textarea name="keterangan" class="form-control" placeholder="Keterangan"></textarea>
																	</div>
																	<div class="form-group">
																		<button type="button" onclick="add_kendaraan()" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
																	</div>
																</form>
															</div>
														</div>
														<div class="collapse" id="add_bbm">
															<br>
															<div class="col-md-2"></div>
															<div class="col-md-8">
																<form id="form_add_bbm">
																	<div class="form-group">
																		<label>Kode Kendaraan</label>
																		<input type="text" placeholder="Masukkan Kode Kendaraan" id="kode_bbm_add" name="kode_bbm" class="form-control" required="required" value="" readonly="readonly">
																	</div>
																	<div class="form-group">
																		<label>Pilih Kendaraan</label>
																		<select class="form-control select2" name="kode_kendaraan" id="data_kendaraan_add" onchange="kendaraanPD(this.value)" style="width: 100%;" required="required"></select>
																	</div>
																	<div class="form-group" id="nama_kendaraan_umum" style="display: none;">
																		<label>Pilih Kendaraan Umum</label>
																			<?php
																			$kendaraan_umum[null] = 'Pilih Data';
																			$sel = [null];
																			$exsel = array('class'=>'form-control select2','placeholder'=>'Kendaraan Umum','id'=>'kendaraan_umum','style'=>'width:100%');
																			echo form_dropdown('kendaraan_umum',$kendaraan_umum,$sel,$exsel);
																			?>
																	</div>
																	<div class="form-group">
																		<label>Dari Jarak (km)</label>
																		<input type="number" min="0" name="jarak_min" class="form-control" placeholder="Jarak Minimal" required="required">
																	</div>
																	<div class="form-group">
																		<label>Sampai Jarak (km)</label>
																		<input type="number" min="0" name="jarak_mak" class="form-control" placeholder="Jarak Maksimal" required="required">
																	</div>
																	<div class="form-group">
																		<label>Nominal</label>
																		<input type="text" name="nominal" class="form-control input-money" placeholder="Besaran Intensif BBM" required="required">
																	</div>
																	<div class="form-group">
																		<label>Keterangan</label>
																		<textarea name="keterangan" class="form-control" placeholder="Keterangan"></textarea>
																	</div>
																	<div class="form-group">
																		<button type="button" onclick="add_bbm()" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
																	</div>
																</form>
															</div>
														</div>
													<?php } ?>
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<div class="callout callout-info"><label><i class="fa fa-info-circle"></i> Bantuan</label><br>Pilih <b>Kode Kendaraan</b> Untuk melihat detail Intensif/Tunjangan BBM berdasarkan Jarak dan Jenis Kendaraan</div>
											<table id="table_data_kendaraan" class="table table-bordered table-striped" width="100%">
												<thead>
													<tr>
														<th>No</th>
														<th>Kode</th>
														<th>Nama</th>
														<th>Keterangan</th>
														<th>Jumlah Data</th>
														<th>Tanggal</th>
														<th>Status</th>
														<th>Aksi</th>
													</tr>
												</thead>
												<tbody>
												</tbody>
											</table>
										</div>
									</div>
								</div>
								<div class="tab-pane" id="kategori">
									<div class="row">
										<div class="col-md-12">
											<div id="accordions">
												<div class="panel">
													<div class="row">
														<div class="col-md-12">
															<div class="pull-left">
																<?php if (in_array($access['l_ac']['add'], $access['access'])) {
																	echo '<a href="#add_kategori" data-toggle="collapse"  data-parent="#accordions" class="btn btn-primary"><i class="fa fa-plus"></i> Tambah Kategori Tunjangan</a>';
																	// echo '<a href="#add_detail" data-toggle="collapse" data-parent="#accordions" onclick="refresh_kategori()" class="btn btn-success"><i class="fa fa-plus"></i> Tambah Detail Kategori Tunjangan</a>';
																}?>
																<div class="btn-group">
																	<button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown"><i class="fas fa-file-excel-o"></i> Export & Import <span class="fa fa-caret-down"></span></button>
																	<ul class="dropdown-menu">
																	<li><a onclick="export_template()">Export Template Grade</a></li>
																	<li><a data-toggle="modal" data-target="#import">Import Master Grade</a>
																	</li>
																	</ul>
																</div>
															</div>
															<div class="pull-right" style="font-size: 8pt;">
																<i class="fa fa-toggle-on stat scc"></i> Aktif<br>
																<i class="fa fa-toggle-off stat err"></i> Tidak Aktif
															</div>
														</div>
													</div>
													<?php if(in_array($access['l_ac']['add'], $access['access'])){?>
														<div class="collapse" id="add_kategori">
															<br>
															<div class="col-md-2"></div>
															<div class="col-md-8">
																<form id="form_add_kategori">
																	<div class="form-group">
																		<label>Kode Kategori Tunjangan</label>
																		<input type="text" placeholder="Masukkan Kode Kategori Tunjangan" id="kode_kategori_add" name="kode" class="form-control" required="required" value="" readonly="readonly">
																	</div>
																	<div class="form-group">
																		<label>Nama Kategori Tunjangan</label>
																		<input type="text" placeholder="Masukkan Nama Kategori Tunjangan" id="data_name_add" name="nama" class="form-control field" required="required">
																	</div>
																	<div class="form-group">
																		<label>Nominal Minimal</label>
																		<?php
																			$yesnox = $this->otherfunctions->getYesNoList();
																			$yesnox[null] = 'Pilih Data';
																			$selpa = array(null);
																			$expa = array('class'=>'form-control select2','style'=>'width:100%;','required'=>'required','id'=>'nominal_minimal_add');
																			echo form_dropdown('nominal_min',$yesnox,$selpa,$expa);
																		?>
																	</div>
																	<div class="form-group">
																		<label>Keterangan</label>
																		<textarea name="keterangan" class="form-control" placeholder="Keterangan"></textarea>
																	</div>
																	<div class="form-group">
																		<button type="button" onclick="add_kategori()" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
																	</div>
																</form>
															</div>
														</div>
														<div class="collapse" id="add_detail">
															<br>
															<div class="col-md-2"></div>
															<div class="col-md-8">
																<form id="form_add_detail">
																	<div class="form-group">
																		<label>Kode</label>
																		<input type="text" placeholder="Masukkan Kode Kendaraan" id="kode_detail_kategori_add" name="kode_kategori" class="form-control" required="required" value="" readonly="readonly">
																	</div>
																	<div class="form-group">
																		<label>Pilih Tunjangan</label>
																		<select class="form-control select2" name="kode_tunjangan" id="data_kategori_add" onchange="dataTunjangan(this.value)" style="width: 100%;" required="required"></select>
																	</div>
																	<div class="form-group" id="data_tempat" style="display: none;">
																		<label>Tempat</label>
																		<?php
																		$penginapan[null] = 'Pilih Data';
																		$sel2 = [null];
																		$exsel2 = array('class'=>'form-control select2','placeholder'=>'penginapan','id'=>'penginapan','style'=>'width:100%');
																		echo form_dropdown('tempat',$penginapan,$sel2,$exsel2);
																		?>
																	</div>
																	<div class="form-group">
																		<label>Grade</label>
																		<select class="form-control select2" name="kode_grade" id="data_grade_add" style="width: 100%;" required="required"></select>
																	</div>
																	<div class="form-group">
																		<label>Nominal</label>
																		<input type="text" name="nominal" class="form-control input-money" placeholder="Besaran Intensif Tunjangan" required="required">
																	</div>
																	<div class="form-group">
																		<label>Keterangan</label>
																		<textarea name="keterangan" class="form-control" placeholder="Keterangan"></textarea>
																	</div>
																	<div class="form-group">
																		<button type="button" onclick="add_detail()" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
																	</div>
																</form>
															</div>
														</div>
													<?php } ?>
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<div class="callout callout-success"><label><i class="fa fa-info-circle"></i> Bantuan</label><br>Pilih <b>Kode Kategori</b> Untuk melihat detail Intensif/Tunjangan pada kategori tersebut</div>
											<table id="table_data_kategori" class="table table-bordered table-striped" width="100%">
												<thead>
													<tr>
														<th>No</th>
														<th>Kode</th>
														<th>Nama</th>
														<th>Keterangan</th>
														<th>Jumlah Data</th>
														<th>Tanggal</th>
														<th>Status</th>
														<th>Aksi</th>
													</tr>
												</thead>
												<tbody>
												</tbody>
											</table>
										</div>
									</div>
								</div>
								<div class="tab-pane" id="antar_plant">
									<div class="row">
										<div class="col-md-12">
											<div id="accordions">
												<div class="panel">
													<div class="row">
														<div class="col-md-12">
															<div class="pull-left">
																<?php if (in_array($access['l_ac']['add'], $access['access'])) {
																	echo '<a href="#add_plant" data-toggle="collapse"  data-parent="#accordions" class="btn btn-primary"><i class="fa fa-plus"></i> Tambah Data</a>';
																}?>
															</div>
															<div class="pull-right" style="font-size: 8pt;">
																<i class="fa fa-toggle-on stat scc"></i> Aktif<br>
																<i class="fa fa-toggle-off stat err"></i> Tidak Aktif
															</div>
														</div>
													</div>
													<?php if(in_array($access['l_ac']['add'], $access['access'])){?>
														<div class="collapse" id="add_plant">
															<br>
															<div class="col-md-2"></div>
															<div class="col-md-8">
																<form id="form_add_plant">
																	<div class="form-group">
																		<label>Kode Jarak</label>
																		<input type="text" placeholder="Masukkan Kode Jarak Antar Plant" id="kode_plant" name="kode" class="form-control" required="required" value="" readonly="readonly">
																	</div>
																	<div class="form-group">
																		<label>Plant Asal</label>
                                       									<select class="form-control select2" name="plant_asal" id="data_plant_asal_add" style="width: 100%;"></select>
																	</div>
																	<div class="form-group">
																		<label>Plant Tujuan</label>
                                       									<select class="form-control select2" name="plant_tujuan" id="data_plant_tujuan_add" style="width: 100%;"></select>
																	</div>
																	<div class="form-group">
																		<label>Jarak (KM)</label>
																		<input type="number" placeholder="Masukkan Jarak Antar Plant" id="data_jarak_plant" name="jarak" class="form-control field" required="required">
																	</div>
																	<div class="form-group">
																		<label>Keterangan</label>
																		<textarea name="keterangan" class="form-control" placeholder="Keterangan"></textarea>
																	</div>
																	<div class="form-group">
																		<button type="button" onclick="add_jarak_plant()" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
																	</div>
																</form>
															</div>
														</div>
													<?php } ?>
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<div class="callout callout-success"><label><i class="fa fa-info-circle"></i> Bantuan</label><br>Pilih <b>Kode Kategori</b> Untuk melihat detail Intensif/Tunjangan pada kategori tersebut</div>
											<table id="table_data_plant" class="table table-bordered table-striped" width="100%">
												<thead>
													<tr>
														<th>No</th>
														<th>Kode</th>
														<th>Plant Asal</th>
														<th>Plant Tujuan</th>
														<th>Jarak</th>
														<th>Keterangan</th>
														<th>Tanggal</th>
														<th>Status</th>
														<th>Aksi</th>
													</tr>
												</thead>
												<tbody>
												</tbody>
											</table>
										</div>
									</div>
								</div>
								<div class="tab-pane" id="kode_akun">
									<div class="row">
										<div class="col-md-12">
											<div id="accordions">
												<div class="panel">
													<div class="row">
														<div class="col-md-12">
															<div class="pull-left">
																<?php if (in_array($access['l_ac']['add'], $access['access'])) {
																	echo '<a href="#add_kode_akun" data-toggle="collapse"  data-parent="#accordions" class="btn btn-success"><i class="fa fa-plus"></i> Tambah Data</a>';
																}?>
															</div>
															<div class="pull-right" style="font-size: 8pt;">
																<i class="fa fa-toggle-on stat scc"></i> Aktif<br>
																<i class="fa fa-toggle-off stat err"></i> Tidak Aktif
															</div>
														</div>
													</div>
													<?php if(in_array($access['l_ac']['add'], $access['access'])){?>
														<div class="collapse" id="add_kode_akun">
															<br>
															<div class="col-md-2"></div>
															<div class="col-md-8">
																<form id="form_add_kode_akun">
																	<div class="form-group">
																		<label>Kode Akun</label>
																		<input type="text" placeholder="Masukkan Kode Akun" id="kode_akun" name="kode" class="form-control" required="required" value="">
																	</div>
																	<div class="form-group">
																		<label>Nama Akun</label>
																		<input type="text" placeholder="Masukkan Nama Akun" name="nama" class="form-control field" required="required">
																	</div>
																	<div class="form-group">
																		<label>Keterangan</label>
																		<textarea name="keterangan" class="form-control" placeholder="Keterangan"></textarea>
																	</div>
																	<div class="form-group">
																		<button type="button" onclick="add_kode_akun()" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
																	</div>
																</form>
															</div>
														</div>
													<?php } ?>
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<table id="table_data_kode_akun" class="table table-bordered table-striped" width="100%">
												<thead>
													<tr>
														<th>No</th>
														<th>Kode Akun</th>
														<th>Nama Akun</th>
														<th>Keterangan</th>
														<th>Tanggal</th>
														<th>Status</th>
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
<!-- view Kendaraan-->
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
							<label class="col-md-6 control-label">Kode Kendaraan</label>
							<div class="col-md-6" id="data_kode_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Nama Kendaraan</label>
							<div class="col-md-6" id="data_name_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Keterangan</label>
							<div class="col-md-6" id="data_keterangan_view"></div>
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
            <div class="row">
               <div class="col-md-12">
                  <div id="data_tabel_view"></div>
               </div>
            </div>
			</div>
			<div class="modal-footer">
				<?php if (in_array($access['l_ac']['edt'], $access['access'])) {
					echo '<button type="submit" class="btn btn-info" onclick="edit_modal()"><i class="fa fa-edit"></i> Edit</button>';
				}?>
				<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
			</div>
		</div>
	</div>
</div>
<!-- edit kendaraan-->
<div id="edit" class="modal fade" role="dialog">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h2 class="modal-title">Edit Data <b class="text-muted header_data"></b></h2>
			</div>
			<div class="modal-body">
				<form id="form_edit_kendaraan">
					<input type="hidden" id="data_id_edit" name="id" value="">
					<input type="hidden" id="data_kode_edit_old" name="kode_old" value="">
					<div class="form-group">
						<label>Kode Kendaraan</label>
						<input type="text" placeholder="Masukkan Kode Kendaraan" id="data_kode_edit" name="kode" value="" class="form-control" required="required" readonly="readonly">
					</div>
					<div class="form-group">
						<label>Nama Kendaraan</label>
						<input type="text" placeholder="Masukkan Nama Kendaraan" id="data_name_edit" name="nama" value="" class="form-control" required="required">
					</div>
					<div class="form-group">
						<label>Keterangan</label>
							<textarea name="keterangan" class="form-control" id="data_keterangane_edit" placeholder="Keterangan"></textarea>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" onclick="edit_kendaraan()" id="btn_edit" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- view Kategori tunjangan-->
<div id="view_kategori" class="modal fade" role="dialog">
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
							<label class="col-md-6 control-label">Kode Kategori</label>
							<div class="col-md-6" id="data_kode_k_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Nama Kategori</label>
							<div class="col-md-6" id="data_name_k_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Nominal Minimal</label>
							<div class="col-md-6" id="data_nominal_min_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Keterangan</label>
							<div class="col-md-6" id="data_keterangan_k_view"></div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Status</label>
							<div class="col-md-6" id="data_status_k_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Dibuat Tanggal</label>
							<div class="col-md-6" id="data_create_date_k_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Diupdate Tanggal</label>
							<div class="col-md-6" id="data_update_date_k_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Dibuat Oleh</label>
							<div class="col-md-6" id="data_create_by_k_view">
							</div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Diupdate Oleh</label>
							<div class="col-md-6" id="data_update_by_k_view">
							</div>
						</div>
					</div>
				</div>
            <div class="row">
               <div class="col-md-12">
                  <div id="data_tabel_k_view"></div>
               </div>
            </div>
			</div>
			<div class="modal-footer">
				<?php if (in_array($access['l_ac']['edt'], $access['access'])) {
					echo '<button type="submit" class="btn btn-info" onclick="edit_modal_kategori()"><i class="fa fa-edit"></i> Edit</button>';
				}?>
				<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
			</div>
		</div>
	</div>
</div>
<!-- edit kategori-->
<div id="edit_kategori" class="modal fade" role="dialog">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h2 class="modal-title">Edit Data <b class="text-muted header_data"></b></h2>
			</div>
			<div class="modal-body">
				<form id="form_edit_kategori">
					<input type="hidden" id="data_id_k_edit" name="id" value="">
					<input type="hidden" id="data_kode_edit_k_old" name="kode_old" value="">
					<div class="form-group">
						<label>Kode Kendaraan</label>
						<input type="text" placeholder="Masukkan Kode Kendaraan" id="data_kode_k_edit" name="kode" value="" class="form-control" required="required" readonly="readonly">
					</div>
					<div class="form-group">
						<label>Nama Kendaraan</label>
						<input type="text" placeholder="Masukkan Nama Kendaraan" id="data_name_k_edit" name="nama" value="" class="form-control" required="required">
					</div>
					<div class="form-group">
						<label>Nominal Minimal</label>
						<?php
							$yesno = $this->otherfunctions->getYesNoList();
							$yesno[null] = 'Pilih Data';
							$selpa = array(null);
							$expa = array('class'=>'form-control select2','style'=>'width:100%;','required'=>'required','id'=>'nominal_minimal_add');
							echo form_dropdown('nominal_min',$yesno,$selpa,$expa);
						?>
					</div>
					<div class="form-group">
						<label>Keterangan</label>
							<textarea name="keterangan" class="form-control" id="data_keterangane_k_edit" placeholder="Keterangan"></textarea>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" onclick="edit_kategori()" id="btn_edit" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
				</div>
			</form>
		</div>
	</div>
</div>
<div class="modal fade" id="import" role="dialog">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close all_btn_import" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Import Data Dari Excel</h4>
         </div>
         <form id="form_import" action="#">
            <div class="modal-body">
				<div class="callout callout-info text-left">
					<b><i class="fa fa-bullhorn"></i> Bantuan</b><br>
					<ul>
						<li>Pastikan cell pada file Excel tidak ada yang di <b>MERGE</b>, jika ada yang di <b>MERGE</b> harap melakukan <b style="color: red">UNMERGE</b> terlebih dahulu!</li>
						<li>Gunakan File Template Excel Master Grade yang telah anda Download dari <b>"Export Template Grade"</b></li>
					</ul>
				</div>
				<div class="form-group">
					<label>Pilih Tunjangan</label>
					<select class="form-control select2" name="kode_tunjangan" id="data_kategori_import" style="width: 100%;" required="required"></select>
				</div>
				<div class="text-center">
					<p class="text-muted">File Data Presensi harus tipe *.xls, *.xlsx, *.csv, *.ods dan *.ots</p>
					<input id="uploadFilex" placeholder="Nama File" readonly="readonly" class="form-control" required="required">
					<span class="input-group-btn">
						<div class="fileUpload btn btn-warning btn-flat">
							<span><i class="fa fa-folder-open"></i> Pilih File</span>
							<input id="uploadBtnx" type="file" class="upload" name="file" onchange="checkFile('#uploadBtnx','#uploadFilex','#save')" />
						</div>
					</span>
            	</div> 
            </div> 
            <div class="modal-footer">
               <div id="progress2" style="float: left;"></div>
               <button class="btn btn-primary all_btn_import" id="save" type="button" disabled style="margin-right: 4px;"><i class="fa fa-check-circle"></i> Upload</button>
               <button id="savex" type="submit" style="display: none;"></button>
               <button type="button" class="btn btn-default all_btn_import" data-dismiss="modal">Kembali</button>
            </div>
         </form>
      </div>
   </div>
</div>
<!-- <form id="form_filter">
	<input type="hidden" name="usage" value="rekap">
	<input type="hidden" name="kategori" value="<?php //echo $this->uri->segment(3) ?>">
</form> -->
<!-- view jarak antar plant-->
<div id="view_jarak" class="modal fade" role="dialog">
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
							<label class="col-md-6 control-label">Kode</label>
							<div class="col-md-6" id="data_kode_plant_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Plant Asal</label>
							<div class="col-md-6" id="data_asal_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Plant Tujuan</label>
							<div class="col-md-6" id="data_tujuan_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Jarak</label>
							<div class="col-md-6" id="data_jarak_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Keterangan</label>
							<div class="col-md-6" id="data_keterangan_plant_view"></div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Status</label>
							<div class="col-md-6" id="data_status_plant_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Dibuat Tanggal</label>
							<div class="col-md-6" id="data_create_date_plant_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Diupdate Tanggal</label>
							<div class="col-md-6" id="data_update_date_plant_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Dibuat Oleh</label>
							<div class="col-md-6" id="data_create_by_plant_view">
							</div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Diupdate Oleh</label>
							<div class="col-md-6" id="data_update_by_plant_view">
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<?php if (in_array($access['l_ac']['edt'], $access['access'])) {
					echo '<button type="submit" class="btn btn-info" onclick="edit_modal_jarak()"><i class="fa fa-edit"></i> Edit</button>';
				}?>
				<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
			</div>
		</div>
	</div>
</div>
<!-- edit jarak-->
<div id="edit_jarak" class="modal fade" role="dialog">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h2 class="modal-title">Edit Data <b class="text-muted header_data"></b></h2>
			</div>
			<div class="modal-body">
				<form id="form_edit_jarak">
					<input type="hidden" id="data_id_jarak_edit" name="id" value="">
					<input type="hidden" id="data_kode_edit_jarak_old" name="kode_old" value="">
					<div class="form-group">
						<label>Kode</label>
						<input type="text" placeholder="Masukkan Kode Kendaraan" id="data_kode_jarak_edit" name="kode" value="" class="form-control" required="required" readonly="readonly">
					</div>
					<div class="form-group">
						<label>Plant Asal</label>
						<select class="form-control select2" name="plant_asal" id="data_plant_asal_edit" style="width: 100%;"></select>
					</div>
					<div class="form-group">
						<label>Plant Tujuan</label>
						<select class="form-control select2" name="plant_tujuan" id="data_plant_tujuan_edit" style="width: 100%;"></select>
					</div>
					<div class="form-group">
						<label>Jarak</label>
						<input type="number" placeholder="Masukkan Nama Kendaraan" id="data_jarak_edit" name="jarak" value="" class="form-control" required="required">
					</div>
					<div class="form-group">
						<label>Keterangan</label>
							<textarea name="keterangan" class="form-control" id="data_keterangane_jarak_edit" placeholder="Keterangan"></textarea>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" onclick="edit_jarak()" id="btn_edit" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- delete kendaraan-->
<div id="delete_modal_kendaraan" class="modal fade" role="dialog">
	<div class="modal-dialog modal-sm modal-danger">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title text-center">Konfirmasi Hapus</h4>
			</div>
			<form id="form_delete_kendaraan">
				<div class="modal-body text-center">
					<input type="hidden" id="data_column_delete_kendaraan" name="column" value="id_pd_kendaraan">
					<input type="hidden" id="data_id_delete_kendaraan" name="id" value="">
					<input type="hidden" id="data_table_delete_kendaraan" name="table" value="master_pd_kendaraan">
					<input type="hidden" name="link_table" value="master_pd_bbm">
					<input type="hidden" name="link_col" value="kode_kendaraan">
					<input type="hidden" id="data_val_link_col" name="link_data_col" value="">
					<p>Apakah anda yakin akan menghapus data dengan nama <b id="data_name_delete_kendaraan" class="header_data_kendaraan"></b> ?</p>
				</div>
			</form>
			<div class="modal-footer">
				<button type="button" onclick="do_delete_kendaraan()" class="btn btn-primary"><i class="fa fa-trash"></i> Hapus</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
			</div>
		</div>
	</div>
</div>
<!-- view kode_akun-->
<div id="view_kode_akun" class="modal fade" role="dialog">
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
							<label class="col-md-6 control-label">Kode Akun</label>
							<div class="col-md-6" id="data_kode_akun_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Nama</label>
							<div class="col-md-6" id="data_nama_akun_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Keterangan</label>
							<div class="col-md-6" id="data_keterangan_akun_view"></div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Status</label>
							<div class="col-md-6" id="data_status_akun_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Dibuat Tanggal</label>
							<div class="col-md-6" id="data_create_date_akun_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Diupdate Tanggal</label>
							<div class="col-md-6" id="data_update_date_akun_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Dibuat Oleh</label>
							<div class="col-md-6" id="data_create_by_akun_view">
							</div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Diupdate Oleh</label>
							<div class="col-md-6" id="data_update_by_akun_view">
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<?php if (in_array($access['l_ac']['edt'], $access['access'])) {
					echo '<button type="submit" class="btn btn-info" onclick="edit_modal_akun()"><i class="fa fa-edit"></i> Edit</button>';
				}?>
				<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
			</div>
		</div>
	</div>
</div>
<!-- edit kode_akun-->
<div id="edit_akun" class="modal fade" role="dialog">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h2 class="modal-title">Edit Data <b class="text-muted header_data"></b></h2>
			</div>
			<div class="modal-body">
				<form id="form_edit_akun">
					<input type="hidden" id="data_id_kode_akun_edit" name="id" value="">
					<input type="hidden" id="data_kode_akun_old" name="kode_old" value="">
					<div class="form-group">
						<label>Kode Akun</label>
						<input type="text" placeholder="Masukkan Kode Akun" id="data_kode_akun_edit" name="kode" value="" class="form-control" required="required">
					</div>
					<div class="form-group">
						<label>Nama</label>
						<input type="text" placeholder="Masukkan Nama Akun" id="data_nama_akun_edit" name="nama" value="" class="form-control" required="required">
					</div>
					<div class="form-group">
						<label>Keterangan</label>
							<textarea name="keterangan" class="form-control" id="data_keterangan_akun_edit" placeholder="Keterangan"></textarea>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" onclick="edit_kode_akun()" id="btn_edit_kode_akun" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- delete kendaraan-->
<div id="delete_modal_kendaraan" class="modal fade" role="dialog">
	<div class="modal-dialog modal-sm modal-danger">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title text-center">Konfirmasi Hapus</h4>
			</div>
			<form id="form_delete_kendaraan">
				<div class="modal-body text-center">
					<input type="hidden" id="data_column_delete_kendaraan" name="column" value="id_pd_kendaraan">
					<input type="hidden" id="data_id_delete_kendaraan" name="id" value="">
					<input type="hidden" id="data_table_delete_kendaraan" name="table" value="master_pd_kendaraan">
					<input type="hidden" name="link_table" value="master_pd_bbm">
					<input type="hidden" name="link_col" value="kode_kendaraan">
					<input type="hidden" id="data_val_link_col" name="link_data_col" value="">
					<p>Apakah anda yakin akan menghapus data dengan nama <b id="data_name_delete_kendaraan" class="header_data_kendaraan"></b> ?</p>
				</div>
			</form>
			<div class="modal-footer">
				<button type="button" onclick="do_delete_kendaraan()" class="btn btn-primary"><i class="fa fa-trash"></i> Hapus</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
			</div>
		</div>
	</div>
</div>
<!-- delete kategori-->
<div id="delete_modal_kategori" class="modal fade" role="dialog">
	<div class="modal-dialog modal-sm modal-danger">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title text-center">Konfirmasi Hapus</h4>
			</div>
			<form id="form_delete_kategori">
				<div class="modal-body text-center">
					<input type="hidden" id="data_column_delete" name="column" value="id_pd_kategori">
					<input type="hidden" id="data_id_delete" name="id" value="">
					<input type="hidden" id="data_table_delete" name="table" value="master_pd_kategori">
					<input type="hidden" name="link_table" value="master_pd_detail_kategori">
					<input type="hidden" name="link_col" value="kode_kategori">
					<input type="hidden" id="data_val_link_col_kategori" name="link_data_col" value="">
					<p>Apakah anda yakin akan menghapus data dengan nama <b id="data_name_delete" class="header_data"></b> ?</p>
				</div>
			</form>
			<div class="modal-footer">
				<button type="button" onclick="do_delete_kategori()" class="btn btn-primary"><i class="fa fa-trash"></i> Hapus</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
			</div>
		</div>
	</div>
</div>
<!-- delete jarak plant-->
<div id="delete_modal_jarak" class="modal fade" role="dialog">
	<div class="modal-dialog modal-sm modal-danger">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title text-center">Konfirmasi Hapus</h4>
			</div>
			<form id="form_delete_jarak">
				<div class="modal-body text-center">
					<input type="hidden" id="data_column_delete_plant" name="column" value="id_jarak_plant">
					<input type="hidden" id="data_id_delete_plant" name="id" value="">
					<input type="hidden" id="data_table_delete_plant" name="table" value="master_pd_jarak_plant">
					<!-- <input type="hidden" name="link_table" value="master_pd_detail_kategori">
					<input type="hidden" name="link_col" value="kode_kategori">
					<input type="hidden" id="data_val_link_col_kategori" name="link_data_col" value=""> -->
					<p>Apakah anda yakin akan menghapus data dengan nama <b id="data_name_delete" class="header_data"></b> ?</p>
				</div>
			</form>
			<div class="modal-footer">
				<button type="button" onclick="do_delete_jarak()" class="btn btn-primary"><i class="fa fa-trash"></i> Hapus</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
			</div>
		</div>
	</div>
</div>
<!-- delete Kode Akun-->
<div id="delete_kode_akun" class="modal fade" role="dialog">
	<div class="modal-dialog modal-sm modal-danger">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title text-center">Konfirmasi Hapus</h4>
			</div>
			<form id="form_delete_akun">
				<div class="modal-body text-center">
					<input type="hidden" id="data_column_delete_akun" name="column" value="id_kode_akun">
					<input type="hidden" id="data_id_delete_akun" name="id" value="">
					<input type="hidden" id="data_table_delete_akun" name="table" value="master_pd_kode_akun">
					<p>Apakah anda yakin akan menghapus data dengan nama <b id="data_name_delete" class="header_data"></b> ?</p>
				</div>
			</form>
			<div class="modal-footer">
				<button type="button" onclick="do_delete_akun()" class="btn btn-primary"><i class="fa fa-trash"></i> Hapus</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
			</div>
		</div>
	</div>
</div>
<div id="modal_delete_partial"></div>
<script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
<script type="text/javascript">
	var url_select="<?php echo base_url('global_control/select2_global');?>";
	var table="master_pd_kendaraan";
	var column="id_pd_kendaraan";
	$(document).ready(function(){
		kendaraan();
		refreshCode();
		$('#save').click(function () {
			$('.all_btn_import').attr('disabled', 'disabled');
			$('#progress2').html('<i class="fa fa-spinner fa-pulse fa-fw"></i> Mohon Tunggu, Data sedang di upload....')
			setTimeout(function () {
				$('#savex').click();
			}, 1000);
		})
		$('#form_import').submit(function (e) {
			e.preventDefault();
			var data_add = new FormData(this);
			var urladd = "<?php echo base_url('master/import_grade_perjalanan_dinas'); ?>";
			submitAjaxFile(urladd, data_add, '#import', '#progress2', '.all_btn_import');
			$('#table_data_kategori').DataTable().ajax.reload(function () {
				Pace.restart();
			});
		});
	});
	function refreshCode() {
		kode_generator("<?php echo base_url('master/master_pd_kendaraan/kode');?>",'kode_pd_add');
		kode_generator("<?php echo base_url('master/master_bbm_kendaraan/kode');?>",'kode_bbm_add');
		kode_generator("<?php echo base_url('master/master_pd_kategori/kode');?>",'kode_kategori_add');
		kode_generator("<?php echo base_url('master/master_detail_kategori/kode');?>",'kode_detail_kategori_add');
		kode_generator("<?php echo base_url('master/master_pd_jarak_plant/kode');?>",'kode_plant');
	}
	function kendaraan() {
		select_data('data_kendaraan_add',url_select,'master_pd_kendaraan','kode','nama');
		$('#table_data_kendaraan').DataTable().destroy();
		$('#table_data_kendaraan').DataTable( {
			ajax: {
				url: "<?php echo base_url('master/master_pd_kendaraan/view_all/')?>",
				type: 'POST',
				data:{access:"<?php echo $this->codegenerator->encryptChar($access);?>"}
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
				width: '15%',
				render: function ( data, type, full, meta ) {
					return '<a href="<?php base_url()?>view_intensif_perjalanan_dinas/'+full[8]+'">'+data+'</a>';
				}
			},
			{   targets: 5,
				width: '10%',
				render: function ( data, type, full, meta ) {
					return '<center>'+data+'</center>';
				}
			},
			{   targets: 6, 
				width: '10%',
				render: function ( data, type, full, meta ) {
					return '<center>'+data+'</center>';
				}
			},
			{   targets: 7,
				width: '10%',
				render: function ( data, type, full, meta ) {
					return '<center>'+data+'</center>';
				}
			},
			]
		});
	}
	function view_modal(id) {
		var data={id_pd_kendaraan:id};
		var callback=getAjaxData("<?php echo base_url('master/master_pd_kendaraan/view_one')?>",data);  
		$('#view').modal('show');
		$('.header_data').html(callback['nama']);
		$('#data_kode_view').html(callback['kode']);
		$('#data_name_view').html(callback['nama']);
		$('#data_keterangan_view').html(callback['v_keterangan']);
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
	}
	function edit_modal() {
		var id = $('input[name="data_id_view"]').val();
		var data={id_pd_kendaraan:id};
		var callback=getAjaxData("<?php echo base_url('master/master_pd_kendaraan/view_one')?>",data); 
		$('#view').modal('toggle');
		setTimeout(function () {
			$('#edit').modal('show');
		},600); 
		$('.header_data').html(callback['nama']);
		$('#data_id_edit').val(callback['id']);
		$('#data_kode_edit_old').val(callback['kode']);
		$('#data_kode_edit').val(callback['kode']);
		$('#data_name_edit').val(callback['nama']);
		$('#data_keterangane_edit').val(callback['keterangan']);
	}
	function delete_modal(id) {
		var data={id_pd_kendaraan:id};
		$('#delete_modal_kendaraan').modal('toggle');
		var callback=getAjaxData("<?php echo base_url('master/master_pd_kendaraan/view_one')?>",data);
		$('#delete_modal_kendaraan #data_id_delete_kendaraan').val(callback['id']);
		$('#delete_modal_kendaraan .header_data_kendaraan').html(callback['nama']);
		$('#delete_modal_kendaraan #data_val_link_col').val(callback['kode']);
	}
	function do_delete_kendaraan(){
		submitAjax("<?php echo base_url('global_control/delete')?>",'delete_modal_kendaraan','form_delete_kendaraan',null,null);
		$('#table_data_kendaraan').DataTable().ajax.reload(function (){
			Pace.restart();
		});
	}
	function do_status(id,data) {
		var data_table={status:data};
		var where={id_pd_kendaraan:id};
		var datax={table:table,where:where,data:data_table};
		submitAjax("<?php echo base_url('global_control/change_status')?>",null,datax,null,null,'status');
		$('#table_data_kendaraan').DataTable().ajax.reload();
	}
	function edit_kendaraan(){
		if($("#form_edit_kendaraan")[0].checkValidity()) {
			submitAjax("<?php echo base_url('master/edt_master_pd_kendaraan')?>",'edit','form_edit_kendaraan',null,null);
			$('#table_data_kendaraan').DataTable().ajax.reload();
		}else{
			notValidParamx();
		} 
	}
	function add_kendaraan(){
		if($("#form_add_kendaraan")[0].checkValidity()) {
			submitAjax("<?php echo base_url('master/add_master_pd_kendaraan')?>",null,'form_add_kendaraan',"<?php echo base_url('master/master_pd_kendaraan/kode');?>",'kode_pd_add');
			$('#table_data_kendaraan').DataTable().ajax.reload(function(){
				Pace.restart();
			});
			$('#form_add_kendaraan')[0].reset();
			refreshCode();
		}else{
			notValidParamx();
		} 
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
			}
		},100);
	}
	function add_bbm(){
		if($("#form_add_bbm")[0].checkValidity()) {
			submitAjax("<?php echo base_url('master/add_master_bbm_kendaraan')?>",null,'form_add_bbm',"<?php echo base_url('master/master_pd_kendaraan/kode');?>",'kode_bbm_add');
			$('#table_data_kendaraan').DataTable().ajax.reload(function(){
				Pace.restart();
			});
			$('#form_add_bbm')[0].reset();
			refreshCode();resetselectAddBBM();
		}else{
			notValidParamx();
		} 
	}
	function refresh_kendaraan(){
		select_data('data_kendaraan_add',url_select,'master_pd_kendaraan','kode','nama');
	}
//========== Kategori Tunjangan ===================
	function kategori_tunjangan() {
		select_data('data_kategori_add',url_select,'master_pd_kategori','kode','nama');
		select_data('data_kategori_import',url_select,'master_pd_kategori','kode','nama');
		getSelect2("<?php echo base_url('employee/emp_part_jabatan_grade/grade')?>",'data_grade_add');
		$('#table_data_kategori').DataTable().destroy();
		$('#table_data_kategori').DataTable( {
			ajax: {
				url: "<?php echo base_url('master/master_pd_kategori/view_all/')?>",
				type: 'POST',
				data:{access:"<?php echo $this->codegenerator->encryptChar($access);?>"}
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
				width: '15%',
				render: function ( data, type, full, meta ) {
					return '<a href="<?php base_url()?>detail_kategori_perjalanan_dinas/'+full[8]+'">'+data+'</a>';
				}
			},
			{   targets: 5,
				width: '10%',
				render: function ( data, type, full, meta ) {
					return '<center>'+data+'</center>';
				}
			},
			{   targets: 6, 
				width: '10%',
				render: function ( data, type, full, meta ) {
					return '<center>'+data+'</center>';
				}
			},
			{   targets: 7,
				width: '10%',
				render: function ( data, type, full, meta ) {
					return '<center>'+data+'</center>';
				}
			},
			]
		});
	}
	function refresh_kategori(){
		select_data('data_kategori_add',url_select,'master_pd_kategori','kode','nama');
		getSelect2("<?php echo base_url('employee/emp_part_jabatan_grade/grade')?>",'data_grade_add');
	}
	function dataTunjangan(f) {
		setTimeout(function () {
			var name = $('#data_kategori_add').val();
			if(name == 'KAPD0001') {
				$('#data_tempat').show();
				$('#kode_tempat_add').attr('required','required');
			}else {
				$('#data_tempat').hide();
				$('#kode_tempat_add').removeAttr('required');
			}
		},100);
	}
	function view_modal_kategori(id) {
		var data={id_pd_kategori:id};
		var callback=getAjaxData("<?php echo base_url('master/master_pd_kategori/view_one')?>",data);  
		$('#view_kategori').modal('show');
		$('.header_data').html(callback['nama']);
		$('#data_kode_k_view').html(callback['kode']);
		$('#data_name_k_view').html(callback['nama']);
		$('#data_nominal_min_view').html(callback['nominal_min_view']);
		$('#data_keterangan_k_view').html(callback['v_keterangan']);
		var status = callback['status'];
		if(status==1){
			var statusval = '<b class="text-success">Aktif</b>';
		}else{
			var statusval = '<b class="text-danger">Tidak Aktif</b>';
		}
		$('#data_status_k_view').html(statusval);
		$('#data_create_date_k_view').html(callback['create_date']+' WIB');
		$('#data_update_date_k_view').html(callback['update_date']+' WIB');
		$('input[name="data_id_view"]').val(callback['id']);
		$('#data_create_by_k_view').html(callback['nama_buat']);
		$('#data_update_by_k_view').html(callback['nama_update']);
		$('#data_tabel_k_view').html(callback['tabel']);
	}
	function edit_modal_kategori() {
		var id = $('input[name="data_id_view"]').val();
		var data={id_pd_kategori:id};
		var callback=getAjaxData("<?php echo base_url('master/master_pd_kategori/view_one')?>",data); 
		$('#view_kategori').modal('toggle');
		setTimeout(function () {
			$('#edit_kategori').modal('show');
		},600); 
		$('.header_data').html(callback['nama']);
		$('#data_id_k_edit').val(callback['id']);
		$('#data_kode_edit_k_old').val(callback['kode']);
		$('#data_kode_k_edit').val(callback['kode']);
		$('#data_name_k_edit').val(callback['nama']);
		$('#nominal_minimal_add').val(callback['nominal_min']);
		$('#data_keterangane_k_edit').val(callback['keterangan']);
	}
	function edit_kategori(){
		if($("#form_edit_kategori")[0].checkValidity()) {
			submitAjax("<?php echo base_url('master/edt_master_pd_kategori')?>",'edit_kategori','form_edit_kategori',null,null);
			$('#table_data_kategori').DataTable().ajax.reload();
		}else{
			notValidParamx();
		} 
	}
	function do_status_kategori(id,data) {
		var data_table={status:data};
		var where={id_pd_kategori:id};
		var datax={table:'master_pd_kategori',where:where,data:data_table};
		submitAjax("<?php echo base_url('global_control/change_status')?>",null,datax,null,null,'status');
		$('#table_data_kategori').DataTable().ajax.reload();
	}
	function delete_modal_kategori(id) {
		var data={id_pd_kategori:id};
		$('#delete_modal_kategori').modal('toggle');
		var callback=getAjaxData("<?php echo base_url('master/master_pd_kategori/view_one')?>",data);
		$('#delete_modal_kategori #data_id_delete').val(callback['id']);
		$('#delete_modal_kategori .header_data').html(callback['nama']);
		$('#delete_modal_kategori #data_val_link_col_kategori').val(callback['kode']);
	}
	function do_delete_kategori(){
		submitAjax("<?php echo base_url('global_control/delete')?>",'delete_modal_kategori','form_delete_kategori',null,null);
		$('#table_data_kategori').DataTable().ajax.reload(function (){
			Pace.restart();
		});
	}
	function add_kategori(){
		if($("#form_add_kategori")[0].checkValidity()) {
			submitAjax("<?php echo base_url('master/add_master_pd_kategori')?>",null,'form_add_kategori',"<?php echo base_url('master/master_pd_kendaraan/kode');?>",'data_kategori_add');
			$('#table_data_kategori').DataTable().ajax.reload(function(){
				Pace.restart();
			});
			$('#form_add_kategori')[0].reset();
			refreshCode();refresh_kategori();
		}else{
			notValidParamx();
		} 
	}
	function add_detail(){
		if($("#form_add_detail")[0].checkValidity()) {
			submitAjax("<?php echo base_url('master/add_master_detail_kategori')?>",null,'form_add_detail',"<?php echo base_url('master/master_pd_kendaraan/kode');?>",'kode_detail_kategori_add');
			$('#table_data_kategori').DataTable().ajax.reload(function(){
				Pace.restart();resetselectTunjangan();
			});
			$('#form_add_detail')[0].reset();
			refreshCode();
		}else{
			notValidParamx();
		} 
	}
	function resetselectTunjangan() {
		$('#data_kategori_add').val('').trigger('change');
		$('#penginapan').val('').trigger('change');
		$('#data_grade_add').val('').trigger('change');
	}
	function resetselectAddBBM() {
		$('#data_kendaraan_add').val('').trigger('change');
		$('#kendaraan_umum').val('').trigger('change');
	}
	function checkFile(idf, idt, btnx) {
		var fext = ['xls', 'xlsx', 'csv', 'ods', 'ots'];
		pathFile(idf, idt, fext, btnx);
	}

	function export_template() {
		$.redirect("<?php echo base_url('rekap/export_template_grade_pd'); ?>", {
				data_filter: $('#form_filter').serialize()
			},
			"POST", "_blank");
	}
//========== JARAK ANTAR PLAN ===================
	function jarak_antar_plant() {
		select_data('data_plant_asal_add',url_select,'master_loker','kode_loker','nama');
		select_data('data_plant_tujuan_add',url_select,'master_loker','kode_loker','nama');
		$('#table_data_plant').DataTable().destroy();
		$('#table_data_plant').DataTable( {
			ajax: {
				url: "<?php echo base_url('master/master_pd_jarak_plant/view_all/')?>",
				type: 'POST',
				data:{access:"<?php echo $this->codegenerator->encryptChar($access);?>"}
			},
			scrollX: true,
			columnDefs: [
			{   targets: 0, 
				width: '5%',
				render: function ( data, type, full, meta ) {
					return '<center>'+(meta.row+1)+'.</center>';
				}
			},
			{   targets: 6,
				width: '10%',
				render: function ( data, type, full, meta ) {
					return '<center>'+data+'</center>';
				}
			},
			{   targets: 7, 
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
	function view_modal_jarak(id) {
		var data={id_jarak_plant:id};
		var callback=getAjaxData("<?php echo base_url('master/master_pd_jarak_plant/view_one')?>",data);  
		$('#view_jarak').modal('show');
		$('.header_data').html(callback['kode']);
		$('#data_kode_plant_view').html(callback['kode']);
		$('#data_asal_view').html(callback['asal']);
		$('#data_tujuan_view').html(callback['tujuan']);
		$('#data_jarak_view').html(callback['jarak']);
		$('#data_keterangan_plant_view').html(callback['v_keterangan']);
		var status = callback['status'];
		if(status==1){
			var statusval = '<b class="text-success">Aktif</b>';
		}else{
			var statusval = '<b class="text-danger">Tidak Aktif</b>';
		}
		$('#data_status_plant_view').html(statusval);
		$('#data_create_date_plant_view').html(callback['create_date']+' WIB');
		$('#data_update_date_plant_view').html(callback['update_date']+' WIB');
		$('input[name="data_id_view"]').val(callback['id']);
		$('#data_create_by_plant_view').html(callback['nama_buat']);
		$('#data_update_by_plant_view').html(callback['nama_update']);
	}
	function edit_modal_jarak() {
		var id = $('input[name="data_id_view"]').val();
		var data={id_jarak_plant:id};
		var callback=getAjaxData("<?php echo base_url('master/master_pd_jarak_plant/view_one')?>",data); 
		select_data('data_plant_asal_edit',url_select,'master_loker','kode_loker','nama');
		select_data('data_plant_tujuan_edit',url_select,'master_loker','kode_loker','nama');
		$('#view_jarak').modal('toggle');
		setTimeout(function () {
			$('#edit_jarak').modal('show');
		},600); 
		$('.header_data').html(callback['kode']);
		$('#data_id_jarak_edit').val(callback['id']);
		$('#data_id_plant_edit').val(callback['id']);
		$('#data_kode_edit_jarak_old').val(callback['kode']);
		$('#data_kode_jarak_edit').val(callback['kode']);
		$('#data_plant_asal_edit').val(callback['e_asal']).trigger('change');
		$('#data_plant_tujuan_edit').val(callback['e_tujuan']).trigger('change');
		$('#data_jarak_edit').val(callback['jarak']);
		$('#data_keterangane_jarak_edit').val(callback['keterangan']);
	}
	function edit_jarak(){
		if($("#form_edit_jarak")[0].checkValidity()) {
			submitAjax("<?php echo base_url('master/edt_master_pd_jarak_plant')?>",'edit_jarak','form_edit_jarak',null,null);
			$('#table_data_plant').DataTable().ajax.reload();
		}else{
			notValidParamx();
		} 
	}
	function do_status_jarak(id,data) {
		var data_table={status:data};
		var where={id_jarak_plant:id};
		var datax={table:'master_pd_jarak_plant',where:where,data:data_table};
		submitAjax("<?php echo base_url('global_control/change_status')?>",null,datax,null,null,'status');
		$('#table_data_plant').DataTable().ajax.reload();
	}
	function delete_modal_jarak(id) {
		var data={id_jarak_plant:id};
		$('#delete_modal_jarak').modal('toggle');
		var callback=getAjaxData("<?php echo base_url('master/master_pd_jarak_plant/view_one')?>",data);
		$('#delete_modal_jarak #data_id_delete_plant').val(callback['id']);
		$('#delete_modal_jarak .header_data').html(callback['kode']);
		// $('#delete_modal_jarak #data_val_link_col_jarak').val(callback['kode']);
	}
	function do_delete_jarak(){
		submitAjax("<?php echo base_url('global_control/delete')?>",'delete_modal_jarak','form_delete_jarak',null,null);
		$('#table_data_plant').DataTable().ajax.reload(function (){
			Pace.restart();
		});
	}
	function add_jarak_plant(){
		if($("#form_add_plant")[0].checkValidity()) {
			submitAjax("<?php echo base_url('master/add_master_pd_jarak_plant')?>",null,'form_add_plant',"<?php echo base_url('master/master_pd_jarak_plant/kode');?>",'kode_plant');
			$('#table_data_plant').DataTable().ajax.reload(function(){
				Pace.restart();
			});
			$('#form_add_plant')[0].reset();
			refreshCode();
		}else{
			notValidParamx();
		} 
	}
//========== KODE AKUN PERJALANAN DINAS ===================
	function kode_akun() {
		$('#table_data_kode_akun').DataTable().destroy();
		$('#table_data_kode_akun').DataTable( {
			ajax: {
				url: "<?php echo base_url('master/master_pd_kode_akun/view_all/')?>",
				type: 'POST',
				data:{access:"<?php echo $this->codegenerator->encryptChar($access);?>"}
			},
			scrollX: true,
			columnDefs: [
			{   targets: 0, 
				width: '5%',
				render: function ( data, type, full, meta ) {
					return '<center>'+(meta.row+1)+'.</center>';
				}
			},
			{   targets: 4,
				width: '10%',
				render: function ( data, type, full, meta ) {
					return '<center>'+data+'</center>';
				}
			},
			{   targets: 5, 
				width: '10%',
				render: function ( data, type, full, meta ) {
					return '<center>'+data+'</center>';
				}
			},
			{   targets: 6,
				width: '10%',
				render: function ( data, type, full, meta ) {
					return '<center>'+data+'</center>';
				}
			},
			]
		});
	}
	function view_kode_akun(id) {
		var data={id_kode_akun:id};
		var callback=getAjaxData("<?php echo base_url('master/master_pd_kode_akun/view_one')?>",data);  
		$('#view_kode_akun').modal('show');
		$('.header_data').html(callback['kode']);
		$('#data_kode_akun_view').html(callback['kode']);
		$('#data_nama_akun_view').html(callback['nama']);
		$('#data_keterangan_akun_view').html(callback['v_keterangan']);
		var status = callback['status'];
		if(status==1){
			var statusval = '<b class="text-success">Aktif</b>';
		}else{
			var statusval = '<b class="text-danger">Tidak Aktif</b>';
		}
		$('#data_status_akun_view').html(statusval);
		$('#data_create_date_akun_view').html(callback['create_date']+' WIB');
		$('#data_update_date_akun_view').html(callback['update_date']+' WIB');
		$('input[name="data_id_view"]').val(callback['id']);
		$('#data_create_by_akun_view').html(callback['nama_buat']);
		$('#data_update_by_akun_view').html(callback['nama_update']);
	}
	function edit_modal_akun() {
		var id = $('input[name="data_id_view"]').val();
		var data={id_kode_akun:id};
		var callback=getAjaxData("<?php echo base_url('master/master_pd_kode_akun/view_one')?>",data); 
		$('#view_kode_akun').modal('toggle');
		setTimeout(function () {
			$('#edit_akun').modal('show');
		},600); 
		$('.header_data').html(callback['kode']);
		$('#data_id_kode_akun_edit').val(callback['id']);
		$('#data_kode_akun_old').val(callback['kode']);
		$('#data_kode_akun_edit').val(callback['kode']);
		$('#data_nama_akun_edit').val(callback['nama']);
		$('#data_keterangan_akun_edit').val(callback['keterangan']);
	}
	function edit_kode_akun(){
		if($("#form_edit_akun")[0].checkValidity()) {
			submitAjax("<?php echo base_url('master/edt_master_pd_kode_akun')?>",'edit_akun','form_edit_akun',null,null);
			$('#table_data_kode_akun').DataTable().ajax.reload();
		}else{
			notValidParamx();
		} 
	}
	function do_status_kode_akun(id,data) {
		var data_table={status:data};
		var where={id_kode_akun:id};
		var datax={table:'master_pd_kode_akun',where:where,data:data_table};
		submitAjax("<?php echo base_url('global_control/change_status')?>",null,datax,null,null,'status');
		$('#table_data_kode_akun').DataTable().ajax.reload();
	}
	function delete_kode_akun(id) {
		var data={id_kode_akun:id};
		$('#delete_kode_akun').modal('toggle');
		var callback=getAjaxData("<?php echo base_url('master/master_pd_kode_akun/view_one')?>",data);
		$('#delete_kode_akun #data_id_delete_akun').val(callback['id']);
		$('#delete_kode_akun .header_data').html(callback['kode']);
	}
	function do_delete_akun(){
		submitAjax("<?php echo base_url('global_control/delete')?>",'delete_kode_akun','form_delete_akun',null,null);
		$('#table_data_kode_akun').DataTable().ajax.reload(function (){
			Pace.restart();
		});
	}
	function add_kode_akun(){
		if($("#form_add_kode_akun")[0].checkValidity()) {
			submitAjax("<?php echo base_url('master/add_master_pd_kode_akun')?>",null,'form_add_kode_akun',"<?php echo base_url('master/master_pd_kode_akun/kode');?>",'kode_akun');
			$('#table_data_kode_akun').DataTable().ajax.reload(function(){
				Pace.restart();
			});
			$('#form_add_kode_akun')[0].reset();
			refreshCode();
		}else{
			notValidParamx();
		} 
	}
</script>
