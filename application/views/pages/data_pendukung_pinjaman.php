
		<div class="row">
			<div class="col-md-12">
				<div class="box box-success">
					<div class="box-header with-border">
						<h3 class="box-title"><i class="fa fa-list"></i> Daftar Master Pinjaman</h3>
						<div class="box-tools pull-right">
							<button class="btn btn-box-tool" onclick="reload_table('table_data')" data-toggle="tooltip" title="Refresh Table"><i class="fas fa-sync"></i></button>
							<button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
							<button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i class="fa fa-times"></i></button>
						</div>
					</div>
					<div class="box-body">
						<div class="row">
							<form id="form_filter">
								<div class="col-md-2"> </div>
								<div class="col-md-4">
									<div class="form-group">
										<label>Bulan</label>
										<?php
											$bulan_cari = $this->formatter->getMonth();
											$sel_cari = array(date('m'));
											$ex_cari = array('class'=>'form-control select2', 'id'=>'bulan_cari', 'style'=>'width:100%;','required'=>'required');
											echo form_dropdown('bulan',$bulan_cari,$sel_cari,$ex_cari);
										?>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label>Tahun</label>
										<?php
											$tahun_cari = $this->formatter->getYear();
											$selt_cari = array(date('Y'));
											$ext_cari = array('class'=>'form-control select2', 'id'=>'tahun_cari', 'style'=>'width:100%;','required'=>'required');
											echo form_dropdown('tahun',$tahun_cari,$selt_cari,$ext_cari);
										?>
									</div>
								</div>
							</form>
						</div>
						<div class="row">
							<div class="col-md-12">
								<div class="pull-right">
									<button type="button" onclick="refresh_tabel('search')" class="btn btn-success"><i class="fa fa-eye"></i> Lihat Data</button>
								</div>
							</div>
						</div><hr>
						<div class="row">
							<div class="col-md-12">
								<div class="row">
									<div class="col-md-12">
										<div class="pull-left">
											<?php if (in_array($access['l_ac']['add'], $access['access'])) {
												echo '<button class="btn btn-success" id="btn_tambah" type="button" data-toggle="collapse" data-target="#add" aria-expanded="false" aria-controls="import"><i class="fa fa-plus"></i> Tambah Pinjaman</button>';
											}?>
											<button class="btn btn-danger" data-toggle="modal" data-target="#modal_cetak" aria-expanded="false" aria-controls="import" style="margin-right: 4px;"><i class="fa fa-print"></i> Cetak Pinjaman</button>
										</div>
										<div class="pull-right" style="font-size: 8pt;">
											<i class="fa fa-toggle-on stat scc"></i> Aktif<br>
											<i class="fa fa-toggle-off stat err"></i> Tidak Aktif
										</div>
									</div>
								</div>
								<?php if(in_array($access['l_ac']['add'], $access['access'])){?>
									<div class="collapse" id="add">
										<div class="col-md-12">
											<input type="hidden" id="key_btn_tambah" value="0">
											<form id="form_add">
												<div class="row">
													<p class="text-danger">Semua data harus diisi!</p>
													<div class="col-md-12">
														<div class="col-md-6">
														<div class="form-group">
															<label>Kode Pinjaman</label>
															<input type="text" placeholder="Masukkan Kode Pinjaman" id="data_kode_add" name="kode" class="form-control" required="required" value="" readonly="readonly">
														</div>
														<div class="form-group">
															<label>Nama Pinjaman</label>
															<input type="text" placeholder="Masukkan Nama Pinjaman" id="data_name_add" name="nama" class="form-control field" required="required">
														</div>
														<div class="form-group">
															<label>Pilih Karyawan</label>
															<select class="form-control select2" name="karyawan" id="data_karyawan_add" style="width: 100%;"></select>
														</div>
														<div class="row">
															<div class="col-md-6">
																<div class="form-group">
																	<label>Bulan Mulai Bayar</label>
																	<?php
																	$bulan_add = $this->formatter->getMonth();
																	$sel_ser = array(date('m'));
																	$ex_ser = array('class'=>'form-control select2', 'id'=>'bulan_add', 'style'=>'width:100%;','required'=>'required');
																	echo form_dropdown('bulan',$bulan_add,$sel_ser,$ex_ser);
																	?>
																</div>
															</div>
															<div class="col-md-6">
																<div class="form-group">
																	<label>Tahun Mulai Bayar</label>
																	<?php
																	$tahun_add = $this->formatter->getYear();
																	$sels = array(date('Y'));
																	$exs = array('class'=>'form-control select2', 'id'=>'tahun_add', 'style'=>'width:100%;','required'=>'required');
																	echo form_dropdown('tahun',$tahun_add,$sels,$exs);
																	?>
																</div>
															</div>
														</div>
														<div class="form-group">
															<label>Keterangan</label>
															<textarea name="keterangan" id="data_keterangan_add" class="form-control field" placeholder="Keterangan"></textarea>
														</div>
														</div>
														<div class="col-md-6">
														<div class="form-group">
															<label>Nominal Pinjaman</label>
															<input type="text" placeholder="Masukkan Nominal Pinjaman" id="data_nominal_add" name="nominal" min="0" value="Rp. " step="0.01" class="form-control field input-money" required="required">
														</div>
														<div class="form-group">
															<label>Lama Angsuran</label>
															<input type="number" placeholder="Berapa Kali Angsuran" id="data_lama_angsuran_add" name="lama_angsuran" min="0" class="form-control field input-number" required="required">
														</div>
														<div class="form-group">
															<span id="denda_off" style="font-size: 20px;"><i class="far fa-square" aria-hidden="true"></i></span>
															<span id="denda_on" style="display: none; font-size: 20px;"><i class="far fa-check-square" aria-hidden="true"></i></span>
															<span style="padding-bottom: 9px;vertical-align: middle;"><b> Jenis Angsuran</b>
															<small class="text-muted"><font color="red"> (Ceklist Jika Nominal Angsuran Berbeda setiap bulannya)</font></small></span>
															<input type="hidden" id="jenis_angsuran_add" name="denda">
														</div>
														<div class="form-group" id="angsuranPerbulan" style="display:none;">
															<label>Angsuran Perbulan</label>
															<input type="number" placeholder="Angsuran Perbulan" id="angsuran_perbulan_add" name="angsuran_per_bulan" min="0" class="form-control field input-number">
														</div>
														<div id="pattern_add"></div>
														</div>
													</div>
												</div><br>
												<div class="row">
													<div class="col-md-12">
														<div class="form-group">
														<button type="button" onclick="do_add()" id="btn_add" class="btn btn-success pull-right"><i class="fa fa-floppy-o"></i> Simpan</button>
														</div>
													</div>
												</div><br><hr>
											</form>
										</div>
									</div>
								<?php } ?>
							</div>
						</div>
						<br>
						<div class="row">
							<div class="col-md-12">
								<!-- Data Begin Here -->
								<table id="table_data" class="table table-bordered table-striped table-responsive" width="100%">
									<thead>
										<tr>
											<th>No.</th>
											<th>Kode Pinjaman</th>
											<th>Nama Pinjaman</th>
											<th>Karyawan</th>
											<th>Mulai Potong Gaji</th>
											<th>Besar Pinjaman</th>
											<th>Lama Angsuran</th>
											<th>Sudah Diangsur</th>
											<th>Sisa Pinjaman</th>
											<th>Status Pinjaman</th>
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

