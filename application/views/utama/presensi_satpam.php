<?php
/**
 * GFEACORP - Web Developer
 *
 * @package  Codeigniter
 * @author   Galeh Fatma Eko Ardiansa <galeh.fatma@gmail.com>
 */
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="description" content="<?php echo $this->otherfunctions->companyProfile()['meta_description'];?>">
	<meta name="author" content="<?php echo $this->otherfunctions->companyProfile()['meta_author'];?>">
	<title>View Izin Harian | HRD Management System HSOFT </title>
	<meta name="theme-color" content="#131c5b">
	<link rel="icon" href="<?php echo base_url();?>asset/img/favicon.png" type="image/png">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="<?php echo base_url('asset/bower_components/bootstrap/dist/css/bootstrap.css');?>">
    <link rel="stylesheet" href="<?php echo base_url('asset/bower_components/font-awesome/css/font-awesome.min.css');?>">
    <link rel="stylesheet" href="<?php echo base_url('asset/vendor/fontawesome5/css/all.min.css');?>">
    <link rel="stylesheet" href="<?php echo base_url('asset/bower_components/Ionicons/css/ionicons.min.css');?>">
    <link rel="stylesheet" href="<?php echo base_url('asset/bower_components/sweetalert2/dist/sweetalert2.min.css');?>">
    <link rel="stylesheet" href="<?php echo base_url('asset/dist/css/AdminLTE.css');?>">
    <link rel="stylesheet" href="<?php echo base_url('asset/plugins/iCheck/square/blue.css');?>">
    <link rel="stylesheet" href="<?php echo base_url('asset/plugins/viewerjs/dist/viewer.css');?>">
    <link rel="stylesheet" href="<?php echo base_url('asset/plugins/timepicker/bootstrap-timepicker.min.css');?>">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    <link href="https://fonts.googleapis.com/css?family=Lobster" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo base_url('asset/bower_components/morris.js/morris.css');?>">
    <link rel="stylesheet" href="<?php echo base_url('asset/bower_components/jvectormap/jquery-jvectormap.css');?>">
    <link rel="stylesheet" href="<?php echo base_url('asset/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css');?>">
    <link rel="stylesheet" href="<?php echo base_url('asset/bower_components/bootstrap-daterangepicker/daterangepicker.css');?>">
    <link rel="stylesheet" href="<?php echo base_url('asset/bower_components/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css');?>">
    <link rel="stylesheet" href="<?php echo base_url('asset/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css');?>">
    <link rel="stylesheet" href="<?php echo base_url('asset/bower_components/datatables.net-bs/css/dataTables.bootstrap.css');?>">
    <link rel="stylesheet" href=" https://cdn.datatables.net/fixedcolumns/3.2.6/css/fixedColumns.bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo base_url('asset/dist/css/skins/_all-skins.css');?>">
    <link rel="stylesheet" href="<?php echo base_url('asset/bower_components/select2/dist/css/select2.css');?>">
    <link rel="stylesheet" href="<?php echo base_url('asset/plugins/pace/pace.css');?>">
    <link href="https://fonts.googleapis.com/css?family=Questrial" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Lato:300,400,700,900' rel='stylesheet' type='text/css'>
    <link href="<?php echo base_url('asset/vendor/overhang/dist/overhang.min.css');?>" rel="stylesheet" media="all">
    <link href="<?php echo base_url('asset/vendor/emoji/dist/emoji.min.css');?>" rel="stylesheet" media="all">
    <link href="<?php echo base_url('asset/vendor/toastr/toastr.min.css');?>" rel="stylesheet" media="all">
    <link href="<?php echo base_url('asset/vendor/iconpicker/dist/css/fontawesome-iconpicker.min.css');?>" rel="stylesheet">
    <link href="<?php echo base_url('asset/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.css');?>" rel="stylesheet">
    <link href="<?php echo base_url('asset/plugins/JsTree/dist/themes/default/style.min.css');?>" rel="stylesheet">
