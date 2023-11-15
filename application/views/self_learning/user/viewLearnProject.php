
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
	<!-- <form class="button_to" method="post" action="<?//=base_url('utama/start/'.$ikutserta['id_latihan_soal'].'/1')?>"> -->
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
			PROJECT IMPLEMENTASI MATERI PEMBELAJARAN
		</a>
	</li>
</ul>

<div class="tab-content" id="quizTabContent">
	<div class="tab-pane fade show active" id="review" role="tabpanel" aria-labelledby="review-tab">
		<ul class="list-group list-group-flush">
			<?php if(!empty($project)){
				$no = 1;
				foreach ($project as $d) {
			?>
			<li data-post-group-slug="quiz" class="list-group-item text-center px-0">
				<div class="row">
					<div class="col-7 col-md-3 text-md-right"><b><?=$no?>. </b></div>
					<div class="col-5 col-md-3 col-lg-2 text-right text-md-left order-md-2">
						<div id="post-actions-49885">
							<i class="icon ion-md-help text-warning align-middle" style="font-size:1.5rem;line-height:1em;"></i>
						</div>
					</div>
					<div class="col-md-6 col-lg-7 order-md-1">
						<div class="row">
							<div class="col-12">
								<div class="post-description-full">
									<div class="mb-3">
										<h3 class="h5 text-md-left"><?=$d->soal_project?><br><small><i><?=$d->keterangan?></i></small></h3>
									</div>
								</div>
								<?php
								$jawaban = null;
								$file = null;
								$file_old = null;
									$jwb = $this->model_learning->getJawabanProjectEmp(['a.kode_project '=>$d->kode, 'a.id_karyawan '=>$id_karyawan], true);
									if(!empty($jwb)){
										$jawaban = (($d->kode == $jwb['kode_project']) ? $jwb['jawaban'] : null);
										$file = (($d->kode == $jwb['kode_project']) ? str_replace('asset/file/self_learning/jawaban_project/', '', $jwb['file']) : null);
										$file_old = (($d->kode == $jwb['kode_project']) ? $jwb['file'] : null);
									}
								?>
                       			<form id="form_add<?=$no?>" class="form" enctype="multipart/form-data" class="form-horizontal" onsubmit="myFunction(<?=$no?>)">
									<input type="hidden" name="id" value="<?=$d->id?>">
									<input type="hidden" name="kode" value="<?=$d->kode?>">
									<input type="hidden" name="kode_materi" value="<?=$d->kode_materi?>">
									<input type="hidden" name="file_old" value="<?=$file_old?>">
									<div class="form-group clearfix">
										<textarea name="jawaban" class="form-control" placeholder="<?=$d->soal_project?>" rows="10" required="required"><?=$jawaban?></textarea>
									</div>
									<?php 
									$file_oldx = (($d->kode == $jwb['kode_project']) ? $jwb['file'] : null);
									if(!empty($file_oldx)){
										echo '<a class="btn btn-outline-secondary btn-sm mr-1 mb-2" type="button" href="'.base_url($file_oldx).'" target="BLANK"> Lihat File </a>';
									}?>
									<!-- <p class="text-muted">File harus bertipe *.pdf atau *.jpg</p> -->
									<div class="form-group">
										<div class="input-group">
											<label class="input-group-btn" style="padding-top:8px;">
												<span class="btn btn-primary"> 
													Pilih File <input type="file" id="BSbtnsuccess" name="file" style="display: none;">
												</span>
											</label>
											<input type="text" class="form-control" readonly="readonly" value="<?=$file?>" style="position: relative;z-index: 1;">
										</div>
									</div>
									<button type="submit" class="btn btn-success text-md-right">Upload & Simpan<span class='glyphicon glyphicon-arrow-up'></span></button>
									<!-- <button type="button" id="btn_add" onclick="do_add(<?=$no?>)" class="btn btn-success text-md-right"><i class="fa fa-floppy-o"></i> Upload & Simpan</button>
									<button type="submit" id="btn_addx<?=$no?>" style="display: none;"></button> -->
								</form>
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
				<small><i>Sudah yakin dengan jawaban Anda ??<br>Anda dapat langsung ke menyelesaikan learning ini dengan klik tombol dibawah. Terimakasih</i></small>
				<br> <br>
				<!-- <a class="btn btn-lg pull-right btn-success" data-remote="true" id="btn<?=$id_materi?>" rel="nofollow" data-method="post" href="<?php// echo base_url('learning/endTest/'.$id_materi); ?>" onclick="return confirm('Yakin akan menyelesaiakan pelatihan ini ??')">Selesaikan Learning</a> -->
				<a class="btn btn-lg pull-right btn-success" data-remote="true" id="btn<?=$id_materi?>" rel="nofollow" data-method="post" href="javascript:void(0)" onclick="doActionEndTest()">Selesaikan Learning</a>
		</div>
	</div>
</div>
<div id="modalEnd" class="modal">
    <div class="modal-content modal-lg">
        <span class="close">&times;</span>
        <div class="container-fluid">
            <div class="row mb-3 mt-2">
                <div class="col-12 col-sm-6 text-center text-sm-left">
                    <h6 class="mb-1 mt-sm-1"> </h6>
                    <h4> RINGKASAN </h4>
                </div>
            </div>
        </div>
        <ul class="nav nav-tabs justify-content-center" id="quizTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active text-uppercase" id="summary-tab" data-toggle="tab" href="#pretest" role="tab" aria-controls="home" aria-selected="true">
                    Pre Test
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-uppercase" id="summary-tab" data-toggle="tab" href="#posttest" role="tab" aria-controls="home" aria-selected="true">
                    Post Test
                </a>
            </li>
        </ul>
        <div class="tab-content" id="quizTabContent">
            <div class="tab-pane fade show active" id="pretest" role="tabpanel" aria-labelledby="summary-tab">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item text-center px-0">
                        <div class="row">
                            <div class="col-6 col-md-6">
                                <div class="text-center">
                                    <h6>pretest</h6>
                                </div>
                            </div>
                            <div class="col-6 col-md-6">
                                <div class="text-center">
                                    <h6>pretest</h6>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="tab-pane fade" id="posttest" role="tabpanel" aria-labelledby="summary-tab">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item text-center px-0">
                        <div class="row">
                            <div class="col-6 col-md-6">
                                <div class="text-center">
                                    <h6>posttest</h6>
                                </div>
                            </div>
                            <div class="col-6 col-md-6">
                                <div class="text-center">
                                    <h6>posttest</h6>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        <div class="card text-dark text-center bg-light my-3 mx-auto" style="max-width:32em">
            <div class="card-body">
                <h5 class="card-title">Ayo Daftar Sekarang!</h5>
                <p class="card-text">
                Dan dapatkan akses ke seluruh
                soal dengan berbagai Pilihan!
                </p>
                <a class="mt-2 btn btn-dark text-uppercase" href="<?=base_url('utama/daftar_siswa')?>">Daftar</a>
            </div>
        </div>
        <div class="col-12 col-sm-12 text-center text-sm-right">
            <div class="mt-1 btn-group" role="group" aria-label="View Start Publish">
                <a class="btn btn-outline-primary" rel="nofollow" data-method="post" href="#"> Ayo Mulai !</a>
            </div>
        </div>
    </div>
</div>
<?php
// print_r($_SESSION);
?>
	<input type="hidden" id="id_materi" value="<?=$id_materi?>">
	<input type="hidden" id="percobaan" value="<?=$percobaan?>">
	<input type="hidden" id="kode_materi" value="<?=$kode_materi?>">

<script src="<?=base_url()?>asset/learning/tabel/vendor/jquery/jquery-3.2.1.min.js"></script>
<script type="text/javascript">
	$('document').ready(function(){
	});
	function myFunction(no) {
		$('#form_add'+no).submit(function(e){
			e.preventDefault();
			var data = new FormData(this);
			var urlx = "<?php echo base_url('learning/addJawabanProject'); ?>";
			submitAjaxFile(urlx,data,null,null,null,null);
		});
	}
	$(function() {
		$(document).on('change', ':file', function() {
			var input = $(this),
				numFiles = input.get(0).files ? input.get(0).files.length : 1,
				label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
			input.trigger('fileselect', [numFiles, label]);
		});
		$(document).ready( function() {
			$(':file').on('fileselect', function(event, numFiles, label) {
				var input = $(this).parents('.input-group').find(':text'),
					log = numFiles > 1 ? numFiles + ' files selected' : label;
				if( input.length ) {
					input.val(log);
				} else {
					if( log ) alert(log);
				}

			});
		});
	});
	// function doEndTest(idx) {
	// 	var modalx = 'modalEnd';
	// 	var modal = document.getElementById(modalx);
	// 	modal.style.display = "block";
	// 	var span = document.getElementsByClassName("close")[0];
	// 	span.onclick = function() {
	// 		modal.style.display = "none";
	// 	}
	// 	window.onclick = function(event) {
	// 		if (event.target == modal) {
	// 			modal.style.display = "none";
	// 		}
	// 	}
	// }
	function doActionEndTest() {
		var id_materi = $('#id_materi').val();
		var percobaan = $('#percobaan').val();
		var kode_materi = $('#kode_materi').val();
		// alert(id_materi+'='+percobaan+'='+kode_materi);
		var datac = { id_materi: id_materi, percobaan: percobaan, kode_materi: kode_materi, };
		$.redirect("<?php echo base_url('learning/doEndTest'); ?>", datac,);
	}
</script>