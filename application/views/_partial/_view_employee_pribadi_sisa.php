<script type="text/javascript">
        
                        var data={nik:nik};
                        var callback=getAjaxData("<?php echo base_url('employee/emppribadi')?>",data);
                        $('#form_pribadi_add input[name="username"]').val(callback['nik'])
                        $('#form_pribadi_add input[name="finger_code"]').val(callback['finger_code'])
                        $('#form_pribadi_add input[name="no_ktp"]').val(callback['no_ktp'])
                        $('#form_pribadi_add input[name="nama"]').val(callback['nama'])
                        $('#form_pribadi_add input[name="alamat_asal_jalan"]').val(callback['alamat_asal_jalan'])
                        $('#form_pribadi_add input[name="alamat_asal_desa"]').val(callback['alamat_asal_desa'])
                        $('#form_pribadi_add input[name="alamat_asal_kecamatan"]').val(callback['alamat_asal_kecamatan'])
                        $('#form_pribadi_add input[name="alamat_asal_kabupaten"]').val(callback['alamat_asal_kabupaten'])
                        $('#form_pribadi_add input[name="alamat_asal_provinsi"]').val(callback['alamat_asal_provinsi'])
                        $('#form_pribadi_add input[name="alamat_asal_pos"]').val(callback['alamat_asal_pos'])
                        $('#form_pribadi_add input[name="alamat_sekarang_jalan"]').val(callback['alamat_sekarang_jalan'])
                        $('#form_pribadi_add input[name="alamat_sekarang_desa"]').val(callback['alamat_sekarang_desa'])
                        $('#form_pribadi_add input[name="alamat_sekarang_kecamatan"]').val(callback['alamat_sekarang_kecamatan'])
                        $('#form_pribadi_add input[name="alamat_sekarang_kabupaten"]').val(callback['alamat_sekarang_kabupaten'])
                        $('#form_pribadi_add input[name="alamat_sekarang_provinsi"]').val(callback['alamat_sekarang_provinsi'])
                        $('#form_pribadi_add input[name="alamat_sekarang_pos"]').val(callback['alamat_sekarang_pos'])
                        $('#form_pribadi_add select[name="gol_darah"]').val(callback['gol_darah']).trigger('change');
                        $('#form_pribadi_add input[name="tinggi"]').val(callback['tinggi_badan'])
                        $('#form_pribadi_add input[name="berat"]').val(callback['berat_badan'])
                        $('#form_pribadi_add input[name="no_hp"]').val(callback['no_hp'])
                        $('#form_pribadi_add input[name="npwp"]').val(callback['npwp'])
                        $('#form_pribadi_add input[name="bpjstk"]').val(callback['bpjstk'])
                        $('#form_pribadi_add input[name="bpjskes"]').val(callback['bpjskes'])
                        $('#form_pribadi_add input[name="rekening"]').val(callback['rekening'])
                        $('#form_pribadi_add input[name="email"]').val(callback['email'])
                        $('#form_pribadi_add select[name="kelamin"]').val(callback['kelamin']).trigger('change');
                        $('#form_pribadi_add select[name="status_nikah"]').val(callback['status_nikah']).trigger('change');
                        $('#form_pribadi_add select[name="agama"]').val(callback['agama']).trigger('change');
                        $('#form_pribadi_add input[name="tempat_lahir"]').val(callback['tempat_lahir'])
                        $('#form_pribadi_add input[name="tgl_lahir"]').val(callback['tgl_lahir'])
</script>