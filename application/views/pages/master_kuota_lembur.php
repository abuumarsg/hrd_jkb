  <style type="text/css">
    .box-id {
      position:relative;
      background:#C9E1FC;
      margin-left: 3px;
      margin-bottom: 3px;
      /* width: 470px;
      height: 610px; */
      width: 100%;
      height: 100%;
      padding: 6px 6px 6px 6px;
      float:left;
      border: 1px solid white;
      /* background-image: linear-gradient(#8fecff, white, white, white); */
      page-break-inside: avoid;
    }
    .box-id-total {
      position:relative;
      background:#64FB4B;
      margin-left: 3px;
      margin-bottom: 3px;
      width: 40%;
      height: 40%;
      padding: 6px 6px 6px 6px;
      float:right;
      border: 1px solid white;
      page-break-inside: avoid;
    }
    .data_detail{
      display: none;
      border-style: solid;
      border-width: 1px;
      border-radius: 3px;
      padding: 8px;
      border-color: #7F7F7F;
      overflow: auto;
      max-height: 200px;
      font-size:8pt;
      width: 300px;
      height: 100%;
    }
</style>
  <div class="content-wrapper">
    <section class="content-header">
      <h1>
        <i class="fa fa-database"></i> Master Data 
        <small>Master Kuota Lembur</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
        <li class="active"><i class="fas fa-clock"></i> Master Kuota Lembur</li>
      </ol>
    </section>
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box box-success">
            <div class="box-header with-border">
              <h3 class="box-title"><i class="fas fa-clock"></i> Daftar Kuota Lembur</h3>
              <div class="box-tools pull-right">
                <button class="btn btn-box-tool" onclick="reload_table('table_data')" data-toggle="tooltip" title="Refresh Table"><i class="fas fa-sync"></i></button>
                <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <div class="box-body">
              <div class="row">
                <div class="col-md-12">
                  <div id="accordion">
                    <div class="panel">
                      <div class="row">
                        <div class="col-md-12">
                          <div class="pull-left">
                            <?php if (in_array($access['l_ac']['add'], $access['access'])) {
                              // echo '<button class="btn btn-success" id="btn_tambah_bidang" type="button" data-toggle="collapse" data-target="#add" aria-expanded="false" aria-controls="import"><i class="fa fa-plus"></i> Tambah Data</button>';
                              echo '<button class="btn btn-success" id="btn_tambah" type="button" href="#addz" data-toggle="collapse" id="btn_tambah" data-parent="#accordion" style="margin-left: 5px;float: left;"><i class="fa fa-plus"></i> Tambah Data</button>';
                              echo '<button class="btn btn-info" id="btn_duplicate" type="button" href="#duplicate" data-toggle="collapse" id="btn_tambah" data-parent="#accordion" style="margin-left: 5px;float: left;"><i class="fa fa-copy"></i> Duplicate Data</button>';
                            }?>
                          </div>
                          <div class="pull-right" style="font-size: 8pt;">
                            <i class="fa fa-toggle-on stat scc"></i> Aktif<br>
                            <i class="fa fa-toggle-off stat err"></i> Tidak Aktif
                          </div>
                        </div>
                      </div>
                      <?php if(in_array($access['l_ac']['add'], $access['access'])){?>
                        <div class="collapse" id="addz">
                          <div class="row">
                            <div class="col-md-12">
                              <div class="box box-info">
                                <div class="box-body">
                                  <form id="form_transaksi">
                                    <div class="row">
                                      <div class="col-md-12">
                                        <div class="col-md-4">
                                          <div class="form-group">
                                            <label>Nama</label>
                                            <input type="text" placeholder="Masukkan Nama Kuota" id="data_name_add" name="nama" class="form-control" required="required">
                                          </div>
                                        </div>
                                        <div class="col-md-4">
                                          <div class="form-group">
                                            <label>Tanggal</label>
                                            <div class="has-feedback">
                                              <span class="fa fa-calendar form-control-feedback"></span>
                                              <input type="text" class="form-control date-range-notime" id="tanggal_harian_filter" name="tanggal" placeholder="Tanggal">
                                            </div>
                                            <!-- <label>Bulan</label> -->
                                            <?php
                                              // $bulan_ser = $this->formatter->getMonth();
                                              // $sel_ser = array(date('m'));
                                              // $ex_ser = array('class'=>'form-control select2', 'id'=>'bulan_add', 'style'=>'width:100%;','required'=>'required');
                                              // echo form_dropdown('bulan',$bulan_ser,$sel_ser,$ex_ser);
                                            ?>
                                          </div>
                                        </div>
                                        <div class="col-md-4">
                                          <div class="form-group">
                                            <label>Pilih Bagian</label>
                                            <select class="form-control select2" name="kode_bagian" id="kode_bagian_add" onchange="addChart2(this.value)" style="width: 100%;"></select>
                                          </div>
                                          <!-- <div class="form-group">
                                            <label>Tahun</label>
                                            <?php
                                              // $tahun_ser = $this->formatter->getYear();
                                              // $sels = array(date('Y'));
                                              // $exs = array('class'=>'form-control select2', 'id'=>'tahun_add', 'style'=>'width:100%;','required'=>'required');
                                              // echo form_dropdown('tahun',$tahun_ser,$sels,$exs);
                                            ?>
                                          </div> -->
                                        </div>
                                      </div>
                                      <div class="col-md-12">
                                        <div class="col-md-3"></div>
                                        <div class="col-md-6">
                                          <div class="form-group">
                                            <!-- <label>Pilih Bagian</label>
                                            <select class="form-control select2" name="kode_bagian" id="kode_bagian_add" onchange="addChart2(this.value)" style="width: 100%;"></select> -->
                                            <label>Jumlah Kuota (Jam)</label>
                                            <input type="number" placeholder="Masukkan Jumlah Kuota" id="data_jumlah_add" name="jumlah" class="form-control" required="required">
                                          </div>
                                          <p class="text-muted" style="color:red;"><span>Jika anda merubah Jumlah Kuota anda harus merubah ulang persentase per bagian</span></p>
                                        </div>
                                      </div>
                                      <div class="col-md-12">
                                        <table id="table_add" class="table table-bordered table-striped" width="100%">
                                          <thead>
                                            <tr class="bg-blue">
                                              <th>No</th>
                                              <th>Bagian</th>
                                              <th>Persen (%)</th>
                                              <th>Kuota (Jam)</th>
                                              <th>Aksi</th>
                                            </tr>
                                          </thead>
                                          <tbody id="tbody_data">
                                          </tbody>
                                        </table>
                                      </div>
                                    </div>
                                    <div class="row">
                                      <div class="col-md-12">
                                        <!-- <div class="box-id-total"> -->
                                          <div class="col-md-12">
                                            <div id="div_non_deposito">
                                              <table style="font-size:14pt;">
                                                <tr>
                                                  <th style="width:600px;"></th>
                                                  <th style="width:200px;">Persentase</th>
                                                  <th style="width:5px;"></th>
                                                  <th style="width:200px;">Total Jam</th>
                                                  <th style="width:50px;"></th>
                                                </tr>
                                                <tr>
                                                  <th style="width:600px;"></th>
                                                  <th style="width:200px;">
                                                    <input type="text" name="total_persen" id="total_persen" value="" class="form-control input-sm" style="text-align:right;margin-bottom:5px;font-size:14pt;" readonly>
                                                  </th>
                                                  <th style="width:5px;"></th>
                                                  <th style="width:200px;">
                                                    <input type="text" name="total_b2" id="total_b2" value="" class="form-control input-sm" style="text-align:right;margin-bottom:5px;font-size:14pt;" readonly>
                                                  </th>
                                                  <th style="width:50px;"></th>
                                                </tr>
                                                <!-- <tr>
                                                  <td></td> 
                                                  <th style="width:200px;">Total Kuota</th>
                                                  <th style="text-align:right;width:300px;">
                                                    <input type="text" name="total_b2" id="total_b2" value="" class="form-control input-sm" style="text-align:right;margin-bottom:5px;font-size:14pt;" readonly>
                                                  </th>
                                                  <input type="hidden" id="total_b" name="total_b" value="" class="form-control input-sm" style="text-align:right;margin-bottom:5px;" readonly>
                                                </tr>
                                                <tr>
                                                  <td></td>
                                                  <th style="width:200px;">Total Persen</th>
                                                  <th style="text-align:right;width:300px;">
                                                    <input type="text" name="total_b2" id="total_b2" value="" class="form-control input-sm" style="text-align:right;margin-bottom:5px;font-size:14pt;" readonly>
                                                  </th>
                                                  <input type="hidden" id="total_b" name="total_b" value="" class="form-control input-sm" style="text-align:right;margin-bottom:5px;" readonly>
                                                </tr> -->
                                              </table>
                                            </div>
                                          </div>
                                        <!-- </div> -->
                                      </div>
                                    </div>
                                  </form>
                                </div>
                                <div class="box-footer">
                                  <button type="botton" onclick="doTransaksi()" id="simpancetak" class="btn btn-lg btn-info pull-right"><i class="fa fa-floppy-o"></i> SIMPAN</button>
                                </div>
                              </div>
                            </div>
                          </div>
                          <!-- <div class="col-md-2"></div>
                          <div class="col-md-8">
                            <form id="form_add">
                              <div class="form-group">
                                <label>Kode Bank</label>
                                <input type="text" placeholder="Masukkan Kode Bank" id="data_kode_add" name="kode" class="form-control" required="required" value="" readonly="readonly">
                              </div>
                              <div class="form-group">
                                <label>Nama Bank</label>
                                <input type="text" placeholder="Masukkan Nama Bank" id="data_name_add" name="nama" class="form-control field" required="required">
                              </div>
                              <div class="form-group">
                                <button type="button" onclick="do_add()" id="btn_add" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
                              </div>
                            </form>
                          </div> -->
                        </div>
                        <div class="collapse" id="duplicate">
                          <div class="row">
                            <div class="col-md-12">
                              <div class="box box-danger">
                                <div class="box-header with-border">
                                  <h3 class="box-title"><i class="fa fa-copy"></i> Duplicate Data</h3>
                                </div>
                                <div class="box-body">
                                  <form id="form_duplicate">
                                    <div class="row">
                                      <div class="col-md-12">
                                        <div class="col-md-3"></div>
                                        <div class="col-md-6">
                                          <div class="callout callout-danger"><label><i class="fa fa-info-circle"></i> INFO</label><br>
                                            Duplicate data akan menduplikat detail dari kuota lembur sebelumnya mulai dari bagian sampai persentase. 
                                          </div>
                                          <div class="form-group">
                                            <label>Nama</label>
                                            <input type="text" placeholder="Masukkan Nama Kuota" name="nama" class="form-control" required="required">
                                          </div>
                                          <div class="form-group">
                                            <label>Tanggal</label>
                                            <div class="has-feedback">
                                              <span class="fa fa-calendar form-control-feedback"></span>
                                              <input type="text" class="form-control date-range-notime" name="tanggal" placeholder="Tanggal">
                                            </div>
                                          </div>
                                          <div class="form-group">
                                            <label>Jumlah Kuota (Jam)</label>
                                            <input type="number" placeholder="Masukkan Jumlah Kuota" id="jumlah_kuota_dup" name="jumlah" class="form-control" required="required">
                                          </div>
                                          <div class="form-group">
                                            <label>Pilih Kuota</label>
                                            <select class="form-control select2" name="kuota" id="kode_kuota_add" onchange="viewKuota(this.value)" style="width: 100%;"></select>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="row" id="div_duplicate" style="display:none;">
                                      <div class="col-md-12">
                                        <table id="table_duplicate" class="table table-bordered table-striped" width="100%">
                                          <thead>
                                            <tr class="bg-blue">
                                              <th>No</th>
                                              <th>Bagian</th>
                                              <th>Persen (%)</th>
                                              <th>Kuota (Jam)</th>
                                              <!-- <th>Aksi</th> -->
                                            </tr>
                                          </thead>
                                          <tbody>
                                          </tbody>
                                        </table>
                                      </div>
                                    </div>
                                  </form>
                                </div>
                                <div class="box-footer">
                                  <button type="botton" onclick="doDuplicate()" class="btn btn-lg btn-info pull-right"><i class="fa fa-floppy-o"></i> SIMPAN</button>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      <?php } ?>
                    </div>
                  </div>
                </div>
              </div>
              <br>
              <div class="row">
                <div class="col-md-12">
                  <!-- Data Begin Here -->
                  <table id="table_data" class="table table-bordered table-striped table-responsive" width="100%">
                    <thead>
                      <tr>
                        <th>No.</th>
                        <th>Nama</th>
                        <th>Tanggal</th>
                        <th>Jumlah Bagian</th>
                        <th>Total Kuota</th>
                        <th>Sisa Kuota</th>
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
              <label class="col-md-6 control-label">Kode Kuota Lembur</label>
              <div class="col-md-6" id="data_kode_view"></div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Nama Kuota Lembur</label>
              <div class="col-md-6" id="data_name_view"></div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Tanggal</label>
              <div class="col-md-6" id="data_tanggal_view"></div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Jumlah Bagian</label>
              <div class="col-md-6" id="data_jumlah_bag_view"></div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Total Kuota</label>
              <div class="col-md-6" id="data_total_view"></div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Sisa Kuota</label>
              <div class="col-md-6" id="data_sisa_view"></div>
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
					<div class="col-md-12">
						<div class="col-md-12" style="overflow: auto;">
							<div id="data_tabel_bag_view"></div>
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
            <label>Kode Kuota Lembur</label>
            <input type="text" placeholder="Masukkan Kode Kuota Lembur" id="data_kode_edit" name="kode" value="" class="form-control" required="required" readonly="readonly">
          </div>
          <div class="form-group">
            <label>Nama Kuota Lembur</label>
            <input type="text" placeholder="Masukkan Nama Kuota Lembur" id="data_name_edit" name="nama" value="" class="form-control" required="required">
          </div>
          <div class="form-group">
            <label>Tanggal</label>
            <div class="has-feedback">
              <span class="fa fa-calendar form-control-feedback"></span>
              <input type="text" class="form-control date-range-notime" id="tanggal_edit" name="tanggal" placeholder="Tanggal">
            </div>
          </div>
          <!-- <div class="form-group">
            <label>Bulan</label>
            <?php
              // $bulan_ser = $this->formatter->getMonth();
              // $sel_ser = array(date('m'));
              // $ex_ser = array('class'=>'form-control select2', 'id'=>'bulan_edit', 'style'=>'width:100%;','required'=>'required');
              // echo form_dropdown('bulan',$bulan_ser,$sel_ser,$ex_ser);
            ?>
          </div>
          <div class="form-group">
            <label>Tahun</label>
            <?php
              // $tahun_ser = $this->formatter->getYear();
              // $sels = array(date('Y'));
              // $exs = array('class'=>'form-control select2', 'id'=>'tahun_edit', 'style'=>'width:100%;','required'=>'required');
              // echo form_dropdown('tahun',$tahun_ser,$sels,$exs);
            ?>
          </div> -->
        </div>
        <div class="modal-footer">
          <button type="button" onclick="do_edit()" id="btn_edit" class="btn btn-success"><i class="fa fa-floppy-o"></i> Simpan</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
        </div>
      </form>
    </div>

  </div>
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
          <input type="hidden" id="data_column2_delete" name="column2">
          <input type="hidden" id="data_id2_delete" name="id2">
          <input type="hidden" id="data_table2_delete" name="table2">
          <p>Apakah anda yakin akan menghapus data dengan nama <b id="data_name_delete" class="header_data"></b> ?</p>
        </div>
      </form>
      <div class="modal-footer">
        <button type="button" onclick="do_delete()" class="btn btn-primary"><i class="fa fa-trash"></i> Hapus</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
      </div>
    </div>
  </div>
