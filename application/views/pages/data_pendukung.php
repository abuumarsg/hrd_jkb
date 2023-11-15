<div class="content-wrapper">
	<section class="content-header">
		<h1>
			<i class="fa fas fa-calendar-plus fa-fw"></i> Data
			<small> Data Pendukung</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fa fas fa-tachometer-alt"></i> Dashboard</a></li>
			<li class="active"><i class="fa fas fa-calendar-plus fa-fw"></i> Data Pendukung</li>
		</ol>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-info">
					<div class="box-header with-border">
						<h3 class="box-title"><i class="fa fas fa-calendar-plus fa-fw"></i> Data Pendukung</h3>
						<div class="box-tools pull-right">
							<button class="btn btn-box-tool" onclick="reload_table('table_data')" data-toggle="tooltip" title="Refresh Table"><i class="fas fa-sync"></i></button>
							<button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
							<button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i class="fa fa-times"></i></button>
						</div>
					</div>
					<div class="box-body">
						<div class="nav-tabs-custom">
							<ul class="nav nav-tabs">
								<?php if (in_array($access['l_ac']['ritasi'], $access['access'])) { ?>
									<li class="<?php echo ($usage == 'data_ritasi' || empty($usage)) ? 'active' : ''; ?>"><a href="<?php echo base_url('pages/data_pendukung/data_ritasi'); ?>">Data Ritasi</a></li>
								<?php } ?>
								<?php if (in_array($access['l_ac']['pendukung_setting_tunjangan'], $access['access'])) { ?>
									<li class="<?php echo ($usage == 'data_setting_tunjangan' || $usage == 'master_tunjangan') ? 'active' : ''; ?>" style="background-color:yellow;"><a href="<?php echo base_url('pages/data_pendukung/data_setting_tunjangan'); ?>">Setting Tunjangan</a></li>
									<li class="<?php echo ($usage == 'data_tunjangan') ? 'active' : ''; ?>" style="background-color:yellow;"><a href="<?php echo base_url('pages/data_pendukung/data_tunjangan'); ?>">Data Tunjangan</a></li>
								<?php } ?>
								<?php if (in_array($access['l_ac']['pendukung_insentif'], $access['access'])) { ?>
									<li class="<?php echo ($usage == 'data_insentif') ? 'active' : ''; ?>" style="background-color:red;"><a href="<?php echo base_url('pages/data_pendukung/data_insentif'); ?>">Data Insentif</a></li>
								<?php } ?>
								<?php if (in_array($access['l_ac']['pendukung_bpjs'], $access['access'])) { ?>
									<li class="<?php echo ($usage == 'data_bpjs') ? 'active' : ''; ?>"><a href="<?php echo base_url('pages/data_pendukung/data_bpjs'); ?>">Data BPJS</a></li>
								<?php } ?>
								<?php if (in_array($access['l_ac']['pendukung_pinjaman'], $access['access'])) { ?>
									<li class="<?php echo ($usage == 'data_pinjaman' || $usage == 'view_angsuran') ? 'active' : ''; ?>"><a href="<?php echo base_url('pages/data_pendukung/data_pinjaman'); ?>">Data Pinjaman</a></li>
								<?php } ?>
								<?php if (in_array($access['l_ac']['pendukung_denda'], $access['access'])) { ?>
									<li class="<?php echo ($usage == 'data_denda') ? 'active' : ''; ?>" style="background-color:red;"><a href="<?php echo base_url('pages/data_pendukung/data_denda'); ?>">Data Denda</a></li>
								<?php } ?>
								<?php if (in_array($access['l_ac']['pendukung_lain'], $access['access'])) { ?>
									<li class="<?php echo ($usage == 'lain_lain') ? 'active' : ''; ?>"><a href="<?php echo base_url('pages/data_pendukung/lain_lain'); ?>">Lain-Lain</a></li>
								<?php } ?>
							</ul>
							<div class="tab-content" style="padding: 12px 0px 0px 0px;">
								<div>
									<?php 
									$n_access = [
										'access'=>$access,
										'jkk'=>$this->model_master->getListBpjsRow(['inisial'=>'JKK-RS','a.status'=>1])['bpjs_karyawan'],
										'jkm'=>$this->model_master->getListBpjsRow(['inisial'=>'JKM','a.status'=>1])['bpjs_karyawan'],
										'jht'=>$this->model_master->getListBpjsRow(['inisial'=>'JHT','a.status'=>1])['bpjs_karyawan'],
										'jpns'=>$this->model_master->getListBpjsRow(['inisial'=>'JPNS','a.status'=>1])['bpjs_karyawan'],
										'jkes'=>$this->model_master->getListBpjsRow(['inisial'=>'JKES','a.status'=>1])['bpjs_karyawan'],
										'indukTunjanganTetap'=>$this->model_master->getIndukTunjanganWhere(['a.sifat'=>1],'a.sifat','DESC'),
										'indukTunjanganNon'=>$this->model_master->getIndukTunjanganWhere(['a.sifat'=>0],'a.sifat','DESC'),
										'masterInsentif'=>$this->model_master->getListInsentif(),
										'nama'=>$nama,
										'admin'=>$admin,
									];
									if($usage == 'data_ritasi'){
										if (in_array($access['l_ac']['ritasi'], $access['access'])) {
											$this->load->view('pages/data_pendukung_ritasi',$n_access);
										}else{
											if (in_array($access['l_ac']['pendukung_setting_tunjangan'], $access['access'])) {
												$this->load->view('pages/data_setting_tunjangan',$n_access);
											}elseif(in_array($access['l_ac']['pendukung_insentif'], $access['access'])){
												$this->load->view('pages/data_pendukung_insentif',$n_access);
											}elseif(in_array($access['l_ac']['pendukung_bpjs'], $access['access'])){
												$this->load->view('pages/data_pendukung_bpjs',$n_access);
											}elseif(in_array($access['l_ac']['pendukung_pinjaman'], $access['access'])){
												$this->load->view('pages/data_pendukung_pinjaman',$n_access);
											}elseif(in_array($access['l_ac']['pendukung_denda'], $access['access'])){
												$this->load->view('pages/data_pendukung_denda',$n_access);
											}elseif(in_array($access['l_ac']['pendukung_lain'], $access['access'])){
												$this->load->view('pages/data_pendukung_lain',$n_access);
											}else{
												echo 'Anda Tidak Memiliki Akses Untuk Menu ini.';
											}
										}
									}elseif($usage == 'data_setting_tunjangan'){
										if (in_array($access['l_ac']['pendukung_setting_tunjangan'], $access['access'])) {
											$this->load->view('pages/data_setting_tunjangan',$n_access);
										}else{
											echo 'Anda Tidak Memiliki Akses Untuk Menu ini.';
										}
									}elseif($usage == 'master_tunjangan'){
										$this->load->view('pages/data_master_tunjangan',$n_access);
									}elseif($usage == 'data_tunjangan'){
										if (in_array($access['l_ac']['pendukung_setting_tunjangan'], $access['access'])) {
											$this->load->view('pages/data_pendukung_tunjangan',$n_access);
										}else{
											echo 'Anda Tidak Memiliki Akses Untuk Menu ini.';
										}
									}elseif($usage == 'data_insentif'){
										if(in_array($access['l_ac']['pendukung_insentif'], $access['access'])){
											$this->load->view('pages/data_pendukung_insentif',$n_access);
										}else{
											echo 'Anda Tidak Memiliki Akses Untuk Menu ini.';
										}
									}elseif($usage == 'data_bpjs'){
										if(in_array($access['l_ac']['pendukung_bpjs'], $access['access'])){
											$this->load->view('pages/data_pendukung_bpjs',$n_access);
										}else{
											echo 'Anda Tidak Memiliki Akses Untuk Menu ini.';
										}
									}elseif($usage == 'data_pinjaman'){
										if(in_array($access['l_ac']['pendukung_pinjaman'], $access['access'])){
											$this->load->view('pages/data_pendukung_pinjaman',$n_access);
										}else{
											echo 'Anda Tidak Memiliki Akses Untuk Menu ini.';
										}
									}elseif($usage == 'view_angsuran'){
										$this->load->view('pages/data_pendukung_view_angsuran',$n_access);
									}elseif($usage == 'data_denda'){
										if(in_array($access['l_ac']['pendukung_denda'], $access['access'])){
											$this->load->view('pages/data_pendukung_denda',$n_access);
										}else{
											echo 'Anda Tidak Memiliki Akses Untuk Menu ini.';
										}
									}elseif($usage == 'lain_lain'){
										if(in_array($access['l_ac']['pendukung_lain'], $access['access'])){
											$this->load->view('pages/data_pendukung_lain',$n_access);
										}else{
											echo 'Anda Tidak Memiliki Akses Untuk Menu ini.';
										}
									}else{
										if (in_array($access['l_ac']['ritasi'], $access['access'])) {
											$this->load->view('pages/data_pendukung_ritasi',$n_access);
										}
										if (in_array($access['l_ac']['pendukung_setting_tunjangan'], $access['access'])) {
											$this->load->view('pages/data_setting_tunjangan',$n_access);
										}
										if(in_array($access['l_ac']['pendukung_pinjaman'], $access['access'])){
											$this->load->view('pages/data_pendukung_pinjaman',$n_access);
										}
										// $this->load->view('pages/data_pendukung_ritasi',$n_access);
									}
									?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>	
</div>
