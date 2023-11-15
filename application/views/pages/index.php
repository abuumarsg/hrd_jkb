<!-- Content Wrapper. Contains page content -->
<?php
//$access_dashboard=(!empty($access['access_dashboard']))?$access['access_dashboard']:[];
?>
      <!-- <?php echo '<pre>'; print_r($adm['access']);?> -->
<div class="content-wrapper">
	<section class="content-header">
		<h1> Dashboard <small>Control panel</small> </h1>
		<ol class="breadcrumb">
			<li><a href="#"><i class="fas fa-tachometer-alt"></i> Home</a></li>
			<li class="active">Dashboard</li>
		</ol>
	</section>
	<section class="content">
		<!-- <div id="show_event"></div> -->
		<div class="row">
			<div class="col-lg-3 col-xs-6">
				<div class="small-box bg-aqua">
					<div class="inner">
						<h3><?php echo $jml_emp;?></h3>
						<p>Jumlah Karyawan</p>
					</div>
					<div class="icon">
						<i class="fa fa-users"></i>
					</div>
					<a href="<?php echo base_url('pages/data_karyawan');?>" class="small-box-footer">Detail <i class="fa fa-arrow-circle-right"></i></a>
				</div>
			</div>
			<div class="col-lg-3 col-xs-6">
				<div class="small-box bg-green">
					<div class="inner">
						<h3><?php echo $agd_actv;?><sup style="font-size: 20px"></sup></h3>
						<p>Agenda Aktif</p>
					</div>
					<div class="icon">
						<i class="fa fa-calendar-check-o"></i>
					</div>
					<a href="<?php echo base_url('pages/agenda');?>" class="small-box-footer">Detail <i class="fa fa-arrow-circle-right"></i></a>
				</div>
			</div>
			<div class="col-lg-3 col-xs-6">
				<div class="small-box bg-yellow">
					<div class="inner">
						<h3><?php echo $agd;?></h3>
						<p>Semua Agenda</p>
					</div>
					<div class="icon">
						<i class="fa fa-calendar"></i>
					</div>
					<a href="<?php echo base_url('pages/log_agenda');?>" class="small-box-footer">Detail <i class="fa fa-arrow-circle-right"></i></a>
				</div>
			</div>
			<div class="col-lg-3 col-xs-6">
				<div class="small-box bg-red">
					<div class="inner">
						<h3><?php echo $conc;?></h3>
						<p>Jumlah Rancangan</p>
					</div>
					<div class="icon">
						<i class="fa fa-flask"></i>
					</div>
					<a href="<?php echo base_url('pages/concept');?>" class="small-box-footer">Detail <i class="fa fa-arrow-circle-right"></i></a>
				</div>
			</div>
		</div>
		<div class="row">
			<section class="col-lg-12 connectedSortable">
				<div class="box box-success">
					<div class="nav-tabs-custom">
						<ul class="nav nav-tabs pull-right">
							<li class="active"><a href="#kpiKunci" data-toggle="tab" onclick="do_search_4tahunan()">KPI Kunci</a></li>
							<li><a href="#allKPI" data-toggle="tab" onclick="allKPI()">Semua KPI</a></li>
							<li class="pull-left header"><i class="fa fa-line-chart"></i> Grafik Raport KPI Tahunan</li>
						</ul>
						<div class="tab-content no-padding">
							<div class="chart tab-pane active" id="kpiKunci">
								<div class="row">
									<div class="col-md-12">
										<form id="form_filter_4tahun">
											<div class="row">
												<div class="col-md-12">
													<div class="col-md-4">
														<label>Tahun Awal</label>
														<input type="text" id="tahun_awal_4tahun" value="<?=date('Y')?>" readonly="readonly" placeholder="Tahun Awal" class="form-control from" data-date-format="yyyy" name="tahun_awal">
													</div>
													<div class="col-md-4">
														<label>Tahun Akhir</label>
														<input type="text" id="tahun_akhir_4tahun" value="<?=date('Y')+3?>" readonly="readonly" placeholder="Tahun Akhir" class="form-control to" data-date-format="yyyy" name="tahun_akhir">
													</div>
													<div class="col-md-4">
														<label>Departement</label>
														<select class="form-control select2" id="departemen_filter_4" name="departemen_filter" style="width: 100%;"></select>
													</div>
												</div>  
											</div>  
											<div class="row">
												<div class="col-md-12">
													<div class="col-md-4">
														<label>Bagian</label>
														<select class="form-control select2" id="bagian_filter_4" name="bagian_filter" style="width: 100%;"></select>
													</div>
													<div class="col-md-4">
														<label>Lokasi</label>
														<select class="form-control select2" id="lokasi_filter_4" name="lokasi_filter" style="width: 100%;"></select>
													</div>
													<div class="col-md-4">
														<label>Pilih Karyawan</label>
														<?php
														$sel2=[];
														$ex2 = ['class'=>'form-control select2','style'=>'width:100%;','required'=>'required','id'=>'karyawan_tahunan_4tahun'];
														echo form_dropdown('karyawan_tahunan',$karyawan,$sel2,$ex2);
														?>
													</div>    
												</div>
											</div>
										</form>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12" id="4tahunanChart_cols"><h3 class="text-center">Grafik KPI 4 Tahunan</h3><canvas id="4tahunanChart"></canvas></div>
								</div>
							</div>
							<div class="chart tab-pane" id="allKPI"><br>
								<div class="row">
									<div class="col-md-12">
										<form id="form_filter_allkpi">
											<div class="row">
												<div class="col-md-12">
													<div class="col-md-3">
														<label>Tahun Awal</label>
														<input type="text" id="tahun_awal_allkpi" value="<?=date('Y')?>" readonly="readonly" placeholder="Tahun Awal" class="form-control from allkpi" data-date-format="yyyy" name="tahun_awal">
													</div>
													<div class="col-md-3">
														<label>Tahun Akhir</label>
														<input type="text" id="tahun_akhir_allkpi" value="<?=date('Y')+3?>" readonly="readonly" placeholder="Tahun Akhir" class="form-control to allkpi" data-date-format="yyyy" name="tahun_akhir">
													</div>
													<div class="col-md-3">
														<label>Departement</label>
														<select class="form-control select2 allkpi" id="departemen_allkpi" name="departemen_filter" style="width: 100%;"></select>
													</div>
													<div class="col-md-3">
														<label>Bagian</label>
														<select class="form-control select2 allkpi" id="bagian_filter_allkpi" name="bagian_filter" style="width: 100%;"></select>
													</div>
												</div>  
											</div>  
											<div class="row">
												<div class="col-md-12">
													<div class="col-md-4">
														<label>Lokasi</label>
														<select class="form-control select2 allkpi" id="lokasi_filter_allkpi" name="lokasi_filter" style="width: 100%;"></select>
													</div>
													<div class="col-md-4">
														<label>KPI</label>
														<select class="form-control select2 allkpi" id="kpi_allkpi" name="kpi_filter" style="width: 100%;"></select>
													</div>
													<div class="col-md-4">
														<label>Pilih Karyawan</label>
														<?php
														$sel3=[];
														$ex3 = ['class'=>'form-control select2 allkpi','style'=>'width:100%;','required'=>'required','id'=>'karyawan_tahunan_allkpi'];
														echo form_dropdown('karyawan_allkpi',$karyawan,$sel3,$ex3);
														?>
													</div>    
												</div>
											</div>
										</form>
									</div>
								</div>
								<div class="row">
									<div class="col-md-12" id="allkpiChart_cols"><h3 class="text-center">Grafik KPI 4 Tahunan</h3><canvas id="allkpiChart"></canvas></div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- <div class="box box-warning"> 
					<div class="box-header with-border">
						<i class="fa fa-line-chart"></i>
						<h3 class="box-title">Grafik Raport KPI Tahunan</h3>
						<div class="pull-right box-tools">
							<button class="btn btn-box-tool" onclick="do_search_4tahunan()" data-toggle="tooltip"
								title="Refresh"><i class="fas fa-sync"></i></button>
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
							</button>
							<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
							</button>
						</div>
					</div>
					<div class="box-body">
						<div class="row">
							<div class="col-md-12">
								<form id="form_filter_4tahun">
									<div class="row">
										<div class="col-md-6">
											<label>Tahun Awal</label>
											<input type="text" id="tahun_awal_4tahun" value="<?=date('Y')?>" readonly="readonly" placeholder="Tahun Awal" class="form-control from" data-date-format="yyyy" name="tahun_awal">
										</div>
										<div class="col-md-6">
											<label>Tahun Akhir</label>
											<input type="text" id="tahun_akhir_4tahun" value="<?=date('Y')+3?>" readonly="readonly" placeholder="Tahun Akhir" class="form-control to" data-date-format="yyyy" name="tahun_akhir">
										</div>
									</div>  
									<div class="row">
										<div class="col-md-4">
											<label>Bagian</label>
											<select class="form-control select2" id="bagian_filter_4" name="bagian_filter" style="width: 100%;"></select>
										</div>
										<div class="col-md-4">
											<label>Lokasi</label>
											<select class="form-control select2" id="lokasi_filter_4" name="lokasi_filter" style="width: 100%;"></select>
										</div>
										<div class="col-md-4">
											<label>Pilih Karyawan</label>
											<?php
											$sel2=[];
											$ex2 = ['class'=>'form-control select2','style'=>'width:100%;','required'=>'required','id'=>'karyawan_tahunan_4tahun'];
											echo form_dropdown('karyawan_tahunan',$karyawan,$sel2,$ex2);
											?>
										</div>    
									</div>
								</form>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12" id="4tahunanChart_cols"><h3 class="text-center">Grafik KPI 4 Tahunan</h3><canvas id="4tahunanChart"></canvas></div>
						</div>                        
					</div>
				</div> -->
			</section>
			<!-- <section class="col-lg-6 connectedSortable">
				<div class="box box-warning"> 
					<div class="box-header with-border">
						<i class="fa fa-line-chart"></i>
						<h3 class="box-title">Grafik Raport Tahunan</h3>
						<div class="pull-right box-tools">
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
							</button>
							<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
							</button>
						</div>
					</div>
					<div class="box-body">
						<div class="row">
							<div class="col-md-12">
								<form id="form_filter_tahunan">
								<div class="row">
									<div class="col-md-4">
										<label>Tahun Awal</label>
										<input type="text" id="tahun_awal" placeholder="Tahun Awal" class="form-control from" data-date-format="yyyy" name="tahun_awal">
									</div>
									<div class="col-md-4">
										<label>Tahun Akhir</label>
										<input type="text" id="tahun_akhir" placeholder="Tahun Akhir" class="form-control to" data-date-format="yyyy" name="tahun_akhir">
									</div>
									<div class="col-md-4">
										<label>Pilih Karyawan</label>
										<?php
										$sel2=[];
										$ex2 = ['class'=>'form-control select2','style'=>'width:100%;','required'=>'required','id'=>'karyawan_tahunan'];
										echo form_dropdown('karyawan_tahunan',$karyawan,$sel2,$ex2);
										?>
									</div>                                
								</div>               
								</form>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12" id="tahunanChart_cols"><h3 class="text-center">Grafik Tahunan</h3><canvas id="tahunanChart"></canvas></div>
						</div>                        
					</div>
				</div>
			</section> -->
		</div>
		<div class="row">
			<section class="col-lg-6 connectedSortable">
				<div class="box box-warning" id="gap_area">
					<div class="box-header with-border">
						<i class="fa fa-line-chart"></i>
						<h3 class="box-title">Grafik KPI</h3>
						<div class="pull-right box-tools">
							<button class="btn btn-box-tool" onclick="refreshChart('kpiChart')" data-toggle="tooltip"
								title="Refresh"><i class="fas fa-sync"></i></button>
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
							</button>
							<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
							</button>
						</div>
					</div>
					<div class="box-body">
						<div class="row">
							<div class="col-md-12">
								<form id="form_filter_kpi_new">
									<div class="row">
										<div class="col-md-12">
											<div class="col-md-4">
												<label>Pilih Tahun</label>
												<select class="form-control select2 search_kpi_new" id="tahun_filter_new"
													name="tahun_filter" style="width: 100%;">
													<option></option>
													<?php
														$year = $this->formatter->getYear();
														foreach ($year as $yk => $yv) {
														echo '<option value="'.$yk.'" '.$select.'>'.$yv.'</option>';
														}
													?>
												</select>
											</div>
											<div class="col-md-4">
												<label>Pilih Kuartal</label>
												<select class="form-control select2 search_kpi_new" id="kuartal_filter_new" name="kuartal_filter" style="width: 100%;"></select>
											</div>
											<div class="col-md-4">
												<label>Bagian</label>
												<select class="form-control select2 search_kpi_new" id="bagian_filter_new" name="bagian_filter" style="width: 100%;"></select>
											</div>
										</div>
										<div class="col-md-12">
											<div class="col-md-6">
												<label>Lokasi</label>
												<select class="form-control select2 search_kpi_new" id="lokasi_filter_new" name="lokasi_filter" style="width: 100%;"></select>
											</div>
											<div class="col-md-6">
												<label>Pilih Karyawan</label>
												<?php
													$sel3=[];
													$ex3 = ['class'=>'form-control select2 search_kpi_new','style'=>'width:100%;','required'=>'required','id'=>'karyawan_kpi_new'];
													echo form_dropdown('karyawan_kpi',$karyawan,$sel3,$ex3);
												?>
											</div>
										</div>
									</div>
								</form>
							</div>
						</div>
						<hr>
						<div class="row">
							<div class="col-md-12" id="kpiChartNew_cols">
								<h3 class="text-center">Grafik Penilaian KPI</h3><canvas id="kpiChartNew"></canvas>
							</div>
						</div>
					</div>
				</div>
			</section>
			<section class="col-lg-6 connectedSortable">
				<div class="box box-solid bg-green-gradient">
					<div class="box-header">
						<i class="fa fa-calendar"></i>
						<h3 class="box-title">Calendar</h3>
						<div class="pull-right box-tools">
							<button type="button" class="btn btn-success btn-sm" data-widget="collapse"><i
									class="fa fa-minus"></i>
							</button>
							<button type="button" class="btn btn-success btn-sm" data-widget="remove"><i
									class="fa fa-times"></i>
							</button>
						</div>
					</div>
					<div class="box-body no-padding">
						<div id="calendar" style="width: 100%"></div>
					</div>
				</div>
			</section>
		</div>
		<!-- <div class="row">
			<section class="col-lg-12 connectedSortable">
				<div class="box box-warning" id="gap_area">
					<div class="box-header with-border">
						<i class="fa fa-line-chart"></i>
						<h3 class="box-title">Grafik KPI</h3>
						<div class="pull-right box-tools">
							<button class="btn btn-box-tool" onclick="refreshChart('kpiChart')" data-toggle="tooltip"
								title="Refresh"><i class="fas fa-sync"></i></button>
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
							</button>
							<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
							</button>
						</div>
					</div>
					<div class="box-body">
						<div class="row">
							<div class="col-md-12">
								<form id="form_filter_kpi">
									<div class="row">
										<div class="col-md-4">
											<label>Pilih Tahun</label>
											<select class="form-control select2 search_kpi" id="tahun_filter"
												name="tahun_filter" style="width: 100%;">
												<option></option>
												<?php
													$year = $this->formatter->getYear();
													foreach ($year as $yk => $yv) {
													echo '<option value="'.$yk.'" '.$select.'>'.$yv.'</option>';
													}
												?>
											</select>
										</div>
										<div class="col-md-4">
											<label>Pilih Kuartal</label>
											<select class="form-control select2 search_kpi" id="kuartal_filter"
												name="kuartal_filter" style="width: 100%;"></select>
										</div>
										<div class="col-md-4">
											<label>Pilih Karyawan</label>
											<?php
												$sel3=[];
												$ex3 = ['class'=>'form-control select2 search_kpi','style'=>'width:100%;','required'=>'required','id'=>'karyawan_kpi'];
												echo form_dropdown('karyawan_kpi',$karyawan,$sel3,$ex3);
											?>
										</div>
									</div>
								</form>
							</div>
						</div>
						<hr>
						<div class="row">
							<div class="col-md-12" id="kpiChart_cols">
								<h3 class="text-center">Grafik Penilaian KPI</h3><canvas id="kpiChart"></canvas>
							</div>
						</div>
						<hr>
						<div class="row">
							<div class="col-md-8">
								<h3 class="text-center">Tabel Penilaian KPI</h3>
								<table id="table_data" class="table table-bordered table-striped table-responsive"
									width="100%">
									<thead>
										<tr>
											<th>No.</th>
											<th>KPI</th>
											<th>Definisi</th>
											<th>Sifat</th>
											<th>Jenis</th>
											<th>Target</th>
											<th>Nilai</th>
											<th>GAP</th>
										</tr>
									</thead>
									<tbody>
									</tbody>
								</table>
							</div>
							<div class="col-md-4">
								<h3 class="text-center">Tabel Konversi GAP</h3>
								<table id="table_konversi" class="table table-bordered table-striped table-responsive"
									width="100%">
									<thead>
										<tr>
											<th>Nilai</th>
											<th>Range</th>
											<th>Warna</th>
										</tr>
									</thead>
									<tbody>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</section>
		</div> -->
		<div class="row">
			<section class="col-lg-12 connectedSortable">
				<div class="box box-success">
					<div class="nav-tabs-custom">
						<ul class="nav nav-tabs pull-right">
							<li class="active"><a href="#table_turnover" data-toggle="tab" onclick="table_turnover()">Turn Over Karyawan</a></li>
							<li><a href="#datein-chart" data-toggle="tab" onclick="refreshChart('dateInChart')">Tanggal Masuk</a></li>
							<li><a href="#dateinmonth-chart" data-toggle="tab" onclick="refreshChart('dateInMonthChart')">Bulan Masuk</a></li>
							<li class="pull-left header"><i class="fas fa-users"></i> Grafik Karyawan</li>
						</ul>
						<div class="tab-content no-padding">
							<div class="chart tab-pane active" id="table_turnover">
								<div class="row">
									<div class="col-md-12">
										<form id="form_turnover">
											<div class="col-md-4">
												<label>Bulan</label>
												<select class="form-control select2" name="bulan" id="turn_bulan" style="width: 100%;" required="required">
													<?php
													$bulan_for = $this->formatter->getMonth();
													foreach ($bulan_for as $buf => $valf) {
														echo '<option value="'.$buf.'">'.$valf.'</option>';
													}
													?>
												</select>
											</div>
											<div class="col-md-4">
												<div class="form-group">
													<label>Tahun</label>
														<input type="text" name="tahun" placeholder="Tahun" id="turn_tahun" class="form-control tahun" data-date-format="yyyy">
												</div>
											</div>
										</form>
										<div class="col-md-2">
											<div class="form-group">
												<div class="pull-right" style="padding-top:8px;"><br>
													<button type="button" onclick="tableTurnover('search')" class="btn btn-success"><i class="fa fa-eye"></i> Lihat Data</button>
												</div>
											</div>
										</div>
										<div class="col-md-2">
											<div class="form-group">
												<div class="pull-right" style="padding-top:8px;"><br>
													<button type="button" onclick="cetakTurnover()" class="btn btn-danger"><i class="fas fa-file-pdf fa-fw"></i> Cetak Data</button>
												</div>
											</div>
										</div>
									</div>
									<hr>
									<div class="col-md-12">
										<div class="col-md-6">
											<div class="row form-box-group">
												<span class="form-box-group-title">TURN OVER KARYAWAN MASUK</span>
												<table id="table_turn_in" class="table table-bordered table-striped table-responsive" width="100%">
													<thead>
														<tr>
															<th>No.</th>
															<th>Nama</th>
															<th>Bagian</th>
															<th>Lokasi Kerja</th>
															<th>Tanggal Masuk</th>
														</tr>
													</thead>
													<tbody>
													</tbody>
												</table>
											</div>
										</div>
										<div class="col-md-6">
											<div class="row form-box-group">
												<span class="form-box-group-title">TURN OVER KARYAWAN KELUAR</span>
												<table id="table_turn_out" class="table table-bordered table-striped table-responsive" width="100%">
													<thead>
														<tr>
															<th>No.</th>
															<th>Nama</th>
															<th>Bagian</th>
															<th>Lokasi Kerja</th>
															<th>Tanggal Keluar</th>
															<th>Alasan</th>
														</tr>
													</thead>
													<tbody>
													</tbody>
												</table>
											</div>
										</div>
									</div>
									<!-- <div class="col-md-12" id="dateInChart_cols">
										<canvas id="dateInChart"></canvas>
									</div> -->
								</div>
							</div>
							<div class="chart tab-pane" id="datein-chart">
								<div class="row">
									<div class="col-md-12" id="dateInChart_cols"><canvas id="dateInChart"></canvas>
									</div>
								</div>
							</div>
							<div class="chart tab-pane" id="dateinmonth-chart"><br>
								<div class="row">
									<div class="col-md-12">
										<form id="form_dateinmonth">
											<div class="col-md-6">
												<div class="form-group">
													<label class="col-sm-3 control-label">Lokasi Kerja</label>
													<div class="col-sm-9">
														<select class="form-control select2 search_kar" name="chart_kar_loker" id="data_lokasibaru_add" required="required" style="width: 100%;"></select>
													</div>
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label class="col-sm-3 control-label">Tahun</label>
													<div class="col-sm-9">
														<input type="text" name="chart_kar_year" placeholder="Tahun"
															class="form-control tahun search_kar"
															data-date-format="yyyy" name="tahun_awal">
													</div>
												</div>
											</div>
										</form>
										<div class="col-md-12" id="dateInMonthChart_cols"><canvas id="dateInMonthChart"></canvas></div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>
		</div>
		<div class="row">
			<section class="col-lg-6 connectedSortable">
				<div class="box box-danger">
					<div class="nav-tabs-custom">
						<ul class="nav nav-tabs pull-right">
							<li class="active"><a href="#loker-chart" data-toggle="tab"
									onclick="refreshChart('lokerChart')">Lokasi Kerja</a></li>
							<li><a href="#kelamin-chart" data-toggle="tab" onclick="refreshChart('jenKelChart')">Jenis
									Kelamin</a></li>
							<li><a href="#statusk-chart" data-toggle="tab"
									onclick="refreshChart('statusKarChart')">Status Karyawan</a></li>
							<li><a href="#agama-chart" data-toggle="tab" onclick="refreshChart('agamaChart')">Agama</a>
							</li>
							<li class="pull-left header"><i class="fa fa-line-chart"></i> Grafik</li>
						</ul>
						<div class="tab-content no-padding">
							<div class="chart tab-pane active" id="loker-chart">
								<div class="row">
									<div class="col-md-12" id="lokerChart_cols"><canvas id="lokerChart"></canvas></div>
								</div>
							</div>
							<div class="chart tab-pane" id="kelamin-chart">
								<div class="row">
									<div class="col md-12">
										<form id="form_kelamin"><br>
											<div class="col-sm-1"></div>
											<div class="col-sm-10">
												<div class="form-group">
													<label class="col-sm-3 control-label">Lokasi Kerja</label>
													<div class="col-sm-9">
														<select class="form-control select2" name="loker_jk"
															id="loker_jk" required="required"
															style="width: 100%;"></select>
													</div>
												</div>
											</div>
										</form>
									</div>
									<div class="col-md-12" id="jenKelChart_cols"><canvas id="jenKelChart"></canvas>
									</div>
								</div>
							</div>
							<div class="chart tab-pane" id="statusk-chart">
								<div class="row">
									<div class="col md-12">
										<form id="form_status"><br>
											<div class="col-sm-1"></div>
											<div class="col-sm-10">
												<div class="form-group">
													<label class="col-sm-3 control-label">Lokasi Kerja</label>
													<div class="col-sm-9">
														<select class="form-control select2" name="loker_status"
															id="loker_status" required="required"
															style="width: 100%;"></select>
													</div>
												</div>
											</div>
										</form>
									</div>
									<div class="col-md-12" id="statusKarChart_cols"><canvas
											id="statusKarChart"></canvas></div>
								</div>
							</div>
							<div class="chart tab-pane" id="agama-chart">
								<div class="row">
									<div class="col md-12">
										<form id="form_agama"><br>
											<div class="col-sm-1"></div>
											<div class="col-sm-10">
												<div class="form-group">
													<label class="col-sm-3 control-label">Lokasi Kerja</label>
													<div class="col-sm-9">
														<select class="form-control select2" name="loker_agama"
															id="loker_agama" required="required"
															style="width: 100%;"></select>
													</div>
												</div>
											</div>
										</form>
									</div>
									<div class="col-md-12" id="agamaChart_cols"><canvas id="agamaChart"></canvas></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>
			<section class="col-lg-6 connectedSortable">
				<div class="box box-primary">
					<div class="nav-tabs-custom">
						<ul class="nav nav-tabs pull-right">
							<li class="active"><a href="#bagian-chart" data-toggle="tab"
									onclick="refreshChart('bagianChart')">Bagian Karyawan</a></li>
							<li><a href="#pendidikan-chart" data-toggle="tab"
									onclick="refreshChart('pendidikanChart')">Pendidikan</a></li>
							<li class="pull-left header"><i class="fa fa-line-chart"></i> Grafik Bagian & Pendidikan
							</li>
						</ul>
						<div class="tab-content no-padding">
							<div class="chart tab-pane active" id="bagian-chart">
								<div class="row">
									<div class="col md-12">
										<form id="form_bagian"><br>
											<div class="col-sm-1"></div>
											<div class="col-sm-10">
												<div class="form-group">
													<label class="col-sm-3 control-label">Lokasi Kerja</label>
													<div class="col-sm-9">
														<select class="form-control select2" name="loker_bagian"
															id="loker_bagian" required="required"
															style="width: 100%;"></select>
													</div>
												</div>
											</div>
										</form>
									</div>
									<div class="col-md-12" id="bagianChart_cols"><canvas id="bagianChart"></canvas>
									</div>
								</div>
							</div>
							<div class="chart tab-pane" id="pendidikan-chart">
								<div class="row">
									<div class="col md-12">
										<form id="form_pendidikan"><br>
											<div class="col-sm-1"></div>
											<div class="col-sm-10">
												<div class="form-group">
													<label class="col-sm-3 control-label">Lokasi Kerja</label>
													<div class="col-sm-9">
														<select class="form-control select2" name="loker_pendidikan"
															id="loker_pendidikan" required="required"
															style="width: 100%;"></select>
													</div>
												</div>
											</div>
										</form>
									</div>
									<div class="col-md-12" id="pendidikanChart_cols"><canvas
											id="pendidikanChart"></canvas></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>
		</div>
		<div class="row">
			<!-- <section class="col-lg-4 connectedSortable">
				<div class="box box-info">
					<div class="box-header">
						<i class="fa fa-envelope"></i>
						<h3 class="box-title">Quick Email</h3>
						<div class="pull-right box-tools">
							<button type="button" class="btn btn-info btn-sm" data-widget="remove" data-toggle="tooltip"
								title="Remove">
								<i class="fa fa-times"></i></button>
						</div>
					</div>
					<div class="box-body">
						<form action="#" method="post">
							<div class="form-group">
								<input type="email" class="form-control" name="emailto" placeholder="Email to:">
							</div>
							<div class="form-group">
								<input type="text" class="form-control" name="subject" placeholder="Subject">
							</div>
							<div>
								<textarea class="textarea" placeholder="Message"
									style="width: 100%; height: 125px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"></textarea>
							</div>
						</form>
					</div>
					<div class="box-footer clearfix">
						<button type="button" class="pull-right btn btn-default" id="sendEmail">Send
							<i class="fa fa-arrow-circle-right"></i></button>
					</div>
				</div>
			</section> -->
			<section class="col-lg-12 connectedSortable">
				<div class="box box-warning">
					<div class="nav-tabs-custom">
						<ul class="nav nav-tabs pull-right">
							<li class="active"><a href="#izinPre-chart" data-toggle="tab" onclick="refreshChart('izinPreChart')">Persentase Presensi</a></li>
							<li><a href="#izinBag-chart" data-toggle="tab" onclick="refreshChart('izinBagChart')">Izin & Cuti Bagian</a></li>
							<li><a href="#izin-chart" data-toggle="tab" onclick="refreshChart('izinChart')">Izin & Cuti</a></li>
							<li><a href="#peringatan-chart" data-toggle="tab" onclick="refreshChart('peringatanChart')">Peringatan</a></li>
							<li class="pull-left header"><i class="fa fa-line-chart"></i> Grafik Izin Cuti & Peringatan
							</li>
						</ul>
						<div class="tab-content no-padding">
							<div class="active chart tab-pane" id="izinPre-chart"><br>
								<div class="row">
									<form id="form_izin_pre">
										<div class="col-md-12">
											<div class="col-md-4">
												<div class="form-group">
													<label>Bagian</label>
													<select class="form-control select2 search_izinpre" name="bagian_izinpre"
														id="bagian_izinpre" required="required" style="width: 100%;">
													</select>
												</div>
											</div>
											<div class="col-md-3">
												<label>Bulan</label>
												<select class="form-control select2" name="bulan" id="data_bulan" style="width: 100%;" required="required">
													<?php
													$bulan_for = $this->formatter->getMonth();
													foreach ($bulan_for as $buf => $valf) {
														echo '<option value="'.$buf.'">'.$valf.'</option>';
													}
													?>
												</select>
											</div>
											<div class="col-md-3">
												<div class="form-group">
													<label>Tahun</label>
														<input type="text" name="tahun" placeholder="Tahun"
															class="form-control tahun" data-date-format="yyyy">
												</div>
											</div>
											<div class="col-md-2">
												<div class="form-group">
													<div class="pull-right"><br>
														<button type="button" onclick="empIzinPre()" class="btn btn-success"><i class="fa fa-eye"></i> Lihat Grafik</button>
													</div>
												</div>
											</div>
										</div>
										<div class="col-md-12">
											<div class="col-md-12" id="izinPreChart_cols"><canvas id="izinPreChart"></canvas>
											</div>
										</div>
									</form>
								</div>
							</div>
							<div class="chart tab-pane" id="izinBag-chart"><br>
								<div class="row">
									<form id="form_izin_bagian">
										<div class="col-md-12">
											<div class="col-md-4">
												<div class="form-group">
													<label>Bagian</label>
													<select class="form-control select2 search_izinBag" name="bagian_izinBag"
														id="bagian_izinBag" required="required" onchange="get_selet_emp(this.value)"
														style="width: 100%;">
													</select>
												</div>
											</div>
											<div class="col-md-3">
												<div class="form-group">
													<label>Karyawan</label>
													<select class="form-control select2" name="kar_izinBag[]" id="kar_izinBag" style="width: 100%;" multiple="multiple" required="required">
														<option></option>
													</select>
												</div>
											</div>
											<div class="col-md-3">
												<div class="form-group">
													<label>Tanggal</label>
													<div class="has-feedback">
														<span class="fa fa-calendar form-control-feedback"></span>
														<input type="text" class="form-control date-range-notime search_izinBag" id="tanggal_izin" name="tanggal_izinBag" placeholder="Tanggal">
													</div>
												</div>
											</div>
											<div class="col-md-2">
												<div class="form-group">
													<div class="pull-right"><br>
														<button type="button" onclick="empIzinBag()" class="btn btn-success"><i class="fa fa-eye"></i> Lihat Grafik</button>
													</div>
												</div>
											</div>
										</div>
										<div class="col-md-12">
											<div class="col-md-12" id="izinBagChart_cols"><canvas id="izinBagChart"></canvas>
											</div>
										</div>
									</form>
								</div>
							</div>
							<div class="chart tab-pane" id="izin-chart"><br>
								<div class="row">
									<form id="form_izin">
										<div class="col-md-12">
											<div class="col-md-6">
												<div class="form-group">
													<label>Bagian</label>
													<select class="form-control select2 search_izin" name="bagian_izin"
														id="bagian_izin" required="required"
														style="width: 100%;">
													</select>
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label>Lokasi Kerja</label>
													<select class="form-control select2 search_izin" name="loker_izin"
														id="loker_izin" required="required"
														style="width: 100%;">
													</select>
												</div>
											</div>
										</div>
										<div class="col-md-12">
											<div class="col-md-6">
												<div class="form-group">
													<label>Pilih Karyawan</label>
													<?php
														$sel3x=[];
														$ex3x = ['class'=>'form-control select2 search_izin','style'=>'width:100%;','required'=>'required','id'=>'karyawan_izin'];
														echo form_dropdown('karyawan_izin',$karyawan,$sel3x,$ex3x);
													?>
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label>Tahun</label>
													<input type="text" name="tahun_izin" placeholder="Tahun"
														class="form-control tahun search_izin" data-date-format="yyyy"
														name="tahun_awal">
												</div>
											</div>
										</div>
										<div class="col-md-12">
											<div class="col-md-12" id="izinChart_cols"><canvas id="izinChart"></canvas>
											</div>
										</div>
									</form>
								</div>
							</div>
							<div class="chart tab-pane" id="peringatan-chart"><br>
								<div class="row">
									<div class="col-md-12">
										<form id="form_peringatan">
											<div class="col-md-6">
												<div class="form-group">
													<label>Lokasi Kerja</label>
													<select class="form-control select2 search_peringatan"
														name="loker_peringatan" id="data_lokasibaru_add"
														required="required" style="width: 100%;"></select>
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label>Tahun</label>
													<input type="text" name="tahun_peringatan" placeholder="Tahun"
														class="form-control tahun search_peringatan"
														data-date-format="yyyy" name="tahun_awal">
												</div>
											</div>
										</form>
										<div class="col-md-12" id="peringatanChart_cols"><canvas
												id="peringatanChart"></canvas></div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>
		</div>
	</section>
