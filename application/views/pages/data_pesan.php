<div class="content-wrapper">
    <section class="content-header">
      <h1>
        <i class="fa fa-envelope"></i> Semua Pesan
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fa fas fa-tachometer-alt"></i> Dashboard</a></li>
        <li class="active">Semua Pesan</li>
      </ol>
    </section>
    <section class="content">
        <div id="data_pesan"></div>
    </section>
</div>
<div id="del" class="modal fade" role="dialog">
	<div class="modal-dialog modal-sm modal-danger">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title text-center">Konfirmasi Hapus Untuk Saya</h4>
			</div>
			<div class="modal-body text-center">
				<p>Apakah anda yakin akan menghapus Semua data Pesan??</p>
			</div>
			<div class="modal-footer">
				<input type="hidden" name="kode">
                <button type="button" onclick="do_delete()" class="btn btn-primary"><i class="fa fa-trash"></i> Hapus</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
			</div>
		</div>
	</div>
</div>
<div id="del_d" class="modal fade" role="dialog">
	<div class="modal-dialog modal-sm modal-danger">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title text-center">Konfirmasi Hapus Dari Database</h4>
			</div>
			<div class="modal-body text-center">
				<p>Apakah anda yakin akan menghapus Semua data Pesan dari database??</p>
			</div>
			<div class="modal-footer">
				<button type="button" onclick="do_delete_db()" class="btn btn-primary"><i class="fa fa-trash"></i> Hapus</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
			</div>
		</div>
	</div>
</div>
<script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
<script type="text/javascript">
    $(document).ready(function() {
        dataPesan();
    });
    function dataPesan() {
        var callback=getAjaxData('<?php echo base_url('employee/data_pesan/view_all'); ?>',null);
        $('#data_pesan').html(callback['value']);
    }
    function do_delete(){
        var datax={id:null};
        submitAjax("<?php echo base_url('employee/del_all_pesan_user')?>",'del',datax,null,null,'status');
        setTimeout(function () {
            window.location.href = "<?php echo site_url('pages/data_pesan')?>";
        },2000); 
    }
    function do_delete_db(){
        var datax={id:null};
        submitAjax("<?php echo base_url('employee/del_all_pesan_database')?>",'del_d',datax,null,null,'status');
        setTimeout(function () {
            window.location.href = "<?php echo site_url('pages/data_pesan')?>";
        },2000); 
    }
</script>