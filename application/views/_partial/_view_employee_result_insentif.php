<div class="box box-success">
    <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-users"></i> Daftar Karyawan Insentif <?php
        $data = ['rekap'=>$kode,'periode'=>null];
        echo (isset($kode['kode_periode']))?$this->model_master->getListPeriodePenilaianActive()[$kode['kode_periode']].' '.$kode['tahun']:$kode['tahun']; ?></h3>
        <div class="box-tools pull-right">
            <button class="btn btn-box-tool" onclick="tableDataInsentif()" data-toggle="tooltip" title="Refresh Table"><i class="fas fa-sync"></i></button>
            <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
            <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i class="fa fa-times"></i></button>
        </div>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-md-12">
                <?php if(in_array($access['l_ac']['rkp'], $access['access'])){?>
                    <div class="pull-left"> 
                        <button type="button" class="btn btn-warning" onclick="rekap_data()" style="margin-right: 3px;"><i class="fas fa-file-excel-o"></i> Export Data</button>
                    </div>
                <?php } 
                if(in_array('PRN', $access['access'])){?>
                    <div class="pull-left">
                        <button type="button" class="btn btn-info" onclick="print_data()"><i class="fa fa-print"></i> Print</button>
                    </div>
                <?php } ?>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-12">
                <div class="callout callout-info">
                    <label><i class="fa fa-info-circle"></i> Bantuan</label><br>
                    Pilih NIK Karyawan untuk melihat Raport Penilaian Kinerja
                </div>
                <table id="table_data_insentif" class="table table-bordered table-striped table-responsive table-hover" width="100%">
                    <thead>
                        <tr>
                            <th rowspan="2">No.</th>
                            <th rowspan="2">NIK</th>
                            <th rowspan="2">Nama Karyawan</th>
                            <th rowspan="2">Jabatan</th>
                            <th rowspan="2">Bagian</th>
                            <th rowspan="2">Departemen</th>
                            <th rowspan="2">Lokasi Kerja</th>
                            <th colspan="3" class="text-center">Nilai Per Periode <?=$kode['tahun']?></th>
                            <th rowspan="2">Rata - Rata</th>
                            <th rowspan="2">Huruf</th>
                        </tr>
                        <tr>
                        <?php 
                        $count_periode=0;
                        if (isset($periode_list)) {
                            $count_periode=count($periode_list);
                            foreach ($periode_list as $pl) {
                                echo '<th>'.$pl->nama.'</th>';
                            }
                        }
                        ?>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
<script type="text/javascript">
var url_select="<?php echo base_url('global_control/select2_global');?>";
$(document).ready(function(){
    select_data('bagian_filter',url_select,'master_bagian','kode_bagian','nama');
    select_data('loker_filter',url_select,'master_loker','kode_loker','nama');
    select_data('departemen_filter',url_select,'master_departement','kode_departement','nama');
    select_data('level_jabatan_filter',url_select,'master_level_jabatan','kode_level_jabatan','nama');
    unsetoption('bagian_filter',['BAG001','BAG002']);
    unsetoption('level_jabatan_filter',['LV001']);
    unsetoption('departemen_filter',['DEP001']);
    var getform = $('#form_filter').serialize();
    $('input[name="data_form"]').val(getform);
    
})
function tableDataInsentif(kode = 'all') { 
    $('#usage').val(kode);
    $('#mode').val('data');
    $('#btn_search').attr("onclick","tableDataInsentif('search')");
    var max_periode=<?=$count_periode?>;
    var cols=max_periode + 6;
    if(kode == 'all'){ $('#form_filter .select2').val(null).trigger('change'); }
    var getform = $('#form_filter').serialize();
    $('input[name="data_form"]').val(getform);
    var t=$('#table_data_insentif').DataTable( {
        ajax: {
            url: "<?php echo base_url('agenda/view_employee_result_insentif')?>",
            type: 'POST',
            data:{form:getform,access:"<?php echo $this->codegenerator->encryptChar($access);?>",data:'<?= $this->codegenerator->encryptChar($kode);?>'}
        },
        scrollX: true,
        processing: true,
        bDestroy: true,
        order:[(cols+1),'desc'],
        columnDefs: [
        {   targets: 0, 
            width: '5%',
            render: function ( data, type, full, meta ) {
                return '<center>'+(meta.row+1)+'.</center>';
            }
        },
        {   targets: 1,
            width: '5%',
            render: function ( data, type, full, meta ) {
                return data;
            }
        },
        {   targets: [2,3],
            width: '30%',
            render: function ( data, type, full, meta ) {
                return data;
            }
        },
        {   targets: [(cols-2),(cols-1),(cols),(cols+1),(cols+2)],
            width: '5%',
            render: function ( data, type, full, meta ) {
                return '<center>'+data+'</center>';
            }
            },
        ]
    });
    t.on( 'order.dt search.dt', function () {
        t.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    }).draw();
}
function rekap_data() {
    window.open("<?php echo base_url('rekap/export_pa_insentif/'.$this->codegenerator->encryptChar($kode).'/'.$this->codegenerator->encryptChar($access)) ?>/?"+$('#form_filter').serialize(), "_blank");
}
function print_data() {
    window.open("<?php echo base_url('pages/print_direct/result_employee_group_insentif/'.$this->codegenerator->encryptChar($kode).'/'.$this->codegenerator->encryptChar($access)) ?>/?"+$('#form_filter').serialize(), "_blank");
}
</script>