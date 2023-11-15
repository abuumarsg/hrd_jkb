
<div class="content-wrapper">
   <section class="content-header">
      <h1>
         <i class="fa fa-database"></i> Master Data 
         <small>Master Tunjangan</small>
      </h1>
      <ol class="breadcrumb">
         <li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
         <li><a href="<?php echo base_url('pages/master_induk_tunjangan');?>"><i class="fa fa-list"></i> Master Induk Tunjangan</a></li>
         <li class="active">Master Tunjangan</li>
      </ol>
   </section>
   <section class="content">
      <div class="row">
         <div class="col-md-12">
            <div class="box box-success">
               <div class="box-header with-border">
                  <h3 class="box-title"><i class="fa fa-list"></i> Daftar <?php echo ucwords($nama); ?> </h3>
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
                                 <?php if (in_array($access['l_ac']['add'], $access['access'])) {
                                    echo '<button class="btn btn-success" id="btn_tambah" type="button" data-toggle="collapse" data-target="#add" aria-expanded="false" aria-controls="import"><i class="fa fa-plus"></i> Tambah Tunjangan</button>';
                                 }?>
                              </div>
                              <div class="pull-right" style="font-size: 8pt;">
                                 <i class="fa fa-toggle-on stat scc"></i> Aktif<br>
                                 <i class="fa fa-toggle-off stat err"></i> Tidak Aktif
                              </div>
                           </div>
                        </div>
                        <?php if(in_array($access['l_ac']['add'], $access['access'])){?>
                           <div class="collapse" id="add">
                              <br>
                              <div class="col-md-2"></div>
                              <div class="col-md-8">
                                 <form id="form_add">
                                    <div class="form-group">
                                       <label>Kode Tunjangan</label>
                                       <input type="text" placeholder="Masukkan Kode Grade" id="data_kode_add" name="kode" class="form-control" required="required" value="" readonly="readonly">
                                    </div>
                                    <div class="form-group">
                                       <input type="hidden" name="induk_tunjangan" value="<?php echo $this->codegenerator->decryptChar($this->uri->segment(3));?>">
                                       <label>Nama Tunjangan</label>
                                       <input type="text" placeholder="Masukkan Nama Grade" id="data_name_add" name="nama" class="form-control field" value="" required="required">
                                    </div>
                                    <!-- <div class="form-group">
                                       <label>Pilih Grade</label>
                                       <select class="form-control select2" name="induk_grade" id="data_induk_grade_add" style="width: 100%;" onchange="get_child(this.value,'grade','add')"></select>
                                    </div>
                                    <div class="form-group">
                                    	<label>Pilih Sub-Grade</label>
                                    	<select class="form-control select2" name="grade[]" id="data_grade_add" style="width: 100%;" multiple="multiple"><option></option></select>
                                    </div> -->
                                    <div class="form-group">
                                       <label>Pilih Karyawan</label>
                                       <select class="form-control select2" name="karyawan[]" id="data_karyawan_add" style="width: 100%;" multiple="multiple">
                                       </select>
                                    </div>
                                    <div class="form-group">
                                       <label>Input Nominal</label>
                                       <input type="text" placeholder="Masukkan Nominal Tunjangan" id="data_nominal_add" name="nominal" min="0" value="Rp. 0" step="0.01" class="form-control field input-money" required="required">
                                    </div>
                                    <div class="form-group">
                                       <label>Tahun</label>
                                       <select class="form-control select2" name="tahun" id="data_tahun_add" style="width: 100%;">
                                       	<?php
                                       		$year = $this->formatter->getYear();
                                       		echo '<option></option>';
                                       		foreach ($year as $key => $value) {
                                       			echo '<option value="'.$value.'">'.$value.'</option>';
                                       		}
                                       	?>
                                       </select>
                                    </div>
                                    <div class="form-group">
                                       <button type="button" onclick="do_add()" id="btn_add" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
                                    </div>
                                 </form>
                              </div>
                           </div>
                        <?php } ?>
                     </div>
                  </div>
                  <br>
                  <div class="row">
                     <div class="col-md-12">
                        <table id="table_data" class="table table-bordered table-striped table-responsive" width="100%">
                           <thead>
                              <tr>
                                 <th>No.</th>
                                 <th>Kode Tunjangan</th>
                                 <th>Nama Tunjangan</th>
                                 <th>Nominal</th>
                                 <th>Jumlah Karyawan</th>
                                 <th>Tahun</th>
                                 <th>Tanggal</th>
                                 <th>Status</th> 
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
   </section>
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
                     <label class="col-md-6 control-label">Kode Tunjangan</label>
                     <div class="col-md-6" id="data_kode_view"></div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Nama Tunjangan</label>
                     <div class="col-md-6" id="data_name_view"></div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Nominal</label>
                     <div class="col-md-6" id="data_nominal_view"></div>
                  </div>
                  <div class="form-group col-md-12">
                     <label class="col-md-6 control-label">Tahun</label>
                     <div class="col-md-6" id="data_tahun_view"></div>
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
               <div class="col-md-12">
               	<div style="text-align: center;"><h3>Detail Data</h3></div>
               	<div style="overflow-y: auto;height: 300px;">
               		<div class="col-md-3"></div>
               		<!-- <div class="col-md-5">
               			<div style="text-align: center;">Grade</div>
               			<div id="data_indukgrade_view"></div>
               		</div>
               		<div class="col-md-3">
               			<div style="text-align: center;">Sub-Grade</div>
               			<div id="data_grade_view"></div>
               			
               		</div> -->
               		<div class="col-md-6">
               			<div style="text-align: center;">Karyawan</div>
               			<div id="data_karyawan_view"></div>
               		</div>
               	</div>
               </div>
            </div>
         </div>
         <div class="modal-footer">
            <?php if (in_array($access['l_ac']['edt'], $access['access'])) {
               echo '<button type="submit" class="btn btn-info" onclick="edit_modal()"><i class="fa fa-edit"></i> Edit</button>';
            }?>
            <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
         </div>
      </div>
   </div>
