
<div class="container-fluid">
	<div class="row mb-3 mt-2">
		<div class="col-12 col-sm-6 text-center text-sm-left">
			<h6 class="mb-1 mt-sm-1"><?//=$nama_materi?> </h6>
			<h4> <?=$nama_materi?> </h4>
		</div>
		<!-- <div class="col-12 col-sm-6 text-center text-sm-right">
			<div class="border border-info text-info rounded d-inline-block mx-auto p-1 text-center" style="width:6em">
				<small class="m-0 font-weight-normal text-uppercase">Skor</small>
				<h2 class="m-0 p-0" id="final-score"><?//=$nilai?></h2>
			</div>
		</div> -->
	</div>
</div>
<div class="mb-3 px-3 text-center text-sm-right">
	<form class="button_to" method="post" action="<?//=base_url('utama/start/'.$ikutserta['id_latihan_soal'].'/1')?>">
	<!-- <input class="btn btn-success"type="submit" value="Coba Lagi" /></form> -->
</div>
<ul class="nav nav-tabs justify-content-center" id="quizTab" role="tablist">
	<!-- <li class="nav-item">
		<a class="nav-link active text-uppercase" id="summary-tab" data-toggle="tab" href="#summary" role="tab"
			aria-controls="home" aria-selected="true">
			Ringkasan
		</a>
	</li> -->
	<li class="nav-item text-uppercase">
		<a class="nav-link active" id="review-tab" data-toggle="tab" href="#review" role="tab" aria-controls="contact"
			aria-selected="false">
			MATERI PEMBELAJARAN
		</a>
	</li>
</ul>

<div class="tab-content" id="quizTabContent">
	<!-- <div class="tab-pane fade" id="summary" role="tabpanel" aria-labelledby="summary-tab">
		<ul class="list-group list-group-flush">
			<li class="list-group-item text-center px-0">
				<div class="row">
					<div class="col-6 col-md-3">
						<div class="text-center">
							<small class="text-uppercase">Jumlah Soal</small>
							<br/>
							<h6><?=$jumlah_soal?> Soal</h6>
						</div>
					</div>
					<div class="col-6 col-md-3">
						<div class="text-center">
							<small class="text-uppercase">Tipe Soal</small>
							<br/>
							<h6>Pilihan Ganda</h6>
						</div>
					</div>
					<div class="col-6 col-md-3">
						<div class="text-center">
							<small class="text-uppercase">Waktu</small>
							<br/>
							<h6><?=$waktuTest?> Menit</h6>
						</div>
					</div>
					<div class="col-6 col-md-3">
						<div class="text-center">
							<small class="text-uppercase">Skor Maks</small>
							<br/>
							<h6>100</h6>
						</div>
					</div>
				</div>
			</li>
			<li class="list-group-item text-center px-0">
				<div class="row">
					<div class="col-12">
						<div class="row">
							<div class="col-6 col-sm-3">
								<div class="text-center">
									<small class="text-uppercase">Selesai</small>
									<br />
									<?=$jumlah_jawaban?> Jawaban
								</div>
							</div>
							<div class="col-6 col-sm-3 text-center">
								<small class="text-uppercase">Benar</small>
								<h6><?=$jawaban_benar?> Soal</h6>
							</div>
							<div class="col-6 col-sm-3 text-center">
								<small class="text-uppercase">Salah</small>
								<h6><?=($jumlah_jawaban-$jawaban_benar)?> Soal</h6>
							</div>
							<div class="col-6 col-sm-3 text-center">
								<small class="text-uppercase">Dilewati</small>
								<h6><?=($jumlah_soal-$jumlah_jawaban)?> Soal</h6>
							</div>
						</div>
					</div>
				</div>
			</li>
		</ul>
	</div> -->

	<div class="tab-pane fade show active" id="review" role="tabpanel" aria-labelledby="review-tab">
		<ul class="list-group list-group-flush">
			<?php if(!empty($materiPembelajaran)){
				$no = 1;
				foreach ($materiPembelajaran as $d) {
			?>
			<li data-post-group-slug="quiz" class="list-group-item text-center px-0">
				<div class="row">
					<div class="col-7 col-md-3 text-md-right"><b><?=$no?>. </b></div>
					<div class="col-5 col-md-3 col-lg-2 text-right text-md-left order-md-2">
						<div id="post-actions-49885">
							<!-- <a class="btn btn-outline-secondary btn-sm mr-1 mb-2" type="button" href="<?php //echo base_url($d->file)?>" target="BLANK"> Lihat Materi </a> -->
							<?php echo '<button type="button" class="btn btn-outline-secondary btn-sm mr-1 mb-2" href="javascript:void(0)" onclick="view_modal_file('.$d->id.')"> Lihat Materi</button> '; ?>
							<i class="icon ion-md-help text-warning align-middle" style="font-size:1.5rem;line-height:1em;"></i>
						</div>
					</div>
					<div class="col-md-6 col-lg-7 order-md-1">
						<div class="row">
							<div class="col-12">
								<div class="post-description-full">
									<div class="mb-3">
										<h3 class="h5 text-md-left"><?=$d->nama?><br><small><i><?=$d->keterangan?></i></small></h3>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</li>
			<?php $no++; 
				}
			}
			?>
		</ul>
	</div>
