<meta http-equiv="cache-control" content="no-cache" />
<meta http-equiv="pragma" content="no-cache" />
<form id="form_nomor_add">
   <input type="hidden" name="id_karyawan" value="<?php echo $profile['id_karyawan'];?>">
	<div class="panel-heading bg-green"><i class="fa fa-phone"></i> Nomor Ponsel Penting</div>
	<div class="panel-body">
		<div class="form-group clearfix">
			<label class="col-sm-2 control-label">Nomor Ponsel</label>
			<div class="col-sm-10">
				<input type="text" name="no_hp" maxlength="15" class="form-control" pattern="\d*" maxlength="16" placeholder="Masukkan Nomor Handphone" value="<?php echo $profile['no_hp'];?>">
			</div>
		</div>
		<div class="form-group clearfix">
			<label class="col-sm-2 control-label">Nomor Ponsel Ayah</label>
			<div class="col-sm-10">
				<input type="text" name="no_hp_ayah" class="form-control" placeholder="Masukkan Nomor Ponsel Ayah"
					value="<?php echo $profile['no_hp_ayah'];?>">
			</div>
		</div>
		<div class="form-group clearfix">
			<label class="col-sm-2 control-label">Nomor Ponsel Ibu</label>
			<div class="col-sm-10">
				<input type="text" name="no_hp_ibu" class="form-control" placeholder="Masukkan Nomor Ponsel Ibu"
					value="<?php echo $profile['no_hp_ibu'];?>">
			</div>
		</div>
		<div class="form-group clearfix">
			<label class="col-sm-2 control-label">Nomor Ponsel Pasangan</label>
			<div class="col-sm-10">
				<input type="text" name="no_hp_pasangan" class="form-control" placeholder="Masukkan Nomor Ponsel Pasangan"
					value="<?php echo $profile['no_hp_pasangan'];?>">
			</div>
		</div>
	</div>
	<div class="panel-heading bg-yellow"><i class="fa fa-credit-card"></i> Nomor Penting Lainnya</div>
	<div class="panel-body">
		<div class="form-group clearfix">
			<label class="col-sm-2 control-label">NPWP</label>
			<div class="col-sm-10">
				<input type="text" name="npwp" class="form-control" maxlength="16" readonly="readonly" placeholder="Masukkan Nomor NPWP" value="<?php echo $profile['npwp'];?>">
			</div>
		</div>
		<div class="form-group clearfix">
			<label class="col-sm-2 control-label">BPJS-TK</label>
			<div class="col-sm-10">
				<input type="text" name="bpjstk" class="form-control" maxlength="16" readonly="readonly" placeholder="Masukkan Nomor BPJS-TK" value="<?php echo $profile['bpjstk'];?>">
			</div>
		</div>
		<div class="form-group clearfix">
			<label class="col-sm-2 control-label">BPJS-KES</label>
			<div class="col-sm-10">
				<input type="text" name="bpjskes" class="form-control" maxlength="16" readonly="readonly" placeholder="Masukkan Nomor BPJS-KES" value="<?php echo $profile['bpjskes'];?>">
			</div>
		</div>
		<div class="form-group clearfix">
			<label class="col-sm-2 control-label">Status Pajak</label>
			<div class="col-sm-10">
				<?php
				$status_pajak[null] = 'Pilih Data';
				$sel66 = array(null);
				$ex66 = array('class'=>'form-control select2','style'=>'width:100%;');
				echo form_dropdown('status_pajak',$status_pajak,$sel66,$ex66);
				?>
			</div>
		</div>
		<div class="form-group clearfix">
			<label class="col-sm-2 control-label">Nomor Rekening</label>
			<div class="col-sm-10">
				<input type="text" name="rekening" class="form-control" pattern="\d*" maxlength="16" readonly="readonly" placeholder="Masukkan Nomor Rekening" value="<?php echo $profile['rekening'];?>">
			</div>
		</div>
		<div class="form-group clearfix">
			<label class="col-sm-2 control-label">Nama Bank</label>
			<div class="col-sm-10">
				<input type="text" name="nama_bank" class="form-control" readonly="readonly" placeholder="Nama Bank" value="<?php echo $profile['nama_bank_emp'];?>">
				<!-- <select class="form-control select2" id="bank_id" name="bank" disable="disable" style="width: 100%;"></select> -->
			</div>
		</div>
	</div>
	<div class="form-group clearfix">
		<div class="col-sm-offset-2 col-sm-10">
			<button type="submit" class="btn btn-primary pull-right"><i class="fa fa-floppy-o"></i> Simpan</button>
		</div>
	</div>
</form>
<script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
<script type="text/javascript">
	$(document).ready(function () {
		form_property();all_property();
	})
	function data_no_penting() {
      submitForm('form_nomor_add');
		var url_select="<?php echo base_url('global_control/select2_global');?>";
		select_data('bank_id',url_select,'master_bank','kode','nama');
		$('#form_nomor_add #bank_id').val('<?php echo $profile['nama_bank']; ?>').trigger('change');
      	$('select[name="status_pajak"]').val('<?php echo $profile['status_pajak']; ?>').trigger('change');
	}
	function submitForm(form) {
		$('#'+form).validator().on('submit', function (e) {
			if (e.isDefaultPrevented()) {
				notValidParamx();
			} else {
				e.preventDefault();
				edit_nomor();
			}
		})
	}
	function edit_nomor() {
		var nik = $('#nik').val();
		submitAjax("<?php echo base_url('kemp/edit_nomor')?>",null,'form_nomor_add',null,null);
	}

</script>
