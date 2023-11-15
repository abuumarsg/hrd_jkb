<div class="content-wrapper">
	<section class="content-header">
		<h1>
			<a class="back" onclick="window.history.back()"><i class="fa fa-chevron-circle-left "></i></a> <i class="fa fa-envelope"></i> Pesan
			<small class="message_judul"></small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
			<li><a href="<?php echo base_url('pages/read_all_notification');?>"><i class="fa fa-envelope"></i> Semua Pesan</a></li>
			<li class="active">Pesan <span class="message_judul"></span></li>
		</ol>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-info">
					<div class="box-header with-border">
						<h3 class="box-title"><span id="notif_tipe"></span></h3>

						<div class="box-tools pull-right">
							<button class="btn btn-box-tool" onclick="get_notif()" data-toggle="tooltip" title="Refresh Data"><i class="fas fa-sync"></i></button>
						</div>
					</div>
					<div class="box-body no-padding">
						<div class="mailbox-read-info">
							<h3 class="message_judul"></h3>
							<h5 id="message_dari"></h5>
							<p id="nama_jenis"></p>							
						</div>
						<div class="mailbox-controls with-border">

							<div class="mailbox-read-message" id="message_isi">

							</div>
						</div>
						<div class="box-footer">
							<ul class="mailbox-attachments clearfix" id="notif_attc">
							</ul>
						</div>
						<div class="box-footer">
							<button type="button" class="btn btn-danger pull-right" onclick="delete_modal()"><i class="fa fa-trash"></i> Delete</button>
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>
	<div id="delete" class="modal fade" role="dialog">
		<div class="modal-dialog modal-sm modal-danger">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title text-center">Konfirmasi Hapus</h4>
				</div>
				<form id="form_delete">
					<div class="modal-body text-center">
						<input type="hidden" id="data_column_delete" name="column">
						<input type="hidden" id="data_id_delete" name="id">
						<input type="hidden" id="data_table_delete" name="table">
						<p>Apakah anda yakin akan menghapus data dengan nomor <b id="data_name_delete" class="header_data"></b> ?</p>
					</div>
				</form>
				<div class="modal-footer">
					<button type="button" onclick="do_delete()" class="btn btn-primary"><i class="fa fa-trash"></i>
						Hapus</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
				</div>
			</div>
		</div>
	</div>
	<form id="form_status">
		<input type="hidden" id="id_pesan" name="id">
	</form>
	<script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
	<script>
		$(document).ready(function(){
			get_messages();
		});
		function get_messages() {
			var data={id:"<?= $this->codegenerator->decryptChar($this->uri->segment(3)) ?>"};
			var callback=getAjaxData("<?php echo base_url('employee/data_pesan/view_one')?>",data); 
			// if (callback.length == 0) {window.location.href="<?= base_url('pages/read_all_notification') ?>";}
			$('.message_judul').html(callback['judul']);
			$('#message_dari').html('Dari : '+callback['nama_karyawan']+'<span class="mailbox-read-time pull-right">'+callback['create']+' WIB</span>');
			$('#message_isi').html(callback['isi']);
			$('#nama_jenis').html(callback['nama_jenis']);
			$('#id_pesan').val(callback['id']);
			change_read();
		}
		function change_read(){
			var id = $('#id_pesan').val();
      		var datax={id:id};
      		submitAjax("<?php echo base_url('employee/status_read')?>",null,datax,null,null,'status');
		}
		function delete_modal() {
			var table="data_pesan";
			var column="id_pesan";
			var data={id:"<?= $this->codegenerator->decryptChar($this->uri->segment(3)) ?>"};
			$('#delete').modal('toggle');
			var callback=getAjaxData("<?php echo base_url('employee/data_pesan/view_one')?>",data);
			$('#delete #data_id_delete').val(callback['id']);
			$('#delete #data_column_delete').val(column);
			$('#delete #data_table_delete').val(table);
			$('#delete #data_name_delete').html(callback['judul']);
		}
		function do_delete(){
			var id = $('#id_pesan').val();
      		var datax={id:id};
      		submitAjax("<?php echo base_url('employee/status_delete_berita')?>",'delete',datax,null,null,'status');
			setTimeout(function () {
				window.location.href = "<?php echo site_url('pages/data_pesan')?>";
			},2000); 
		}
	</script>