<div class="content-wrapper">
	<section class="content-header">
		<h1>
			<i class="fa far fa-credit-card"></i> Penggajian
			<small>Log Data penggajian Lembur</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
			<li class="active">Log Data Penggajian Lembur</li>
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
                        		<input type="hidden" name="usage" value="log">
								<div class="col-md-1">
								</div>
								<div class="col-md-5">
									<div class="">
										<label>Pilih Periode</label>
										<select class="form-control select2" name="periode" id="data_periode_filter" style="width: 100%;" onchange="get_bagian_adv(this.value, 'adv_bagian')">
											<?php
											// $periode = $this->model_payroll->getDataLogPayrollLembur(['a.create_by'=>$id_admin,'a.kode_master_penggajian'=>'BULANAN'],0,'a.kode_periode');
											//$periode = $this->model_master->getListPeriodeLembur(['a.status_gaji'=>1]);
											echo '<option></option>';
											foreach ($periode as $p) {
												$wktime = strtoupper($this->formatter->getNameOfMonth(date("m",strtotime($p->tgl_selesai)))).' '.date("Y",strtotime($p->tgl_selesai));
												echo '<option value="'.$p->kode_periode_lembur.'">'.$p->nama.' ('.$p->nama_sistem_penggajian.') - '.$wktime.'</option>';
											}
											?>
										</select>
									</div>
								</div>
								<div class="col-md-5">
									<div class="">
										<label>Pilih Bagian</label>
										<select class="form-control select2" name="bagian" id="adv_bagian" style="width: 100%;"></select>
									</div>
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
						<h3 class="box-title"><i class="fa fa-list"></i> Daftar Log Data Penggajian Lembur</h3>
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
													<div class="dropdown">
														<button class="btn btn-warning dropdown-toggle" type="button" data-toggle="dropdown"><i class="fa fa-print fa-fw"></i> Cetak
															<span class="caret"></span>
														</button>
														<ul class="dropdown-menu">
															<li><a onclick="do_print_slip()">Slip Gaji</a></li>
															<li><a onclick="rekap_lembur()">Rekap Gaji Lembur PDF</a></li>
															<li><a onclick="rekap_lembur_excel()">Rekap Gaji Lembur Excel</a></li>
															<li><a onclick="rekap_req()">Export Data Lembur & Perhitungan</a></li>
															<li><a onclick="rekap_jabatan()">Rekapitulasi Per Jabatan</a></li>
															<li><a onclick="rekap_bagian()">Rekapitulasi Per Bagian</a></li>
															<li><a onclick="rekap_tanda_terima()">Cetak Tanda Terima</a></li>
															<li><a onclick="rekap_transfer()">Cetak Transfer Bank</a></li>
															<!-- <li><a onclick="rekap_gaji()">Rekap</a></li>
															<li><a onclick="rekapitulasi()">Rekapitulasi</a></li>
															<li><a onclick="do_rekap_bagian()">Rekapitulasi Gaji Lembur Bagian</a></li> -->
														</ul>
													</div> 
												</div>
												<div class="pull-right" style="font-size: 8pt;">
													<i class="fa fa-toggle-on stat scc"></i> Aktif<br>
													<i class="fa fa-toggle-off stat err"></i> Tidak Aktif
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<style type="text/css">
							table#table_data thead tr th, table#table_data tbody tr td, table.DTFC_Cloned thead tr th{
								white-space: pre;
							}
							table.DTFC_Cloned tbody{
								overflow: hidden;
							}
						</style>
						<div class="row">
							<div class="col-md-12">
								<table id="table_data" class="table table-bordered table-striped table-responsive" width="100%">
									<thead>
										<tr>
											<th>No.</th>
											<th>NIK Karyawan</th>
											<th>Nama Karyawan</th>
											<th>Jabatan</th>
											<th>Grade</th>
											<th>Tanggal Masuk</th>
											<th>Masa Kerja</th>
											<th>Nama Periode</th>
											<th>Sistem Penggajian</th>
											<th>Bulan</th>
											<th>Tahun</th>
											<th>Gaji Pokok</th>
											<th>Upah</th>
											<th>Jam Lembur Hari Biasa</th>
											<th>Nominal Lembur Hari Biasa</th>
											<th>Jam Lembur Hari Libur</th>
											<th>Nominal Lembur Hari Libur</th>
											<th>Jam Lembur Istirahat</th>
											<th>Nominal Lembur Istirahat</th>
											<th>Gaji Lembur</th>
											<th>Nomor Rekening</th>
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
							<label class="col-md-6 control-label">Jabatan</label>
							<div class="col-md-6" id="data_jabatan_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Grade</label>
							<div class="col-md-6" id="data_grade_view"></div>
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
							<label class="col-md-6 control-label">Gaji Pokok</label>
							<div class="col-md-6" id="data_gaji_pokok_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Sistem Penggajian</label>
							<div class="col-md-6" id="data_sistem_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Tanggal Masuk</label>
							<div class="col-md-6" id="data_tanggal_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Masa Kerja</label>
							<div class="col-md-6" id="data_masa_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Nomor Rekening</label>
							<div class="col-md-6" id="data_rekening_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Periode Lembur</label>
							<div class="col-md-6" id="data_periode_view"></div>
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
						<div class="form-group col-md-12">
							<div id="data_gaji_bersih_view"></div>
						</div>
						<div class="form-group col-md-12">
							<div id="data_pph_view"></div>
						</div>
					</div>
					<div class="col-md-12">
						<div style="text-align: center;"><h3>Detail Data</h3></div>
						<div style="overflow-y: auto;height: 300px;">
							<div class="col-md-12">
								<div id="data_detail_view"></div>
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
<div id="rekap_mode" class="modal fade" role="dialog">
  <div class="modal-dialog modal-sm modal-warning">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title text-center"><i class="fas fa-file-export"></i> Rekap Mode</h4>
      </div>
      <div class="modal-body text-center">
			<input type="hidden" name="mode" id="usage_rekap_mode" value="">
			<div class="form-group">
				<label>Jenis Rekap</label>
					<?php
						$jenisRekap[null] = 'Pilih Data';
						$jenisRekap = $this->otherfunctions->getJenisRekapLembur();
						$selw = [null];
						$exselw = array('class'=>'form-control select2','placeholder'=>'Jenis Rekap','required'=>'required','id'=>'jenis_rekap_lembur','style'=>'width:100%');
						echo form_dropdown('jenis_rekap',$jenisRekap,$selw,$exselw);
					?>
			</div>
			<div class="form-group">
				<label>Pilih Yang Menyetujui</label>
				<select class="form-control select2" name="karyawan_rekap" id="karyawan_rekap" style="width: 100%;"></select>
			</div>
      	<div class="col-md-12" style="text-align: center;">
      		<button type="button" class="btn btn-danger" onclick="do_rekap('pdf')"><i class="fas fa-file-pdf fa-fw"></i>Cetak PDF</button>
      	</div>
      	<!-- <div class="col-md-6" style="text-align: center;">
      		<button type="button" class="btn btn-primary" onclick="do_rekap('excel')"><i class="fas fa-file-excel fa-fw"></i>Excel</button>
      	</div> -->
      	<div class="clearfix"></div>
      </div>
      <div class="modal-footer">
      </div>
    </div>
  </div>
