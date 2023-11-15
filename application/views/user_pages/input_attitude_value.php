  <div class="content-wrapper">
  	<div class="alert alert-info">
  		<i class="fa fa-calendar faa-shake animated text-blue" style="font-size: 14pt;"></i>
  		<?php 
  		if ($agd != "") {
  			echo ' <b>Agenda Penilaian Sikap (360°) '.$agd['nama_agenda'].' Tahun '.$agd['tahun'].' '.$agd['periode'].'</b>';
  		} 
  		?>
  	</div>
  	<section class="content-header">
  		<h1>
  			<a class="back" onclick="window.history.back()"><i class="fa fa-chevron-circle-left "></i></a> <i class="fa fa-edit"></i> Input Nilai
  			<small><?php echo $nama;?></small>
  		</h1> 
  		<ol class="breadcrumb">
  			<li><a href="<?php echo base_url('kpages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
  			<li><a href="<?php echo base_url('kpages/attitude_tasks');?>"><i class="fa fa-calendar"></i> Daftar Agenda</a></li>
  			<li><a href="<?php echo base_url('kpages/input_attitude_tasks_value/'.$kode_sikap_en);?>"><i class="fa fa-users"></i> Daftar Karyawan</a></li>
  			<li class="active">Input Nilai <?php echo $nama;?></li>
  		</ol>
  	</section>
  	<section class="content"> 
  		<div class="row">
  			<div class="col-md-3">
  				<div class="box box-primary">
  					<div class="box-body box-profile">
  						<img class="profile-user-img img-responsive img-circle view_photo" style="width: 200px;" src="<?php if($foto == null){
  							if($kelamin == 'l'){
  								echo base_url('asset/img/user-photo/user.png');
  								}else{
  									echo base_url('asset/img/user-photo/userf.png');
  								}
  								}else{
  									echo base_url($foto);
  								} ?>" alt="User profile picture">

  								<h3 class="profile-username text-center"><?php echo $nama;?></h3>

  								<p class="text-muted text-center"><?php echo $jabatan;?></p>

  								<ul class="list-group list-group-unbordered">
  									<li class="list-group-item">
  										<b>Lokasi Kerja</b> <label class="label label-primary pull-right"><?php echo $loker;?></label>
  									</li>
  									<li class="list-group-item" id="sebagai">
  									</li>
  								</ul>
  							</div>
  						</div>
  						<div class="box box-danger bg-aqua">
  							<div class="box-body box-profile" id="das">
  							</div>
  						</div>
  					</div>
  					<div class="col-md-9">
  						<div class="box box-success">
  							<div class="box-header with-border">
  								<h3 class="box-title"><i class="fa fa-question-circle"></i> Daftar Kuisioner Untuk <?php echo $nama;?></h3>
  								<div class="box-tools pull-right">
  									<button class="btn btn-box-tool" onclick="reload_table('table_data')" data-toggle="tooltip" title="Refresh Table"><i class="fa fa-refresh"></i></button>
  									<button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
  									<button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i class="fa fa-times"></i></button>
  								</div>
  							</div>
  							<div class="box-body">
  								<div class="row">
  									<div class="col-md-12">
  										<form id="form_edit">
  											<div class="callout callout-info"><label><i class="fa fa-bullhorn"></i> Petunjuk</label><br>
  												<ul>
  													<li>Jika ada peringatan <b class="text-red">Anda Harus Mengisi Keterangan</b>, maka anda harus mengisi kolom keterangan tersebut dengan minimal <b class="bg-yellow">10 Karakter</b></li>
  													<li>Jika Anda ingin menilai Aspek Sikap yang lain, maka anda bisa memilih pada Box Menu <b>Daftar Aspek Sikap</b> yang berwarna biru</li>
  													<li><label class="label label-danger"><i class="fa fa-warning"></i> WARNING</label> Anda harus <b class="text-red">MENGISI SEMUA NILAI</b> yang ada pada box Menu <b>Daftar Aspek Sikap</b></li>
  													<li>Pastikan Semua Kuisioner berstatus <i class="fa fa-check-circle text-green" style="font-size: 12pt;"></i></li>
  												</ul>
  											</div>
  											<div class="table-responsive">
  												<table id="table_data" class="table table-bordered table-striped" width="100%">
  													<thead>
  														<tr class="bg-blue">
  															<th>No.</th>
  															<th>Kuisioner</th>
  															<th>Nilai</th>
  															<th>Keterangan</th>
  														</tr>
  													</thead>
  													<tbody>
  													</tbody>
  												</table>
  											</div>
  											<div class="form-group pull-right">
  												<input type="hidden" name="kode" id="kode" value="">
  												<input type="hidden" name="penilai" id="penilai" value="">
  												<input type="hidden" name="id" id="id" value="">
  												<input type="hidden" name="sbg" id="sbg" value="">
  												<input type="hidden" name="tabel" id="tabel" value="">
  												<input type="hidden" name="aspek" id="aspek" value="">
  												<input type="hidden" name="koas" id="koas" value="">
  												<button class="btn btn-danger" type="reset" onclick="function myFunction() {location.reload();}"><i class="fa fa-refresh"></i> Reset</button>
  												<button class="btn btn-success" id="sv" onclick="do_edit()" type="button" disabled="disabled"><i class="fa fa-floppy-o"></i> Simpan</button>
  											</div>
  										</form>
  									</div>
  								</div>
  							</div>
  						</div>
  					</div>
  				</div>
  				<div id="alert_ket" class="modal fade" role="dialog">
  					<div class="modal-dialog modal-sm modal-danger">
  						<div class="modal-content">
  							<div class="modal-header">
  								<button type="button" class="close" data-dismiss="modal">&times;</button>
  								<h4 class="modal-title text-center">Keterangan Kosong</h4>
  							</div>
  							<div class="modal-body text-center">
  								<p>Anda Harus Mengisi Keterangan Terlebih Dahulu</p>
  							</div>
  							<div class="modal-footer">
  								<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
  							</div>
  						</div>
  					</div>
  				</div>
  			</section>
  		</div>