</div>

<!-- edit -->
<div id="edit" class="modal fade" role="dialog">
   <div class="modal-dialog modal-md">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h2 class="modal-title">Edit Data <b class="text-muted header_data"></b></h2>
         </div>
         <div class="modal-body">
            <form id="form_edit">
               <input type="hidden" id="data_id_edit" name="id" value="">
               <input type="hidden" id="data_kode_edit_old" name="kode_old" value="">
               <div class="form-group">
               	<label>Kode Tunjangan</label>
               	<input type="text" placeholder="Masukkan Kode Grade" id="data_kode_edit" name="kode" class="form-control" required="required" value="" readonly="readonly">
               </div>
               <div class="form-group">
                  <label>Nama Tunjangan</label>
                  <input type="text" placeholder="Masukkan Nama Grade" id="data_name_edit" name="nama" value="" class="form-control" required="required">
               </div>
               <!-- <div class="form-group">
               	<label>Pilih Grade</label>
               	<select class="form-control select2" name="induk_grade" id="data_induk_grade_edit" style="width: 100%;" onchange="get_child(this.value,'grade','edit')"></select>
               </div>
               <div class="form-group">
               	<label>Pilih Sub-Grade</label>
               	<select class="form-control select2" name="grade[]" id="data_grade_edit" style="width: 100%;" multiple="multiple"><option></option></select>
               </div> -->
               <div class="form-group">
               	<label>Pilih Karyawan</label>
               	<select class="form-control select2" name="karyawan[]" id="data_karyawan_edit" style="width: 100%;" multiple="multiple">
               	</select>
               </div>
               <div class="form-group">
               	<label>Input Nominal</label>
               	<input type="text" placeholder="Masukkan Nominal Tunjangan" id="data_nominal_edit" name="nominal" min="0" value="0" step="0.01" class="form-control field input-money input-number" required="required">
               </div>
               <div class="form-group">
               	<label>Tahun</label>
               	<select class="form-control select2" name="tahun" id="data_tahun_edit" style="width: 100%;">
               		<?php
               		$year = $this->formatter->getYear('2010');
               		echo '<option></option>';
               		foreach ($year as $key => $value) {
               			echo '<option value="'.$value.'">'.$value.'</option>';
               		}
               		?>
               	</select>
               </div>
            </div>
            <div class="modal-footer">
               <button type="button" onclick="do_edit()" id="btn_edit" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
               <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
            </div>
         </form>
      </div>

   </div>