</div>

<div id="bagian_mode" class="modal fade" role="dialog">
	<div class="modal-dialog modal-md modal-default">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title text-center"><i class="fas fa-file-export"></i> Cetak Rekapitulasi Per Bagian</h4>
			</div>
			<div class="modal-body">
				<form id="form_bagian">
					<input type="hidden" name="mode" id="usage_bagian_mode" value="">
					<input type="hidden" name="usage" value="log">
					<div class="form-group">
						<label>Pilih Periode</label>
							<select class="form-control select2" name="periode" id="data_periode_bag" style="width: 100%;" onchange="get_bagian_adv(this.value,'bagian_bag')">
								<?php
								
								echo '<option></option>';
								foreach ($periode as $p) {
									echo '<option value="'.$p->kode_periode_lembur.'">'.$p->nama.' ('.$p->nama_sistem_penggajian.')</option>';
								}
								?>
							</select>
					</div>
					<div class="form-group">
						<label>Pilih Bagian</label>
						<select class="form-control select2" name="bagian[]" multiple="multiple" id="bagian_bag" style="width: 100%;"></select>
					</div>
					<div class="form-group">
						<label>Pilih Yang Mengetahui</label>
						<select class="form-control select2" name="karyawan_bagian_mengetahui" id="karyawan_bagian_mengetahui" style="width: 100%;"></select>
					</div>
					<div class="form-group">
						<label>Pilih Yang Menyetujui</label>
						<select class="form-control select2" name="karyawan_bagian_menyetujui" id="karyawan_bagian_menyetujui" style="width: 100%;"></select>
					</div>
				</form>
				<!-- <div class="col-md-6" style="text-align: center;">
					<button type="button" class="btn btn-danger" onclick="do_rekap_bagian('pdf')"><i class="fas fa-file-pdf fa-fw"></i>PDF</button>
				</div>
				<div class="col-md-6" style="text-align: center;">
      				<button type="button" class="btn btn-primary" onclick="do_rekap_bagian('excel')"><i class="fas fa-file-excel fa-fw"></i>Excel</button>
				</div>
				<div class="clearfix"></div> -->
			</div>
			<div class="modal-footer">
				<div class="col-md-6 pull-right">
      				<button type="button" class="btn btn-primary" onclick="do_rekap_bagian('excel')"><i class="fas fa-file-excel fa-fw"></i>Excel</button>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="lembur_excel" class="modal fade" role="dialog">
	<div class="modal-dialog modal-md modal-default">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title text-center"><i class="fas fa-file-export"></i> Cetak Rekap Penggajian Excel</h4>
			</div>
			<div class="modal-body">
				<form id="form_lembur_excel">
					<input type="hidden" name="mode" id="usage_bagian_mode" value="">
					<input type="hidden" name="usage" value="log">
					<div class="form-group">
						<label>Pilih Periode</label>
							<select class="form-control select2" name="periode" id="data_periode_lem" style="width: 100%;" onchange="get_bagian_adv(this.value,'bagian_lembur')">
								<?php
								
								echo '<option></option>';
								foreach ($periode as $p) {
									echo '<option value="'.$p->kode_periode_lembur.'">'.$p->nama.' ('.$p->nama_sistem_penggajian.')</option>';
								}
								?>
							</select>
					</div>
					<div class="form-group">
						<label>Pilih Bagian</label>
						<select class="form-control select2" name="bagian[]" multiple="multiple" id="bagian_lembur" style="width: 100%;"></select>
					</div>
					<div class="form-group">
						<label>Pilih Yang Mengetahui</label>
						<select class="form-control select2" name="karyawan_bagian_mengetahui" id="karyawan_bagian_mengetahui" style="width: 100%;"></select>
					</div>
					<div class="form-group">
						<label>Pilih Yang Menyetujui</label>
						<select class="form-control select2" name="karyawan_bagian_menyetujui" id="karyawan_bagian_menyetujui" style="width: 100%;"></select>
					</div>
				</form>
				<div class="clearfix"></div>
			</div>
			<div class="modal-footer">
				<div class="col-md-12 pull-right">
      				<button type="button" class="btn btn-primary" onclick="do_lembur_excel()"><i class="fas fa-file-excel fa-fw"></i>Cetak Excel</button>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="rekap_jabatan" class="modal fade" role="dialog">
	<div class="modal-dialog modal-md modal-default">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title text-center"><i class="fas fa-file-export"></i> Cetak Rekap Per Jabatan</h4>
			</div>
			<div class="modal-body">
				<form id="form_jabatan">
					<input type="hidden" name="mode" id="usage_jabatan_mode" value="">
					<input type="hidden" name="usage" value="log">
					<div class="form-group">
						<label>Pilih Periode</label>
						<select class="form-control select2" name="periode" id="data_periode_jab" style="width: 100%;" onchange="get_jabatan_adv(this.value,'jabatan_jab')">
							<?php
							
							echo '<option></option>';
							foreach ($periode as $p) {
								echo '<option value="'.$p->kode_periode_lembur.'">'.$p->nama.' ('.$p->nama_sistem_penggajian.')</option>';
							}
							?>
						</select>
					</div>
					<div class="form-group">
						<label>Pilih Jabatan</label>
						<select class="form-control select2" name="jabatan[]" multiple="multiple" id="jabatan_jab" style="width: 100%;"></select>
					</div>
					<div class="form-group">
						<label>Pilih Yang Mengetahui</label>
						<select class="form-control select2" name="karyawan_jabatan_mengetahui" id="karyawan_jabatan_mengetahui" style="width: 100%;"></select>
					</div>
					<div class="form-group">
						<label>Pilih Yang Menyetujui</label>
						<select class="form-control select2" name="karyawan_jabatan_menyetujui" id="karyawan_jabatan_menyetujui" style="width: 100%;"></select>
					</div>
				</form>
				<div class="clearfix"></div>
			</div>
			<div class="modal-footer">
				<div class="col-md-12 pull-right">
      				<button type="button" class="btn btn-primary" onclick="do_rekap_jabatan('excel')"><i class="fas fa-file-excel fa-fw"></i>Cetak Excel</button>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="rekap_transfer_bank" class="modal fade" role="dialog">
	<div class="modal-dialog modal-md modal-default">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title text-center"><i class="fas fa-file-export"></i> Rekap Transfer Bank</h4>
			</div>
			<div class="modal-body">
				<form id="form_transfer_bank">
					<input type="hidden" name="mode" id="usage_transfer_bank_mode" value="">
					<input type="hidden" name="usage" value="log">
					<div class="form-group">
						<label>Pilih Periode</label>
						<select class="form-control select2" name="periode" id="data_periode_jab" style="width: 100%;" onchange="get_bagian_adv(this.value,'data_bagian_rekap_transfer_bank')">
							<?php
							
							echo '<option></option>';
							foreach ($periode as $p) {
								echo '<option value="'.$p->kode_periode_lembur.'">'.$p->nama.' ('.$p->nama_sistem_penggajian.')</option>';
							}
							?>
						</select>
					</div>
					<div class="form-group">
						<label>Pilih Bagian</label>
						<select class="form-control select2" name="bagian[]" multiple="multiple" id="data_bagian_rekap_transfer_bank" style="width: 100%;"></select>
					</div>
				</form>
				<div class="clearfix"></div>
			</div>
			<div class="modal-footer">
				<div class="col-md-12 pull-right">
      				<button type="button" class="btn btn-primary" onclick="do_rekap_transfer_bank('excel')"><i class="fas fa-file-excel fa-fw"></i>Cetak Excel</button>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="rekap_tanda_terima" class="modal fade" role="dialog">
	<div class="modal-dialog modal-sm modal-default">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title text-center"><i class="fas fa-file-export"></i> Cetak Tanda Terima Lembur</h4>
			</div>
			<div class="modal-body">
				<form id="form_tanda">
					<input type="hidden" name="mode" id="usage_tanda_mode" value="">
					<input type="hidden" name="usage" value="log">
					<div class="form-group">
						<label>Pilih Periode</label>
							<select class="form-control select2" name="periode" id="data_periode_tanda" style="width: 100%;" onchange="get_bagian_adv(this.value,'bagian_tanda')">
								<?php
								
								echo '<option></option>';
								foreach ($periode as $p) {
									echo '<option value="'.$p->kode_periode_lembur.'">'.$p->nama.' ('.$p->nama_sistem_penggajian.')</option>';
								}
								?>
							</select>
					</div>
					<div class="form-group">
						<label>Pilih Bagian</label>
						<select class="form-control select2" name="bagian[]" multiple="multiple" id="bagian_tanda" style="width: 100%;"></select>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<div class="col-md-12 pull-right">
					<button type="button" class="btn btn-danger" onclick="do_rekap_ttd()"><i class="fas fa-file-pdf fa-fw"></i>PDF</button>
				</div>
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
				<p>Data Tidak Ditemukan!</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
			</div>
		</div>
	</div>
