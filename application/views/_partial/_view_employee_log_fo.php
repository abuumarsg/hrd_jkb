<meta http-equiv="Pragma" content="no-cache">
<p style="color:red">Riwayat login kamu akan dihapus otomatis selama 6 bulan sekali</p>
<div class="row">
	<div class="col-md-12">
    <table id="table_data_log" class="table table-bordered table-striped table-responsive" style="width: 100%;">
			<thead>
			  <tr>
				  <th width="1%">No.</th>
				  <th width="25%">Tanggal Login</th>
			  </tr>
		  </thead>
		  <tbody>
		  </tbody>
		</table>
		<?php 
  		if ($jumlah_log > 0) { ?>
		<a onclick="modal_delete()" class="btn btn-flat btn-danger" id="btn_delete_log"><i class="fa fa-trash"></i> Hapus Riwayat Login</a>
		<?php } ?>
	</div>
</div>
<div id="delete_log" class="modal fade" role="dialog">
  <div class="modal-dialog modal-sm modal-danger text-center">
  	<div class="modal-content">
  		<div class="modal-header">
  			<button type="button" class="close" data-dismiss="modal">&times;</button>
  			<h4 class="modal-title">Konfirmasi Hapus</h4>
  		</div>
  		<div class="modal-body">
  			<p>Kamu yakin akan menghapus semua data riwayat login karyawan dengan nama <b><?php echo $profile['nama']; ?></b> ?</p>
  		</div>
  		<div class="modal-footer">
  			<form id="form_delete">
	  			<input type="hidden" name="id" value="<?php echo $profile['id_karyawan']; ?>">
	  			<button type="button" onclick="do_delete_all()" class="btn btn-primary"><i class="fa fa-trash"></i> Hapus</button>
	  			<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
	  		</form>
			</div>
  	</div>
  </div>
</div>
<script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
<script type="text/javascript">
	$(document).ready(function(){
		form_property();all_property();
	});
	function log_fo(){
    	$('#table_data_log').DataTable().destroy();
		$('#table_data_log').DataTable( {
			ajax: {
				url: "<?php echo base_url().'kemp/emp_log/view_all/'.$profile['nik']; ?>",
				type: 'POST'
			},
			scrollX: true,
			columnDefs: [
			{   targets: 0, 
				width: '5%',
				render: function ( data, type, full, meta ) {
					return '<center>'+(meta.row+1)+'.</center>';
				}
			}
			]
		});		
	}
	function modal_delete() {
		$('#delete_log').modal('toggle');
	}
	function do_delete_all(){
		if($("#form_delete")[0].checkValidity()) {
			submitAjax("<?php echo base_url('kemp/del_log')?>",'delete_log','form_delete',null,null);
			$('#table_data_log').DataTable().ajax.reload(function (){
				Pace.restart();
			});
			var callback=getAjaxData("<?php echo base_url().'kemp/emp_log/view_one/'.$profile['nik']; ?>",null);
			if(callback['jumlah_log']<1){
				$('#btn_delete_log').css('display','none');
			}else{
				$('#btn_delete_log').css('display','block');
			}
		}else{
			notValidParamx();
		} 
	}
</script>