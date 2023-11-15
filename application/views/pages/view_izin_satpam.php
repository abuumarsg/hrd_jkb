<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fas fa-calendar-times"></i> View Izin / Cuti Harian <small>HRD Management System HSOFT</small></h1>
    </section>
    <section class="content">
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fas fa-calendar-times"></i> Data Izin / Cuti Harian</h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" onclick="tableData('all')" data-toggle="tooltip" title="Refresh Table"><i class="fas fa-sync"></i></button>
                    <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i class="fa fa-times"></i></button>
                </div>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                        <?php $this->date = gmdate("Y-m-d H:i:s", time() + 3600*(7)); ?>
                        <div class="callout callout-info"><label><i class="fa fa-info-circle"></i> Bantuan</label><br>
                        Data Yang Ditampilkan adalah hari ini  <b><?php echo $this->formatter->getDateMonthFormatUser($this->date);?></b><br>Pilih <button type="button" class="btn btn-info btn-sm"><i class="fa fa-info-circle" data-toggle="tooltip" title="Detail Data"></i></button> untuk melihat detail izin pada data izin karyawan</div>
                        <table id="table_data" class="table table-bordered table-striped" width="100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>NIK</th>
                                    <th>Nama</th>
                                    <th>Jabatan</th>
                                    <th>Bagian</th>
                                    <th>Izin / Cuti</th>
                                    <th>Jenis Izin/Cuti</th>
                                    <th>Tanggal Mulai</th>
                                    <th>Tanggal Selesai</th>
                                    <th>Status Validasi</th>
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
    </section>
</div>
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
                    <div class="col-md-2">
                        <img class="profile-user-img img-responsive img-circle view_photo" id="data_foto_view"
                            data-source-photo="" src="" alt="User profile picture" style="width: 100%;">
                    </div>
                    <div class="col-md-5">
                        <div class="form-group col-md-12">
                            <label class="col-md-6 control-label">NIK</label>
                            <div class="col-md-6" id="data_nik_view"></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-6 control-label">Nama</label>
                            <div class="col-md-6" id="data_nama_view"></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-6 control-label">Lokasi</label>
                            <div class="col-md-6" id="data_loker_view"></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-6 control-label">Jabatan</label>
                            <div class="col-md-6" id="data_jabatan_view"></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-6 control-label">Bagian</label>
                            <div class="col-md-6" id="data_bagian_view"></div>
                        </div>
                    </div>
                    <div class="col-md-5">
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
                    <div class="col-md-6">
                        <div class="form-group col-md-12">
                            <label class="col-md-6 control-label">Nomor Izin/Cuti</label>
                            <div class="col-md-6" id="data_no_view"></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-6 control-label">Jenis Izin/Cuti</label>
                            <div class="col-md-6" id="data_jenis_view"></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-6 control-label">Mulai</label>
                            <div class="col-md-6" id="data_mulai_view"></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-6 control-label">Selesai</label>
                            <div class="col-md-6" id="data_selesai_view"></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-6 control-label">SKD Dibayar</label>
                            <div class="col-md-6" id="data_skd_view"></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-6 control-label">Alasan</label>
                            <div class="col-md-6" id="data_alasan_view"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group col-md-12">
                            <label class="col-md-6 control-label">Mengetahui</label>
                            <div class="col-md-6" id="data_mengetahui_view"></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-6 control-label">Menyetujui</label>
                            <div class="col-md-6" id="data_menyetujui_view"></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-6 control-label">Menyetujui 2</label>
                            <div class="col-md-6" id="data_menyetujui2_view"></div>
                        </div>
                        <div class="form-group col-md-12">
                            <label class="col-md-6 control-label">Keterangan</label>
                            <div class="col-md-6" id="data_keterangan_view"></div>
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
    var url_select="<?php echo base_url('global_control/select2_global');?>";
    var table="data_izin_cuti_karyawan";
    var column="id_izin_cuti";
    $(document).ready(function(){
        tableData('all');
    });
    function tableData(kode) {
        $('#table_data').DataTable().destroy();
        var datax = {param:'all',access:""};
        $('#table_data').DataTable( {
            ajax: {
                url: "<?php echo base_url('presensi/izin_cuti_satpam/view_all/')?>",
                type: 'POST',
                data:datax
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
                width: '10%',
                render: function ( data, type, full, meta ) {
                    return data;
                }
            },
            {   targets: 10, 
                width: '7%',
                render: function ( data, type, full, meta ) {
                    return '<center>'+data+'</center>';
                }
            },
            ]
        });
    }
    function view_modal(id) {
        var data={id_izin_cuti:id};
        var callback=getAjaxData("<?php echo base_url('presensi/izin_cuti_satpam/view_one')?>",data);  
        $('#view').modal('show');
        $('.header_data').html(callback['nama']);
        $('#data_nik_view').html(callback['nik']);
        $('#data_nama_view').html(callback['nama']);
        $('#data_loker_view').html(callback['loker']);
        $('#data_jabatan_view').html(callback['jabatan']);
        $('#data_bagian_view').html(callback['bagian']);
        $('#data_foto_view').attr('src',callback['foto']);
        $('#data_nik_view').html(callback['nik']);
        $('#data_nama_view').html(callback['nama']);
        $('#data_no_view').html(callback['nomor']);
        $('#data_jenis_view').html(callback['jenis_cuti']);
        $('#data_mulai_view').html(callback['tanggal_mulai']);
        $('#data_selesai_view').html(callback['tanggal_selesai']);
        $('#data_skd_view').html(callback['skd']);
        $('#data_alasan_view').html(callback['alasan']);
        $('#data_mengetahui_view').html(callback['mengetahui']);
        $('#data_menyetujui_view').html(callback['menyetujui']);
        $('#data_menyetujui2_view').html(callback['menyetujui2']);
        $('#data_keterangan_view').html(callback['keterangan']);
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
</script>