  <div class="content-wrapper">
    <section class="content-header">
      <h1>
        <i class="fa fa-database"></i> Master Data 
        <small>Master Izin / Cuti</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
        <li class="active">Master Izin / Cuti</li>
      </ol>
    </section>
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box box-success">
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fa fa-list"></i> Daftar Izin / Cuti</h3>
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
                          echo '<button class="btn btn-success" id="btn_tambah" type="button" data-toggle="collapse" data-target="#add" aria-expanded="false" aria-controls="import"><i class="fa fa-plus"></i> Tambah Izin / Cuti</button>';
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
                          <div class="col-md-12">
                            <div class="form-group">
                              <label>Kode Master Izin/Cuti</label>
                              <input type="text" placeholder="Masukkan Kode Izin / Cuti" id="data_kode_add" name="kode" class="form-control" required="required" value="">
                            </div>
                          </div>
                          <div class="col-md-12">
                            <div class="form-group">
                              <label>Nama Master Izin/Cuti</label>
                              <input type="text" placeholder="Masukkan Nama Izin / Cuti" id="data_name_add" name="nama" class="form-control field" required="required">
                            </div>
                          </div>
                          <div class="col-md-12">
                            <div class="form-group">
                              <label>Maksimal Izin/Cuti (Hari)</label>
                              <input type="number" placeholder="Masukkan Nilai Maksimal" id="data_maksimal_add" name="maksimal" class="form-control field" required="required">
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group">
                              <label>Potong Pengurang Penilaian</label>
                                <?php
                                $yesno[null] = 'Pilih Data';
                                $selpa = array(null);
                                $expa = array('class'=>'form-control select2','style'=>'width:100%;','required'=>'required','id'=>'data_potongpa_add');
                                echo form_dropdown('potong_pa',$yesno,$selpa,$expa);
                                ?>
                              <!-- <label>Potongan (Kali)</label>
                              <input type="number" placeholder="Masukkan Nilai Potongan" id="data_potongan_add" name="potongan_kali" class="form-control field" required="required"> -->
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group">
                              <label>Potong Upah</label>
                                <?php
                                $yesno[null] = 'Pilih Data';
                                $sel3 = array(null);
                                $ex3 = array('class'=>'form-control select2','style'=>'width:100%;','required'=>'required','id'=>'data_potongupah_add');
                                echo form_dropdown('potong_upah',$yesno,$sel3,$ex3);
                                ?>
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group">
                              <label>Penggajian (Satuan)</label>
                              <select class="form-control select2" name="satuan" id="data_satuan_add" style="width: 100%;"></select>
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group">
                              <label>Jenis (Izin/Cuti)</label>
                                <?php
                                $izincuti[null] = 'Pilih Data';
                                $sel4 = array(null);
                                $ex4 = array('class'=>'form-control select2','style'=>'width:100%;','required'=>'required','id'=>'data_jenis_add','onchange'=>'potongCuti(this.value)');
                                echo form_dropdown('jenis',$izincuti,$sel4,$ex4);
                                ?>
                            </div>
                          </div>
                          <div class="col-md-12" id="div_span_potong" style="display:none;">
                            <div class="form-group">
                              <label>Potong Cuti</label>
                                <?php
                                $yesno[null] = 'Pilih Data';
                                $sel5 = array(null);
                                $ex5 = array('class'=>'form-control select2','style'=>'width:100%;','required'=>'required','id'=>'potong_cuti_add');
                                echo form_dropdown('potong_cuti',$yesno,$sel5,$ex5);
                                ?>
                            </div>
                          </div>
                          <div class="col-md-12">
                              <div class="form-group">
                                <label>Besar Potongan Gaji (%)</label>
                                <input type="number" placeholder="Masukkan Berapa Persen Potongan Gaji" id="data_potongan_add" name="potongan" class="form-control field" required="required">
                              </div>
                          </div>
                          <div class="col-md-12">
                            <div class="form-group">
                              <label>Dokumen</label>
                              <select class="form-control select2" name="dokumen" id="data_dokumen_add" style="width: 100%;"></select>
                            </div>
                          </div>
                          <div class="col-md-12">
                            <div class="form-group">
                              <button type="button" onclick="do_add()" id="btn_add" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
                            </div>
                          </div>
                        </form>
                      </div>
                    </div>
                    <?php } 
                    ?>
                </div>
              </div>
              <br>
              <div class="row">
                <div class="col-md-12">
                  <!-- Data Begin Here -->
                  <div class="callout callout-info"><label><i class="fa fa-info-circle"></i> INFO </label><br>
                    <p>Ada Beberapa Jenis Master Izin / Cuti yang kode Master Harus di sesuaikan :</p>
                    <ul>
                      <li>
                        Kode Master <b>IMP</b> untuk nama master izin <b>IZIN MENINGGALKAN PEKERJAAN</b>
                      </li>
                      <li>
                        Kode Master <b>IZIN</b> untuk nama master izin <b>IZIN</b>
                      </li>
                      <li>
                        Kode Master <b>ISKD</b> untuk nama master izin <b>IZIN DENGAN SURAT DOKTER</b>
                      </li>
                    </ul>
                    <p>Jika Data terhapus harap disesuaikan Kode masternya</p>
                  </div>
                  <table id="table_data" class="table table-bordered table-striped table-responsive" width="100%">
                    <thead>
                      <tr>
                        <th>No.</th>
                        <th>Kode Master</th>
                        <th>Nama Master</th>
                        <th>Jenis</th>
                        <th>Maksimal Izin/Cuti</th>
                        <th>Potong Upah</th>
                        <th>Pengurang Penilaian</th>
                        <th>Satuan</th>
                        <th>Potong Cuti</th>
                        <th>Potongan Gaji (%)</th>
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
              <label class="col-md-6 control-label">Kode Master Izin/Cuti</label>
              <div class="col-md-6" id="data_kode_view"></div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Nama Master Izin/Cuti</label>
              <div class="col-md-6" id="data_name_view"></div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Maksimal Izin/Cuti</label>
              <div class="col-md-6" id="data_maksimal_view"></div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Potong Upah</label>
              <div class="col-md-6" id="data_potongupah_view"></div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Pengurang Penilaian</label>
              <div class="col-md-6" id="data_potongpa_view"></div>
            </div>
            <!-- <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Potongan</label>
              <div class="col-md-6" id="data_potongan_view"></div>
            </div> -->
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Penggajian (satuan)</label>
              <div class="col-md-6" id="data_satuan_view"></div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Jenis(Izin/Cuti)</label>
              <div class="col-md-6" id="data_jenis_view"></div>
            </div>
            <div class="form-group col-md-12" id="view_potong_cuti" style="display:none;">
              <label class="col-md-6 control-label">Potong Cuti</label>
              <div class="col-md-6" id="data_potong_view"></div>
            </div>
            <div class="form-group col-md-12">
                <label class="col-md-6 control-label">Besar Potongan Gaji (%)</label>
                <div class="col-md-6" id="data_potongan_view"></div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Dokumen</label>
              <div class="col-md-6" id="data_dokumen_view"></div>
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
              <label>Kode Master</label>
              <input type="text" placeholder="Masukkan Kode Master Izin/Cuti" id="data_kode_edit" name="kode" value="" class="form-control" required="required">
            </div>
            <div class="form-group">
              <label>Nama Master</label>
              <input type="text" placeholder="Masukkan Nama Master Izin/Cuti" id="data_name_edit" name="nama" value="" class="form-control" required="required">
            </div>
            <div class="form-group">
              <label>Maksimal Izin/Cuti</label>
              <input type="number" placeholder="Masukkan Nilai Maksimal Izin/Cuti" id="data_maksimal_edit" name="maksimal" value="" class="form-control" required="required">
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label>Potong Upah</label>
                    <?php
                    $sel1 = array(null);
                    $ex1 = array('class'=>'form-control select2','style'=>'width:100%;','required'=>'required','id'=>'data_potongupah_edit');
                    echo form_dropdown('potong_upah',$yesno,$sel1,$ex1);
                    ?>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>Penggajian(satuan)</label>
                    <select class="form-control select2" name="satuan" id="data_satuan_edit" style="width: 100%;"></select>
                </div>
              </div>
            </div>
            <div class="row">
              <!-- <div class="col-md-6">
                <div class="form-group">
                  <label>Potongan</label>
                  <input type="number" placeholder="Masukkan Potongan Izin / Cuti" id="data_potongan_edit" name="potongan_kali" value="" class="form-control field" required="required">
                </div>
              </div> -->
              <div class="col-md-6">
                <div class="form-group">
                  <label>Pengurang Penilaian</label>
                      <?php
                      $selpae = array(null);
                      $expae = array('class'=>'form-control select2','style'=>'width:100%;','required'=>'required','id'=>'data_potongpa_edit');
                      echo form_dropdown('potong_pa',$yesno,$selpae,$expae);
                      ?>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label>Jenis (Izin/Cuti)</label>
                      <?php
                      $sel2 = array(null);
                      $ex2 = array('class'=>'form-control select2','style'=>'width:100%;','required'=>'required','id'=>'data_jenis_edit','onchange'=>'potongCutiEdit(this.value)');
                      echo form_dropdown('jenis',$izincuti,$sel2,$ex2);
                      ?>
                </div>
              </div>
              <div class="col-md-12" id="div_span_potong_edit" style="display:none">
                <div class="form-group">
                  <label>Potong Cuti</label>
                    <?php
                    $sel2 = array(null);
                    $ex2 = array('class'=>'form-control select2','style'=>'width:100%;','required'=>'required','id'=>'data_potongcuti_edit');
                    echo form_dropdown('potong_cuti',$yesno,$sel2,$ex2);
                    ?>
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-group">
                  <label>Besar Potongan Gaji (%)</label>
                  <input type="number" placeholder="Masukkan Berapa Persen Potongan Gaji" id="data_potongan_edit" name="potongan" class="form-control field" required="required">
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-group">
                  <label>Dokumen</label>
                  <select class="form-control select2" name="dokumen" id="data_dokumen_edit" style="width: 100%;"></select>
                  <input type="text" id="hide_text_edit" class="hidex-text">
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
  //wajib diisi
  var table="master_izin";
  var column="id_master_izin";
  $(document).ready(function(){
    refreshCode();
    $('#table_data').DataTable( {
      ajax: {
        url: "<?php echo base_url('master/master_izin/view_all/')?>",
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
      {   targets: 1,
        width: '15%',
        render: function ( data, type, full, meta ) {
          return data;
        }
      },
      {   targets: 2,
        width: '15%',
        render: function ( data, type, full, meta ) {
          return data;
        }
      },
      {   targets: 3,
        width: '10%',
        render: function ( data, type, full, meta ) {
          return data;
        }
      },
      {   targets: 4,
        width: '10%',
        render: function ( data, type, full, meta ) {
          return ''+data+' Hari';
        }
      },
      {   targets: 5,
        width: '10%',
        render: function ( data, type, full, meta ) {
          return data;
        }
      },
      {   targets: 6,
        width: '10%',
        render: function ( data, type, full, meta ) {
          return data;
        }
      },
      {   targets: 7,
        width: '10%',
        render: function ( data, type, full, meta ) {
          return data;
        }
      },
      {   targets: 9,
        width: '10%',
        render: function ( data, type, full, meta ) {
          return data;
        }
      },
      {   targets: 11,
        width: '10%',
        render: function ( data, type, full, meta ) {
          return '<center>'+data+'</center>';
        }
      },
      //aksi
      {   targets: 12, 
        width: '5%',
        render: function ( data, type, full, meta ) {
          return '<center>'+data+'</center>';
        }
      },
      ]
    });
    $('#btn_tambah').click(function(){
      select_data('data_satuan_add',url_select,'master_sistem_penggajian','kode_master_penggajian','nama');
      select_data('data_dokumen_add',url_select,'master_dokumen','kode_dokumen','nama');
    });
  });
	function potongCuti(f) {
		setTimeout(function () {
			var name = $('#data_jenis_add').val();
			if(name == 'C') {
				$('#div_span_potong').show();
				$('#potong_cuti_add').attr('required','required');
			}else {
				$('#div_span_potong').hide();
				$('#potong_cuti_add').removeAttr('required');
				$('#potong_cuti_add').val('');
			}
		},100);
	}
	function potongCutiEdit(f) {
		setTimeout(function () {
			var name = $('#data_jenis_edit').val();
			if(name == 'C') {
				$('#div_span_potong_edit').show();
				$('#data_potongcuti_edit').attr('required','required');
			}else {
				$('#div_span_potong_edit').hide();
				$('#data_potongcuti_edit').removeAttr('required');
				$('#data_potongcuti_edit').val('');
			}
		},100);
	}
  function refreshCode() {
    kode_generator("<?php echo base_url('master/master_izin/kode');?>",'data_kode_add');
  }
  function refreshAdd() {
		$('#data_potongupah_add').val('').trigger('change');
		$('#data_satuan_add').val('').trigger('change');
		$('#data_jenis_add').val('').trigger('change');
		$('#potong_cuti_add').val('').trigger('change');
		$('#data_dokumen_add').val('').trigger('change');
  }
  function view_modal(id) {
    var data={id_master_izin:id};
    var callback=getAjaxData("<?php echo base_url('master/master_izin/view_one')?>",data);  
    $('#view').modal('show');
    $('.header_data').html(callback['nama']);
    $('#data_kode_view').html(callback['kode_master_izin']);
    $('#data_name_view').html(callback['nama']);
    $('#data_maksimal_view').html(callback['maksimal']);
    $('#data_potongupah_view').html(callback['potong_upah']);
    // $('#data_potongan_view').html(callback['potongan_kali']);
    $('#data_satuan_view').html(callback['nama_sistem_penggajian']);
    $('#data_jenis_view').html(callback['jenis']);
    $('#data_potong_view').html(callback['potong_cuti']);
    $('#data_potongpa_view').html(callback['ikut_pa_view']);
    $('#data_dokumen_view').html(callback['nama_dokumen']);
    $('#data_potongan_view').html(callback['potongan_view']);
    var jenis = callback['kode_jenis'];
    if(jenis=='C'){
				$('#view_potong_cuti').show();
    }else{
				$('#view_potong_cuti').hide();
    }
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
    select_data('data_dokumen_edit',url_select,'master_dokumen','kode_dokumen','nama');
    select_data('data_satuan_edit',url_select,'master_sistem_penggajian','kode_master_penggajian','nama');
    var id = $('input[name="data_id_view"]').val();
    var data={id_master_izin:id};
    var callback=getAjaxData("<?php echo base_url('master/master_izin/view_one')?>",data); 
    $('#view').modal('toggle');
    setTimeout(function () {
       $('#edit').modal('show');
    },600); 
    $('.header_data').html(callback['nama']);
    $('#data_id_edit').val(callback['id']);
    $('#data_kode_edit_old').val(callback['kode_master_izin']);
    $('#data_kode_edit').val(callback['kode_master_izin']);
    $('#data_name_edit').val(callback['nama']);
    $('#data_maksimal_edit').val(callback['maksimal_e']);
    $('#data_potongupah_edit').val(callback['kode_potong_upah']).trigger('change');
    $('#data_potongcuti_edit').val(callback['e_potong_cuti']).trigger('change');
    $('#data_potongpa_edit').val(callback['ikut_pa']).trigger('change');
    // $('#data_potongan_edit').val(callback['potongan_kali']);
    $('#data_potongan_edit').val(callback['potongan']);
    $('#data_jenis_edit').val(callback['kode_jenis']).trigger('change');
    $('#data_satuan_edit').val(callback['kode_master_penggajian']).trigger('change');
    $('#data_dokumen_edit').val(callback['kode_dokumen']).trigger('change');
  }
  function delete_modal(id) {
    var data={id_master_izin:id};
    var callback=getAjaxData("<?php echo base_url('master/master_izin/view_one')?>",data);
    var datax={table:table,column:column,id:id,nama:callback['nama']};
    loadModalAjax("<?php echo base_url('pages/load_modal_delete')?>",'modal_delete_partial',datax,'delete');
  }
  //doing db transaction
  function do_status(id,data) {
    var data_table={status:data};
    var where={id_master_izin:id};
    var datax={table:table,where:where,data:data_table};
    submitAjax("<?php echo base_url('global_control/change_status')?>",null,datax,null,null,'status');
    $('#table_data').DataTable().ajax.reload();
  }
  function do_edit(){
    if($("#form_edit")[0].checkValidity()) {
      submitAjax("<?php echo base_url('master/edt_master_izin')?>",'edit','form_edit',null,null);
      $('#table_data').DataTable().ajax.reload();
    }else{
      notValidParamx();
    } 
  }
  function do_add(){
    if($("#form_add")[0].checkValidity()) {
      submitAjax("<?php echo base_url('master/add_master_izin')?>",null,'form_add',"<?php echo base_url('master/master_izin/kode');?>",'data_kode_add');
      $('#table_data').DataTable().ajax.reload(function(){
        Pace.restart();
      });
      $('#form_add')[0].reset();
        refreshCode();
        refreshAdd();
    }else{
      notValidParamx();
    } 
  }
</script>