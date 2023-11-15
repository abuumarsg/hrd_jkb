<?php
  //$color = $this->otherfunctions->getSkinColorText($adm['skin']);
?>
<style type="text/css">
  .wordwrap { 
     white-space: pre-wrap;      /* CSS3 */   
     white-space: -moz-pre-wrap; /* Firefox */    
     white-space: -pre-wrap;     /* Opera <7 */   
     white-space: -o-pre-wrap;   /* Opera 7 */    
     word-wrap: break-word;      /* IE */
  }
</style>
<fieldset>
<legend class="legend"><i class="fas fa-user-cog"></i> Mutasi/Promosi/Demosi</legend>
</fieldset>
<div class="row">
  <div class="col-md-12">
    <div id="data_tabel_mutasi"></div>
  </div>
</div>

<fieldset>
<legend class="legend"><i class="fas fa-file-contract"></i> Perjanjian Kerja</legend>
</fieldset>
<div class="row">
  <div class="col-md-12">
    <div id="data_tabel_perjanjian"></div>
  </div>
</div>

<fieldset>
<legend class="legend"><i class="fas fa-exclamation-triangle"></i> Data Peringatan</legend>
</fieldset>
<div class="row">
  <div class="col-md-12">
    <div id="data_tabel_peringatan"></div>
  </div>
</div>

<fieldset>
<legend class="legend"><i class="fab fa-glide"></i> Data Grade</legend>
</fieldset>
<div class="row">
  <div class="col-md-12">
    <div id="data_tabel_grade"></div>
  </div>
</div>   

<fieldset>
<legend class="legend"><i class="fas fa-ambulance"></i> Kecelakaan Kerja</legend>
</fieldset>
<div class="row">
  <div class="col-md-12">
    <div id="data_tabel_kecelakaan"></div>
  </div>
</div>

