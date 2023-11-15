<meta http-equiv="cache-control" content="no-cache" />
<meta http-equiv="pragma" content="no-cache" />
<?php if (in_array($access['l_ac']['add'], $access['access'])) { ?>
<button type="button" id="btn_nformal" class="btn btn-success btn-sm" onclick="add_modal_pnf()">
	<i class ="fa fa-plus"></i> Tambah Pendidikan Non Formal
</button>
<div class="modal modal-default fade" id="add_pnf">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span></button>
        <h2 class="modal-title">Tambah Data</h2>
			</div>
			<form id="form_nformal_add">
				<div class="modal-body">
					<input type="hidden" name="nik" value="<?php echo $profile['nik'];?>">
					<div class="form-group clearfix">
						<label class="col-sm-4 control-label">Nama Kursus/Penghargaan</label>
						<div class="col-sm-8">
						<input type="text" class="form-control" name="nama_pnf" placeholder="Nama Kursus/Penghargaan" required>
						</div>
					</div>
					<div class="form-group clearfix">
						<label for="nama" class="col-sm-4 control-label">Tanggal Masuk</label>
						<div class="col-sm-8">
              <div class="has-feedback">
                <span class="fa fa-calendar form-control-feedback"></span>
						    <input type="text" name="tanggal_masuk_pnf" class="form-control pull-right date-picker" placeholder="Tanggal Masuk" readonly="readonly">
						  </div>
						</div>
					</div>
					<div class="form-group clearfix">
						<label class="col-sm-4 control-label">Sertifikat</label>
						<div class="col-sm-8">
						<input type="text" class="form-control" name="sertifikat_pnf" placeholder="Sertifikat">
						</div>
					</div>
					<div class="form-group clearfix">
						<label class="col-sm-4 control-label">Nama Lembaga</label>
						<div class="col-sm-8">
						<input type="text" class="form-control" name="nama_lembaga_pnf" placeholder="Nama Lembaga">
						</div>
					</div>
					<div class="form-group clearfix">
						<label class="col-sm-4 control-label">Alamat Kursus</label>
						<div class="col-sm-8">
						<input type="text" class="form-control" name="alamat_pnf" placeholder="Alamat Kursus" required>
						</div>
					</div>
					<div class="form-group clearfix">
						<label class="col-sm-4 control-label">Keterangan</label>
						<div class="col-sm-8">
						<input type="text" class="form-control" name="keterangan_pnf" placeholder="Keterangan">
						</div>
					</div>
				</div>
			</form>
			<div class="modal-footer">
          <button type="button" class="btn btn-primary" onclick="add_nformal()"><i class="fa fa-floppy-o"></i> Simpan</button>
				<!-- <input type="submit" class="btn btn-success" name="submit" value="Simpan Data"> -->
				<button type="button" class="btn btn-default pull-right" data-dismiss="modal"> Kembali</button>
			</div>
			<!-- <?php //echo form_close();?> -->
		</div>
	</div>
</div>
<?php } ?>

<div class="row">
  <div class="col-md-12" style="padding-top: 10px;">
		<table id="table_data_nformal" class="table table-bordered table-striped table-responsive" width="100%">
			<thead>
				<tr>
					<th>No</th>
					<th>Nama Kursus</th>
					<th>Sertifikat</th>
					<th>Nama Lembaga</th>
					<th>Aksi</th>
				</tr>
			</thead>
			<tbody>

			</tbody>
		</table>
	</div>
</div>

<!-- view -->
<div id="view_nformal" class="modal fade" role="dialog">
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
              <label class="col-md-6 control-label">Nama Pendidikan</label>
              <div class="col-md-6" id="nama_pnf"></div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Tanggal Masuk</label>
              <div class="col-md-6" id="tanggal_masuk_pnf"></div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Sertifikat</label>
              <div class="col-md-6" id="sertifikat_pnf"></div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Nama Lembaga</label>
              <div class="col-md-6" id="nama_lembaga_pnf"></div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Alamat</label>
              <div class="col-md-6" id="alamat_pnf"></div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Keterangan</label>
              <div class="col-md-6" id="keterangan_pnf"></div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Status</label>
              <div class="col-md-6" id="data_status_view">
                
              </div>
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
        <?php if (in_array($access['l_ac']['edt'], $access['access'])) {
          echo '<button type="submit" class="btn btn-info" onclick="edit_modal_nformal()"><i class="fa fa-edit"></i> Edit</button>';
        }?>
        <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
      </div>
    </div>
  </div>
