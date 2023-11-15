
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
			<small>Log Data penggajian Harian</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
			<li class="active">Log Data Penggajian Harian</li>
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
								<div class="col-md-2">
								</div>
								<div class="col-md-8">
									<div class="">
										<label>Pilih Periode</label>
										<select class="form-control select2" name="periode" id="data_periode_filter" style="width: 100%;">
											<?php
											$periode = $this->model_payroll->getDataLogPayrollHarian(['a.create_by'=>$id_admin,'a.kode_master_penggajian'=>'HARIAN'],0,'a.kode_periode');
											echo '<option></option>';
											foreach ($periode as $p) {
                                    $wktime = strtoupper($this->formatter->getNameOfMonth(date("m",strtotime($p->tgl_selesai)))).' '.date("Y",strtotime($p->tgl_selesai));
												echo '<option value="'.$p->kode_periode.'">'.$p->nama_periode.' ('.$p->nama_sistem_penggajian.') - '.$wktime.'</option>';
											}
											?>
										</select>
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
															<li><a onclick="rekap_gaji()">Rekap</a></li>
															<!-- <li><a onclick="rekapitulasi()">Rekapitulasi</a></li> -->
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
											<th>Gaji Pokok (Rp)</th>
											<th>Insentif (Rp)</th>
											<th>Ritasi (Rp)</th>
											<th>Uang Makan (Rp)</th>
											<th>Potongan Tidak Masuk (Rp)</th>
											<th>BPJS JHT (Rp)</th>
											<th>BPJS JKK (Rp)</th>
											<th>BPJS JKM (Rp)</th>
											<th>BPJS JPEN (Rp)</th>
											<th>BPJS JKES (Rp)</th>
											<th>Angsuran (Rp)</th>
											<th>Angsuran Ke</th>
											<th>Gaji Lembur (Rp)</th>
											<th>Lainnya</th>
											<th>Nominal Lainnya (Rp)</th>
											<th>Keterangan Lainnya</th>
											<th>Gaji Terima (Rp)</th>
											<th>Nomor Rekening</th>
											<!-- <th>Tanggal Transfer</th> -->
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
						<div style="text-align: center;">
							<h3>Detail Data</h3>
						</div>
                  <div style="overflow-y: auto;height: 300px;">
							<div class="col-md-12">
								<div id="data_lembur_view"></div>
							</div>
						</div>
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
	$(document).ready(function(){
		var kode_periode = $('#data_periode_filter').val();
		table_data(kode_periode);
	});
	function table_filter() {
		var kode_periode = $('#data_periode_filter').val();
		table_data(kode_periode);
	}
	function table_data(kode_periode) {
		$('#table_data').DataTable().destroy();
		setTimeout(function () {
			$('#table_data').DataTable( {
				ajax: {
					url: "<?php echo base_url('cpayroll/data_log_penggajian_harian/view_all/')?>",
					type: 'POST',
					data:{access:"<?php echo $this->codegenerator->encryptChar($access);?>",kode_periode:kode_periode}
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
				{   targets: 30, 
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
		var kode_periode = $('#data_periode_filter').val('').trigger('change');
		setTimeout(function () {
			table_filter();
		},600); 
	}
	function view_modal(id) {
		var data={id_pay:id, mode: 'view'};
		var callback=getAjaxData("<?php echo base_url('cpayroll/data_log_penggajian_harian/view_one')?>",data);  
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
      $('#data_lembur_view').html(callback['lembur']);
      
		$('#data_gaji_bersih_view').html(callback['total_gaji']);
		$('#data_gaji_pokok_view').html(callback['gaji_pokok']);
		$('#data_pph_view').html(callback['pph']);

		$('#data_periode_view').html(callback['periode']);
		var status = callback['status'];
		if (status == 1) {
			var statusval = '<b class="text-success">Aktif</b>';
		} else {
			var statusval = '<b class="text-danger">Tidak Aktif</b>';
		}
		$('#data_status_view').html(statusval);
		$('#data_create_date_view').html(callback['create_date'] + ' WIB');
		$('#data_update_date_view').html(callback['update_date'] + ' WIB');
		$('#data_create_by_view').html(callback['nama_buat']);
		$('#data_update_by_view').html(callback['nama_update']);
	}
	function print_slip() {
		var kode_periode = $('#data_periode_filter').val();
		if(kode_periode == ''){
			$('#alert').modal('show');
		}else{
         /* var encrypt_kode_periode = getAjaxData("<?php echo base_url('global_control/encryptChar')?>",{val:kode_periode}); */ 
         /* window.open('<?php /* echo base_url()."pages/slip_gaji_harian/".$this->codegenerator->encryptChar("log")."/"; */ ?>'+encrypt_kode_periode,'_blank'); */
         $.redirect("<?php echo base_url('pages/slip_gaji_harian'); ?>", 
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
				if(file == 'pdf'){
					$.redirect("<?php echo base_url('pages/rekap_payroll_harian'); ?>", 
					{
						data_filter: $('#form_filter').serialize()
					},
					"POST", "_blank");
				}else{
					$.redirect("<?php echo base_url('rekap/export_log_data_gaji_harian'); ?>", 
					{
						data_filter: $('#form_filter').serialize()
					},
					"POST", "_blank");
				}
			}else if(usage == 'rekapitulasi'){
				if(file == 'pdf'){
					var encrypt_kode_periode = getAjaxData("<?php echo base_url('global_control/encryptChar')?>",{val:kode_periode}); 
					window.open('<?php echo base_url()."pages/rekapitulasi_payroll/".$this->codegenerator->encryptChar("log")."/"; ?>'+encrypt_kode_periode,'_blank');
				}else{
					var encrypt_kode_periode = getAjaxData("<?php echo base_url('global_control/encryptChar')?>",{val:kode_periode}); 
					window.location.href = "<?php echo base_url('rekap/export_rekapitulasi')?>?kode_periode="+encrypt_kode_periode;
				}
			}
		}
	}
	</script>