<!-- view mutasi-->
<div id="view_mutasi" class="modal fade" role="dialog">
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
              <label class="col-md-6 control-label">Nomor SK</label>
              <div class="col-md-6" id="data_nosk_view"></div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Tanggal SK</label>
              <div class="col-md-6" id="data_tglsk_view"></div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Tanggal Berlaku</label>
              <div class="col-md-6" id="data_tglberlaku_view"></div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">NIK</label>
              <div class="col-md-6" id="data_nik_view"></div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Nama</label>
              <div class="col-md-6" id="data_nama_view"></div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Status Mutasi</label>
              <div class="col-md-6" id="data_statusmutasi_view"></div>
            </div>
                       
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Mengetahui</label>
              <div class="col-md-6" id="data_mengetahui_view"></div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Menyetujui</label>
              <div class="col-md-6" id="data_menyetujui_view"></div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Keterangan</label>
              <div class="col-md-6" id="data_keterangan_view"></div>
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
        <hr>
        <div class="row">
          <div class="col-md-6">
            <div class="panel panel-danger">
            <div class="panel-heading bg-red"><b>Status Lama</b></div>
            <div class="panel-body">
              <div class="form-group col-md-12">
                  <label class="col-md-6 control-label">Jabatan Lama</label>
                  <div class="col-md-6" id="data_jabatanlama_view"></div>
                </div>
                <div class="form-group col-md-12">
                  <label class="col-md-6 control-label">Lokasi Lama</label>
                  <div class="col-md-6" id="data_lokasiasal_view"></div>
                </div>
            </div>
          </div>
          </div>
          <div class="col-md-6">
            <div class="panel panel-success">
            <div class="panel-heading bg-green"><b>Status Baru</b></div>
            <div class="panel-body">
              <div class="form-group col-md-12">
                  <label class="col-md-6 control-label">Jabatan Baru</label>
                  <div class="col-md-6" id="data_jabatanbaru_view"></div>
                </div>
              <div class="form-group col-md-12">
                  <label class="col-md-6 control-label">Lokasi Baru</label>
                  <div class="col-md-6" id="data_lokasibaru_view"></div>
                </div>
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
<!-- view perjanjian-->
<div id="view_perjanjian" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h2 class="modal-title">Detail Data <b class="text-muted header_data"></b></h2>
        <input type="hidden" name="data_id_view2">
      </div>
      <div class="modal-body">
        
        <div class="row">
          <div class="col-md-6">
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">NIK</label>
              <div class="col-md-6" id="data_nik_view2"></div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Nama</label>
              <div class="col-md-6" id="data_nama_view2"></div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Perjanjian Baru</label>
              <div class="col-md-6" id="data_perjanjian3_view2"></div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Mengetahui</label>
              <div class="col-md-6" id="data_mengetahui_view2"></div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Menyetujui</label>
              <div class="col-md-6" id="data_mmenyetujui_view2"></div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Keterangan</label>
              <div class="col-md-6" id="data_keterangan_view2"></div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Status</label>
              <div class="col-md-6" id="data_status_view2">
              
              </div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Dibuat Tanggal</label>
              <div class="col-md-6" id="data_create_date_view2"></div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Diupdate Tanggal</label>
              <div class="col-md-6" id="data_update_date_view2"></div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Dibuat Oleh</label>
              <div class="col-md-6" id="data_create_by_view2">
              </div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Diupdate Oleh</label>
              <div class="col-md-6" id="data_update_by_view2">
              </div>
            </div>
          </div>
        </div>
        <hr>
        <div class="row">
          <div class="col-md-6">
            <div class="panel panel-danger">
            <div class="panel-heading bg-red"><h4>Data Sebelumnya</h4></div>
            <div class="panel-body">
                <div class="form-group col-md-12">
                  <label class="col-md-6 control-label">Nomor SK Sebelumnya</label>
                  <div class="col-md-6" id="data_nosk1_view2"></div>
                </div>
                <div class="form-group col-md-12">
                  <label class="col-md-6 control-label">Perjanjian Sebelumnya</label>
                  <div class="col-md-6" id="data_perjanjian1_view2"></div>
                </div>
                <div class="form-group col-md-12">
                  <label class="col-md-6 control-label">Tanggal SK Sebelumnya</label>
                  <div class="col-md-6" id="data_tglsk1_view2"></div>
                </div>
                <div class="form-group col-md-12">
                  <label class="col-md-6 control-label">Tanggal Berlaku Sebelumnya</label>
                  <div class="col-md-6" id="data_tglberlaku1_view2"></div>
                </div>
                <div class="form-group col-md-12">
                  <label class="col-md-6 control-label">Berlaku Sampai Tanggal</label>
                  <div class="col-md-6" id="data_berlakusampai1_view2"></div>
                </div>
            </div>
          </div>
          </div>
          <div class="col-md-6">
            <div class="panel panel-success">
            <div class="panel-heading bg-green"><h4>Data Baru</h4></div>
            <div class="panel-body">
                <div class="form-group col-md-12">
                  <label class="col-md-6 control-label">Nomor SK Baru</label>
                  <div class="col-md-6" id="data_nosk2_view2"></div>
                </div>
                <div class="form-group col-md-12">
                  <label class="col-md-6 control-label">Perjanjian Baru</label>
                  <div class="col-md-6" id="data_perjanjian2_view2"></div>
                </div>
                <div class="form-group col-md-12">
                  <label class="col-md-6 control-label">Tanggal SK Baru</label>
                  <div class="col-md-6" id="data_tglsk2_view2"></div>
                </div>
                <div class="form-group col-md-12">
                  <label class="col-md-6 control-label">Tanggal Berlaku Baru</label>
                  <div class="col-md-6" id="data_tglberlaku2_view2"></div>
                </div>
                <div class="form-group col-md-12">
                  <label class="col-md-6 control-label">Berlaku Sampai Tanggal</label>
                  <div class="col-md-6" id="data_berlakusampai2_view2"></div>
                </div>
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