</div>
<input type="hidden" id="usage_jabatan_add" value="0">
<input type="hidden" id="usage_grade_add" value="0">
<input type="hidden" id="value_grade_add" value="0">
<!-- delete -->
<div id="modal_delete_partial"></div>
<script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
<script type="text/javascript">
  var url_select="<?php echo base_url('global_control/select2_global');?>";
  /*wajib diisi*/
  var table="master_tunjangan";
  var column="id_tunjangan";
  $(document).ready(function(){
    $('#table_data').DataTable( {
      ajax: {
        url: "<?php echo base_url('master/master_tunjangan/view_all/'.$this->uri->segment(3))?>",
        type: 'POST',
        data:{access:"<?php echo $this->codegenerator->encryptChar($access);?>"}
      },
      scrollX: true,
  		autoWidth: false,
      columnDefs: [
      {   targets: 0, 
        width: '5%',
        render: function ( data, type, full, meta ) {
          return '<center>'+(meta.row+1)+'.</center>';
        }
      },
      {   targets: 1,
        width: '15%',
        render: function ( data, type, full, meta ) {
          return data;
        }
      },
      {   targets: 7,
        width: '5%',
        render: function ( data, type, full, meta ) {
          return '<center>'+data+'</center>';
        }
      },
      /*aksi*/
      {   targets: 8,
        width: '10%',
        render: function ( data, type, full, meta ) {
          return '<center>'+data+'</center>';
        }
      },
      ]
    });
    $('#table_data').DataTable().ajax.reload();
    $('#btn_tambah').click(function(){
    	$('#form_add')[0].reset();
    	$('#data_induk_grade_add').val('').trigger('change');
    	$('#data_grade_add').val('').trigger('change');
    	$('#data_loker_add').val('').trigger('change');
    	$('#data_jabatan_add').val('').trigger('change');
    	$('#data_tahun_add').val('').trigger('change');
    	refreshCode();
		getSelect2("<?=base_url('employee/employee/get_select2')?>",'data_karyawan_add');
      $('#data_tahun_add').val('').trigger('change');
      $('#data_nominal_add').val('Rp. 0');
      $('#data_name_add').val('<?php echo ucwords($nama); ?>');
    })
  });
  function get_child(kode,usage,tox) {
  		if(usage == 'grade'){
  			if(kode == ''){
  				select_data('data_loker_'+tox,url_select,'master_loker','kode_loker','nama');
  				$('#data_grade_'+tox).html('<option></option>');
  			}else{
  				var data={induk_grade: kode, usage: usage};
  				var callback=getAjaxData("<?php echo base_url('master/get_child_tunjangan')?>",data); 
  				$('#data_grade_'+tox).html(callback['grade']);
  				$('#data_loker_'+tox).html(callback['loker']);
  				$('#usage_grade_'+tox).val('0');
  				$('#value_grade_'+tox).val(callback['count_grade']);
  			}
  		}else if(usage == 'loker'){
  			if(kode == ''){
  				get_child($('#data_induk_grade_'+tox).val(),'grade');
  			}else{
  				var data={grade: kode, induk_grade: $('#data_induk_grade_'+tox).val(), usage: usage};
  				var callback=getAjaxData("<?php echo base_url('master/get_child_tunjangan')?>",data); 
  				$('#data_loker_'+tox).html(callback['loker']);
  			}
  		}else if(usage == 'jabatan'){
  			if(kode == ''){
  				get_child($('#data_grade_'+tox).val(),'loker');
  			}else{
  				var data={grade: $('#data_grade_'+tox).val(), induk_grade: $('#data_induk_grade_'+tox).val(), loker: kode, usage: usage};
  				var callback=getAjaxData("<?php echo base_url('master/get_child_tunjangan')?>",data); 
  			}
  		}
  }
  function refreshCode() {
    kode_generator("<?php echo base_url('master/master_tunjangan/kode');?>",'data_kode_add');
  }
  function view_modal(id) {
    var data={id_tunjangan:id};
    var callback=getAjaxData("<?php echo base_url('master/master_tunjangan/view_info')?>",data);  
    $('#view').modal('show');
    $('.header_data').html(callback['nama']);
    $('#data_kode_view').html(callback['kode_tunjangan']);
    $('#data_name_view').html(callback['nama']);
    
    $('#data_indukgrade_view').html(callback['induk_grade'])
    $('#data_grade_view').html(callback['grade'])
    $('#data_karyawan_view').html(callback['jabatan'])
    $('#data_nominal_view').html(callback['nominal']);
    $('#data_tahun_view').html(callback['tahun']);
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
  }
  	function edit_modal() {
  		setTimeout(function () {
  			$('#edit').modal('show');
  		},600); 
		getSelect2("<?=base_url('employee/employee/get_select2')?>",'data_karyawan_edit');
  		var id = $('input[name="data_id_view"]').val();
  		var data={id_tunjangan:id};
  		var callback=getAjaxData("<?php echo base_url('master/master_tunjangan/view_one')?>",data); 
  		$('#view').modal('toggle');
  		$('.header_data').html(callback['nama']);
  		$('#data_id_edit').val(callback['id']);
  		$('#data_kode_edit_old').val(callback['kode_tunjangan']);
  		$('#data_name_edit').val(callback['nama']);
  		$('#data_kode_edit').val(callback['kode_tunjangan']);
  		$('#data_nominal_edit').val(callback['nominal']);
  		$('#data_tahun_edit').val(callback['tahun']).trigger('change');
  		$('#data_induk_grade_edit').val(callback['induk_grade']).trigger('change');
  		$('#data_grade_edit').val(callback['grade']).trigger('change');
  		$('#data_karyawan_edit').val(callback['tunjangan']).trigger('change');

  	}
  function delete_modal(id) {
    var callback=getAjaxData("<?php echo base_url('master/master_tunjangan/view_one')?>",{id_tunjangan:id});
    var datax={table:table,column:'kode_tunjangan',id:callback['kode_tunjangan'],nama:callback['nama']};
    loadModalAjax("<?php echo base_url('pages/load_modal_delete')?>",'modal_delete_partial',datax,'delete');
  }
  /*doing db transaction*/
  function do_status(id,data) {
  	var callback=getAjaxData("<?php echo base_url('master/master_tunjangan/view_one')?>",{id_tunjangan:id}); 

    var data_table={status:data};
    var where={kode_tunjangan:callback['kode_tunjangan']};
    var datax={table:table,where:where,data:data_table};
    submitAjax("<?php echo base_url('global_control/change_status')?>",null,datax,null,null,'status');
    $('#table_data').DataTable().ajax.reload();
  }
  function do_edit(){
    if($("#form_edit")[0].checkValidity()) {
      submitAjax("<?php echo base_url('master/edt_tunjangan')?>",'edit','form_edit',null,null);
      $('#table_data').DataTable().ajax.reload();
    }else{
      notValidParamx();
    } 
  }

  function do_add(){
    if($("#form_add")[0].checkValidity()) {
      submitAjax("<?php echo base_url('master/add_tunjangan')?>",null,'form_add',null,null);
      $('#table_data').DataTable().ajax.reload(function (){
        Pace.restart();
      });
      $('#form_add')[0].reset();
      $('#data_induk_grade_add').val('').trigger('change');
      $('#data_grade_add').val('').trigger('change');
      $('#data_loker_add').val('').trigger('change');
      $('#data_jabatan_add').val('').trigger('change');
      $('#data_tahun_add').val('').trigger('change');
      refreshCode();
    }else{
      notValidParamx();
    } 
  } 
</script>