</div>
<script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
<script type="text/javascript">
	var url_select = "<?php echo base_url('global_control/select2_global');?>";
	$(document).ready(function () {
		load_event();
		var search_bag = $('#form_bagian').serialize();
		var search_izinBag = $('#form_izin_bagian').serialize();
		drawChart("<?php echo base_url('chart/chart_datein_employee');?>", "bar", "dateInChart", true);
		drawChart("<?php echo base_url('chart/chart_loker_employee');?>", "pie", "lokerChart", true);
		drawChart("<?php echo base_url('chart/chart_bagian');?>", "horizontalBar", "bagianChart", true, {
			param: search_bag
		});
		drawChart("<?php echo base_url('chart/chart_izin_cuti_bagian');?>", "bar", "izinBagChart", true, {
			param: search_izinBag
		});
		select_data('data_lokasibaru_add', url_select, 'master_loker', 'kode_loker', 'nama');
		select_data('loker_jk', url_select, 'master_loker', 'kode_loker', 'nama');
		select_data('loker_status', url_select, 'master_loker', 'kode_loker', 'nama');
		select_data('loker_agama', url_select, 'master_loker', 'kode_loker', 'nama');
		select_data('loker_bagian', url_select, 'master_loker', 'kode_loker', 'nama');
		select_data('loker_pendidikan', url_select, 'master_loker', 'kode_loker', 'nama');
		select_data('loker_izin', url_select, 'master_loker', 'kode_loker', 'nama');
		select_data('loker_peringatan', url_select, 'master_loker', 'kode_loker', 'nama');
		$('.search_kar').change(function () {
			empInOut();
		});
		$('#loker_status').change(function () {
			statusEmployee();
		});
		$('#loker_jk').change(function () {
			jkEmployee();
		});
		$('#loker_agama').change(function () {
			agamaEmployee();
		});
		$('.search_izin').change(function () {
			empIzin();
		});
		$('.search_peringatan').change(function () {
			empPeringatan();
		});
		$('#loker_bagian').change(function () {
			empBagian();
		});
		$('#loker_pendidikan').change(function () {
			empPendidikan();
		});
		getSelect2('<?php echo base_url('global_control/select2_custom/master_periode_penilaian');?>','kuartal_filter',{table:'<?php echo $this->codegenerator->encryptChar("master_periode_penilaian");?>',});
		select_data('loker_filter',url_select,'master_loker','kode_loker','nama');
		select_data('departemen_filter',url_select,'master_departement','kode_departement','nama');
		unsetoption('bagian_filter',['BAG001','BAG002']);
		unsetoption('departemen_filter',['DEP001']);
		$('.search_kpi').change(function() {
			do_search_kpi();
		});
		// do_search_kpi();
      	// getYears();
      	// getYears4();
		$('.search_kpi_new').change(function() {
			do_search_kpi_new();
		});
		getSelect2('<?php echo base_url('global_control/select2_custom/master_periode_penilaian');?>','kuartal_filter_new',{table:'<?php echo $this->codegenerator->encryptChar("master_periode_penilaian");?>',});
		select_data('lokasi_filter_new',url_select,'master_loker','kode_loker','nama');
		// do_search_kpi_new();
		select_data('lokasi_filter_4',url_select,'master_loker','kode_loker','nama');
		select_data('lokasi_filter_4',url_select,'master_loker','kode_loker','nama');
		select_data('kpi_allkpi',url_select,'master_kpi','kode_kpi','kpi','no','kpi','ASC');
		select_data('departemen_filter_4',url_select,'master_level_struktur','kode_level_struktur','nama','no','nama','ASC');
		select_data('departemen_allkpi',url_select,'master_level_struktur','kode_level_struktur','nama','no','nama','ASC');
		select_data('lokasi_filter_allkpi',url_select,'master_loker','kode_loker','nama');
      	getSelect2("<?php echo base_url('master/master_bagian/get_select2')?>",'bagian_filter_allkpi,#bagian_filter_4,#bagian_filter_new,#bagian_filter,#bagian_izin,#bagian_izinpre,#bagian_izinBag');
		tableTurnover('n');


		// getSelect2("<?php //echo base_url('presensi/data_presensi/employee')?>",'kar_izinBag');
		// select_data('bagian_filter_4',url_select,'master_bagian','kode_bagian','nama');
		// select_data('bagian_filter_allkpi',url_select,'master_bagian','kode_bagian','nama');
		// select_data('bagian_filter_new',url_select,'master_bagian','kode_bagian','nama');
		// select_data('bagian_filter',url_select,'master_bagian','kode_bagian','nama');
      	<?php //if(in_array('GAP',$access_dashboard)){ ?>
		<?php //}?>
	});

	function refreshChart(id) {
		var urlx;
		var range;
		var type;
		if (id == 'dateInChart') {
			urlx = "<?php echo base_url('chart/chart_datein_employee');?>";
			range = true;
			type = 'bar';
		} else if (id == 'genderChart') {
			urlx = "<?php echo base_url('chart/chart_gender_employee');?>";
			range = false;
			type = 'pie';
		} else if (id == 'lokerChart') {
			urlx = "<?php echo base_url('chart/chart_loker_employee');?>";
			range = false;
			type = 'pie';
		} else if (id == 'bagChart') {
			urlx = "<?php echo base_url('chart/chart_bagian_employee');?>";
			range = false;
			type = 'horizontalBar';
		} else if (id == 'dateInMonthChart') {
			var search = $('#form_dateinmonth').serialize();
			urlx = "<?php echo base_url('chart/chart_dateinout_month');?>";
			range = false;
			type = 'line';
		} else if (id == 'statusKarChart') {
			var search = $('#form_status').serialize();
			urlx = "<?php echo base_url('chart/chart_status_kar');?>";
			range = false;
			type = 'pie';
		} else if (id == 'jenKelChart') {
			var search = $('#form_kelamin').serialize();
			urlx = "<?php echo base_url('chart/chart_jenis_kelamin');?>";
			range = false;
			type = 'pie';
		} else if (id == 'agamaChart') {
			var search = $('#form_agama').serialize();
			urlx = "<?php echo base_url('chart/chart_agama');?>";
			range = false;
			type = 'pie';
		} else if (id == 'izinBagChart') {
			var search = $('#form_izin_bagian').serialize();
			urlx = "<?php echo base_url('chart/chart_izin_cuti_bagianx');?>";
			range = false;
			type = 'bar';
		} else if (id == 'izinPreChart') {
			var search = $('#form_izin_pre').serialize();
			urlx = "<?php echo base_url('chart/chart_izin_cuti_bagian');?>";
			range = false;
			type = 'bar';
		} else if (id == 'izinChart') {
			var search = $('#form_izin').serialize();
			urlx = "<?php echo base_url('chart/chart_izin_cuti');?>";
			range = false;
			type = 'line';
		} else if (id == 'peringatanChart') {
			var search = $('#form_peringatan').serialize();
			urlx = "<?php echo base_url('chart/chart_peringatan');?>";
			range = false;
			type = 'line';
		} else if (id == 'bagianChart') {
			var search = $('#form_bagian').serialize();
			urlx = "<?php echo base_url('chart/chart_bagian');?>";
			range = false;
			type = 'horizontalBar';
		} else if (id == 'pendidikanChart') {
			var search = $('#form_pendidikan').serialize();
			urlx = "<?php echo base_url('chart/chart_pendidikan');?>";
			range = false;
			type = 'bar';
		} else if (id == 'kpiKunci') {
			var search = $('#form_filter_4tahun').serialize();
			urlx = "<?php echo base_url('chart/kpi_4tahunan/backoffice');?>";
			range = false;
			type = 'bar';
		} else if (id == 'allKPI') {
			var search = $('#form_filter_allkpi').serialize();
			urlx = "<?php echo base_url('chart/chart_pendidikan');?>";
			range = false;
			type = 'bar';
		}
		drawChart(urlx, type, id, range, {
			param: search
		});
	}

	function empInOut() {
		var search = $('#form_dateinmonth').serialize();
		drawChart("<?php echo base_url('chart/chart_dateinout_month');?>", "line", "dateInMonthChart", false, {
			param: search
		});
	}

	function statusEmployee() {
		var search = $('#form_status').serialize();
		drawChart("<?php echo base_url('chart/chart_status_kar');?>", "pie", "statusKarChart", false, {
			param: search
		});
	}

	function jkEmployee() {
		var search = $('#form_kelamin').serialize();
		drawChart("<?php echo base_url('chart/chart_jenis_kelamin');?>", "pie", "jenKelChart", false, {
			param: search
		});
	}

	function agamaEmployee() {
		var search = $('#form_agama').serialize();
		drawChart("<?php echo base_url('chart/chart_agama');?>", "pie", "agamaChart", false, {
			param: search
		});
	}

	function empIzinBag() {
		var search = $('#form_izin_bagian').serialize();
		drawChart("<?php echo base_url('chart/chart_izin_cuti_bagianx');?>", "bar", "izinBagChart", false, {
			param: search
		});
	}
	function empIzinPre() {
		var search = $('#form_izin_pre').serialize();
		drawChart("<?php echo base_url('chart/chart_izin_cuti_bagian');?>", "bar", "izinPreChart", false, {
			param: search
		});
	}
	function empIzin() {
		var search = $('#form_izin').serialize();
		drawChart("<?php echo base_url('chart/chart_izin_cuti');?>", "line", "izinChart", false, {
			param: search
		});
	}

	function empPeringatan() {
		var search = $('#form_peringatan').serialize();
		drawChart("<?php echo base_url('chart/chart_peringatan');?>", "line", "peringatanChart", false, {
			param: search
		});
	}

	function empBagian() {
		var search = $('#form_bagian').serialize();
		drawChart("<?php echo base_url('chart/chart_bagian');?>", "horizontalBar", "bagianChart", false, {
			param: search
		});
	}

	function empPendidikan() {
		var search = $('#form_pendidikan').serialize();
		drawChart("<?php echo base_url('chart/chart_pendidikan');?>", "bar", "pendidikanChart", false, {
			param: search
		});
	}
	function do_search_kpi() {
		var search = $('#form_filter_kpi').serialize();
		drawChart("<?php echo base_url('chart/dashboard_kpi/bo');?>", "horizontalBar", "kpiChart", true, {
			search_param: search
		});
		$('#table_data').DataTable({
			ajax: {
				url: "<?php echo base_url('chart/dashboard_kpi_tabel/bo')?>",
				type: 'POST',
				data: {
					search_param: search
				}
			},
			scrollX: true,
			columnDefs: [{
					targets: 0,
					width: '5%',
					render: function (data, type, full, meta) {
						return '<center>' + (meta.row + 1) + '.</center>';
					}
				},
				{
					targets: 1,
					width: '25%',
					render: function (data, type, full, meta) {
						return data;
					}
				},
				{
					targets: 2,
					width: '35%',
					render: function (data, type, full, meta) {
						return data;
					}
				},
				{
					targets: 5,
					width: '10%',
					render: function (data, type, full, meta) {
						return '<center>' + data + '</center>';
					}
				},
				{
					targets: 6,
					width: '10%',
					render: function (data, type, full, meta) {
						return '<center>' + data + '</center>';
					}
				},
				{
					targets: 7,
					width: '10%',
					render: function (data, type, full, meta) {
						return '<center>' + data + '</center>';
					}
				},
			],
			"bDestroy": true,
		});
		$('#table_konversi').DataTable({
			ajax: {
				url: "<?php echo base_url('agenda/konversi_gap')?>",
				type: 'POST',
			},
			order: [
				[1, 'asc']
			],
			scrollX: true,
			searching: false,
			columnDefs: [{
				targets: 2,
				width: '7%',
				render: function (data, type, full, meta) {
					return '<center>' + data + '</center>';
				}
			}, ],
			"bDestroy": true,
		});
	}
	function getYears(){
		$('#tahun_awal').change(function() {
			$('#tahun_akhir').val(getYearInterval(this.value,true,4));
			do_search_tahunan();
		});
		$('#tahun_akhir').change(function() {
			do_search_tahunan();
		});
		$('#karyawan_tahunan').change(function() {
			do_search_tahunan();
		});
	}
	function getYearInterval(fst,plus,range = 5){
		range = range-1;
		if (plus) {
			var val=parseInt(fst)+parseInt(range);
		}else{
			var val=parseInt(fst)-parseInt(range);
		}
		return val;
	}
	function do_search_tahunan() {
		var search=$('#form_filter_tahunan').serialize();
		drawChart("<?php echo base_url('chart/dashboard_tahunan/backoffice');?>","line","tahunanChart",false,{search_param:search});
	}
	function getYears4(){
		$('#tahun_awal_4tahun').change(function() {
			$('#tahun_akhir_4tahun').val(getYearInterval(this.value,true,4));
			do_search_4tahunan();
		});
		$('#tahun_akhir_4tahun').change(function() {
			$('#tahun_awal_4tahun').val(getYearInterval(this.value,false,4));
			do_search_4tahunan();
		});
		$('#karyawan_tahunan_4tahun').change(function() {
			do_search_4tahunan();
		});
		$('#bagian_filter_4').change(function() {
			do_search_4tahunan();
		});
		$('#lokasi_filter_4').change(function() {
			do_search_4tahunan();
		});
		$('#departemen_filter_4').change(function() {
			do_search_4tahunan();
		});
		do_search_4tahunan();
	}
	function allKPI(){
		$('#tahun_awal_allkpi').change(function() {
			$('#tahun_akhir_allkpi').val(getYearInterval(this.value,true,4));
			do_search_allkpi();
		});
		$('#tahun_akhir_allkpi').change(function() {
			$('#tahun_awal_allkpi').val(getYearInterval(this.value,false,4));
			do_search_allkpi();
		});
		$('.allkpi').change(function() {
			do_search_allkpi();
		});
		do_search_allkpi();
	}
	function do_search_4tahunan() {
		var search=$('#form_filter_4tahun').serialize();
		drawChart("<?php echo base_url('chart/kpi_4tahunan/backoffice');?>","bar","4tahunanChart",false,{search_param:search});
	}
	function do_search_allkpi() {
		var search=$('#form_filter_allkpi').serialize();
		drawChart("<?php echo base_url('chart/kpi_4tahunan_allkpi/backoffice');?>","bar","allkpiChart",false,{search_param:search});
	}
	function do_search_kpi_new() {
		var search = $('#form_filter_kpi_new').serialize();
		drawChart("<?php echo base_url('chart/dashboard_kpi_new/bo');?>", "horizontalBar", "kpiChartNew", true, {
			search_param: search
		});
	}
	function get_selet_emp(kode) {
		var data={kode_bagian:kode};
		var callback=getAjaxData("<?php echo base_url('chart/view_select')?>",data);
		$('#kar_izinBag').html(callback);
	}
	function tableTurnover(param)
	{
		var form = $('#form_turnover').serialize();
		$('#table_turn_in').DataTable().destroy();
		$('#table_turn_in').DataTable( {
			ajax: {
				url: "<?php echo base_url('chart/getTurnOverKaryawan/view_in/')?>",
				type: 'POST',
				data:{form:form,param:param}
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
			]
		});
		$('#table_turn_out').DataTable().destroy();
		$('#table_turn_out').DataTable( {
			ajax: {
				url: "<?php echo base_url('chart/getTurnOverKaryawan/view_out/')?>",
				type: 'POST',
				data:{form:form,param:param}
			},
			scrollX: true,
			columnDefs: [
			{   targets: 0, 
				width: '5%',
				render: function ( data, type, full, meta ) {
					return '<center>'+(meta.row+1)+'.</center>';
				}
			},
			{   targets: 5,
				width: '10%',
				render: function ( data, type, full, meta ) {
					return '<center>'+data+'</center>';
				}
			},
			]
		});

	}
	function cetakTurnover(){
		var bulan = $('#turn_bulan').val();
		var tahun = $('#turn_tahun').val();
		if (bulan == '') {
			notValidParamxCustom('Harap Pilih Bulan !');
		} else if (tahun == '') {
			notValidParamxCustom('Harap Pilih Tahun !');
		}else{
			$.redirect("<?php echo base_url('chart/cetak_turnover'); ?>",  { data_filter: $('#form_turnover').serialize() }, "POST", "_blank");
		}
	}
</script>
