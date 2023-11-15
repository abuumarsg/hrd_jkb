
<div class="container-fluid">
	<div class="row mb-3 mt-2">
		<div class="col-12 col-sm-6 text-center text-sm-left">
			<!-- <h6 class="mb-1 mt-sm-1"><?=$nama_materi?> </h6> -->
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
	<form class="button_to" method="post" action="<?//=base_url('utama/start/'.$ikutserta['id_latihan_soal'].'/1')?>">
	<!-- <input class="btn btn-success"type="submit" value="Coba Lagi" /></form> -->
</div>
<ul class="nav nav-tabs justify-content-center" id="quizTab" role="tablist">
	<li class="nav-item">
		<a class="nav-link active text-uppercase" id="summary-tab" data-toggle="tab" href="#summary" role="tab"
			aria-controls="home" aria-selected="true">
			Ringkasan
		</a>
	</li>
	<!-- <li class="nav-item text-uppercase">
		<a class="nav-link" id="review-tab" data-toggle="tab" href="#review" role="tab" aria-controls="contact"
			aria-selected="false">
			Ulasan
		</a>
	</li> -->
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

	<!-- <div class="tab-pane fade" id="review" role="tabpanel" aria-labelledby="review-tab">
		<ul class="list-unstyled" id="posts-list">
			<?php foreach ($soal_soal as $ss) { 
				$kunciA = ($ss->kunci_jawaban == 'a')?'<i class="float-right fa fa-check-circle text-success" style="font-size:1.25em;" title="Jawaban Yang Benar"></i>':null;
				$borderA = ($ss->kunci_jawaban == 'a')?' border rounded':null;
				$kunciB = ($ss->kunci_jawaban == 'b')?'<i class="float-right fa fa-check-circle text-success" style="font-size:1.25em;" title="Jawaban Yang Benar"></i>':null;
				$borderB = ($ss->kunci_jawaban == 'b')?' border rounded':null;
				$kunciC = ($ss->kunci_jawaban == 'c')?'<i class="float-right fa fa-check-circle text-success" style="font-size:1.25em;" title="Jawaban Yang Benar"></i>':null;
				$borderC = ($ss->kunci_jawaban == 'c')?' border rounded':null;
				$kunciD = ($ss->kunci_jawaban == 'd')?'<i class="float-right fa fa-check-circle text-success" style="font-size:1.25em;" title="Jawaban Yang Benar"></i>':null;
				$borderD = ($ss->kunci_jawaban == 'd')?' border rounded':null;
				$jawabanA = ($ss->jawaban_siswa == 'a')?'badge-info':'badge-light';
				$jawabanB = ($ss->jawaban_siswa == 'b')?'badge-info':'badge-light';
				$jawabanC = ($ss->jawaban_siswa == 'c')?'badge-info':'badge-light';
				$jawabanD = ($ss->jawaban_siswa == 'd')?'badge-info':'badge-light';
			?>
			<li data-id="49885" data-status="pending" data-post-group-slug="quiz" class="p-3 border-bottom">
				<div class="row">
					<div class="col-7 col-md-3 text-md-right"><b><?//=$ss->nomor?>.</b></div>
					<div class="col-5 col-md-3 col-lg-2 text-right text-md-left order-md-2">
						<div id="post-actions-49885">
							<button class="btn btn-outline-secondary btn-sm mr-1 mb-2" type="button" data-toggle="collapse" data-target="#<?//=$ss->id?>" aria-expanded="false" aria-controls="collapseExample">
								Bahas
							</button>
							<i class="icon ion-md-help text-warning align-middle" style="font-size:1.5rem;line-height:1em;"></i>
						</div>
					</div>
					<div class="col-md-6 col-lg-7 order-md-1">
						<div class="row">
							<div class="col-12">
								<div class="post-description-full">
									<div class="mb-3">
										<h1 class="h5"><?//=$ss->soal?></h1>
									</div>
								</div>
								<div class="post-alternatives-full mb-2">
									<div class="mb-3">
										<div class="media pt-2 pb-0 px-2 <?//=$borderA?>">
											<h5 class="mb-0 text-weight-normal">
												<span class="badge badge-pill mr-3 px-3 py-1 <?//=$jawabanA?>">
													A
												</span>
											</h5>
											<div class="media-body"><?//=$kunciA?>
												<p><?//=$ss->a?></p>
											</div>
										</div>
										<div class="media pt-2 pb-0 px-2 <?//=$borderB?>">
											<h5 class="mb-0 text-weight-normal">
												<span class="badge badge-pill mr-3 px-3 py-1 <?//=$jawabanB?>">
													B
												</span>
											</h5>
											<div class="media-body"><?//=$kunciB?>
												<p><?//=$ss->b?></p>
											</div>
										</div>
										<div class="media pt-2 pb-0 px-2 <?//=$borderC?>">
											<h5 class="mb-0 text-weight-normal">
												<span class="badge badge-pill mr-3 px-3 py-1 <?//=$jawabanC?>">
													C
												</span>
											</h5>
											<div class="media-body"><?//=$kunciC?>
												<p><?//=$ss->c?></p>
											</div>
										</div>
										<div class="media pt-2 pb-0 px-2 <?//=$borderD?>">
											<h5 class="mb-0 text-weight-normal">
												<span class="badge badge-pill mr-3 px-3 py-1 <?//=$jawabanD?>">
													D
												</span>
											</h5>
											<div class="media-body"><?//=$kunciD?>
												<p><?//=$ss->d?></p>
											</div>
										</div>
									</div>
									<div class="mt-3 collapse" id="<?//=$ss->id?>">
										<div class="card mb-3">
											<div class="card-body">
												<h6 class="card-title text-uppercase">Pembahasan :</h6>
												<?//=$ss->pembahasan?>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-12">
								<div class="mb-3topic-list">
								</div>
							</div>
						</div>
					</div>
				</div>
			</li>
			<?php } ?>
		</ul>
	</div> -->
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
				<small><i>Sesi pre-test telah selesai dan anda dapat langsung ke langkah selanjutnya dengan klik tombol dibawah.</i></small>
				<br> <br>
				<a class="btn btn-lg pull-right btn-success" data-remote="true" rel="nofollow" data-method="post" href="javascript:void(0)" onclick="doEndLearning()">Lanjutkan</a>
			<!-- </div> -->
		</div>
	</div>