<!-- view -->
<div id="view" class="modal fade" role="dialog">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="btn btn-danger btn-sm pull-right" data-dismiss="modal"><i class="fa fa-times"></i></button>
            <h2 class="modal-title">Detail Data <b class="text-muted header_data"></b></h2>
            <input type="hidden" name="data_id_view">
         </div>
         <div class="modal-body">
            <div class="row" id="view_loading" style="text-align: center;">
               <i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
            </div>
            <div class="row" id="view_show">
               <div class="col-md-6">
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Kode Pinjaman</label>
                     <div class="col-md-6" id="data_kode_view"></div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Nama</label>
                     <div class="col-md-6" id="data_name_view"></div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Karyawan</label>
                     <div class="col-md-6" id="data_karyawan_view"></div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Jabatan</label>
                     <div class="col-md-6" id="data_jabatan_view"></div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Bagian</label>
                     <div class="col-md-6" id="data_bagian_view"></div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Lokasi Kerja</label>
                     <div class="col-md-6" id="data_loker_view"></div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Mulai Potong Gaji</label>
                     <div class="col-md-6" id="data_tanggal_view"></div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Besar Pinjaman</label>
                     <div class="col-md-6" id="data_nominal_view"></div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Lama Angsuran</label>
                     <div class="col-md-6" id="data_lama_angsuran_view"></div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Sudah Diangsur</label>
                     <div class="col-md-6" id="data_sudah_diangsur_view"></div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Sisa Pinjaman</label>
                     <div class="col-md-6" id="data_sisa_pinjaman_view"></div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Keterangan</label>
                     <div class="col-md-6" id="data_keterangan_view"></div>
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Status</label>
                     <div class="col-md-6" id="data_status_view"> </div>
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
            <hr>
            <div class="row">
               <div class="col-md-12">
                  <div class="col-md-12">
                     <h3 class="text-center">Detail Besaran Angsuran</h3>
                     <div id="data_table_view_plan"></div>
                  </div>
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
<!-- edit -->
<div id="edit" class="modal fade" role="dialog">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="btn btn-danger btn-sm pull-right" data-dismiss="modal"><i class="fa fa-times"></i></button>
            <h2 class="modal-title">Edit Data <b class="text-muted header_data"></b></h2>
         </div>
         <div class="modal-body">
            <div class="row" id="edit_loading" style="text-align: center;">
               <i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
            </div>
            <div class="row" id="edit_show">
               	<div class="col-md-12">
					<form id="form_edit">
						<input type="hidden" id="data_id_edit" name="id" value="">
						<input type="hidden" id="data_kode_edit_old" name="kode_old" value="">
						<div class="form-group">
							<label>Kode Pinjaman</label>
							<input type="text" placeholder="Masukkan Kode Pinjaman" id="data_kode_edit" name="kode" class="form-control" required="required" value="" readonly="readonly">
						</div>
						<div class="form-group">
							<label>Nama Pinjaman</label>
							<input type="text" placeholder="Masukkan Nama Pinjaman" id="data_name_edit" name="nama" class="form-control field" required="required">
						</div>
						<div class="form-group">
							<label>Pilih Karyawan</label>
							<select class="form-control select2" name="karyawan" id="data_karyawan_edit" style="width: 100%;">
								<option></option>
							</select>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
								<label>Bulan</label>
								<?php
									$bulan_edt = $this->formatter->getMonth();
									$sel_ser = array(date('m'));
									$ex_ser = array('class'=>'form-control select2', 'id'=>'data_bulan_edit', 'style'=>'width:100%;','required'=>'required');
									echo form_dropdown('bulan',$bulan_edt,$sel_ser,$ex_ser);
								?>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
								<label>Tahun</label>
								<?php
									$tahun_edt = $this->formatter->getYear();
									$sels = array(date('Y'));
									$exs = array('class'=>'form-control select2', 'id'=>'data_tahun_edit', 'style'=>'width:100%;','required'=>'required');
									echo form_dropdown('tahun',$tahun_edt,$sels,$exs);
								?>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label>Besar Pinjaman</label>
							<input type="text" placeholder="Masukkan Nominal Tunjangan" id="data_nominal_edit" name="nominal" min="0" value="Rp. 0" step="0.01" class="form-control field input-money" required="required">
						</div>
						<div class="form-group">
							<label>Lama Angsuran</label>
							<input type="number" placeholder="Berapa Kali Angsuran" id="data_lama_angsuran_edit" name="lama_angsuran" min="0" value="0" class="form-control field input-number" required="required">
						</div>
						<div class="form-group">
							<label>Keterangan</label>
							<textarea name="keterangan" id="data_keterangan_edit" class="form-control field" placeholder="Keterangan"></textarea>
						</div>
						<div class="form-group">
							<span id="jenis_off" style="font-size: 20px;"><i class="far fa-square" aria-hidden="true"></i></span>
							<span id="jenis_on" style="display: none; font-size: 20px;"><i class="far fa-check-square" aria-hidden="true"></i></span>
							<span style="padding-bottom: 9px;vertical-align: middle;"><b> Jenis Angsuran</b>
							<small class="text-muted"><font color="red"> (Ceklist Jika Nominal Angsuran Berbeda setiap bulannya)</font></small></span>
							<input type="hidden" id="jenis_angsuran_edit" name="jenis">
							<input type="hidden" id="jenis_old_angsuran_edit" name="jenis_old">
						</div>
						<div id="pattern_edit"></div>
					</form>
				</div>
            </div>
         </div>
         <div class="modal-footer">
            <button type="button" onclick="do_edit()" id="btn_edit" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
         </div>
      </div>
   </div>
