<div class="content-wrapper">
    <section class="content-header">
      <h1>
        <a href="<?php echo base_url('pages/master_aspek'); ?>" title="Kembali"><i class="fas fa-chevron-circle-left"></i></a>
        <i class="fa fa-database"></i> Master Data 
        <small>Kuisioner Aspek Sikap <?php echo $nama;?></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
        <li><a href="<?php echo base_url('pages/master_aspek');?>"><i class="fa fa-database"></i> Master Aspek Sikap</a></li>
        <li class="active">Kuisioner <?php echo $nama;?></li>
      </ol>
    </section>
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box box-success">
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-list"></i> Kuisioner <?php echo $nama;?></h3>
              <div class="box-tools pull-right">
                <button class="btn btn-box-tool" onclick="reload_table('table_data')" data-toggle="tooltip" title="Refresh Table"><i class="fas fa-sync"></i></button>
                <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
              	<div class="row">
					<div id="accordion">
						<div class="panel">
							<div class="col-md-12">
								<div class="row">
									<div class="col-md-12">
										<div class="pull-left">
											<?php 
											if (in_array('ADD', $access['access'])) {
												echo '<a href="#add" data-toggle="collapse" id="btn_tambah" data-parent="#accordion" class="btn btn-success "><i class="fa fa-plus"></i> Tambah Kuisioner</a>';
											}
											if (count($kuisioner) > 0) {
											?>
											<a href="#set" data-toggle="collapse" data-parent="#accordion" class="btn btn-warning"><i class="fas fa-exchange-alt"></i> Set Semua Batasan</a>
										<?php } ?>
										</div>
										<div class="pull-right" style="font-size: 8pt;">
											<i class="fa fa-toggle-on stat scc"></i> Aktif<br>
											<i class="fa fa-toggle-off stat err"></i> Tidak Aktif
										</div>
									</div>
								</div>
							</div>
							<div id="add" class="collapse">
								<br>
								<div class="col-md-2"></div>
								<div class="col-md-8">
									<form id="form_add">
										<div class="form-group">
											<label>Kode Kuisioner</label>
											<input type="text" placeholder="Masukkan Kode Kuisioner" name="kode"
												class="form-control" id="data_kode_add" readonly="readonly">
										</div>
										<div class="form-group">
											<label>Kuisioner</label>
											<textarea class="form-control" name="kuisioner" placeholder="Kuisioner"
												required="required"></textarea>
										</div>
										<div class="form-group">
											<label>Definisi</label>
											<textarea class="form-control textarea" name="definisi" placeholder="Definisi"
												required="required"></textarea>
										</div>
										<div class="form-group">
											<label>Tipe Form Untuk Jabatan</label>
											<?php
											$sel = array(NULL);
											$ex = array('class'=>'form-control-feedback select2','placeholder'=>'Tipe Jabatan','id'=>'tipe_jabatan_add','required'=>'required','style'=>'width:100%');
											echo form_dropdown('tipe',$tipe_jabatan,$sel,$ex);
											?>
										</div>
										<div class="panel panel-primary">
											<div class="panel-heading">Batasan Poin KPI</div>
											<div class="panel-body">
												<p class="text-muted" style="padding-left: 10px;">Kosongkan jika tidak ada poin dan satuan!</p>
												<?php 
													for ($i=1; $i < 6; $i++) { 
												?>
												<div class="col-md-4">
													<label>Poin <?php echo $i; ?></label>
													<input type="number" placeholder="Masukkan Poin <?php echo $i; ?>" name="poin_<?php echo $i; ?>" class="form-control poin_add" onkeyup="countbobot('.poin_add','#min_add','#max_add','#btn_add','#xmin_add','#xmax_add')" onclick="countbobot('.poin_add','#min_add','#max_add','#btn_add','#xmin_add','#xmax_add')" value="0">
												</div>
												<div class="col-md-8">
													<label>Satuan <?php echo $i; ?></label>
													<input type="text" placeholder="Masukkan Satuan <?php echo $i; ?>"
														name="satuan_<?php echo $i; ?>" class="form-control">
												</div>
												<?php } ?>
											</div>
										</div>
										<div class="form-group">
											<label>Batasan Nilai</label>
											<div class="row">
												<div class="col-md-6">
													<label>Batas Bawah (%)</label>
													<input type="number" max="100" min="0" placeholder="Batas Bawah" name="bawah" id="min_add" onkeyup="checkbataskey('add','min')" onblur="checkbatasblur('add')" class="form-control" value="0">
													<input type="hidden" id="xmin_add" value="0">
													<span id="not_min_add" style="color: red;font-size: 9pt;display: none;">batas bawah kurang dan tidak boleh kosong</span>
												</div>
												<div class="col-md-6">
													<label>Batas Atas (%)</label>
													<input type="number" max="100" min="0" placeholder="Batas Atas" name="atas"
														id="max_add" onkeyup="checkbataskey('add','max')"
														onblur="checkbatasblur('add')" class="form-control" value="0">
													<input type="hidden" id="xmax_add" value="0">
													<span id="not_max_add" style="color: red;font-size: 9pt;display: none;">batas
														atas berlebihan dan tidak boleh kosong</span>
												</div>
											</div>
										</div>
										<div class="form-group">
											<input type="hidden" name="kode_aspek" value="<?php echo $kode;?>">
											<button type="button" onclick="do_add()" id="btn_add" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
										</div>
									</form>
								</div>
							</div>
							<?php if (count($kuisioner) > 0) { ?>
							<div id="set" class="collapse">
								<br>
								<div class="col-md-2"></div>
								<div class="col-md-8">
									<form id="form_batas_add">
										<div class="form-group">
											<label>Batasan Nilai</label>
											<div class="row">
												<div class="col-md-6">
													<label>Batas Bawah (%)</label>
													<input type="number" max="100" min="0" placeholder="Masukkan Batas Bawah" onkeyup="checkbataskey('edit')" onblur="checkbatasblur('batas_add')" name="bawah" class="form-control">
												</div>
												<div class="col-md-6">
													<label>Batas Atas (%)</label>
													<input type="number" max="100" min="0" placeholder="Masukkan Batas Atas" onkeyup="checkbataskey('edit')" onblur="checkbatasblur('batas_add')" name="atas" class="form-control">
												</div>
											</div>
										</div>
										<div class="form-group">
											<input type="hidden" name="kode" value="<?php echo $kode;?>">
											<button type="button" class="btn btn-success" onclick="do_batas_add()" id="btn_batas_add"><i class="fa fa-floppy-o"></i> Simpan</button>
										</div>
									</form>
								</div>
							</div>
							<?php }?>
						</div>
                	</div>
              	</div>
              <br>
              <div class="row">
                <div class="col-md-12">
                  <table id="table_data" class="table table-bordered table-striped table-responsive" width="100%">
                    <thead>
                      <tr>
                        <th>No.</th>
                        <th>Kode Kuisioner</th>
                        <th>Kuisioner</th>
                        <th>Definisi</th>
                        <th>Batasan Nilai</th>
                        <th>Tipe Jabatan</th>
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
              <label class="col-md-6 control-label">Kode Kuisioner</label>
              <div class="col-md-6" id="data_kode_view"></div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Kuisioner</label>
              <div class="col-md-6" id="data_name_view"></div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Definisi</label>
              <div class="col-md-6" id="data_definisi_view"></div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Batas Nilai</label>
              <div class="col-md-6" id="data_batas_view"></div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Tipe Jabatan</label>
              <div class="col-md-6" id="data_tipe_view"></div>
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
        <hr>
        <div class="row">
          <div class="col-md-3"></div>
          <div class="col-md-6">
            <h3 class="text-center">Rincian Point Kuisioner Aspek Sikap</h3>
            <table class="table table-hover table-striped">
              <thead>
                <tr class="bg-blue">
                  <th class="text-center">Point</th>
                  <th class="text-center">Satuan</th>
                </tr>
              </thead>
              <tbody id="data_tr_view">
              </tbody>
            </table>
            
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <?php if (in_array('EDT', $access['access'])) {
          echo '<button type="submit" class="btn btn-info" onclick="edit_modal()"><i class="fa fa-edit"></i> Edit</button>';
        }?>
        <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
      </div>
    </div>
  </div>