</div>
<script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
<script type="text/javascript">
  var table="master_kuota_lembur";
  var column="id_kuota_lembur";
  $(document).ready(function(){
    refreshAll();
    $('#table_data').DataTable( {
      ajax: {
        url: "<?php echo base_url('master/master_kuota_lembur/view_all/')?>",
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
				  return '<a onclick="detail_menu('+full[0]+')" style="color: #4286f4;cursor: pointer;"><i class="fa fa-eye"></i> '+data+'</a>'+
				  '<div class="data_detail" id="d_menu_'+full[0]+'" style="display: none;">'+full[9]+'</div>';
        }
      },
      {   targets: 6,
        width: '7%',
        render: function ( data, type, full, meta ) {
          return '<center>'+data+'</center>';
        }
      },
      //aksi
      {   targets: 7, 
        width: '5%',
        render: function ( data, type, full, meta ) {
          return '<center>'+data+'</center>';
        }
      },
      ]
    });
    getSelect2("<?php echo base_url('master/master_kuota_lembur/get_select2')?>",'kode_bagian_add');
    getSelect2("<?php echo base_url('master/master_kuota_lembur/get_select2_kuota')?>",'kode_kuota_add');
  });
  function refreshAll(){
    refreshCode();getChar();total_kuota();refreshSatuan();//viewKuotaAfter();
  }
  function detail_menu(id) {
    $('#d_menu_'+id).slideToggle('slow');
  }
	function refreshSatuan(){
		var jumlahChar=getAjaxData2("<?php echo base_url('master/getCart/get_jumlah_char')?>",null);
		for (let i = 1; i <= jumlahChar; i++) {
      $('#kuota'+i).on("blur",function(){
        var id = $('#id'+i).val();
				var kuota = $('#kuota'+i).val();
		    submitAjax("<?php echo base_url('master/getCart/getUpdateKuota')?>",null, {kuota:kuota,id:id}, null, null,'status','no');
				refreshAll();
				// var qtyx=getAjaxData2("<?php echo base_url('master/getCart/getUpdateKuota')?>",{kuota:kuota,id:id},'notif');
        // $('#table_add').DataTable().ajax.reload();
			});
			$('#persen'+i).on("blur",function(){
        var jumlah = $('#data_jumlah_add').val();
        if(jumlah == ''){
			    notValidParamxCustom('Harap Isi Jumlah Kuota (Jam) !');
        }else{
          var id = $('#id'+i).val();
          var persen = $('#persen'+i).val();
          submitAjax("<?php echo base_url('master/getCart/getPersenKuota')?>",null, {persen:persen,id:id,jumlah_kuota:jumlah}, null, null,'status','no');
          refreshAll();
        }
				// var persenx=getAjaxData("<?php echo base_url('master/getCart/getPersenKuota')?>",{persen:persen,id:id},'ntf');
        // $('#table_add').DataTable().ajax.reload();
			});
			// $('#qty'+i).on("input",function(){
			// 	var qty = $('#qty'+i).val();
			// 	var qtyx=getAjaxData("<?php echo base_url('master/getCart/getQtyKuota')?>",{qty:qty,id:id},'ntf');
      //   // $('#table_add').DataTable().ajax.reload();
			// 	refreshAll();
			// });
			// $('#persendup'+i).on("blur",function(){
      //   var jumlahx = $('#jumlah_kuota_dup').val();
      //   if(jumlahx == ''){
			//     notValidParamxCustom('Harap Isi Jumlah Kuota (Jam) !');
      //   }else{
      //     var idx = $('#iddup'+i).val();
      //     var persenx = $('#persendup'+i).val();
      //     submitAjax("<?php echo base_url('master/getCartForDuplicate/getPersenKuota')?>",null, {persen:persenx,id:idx,jumlah_kuota:jumlahx}, null, null,'status','no');
      //     refreshAll();
      //     // alert('ok');
      //   }
			// });
		}
  }
	function deleteChart(idx){
		var datax={id:idx};
		submitAjax("<?php echo base_url('master/getCart/delete')?>",null, datax, null, null,'status');
    $('#table_data').DataTable().ajax.reload();
		refreshAll();
	}
  function refreshCode() {
    kode_generator("<?php echo base_url('master/master_bank/kode');?>",'data_kode_add');
  }
	function addChart2(kode){
    var jumlah = $('#data_jumlah_add').val();
		var data = {kode_bagian:kode,jumlah_kuota:jumlah};
		submitAjax("<?php echo base_url('master/addToCartKuota')?>",null, data, null, null,'status','no');
		$('#table_add').DataTable().ajax.reload();
    refreshAll();
	}
	function getChar(){
		$('#table_add').DataTable({
			ajax: {
				url: "<?php echo base_url('master/getCart/view_all/')?>",
				type: 'POST',
				data: {
					access: "<?php echo base64_encode(serialize($access));?>"
				}
			},
			scrollX: true,
			destroy: true,
			columnDefs: [
				{
					targets: 0,
					width: '3%',
					render: function (data, type, full, meta) {
						return data;
					}
				},
				{
					targets: 4,
					width: '5%',
					render: function (data, type, full, meta) {
						return '<center>' + data + '</center>';
					}
				},
			]
		});
	}
	function total_kuota(){
		var total=getAjaxData("<?php echo base_url('master/getCart/total_kuota')?>",null);
		$('#total_b2').val(total['total_user']);
		$('#total_b').val(total['total_belanja']);
		$('#total_persen').val(total['total_persen']);
	}
	function doTransaksi(){
    if($("#form_transaksi")[0].checkValidity()) {
      submitAjax("<?php echo base_url('master/do_add_master_kuota')?>",null,'form_transaksi');
      $('#table_data').DataTable().ajax.reload(function(){
        Pace.restart();
      });
      $('#form_transaksi')[0].reset();refreshCode();getChar();refreshSatuan();
      $('#bulan_add').val('').trigger('change');
      $('#tahun_add').val('').trigger('change');
		  $('#kode_bagian_add').val('').trigger('change');
    }else{
      notValidParamx();
    } 
		// const count = submitAjaxCall("<?php //echo base_url('master/do_add_master_kuota')?>", 'form_transaksi');
		// var dataax = count['jumlah'];
		// dataax.forEach(function(entry) {
		// 	deleteChart(entry);
		// });
		// $('#data_pelanggan').val('0');
		// $('#nama_konsumen').val('');
		// $('#tot_diskon').val('0');
		// $('#jml_uang').val('0');
		// $('#kembalian').val('0');
		// $('#kode_pelanggan').val('').trigger('change');
		// getKode();
		// if(count['insert']){
		// 	$('#pSuccess').html('<h3><b><i class="fa fa-check"></i> Transaksi Berhasil</b></h3>');
		// 	$('#transaksi_success').show();
		// 	$('#btn_cetak').show();
		// 	$('#pSuccess').show();
		// 	var datax={kode:count['kode']};
		// 	var call = getAjaxData("<?php //echo base_url('transaksi/get_char/getLabelSuccess')?>", datax);
		// 	$('#k_kode').val(call['kode']);
		// 	$('#s_kode').html(call['kode']);
		// 	$('#s_nama').html(call['nama']);
		// 	$('#s_tgl_transaksi').html(call['tanggal']);
		// 	$('#tabel_sukses').html(call['tabel']);
		// 	$('#total_sukses').html(call['jumlah']);
		// 	$('#total_belanja').html(call['total_harga']);
		// 	$('#diskon_sukses').html(call['diskon']);
		// 	$('#tunai_sukses').html(call['bayar']);
		// 	$('#kembali_sukses').html(call['kembali']);
		// }else{
		// 	$('#transaksi_success').hide();
		// 	$('#btn_cetak').hide();
		// 	$('#pFailure').html('<h3><b><i class="fa fa-times"></i> Transaksi GAGAL</b></h3>');
		// 	$('#pFailure').show();
		// }
	}
  function view_modal(id) {
    var data={id_kuota_lembur:id};
    var callback=getAjaxData("<?php echo base_url('master/master_kuota_lembur/view_one')?>",data);  
    $('#view').modal('show');
    $('.header_data').html(callback['nama']);
    $('#data_kode_view').html(callback['kode']);
    $('#data_name_view').html(callback['nama']);
    $('#data_tanggal_view').html(callback['tanggal_view']);
    $('#data_jumlah_bag_view').html(callback['jumlah_bag']);
    $('#data_total_view').html(callback['total']);
    $('#data_sisa_view').html(callback['sisa']);
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
		$('#data_tabel_bag_view').html(callback['data_bag']);
  }
  function edit_modal() {
    var id = $('input[name="data_id_view"]').val();
    var data={id_kuota_lembur:id};
    var callback=getAjaxData("<?php echo base_url('master/master_kuota_lembur/view_one')?>",data); 
    $('#view').modal('toggle');
    setTimeout(function () {
       $('#edit').modal('show');
    },600); 
    $('.header_data').html(callback['nama']);
    $('#data_id_edit').val(callback['id']);
    $('#data_kode_edit_old').val(callback['kode']);
    $('#data_kode_edit').val(callback['kode']);
    $('#data_name_edit').val(callback['nama']);
    // $('#tanggal_edit').val(callback['tanggal_e']);
		$("#tanggal_edit").data('daterangepicker').setStartDate(callback['e_tanggal_mulai']);
		$("#tanggal_edit").data('daterangepicker').setEndDate(callback['e_tanggal_selesai']);
    // $('#bulan_edit').val(callback['bulan_e']);
    // $('#tahun_edit').val(callback['tahun_e']);
  }
  function delete_modal(id) {
    var data={id_kuota_lembur:id};
    var callback=getAjaxData("<?php echo base_url('master/master_kuota_lembur/view_one')?>",data);
    $('#delete').modal('show');
    $('#data_name_delete').html(callback['nama']);
    $('#data_column_delete').val(column);
    $('#data_id_delete').val(callback['id']);
    $('#data_table_delete').val(table);
    $('#data_column2_delete').val('kode_kuota_lembur');
    $('#data_id2_delete').val(callback['kode']);
    $('#data_table2_delete').val('detail_kuota_lembur');
  }
  function do_delete(){
    submitAjax("<?php echo base_url('global_control/delete')?>",'delete','form_delete',null,null);
    $('#table_data').DataTable().ajax.reload(function (){
      Pace.restart();
    });
  }
  function do_status(id,data) {
    var data_table={status:data};
    var where={id_kuota_lembur:id};
    var datax={table:table,where:where,data:data_table};
    submitAjax("<?php echo base_url('global_control/change_status')?>",null,datax,null,null,'status');
    $('#table_data').DataTable().ajax.reload();
  }
  function do_edit(){
    if($("#form_edit")[0].checkValidity()) {
      submitAjax("<?php echo base_url('master/edit_kuota_lembur')?>",'edit','form_edit',null,null);
      $('#table_data').DataTable().ajax.reload();
    }else{
      notValidParamx();
    } 
  }
	function viewKuota(sdf){  
    $('#div_duplicate').show();
    var jumlah = $('#jumlah_kuota_dup').val();
    var kode = $('#kode_kuota_add').val();
    if(jumlah == ''){
      notValidParamxCustom('Harap Isi Kolom Jumlah Kuota (Jam) !');
    }else{
      $('#table_duplicate').DataTable({
        ajax: {
          url: "<?php echo base_url('master/getCartForDuplicate/view_table/')?>",
          type: 'POST',
          data: {
            kode: kode, jumlah:jumlah
          }
        },
        scrollX: true,
        destroy: true,
        columnDefs: [
          {
            targets: 0,
            width: '3%',
            render: function (data, type, full, meta) {
              return data;
            }
          },
          {
            targets: 3,
            width: '15%',
            render: function (data, type, full, meta) {
              return '<center>' + data + '</center>';
            }
          },
        ]
      });
    }
    refreshSatuan();//viewKuotaAfter();
  }
	function doDuplicate(){
    if($("#form_duplicate")[0].checkValidity()) {
      submitAjax("<?php echo base_url('master/duplicate_master_kuota')?>",null,'form_duplicate');
      $('#table_data').DataTable().ajax.reload(function(){
        Pace.restart();
      });
      $('#form_duplicate')[0].reset();refreshCode();getChar();refreshSatuan();
		  $('#kode_kuota_add').val('').trigger('change');
      $('#div_duplicate').hide();
    }else{
      notValidParamx();
    } 
  }
	// function viewKuotaAfter(){
  //   $('#table_duplicate').DataTable({
  //     ajax: {
  //       url: "<?php echo base_url('master/getCartForDuplicate/view_table/')?>",
  //       type: 'POST',
  //       data: {
  //         kode: null, jumlah:null,after:'ya'
  //       }
  //     },
  //     scrollX: true,
  //     destroy: true,
  //     columnDefs: [
  //       {
  //         targets: 0,
  //         width: '3%',
  //         render: function (data, type, full, meta) {
  //           return data;
  //         }
  //       },
  //       {
  //         targets: 3,
  //         width: '15%',
  //         render: function (data, type, full, meta) {
  //           return '<center>' + data + '</center>';
  //         }
  //       },
  //     ]
  //   });
  // }
</script>