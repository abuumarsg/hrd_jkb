<div class="content-wrapper">
	<section class="content-header">
      <h1>
        <i class="fas fa-calendar-times"></i> Data
        <small>Izin / Cuti</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fa fas fa-tachometer-alt"></i> Dashboard</a></li>
        <li class="active"><i class="fas fa-calendar-times"></i> Izin / Cuti</li>
      </ol>
    </section>
    <section class="content">
    	<div class="row">
			<div class="col-md-12">
				<div class="box box-primary">
					<div class="box-header with-border">
						<h3 class="box-title"><i class="fa fa-search"></i> Advance Filter Data</h3>
						<div class="box-tools pull-right">
							<button class="btn btn-box-tool" onclick="refreshData()" data-toggle="tooltip" title="Refresh Data"><i class="fas fa-sync"></i></button>
							<button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
							<button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i class="fa fa-times"></i></button>
						</div>
					</div>
					<div style="padding-top: 10px;">
						<form id="form_filter">
							<input type="hidden" name="param" value="all">
							<input type="hidden" name="mode" value="data">
							<div class="box-body">
								<div class="col-md-6">
									<div class="form-group">
										<label>Pilih Bagian</label>
										<select class="form-control select2" id="bagian_export" name="bagian_export" style="width: 100%;"></select>
									</div>
									<div class="form-group">
										<label>Pilih Lokasi Kerja</label>
										<select class="form-control select2" id="unit_export" name="unit_export" style="width: 100%;"></select>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label>Bulan</label>
										<select class="form-control select2" id="bulan_export" name="bulan_export" style="width: 100%;">
											<option></option>
											<?php
											for ($i=1; $i <= 12; $i++) { 
												echo '<option value="'.$this->formatter->zeroPadding($i).'">'.$this->formatter->getNameOfMonth($i).'</option>';
											}
											?>
										</select>
									</div>
									<div class="form-group">
										<label>Tahun</label>
										<select class="form-control select2" id="tahun_export" name="tahun_export" style="width: 100%;">
											<option></option>
											<?php
											$year = $this->formatter->getYear();
											foreach ($year as $yk => $yv) {
												echo '<option value="'.$yk.'">'.$yv.'</option>';
											}
											?>
										</select>
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
			    </div>
			</div>
		</div>
		<div class="row">
    		<div class="col-md-12">
    			<div class="box box-info">
    				<div class="box-header with-border">
    					<h3 class="box-title"><i class="fas fa-calendar-times"></i> Data Izin / Cuti</h3>
    					<div class="box-tools pull-right">
    						<button class="btn btn-box-tool" onclick="reload_table('table_data')" data-toggle="tooltip" title="Refresh Table"><i class="fas fa-sync"></i></button>
    						<button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
    						<button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i class="fa fa-times"></i></button>
    					</div>
    				</div>
    				<div class="box-body">
					    <div id="accordion">
							<div class="panel">
								<?php if (in_array($access['l_ac']['add'], $access['access'])) {
									echo '<button href="#tambah_cuti" data-toggle="collapse" id="btn_tambah_cuti" data-parent="#accordion" class="btn btn-success" style="float: left;"><i class="fa fa-plus"></i> Tambah Izin / Cuti</button> ';}
								if (in_array($access['l_ac']['rst_cuti'], $access['access'])) {
									echo '<button id="btn_reset" class="btn btn-danger" style="margin-left: 5px;float: left;"><i class="fas fa-sync"></i> Setting Sisa Cuti</button>';}
								echo '<button id="btn_view_sisa" onclick="view_sisa_cuti()" class="btn btn-info" style="margin-left: 5px;float: left;"><i class="fas fa-eye"></i> Lihat Sisa Cuti</button>';
								// if (in_array($access['l_ac']['rkp'], $access['access'])) {
								// 	echo '<button type="button" onclick="rekap()" class="btn btn-warning" ><i class="fa fa-file-excel-o"></i> Export Data</button>';}
								if (in_array($access['l_ac']['rkp'], $access['access'])) {
									echo '<div class="dropdown" style="float: left;margin-left: 5px;">
										<button class="btn btn-warning dropdown-toggle" type="button" data-toggle="dropdown"><i class="fa fa-print fa-fw"></i> Cetak Data <span class="caret"></span></button>
										<ul class="dropdown-menu">
											<li><a onclick="rekap()"><i class="fa fa-file-excel-o"></i> Export Data</a></li>
											<li><a onclick="cetak_kartu()"><i class="fa fa-print fa-fw"></i> Cetak Kartu Monitor Absensi</a></li>
										</ul>
									</div>';
								}
								?>
								<?php if (in_array($access['l_ac']['add'], $access['access'])) { ?>
									<div id="tambah_cuti" class="collapse">
									<br>
										<div class="box box-success">
											<div class="box-header with-border">
												<h3 class="box-title"><i class="fa fa-plus"></i> Tambah Izin/Cuti</h3>
											</div>
											<form id="form_add_cuti" class="form-horizontal">
											<div class="box-body">
												<div class="callout callout-info"><label><i class="fa fa-info-circle"></i> Bantuan</label><br> Untuk meminimalisir Nomor Surat Sama, Silahkan klik <b><i class="fa fa-refresh"></i></b> disamping kolom nomor surat sebelum menyimpan</div>
												<div class="col-md-6">
													<div class="row">
									                    <div class="form-group">
														 <input type="hidden" id="id_karyawan_cuti" name="id_karyawan_cuti" value="">
									                      	<label class="col-sm-3 control-label">Nomor Izin/Cuti</label>
										                    <div class="col-sm-8">
										                        <input type="text" name="no_cuti" id="no_cuti_add" class="form-control" placeholder="Nomor Cuti">
										                    </div>
															<div class="col-sm-1">
																<span class="control-label" style="vertical-align: middle;"><i class="fa fa-refresh" style="font-size: 16px;cursor:pointer;" onclick="refreshCode()"></i></span>
															</div>
									                    </div>
									                    <div class="form-group">
									                      	<label class="col-sm-3 control-label">NIK</label>
										                    <div class="col-sm-7">
										                        <input type="text" name="nik_cuti" id="nik_cuti" class="form-control" placeholder="Nomor Induk Karyawan" required="required" readonly="readonly">
										                    </div>
									                        <div class="col-sm-1">
									                           	<button type="button" class="btn btn-default btn-sm" onclick="pilih_karyawan_cuti()">
						    									<i class ="fa fa-plus"></i></button>
									                        </div>
									                    </div>
									                    <div class="form-group">
									                      	<label class="col-sm-3 control-label">Nama Karyawan</label>
										                    <div class="col-sm-9">
										                        <input type="text" name="nama_cuti" id="nama_cuti" class="form-control" placeholder="Nama Karyawan" readonly="readonly">
										                    </div>
									                    </div>
														<div class="form-group">
									                      	<label class="col-sm-3 control-label">Jabatan</label>
									                        	<input type="hidden" name="jabatan_cuti" id="jabatan_cuti">
									                      	<div class="col-sm-9">
									                        	<input type="text" name="nama_jabatan_cuti" id="nama_jabatan_cuti" class="form-control" placeholder="Jabatan Asal Karyawan" readonly="readonly">
									                      	</div>
									                    </div>
														<div class="form-group">
									                      	<label class="col-sm-3 control-label">Lokasi</label>
									                        	<input type="hidden" name="lokasi_asal_cuti" id="kode_lokasi_cuti">
									                      	<div class="col-sm-9">
									                        	<input type="text" name="nama_lokasi_cuti" id="nama_lokasi_cuti" class="form-control" placeholder="Lokasi Asal Karyawan" readonly="readonly">
									                      	</div>
									                    </div>
									                    <div class="form-group">
									                    	<label class="col-sm-3 control-label">Tanggal Mulai - Selesai</label>
									                    	<div class="col-sm-9">
													            <div class="has-feedback">
													              <span class="fa fa-calendar form-control-feedback"></span>
									                    			<input type="text" name="tanggal" id="tanggal_izin_add" class="form-control pull-right date-range" placeholder="Tanggal Cuti" required="required" readonly="readonly">
									                    		</div>
													            <span id="div_span_tgl"></span>
									                    	</div>
									                    </div>
								                        <div class="form-group">
								                            <label class="col-sm-3 control-label">Jenis Izin/Cuti</label>
									                      	<div class="col-sm-9">
									                      		<select class="form-control select2" name="jenis_cuti" required="required" id="data_jenis_cuti_add" style="width: 100%;"></select>
								                            </div>
								                        </div>
									                </div>
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label class="col-sm-3 control-label">SKD Dibayar</label>
														<div class="col-sm-9">
															<a id="skd_no" style="font-size: 16pt;"><i class="far fa-square"></i></a>
															<a id="skd_yes" style="font-size: 16pt;display: none;"><i class="fa fa-check-square"></i></a>
															<input type="hidden" name="skd" id="skd_add" class="form-control">
														</div>
													</div>
													<div class="form-group">
														<label class="col-sm-3 control-label">Mengetahui</label>
														<div class="col-sm-9">
															<select class="form-control select2" name="mengetahui" required="required" id="data_mengetahui_add"  style="width: 100%;"></select>
														</div>
													</div>
													<div class="form-group">
														<label class="col-sm-3 control-label">Menyetujui</label>
														<div class="col-sm-9">
															<select class="form-control select2" name="menyetujui" required="required" id="data_menyetujui_add"  style="width: 100%;"></select>
														</div>
													</div>
													<div class="form-group">
														<label class="col-sm-3 control-label">Menyetujui 2</label>
														<div class="col-sm-9">
															<select class="form-control select2" name="menyetujui2" required="required" id="data_menyetujui2_add"  style="width: 100%;"></select>
														</div>
													</div>
													<div class="form-group">
								                      	<label class="col-sm-3 control-label">Alasan Izin/Cuti</label>
								                      	<div class="col-sm-9">
								                        	<textarea name="alasan_cuti" class="form-control" required="required" placeholder="Alasan Izin/Cuti"></textarea>
								                      	</div>
								                    </div>
													<div class="form-group">
								                      	<label class="col-sm-3 control-label">Keterangan</label>
								                      	<div class="col-sm-9">
								                        	<textarea name="keterangan_cuti" class="form-control" placeholder="Keterangan"></textarea>
															<span id="div_span_tgl_izin"></span><br>
															<span id="div_span_sisa_cuti"></span>
								                      	</div>
								                    </div>
												</div>
											</div>
											<div class="box-footer">
						         				<div class="pull-right">
						            				<button type="submit" id="btn_save" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
						          				</div>
						        			</div>
										</form>
										</div>
									</div>
								<?php } ?>
							</div>
						</div><br>
                  		<div class="row">
                  			<div class="col-md-12">
                  				<div class="callout callout-info"><label><i class="fa fa-info-circle"></i> Bantuan</label><br>Pilih <b>NIK</b> (Nomor Induk Karyawan) untuk melihat detail izin, memvalidasi, maupun melakukan update pada data izin karyawan</div>
                  				<table id="table_data" class="table table-bordered table-striped" width="100%">
                  					<thead>
                  						<tr>
                  							<th>No</th>
                  							<th>NIK</th>
                  							<th>Nama</th>
                  							<th>Jabatan</th>
                  							<th>Bagian</th>
                  							<th>Lokasi Kerja</th>
                  							<th>Tanggal Pengajuan</th>
                  							<th>Jumlah Izin/Cuti</th>
                  							<th>Validasi</th>
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
<div class="modal modal-default fade" id="modal_pilih_karyawan_cuti" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal">&times;</button>
		        <h2 class="modal-title">Pilih Karyawan</h2>
		    </div>
			<div class="modal-body">
					<table id="table_pilih_cuti" class="table table-bordered table-striped table-responsive" width="100%">
						<thead>
							<tr>
								<th width="7%">NO</th>
								<th width="25%">NIK</th>
								<th width="25%">Nama Karyawan</th>
								<th width="25%">Jabatan</th>
								<th>Lokasi</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default pull-right" data-dismiss="modal">Kembali</button>
			</div>
		</div>
	</div>
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
          <div class="col-md-2">
          	<img class="profile-user-img img-responsive img-circle view_photo" id="data_foto_view" data-source-photo="" src="" alt="User profile picture" style="width: 100%;">
          </div>
          <div class="col-md-5">
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
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Bagian</label>
              <div class="col-md-6" id="data_bagian_view"></div>
            </div>
          </div>
          <div class="col-md-5">
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
        <hr>
        	<h4 style="text-align: center;"><b>Daftar Izin Cuti</b></h4>
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
<?php if (in_array($access['l_ac']['rst_cuti'], $access['access'])) { ?>
<div id="reset" class="modal fade" role="dialog">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h2 class="modal-title">Setting Sisa Cuti</h2>
			</div>
			<form id="form_reset" class="form-horizontal">
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12">
							<p>Pilih karyawan dan masukkan jumlah cuti karyawan untuk mengatur jumlah cuti karyawan ataupun untuk mereset sisa cuti.</p>
							<div class="form-group clearfix">
								<div class="col-md-12">
									<label>Karyawan</label>
										<span style="padding-bottom: 9px;vertical-align: middle;" class="pull-right"><b>&nbsp;&nbsp;&nbsp;Semua Karyawan</b></span>
										<span id="kar_off" style="font-size: 20px;" onclick="karKlik();"><i class="far fa-square pull-right" aria-hidden="true"></i></span>
										<span id="kar_on" style="display: none; font-size: 20px;" onclick="karKlik();"><i class="far fa-check-square pull-right" aria-hidden="true"></i></span>
										<input type="hidden" name="kar_sisa">
										<select class="form-control select2" name="karyawan_reset[]" id="data_karyawan_reset" multiple="multiple" required="required" style="width: 100%;max-height: 40%;"></select>
								</div>
							</div>
							<div class="form-group clearfix">
								<div class="col-md-12">
									<label>Jumlah Cuti</label>
										<input type="number" placeholder="Masukkan Jumlah Cuti" name="jumlah_cuti" id="jumlah_cuti" class="form-control" required="required">
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" id="btn_reset_save" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
				</div>
			</form>
		</div>
	</div>
</div>
<?php } ?>
<div id="cetak_kartu" class="modal fade" role="dialog">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h2 class="modal-title">Cetak Kartu Monitor Absensi Karyawan</h2>
			</div>
			<form id="form_kartu" class="form-horizontal">
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12">
							<div class="form-group clearfix">
								<div class="col-md-12">
									<label>Karyawan</label>
									<span style="padding-bottom: 9px;vertical-align: middle;" class="pull-right"><b>&nbsp;&nbsp;&nbsp;Semua Karyawan</b></span>
									<span id="kartu_off" style="font-size: 20px;" onclick="kartuKlik();"><i class="far fa-square pull-right" aria-hidden="true"></i></span>
									<span id="kartu_on" style="display: none; font-size: 20px;" onclick="kartuKlik();"><i class="far fa-check-square pull-right" aria-hidden="true"></i></span>
									<input type="hidden" name="kartu_sisa">
									<select class="form-control select2" name="karyawan_kartu[]" id="data_karyawan_kartu" multiple="multiple" required="required" style="width: 100%;max-height: 40%;"></select>
								</div>
							</div>
							<div class="form-group clearfix">
								<div class="col-md-12">
									<label>Tahun</label>
									<select class="form-control select2" id="tahun_monitor" name="tahun_monitor" style="width: 100%;">
										<option></option>
										<?php
										$year = $this->formatter->getYear();
										foreach ($year as $yk => $yv) {
											echo '<option value="'.$yk.'">'.$yv.'</option>';
										}?>
									</select>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" onclick="do_kartu()" class="btn btn-success"><i class="fa fa-print fa-fw"></i> Cetak</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
				</div>
			</form>
		</div>
	</div>
