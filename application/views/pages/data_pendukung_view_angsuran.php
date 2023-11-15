<div class="row">
	<div class="col-md-12">
		<div class="box box-success">
			<div class="box-header with-border">
				<h3 class="box-title"><i class="fa fa-list"></i> Daftar Detail Angsuran</h3>
				<div class="box-tools pull-right">
					<button class="btn btn-box-tool" onclick="reload_table('table_data')" data-toggle="tooltip" title="Refresh Table"><i class="fas fa-sync"></i></button>
					<button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
					<button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i class="fa fa-times"></i></button>
				</div>
			</div>
			<div class="box-body">
				<div class="row">
					<div class="col-md-12">
						<div class="row">
							<div class="col-md-12">
								<div class="pull-left">
								</div>
								<div class="pull-right" style="font-size: 8pt;">
									<i class="fa fa-toggle-on stat scc"></i> Aktif<br>
									<i class="fa fa-toggle-off stat err"></i> Tidak Aktif
								</div>
							</div>
						</div>
					</div>
				</div>
				<br>
				<div class="row">
					<div class="col-md-12">
						<table id="table_data_ang" class="table table-bordered table-striped table-responsive" width="100%">
							<thead>
								<tr>
									<th>No.</th>
									<th>Kode Angsuran</th>
									<th>Kode Pinjaman</th>
									<th>Karyawan</th>
									<th>Di Angsur</th>
									<th>Besar Diangsur</th>
									<th>Keterangan</th>
									<th>Aksi</th>
								</tr>
							</thead>
							<tbody>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- view -->
<div id="view" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h2 class="modal-title">Detail Data <b class="text-muted header_data"></b></h2>
				<input type="hidden" name="data_id_view">
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Kode</label>
							<div class="col-md-6" id="data_kode_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Nama</label>
							<div class="col-md-6" id="data_karyawan_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Jabatan</label>
							<div class="col-md-6" id="data_jabatan_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Nominal</label>
							<div class="col-md-6" id="data_nominal_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Keterangan</label>
							<div class="col-md-6" id="data_keterangan_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Bulan Tahun</label>
							<div class="col-md-6" id="data_tanggal_view"></div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Status</label>
							<div class="col-md-6" id="data_status_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Dibuat Tanggal</label>
							<div class="col-md-6" id="data_create_date_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Diupdate Tanggal</label>
							<div class="col-md-6" id="data_update_date_view"></div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Dibuat Oleh</label>
							<div class="col-md-6" id="data_create_by_view">
							</div>
						</div>
						<div class="form-group col-md-12">
							<label class="col-md-6 control-label">Diupdate Oleh</label>
							<div class="col-md-6" id="data_update_by_view">
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
			</div>
		</div>
	</div>
</div>
<!-- delete -->
<div id="modal_delete_partial"></div>
<script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
<script type="text/javascript">
   var url_select="<?php echo base_url('global_control/select2_global');?>";
   var table="data_angsuran";
   var column="id_angsuran";
   $(document).ready(function(){
      refresh_tabel('pinjaman','all');
   });
   function refresh_tabel(kode,usage) {
      $('#table_data_ang').DataTable( {
         ajax: {
			url: "<?php echo base_url('cpayroll/data_angsuran/view_all/'.$this->uri->segment(4))?>",
            type: 'POST',
            data:{access:"<?php echo base64_encode(serialize($access));?>"}
         },
         scrollX: true,
         bDestroy: true,
         columnDefs: [
         {   targets: 0, 
            width: '5%',
            render: function ( data, type, full, meta ) {
               return '<center>'+(meta.row+1)+'.</center>';
            }
         },
         {   targets: 1,
            width: '10%',
            render: function ( data, type, full, meta ) {
               return data;
            }
         },
         {   targets: 6,
            width: '5%',
            render: function ( data, type, full, meta ) {
               return data;
            }
         },
         /*aksi*/
         {   targets: 7, 
            width: '10%',
            render: function ( data, type, full, meta ) {
               return '<center>'+data+'</center>';
            }
         },
         ]
      });
   }
   function refreshCode() {
         kode_generator("<?php echo base_url('cpayroll/master_pinjaman/kode');?>",'data_kode_add');
   }
   function view_modal(id) {
      if($('#key_btn_tambah').val() == 1){
         $('#btn_add_collapse').click();
      }
      $('#view_show').hide();
      $('#view_loading').show();
      $('#view').modal('show');
      var data={id_angsuran:id, mode: 'view'};
      var callback=getAjaxData("<?php echo base_url('cpayroll/data_angsuran/view_one')?>",data); 
      $('.header_data').html(callback['nama']);
      $('#data_kode_view').html(callback['kode']);
      $('#data_name_view').html(callback['nama']);
      $('#data_nominal_view').html(callback['nominal']);
      $('#data_keterangan_view').html(callback['keterangan']);
      $('#data_karyawan_view').html(callback['karyawan']);
      $('#data_jabatan_view').html(callback['jabatan']);
      $('#data_bagian_view').html(callback['bagian']);
      $('#data_loker_view').html(callback['loker']);
      $('#data_tanggal_view').html(callback['bulan']);
      var status = callback['status'];
      if(status==1){
         var statusval = '<b class="text-success">Aktif</b>';
      }else{
         var statusval = '<b class="text-danger">Tidak Aktif</b>';
      }
      $('#data_status_view').html(statusval);
      $('#data_create_date_view').html(callback['create_date']+' WIB');
      $('#data_update_date_view').html(callback['update_date']+' WIB');
      $('input[name="data_id_view"]').val(callback['id']);
      $('#data_create_by_view').html(callback['nama_buat']);
      $('#data_update_by_view').html(callback['nama_update']);

      $('#view_show').show();
      $('#view_loading').hide();
   }
  function delete_modal(id) {
    var data={id_angsuran:id};
    var callback=getAjaxData("<?php echo base_url('cpayroll/data_angsuran/view_one')?>",data);
    var datax={table:table,column:column,id:id,nama:callback['kode']};
    loadModalAjax("<?php echo base_url('pages/load_modal_delete')?>",'modal_delete_partial',datax,'delete');
  }
</script>