</div>

<!-- edit -->
<div id="edit" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h2 class="modal-title">Edit Data <b class="text-muted header_data"></b></h2>
      </div>
      <form id="form_edit">
        <div class="modal-body">
          <div class="callout callout-danger">
            <b><i class="fa fa-warning"></i> Peringatan</b><br>
            Edit data master Kuisioner akan berpengaruh pada <b>Agenda Sikap <b class="text-red">(yang belum dilakukan Validasi)</b></b>. Pastikan data diedit dengan benar!
          </div>
          <input type="hidden" id="data_id_edit" name="id" value="">
          <input type="hidden" id="data_kode_edit_old" name="kode_old" value="">
          <div class="form-group">
            <label>Kode Kuisioner</label>
            <input type="text" placeholder="Masukkan Kode Kuisioner" name="kode" class="form-control" id="data_kode_edit" readonly="readonly">
          </div>
          <div class="form-group">
            <label>Kuisioner</label>
            <textarea class="form-control" name="kuisioner" id="data_kuisioner_edit" placeholder="Kuisioner"></textarea>
          </div>
          <div class="form-group">
            <label>Definisi</label>
            <textarea class="form-control textarea" name="definisi" id="data_definisi_edit" placeholder="Definisi"></textarea>
          </div>
          <div class="form-group">
            <label>Tipe Form Untuk Jabatan</label>
            <?php
            $sel = array(NULL);
            $ex = array('class'=>'form-control select2','placeholder'=>'Tipe Jabatan','id'=>'tipe_jabatan_edit','required'=>'required','style'=>'width:100%');
            echo form_dropdown('tipe',$tipe_jabatan,$sel,$ex);
            ?>
          </div>
          <div class="clearfix" style="border-style: solid;border-width: 1px;">
            <p class="text-muted" style="padding-left: 10px;">Kosongkan jika tidak ada poin dan satuan!</p>
            <?php 
            for ($i=1; $i < 6; $i++) { 
            ?>
            <div class="col-md-4">
              <label>Poin <?php echo $i; ?></label>
              <input type="number" placeholder="Masukkan Poin <?php echo $i; ?>" name="poin_<?php echo $i; ?>" id="data_poin_<?php echo $i; ?>_edit" class="form-control poin_edit" onkeyup="countbobot('.poin_edit','#data_bawah_edit','#data_atas_edit','#btn_edit','#xmin_edit','#xmax_edit')" onclick="countbobot('.poin_edit','#data_bawah_edit','#data_atas_edit','#btn_edit','#xmin_edit','#xmax_edit')" value="0">
            </div>
            <div class="col-md-8">
              <label>Satuan <?php echo $i; ?></label>
              <input type="text" placeholder="Masukkan Satuan <?php echo $i; ?>" name="satuan_<?php echo $i; ?>" id="data_satuan_<?php echo $i; ?>_edit" class="form-control">
            </div>
          <?php } ?>
          </div>
          <div class="form-group">
            <label>Batasan Nilai</label>
            <div class="row">
              <div class="col-md-6">
                <label>Batas Bawah (%)</label>
                <input type="number" max="100" min="0" placeholder="Batas Bawah" name="bawah" onkeyup="checkbataskey('edit','min')" onblur="checkbatasblur('edit')" id="data_bawah_edit" class="form-control" value="0">
                <input type="hidden" id="xmin_edit" value="0">
                <span id="not_min_edit" style="color: red;font-size: 9pt;display: none;">batas bawah kurang dan tidak boleh kosong</span>
              </div>
              <div class="col-md-6">
                <label>Batas Atas (%)</label>
                <input type="number" max="100" min="0" placeholder="Batas Atas" name="atas" onkeyup="checkbataskey('edit','max')" onblur="checkbatasblur('edit')" id="data_atas_edit" class="form-control" value="0">
                <input type="hidden" id="xmax_edit" value="0">
                <span id="not_max_edit" style="color: red;font-size: 9pt;display: none;">batas atas berlebihan dan tidak boleh kosong</span>
              </div>
            </div>
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