</div>
<div id="modal_cetak" class="modal fade" role="dialog">
	<div class="modal-dialog modal-md modal-default">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title text-center">Cetak Data Pinjaman</h4>
			</div>
			<form id="form_need">
				<div class="modal-body">
					<div class="callout callout-info text-left">
						<b><i class="fa fa-bullhorn"></i> Bantuan</b><br>
						<ul>
							<li>Pastikan data memilih periode yang tepat, periode berikut merupakan periode yang telah selesai diupload</li>
						</ul>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="col-md-6">
								<div class="form-group">
									<label>Bulan</label>
									<?php
										$bulan_ser = $this->formatter->getMonth();
										$sel_ser = array(date('m'));
										$ex_ser = array('class'=>'form-control select2', 'id'=>'bulan_cetak', 'style'=>'width:100%;','required'=>'required');
										echo form_dropdown('bulan',$bulan_ser,$sel_ser,$ex_ser);
									?>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label>Tahun</label>
									<?php
										$tahun_ser = $this->formatter->getYear();
										$sels = array(date('Y'));
										$exs = array('class'=>'form-control select2', 'id'=>'tahun_cetak', 'style'=>'width:100%;','required'=>'required');
										echo form_dropdown('tahun',$tahun_ser,$sels,$exs);
									?>
								</div>
							</div>
						</div>
					</div>
					<!-- <div class="form-group">
						<label>Periode Penggajian</label>
						<select class="form-control select2" name="periode" id="data_periode_cetak" style="width: 100%;">
							<?php
								// status gaji selesai
								// $wherex=['a.status_gaji'=>1,'a.status'=>1,'a.kode_master_penggajian'=>'BULANAN'];
								// $periode = $this->model_master->getPeriodePenggajian($wherex);
								// echo '<option></option>';
								// foreach ($periode as $p) {
								// 	echo '<option value="'.$p->kode_periode_penggajian.'">'.$p->nama.' ('.$p->nama_sistem_penggajian.')</option>';
								// }
							?>
						</select>
					</div> -->
				</div>
			</form>
			<div class="modal-footer">
				<button type="button" onclick="cetakPinjaman()" class="btn btn-danger"><i class="fa fa-print"></i> Cetak PDF</button>
			</div>
		</div>
	</div>
