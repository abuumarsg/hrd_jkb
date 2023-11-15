<body class="fullscreen">
  	<div class="" id="post-container">
  		<div id="quiz-header-3329" class="px-3 row fixed-top text-white bg-dark">
			<input type="hidden" name="kode_materi" value="<?=$kode_materi?>" id="kode_materi">
			<input type="hidden" name="kode_soal" id="kode_soal">
			<input type="hidden" name="percobaan" id="percobaan">
			<input type="hidden" name="jumlah_jawaban" id="jumlah_jawaban">
			<input type="hidden" name="nomor" id="nomorx">
			<input type="hidden" name="sisa_waktu" id="sisa_waktu">
			<input type="hidden" name="id_materi" value="<?=$id_materi?>" id="id_materi">
  			<div class="col-8">
  				<h6 class="mb-1 mt-2 text-info text-truncate">
  					<span class="">Latihan</span> <b></b>
  				</h6>
  				<h5 class="text-truncate" id="nama_materi"><?//=$latihan['judul']?></h5>
  			</div>
  			<div class="col-4 text-right">
  				<p class="p-0 mt-1 mb-0"><small>Tersisa</small></p>
				<div id="timeToShow"></div>
    			<!-- <progress value="<?//=$secondx?>" max="<?//=$secondx?>" id="pageBeginCountdown"></progress> -->
  				<h5 class="text-monospace" id="timestamp" data-countdown="2020-07-22T19:59:41Z">--:--:--</h5>
  			</div>
  		</div>
  		<div class="card-body bg-white" style="padding-top:90px;padding-bottom:60px;" id="divViewSoal">
  			<div class="p-2 mb-3 text-center">
  				<!-- Quiz Question Top Banner -->
  				<ins class="adsbygoogle" style="display:inline-block;width:728px;height:90px"
  					data-ad-client="ca-pub-4875339972925852" data-ad-slot="6892933992"></ins>
  				<script>
  					(adsbygoogle = window.adsbygoogle || []).push({});
  				</script>
  			</div>
  			<div class="row">
  				<div class="col-7 col-md-3 text-md-right">
					<h1 class="h3" id="nomor"></h1>
  				</div>
  				<div class="col-5 col-md-3 col-lg-2 text-right text-md-left order-md-2">
  					<div id="post-actions-49887"> </div>
  				</div>
  				<div class="col-md-6 col-lg-7 order-md-1">
  					<div class="row">
  						<div class="col-12">
  							<div class="post-description-full">
  								<div class="mb-3">
								  	<div id="file"></div>
  									<h1 class="h3" id="soal"></h1>
  								</div>
  							</div>
  							<div class="post-alternatives-full mb-2">
  								<div class="mb-3">
  									<div class="media pt-2 pb-0 px-2">
  										<h5 class="mb-0 text-weight-normal">
  											<a class="btn btn-sm font-weight-normal mr-3 px-3 py-1 btn-light" data-remote="true" rel="nofollow" data-method="post" href="javascript:void(0)" onclick="doSelectAnswer('a')" id="btn_a">A</a>
  										</h5>
  										<div class="media-body">
  											<p id="choice_a"></p>
  										</div>
  									</div>
  									<div class="media pt-2 pb-0 px-2">
  										<h5 class="mb-0 text-weight-normal">
  											<a class="btn btn-sm font-weight-normal mr-3 px-3 py-1 btn-light" data-remote="true" rel="nofollow" data-method="post" href="javascript:void(0)" onclick="doSelectAnswer('b')" id="btn_b">B</a>
  										</h5>
  										<div class="media-body">
  											<p id="choice_b"></p>
  										</div>
  									</div>
  									<div class="media pt-2 pb-0 px-2">
  										<h5 class="mb-0 text-weight-normal">
  											<a class="btn btn-sm font-weight-normal mr-3 px-3 py-1 btn-light" data-remote="true" rel="nofollow" data-method="post" href="javascript:void(0)" onclick="doSelectAnswer('c')" id="btn_c">C</a>
  										</h5>
  										<div class="media-body">
  											<p id="choice_c"></p>
  										</div>
  									</div>
  									<div class="media pt-2 pb-0 px-2">
  										<h5 class="mb-0 text-weight-normal">
  											<a class="btn btn-sm font-weight-normal mr-3 px-3 py-1 btn-light" data-remote="true" rel="nofollow" data-method="post" href="javascript:void(0)" onclick="doSelectAnswer('d')" id="btn_d">D</a>
  										</h5>
  										<div class="media-body">
  											<p id="choice_d"></p>
  										</div>
  									</div>
  									<div class="media pt-2 pb-0 px-2">
  										<h5 class="mb-0 text-weight-normal">
  											<a class="btn btn-sm font-weight-normal mr-3 px-3 py-1 btn-light" data-remote="true" rel="nofollow" data-method="post" href="javascript:void(0)" onclick="doSelectAnswer('e')" id="btn_e">E</a>
  										</h5>
  										<div class="media-body">
  											<p id="choice_e"></p>
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
		</div>
  		<div class="card-body bg-white" style="padding-top:90px;padding-bottom:60px;" id="divTimeOut" style="display:none;">
  			<div class="p-2 mb-3 text-center">
  				<!-- Quiz Question Top Banner -->
  				<ins class="adsbygoogle" style="display:inline-block;width:728px;height:90px"
  					data-ad-client="ca-pub-4875339972925852" data-ad-slot="6892933992"></ins>
  				<script>
  					(adsbygoogle = window.adsbygoogle || []).push({});
  				</script>
  			</div>
  			<div class="row">
  				<div class="col-7 col-md-3 text-md-right">
					<h1 class="h3" id="nomor"></h1>
  				</div>
  				<div class="col-5 col-md-3 col-lg-2 text-right text-md-left order-md-2">
  					<div id="post-actions-49887">
  					</div>
  				</div>
  				<div class="col-md-6 col-lg-7 order-md-1">
  					<div class="row">
  						<div class="col-12">
  							<div class="post-description-full">
  								<div class="mb-3">
  									<h1 class="h3">Anda telah selesai mengerjakan soal Pre Test, anda dapat mereview atau merevisi jawaban anda selama waktu masih tersedia</h1>
									<br>
									<small><i>Jika anda tidak merevisi jawaban anda, berarti anda akan mengakhiri sesi pre test dan anda dapat langsung ke langkah selanjutnya dengan klik tombol dibawah.</i></small>
									<br>
									<a class="btn btn-lg  btn-success" data-remote="true" rel="nofollow" data-method="post" href="javascript:void(0)" onclick="doEndLearning()">Lanjutkan</a>
  								</div>
  							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
            $nomor = 1;
			$noPre = $nomor-1;
			$noNext = $nomor+1;
		?>
  		<div class="fixed-bottom card-footer text-center bg-white">
			<div id="divFooter"></div>
  		</div>
  	</div>
