<meta http-equiv="Pragma" content="no-cache">
<style type="text/css">
	.col-sm-10{
		padding-right: 0px;
	}
	.form-control-feedback{
		margin-top: 10px;
	}
</style>
<form id="form_pribadi_add">
	<input type="hidden" name="id_karyawan" value="<?php echo $profile['id_karyawan'];?>">
	<div class="form-group clearfix">
		<label class="col-sm-2 control-label"></label>
		<div class="col-sm-10">
			<b class="text-red">Kosongkan form isian bila tidak ada data</b>
		</div>
	</div>
	<div class="form-group clearfix">
		<label class="col-sm-2 control-label">Username dan NIK</label>
		<div class="col-sm-10">
			<input type="text" class="form-control" placeholder="Username" value="<?php echo $profile['nik'];?>" readonly="readonly" >
		</div>
	</div>
	<div class="form-group clearfix">
		<label class="col-sm-2 control-label">Nomor KTP</label>
		<div class="col-sm-10">
			<input type="number" name="no_ktp" class="form-control" placeholder="Masukkan Nomor KTP" value="<?php echo $profile['no_ktp'];?>" >
		</div>
	</div>
	<div class="form-group clearfix">
		<label class="col-sm-2 control-label">Nama</label>
		<div class="col-sm-10">
			<input type="text" name="nama" class="form-control" placeholder="Masukkan Nama" value="<?php echo $profile['nama'];?>" >
		</div>
	</div>

	<div class="form-group clearfix">
		<label class="col-sm-2 control-label">Email</label>
		<div class="col-sm-10">
			<input type="email" name="email" class="form-control" placeholder="Email" value="<?php echo $profile['email'];?>" >
		</div>
	</div>
	<div class="form-group clearfix">
		<label class="col-sm-2 control-label">No. HP</label>
		<div class="col-sm-10">
			<input type="text" name="no_hp" class="form-control" placeholder="No. HP" value="<?php echo $profile['no_hp'];?>" >
		</div>
	</div>
	<div class="form-group clearfix">
		<label class="col-sm-2 control-label">Jenis Kelamin</label>
		<div class="col-sm-10">
			<select class="form-control select2" name="kelamin" style="width: 100%;" >
				<option></option>
				<?php
					foreach ($kelamin as $kl => $vkl) {
						$select = '';
						if($profile['kelamin']==$kl){
							$select = 'selected';
						}
						echo '<option value="'.$kl.'" '.$select.'>'.$vkl.'</option>';
					}
				?>
			</select>
		</div>
	</div>

	<div class="form-group clearfix">
		<label for="nama" class="col-sm-2 control-label">Tempat Tanggal Lahir</label>
		<div class="col-sm-10">
			<input type="text" class="form-control" name="tempat_lahir" placeholder="Tempat Lahir" value="<?php echo $profile['tempat_lahir']; ?>" >
			<div class="input-group date">
				<div class="input-group-addon">
					<i class="fa fa-calendar"></i>
				</div>
				<input type="text" name="tgl_lahir" class="form-control pull-right date-picker" placeholder="Tanggal Lahir" value="<?php echo $this->formatter->getDateFormatUser($profile['tgl_lahir']); ?>" readonly="readonly"  >
			</div>
		</div>
	</div>
	<div class="form-group clearfix">
		<div class="col-sm-offset-2 col-sm-10">
			<button type="submit" class="btn btn-primary"><i class="fa fa-floppy-o"></i> Simpan</button>
		</div>
	</div>
</form>
<script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
<script type="text/javascript">
	$(document).ready(function(){
		form_property();all_property();
	})
	function update_fo(){
		$('#form_pribadi_add').validator().on('submit', function (e) {
			if (e.isDefaultPrevented()) {
				notValidParamx();
			} else {
				e.preventDefault(); 
				add_pribadi()
				return false;
			}
		})
		$('select[name="kelamin"]').val('<?php echo $profile['kelamin']; ?>').trigger('change');
	}
	function add_pribadi() {
		submitAjax("<?php echo base_url('kemp/edit_pribadi')?>",null,'form_pribadi_add',null,null);
		var callback=getAjaxData("<?php echo base_url('kemp/emppribadi')?>",null);
		$('#view_info #view_finger').html(callback['id_finger']);
		$('#view_info #view_ktp').text(callback['no_ktp']);
		$('#view_info #view_nama, .view_nama').html(callback['nama']);
		$('#view_info #view_nama_full, .view_nama_full').html(callback['nama_full']);
		$('.view_stkaryawan').html(callback['status_pegawai']);
		$('#view_info #view_email').html(callback['email']);
		$('#view_info #view_jk').html(callback['kelamin']);
		$('#view_info #view_tgllahir').html(callback['tempat_lahir']+', '+callback['tgl_lahir']);
		$('#view_info #view_ponsel').html(callback['no_hp']);
		$('#view_info #view_nikah').html(callback['status_nikah']);
		$('#view_info #view_tglupd').html(callback['update_date']);
		if(callback['foto']==''){
			if(callback['kodekelamin']=='l'){
				var foto = '<?php echo base_url(); ?>asset/img/user-photo/user.png';
			}else{
				var foto = '<?php echo base_url(); ?>asset/img/user-photo/userf.png';
			}
			var outputp = document.getElementById('fotop');
			var outputp = document.getElementById('fotok');
		}else{
			var foto ='<?php echo base_url(); ?>'+callback['foto'];
		}
		outputp.src = foto; 
	}
</script>