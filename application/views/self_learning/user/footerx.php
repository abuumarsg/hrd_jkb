
	<div id="view_modal_file" class="modal fade modal-open" role="dialog">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Detail File Data <b class="text-muted header_data"></b></h4>
					<input type="hidden" name="data_id_view">
				</div>
				<div class="modal-body">
					<div class="row">
					<div class="col-md-12">
						<div id="fileView"></div>
					</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
				</div>
			</div>
		</div>
	</div>
	<div id="modal_error" class="modal fade" role="dialog">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">Notifikasi Error</h4>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				<div class="modal-body">
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
</body>
		<script src="<?=base_url()?>asset/learning/js/jquery-3.2.1.min.js"></script>
		<script src="<?=base_url()?>asset/learning/js/bootstrap.min.js"></script>
		<script src="<?=base_url()?>asset/learning/js/jquery.slicknav.min.js"></script>
		<script src="<?=base_url()?>asset/learning/js/owl.carousel.min.js"></script>
		<script src="<?=base_url()?>asset/learning/js/mixitup.min.js"></script>
		<script src="<?=base_url()?>asset/learning/js/main.js"></script>
		<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>


	<!--===============================================================================================-->
		<script src="<?=base_url()?>asset/learning/tabel/js/main.js"></script>
	<!--===============================================================================================-->	
		<script src="<?=base_url()?>asset/learning/tabel/vendor/jquery/jquery-3.2.1.min.js"></script>
	<!--===============================================================================================-->
		<script src="<?=base_url()?>asset/learning/tabel/vendor/bootstrap/js/popper.js"></script>
		<script src="<?=base_url()?>asset/learning/tabel/vendor/bootstrap/js/bootstrap.min.js"></script>
	<!--===============================================================================================-->
		<script src="<?=base_url()?>asset/learning/tabel/vendor/select2/select2.min.js"></script>
	<!--===============================================================================================-->
		<script src="<?=base_url()?>asset/learning/tabel/vendor/perfect-scrollbar/perfect-scrollbar.min.js"></script>
		<script src="<?php echo base_url('asset/vendor/jquery.redirect-master/jquery.redirect.js');?>"></script>
		<script src="<?php echo base_url('asset/customs.js');?>"></script>
		<!-- <script src="<?php //echo base_url('asset/ajax.js');?>"></script> -->
		<script src="<?php echo base_url('asset/plugins/pace/pace.min.js');?>"></script>
		<script src="<?php echo base_url('asset/vendor/overhang/dist/overhang.min.js');?>"></script>
		<script src="<?php echo base_url('asset/vendor/validator/js/validator.js');?>"></script>
<script src="<?php echo base_url('asset/bower_components/jquery-ui/jquery-ui.min.js');?>"></script>
		<script>
			$('.js-pscroll').each(function(){
				var ps = new PerfectScrollbar(this);
				$(window).on('resize', function(){
					ps.update();
				})
			});
			function getAjaxData(urlx, where, method = "POST") {
				// Pace.restart();
				var viewx;
				$.ajax({
					url: urlx,
					method: method,
					data: where,
					async: false,
					dataType: 'json',
					success: function (data) {
						viewx = data;
						// console.clear();
					},
					error: function (data) {
						$("body").overhang({
							type: "error",
							message: fail + 'Invalid Parameter',
							html: true
						});
						show_modal_error(data.responseText);
					}
				});
				return viewx;
			}
			function submitAjaxCall(urlx, formx, usage=null){
				if (usage == 'status') {
					var data = formx;
				} else {
					var data = $('#' + formx).serialize();
				}
				var viewx;
				$.ajax({
					url: urlx,
					method: "POST",
					data: data,
					async: false,
					dataType: 'json',
					success: function (data) {
						viewx = data;
					},
					error: function (data) {
						show_modal_error(data.responseText);
					}
				});
				return viewx;
			}
			function show_modal_error(msg) {
				$('#modal_error .modal-body').html(msg);
				$('#modal_error').modal('show');
			}
			function submitAjaxFile(urlx, datax, modalx, progx, btnx, tabelx = 'table_data') {
				$.ajax({
					url: urlx,
					type: "post",
					data: datax,
					processData: false,
					contentType: false,
					cache: false,
					async: false,
					dataType: 'json',
					success: function (data) {
						// $(progx).hide();
						// $(btnx).removeAttr('disabled');
						// if (data.status_data == true) {
						// 	$("body").overhang({
						// 		type: "success",
						// 		message: data.msg,
						// 		html: true
						// 	});
						// } else if (data.status_data == 'warning') {
						// 	$("body").overhang({
						// 		type: "warn",
						// 		message: data.msg,
						// 		html: true
						// 	});
						// } else {
						// 	if (data.msg != null) {
						// 		$("body").overhang({
						// 			type: "error",
						// 			message: data.msg,
						// 			html: true
						// 		});
						// 	} else {
						// 		$("body").overhang({
						// 			type: "error",
						// 			message: fail + 'Invalid Parameter',
						// 			html: true
						// 		});
						// 	}
						// }
						// setTimeout(function () {
						// 	$(modalx).modal('toggle');
						// }, 1000);
						if(tabelx == '' || tabelx == null){
							$("body").overhang({
								type: "success",
								message: data.msg,
								html: true
							});
						}else{
							$('#' + tabelx).DataTable().ajax.reload(function () {
								Pace.restart();
							});
						}
					},
					error: function (data) {
						$("body").overhang({
							type: "error",
							message: fail + 'Invalid Parameter',
							html: true
						});
					}
				});
			}
		</script>
	</body>
</html>