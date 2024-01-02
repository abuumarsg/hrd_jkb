
<style type="text/css">
table#table_data thead tr th, table#table_data tbody tr td, table.DTFC_Cloned thead tr th{
	white-space: pre;
}
table.DTFC_Cloned tbody{
	overflow: hidden;
}
</style>
<div class="content-wrapper">
	<section class="content-header">
		<h1>
			<i class="fa far fa-credit-card"></i> Penggajian
			<small>Log Data penggajian</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
			<li class="active">Log Data Penggajian</li>
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
											echo '<option></option>';
											foreach ($periode as $p) {
												$wktime = strtoupper($this->formatter->getNameOfMonth(date("m",strtotime($p->tgl_selesai)))).' '.date("Y",strtotime($p->tgl_selesai));
												echo '<option value="'.$p->kode_periode.'">'.$p->nama_periode.'</option>';
												// echo '<option value="'.$p->kode_periode.'">'.$p->nama_periode.' ('.$p->nama_sistem_penggajian.') - '.$wktime.'</option>';
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
						<h3 class="box-title"><i class="fa fa-list"></i> Daftar Log Data Penggajian</h3>
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
															<li><a onclick="print_slip()">Slip Gaji</a></li>
															<li><a onclick="rekap_gaji()">Rekap Gaji</a></li>
															<li><a onclick="rekap_bagian()">Rekapitulasi Gaji Bagian</a></li>
															<li><a onclick="rekap_tanda_terima()">Cetak Tanda Terima</a></li>
															<li><a onclick="rekap_transfer()">Cetak Transfer Bank</a></li>
															<!-- <li><a onclick="rekap_pph()">PPH-21</a></li> -->
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
						<div class="row">
							<div class="col-md-12">
								<table id="table_data" class="table table-bordered table-striped table-responsive" width="100%">
									<thead>
										<tr>
											<th rowspan="2">No.</th>
											<th rowspan="2">NIK Karyawan</th>
											<th rowspan="2">Nama Karyawan</th>
											<th rowspan="2">Jabatan</th>
											<th rowspan="2">Grade</th>
											<th rowspan="2">Tanggal Masuk</th>
											<th rowspan="2">Masa Kerja</th>
											<th rowspan="2">Gaji Pokok (Rp)</th>
											<?php
												echo '<th class="text-center" style="background-color:green;" colspan="'.count($indukTunjanganTetap).'">DATA TUNJANGAN TETAP</th>';
											?>
											<?php
												echo '<th class="text-center" style="background-color:yellow;" colspan="'.count($indukTunjanganNon).'">DATA TUNJANGAN TIDAK TETAP</th>';
											?>
											<th rowspan="2">Insentif (Rp)</th>
											<th rowspan="2">Ritasi (Rp)</th>
											<th rowspan="2">Uang Makan (Rp)</th>
											<th rowspan="2">Potongan Tidak Masuk (Rp)</th>
											<th rowspan="2">BPJS JHT (Rp)</th>
											<!-- <th rowspan="2">BPJS JKK (Rp)</th>
											<th rowspan="2">BPJS JKM (Rp)</th> -->
											<th rowspan="2">BPJS JPEN (Rp)</th>
											<th rowspan="2">BPJS JKES (Rp)</th>
											<th rowspan="2">Angsuran (Rp)</th>
											<!-- <th rowspan="2">Angsuran Ke</th> -->
											<th rowspan="2">Denda (Rp)</th>
											<th rowspan="2">Angsuran Denda Ke</th>
											<th class="text-center" colspan="3" style="background-color:yellow;">Pendukung Lainnya</th>
											<!-- <th rowspan="2">Jenis Lainnya</th>
											<th rowspan="2">Nominal Lainnya (Rp)</th>
											<th rowspan="2">Keterangan Lainnya</th> -->
											<th rowspan="2">Gaji Bersih (Rp)</th>
											<th rowspan="2">Nomor Rekening</th>
											<!-- <th rowspan="2">Tanggal</th> -->
											<th rowspan="2">Tanggal</th>
											<th rowspan="2">Aksi</th>
										</tr>
										<tr>
											<?php
												foreach ($indukTunjanganTetap as $key_tt) {
													echo '<th>'.ucwords(strtolower(str_replace('TUNJANGAN','',$key_tt->nama))).' (Rp)</th>';
												}
											?>
											<?php
												foreach ($indukTunjanganNon as $key_it) {
													echo '<th>'.ucwords(strtolower(str_replace('TUNJANGAN','',$key_it->nama))).' (Rp)</th>';
												}
											?>
											<th>Jenis</th>
											<th>Nama</th>
											<th>Nominal (Rp)</th>
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
							<label class="col-md-6 control-label">Periode Penggajian</label>
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
							<div class="col-md-6">
								<div id="data_penambah_view"></div>
							</div>
							<div class="col-md-6">
								<div id="data_pengurang_view"></div>
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
  <div class="modal-dialog modal-sm modal-warning">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title text-center"><i class="fas fa-file-export"></i> Rekap Mode</h4>
      </div>
      <div class="modal-body text-center">
      	<div class="col-md-6" style="text-align: center;">
      		<button type="button" class="btn btn-danger" onclick="do_rekap('pdf')"><i class="fas fa-file-pdf fa-fw"></i>PDF</button>
      	</div>
      	<div class="col-md-6" style="text-align: center;">
      		<button type="button" class="btn btn-primary" onclick="do_rekap('excel')"><i class="fas fa-file-excel fa-fw"></i>Excel</button>
      	</div>
      	<input type="hidden" id="usage_rekap_mode" value="">
      	<div class="clearfix"></div>
      </div>
      <div class="modal-footer">
      </div>
    </div>
  </div>
</div>
<div id="rekap_tanda_terima" class="modal fade" role="dialog">
	<div class="modal-dialog modal-sm modal-default">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title text-center"><i class="fas fa-file-export"></i> Cetak Tanda Terima</h4>
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
										echo '<option value="'.$p->kode_periode.'">'.$p->nama_periode.' ('.$p->nama_sistem_penggajian.')</option>';
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
								echo '<option value="'.$p->kode_periode.'">'.$p->nama_periode.' ('.$p->nama_sistem_penggajian.')</option>';
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
<div id="bagian_mode" class="modal fade" role="dialog">
	<div class="modal-dialog modal-sm modal-default">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title text-center"><i class="fas fa-file-export"></i> Cetak Rekap Per Bagian</h4>
			</div>
			<div class="modal-body text-center">
				<form id="form_bagian">
					<input type="hidden" name="mode" id="usage_bagian_mode" value="">
					<input type="hidden" name="usage" value="data">
					<div class="form-group">
						<label>Pilih Yang Mengetahui</label>
						<select class="form-control select2" name="karyawan_bagian_mengetahui"
							id="karyawan_bagian_mengetahui" style="width: 100%;"></select>
					</div>
					<div class="form-group">
						<label>Pilih Yang Menyetujui</label>
						<select class="form-control select2" name="karyawan_bagian_menyetujui"
							id="karyawan_bagian_menyetujui" style="width: 100%;"></select>
					</div>
				</form>
				<div class="col-md-6" style="text-align: center;">
					<button type="button" class="btn btn-danger" onclick="do_rekap_bagian('pdf')"><i
							class="fas fa-file-pdf fa-fw"></i>PDF</button>
				</div>
				<div class="col-md-6" style="text-align: center;">
					<button type="button" class="btn btn-primary" onclick="do_rekap_bagian('excel')"><i
							class="fas fa-file-excel fa-fw"></i>Excel</button>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="modal-footer">
			</div>
		</div>
	</div>
</div>
<script type="text/javascript" charset="utf-8" async defer>
  function do_delete(){
  	var table = $('#data_form_table').val();
    submitAjax("<?php echo base_url('global_control/delete')?>",'delete','form_delete',null,null);
    $(table).DataTable().ajax.reload(function (){
      Pace.restart();
    });
  }
</script>
<div id="modal_delete_partial"></div>
<script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
<script type="text/javascript">
	var jumlah_induk_t = <?=count($indukTunjanganTetap)?>; 
	var jumlah_induk = <?=count($indukTunjanganNon)?>; 
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
				url: "<?php echo base_url('cpayroll/data_log_penggajian/view_all/')?>",
				type: 'POST',
				data:{access:"<?php echo $this->codegenerator->encryptChar($access);?>",kode_periode:kode_periode,bagian:$('#adv_bagian').val()}
			},
			fixedColumns:   {
				leftColumns: 3,
				rightColumns: 1
			},
			bDestroy: true,
			scrollX: true,
			autoWidth: false,
			columnDefs: [
			{   targets: 0, 
				width: '5%',
				render: function ( data, type, full, meta ) {
					return '<center>'+(meta.row+1)+'.</center>';
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
		var data={id_pay:id, mode: 'view'};
		var callback=getAjaxData("<?php echo base_url('cpayroll/data_log_penggajian/view_one')?>",data);  
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
		$('#data_penambah_view').html(callback['penambah']);
		$('#data_pengurang_view').html(callback['pengurang']);
		$('#data_gaji_bersih_view').html(callback['total_gaji']);
		$('#data_gaji_pokok_view').html(callback['gaji_pokok']);
		$('#data_pph_view').html(callback['pph']);

		$('#data_periode_view').html(callback['periode']);
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
	function get_bagian_adv(value, id) {
		var data = {
			kode_periode: value,
			log:true,
		};
		var callback = getAjaxData("<?php echo base_url('cpayroll/data_penggajian/bagian/')?>", data);
		$('#'+id).html(callback['bagian']);
	}
	function print_slip() {
		var kode_periode = $('#data_periode_filter').val();
		if(kode_periode == ''){
			$('#alert').modal('show');
		}else{
			$.redirect("<?php echo base_url('pages/slip_gaji'); ?>", 
         {
            data_filter: $('#form_filter').serialize()
         },
         "POST", "_blank");
		}
	}
	function rekapitulasi() {
		$('#usage_rekap_mode').val('rekapitulasi');
		var kode_periode = $('#data_periode_filter').val();
		if(kode_periode == ''){
			$('#alert').modal('show');
		}else{
			$('#rekap_mode').modal('show');
		}
	}
	function rekap_gaji() {
		$('#usage_rekap_mode').val('rekap');
		var kode_periode = $('#data_periode_filter').val();
		if(kode_periode == ''){
			$('#alert').modal('show');
		}else{
			$('#rekap_mode').modal('show');
		}
	}
	function rekap_bagian() {
		$('#usage_bagian_mode').val('export_lembur');
		$('#bagian_mode').modal('show');
		getSelect2("<?php echo base_url('employee/mutasi_jabatan/employee')?>",'karyawan_bagian_mengetahui, #karyawan_bagian_menyetujui');
	}
	function rekap_pph() {
		$('#usage_rekap_mode').val('rekap');
		var kode_periode = $('#data_periode_filter').val();
		if(kode_periode == ''){
			$('#alert').modal('show');
		}else{
			var encrypt_kode_periode = getAjaxData("<?php echo base_url('global_control/encryptChar')?>",{val:kode_periode}); 
			window.location.href = "<?php echo base_url('rekap/export_pph')?>?kode_periode="+encrypt_kode_periode;
		}
	}

	function do_rekap(file) {
		var usage = $('#usage_rekap_mode').val();
		var kode_periode = $('#data_periode_filter').val();
		if(usage == ''){
			notValidParamx();
		}else{
			if(usage == 'rekap'){
				if (file == 'pdf') {
					$.redirect("<?php echo base_url('pages/rekap_payroll'); ?>", 
					{
						data_filter: $('#form_filter').serialize()
					},
					"POST", "_blank");
				} else {
					$.redirect("<?php echo base_url('rekap/export_log_data_gaji'); ?>", 
					{
						data_filter: $('#form_filter').serialize()
					},
					"POST", "_blank");
				}
			}else if(usage == 'rekapitulasi'){
				var mengetahui = $('#karyawan_bagian_mengetahui').val();
				var menyetujui = $('#karyawan_bagian_menyetujui').val();
				if (file == 'pdf') {
					$.redirect("<?php echo base_url('pages/rekap_gaji_bulanan_bagian'); ?>", 
					{
						data_filter: $('#form_filter').serialize(),
						mengetahui:mengetahui,
						menyetujui:menyetujui,
					},
					"POST", "_blank");
				} else {
					$.redirect("<?php echo base_url('rekap/export_penggajian_bulanan_bagian'); ?>", 
					{
						data_filter: $('#form_filter').serialize()
					},
					"POST", "_blank");
				}
			}
		}
	}
	function do_rekap_bagian(file) {
		var kode_periode = $('#data_periode_filter').val();
		var mengetahui = $('#karyawan_bagian_mengetahui').val();
		var menyetujui = $('#karyawan_bagian_menyetujui').val();
		var data = {
			kode_periode: kode_periode
		};
		if (kode_periode == '') {
			$('#alert').modal('show');
		} else {
			if (file == 'pdf') {
				$.redirect("<?php echo base_url('pages/rekap_gaji_bulanan_bagian'); ?>", 
				{
					data_filter: $('#form_filter').serialize(),
					mengetahui:mengetahui,
					menyetujui:menyetujui,
				},
				"POST", "_blank");
			} else {
				$.redirect("<?php echo base_url('rekap/export_penggajian_bulanan_bagian'); ?>", 
				{
					data_filter: $('#form_filter').serialize(),
					mengetahui:mengetahui,
					menyetujui:menyetujui,
				},
				"POST", "_blank");
			}
		}
	}
	function rekap_tanda_terima() {
		$('#usage_bagian_mode').val('export_lembur');
		$('#rekap_tanda_terima').modal('show');
	}
	function rekap_transfer() {
		$('#rekap_transfer_bank').modal('show');
	}
	function do_rekap_ttd() {		
		$.redirect("<?php echo base_url('pages/tanda_terima_gaji'); ?>", 
		{
			data_filter: $('#form_tanda').serialize()
		},
		"POST", "_blank");
	}
	function do_rekap_transfer_bank(){
		$.redirect("<?php echo base_url('rekap/export_gaji_transfer_bank'); ?>", 
		{
			data_filter: $('#form_transfer_bank').serialize(),
		},
		"POST", "_blank");
	}
	</script>