</div>

<!-- edit -->
<div class="modal modal-default fade" id="edit_nformal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span></button>
				<h2 class="modal-title">Edit Data <b class="text-muted header_data"></b></h2>
			</div>
			<form id="form_nformal_edit">
				<div class="modal-body">
					<input type="hidden" name="id_k_pnf">
					<input type="hidden" name="nik" value="<?php echo $profile['nik'];?>">
					<div class="form-group clearfix">
					<label class="col-sm-3 control-label">Nama Kursus</label>
					<div class="col-sm-9">
					<input type="text" class="form-control" name="nama_pnf" placeholder="Nama Kursus/Penghargaan" required>
					</div>
					</div>
					<div class="form-group clearfix">
					<label for="nama" class="col-sm-3 control-label">Tanggal Masuk</label>
  					<div class="col-sm-9">
              <div class="has-feedback">
                <span class="fa fa-calendar form-control-feedback"></span>
  					     <input type="text" name="tanggal_masuk_pnf" class="form-control pull-right date-picker" placeholder="Tanggal Masuk" readonly="readonly">
  					 </div>
  					</div>
					</div>
					<div class="form-group clearfix">
					<label class="col-sm-3 control-label">Sertifikat</label>
					<div class="col-sm-9">
					<input type="text" class="form-control" name="sertifikat_pnf" placeholder="Sertifikat">
					</div>
					</div>
					<div class="form-group clearfix">
					<label class="col-sm-3 control-label">Nama Lembaga</label>
					<div class="col-sm-9">
					<input type="text" class="form-control" name="nama_lembaga_pnf" placeholder="Nama Lembaga">
					</div>
					</div>
					<div class="form-group clearfix">
					<label class="col-sm-3 control-label">Alamat Kursus</label>
					<div class="col-sm-9">
					<input type="text" class="form-control" name="alamat_pnf" placeholder="Alamat Kursus">
					</div>
					</div>
					<div class="form-group clearfix">
					<label class="col-sm-3 control-label">Keterangan</label>
					<div class="col-sm-9">
					<input type="text" class="form-control" name="keterangan_pnf" placeholder="Keterangan">
					</div>
					</div>
				</div>
			</form>
			<div class="modal-footer">
        <button type="button" class="btn btn-success" onclick="edit_nformal()"><i class="fa fa-floppy-o"></i> Simpan</button>
				<button type="button" class="btn btn-default pull-right" data-dismiss="modal"> Kembali</button>
			</div>
		</div>
	</div>
</div>

<!-- delete -->
<div id="delete_nformal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-sm modal-danger">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title text-center">Konfirmasi Hapus</h4>
      </div>
      <form id="form_delete_nformal">
        <div class="modal-body text-center">
          <input type="hidden" id="data_column_delete" name="column" value="id_k_pnf">
          <input type="hidden" id="data_id_delete" name="id">
          <input type="hidden" id="data_table_delete" name="table" value="karyawan_pnf">
          <p>Apakah anda yakin akan menghapus data dengan nama <b id="data_name_delete" class="header_data"></b> ?</p>
        </div>
      </form>
      <div class="modal-footer">
        <button type="button" onclick="do_delete_nformal()" class="btn btn-primary"><i class="fa fa-trash"></i> Hapus</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
      </div>
    </div>
  </div>
</div>

