
	<div class="container-fluid">
		<div class="row mb-3 mt-2">
			<div class="col-12 col-sm-6 text-center text-sm-left">
				<h4> <?=$nama_materi?> </h4>
			</div>
			<div class="col-12 col-sm-6 text-center text-sm-right">
				<div class="border border-info text-info rounded d-inline-block mx-auto p-1 text-center" style="width:6em">
					<small class="m-0 font-weight-normal text-uppercase">Skor</small>
					<h2 class="m-0 p-0" id="final-score"><?=$nilai?></h2>
				</div>
			</div>
		</div>
	</div>
	<div class="mb-3 px-3 text-center text-sm-right">
	</div>
	<ul class="nav nav-tabs justify-content-center" id="quizTab" role="tablist">
		<li class="nav-item">
			<a class="nav-link active text-uppercase" id="summary-tab" data-toggle="tab" href="#summary" role="tab"
				aria-controls="home" aria-selected="true">
				Ringkasan
			</a>
		</li>
	</ul>
	<div class="tab-content" id="quizTabContent">
		<div class="tab-pane fade show active" id="summary" role="tabpanel" aria-labelledby="summary-tab">
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
						<hr>
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
					<small><i>Sesi pre-test telah selesai dan anda dapat langsung ke langkah selanjutnya dengan klik tombol dibawah.</i></small>
					<br> <br>
					<a class="btn btn-lg pull-right btn-success" data-remote="true" rel="nofollow" data-method="post" href="javascript:void(0)" onclick="doEndRingkasan()">Lanjutkan</a>
			</div>
		</div>
	</div>
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
		function doEndRingkasan() {
			var id_materi = $('#id_materi').val();
			var percobaan = $('#percobaan').val();
			var kode_materi = $('#kode_materi').val();
			// alert(id_materi+'='+percobaan+'='+kode_materi);
			var datac = { id_materi: id_materi, percobaan: percobaan, kode_materi: kode_materi, };
			$.redirect("<?php echo base_url('learning/viewMateriPembelajaran'); ?>", datac,);
		}
	</script>