</div>
<div id="modalEndPinjaman" class="modal fade" role="dialog">
	<div class="modal-dialog modal-md modal-default">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title text-center">Ubah Status Pinjaman</h4>
			</div>
			<form id="form_status">
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<input type="hidden" name="id_pinjaman" id="id_pinjaman_stt">
								<label>Status</label>
								<select name="status" class="form-control select2" id="ubah_status_pinjaman" style="width:100%;">
									<option value="1">LUNAS</option>
									<option value="0">BELUM LUNAS</option>
								</select>
							</div>
							<div class="form-group">
								<label>CATATAN</label>
								<textarea name="keterangan" id="data_catatan" class="form-control field" placeholder="CATATAN"></textarea>
							</div>
						</div>
					</div>
				</div>
			</form>
			<div class="modal-footer">
				<button type="button" onclick="ubahStatusPinjaman()" class="btn btn-success"><i class="fa fa-floppy-o"></i> SIMPAN</button>
			</div>
		</div>
	</div>
</div>
<!-- delete -->
<div id="modal_delete_partial"></div>
<script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
<script type="text/javascript">
	var url_select="<?php echo base_url('global_control/select2_global');?>";
	var table="data_pinjaman";
	var column="id_pinjaman";
	$(document).ready(function(){
		refresh_tabel('all');
		$('#denda_off').click(function(){
			var kode = 'add';
			$('#denda_off').hide();
			$('#denda_on').show();
			$('#angsuranPerbulan').show();
			$('input[name="denda"]').val('1');
			var j_a = $('#data_lama_angsuran_add').val();
			$('#angsuran_perbulan_add').blur(function(){
				var valperbulan = $('#angsuran_perbulan_add').val();
				isiKolom(j_a,kode,valperbulan); samakanKolom(j_a,kode); hitungbobot(kode); hitungTotal(kode);
			});
			draw_shift(j_a,'add','show');
			$('.nominal_'+kode+'').blur(function(){ samakanKolom(j_a,kode); hitungbobot(kode); hitungTotal(kode); });
		})
		$('#denda_on').click(function(){
			$('#denda_off').show();
			$('#denda_on').hide();
			$('#angsuranPerbulan').hide();
			$('input[name="denda"]').val('0');
			var j_a = $('#data_lama_angsuran_add').val();
			draw_shift(j_a,'add','hide');
			$('#btn_add').removeAttr('disabled','disabled'); 
		});
		$('#data_lama_angsuran_add').bind('keyup focusout', function () {
			draw_shift(this.value,'add');
		});
		$('#btn_add_collapse').click(function(){
			var key = $('#key_btn_tambah').val();
			if(key == 0){
				$('#form_add')[0].reset();
				refreshCode();
				$('#key_btn_tambah').val('1');
			}else { $('#key_btn_tambah').val('0'); }
		});
		getSelect2("<?php echo base_url('master/master_pinjaman/employee')?>",'data_karyawan_add');
		$('#data_karyawan_add').val([]).trigger('change');
		$('#btn_tambah').click(function(){
			refreshCode();
		});
		$('#kar_off').click(function(){
			$('#kar_off').hide();
			$('#kar_on').show();
			$('#lev_off').show();
			$('#lev_on').hide();
			$('input[name="all_kar"]').val('1');
			$('input[name="all_lev"]').val('0');
			$('#karyawan_sync').show();
			$('#level_sync').hide();
		})
		$('#kar_on').click(function(){
			$('#kar_off').show();
			$('#kar_on').hide();
			$('#lev_off').hide();
			$('#lev_on').show();
			$('input[name="all_kar"]').val('0');
			$('input[name="all_lev"]').val('1');
			$('#karyawan_sync').hide();
			$('#level_sync').show();
		})
		$('#lev_off').click(function(){
			$('#lev_off').hide();
			$('#lev_on').show();
			$('#kar_off').show();
			$('#kar_on').hide();
			$('input[name="all_kar"]').val('0');
			$('input[name="all_lev"]').val('1');
			$('#karyawan_sync').hide();
			$('#level_sync').show();
		})
		$('#lev_on').click(function(){
			$('#lev_off').show();
			$('#lev_on').hide();
			$('#kar_off').hide();
			$('#kar_on').show();
			$('input[name="all_kar"]').val('1');
			$('input[name="all_lev"]').val('0');
			$('#karyawan_sync').show();
			$('#level_sync').hide();
		})
	});
	function refreshCode() {
		kode_generator("<?php echo base_url('master/master_pinjaman/kode');?>",'data_kode_add');
	}
	function refresh_tabel(kode,usage) {
			var bulan = $('#bulan_cari').val();
			var tahun = $('#tahun_cari').val();
		$('#table_data').DataTable( {
			ajax: {
				url: "<?php echo base_url('cpayroll/master_pinjaman/view_all/')?>",
				type: 'POST',
				data:{form:$('#form_filter').serialize(),access:"<?php echo base64_encode(serialize($access));?>",kode:kode,bulan:bulan,tahun:tahun}
			},
			scrollX: true,
			bDestroy: true,
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
			{   targets: 8,
				width: '5%',
				render: function ( data, type, full, meta ) {
				return data;
				}
			},
			{   targets: 10, 
				width: '10%',
				render: function ( data, type, full, meta ) {
				return '<center>'+data+'</center>';
				}
			},
			]
		});
	}
	function refreshCode() {
		kode_generator("<?php echo base_url('cpayroll/master_pinjaman/kode');?>",'data_kode_add');
	}
	function view_modal(id) {
		if($('#key_btn_tambah').val() == 1){
			$('#btn_add_collapse').click();
		}
		$('#view_show').hide();
		$('#view_loading').show();
		$('#view').modal('show');
		var data={id_pinjaman:id, mode: 'view'};
		var callback=getAjaxData("<?php echo base_url('cpayroll/master_pinjaman/view_one')?>",data); 
		$('.header_data').html(callback['nama']);
		$('#data_kode_view').html(callback['kode']);
		$('#data_name_view').html(callback['nama']);
		$('#data_nominal_view').html(callback['nominal']);
		$('#data_angsuran_ke_view').html(callback['angsuran_ke']);
		$('#data_keterangan_view').html(callback['keterangan']);
		$('#data_karyawan_view').html(callback['karyawan']);
		$('#data_jabatan_view').html(callback['jabatan']);
		$('#data_bagian_view').html(callback['bagian']);
		$('#data_loker_view').html(callback['loker']);
		$('#data_tanggal_view').html(callback['tanggal']);
		$('#data_lama_angsuran_view').html(callback['lama_angsuran']);
		$('#data_sudah_diangsur_view').html(callback['sudah_diangsur']);
		$('#data_sisa_pinjaman_view').html(callback['sisa_pinjaman']);
		$('#data_table_view_plan').html(callback['table']);
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
		$('#view_show').show();
		$('#view_loading').hide();
	}
	function edit_modal() {
		get_selet_emp();
		$('#edit_show').hide();
		$('#edit_loading').show();
		$('#view').modal('hide');
		setTimeout(function () {
			$('#edit').modal('show');
		},600); 
		var id = $('input[name="data_id_view"]').val();
		var data={id_pinjaman:id, mode: 'edit'};
		var callback=getAjaxData("<?php echo base_url('cpayroll/master_pinjaman/view_one')?>",data); 
		$('.header_data').html(callback['nama']);
		$('#data_id_edit').val(callback['id']);
		$('#data_kode_edit_old').val(callback['kode']);
		$('#data_kode_edit').val(callback['kode']);
		$('#data_name_edit').val(callback['nama']);
		$('#data_nominal_edit').val(callback['nominal']);
		$('#data_lama_angsuran_edit').val(callback['lama_angsuran']);
		$('#data_tanggal_edit').val(callback['e_tanggal']);
		$('#data_bulan_edit').val(callback['e_bulan']);
		$('#data_tahun_edit').val(callback['e_tahun']);
		$('#data_keterangan_edit').val(callback['keterangan']);
		$('#jenis_old_angsuran_edit').val(callback['jenis']);
		$('#data_karyawan_edit').val(callback['karyawan']).trigger('change');
		$('#edit_show').show();
		$('#edit_loading').hide();
		var kode = 'edit';
		var jenis = callback['jenis'];
		var jum_angsuran = callback['jum_angsuran'];
		// if(jenis==1){
			// $('#jenis_off').hide();
			// $('#jenis_on').show();
			var j_a = $('#data_lama_angsuran_edit').val();
			draw_shift(j_a,'edit','show'); 
			$.each( callback['pattern'], function( key, value ) {
				$('#pattern_edit'+key).val(value);
				$('input[name="pattern_'+kode+key+'"').val(value);
			});
			for (i = 1; i <= jum_angsuran; i++) {
				$('#pattern_edit'+i).prop('readonly', true);
			}
			// $('.nominal_'+kode+'').keyup(function(){ samakanKolom(j_a,kode); hitungbobot(kode); hitungTotal(kode); });
			$('#data_lama_angsuran_edit').blur(function(){ draw_shift(j_a,'edit','hide');  });
			$('.nominal_'+kode+'').blur(function(){ samakanKolom(j_a,kode); hitungbobot(kode); hitungTotal(kode); });
		// }else{
		//    $('#jenis_off').show();
		//    $('#jenis_on').hide();
		//    $('input[name="jenis"]').val('0');
		//    var j_a = $('#data_lama_angsuran_edit').val();
		//    draw_shift(j_a,'edit','hide');
		//    $('#btn_edit').removeAttr('disabled','disabled'); 
		// }
		$('#jenis_off').click(function(){
			var kode = 'edit';
			$('#jenis_off').hide();
			$('#jenis_on').show();
			$('input[name="jenis"]').val('1');
			var j_a = $('#data_lama_angsuran_edit').val();
			draw_shift(j_a,'edit','show');
			$.each( callback['pattern'], function( key, value ) {
				$('#pattern_edit'+key).val(value);
				$('input[name="pattern_'+kode+key+'"').val(value);
			});
			for (i = 1; i <= jum_angsuran; i++) {
				$('#pattern_edit'+i).prop('readonly', true);
			}
			// $('.nominal_'+kode+'').keyup(function(){ samakanKolom(j_a,kode); hitungbobot(kode); hitungTotal(kode); });
			$('.nominal_'+kode+'').blur(function(){ samakanKolom(j_a,kode); hitungbobot(kode); hitungTotal(kode); });
		})
		$('#jenis_on').click(function(){
			$('#jenis_off').show();
			$('#jenis_on').hide();
			$('input[name="jenis"]').val('0');
			var j_a = $('#data_lama_angsuran_edit').val();
			draw_shift(j_a,'edit','hide');
			$('#btn_edit').removeAttr('disabled','disabled'); 
		});
	}
	function delete_modal(id) {
		var data={id_pinjaman:id, mode: 'view'};
		var callback=getAjaxData("<?php echo base_url('cpayroll/master_pinjaman/view_one')?>",data);
		var datax={table:table,column:column,id:id,nama:callback['nama']};
		loadModalAjax("<?php echo base_url('pages/load_modal_delete')?>",'modal_delete_partial',datax,'delete');
	}
	function do_status(id,data) {
		var data_table={status:data};
		var where={id_pinjaman:id};
		var datax={table:table,where:where,data:data_table};
		submitAjax("<?php echo base_url('global_control/change_status')?>",null,datax,null,null,'status');
		$('#table_data').DataTable().ajax.reload(null,false);
	}
	function do_edit(){
		if($("#form_edit")[0].checkValidity()) {
			submitAjax("<?php echo base_url('cpayroll/edt_pinjaman')?>",'edit','form_edit',null,null);
			$('#table_data').DataTable().ajax.reload(null,false);
		}else{
			notValidParamx();
		} 
	}
	function do_add(){
		if($("#form_add")[0].checkValidity()) {
			submitAjax("<?php echo base_url('cpayroll/add_pinjaman')?>",null,'form_add',"<?php echo base_url('employee/master_pinjaman/kode');?>",'data_kode_add');
			$('#table_data').DataTable().ajax.reload(function (){Pace.restart();},false);
			$('#form_add')[0].reset();
			$('#data_karyawan_add').val('').trigger('change');
			refreshCode();
		}else{
			notValidParamx();
		} 
	}
	function draw_shift(max,usage,val) {
		var pattern='';
		if(val == 'show'){
			for (i = 1; i <= max; i++) {
				pattern+='<div class="form-group">'+
					'<label>Besar Nominal Angsuran Bulan Ke - '+i+' (Rp)</label>'+
					'<input type="number" placeholder="Masukkan Nominal Bulan Ke - '+i+' (Hanya Tipe Angka)" id="pattern_'+usage+i+'" name="pattern['+i+']" min="0" class="form-control input-money field nominal_'+usage+'" required="required">'+
					'<input type="hidden" name="pattern_'+usage+i+'" id="pattern_'+usage+'[]" class="form-control">'+
				'</div>';
			}
			pattern+='<div class="form-group">'+
				'<label>TOTAL (Rp)</label>'+
				'<input type="text" id="data_hasilangsuran_'+usage+'" name="total" class="form-control input-money" readonly="readonly">'+
				'</div>';
			pattern+='<span id="div_span_tgl_'+usage+'"></span>';
		}else{
			pattern='';
		}
		$('#pattern_'+usage).html(pattern);
	}
	function hitungbobot(kode) {
		var users = $('input[id="pattern_'+kode+'[]"]').map(function(){ 
			return this.value; 
		}).get();
		getAjaxCount(null,users,"data_hasilangsuran_"+kode+"");
	}
	function hitungTotal(kode) {
		var tot = $('#data_hasilangsuran_'+kode+'').val();
		var ang = $('#data_nominal_'+kode+'').val();
		var nominal=getAjaxData("<?php echo base_url('cpayroll/master_pinjaman/getJustNominal')?>",{nominal:ang});
		// alert(tot);
		// alert(nominal);
		if(nominal == tot){
			$('#div_span_tgl_'+kode+'').html('Besar Pinjaman dan Total Angsuran Sudah Sama').css('color','green');
			$('#btn_'+kode+'').removeAttr('disabled','disabled'); 
		}else{
			$('#div_span_tgl_'+kode+'').html('Besar Pinjaman dan Total Angsuran Tidak Sama').css('color','red');
			$('#btn_'+kode+'').attr('disabled','disabled');
		}
	}
	function samakanKolom(max,kode){
		for (i = 1; i <= max; i++) {
			$('input[name="pattern_'+kode+i+'"]').val($('#pattern_'+kode+i+'').val());
		}
	}
	function isiKolom(max,kode,val){
		for (i = 1; i <= max; i++) {
			$('input[name="pattern['+i+']"]').val(val);
		}
	}
	function do_rekap(file) {
			var data=$('#form_filter').serialize();
		window.location.href = "<?php echo base_url('rekap/export_data_pinjaman')?>?"+data;
	}
	function get_selet_emp(kode) {
		var data={kode_lokasi:kode};
		var callback=getAjaxData("<?php echo base_url('cpayroll/master_pinjaman/view_select')?>",data);
		$('#data_karyawan_add').html(callback);
		$('#data_karyawan_edit').html(callback);
	}
	function cetakPinjaman() {
		var periode = 'c';//$('#data_periode_cetak').val();
		var bulan = $('#bulan_cetak').val();
		var tahun = $('#tahun_cetak').val();
		if (periode == '') {
			notValidParamxCustom('Harap Pilih Periode !');
		}else if(bulan == ''){
			notValidParamxCustom('Harap Pilih Bulan !');
		}else if(tahun == ''){
			notValidParamxCustom('Harap Pilih Tahun !');
		}else{
			$.redirect("<?php echo base_url('pages/cetak_data_pinjman'); ?>",  { periode: periode,bulan: bulan,tahun: tahun }, "POST", "_blank");
		}
	}
	function modalEndPinjaman(id) {
		$('#modalEndPinjaman').modal('show');
		var data={id_pinjaman:id, mode: 'view'};
		var callback=getAjaxData("<?php echo base_url('cpayroll/master_pinjaman/view_one')?>",data); 
		$('.header_data').html(callback['nama']);
		$('#id_pinjaman_stt').val(id);
		$('#data_catatan').val(callback['catatan']);
		$('#ubah_status_pinjaman').val(callback['status_pinjaman']).trigger('change');
	}
	function ubahStatusPinjaman(){
		if($("#form_status")[0].checkValidity()) {
			submitAjax("<?php echo base_url('cpayroll/ubah_status_pinjaman')?>",'modalEndPinjaman','form_status',null,null);
			$('#table_data').DataTable().ajax.reload(null,false);
		}else{
			notValidParamx();
		} 
	}
</script>