</div>
<div id="view_sisa_cuti" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h2 class="modal-title">Sisa Cuti</h2>
			</div>
			<form id="form_reset" class="form-horizontal">
				<div class="modal-body">
					<div class="row">
						<div class="col-md-12">
							<div id="lihat_sisa_cuti"></div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
				</div>
			</form>
		</div>
	</div>
</div>
<div id="modal_delete_partial"></div>
<script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
<script type="text/javascript">
  	var url_select="<?php echo base_url('global_control/select2_global');?>";
	var table="data_izin_cuti_karyawan";
	var column="id_izin_cuti";
	$(document).ready(function(){
		refreshCode();
		refreshData();
		tableData('all');
		resetselectAdd()
		submitForm('form_add_cuti');
		submitForm('form_reset');
		$('#skd_no').click(function(){
			$('#skd_no').hide();
			$('#skd_yes').show();
			$('#skd_add').val('1');
		})
		$('#skd_yes').click(function(){
			$('#skd_yes').hide();
			$('#skd_no').show();
			$('#skd_add').val('0');
		})
		getSelect2("<?php echo base_url('master/master_bagian/get_select2')?>",'bagian_export');
		getSelect2("<?php echo base_url('presensi/izin_cuti/izincuti')?>",'data_jenis_cuti_add');
		getSelect2("<?php echo base_url('employee/emp_part_jabatan_grade/grade')?>",'data_grade_baru_add');
		getSelect2("<?php echo base_url('employee/mutasi_jabatan/employee')?>",'data_mengetahui_add,#data_menyetujui_add,#data_menyetujui2_add');
		getSelect2("<?php echo base_url('presensi/data_presensi/employee')?>",'data_karyawan_reset,#data_karyawan_kartu');
		$('#btn_reset').click(function() {
			$('#reset').modal('show');
		})
		$('#data_jenis_cuti_add, #tanggal_izin_add, #id_karyawan_cuti').change(function(){
			var jc = $('#data_jenis_cuti_add').val();
			var idk = $('#id_karyawan_cuti').val();
			var tgl = $('#tanggal_izin_add').val();
			var datax = {jenis: jc,tanggal: tgl,id_kar: idk};
			var tgl_ini=getAjaxData("<?php echo base_url('presensi/cekTanggalIzin')?>",datax);
			if (tgl_ini['cek'] > 0) {
				$('#div_span_tgl_izin').html(tgl_ini['msg']).css('color','red');
				$('#btn_save').prop('disabled', true);
			}else{
				$('#div_span_tgl_izin').html(tgl_ini['msg']).css('color','green');
				$('#btn_save').prop('disabled', false); 
			};
			var sisacuti=getAjaxData("<?php echo base_url('presensi/cekSisaCuti')?>",datax);
			var tgl_izin=getAjaxData("<?php echo base_url('presensi/tanggalIzin')?>",datax);
			if (tgl_izin['jenis'] == 'C') {
				if (tgl_izin['hari'] > tgl_izin['maksimal']) {
					$('#div_span_tgl').html(tgl_izin['msg']).css('color','red');
					$('#btn_save').prop('disabled', true);
				}else{
					$('#div_span_tgl').html(tgl_izin['msg']).css('color','green');
					$('#btn_save').prop('disabled', false);
				}
			}else{
				if (tgl_izin['maksimal'] < tgl_izin['hari']) {
					$('#div_span_tgl').html(tgl_izin['msg']).css('color','red');
					$('#btn_save').prop('disabled', true);
				}else{
					$('#div_span_tgl').html(tgl_izin['msg']).css('color','green');
					$('#btn_save').prop('disabled', false); 
				}
				$('#div_span_sisa_cuti').html(sisacuti['msg']).css('color','green');
				$('#btn_save').prop('disabled', false); 
			};
			if (tgl_izin['jenis'] == 'C' && sisacuti['potong_cuti'] == '1') {
				if (sisacuti['sisa_cuti'] >= sisacuti['hari']) {
					$('#div_span_sisa_cuti').html(sisacuti['msg']).css('color','green');
					$('#btn_save').prop('disabled', false); 
				}else{
					$('#div_span_sisa_cuti').html(sisacuti['msg']).css('color','red');
					$('#btn_save').prop('disabled', true);
				}
			}else if (tgl_izin['jenis'] == 'C' && sisacuti['potong_cuti'] != '1') {
					$('#div_span_sisa_cuti').html(sisacuti['msg']).css('color','green');
					$('#btn_save').prop('disabled', false); 
			}
		})
		$('#kar_off').click(function(){
			$('#kar_off').hide();
			$('#kar_on').show();
			$('input[name="kar_sisa"]').val('1');
		})
		$('#kar_on').click(function(){
			$('#kar_off').show();
			$('#kar_on').hide();
			$('input[name="kar_sisa"]').val('0');
		})
		$('#kartu_off').click(function(){
			$('#kartu_off').hide();
			$('#kartu_on').show();
			$('input[name="kartu_sisa"]').val('1');
		})
		$('#kartu_on').click(function(){
			$('#kartu_off').show();
			$('#kartu_on').hide();
			$('input[name="kartu_sisa"]').val('0');
		})
	});
	function karKlik(){		
		var name = $('input[name="kar_sisa"]').val();
		if(name == 1) {
			var data={val:name};
			var callback=getAjaxData("<?php echo base_url('presensi/izin_cuti/kar_reset_val_null')?>",data);
			$('#data_karyawan_reset').val(callback).trigger('change');
		}else {
			var data={val:name};
			var callback=getAjaxData("<?php echo base_url('presensi/izin_cuti/kar_reset')?>",data);
			var text = "";
			var i;
			var selectedValues = new Array();
			for (i = 0; i < callback.length; i++) {
				selectedValues[i] = callback[i];
			} 
			$('#data_karyawan_reset').val(selectedValues).trigger('change');
		}
	}
	function kartuKlik(){		
		var name = $('input[name="kartu_sisa"]').val();
		if(name == 1) {
			var data={val:name};
			var callback=getAjaxData("<?php echo base_url('presensi/izin_cuti/kar_reset_val_null')?>",data);
			$('#data_karyawan_kartu').val(callback).trigger('change');
		}else {
			var data={val:name};
			var callback=getAjaxData("<?php echo base_url('presensi/izin_cuti/kar_reset')?>",data);
			var text = "";
			var i;
			var selectedValues = new Array();
			for (i = 0; i < callback.length; i++) {
				selectedValues[i] = callback[i];
			} 
			$('#data_karyawan_kartu').val(selectedValues).trigger('change');
		}
	}
	function submitForm(form) {
		$('#'+form).validator().on('submit', function (e) {
			if (e.isDefaultPrevented()) {
				notValidParamx();
			} else {
				e.preventDefault(); 
				if(form=='form_add_cuti'){
					do_add()
				}else{
					do_reset()
				}
			}
		})
	}
	function tableData(kode) {
		$('input[name="param"').val(kode);
		$('#table_data').DataTable().destroy();
		if(kode=='all'){
			var datax = {param:'all',access:"<?php echo $this->codegenerator->encryptChar($access);?>"};
		}else{
			var bagian = $('#bagian_export').val();
			var unit = $('#unit_export').val();
			var bulan = $('#bulan_export').val();
			var tahun = $('#tahun_export').val();
			var datax = {param:'search',bagian:bagian,unit:unit,bulan:bulan,tahun:tahun,access:"<?php echo $this->codegenerator->encryptChar($access);?>"};
		}
		$('#table_data').DataTable( {
			ajax: {
				url: "<?php echo base_url('presensi/izin_cuti/view_all/')?>",
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
					return '<a href="<?php base_url()?>view_izin_cuti/'+full[10]+'">' +data+'</a>';
				}
			},
			{   targets: 9, 
				width: '7%',
				render: function ( data, type, full, meta ) {
					return '<center>'+data+'</center>';
				}
			},
			]
		});
	}
	function pilih_karyawan_cuti() {
		$('#modal_pilih_karyawan_cuti').modal('toggle');
		$('#modal_pilih_karyawan_cuti .header_data').html('Pilih Karyawan');
		$('#table_pilih_cuti').DataTable( {
			ajax: "<?php echo base_url('presensi/pilih_k_cuti')?>",
			scrollX: true,
			destroy: true,
			columnDefs: [
			{   targets: 0, 
				width: '10%',
				render: function ( data, type, full, meta ) {
					return '<center>'+(meta.row+1)+'.</center>';
				}
			},
			{   targets: 4,
				render: function ( data, type, full, meta ) {
					return data;
				}
			},
			]
		});
	}
	function refreshCode() {
		kode_generator("<?php echo base_url('presensi/izin_cuti/kode');?>",'no_cuti_add');
	}
	function refreshData() {
		getSelect2("<?php echo base_url('master/master_bagian/get_select2')?>",'bagian_export');
		select_data('unit_export',url_select,'master_loker','kode_loker','nama','placeholder');
		unsetoption('bagian_export',['BAG001','BAG002']);
	}
	function resetselectAdd() {
		$('#data_jenis_cuti_add').val('').trigger('change');
		$('#data_mengetahui_add').val('').trigger('change');
		$('#data_menyetujui_add').val('').trigger('change');
	}
	function resetselectAddModalReset() {
		$('#data_karyawan_reset').val('').trigger('change');
		$('#jumlah_cuti').val('');
	}
	function view_modal(id) {
		var data={id_izin_cuti:id};
		var callback=getAjaxData("<?php echo base_url('presensi/izin_cuti/view_one')?>",data);  
		$('#view').modal('show');
		$('.header_data').html(callback['nama']);
		$('#data_nik_view').html(callback['nik']);
		$('#data_nama_view').html(callback['nama']);
		$('#data_loker_view').html(callback['loker']);
		$('#data_jabatan_view').html(callback['jabatan']);
		$('#data_bagian_view').html(callback['bagian']);
		$('#data_foto_view').attr('src',callback['foto']);
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
		$('#data_tabel_view').html(callback['tabel_izin']);
	}
	function delete_modal(id) {
		var data={id_izin_cuti:id};
		var callback=getAjaxData("<?php echo base_url('presensi/izin_cuti/view_one')?>",data);
		var datax={table:table,column:'id_karyawan',id:callback['id_karyawan'],nama:callback['nama']};
		loadModalAjax("<?php echo base_url('pages/load_modal_delete')?>",'modal_delete_partial',datax,'delete');
	}
	function do_add(){
		if($("#form_add_cuti")[0].checkValidity()) {
			submitAjax("<?php echo base_url('presensi/add_izin_cuti')?>",null,'form_add_cuti',null,null);
			$('#table_data').DataTable().ajax.reload(function(){
				Pace.restart();
			});
			$('#form_add_cuti')[0].reset();
			refreshCode();
			resetselectAdd();
		}else{
			notValidParamx();
		} 
	}
	function do_reset(){
		if($("#form_reset")[0].checkValidity()) {
			submitAjax("<?php echo base_url('presensi/reset_izin_cuti')?>",'reset','form_reset',null,null);
			$('#table_data').DataTable().ajax.reload();resetselectAddModalReset();
		}else{
			notValidParamx();
		} 
	}
	function do_kartu(){
		var karyawan = $('#data_karyawan_kartu').val();
		var tahun = $('#tahun_monitor').val();
		if(karyawan != "" && tahun != "") {
			$.redirect("<?php echo base_url('pages/monitor_presensi'); ?>", 
			{
				data_filter: $('#form_kartu').serialize()
			},
			"POST", "_blank");
		}else{
			submitAjax("<?php echo base_url('cpayroll/cek_data_payroll_notif/kartu')?>",null,null,null,null,'status');
		} 
	}
	$(document).on('click','.pilih_cuti',function(){
		document.getElementById("id_karyawan_cuti").value = $(this).attr('data-id_karyawan_cuti');
		document.getElementById("nik_cuti").value = $(this).attr('data-nik_cuti');
		document.getElementById("nama_cuti").value = $(this).attr('data-nama_cuti');
		document.getElementById("jabatan_cuti").value = $(this).attr('data-jabatan_cuti');
		document.getElementById("nama_jabatan_cuti").value = $(this).attr('data-nama_jabatan_cuti');
		document.getElementById("kode_lokasi_cuti").value = $(this).attr('data-kode_lokasi_cuti');
		document.getElementById("nama_lokasi_cuti").value = $(this).attr('data-nama_lokasi_cuti');
		$('#modal_pilih_karyawan_cuti').modal('hide');
	});
	function rekap() {
		var data=$('#form_filter').serialize();
		window.location.href = "<?php echo base_url('rekap/export_izin_cuti')?>?"+data;
	}
	function cetak_kartu() {
		$('#cetak_kartu').modal('show');
	}
	function view_sisa_cuti() {
		$('#view_sisa_cuti').modal('show');
		var callback=getAjaxData("<?php echo base_url('presensi/izin_cuti/view_sisa_cuti')?>");
		$('#lihat_sisa_cuti').html(callback['tabel_sisa_cuti']);
	}
</script> 