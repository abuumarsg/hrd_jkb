<div class="content-wrapper">
	<section class="content-header">
		<h1>
			<i class="fa fa-users"></i> Daftar Bawahan
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo base_url('kpages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
			<li><a href="<?php echo base_url('kpages/raport_bawahan');?>"><i class="fas fa-calendar"></i> Agenda Akhir</a></li>
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
								<div class="callout callout-info"><label><i class="fa fa-info-circle"></i>
										Bantuan</label><br>Pilih NIK untuk melihat Raport Akhir <?=$nama_agenda?>
								</div>
								<table id="table_data" class="table table-bordered table-striped table-responsive table-hover"
									width="100%">
									<thead>
  										<tr>
											<th rowspan="2">No.</th>
											<th rowspan="2">NIK</th>
											<th rowspan="2">Nama Karyawan</th>
											<th rowspan="2">Jabatan</th>
											<th rowspan="2">Bagian</th>
											<th rowspan="2">Departemen</th>
											<th rowspan="2">Lokasi Kerja</th>
											<th colspan="3" class="text-center">Nilai Per Periode <?=$kode['tahun']?></th>
										</tr>
										<tr>
                                        <?php 
                                        $count_periode=0;
                                        if (isset($periode_list)) {
                                            $count_periode=count($periode_list);
                                            foreach ($periode_list as $pl) {
                                                echo '<th>'.$pl->nama.'</th>';
                                            }
                                        }
                                        ?>
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
        var max_periode=<?=$count_periode?>;
        var cols=max_periode + 8;
		var t=$('#table_data').DataTable({
			ajax: {
				url: "<?php echo base_url('kagenda/view_raport_bawahan/tahunan')?>",
				type:"POST",
				data:{kode:"<?= $this->codegenerator->encryptChar($kode);?>"}
			},
			scrollX: true,
			processing: true,
			order: [1,'asc'],
			columnDefs: [{
					targets: 0,
					width: '5%',
					render: function (data, type, full, meta) {
						return '<center>' + (meta.row + 1) + '.</center>';
					}
				},
				{
					targets: 1,
					width: '5%',
					render: function (data, type, full, meta) {
						return '<a href="<?php echo base_url("kpages/report_value_bawahan_group/".$this->uri->segment(3));?>/' +full[10] + '" target="_blank">' + data + '</a>';
					}
				},
				{
					targets: [2,3],
					width: '30%',
					render: function (data, type, full, meta) {
						return data;
					}
				},
				// {   
                //     targets: [(cols+1),(cols+2),(cols+3),(cols+4)],
                //     width: '5%',
                //     render: function ( data, type, full, meta ) {
                //         return '<center>'+data+'</center>';
                //     }
                // },
			]
		});
		t.on( 'order.dt search.dt', function () {
			t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
				cell.innerHTML = i+1;
			} );
		}).draw();
	});
	function do_rekap() {
		window.open("<?= base_url('kagenda/rekap_akhir_bawahan_tahunan/'.$this->uri->segment(3))?>/?"+$('#form_filter').serialize());
	}
</script>