<script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
<script type="text/javascript">
	$(document).ready(function(){
		var datax={tabel:"<?php echo $this->codegenerator->encryptChar($tabel);?>",
		kode_agenda:"<?php echo $this->codegenerator->encryptChar($kode_sikap);?>",
		kode_aspek:"<?php echo $kode_aspek;?>",
		id:"<?php echo $this->codegenerator->encryptChar($id);?>"}
		var callback=getAjaxData("<?php echo base_url('kagenda/input_attitude_value/view_all/'.$this->uri->segment(4))?>",datax);
		$('#das').html(callback['das']);
		$('#sebagai').html(callback['sebagai']);
		$('#kuisioner').html(callback['kuisioner']);
		$('#kode').val(callback['kode']);
		$('#penilai').val(callback['penilai']);
		$('#id').val(callback['id']);
		$('#sbg').val(callback['sbg']);
		$('#tabel').val(callback['tabel']);
		$('#aspek').val(callback['aspek']);
		$('#koas').val(callback['koas']);
		$('#table_data').DataTable( {
			ajax: {
				url: "<?php echo base_url('kagenda/input_attitude_value/view_one/'.$this->uri->segment(4))?>",
				type: 'POST',
				data:{tabel:"<?php echo $this->codegenerator->encryptChar($tabel);?>",
				kode_agenda:"<?php echo $this->codegenerator->encryptChar($kode_sikap);?>",
				kode_aspek:"<?php echo $kode_aspek;?>",
				id:"<?php echo $this->codegenerator->encryptChar($id);?>"}
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
				width: '55%',
				render: function ( data, type, full, meta ) {
					return data;
				}
			},
			{   targets: 2,
				width: '20%',
				render: function ( data, type, full, meta ) {
					return data;
				}
			},
			//aksi
			{   targets: 3, 
				width: '20%',
				render: function ( data, type, full, meta ) {
					return '<center>'+data+'</center>';
				}
			},
			],
			drawCallback: function() {
				$('.select2').select2({
					placeholder: 'Pilih Data',
					allowClear: true
				});
			}
		});
	})
	function isi(n){
		var mbu = $("#kt"+n);
		var up = $("#up"+n).val();          
		if ((up > $("#up"+n).data("bawah") && up < $("#up"+n).data("atas"))) {
			$("#kt"+n).attr("disabled","disabled");
			$("#kt"+n).val("");
			$("#kt"+n).removeAttr("required");
			$("#ps"+n).html("");
			$("#sv").removeAttr("disabled");
		}else{
			$("#kt"+n).removeAttr("disabled");
			$("#kt"+n).attr("required","required");
			if (mbu.val() == "") {
				$("#sv").attr("disabled","disabled");
			}
			mbu.keyup(function(){
				if (mbu.val() != "") {
					if (mbu.val().length > 10) {
						$("#sv").removeAttr("disabled");
					}else{
						$("#sv").attr("disabled","disabled"); 
					}
				}else{
					$("#sv").attr("disabled","disabled"); 
				}
			});
			$("#ps"+n).html("Anda Harus Mengisi Keterangan");
		}
	}
	function do_edit(){
		submitAjax("<?php echo base_url('kagenda/input_sikap_value')?>",null,'form_edit',null,null);
	}
</script>