<script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
<script type="text/javascript">
	$(document).ready(function(){
   form_property();all_property();//data_non_formal();
	})
  function data_non_formal(){
    $('#table_data_nformal').DataTable().destroy();
    $('#table_data_nformal').DataTable( {
      ajax: {
        url: "<?php echo base_url().'employee/emp_nonformal/view_all/'.$profile['nik']; ?>",
        type: 'POST',
        data:{access:"<?php echo base64_encode(serialize($access));?>"}
      },
      scrollX: true,
      columnDefs: [
      {   targets: 0, 
        width: '5%',
        render: function ( data, type, full, meta ) {
          return '<center>'+(meta.row+1)+'.</center>';
        }
      },
      //aksi
      {   targets: 4, 
        width: '10%',
        render: function ( data, type, full, meta ) {
          return '<center>'+data+'</center>';
        }
      },
      ]
    });
  }
  function add_modal_pnf() {
    $('#add_pnf').modal('toggle');
    $('#add_pnf .header_data').html('Tambah Data Pendidikan Non Formal');
  }
  function add_nformal() {
    submitAjax("<?php echo base_url('employee/add_nformal')?>",'add_pnf','form_nformal_add',null,null);
    $('#table_data_nformal').DataTable().ajax.reload(function (){
      Pace.restart();
    });
  }
	function view_nformal(id) {
    var data={id_k_pnf:id};
    var callback=getAjaxData("<?php echo base_url().'employee/emp_nonformal/view_one/'.$profile['nik']; ?>",data);  
    $('#view_nformal').modal('toggle');
    $('#view_nformal input[name="data_id_view"]').val(callback['id']);
    $('#view_nformal .header_data').html(callback['nama_pnf']);
    $('#view_nformal #nama_pnf').html(callback['nama_pnf']);
    $('#view_nformal #tanggal_masuk_pnf').html(callback['getvtanggal_masuk_pnf']);
    $('#view_nformal #sertifikat_pnf').html(callback['sertifikat_pnf']);
    $('#view_nformal #nama_lembaga_pnf').html(callback['nama_lembaga_pnf']);
    $('#view_nformal #alamat_pnf').html(callback['alamat_pnf']);
    $('#view_nformal #keterangan_pnf').html(callback['keterangan_pnf']);
    var status = callback['status'];
    if(status==1){
      var statusval = '<b class="text-success">Aktif</b>';
    }else{
      var statusval = '<b class="text-danger">Tidak Aktif</b>';
    }
    $('#view_nformal #data_status_view').html(statusval);
    $('#view_nformal #data_create_date_view').html(callback['create_date']+' WIB');
    $('#view_nformal #data_update_date_view').html(callback['update_date']+' WIB');
    $('#view_nformal #data_create_by_view').html(callback['nama_buat']);
    $('#view_nformal #data_update_by_view').html(callback['nama_update']);
  }
  function edit_modal_nformal() {
    var id = $('#view_nformal input[name="data_id_view"]').val();
    var data={id_k_pnf:id};
    var callback=getAjaxData("<?php echo base_url().'employee/emp_nonformal/view_one/'.$profile['nik']; ?>",data); 
    $('#view_nformal').modal('toggle');
    setTimeout(function () {
     $('#edit_nformal').modal('show');
   },600); 
    $('#edit_nformal .header_data').html(callback['nama_pnf']);
    $('#edit_nformal input[name="id_k_pnf"]').val(callback['id']);
    $('#edit_nformal input[name="nik"]').val(callback['nik']);
    $('#edit_nformal input[name="nama_pnf"]').val(callback['nama_pnf']);
    $('#edit_nformal input[name="tanggal_masuk_pnf"]').val(callback['gettanggal_masuk_pnf']);
    $('#edit_nformal input[name="sertifikat_pnf"]').val(callback['sertifikat_pnf']);
    $('#edit_nformal input[name="nama_lembaga_pnf"]').val(callback['nama_lembaga_pnf']);
    $('#edit_nformal input[name="alamat_pnf"]').val(callback['alamat_pnf']);
    $('#edit_nformal input[name="keterangan_pnf"]').val(callback['keterangan_pnf']);
  }
  function edit_nformal() {
    submitAjax("<?php echo base_url('employee/edit_nformal')?>",'edit_nformal','form_nformal_edit',null,null);
    $('#table_data_nformal').DataTable().ajax.reload(function (){
      Pace.restart();
    });
  }
  function delete_nformal(id) {
    var data={id_k_pnf:id};
    $('#delete_nformal').modal('toggle');
    var callback=getAjaxData("<?php echo base_url().'employee/emp_nonformal/view_one/'.$profile['nik']; ?>",data);
    $('#delete_nformal #data_id_delete').val(callback['id']);
    $('#delete_nformal .header_data').html(callback['nama_pnf']);
  }
  function do_delete_nformal(){
    submitAjax("<?php echo base_url('global_control/delete')?>",'delete_nformal','form_delete_nformal',null,null);
    $('#table_data_nformal').DataTable().ajax.reload(function (){
      Pace.restart();
    });
  }
</script>