</div>
<!-- <a class="btn btn-lg pull-right btn-success" data-remote="true" rel="nofollow" data-method="post" href="javascript:void(0)" onclick="doEndLearning()">Lanjutkan</a> -->

<script>
	if (window.location.hash == '#questions') {
		$('#quizTab a[href="#questions"]').tab('show');
	}
	if (window.location.hash == '#review') {
		$('#quizTab a[href="#review"]').tab('show');
	}
</script>

<script>
	var ctx = document.getElementById('myChart').getContext('2d');
	var myChart = new Chart(ctx, {
		type: 'doughnut',
		data: {
			labels: ['Benar', 'Salah', 'Dilewati'],
			datasets: [{
				data: [2.0, 6.0, 2.0],
				backgroundColor: ['#27ae60', '#e74c3c', '#2c3e50'],
			}]
		},
		options: {}
	});
</script>
</main>

</div>



<script>
	// The debounce function receives our function as a parameter
	const debounce = (fn) => {

		// This holds the requestAnimationFrame reference, so we can cancel it if we wish
		let frame;

		// The debounce function returns a new function that can receive a variable number of arguments
		return (...params) => {
			// If the frame variable has been defined, clear it now, and queue for next frame
			if (frame) {
				cancelAnimationFrame(frame);
			}

			// Queue our function call for the next frame
			frame = requestAnimationFrame(() => {

				// Call our function and pass any params we received
				fn(...params);
			});
		}
	};

	// Reads out the scroll position and stores it in the data attribute
	// so we can use it in our stylesheets
	const storeScroll = () => {
		document.documentElement.dataset.scroll = window.scrollY;
	}

	// Listen for new scroll events, here we debounce our `storeScroll` function
	document.addEventListener('scroll', debounce(storeScroll), {
		passive: true
	});

	// Update scroll position for first time
	storeScroll();
</script>

<script>
	$.notify({
		message: 'Kuis selesai',
		type: 'info'
	}, {
		newest_on_top: true,
		offset: {
			x: 4,
			y: 68
		},
		delay: 3000,
		animate: {
			enter: 'animated fadeInRight',
			exit: 'animated fadeOutRight'
		}
	});
</script>

</body>

</html>