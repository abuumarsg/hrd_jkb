  <div class="content-wrapper">
  	<section class="content-header">
  		<h1>
  			<a class="back" href="<?php echo base_url('pages/data_hasil_group'); ?>"><i class="fa fa-chevron-circle-left "></i></a> <i class="fa fa-users"></i> Daftar Karyawan
  		</h1>
  		<ol class="breadcrumb">
  			<li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
  			<li><a href="<?php echo base_url('pages/data_hasil_group');?>"><i class="fa fa-calendar"></i> Daftar Agenda Gabungan</a></li>
  			<li class="active">Daftar Karyawan</li>
  		</ol>
  	</section>
  	<section class="content">
  		<div class="row">
  			<div class="col-md-12">
  				<div class="box box-warning">
  					<div class="box-header with-border">
  						<h3 class="box-title"><i class="fa fa-users"></i> Daftar Bawahan <?=$nama_agenda?></h3>
						<div class="box-tools pull-right">
							<button class="btn btn-box-tool" onclick="reload_table('table_data')" data-toggle="tooltip"
								title="Refresh Table"><i class="fas fa-sync"></i></button>
							<button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
								title="Collapse"><i class="fa fa-minus"></i></button>
							<button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i
									class="fa fa-times"></i></button>
						</div> 
  					</div>
  					<div class="box-body">
  						<div class="row">
							<div class="col-md-12">
								<form id="form_filter">
									<input type="hidden" name="nama_periode" value="<?= $nama_agenda; ?>">  
									<input type="hidden" name="kode" value="<?= $this->codegenerator->encryptChar($kode); ?>">  
								</form>
								<button onclick="do_rekap()" class="btn btn-warning"><i class="fas fa-file-excel-o"></i> Export Data</button>
							</div>
						</div><br>
  						<div class="row">
  							<div class="col-md-12">
  								<div class="callout callout-info">
  									<label><i class="fa fa-info-circle"></i> Bantuan</label><br>
  									Pilih NIK Karyawan untuk melihat Rapor Penilaian Kinerja 
  								</div>
  								<table id="table_data" class="table table-bordered table-striped table-responsive table-hover" width="100%">
  									<thead>
									  	<tr>
  											<th rowspan="2">No.</th>
  											<th rowspan="2">NIK</th>
  											<th rowspan="2">Nama Karyawan</th>
  											<th rowspan="2">Jabatan</th>
  											<th rowspan="2">Bagian</th>
  											<th rowspan="2">Departemen</th>
  											<th rowspan="2">Lokasi Kerja</th>
  											<th colspan="2" class="text-center">Nilai</th>
  											<th rowspan="2">Nilai Total</th>
  											<th rowspan="2">Kedisiplinan</th>
  											<th rowspan="2" class="text-center bg-blue">Total Nilai Sekarang <?php echo (isset($kode['kode_periode']))?$this->model_master->getListPeriodePenilaianActive()[$kode['kode_periode']].' '.$kode['tahun']:$kode['tahun']; ?></th>
                       						<th rowspan="2">Huruf</th>
  										</tr>
										<tr>
											<th>Nilai Aspek KPI Output</th>
										  	<th>Nilai Aspek Sikap 360°</th>
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

  <script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
  <script type="text/javascript">
  	var url_select="<?php echo base_url('global_control/select2_global');?>";
  	$(document).ready(function(){
  		tableData();
  	})
  	function tableData() { 
  		var t=$('#table_data').DataTable( {
  			ajax: {
  				url: "<?php echo base_url('kagenda/view_raport_bawahan/periode')?>",
  				type: 'POST',
  				data:{kode:"<?= $this->codegenerator->encryptChar($kode);?>"}
  			},
  			scrollX: true,
			processing: true,
			order:[1,'asc'],
			bDestroy:true,
  			columnDefs: [
  			{   targets: 0, 
  				width: '5%',
  				render: function ( data, type, full, meta ) {
  					return '<center>'+(meta.row+1)+'.</center>';
  				}
  			},
  			{   targets: 1,
  				width: '5%',
  				render: function ( data, type, full, meta ) {
  					return '<a href="<?php echo base_url("kpages/report_value_bawahan_group/".$this->uri->segment(3));?>/' +full[14] + '" target="_blank">' + data + '</a>';
  				}
  			},
  			{   targets: [2,3],
  				width: '30%',
  				render: function ( data, type, full, meta ) {
  					return data;
  				}
  			},
  			{   targets: [7,8,9,10,11,12],
  				width: '5%',
  				render: function ( data, type, full, meta ) {
  					return '<center>'+data+'</center>';
  				}
			  },
  			]
  		});
  		t.on( 'order.dt search.dt', function () {
  			t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
  				cell.innerHTML = i+1;
  			} );
  		}).draw();
  	}
	function do_rekap() {
		window.open("<?= base_url('kagenda/rekap_akhir_bawahan/'.$this->uri->segment(3))?>/?"+$('#form_filter').serialize());
	}
  </script>