</head>
<body class="hold-transition skin-blue layout-top-nav">
	<div class="wrapper">
		<header class="main-header">
			<nav class="navbar navbar-static-top">
				<div class="container" style="width: 1600px;">
					<div class="navbar-header">
                        <span><img src="<?php echo $this->otherfunctions->companyClientProfile()['logo'];?>" class="navbar-brand"></span>
						<!-- <span><a href="<?php //echo base_url();?>" class="navbar-brand"><b>HRD </b>JKB</a></span> -->
						<button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
							data-target="#navbar-collapse">
							<i class="fa fa-bars"></i> dfa
						</button>
					</div>
					<div class="collapse navbar-collapse pull-left" id="navbar-collapse">
						<ul class="nav navbar-nav">
							<li class="active"><a href="<?php echo base_url('izinsatpam');?>">Presensi Harian <span class="sr-only">(current)</span></a></li>
						</ul>
					</div>
					<div class="collapse navbar-collapse pull-right" id="navbar-collapse">
						<ul class="nav navbar-nav">
							<li class="pull-right">
                                <a href="<?php echo base_url();?>"> <i class="fas fa-sign-in-alt"></i> Login </a>
                            </li>
                            <li class="pull-right">
                                <a class="tgl" id="date_time" style="font-size: 9pt"></a>
                            </li>
						</ul>
					</div>
				</div>
			</nav>
		</header>
		<div class="content-wrapper">
			<div class="container" style="width: 1600px;">
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
                        url: "<?php echo base_url('izinsatpam/izin_cuti/view_all/')?>",
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
                var callback=getAjaxData("<?php echo base_url('izinsatpam/izin_cuti/view_one')?>",data);  
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
		<footer class="main-footer">
			<div class="container" style="width: 1600px;">
				<div class="pull-right hidden-xs">
					<b>HSOFT</b> v<?php echo $this->otherfunctions->companyProfile()['version']; ?>
				</div>
				<strong><img src="<?php echo base_url('asset/img/favicon.png');?>" width="20px"> Copyright &copy;
					<?php echo date("Y");?> <a href="http://hucle-consulting.com" target="blank"><img src="<?php echo base_url('asset/img/hsoftl.png');?>" width="15px"> <b class="fnt">PT. HUCLE Indonesia</b></a> | </strong> All rights reserved.
			</div>
		</footer>
	</div>
    <script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
    <script src="<?php echo base_url('asset/bower_components/select2/dist/js/select2.full.min.js');?>"></script>
    <script src="<?php echo base_url('asset/bower_components/jquery-ui/jquery-ui.min.js');?>"></script>
    <script src="<?php echo base_url('asset/bower_components/bootstrap/dist/js/bootstrap.min.js');?>"></script>
    <script src="<?php echo base_url('asset/bower_components/jquery-slimscroll/jquery.slimscroll.min.js');?>"></script>
    <script src="<?php echo base_url('asset/bower_components/fastclick/lib/fastclick.js');?>"></script>
    <script src="<?php echo base_url('asset/dist/js/adminlte.min.js');?>"></script>
    <script src="<?php echo base_url('asset/dist/js/demo.js');?>"></script>
    <script src="<?php echo base_url('asset/plugins/iCheck/icheck.min.js');?>"></script>
    <script src="<?php echo base_url('asset/plugins/viewerjs/dist/viewer.min.js');?>"></script>
    <script src="<?php echo base_url('asset/plugins/timepicker/bootstrap-timepicker.min.js');?>"></script>
    <script src="<?php echo base_url('asset/plugins/chart.js/dist/Chart.min.js');?>"></script>
    <script src="<?php echo base_url('asset/customs.js');?>"></script>
    <script src="<?php echo base_url('asset/ajax.js');?>"></script>
    <script src="<?php echo base_url('asset/chartajax.js');?>"></script>
    <script src="<?php echo base_url('asset/bower_components/datatables.net/js/jquery.dataTables.min.js');?>"></script>
    <script src="<?php echo base_url('asset/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js');?>"></script>
    <script>
    $.widget.bridge('uibutton', $.ui.button);
    </script>
    <script type="text/javascript">
    var base_url = "<?php print base_url(); ?>";
    </script>
    <script src="<?php echo base_url('asset/bower_components/sweetalert2/dist/sweetalert2.min.js');?>"></script>
    <script src="<?php echo base_url('asset/bower_components/raphael/raphael.min.js');?>"></script>
    <script src="<?php echo base_url('asset/bower_components/jquery-sparkline/dist/jquery.sparkline.min.js');?>"></script>
    <script src="<?php echo base_url('asset/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js');?>"></script>
    <script src="<?php echo base_url('asset/plugins/jvectormap/jquery-jvectormap-world-mill-en.js');?>"></script>
    <script src="<?php echo base_url('asset/bower_components/jquery-knob/dist/jquery.knob.min.js');?>"></script>
    <script src="<?php echo base_url('asset/bower_components/moment/min/moment.min.js');?>"></script>
    <script src="<?php echo base_url('asset/bower_components/bootstrap-daterangepicker/daterangepicker.js');?>"></script>
    <script src="<?php echo base_url('asset/bower_components/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js');?>"></script>
    <script src="<?php echo base_url('asset/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js');?>"></script>
    <script src="<?php echo base_url('asset/bower_components/bootstrap-datepicker/dist/locales/bootstrap-datepicker.id.min.js');?>"></script>
    <script src="<?php echo base_url('asset/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js');?>"></script>
    <script src="<?php echo base_url('asset/dist/js/pages/dashboard.js');?>"></script>
    <script src="<?php echo base_url('asset/plugins/pace/pace.min.js');?>"></script>
    <script src="<?php echo base_url('asset/vendor/overhang/dist/overhang.min.js');?>"></script>
    <script src="<?php echo base_url('asset/vendor/validator/js/validator.js');?>"></script>
    <script src="<?php echo base_url('asset/vendor/toastr/toastr.min.js');?>"></script>
    <script src="<?php echo base_url('asset/vendor/iconpicker/dist/js/fontawesome-iconpicker.js');?>"></script>
    <script src="<?php echo base_url('asset/plugins/JsTree/dist/jstree.min.js');?>"></script>
    <script type="text/javascript">window.onload = date_time('date_time');</script>
    <script src="<?php echo base_url('asset/plugins/input-mask/jquery.inputmask.js');?>"></script>
    <script src="<?php echo base_url('asset/plugins/input-mask/jquery.inputmask.date.extensions.js');?>"></script>
    <script src="<?php echo base_url('asset/plugins/input-mask/jquery.inputmask.extensions.js');?>"></script>
    <script src="<?php echo base_url('asset/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js');?>"></script>
    <script src="https://cdn.datatables.net/fixedcolumns/3.2.6/js/dataTables.fixedColumns.min.js"></script>
    <script src="<?php echo base_url('asset/vendor/jquery.redirect-master/jquery.redirect.js');?>"></script>
    <script type="text/javascript">
    $(document).ready(function(){
        all_property();form_property();set_interval();reset_interval();

        //change skin
        if (localStorage.getItem("skin") != null) {
        localStorage.clear("skin");
        }
        $('#skinX').click(function() {
        changeTheme("<?php echo base_url('admin/changeSkin');?>","<?php echo $this->session->userdata('adm')['id'];?>",$('#skinX').data('skin'));
        });
    });
    </script>
</body>
</html>