</div>
<script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
<script type="text/javascript">
	$(document).ready(function(){
		var kode_periode = $('#data_periode_filter').val();
		table_data(kode_periode);
	});
	function table_filter() {
		var kode_periode = $('#data_periode_filter').val();
		table_data(kode_periode);
	}
	function table_data(kode_periode) {
		$('#table_data').DataTable( {
			ajax: {
				url: "<?php echo base_url('cpayroll/data_log_penggajian_lembur/view_all/')?>",
				type: 'POST',
				data:{access:"<?php echo $this->codegenerator->encryptChar($access);?>",kode_periode:kode_periode,bagian:$('#adv_bagian').val()}
			},
			fixedColumns:   {
				leftColumns: 3,
				rightColumns: 1
			},
			scrollX: true,
			bDestroy: true,
			autoWidth: false,
			columnDefs: [
			{   targets: 0, 
				width: '5%',
				render: function ( data, type, full, meta ) {
					return '<center>'+(meta.row+1)+'.</center>';
				}
			},
			{   targets: 17,
				width: '5%',
				render: function ( data, type, full, meta ) {
					return '<center>'+data+'</center>';
				}
			},
			{   targets: 22, 
				width: '10%',
				render: function ( data, type, full, meta ) {
					return '<center>'+data+'</center>';
				}
			},
			]
		});
	}
	function refreshData() {
		var kode_periode = $('#data_periode_filter').val('').trigger('change');
		setTimeout(function () {
			table_filter();
		},600); 
	}
	function view_modal(id) {
		var data={id_penggajian_lembur:id, mode: 'view'};
		var callback=getAjaxData("<?php echo base_url('cpayroll/data_log_penggajian_lembur/view_one')?>",data);  
		$('#view').modal('show');
		$('.header_data').html(callback['nama_karyawan']);
		$('input[name="data_id_view"]').val(callback['id']);

		$('#data_nik_view').html(callback['nik']);
		$('#data_name_view').html(callback['nama_karyawan']);

		$('#data_jabatan_view').html(callback['jabatan']);
		$('#data_grade_view').html(callback['grade']);
		$('#data_bagian_view').html(callback['bagian']);
		$('#data_loker_view').html(callback['loker']);
		$('#data_sistem_view').html(callback['sistem']);
		$('#data_tanggal_view').html(callback['tanggal']);
		$('#data_masa_view').html(callback['masa']);
		$('#data_rekening_view').html(callback['rekening']);
		$('#data_gaji_pokok_view').html(callback['gaji_pokok']);
		$('#data_periode_view').html(callback['periode']);
		$('#data_detail_view').html(callback['detail_lembur']);

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
	function do_print_slip() {
		var kode_periode = $('#data_periode_adv').val();
		if(kode_periode == ''){
			$('#alert').modal('show');
		}else{
			$.redirect("<?php echo base_url('pages/slip_lembur'); ?>", 
			{
				data_filter: $('#form_filter').serialize()
			},
			"POST", "_blank");
		}
	}
	function rekap_lembur() {
		$('#usage_rekap_mode').val('export_lembur');
		$('#rekap_mode').modal('show');
		getSelect2("<?php echo base_url('employee/mutasi_jabatan/employee')?>",'karyawan_rekap');
	}
	function do_rekap(file) {
		var usage = $('#usage_rekap_mode').val();
		var emp = $('#karyawan_rekap').val();
		var jenis = $('#jenis_rekap_lembur').val();
		var kode_periode = $('#data_periode_adv').val();
		if(usage == '' || kode_periode == ''){
			$('#rekap_mode').modal('toggle');
			$('#alert').modal('show');
		}else{
			if(usage == 'export_lembur'){
				if (file == 'pdf') {
					$.redirect("<?php echo base_url('pages/rekap_lembur'); ?>", 
					{
						data_filter: $('#form_filter').serialize(),
						karyawan:emp,
						jenis:jenis,
						usage:'log'
					},
					"POST", "_blank");
				} else {
					$.redirect("<?php echo base_url('rekap/export_data_penggajian_lembur'); ?>", 
					{
						data_filter: $('#form_filter').serialize(),
						karyawan:emp,
						jenis:jenis,
						usage:'log'
					},
					"POST", "_blank");
				}
			}
		}
	}
	function rekap_bagian() {
		$('#usage_bagian_mode').val('export_lembur');
		$('#bagian_mode').modal('show');
		getSelect2("<?php echo base_url('employee/mutasi_jabatan/employee')?>",'karyawan_bagian_mengetahui, #karyawan_bagian_menyetujui');
	}
	function do_rekap_bagian(file) {
		$.redirect("<?php echo base_url('rekap/export_penggajian_lembur_bagian'); ?>", 
		{
			data_filter: $('#form_bagian').serialize(),
			mengetahui:null,
			menyetujui:null,
		},
		"POST", "_blank");
	}
	function do_rekap_jabatan(file) {
		$.redirect("<?php echo base_url('rekap/export_penggajian_lembur_jabatan'); ?>", 
		{
			data_filter: $('#form_jabatan').serialize(),
			mengetahui:null,
			menyetujui:null,
		},
		"POST", "_blank");
	}
	function do_rekap_ttd() {		
		$.redirect("<?php echo base_url('pages/tanda_terima_lembur'); ?>", 
		{
			data_filter: $('#form_tanda').serialize()
		},
		"POST", "_blank");
	}
	function get_bagian_adv(value,id=null) {
		var data={kode_periode:value,log:true};
		var callback=getAjaxData("<?php echo base_url('cpayroll/data_penggajian_lembur/bagian/')?>",data);
		$('#adv_bagian').html(callback['bagian']);
		if(id !== null){
			$('#'+id).html(callback['bagian']);
		}
	}
	function get_jabatan_adv(value,id=null) {
		var data={kode_periode:value,log:true};
		var callback=getAjaxData("<?php echo base_url('cpayroll/data_penggajian_lembur/jabatan/')?>",data);
		if(id !== null){
			$('#'+id).html(callback['jabatan']);
		}
	}
	function rekap_lembur_excel() {
		$('#usage_bagian_mode').val('export_lembur');
		$('#lembur_excel').modal('show');
		getSelect2("<?php echo base_url('employee/mutasi_jabatan/employee')?>",'karyawan_bagian_mengetahui, #karyawan_bagian_menyetujui');
	}
	function rekap_jabatan() {
		$('#usage_bagian_mode').val('export_lembur');
		$('#rekap_jabatan').modal('show');
		getSelect2("<?php echo base_url('employee/mutasi_jabatan/employee')?>",'karyawan_jabatan_mengetahui, #karyawan_jabatan_menyetujui');
	}
	function rekap_tanda_terima() {
		$('#usage_bagian_mode').val('export_lembur');
		$('#rekap_tanda_terima').modal('show');
		getSelect2("<?php echo base_url('employee/mutasi_jabatan/employee')?>",'karyawan_bagian_mengetahui, #karyawan_bagian_menyetujui');
	}
	function rekap_transfer() {
		$('#rekap_transfer_bank').modal('show');
	}
	function do_lembur_excel(){
		$.redirect("<?php echo base_url('rekap/export_data_penggajian_lembur'); ?>", 
		{
			data_filter: $('#form_lembur_excel').serialize(),
		},
		"POST", "_blank");
	}
	function do_rekap_transfer_bank(){
		$.redirect("<?php echo base_url('rekap/export_gaji_lembur_transfer_bank'); ?>", 
		{
			data_filter: $('#form_transfer_bank').serialize(),
		},
		"POST", "_blank");
	}
	function rekap_req() {
		var periode = $('#data_periode_filter').val();
		var bagian = $('#adv_bagian').val();
		if(periode == ''){
			notValidParamxCustom('Harap Pilih Periode Lembur !');
		}else if(bagian == ''){
			notValidParamxCustom('Harap Pilih Bagian !');
		}else{	
			$.redirect("<?php echo base_url('rekap/export_gaji_lembur_perhitungan'); ?>", 
			{
				data_filter: $('#form_filter').serialize(),
			},
			"POST", "_blank");
		}
	}
</script>