</div>
<hr/>
<div class="container-fluid">
	<div class="row mb-3 mt-2">
		<div class="col-12 col-sm-4 text-center text-sm-left">
			<h6 class="mb-1 mt-sm-1"></h6>
			<h4></h4>
		</div>
		<div class="col-12 col-sm-8 text-center text-sm-right">
			<!-- <div class="border border-info text-info rounded d-inline-block mx-auto p-1 text-center" style="width:6em"> -->
				<!-- <small class="m-0 font-weight-normal text-uppercase">Skor</small>
				<h2 class="m-0 p-0" id="final-score">96<?//=$skor?></h2> -->
				<small><i>Sudah selesai mempelajari materi ??<br> Sudah siap untuk Post-test ??<br> Anda dapat langsung ke Post-test dengan klik tombol dibawah.</i></small>
				<br> <br>
				<a class="btn btn-lg pull-right btn-success" data-remote="true" rel="nofollow" data-method="post" href="javascript:void(0)" onclick="doPostTest()">Lanjutkan</a>
			<!-- </div> -->
		</div>
	</div>
</div>
<?php
// print_r($_SESSION);
?>
	<input type="hidden" id="id_materi" value="<?=$id_materi?>">
	<input type="hidden" id="percobaan" value="<?=$percobaan?>">
	<input type="hidden" id="kode_materi" value="<?=$kode_materi?>">

<script>
	if (window.location.hash == '#questions') {
		$('#quizTab a[href="#questions"]').tab('show');
	}
	if (window.location.hash == '#review') {
		$('#quizTab a[href="#review"]').tab('show');
	}
	$(document).off('view_modal_file.modal');
	function doPostTest() {
		var id_materi = $('#id_materi').val();
		var percobaan = $('#percobaan').val();
		var kode_materi = $('#kode_materi').val();
		// alert(id_materi+'='+percobaan+'='+kode_materi);
		var datac = { id_materi: id_materi, percobaan: percobaan, kode_materi: kode_materi, };
		$.redirect("<?php echo base_url('learning/pagePostTest'); ?>", datac,);
	}
	function view_modal_file(id, ext) {
		var data = {id:id, ext:ext};
		var callback=getAjaxData("<?php echo base_url('learning/getFileMateriLearning/view_one')?>",data);  
		$('#view_modal_file').modal('show');
		$('.header_data').html(callback['nama']);
		// $('#data_tabel_view').html(callback['tabel']);
		$('#fileView').html(callback['fileView']);
	}
</script>