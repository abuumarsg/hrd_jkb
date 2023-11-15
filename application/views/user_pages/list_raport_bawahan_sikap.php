<div class="content-wrapper">
  	<section class="content-header">
  		<h1>
  			<i class="fa fa-users"></i> Daftar Bawahan
  		</h1>
  		<ol class="breadcrumb">
  			<li><a href="<?php echo base_url('kpages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
        <li><a href="<?php echo base_url('kpages/raport_bawahan_sikap');?>"><i class="fa fa-calendar"></i> Daftar Agenda</a></li>
  			<li class="active">Daftar Bawahan</li>
  		</ol>
  	</section>
  	<section class="content">
  		<div class="row">
  			<div class="col-md-12">
  				<div class="box box-info">
  					<div class="box-header with-border">
  						<h3 class="box-title"><i class="fa fa-users"></i> Daftar Bawahan <?=$nama_agenda?></h3>
  						<div class="box-tools pull-right">
  							<button class="btn btn-box-tool" onclick="reload_table('table_data')" data-toggle="tooltip"
  								title="Refresh Table"><i class="fas fa-sync"></i></button>
  							<button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i
  									class="fa fa-minus"></i></button>
  							<button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i
  									class="fa fa-times"></i></button>
  						</div>
  					</div>
  					<div class="box-body">
            <div class="row">
  							<div class="col-md-12">
  								<button onclick="do_rekap()" class="btn btn-warning"><i class="fas fa-file-excel-o"></i> Export
  									Data</button>
  							</div>
  						</div><br>
  						<div class="row">
  							<div class="col-md-12">
  								<div class="callout callout-info"><label><i class="fa fa-info-circle"></i> Bantuan</label><br>Pilih
  									NIK untuk melihat Raport Agenda penilaian Aspek Sikap (360°)</div>
  								<table id="table_data" class="table table-bordered table-striped table-responsive" width="100%">
  									<thead>
  										<tr>
  											<th>No.</th>
  											<th>NIK</th>
  											<th>Nama Karyawan</th>
  											<th>Jabatan</th>
  											<th>Bagian</th>
  											<th>Departemen</th>
  											<th>Lokasi Kerja</th>
  											<th>Nilai</th>
  											<th>Huruf</th>
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
  	$(document).ready(function () {
  		var t=$('#table_data').DataTable({
  			ajax: {
  				url: "<?php echo base_url('kagenda/list_raport_bawahan_sikap/view_all')?>",
          type: 'POST',
          data:{kode:"<?= $this->uri->segment(3);?>",tabel:"<?=$this->codegenerator->encryptChar($tabel)?>"}
  			},
  			scrollX: true,
				processing: true,
        order: [1,'asc'],
  			columnDefs: [{
  					targets: 0,
  					width: '3%',
  					render: function (data, type, full, meta) {
  						return '<center>' + (meta.row + 1) + '.</center>';
  					}
  				},
  				{
  					targets: 1,
  					width: '5%',
  					render: function (data, type, full, meta) {
  						return '<a href="<?php echo base_url("kpages/view_raport_bawahan_sikap/".$this->uri->segment(3));?>/' + full[0] +'" target="_blank">' + data + '</a>';
  					}
  				},
  				{
  					targets: [2,3],
  					width: '30%',
  					render: function (data, type, full, meta) {
  						return data;
  					}
  				},
  				{
  					targets: [7,8],
  					width: '5%',
  					render: function (data, type, full, meta) {
  						return '<center>' + data + '</center>';
  					}
  				},
  			]
  		});
      t.on( 'order.dt search.dt', function () {
        t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
      }).draw();
  	});

  	function do_rekap() {
  		var code = $('#data_agenda_sikap').val();
  		window.open("<?= base_url('kagenda/rekap_sikap_bawahan/'.$this->uri->segment(3))?>");
  	}

  </script>