<!-- delete -->
<div id="modal_delete_partial"></div>
<script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
  <script type="text/javascript">
    var url_select="<?php echo base_url('global_control/select2_global');?>";
    var table="master_kuisioner";
    var column="id_kuisioner";
    $(document).ready(function(){
      refreshCode();
      $('#table_data').DataTable( {
        ajax: {
          url: "<?php echo base_url('master/master_kuisioner/view_all/'.$kode)?>",
          type: 'POST',
          data:{access:"<?php echo $this->codegenerator->encryptChar($access);?>"}
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
        {   targets: [6,7,8], 
          width: '5%',
          render: function ( data, type, full, meta ) {
            return '<center>'+data+'</center>';
          }
        },
        ]
      });
    })
    function checkbataskey(kode,kmx) {
      var atas = $('#form_'+kode+' input[name="atas"]').val();
      var bawah = $('#form_'+kode+' input[name="bawah"]').val();
      if(bawah>=atas){
        $('#btn_'+kode).attr('disabled','disabled');
      }else{
        var minx = $('#xmin_'+kode).val();
        var maxx = $('#xmax_'+kode).val();
        if(parseInt(bawah)<parseInt(minx) || parseInt(atas)>parseInt(maxx) || bawah=='' || atas==''){
          if(kmx=='min'){
            if(parseInt(bawah)<parseInt(minx) || bawah==''){
              $('#not_min_'+kode).css('display','block');
            }else{
              $('#not_min_'+kode).css('display','none');
            }
          }else{
            if(parseInt(atas)>parseInt(maxx) || atas==''){
              $('#not_max_'+kode).css('display','block');
            }else{
              $('#not_max_'+kode).css('display','none');
            }
          }
          $('#btn_'+kode).attr('disabled','disabled');
        }else{
          $('#btn_'+kode).removeAttr('disabled');
          $('#not_min_'+kode).css('display','none');
          $('#not_max_'+kode).css('display','none');
        }
      }
    }
    function checkbatasblur(kode) {
      var atas = $('#form_'+kode+' input[name="atas"]').val();
      var bawah = $('#form_'+kode+' input[name="bawah"]').val();
      if(bawah>=atas){
        notValidCustom('Batas Atas Tidak Boleh Lebih Kecil dari Batas Bawah!');
      }
    }
    function refreshCode() {
      kode_generator("<?php echo base_url('master/master_kuisioner/kode');?>",'data_kode_add');
    }

    function view_modal(id) {
      var data={id_kuisioner:id};
      var callback=getAjaxData("<?php echo base_url('master/master_kuisioner/view_one')?>",data);  
      $('#view').modal('show');
      $('.header_data').html(callback['nama']);
      $('input[name="data_id_view"]').val(callback['id']);
      $('#data_kode_view').html(callback['kode_kuisioner']);
      $('#data_name_view').html(callback['nama']);
      $('#data_definisi_view').html(callback['definisi']);
      $('#data_tipe_view').html(callback['tipe']);
      $('#data_batas_view').html(callback['bawah']+' - '+callback['atas']);
      $('#data_poin_1_view').html(callback['poin_1']);
      $('#data_satuan_1_view').html(callback['satuan_1']);
      $('#data_poin_2_view').html(callback['poin_2']);
      $('#data_satuan_2_view').html(callback['satuan_2']);
      $('#data_poin_3_view').html(callback['poin_3']);
      $('#data_satuan_3_view').html(callback['satuan_3']);
      $('#data_poin_4_view').html(callback['poin_4']);
      $('#data_satuan_4_view').html(callback['satuan_4']);
      $('#data_poin_5_view').html(callback['poin_5']);
      $('#data_satuan_5_view').html(callback['satuan_5']);
      $('#data_tr_view').html(callback['tr_table']);
      var status = callback['status'];
      if(status==1){
        var statusval = '<b class="text-success">Aktif</b>';
      }else{
        var statusval = '<b class="text-danger">Tidak Aktif</b>';
      }
      $('#data_status_view').html(statusval);
      $('#data_create_date_view').html(callback['create_date']+' WIB');
      $('#data_update_date_view').html(callback['update_date']+' WIB');
      $('#data_create_by_view').html(callback['nama_buat']);
      $('#data_update_by_view').html(callback['nama_update']);
    }
    function edit_modal() {
      var id = $('input[name="data_id_view"]').val();
      var data={id_kuisioner:id};
      var callback=getAjaxData("<?php echo base_url('master/master_kuisioner/view_one')?>",data); 
      $('#view').modal('toggle');
      setTimeout(function () {
         $('#edit').modal('show');
      },600); 
      $('.header_data').html(callback['nama']);
      $('#data_id_edit').val(callback['id']);
      $('#data_kode_edit_old').val(callback['kode_kuisioner']);
      $('#data_kode_edit').val(callback['kode_kuisioner']);
      addValueEditor('data_definisi_edit',callback['definisi']);
      $('#data_kuisioner_edit').val(callback['nama']);
      $('#tipe_jabatan_edit').val(callback['tipe_val']).trigger('change');
      $('#data_bawah_edit, #xmin_edit').val(callback['bawah']);
      $('#data_atas_edit, #xmax_edit').val(callback['atas']);
      var i;
      var x = 1;
      for (i = 0; i < 5; i++) {
        $('#data_poin_'+x+'_edit').val(callback['poin_'+x]);
        $('#data_satuan_'+x+'_edit').val(callback['satuan_'+x]);
        x++;
      }
    }

    function delete_modal(id) {
      var data={id_kuisioner:id};
      var callback=getAjaxData("<?php echo base_url('master/master_kuisioner/view_one')?>",data);
      var datax={table:table,column:column,id:id,nama:callback['nama']};
      loadModalAjax("<?php echo base_url('pages/load_modal_delete')?>",'modal_delete_partial',datax,'delete');
    }
    function do_status(id,data) {
      var data_table={status:data};
      var where={id_kuisioner:id};
      var datax={table:table,where:where,data:data_table};
      submitAjax("<?php echo base_url('global_control/change_status')?>",null,datax,null,null,'status');
      $('#table_data').DataTable().ajax.reload(function (){Pace.restart();},false);
    }
  function do_edit(){
    if($("#form_edit")[0].checkValidity()) {
      submitAjax("<?php echo base_url('master/edt_kuisioner')?>",'edit','form_edit',null,null);
      $('#table_data').DataTable().ajax.reload(function (){Pace.restart();},false);
    }else{
      notValidParamx();
    } 
  }
  function do_add(){
      if($("#form_add")[0].checkValidity()) {
        submitAjax("<?php echo base_url('master/add_kuisioner')?>",null,'form_add',"<?php echo base_url('master/master_kuisioner/kode');?>",'data_kode_add');
      $('#table_data').DataTable().ajax.reload(function (){Pace.restart();},false);
      $('#form_add')[0].reset();
      refreshCode();
      }else{
        notValidParamx();
      }
    }
    function do_batas_add(){
      if($("#form_batas_add")[0].checkValidity()) {
        submitAjax("<?php echo base_url('master/set_batasan_kuisioner')?>",null,'form_batas_add',null,null);
      $('#table_data').DataTable().ajax.reload(function (){Pace.restart();},false);
      $('#form_batas_add')[0].reset();
      refreshCode();
      }else{
        notValidParamx();
      }
    }
    function countbobot(kodex,min,max,btnx,minx,maxx) {
    var getBobot = $(kodex).map(function(){ 
      return this.value; 
    }).get();

    var nomor = getBobot;
    var maxv = Math.max.apply(Math,nomor); // 3
    var minv = nomor.filter(function (x) { return x != 0; }).reduce(function (a, b) { return Math.min(a, b); }, Infinity); 
    if(maxv>100 || minv<0){
      notValidParamxCustom('Poin Tidak Boleh Kurang dari 0 dan Tidak Boleh Lebih Dari 100');
      $(btnx).attr('disabled','disabled');
    }else{
      $(max).val(maxv);
      $(min).val(minv);
      $(maxx).val(maxv);
      $(minx).val(minv);
      $(btnx).removeAttr('disabled');
    }
  }
  </script>