<?php
	$waktu = 60*($waktuTest);
	$secondx = (!empty($waktu)?$waktu:0);
	// session_start();
	if (!isset($_SESSION['StartTime']))
	{
		$_SESSION['StartTime'] = time();
	}
	$Start_Time = $_SESSION['StartTime'];
	$ElapsedTime = $secondx - ((time()-$Start_Time) % $secondx);
	// $Mins = intval($ElapsedTime / 60);
	// $Secs = $ElapsedTime % 60;
	// echo $Mins.":".$Secs."||".$ElapsedTime."||".$_SESSION['StartTime']."||".time();
?>
<script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
<script type="text/javascript">
	$(document).ready(function(){
		var nomor = $('#nomorx').val();
		var jumlah_jawaban = $('#jumlah_jawaban').val();
		// alert(nomor+' / '+jumlah_jawaban);
		// if(jumlah_jawaban == nomor){
		// 	$('#divViewSoal').hide();
		// 	$('#divTimeOut').show();
			// goViewHasil();
		// }else{
			allViews();
		// }
	});
	function allViews()
	{
		// $('#divViewSoal').show();
		// $('#divTimeOut').hide();
		var id = $('#id_materi').val();
		var kode = $('#kode_materi').val();
		var cbx = getAjaxData("<?php echo base_url('learning/startPelatihan')?>", { id:id,kode_materi:kode });
		$('#kode_soal').val(cbx['kode_soal']);
		$('#percobaan').val(cbx['percobaan']);
		$('#nomorx').val(cbx['nomor']);
		$('#jumlah_jawaban').val(cbx['jumlah_jawaban']);
		$('#timeToShow').html(cbx['timeToShow']);
		$('#nama_materi').html(cbx['nama_materi']);
		$('#soal').html(cbx['soal']);
		$('#nomor').html(cbx['nomor']);
		$('#file').html(cbx['file']);
		$('#choice_a').html(cbx['choice_a']);
		$('#choice_b').html(cbx['choice_b']);
		$('#choice_c').html(cbx['choice_c']);
		$('#choice_d').html(cbx['choice_d']);
		$('#choice_e').html(cbx['choice_e']);
		$('#divFooter').html(cbx['footer']);
		$('#btn_a').css('background-color','#F8F9FA');
		$('#btn_b').css('background-color','#F8F9FA');
		$('#btn_c').css('background-color','#F8F9FA');
		$('#btn_d').css('background-color','#F8F9FA');
		$('#btn_e').css('background-color','#F8F9FA');
		var status = cbx['statusLearn'];
		if(status == 'selesai'){
			selesai();
		}else if(status == 'hasil_pretest'){
			doEndLearning();
		}else if(status == 'learn_materi'){
			doEndRingkasan();
		}else if(status == 'posttest' || status == 'selesai_posttest' || status == 'hasil_posttest' || status == 'learn_project'){
			doPostTest();
		}else if(status == 'selesai_learning'){
			doSelesaiTest();
		}else{
			belumSelesai();
		}
	}
	function selesai(){
		$('#divViewSoal').hide();
		$('#divTimeOut').show();
	}
	function belumSelesai(){
		$('#divViewSoal').show();
		$('#divTimeOut').hide();
	}
	function doSelectAnswer(answer)
	{
		var id_materi = $('#id_materi').val();
		var kode_materi = $('#kode_materi').val();
		var kode_soal = $('#kode_soal').val();
		var nomor = $('#nomorx').val();
		var percobaan = $('#percobaan').val();
		var datax = {
			id_materi:id_materi,
			kode_materi:kode_materi,
			kode_soal:kode_soal,
			answer:answer,
			nomor:nomor,
			percobaan:percobaan,
		};
		// alert(id_materi+' / '+kode_materi+' / '+kode_soal+' / '+answer);
    	submitAjaxCall("<?php echo base_url('learning/inputAnswer')?>", datax, 'status');
		allViews();
	}
	function goAnswer(answer)
	{
		var jumlah_jawaban = $('#jumlah_jawaban').val();
		if(answer > jumlah_jawaban){
			allViews();
		}else{
			belumSelesai();
			// $('#divViewSoal').show();
			// $('#divTimeOut').hide();
			var id = $('#id_materi').val();
			var kode = $('#kode_materi').val();
			var cbx = getAjaxData("<?php echo base_url('learning/goAnswerBefore')?>", { id:id,kode_materi:kode,answer:answer });
			$('#kode_soal').val(cbx['kode_soal']);
			$('#percobaan').val(cbx['percobaan']);
			$('#nomorx').val(cbx['nomor']);
			$('#timeToShow').html(cbx['timeToShow']);
			$('#nama_materi').html(cbx['nama_materi']);
			$('#soal').html(cbx['soal']);
			$('#nomor').html(cbx['nomor']);
			$('#file').html(cbx['file']);
			$('#choice_a').html(cbx['choice_a']);
			$('#choice_b').html(cbx['choice_b']);
			$('#choice_c').html(cbx['choice_c']);
			$('#choice_d').html(cbx['choice_d']);
			$('#choice_e').html(cbx['choice_e']);
			$('#divFooter').html(cbx['footer']);
			var jawaban = cbx['jawaban'];
			if(jawaban == 'a'){
				$('#btn_a').css('background-color','green');
				$('#btn_b').css('background-color','#F8F9FA');
				$('#btn_c').css('background-color','#F8F9FA');
				$('#btn_d').css('background-color','#F8F9FA');
				$('#btn_e').css('background-color','#F8F9FA');
			}else if(jawaban == 'b'){
				$('#btn_a').css('background-color','#F8F9FA');
				$('#btn_b').css('background-color','green');
				$('#btn_c').css('background-color','#F8F9FA');
				$('#btn_d').css('background-color','#F8F9FA');
				$('#btn_e').css('background-color','#F8F9FA');
			}else if(jawaban == 'c'){
				$('#btn_a').css('background-color','#F8F9FA');
				$('#btn_b').css('background-color','#F8F9FA');
				$('#btn_c').css('background-color','green');
				$('#btn_d').css('background-color','#F8F9FA');
				$('#btn_e').css('background-color','#F8F9FA');
			}else if(jawaban == 'd'){
				$('#btn_a').css('background-color','#F8F9FA');
				$('#btn_b').css('background-color','#F8F9FA');
				$('#btn_c').css('background-color','#F8F9FA');
				$('#btn_d').css('background-color','green');
				$('#btn_e').css('background-color','#F8F9FA');
			}else if(jawaban == 'e'){
				$('#btn_a').css('background-color','#F8F9FA');
				$('#btn_b').css('background-color','#F8F9FA');
				$('#btn_c').css('background-color','#F8F9FA');
				$('#btn_d').css('background-color','#F8F9FA');
				$('#btn_e').css('background-color','green');
			}
		}
	}
	function goViewHasil(){
		selesai();
	}
	function doEndLearning() {
		var id_materi = $('#id_materi').val();
		var percobaan = $('#percobaan').val();
		var kode_materi = $('#kode_materi').val();
		// alert(id_materi+'='+percobaan+'='+kode_materi);
		var datac = { id_materi: id_materi, percobaan: percobaan, kode_materi: kode_materi, };
		$.redirect("<?php echo base_url('learning/pagesViewRingkasan'); ?>", datac,);
	}
	function doEndRingkasan() {
		var id_materi = $('#id_materi').val();
		var percobaan = $('#percobaan').val();
		var kode_materi = $('#kode_materi').val();
		// alert(id_materi+'='+percobaan+'='+kode_materi);
		var datac = { id_materi: id_materi, percobaan: percobaan, kode_materi: kode_materi, };
		$.redirect("<?php echo base_url('learning/viewMateriPembelajaran'); ?>", datac,);
	}
	function doPostTest() {
		var id_materi = $('#id_materi').val();
		var percobaan = $('#percobaan').val();
		var kode_materi = $('#kode_materi').val();
		// alert(id_materi+'='+percobaan+'='+kode_materi);
		var datac = { id_materi: id_materi, percobaan: percobaan, kode_materi: kode_materi, };
		$.redirect("<?php echo base_url('learning/pagePostTest'); ?>", datac,);
	}
	function doSelesaiTest() {
		alert('Anda telah selesai mengerjakan pelatihan ini, tunggu atasan atau pembimbing menilai dan tunggu info selanjutnya. Terimakasih');
		setTimeout(function () {
			$.redirect("<?php echo base_url('kpages/task_to_follow'); ?>", null,);
		},600); 
	}
	var startTime = <?php echo $ElapsedTime; ?>;
	<?php $idx = $this->uri->segment(3);?>
	startCountdown(startTime, 'pageBeginCountdown', 'timestamp').then(value => alert(`Page has started: ${value}.`));
	function startCountdown(startFrom, bar, text)
	{
		return new Promise((resolve, reject) => {
			var countdownTimer = setInterval(() => {
				startFrom--;
				var days        = Math.floor(startFrom/24/60/60);
				var hoursLeft   = Math.floor((startFrom) - (days*86400));
				var hours       = Math.floor(hoursLeft/3600);
				var minutesLeft = Math.floor((hoursLeft) - (hours*3600));
  				var minutes     = Math.floor(minutesLeft/60);
				menit			= Math.floor(startFrom/60);
				second			= startFrom%60;
				if(hours < 10){
					hoursx = '0'+hours;
				}else{
					hoursx = hours;
				}
				if(minutes < 10){
					minutesx = '0'+minutes;
				}else{
					minutesx = minutes;
				}
				if(second < 10){
					secondx = '0'+second;
				}else{
					secondx = second;
				}
				var xx = (hoursx) + ':' + (minutesx) + ':' + (secondx);
				document.getElementById(bar).value = startFrom;
				document.getElementById(text).textContent = xx;
				$('#sisa_waktu').val(startFrom);
				if (startFrom <= 0) {
					doEndLearning();
				}
			}, 1000);
		});
	}
</script>