<!-- view peringatan-->
<div id="view_peringatan" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h2 class="modal-title">Detail Data <b class="text-muted header_data"></b></h2>
        <input type="hidden" name="data_id_view3">
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-6">
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">NIK</label>
              <div class="col-md-6" id="data_nik_view3"></div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Nama</label>
              <div class="col-md-6" id="data_nama_view3"></div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Nomor SK</label>
              <div class="col-md-6" id="data_nosk_view3"></div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Tanggal SK</label>
              <div class="col-md-6" id="data_tglsk_view3"></div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Tanggal Berlaku</label>
              <div class="col-md-6" id="data_tglberlaku_view3"></div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Berlaku Sampai</label>
              <div class="col-md-6" id="data_tglberlakusampai_view3"></div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Mengetahui</label>
              <div class="col-md-6" id="data_mengetahui_view3"></div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Menyetujui</label>
              <div class="col-md-6" id="data_menyetujui_view3"></div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Keterangan</label>
              <div class="col-md-6" id="data_keterangan_view3"></div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Status</label>
              <div class="col-md-6" id="data_status_view3"></div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Dibuat Tanggal</label>
              <div class="col-md-6" id="data_create_date_view3"></div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Diupdate Tanggal</label>
              <div class="col-md-6" id="data_update_date_view3"></div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Dibuat Oleh</label>
              <div class="col-md-6" id="data_create_by_view3">
              </div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Diupdate Oleh</label>
              <div class="col-md-6" id="data_update_by_view3">
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="panel panel-danger">
              <div class="panel-heading bg-red"><h4>Data Sebelumnya</h4></div>
              <div class="panel-body">
                <div class="form-group col-md-12">
                  <label class="col-md-6 control-label">Peringatan Sebelumnya</label>
                  <div class="col-md-6" id="data_peringatanasal_view3"></div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="panel panel-success">
              <div class="panel-heading bg-green"><h4>Data Baru</h4></div>
              <div class="panel-body">
                <div class="form-group col-md-12">
                  <label class="col-md-6 control-label">Peringatan Baru</label>
                  <div class="col-md-6" id="data_peringatanbaru_view3"></div>
                </div>
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
<!-- view grade-->
<div id="view_grade" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h2 class="modal-title">Detail Data <b class="text-muted header_data"></b></h2>
        <input type="hidden" name="data_id_view4">
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-6">
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">NIK</label>
              <div class="col-md-6" id="data_nik_view4"></div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Nama</label>
              <div class="col-md-6" id="data_nama_view4"></div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Nomor SK</label>
              <div class="col-md-6" id="data_nosk_view4"></div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Tanggal SK</label>
              <div class="col-md-6" id="data_tglsk_view4"></div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Tanggal Berlaku</label>
              <div class="col-md-6" id="data_tglberlaku_view4"></div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Grade Sebelumnya</label>
              <div class="col-md-6" id="data_gradeasal_view4"></div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Grade Baru</label>
              <div class="col-md-6" id="data_gradebaru_view4"></div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Mengetahui</label>
              <div class="col-md-6" id="data_mengetahui_view4"></div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Menyetujui</label>
              <div class="col-md-6" id="data_menyetujui_view4"></div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Keterangan</label>
              <div class="col-md-6" id="data_keterangan_view4"></div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Status</label>
              <div class="col-md-6" id="data_status_view4">
              
              </div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Dibuat Tanggal</label>
              <div class="col-md-6" id="data_create_date_view4"></div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Diupdate Tanggal</label>
              <div class="col-md-6" id="data_update_date_view4"></div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Dibuat Oleh</label>
              <div class="col-md-6" id="data_create_by_view4">
              </div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Diupdate Oleh</label>
              <div class="col-md-6" id="data_update_by_view4">
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
<!-- view kecelakaan kerja-->
<div id="view_kecelakaan" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h2 class="modal-title">Detail Data <b class="text-muted header_data"></b></h2>
        <input type="hidden" name="data_id_view5">
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-6">
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">NIK</label>
              <div class="col-md-6" id="data_nik_view5"></div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Nama</label>
              <div class="col-md-6" id="data_nama_view5"></div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Jabatan</label>
              <div class="col-md-6" id="data_jabatan_view5"></div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Lokasi Kerja</label>
              <div class="col-md-6" id="data_loker_view5"></div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Status</label>
              <div class="col-md-6" id="data_status_view5"></div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Dibuat Tanggal</label>
              <div class="col-md-6" id="data_create_date_view5"></div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Diupdate Tanggal</label>
              <div class="col-md-6" id="data_update_date_view5"></div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Dibuat Oleh</label>
              <div class="col-md-6" id="data_create_by_view5">
              </div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Diupdate Oleh</label>
              <div class="col-md-6" id="data_update_by_view5">
              </div>
            </div>
          </div>
      </div>
      <hr>
      <div class="row">
          <div class="col-md-6">
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Nomor Kecelakaan</label>
              <div class="col-md-6" id="data_no_view5"></div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Tanggal Kejadian</label>
              <div class="col-md-6" id="data_tgl_view5"></div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Jam Kejadian</label>
              <div class="col-md-6" id="data_jam_view5"></div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Lokasi Kejadian</label>
              <div class="col-md-6" id="data_lokasi_view5"></div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Kategori Kecelakaan</label>
              <div class="col-md-6" id="data_kategori_view5"></div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Rumah Sakit</label>
              <div class="col-md-6" id="data_rs_view5"></div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Mengetahui</label>
              <div class="col-md-6" id="data_mengetahui_view5"></div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Menyatakan</label>
              <div class="col-md-6" id="data_menyatakan_view5"></div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Saksi 1</label>
              <div class="col-md-6" id="data_saksi1_view5"></div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Saksi 2</label>
              <div class="col-md-6" id="data_saksi2_view5"></div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Penanggung Jawab</label>
              <div class="col-md-6" id="data_penanggungjawab_view5"></div>
            </div>
            <div class="form-group col-md-12">
              <label class="col-md-6 control-label">Keterangan</label>
              <div class="col-md-6" id="data_keterangan_view5"></div>
            </div>
          </div>
        </div>
        <hr>
        <div class="row">
          <div class="col-md-12">
            <div class="panel panel-danger">
            <div class="panel-heading bg-red"><b>Kronologi</b></div>
            <div class="panel-body">
              <div class="form-group col-md-12">
                  <label class="col-md-4 control-label">Kejadian</label>
                  <div class="col-md-8" id="data_kejadian_view5"></div>
                </div>
                <div class="form-group col-md-12">
                  <label class="col-md-4 control-label">Alat</label>
                  <div class="col-md-8" id="data_alat_view5"></div>
                </div>
                <div class="form-group col-md-12">
                  <label class="col-md-4 control-label">Bagian Tubuh</label>
                  <div class="col-md-8" id="data_bagiantubuh_view5"></div>
                </div>
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
<script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
<script type="text/javascript">
  function riwayat_kerja(){
    var id_karyawan=<?php echo $profile['nik'];?>;
    var data={id_karyawan:id_karyawan};
    var callback=getAjaxData("<?php echo base_url('employee/view_karyawan_tidak_aktif/view_mutasi')?>",data);
    $('#data_tabel_mutasi').html(callback['tabel_mutasi']);

    var callback2=getAjaxData("<?php echo base_url('employee/view_karyawan_tidak_aktif/view_perjanjian')?>",data);
    $('#data_tabel_perjanjian').html(callback2['tabel_perjanjian']);

    var callback3=getAjaxData("<?php echo base_url('employee/view_karyawan_tidak_aktif/view_peringatan')?>",data);
    $('#data_tabel_peringatan').html(callback3['tabel_peringatan']);

    var callback4=getAjaxData("<?php echo base_url('employee/view_karyawan_tidak_aktif/view_grade')?>",data);
    $('#data_tabel_grade').html(callback4['tabel_grade']);

    var callback5=getAjaxData("<?php echo base_url('employee/view_karyawan_tidak_aktif/view_kecelakaan')?>",data);
    $('#data_tabel_kecelakaan').html(callback5['tabel_kecelakaan']);
  }
  function view_modal_mutasi(id) {
    var data={id_mutasi:id};
    var callback=getAjaxData("<?php echo base_url('employee/view_mutasi_jabatan/view_one')?>",data);  
    $('#view_mutasi').modal('show');
    $('.header_data').html(callback['no_sk']);
    $('#data_nosk_view').html(callback['no_sk']);
    $('#data_tglsk_view').html(callback['tgl_sk']);
    $('#data_tglberlaku_view').html(callback['tgl_berlaku']);
    $('#data_nik_view').html(callback['nik']);
    $('#data_nama_view').html(callback['nama']);
    $('#data_lokasiasal_view').html(callback['lokasi_asal']);
    $('#data_lokasibaru_view').html(callback['vlokasi_baru']);
    $('#data_statusmutasi_view').html(callback['vstatus_mutasi']);
    $('#data_jabatanlama_view').html(callback['jabatan_lama']);
    $('#data_jabatanbaru_view').html(callback['vjabatan_baru']);
    $('#data_mengetahui_view').html(callback['vmengetahui']);
    $('#data_menyetujui_view').html(callback['vmenyetujui']);
    $('#data_keterangan_view').html(callback['vketerangan']);
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
  function view_modal_perjanjian(id) {
    var data={id_p_kerja:id};
    var callback=getAjaxData("<?php echo base_url('employee/view_perjanjian_kerja/view_one')?>",data);  
    $('#view_perjanjian').modal('show');
    $('.header_data2').html(callback['no_sk']);
    $('#data_nik_view2').html(callback['nik']);
    $('#data_nama_view2').html(callback['nama']);
    $('#data_perjanjian1_view2').html(callback['status_lama']);
    $('#data_nosk1_view2').html(callback['no_sk_lama']);
    $('#data_tglsk1_view2').html(callback['tgl_sk_lama']);
    $('#data_tglberlaku1_view2').html(callback['tgl_berlaku_lama']);
    $('#data_berlakusampai1_view2').html(callback['berlaku_sampai_lama']);
    $('#data_perjanjian2_view2').html(callback['status_baru']);
    $('#data_perjanjian3_view2').html(callback['status_baru']);
    $('#data_nosk2_view2').html(callback['no_sk_baru']);
    $('#data_tglsk2_view2').html(callback['tgl_sk_baru']);
    $('#data_tglberlaku2_view2').html(callback['tgl_berlaku_baru']);
    $('#data_berlakusampai2_view2').html(callback['berlaku_sampai_baru']);
    $('#data_mengetahui_view2').html(callback['mengetahuiv']);
    $('#data_menyrtujui_view2').html(callback['menyetujuiv']);
    $('#data_keterangan_view2').html(callback['keterangan']);
    var status = callback['status'];
    if(status==1){
      var statusval = '<b class="text-success">Aktif</b>';
    }else{
      var statusval = '<b class="text-danger">Tidak Aktif</b>';
    }
    $('#data_status_view2').html(statusval);
    $('#data_create_date_view2').html(callback['create_date']+' WIB');
    $('#data_update_date_view2').html(callback['update_date']+' WIB');
    $('input[name="data_id_view2"]').val(callback['id']);
    $('#data_create_by_view2').html(callback['nama_buat']);
    $('#data_update_by_view2').html(callback['nama_update']);
  }
  function view_modal_peringatan(id) {
    var data={id_peringatan:id};
    var callback=getAjaxData("<?php echo base_url('employee/view_peringatan_karyawan/view_one')?>",data);  
    $('#view_peringatan').modal('show');
    $('.header_data').html(callback['no_sk']);
    $('#data_nosk_view3').html(callback['no_sk']);
    $('#data_tglsk_view3').html(callback['tgl_sk']);
    $('#data_tglberlaku_view3').html(callback['tgl_berlaku']);
    $('#data_tglberlakusampai_view3').html(callback['berlaku_sampai']);
    $('#data_nik_view3').html(callback['nik']);
    $('#data_nama_view3').html(callback['nama']);
    $('#data_peringatanasal_view3').html(callback['status_lama']);
    $('#data_peringatanbaru_view3').html(callback['status_baru']);
    $('#data_mengetahui_view3').html(callback['mengetahui']);
    $('#data_menyetujui_view3').html(callback['menyetujui']);
    $('#data_keterangan_view3').html(callback['keterangan']);
    var status = callback['status'];
    if(status==1){
      var statusval = '<b class="text-success">Aktif</b>';
    }else{
      var statusval = '<b class="text-danger">Tidak Aktif</b>';
    }
    $('#data_status_view3').html(statusval);
    $('#data_create_date_view3').html(callback['create_date']+' WIB');
    $('#data_update_date_view3').html(callback['update_date']+' WIB');
    $('input[name="data_id_view3"]').val(callback['id']);
    $('#data_create_by_view3').html(callback['nama_buat']);
    $('#data_update_by_view3').html(callback['nama_update']);
  }
  function view_modal_grade(id) {
    var data={id_grade:id};
    var callback=getAjaxData("<?php echo base_url('employee/view_grade_karyawan/view_one')?>",data);  
    $('#view_grade').modal('show');
    $('.header_data4').html(callback['no_sk']);
    $('#data_nosk_view4').html(callback['no_sk']);
    $('#data_tglsk_view4').html(callback['tgl_sk']);
    $('#data_tglberlaku_view4').html(callback['tgl_berlaku']);
    $('#data_tglberlakusampai_view4').html(callback['berlaku_sampai']);
    $('#data_nik_view4').html(callback['nik']);
    $('#data_nama_view4').html(callback['nama']);
    $('#data_gradeasal_view4').html(callback['grade_lama']);
    $('#data_gradebaru_view4').html(callback['grade_baru']);
    $('#data_mengetahui_view4').html(callback['mengetahui']);
    $('#data_menyetujui_view4').html(callback['menyetujui']);
    $('#data_keterangan_view4').html(callback['keterangan']);
    var status = callback['status4'];
    if(status==1){
      var statusval = '<b class="text-success">Aktif</b>';
    }else{
      var statusval = '<b class="text-danger">Tidak Aktif</b>';
    }
    $('#data_status_view4').html(statusval);
    $('#data_create_date_view4').html(callback['create_date']+' WIB');
    $('#data_update_date_view4').html(callback['update_date']+' WIB');
    $('input[name="data_id_view4"]').val(callback['id']);
    $('#data_create_by_view4').html(callback['nama_buat']);
    $('#data_update_by_view4').html(callback['nama_update']);
  }
  function view_modal_kecelakaan(id) {
    var data={id_kecelakaan:id};
    var callback=getAjaxData("<?php echo base_url('employee/view_kecelakaan_kerja/view_one')?>",data);  
    $('#view_kecelakaan').modal('show');
    $('.header_data').html(callback['no_sk']);
    $('#data_nik_view5').html(callback['nik']);
    $('#data_nama_view5').html(callback['nama']);
    $('#data_jabatan_view5').html(callback['nama_jabatan']);
    $('#data_loker_view5').html(callback['nama_loker']);
    $('#data_no_view5').html(callback['no_sk']);
    $('#data_tgl_view5').html(callback['tgl']);
    $('#data_jam_view5').html(callback['jam']+' WIB');
    $('#data_lokasi_view5').html(callback['lokasi']);
    $('#data_kategori_view5').html(callback['kategori']);
    $('#data_rs_view5').html(callback['rumahsakit']);
    $('#data_mengetahui_view5').html(callback['mengetahui']);
    $('#data_menyatakan_view5').html(callback['menyatakan']);
    $('#data_saksi1_view5').html(callback['saksi_1']);
    $('#data_saksi2_view5').html(callback['saksi_2']);
    $('#data_penanggungjawab_view5').html(callback['penanggungjawab']);
    $('#data_keterangan_view5').html(callback['keterangan']);
    $('#data_kejadian_view5').html(callback['kejadian']);
    $('#data_alat_view5').html(callback['alat']);
    $('#data_bagiantubuh_view5').html(callback['bagiantubuh']);
    var status = callback['status'];
    if(status==1){
      var statusval = '<b class="text-success">Aktif</b>';
    }else{
      var statusval = '<b class="text-danger">Tidak Aktif</b>';
    }
    $('#data_status_view5').html(statusval);
    $('#data_create_date_view5').html(callback['create_date']+' WIB');
    $('#data_update_date_view5').html(callback['update_date']+' WIB');
    $('input[name="data_id_view5"]').val(callback['id']);
    $('#data_create_by_view5').html(callback['nama_buat']);
    $('#data_update_by_view5').html(callback['nama_update']);
  }
</script>