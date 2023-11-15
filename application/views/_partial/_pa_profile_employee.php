<?php 
$foto = $this->otherfunctions->getFotoValue($profile['foto'],$profile['kelamin']);
?>
<div class="row">
    <div class="col-md-4">
        <div class="box box-primary">
            <div class="box-body box-profile">
                <img class="profile-user-img img-responsive img-circle view_photo" src="<?php echo base_url($foto); ?>" alt="User profile picture">
                <h3 class="profile-username text-center"><?php echo ucwords($profile['nama']); ?></h3>
                <p class="text-muted text-center"><?php 
                echo (empty($profile['nama_jabatan_baru']))?'<label class="label label-danger text-center">Tidak Punya Jabatan</label>':$profile['nama_jabatan_baru'];
                ?></p>

                <ul class="list-group list-group-unbordered"> 
                    <li class="list-group-item">
                        <b>Terdaftar Sejak</b><label class="pull-right label label-primary"><?php echo $this->formatter->getDayDateFormatUserId($profile['tgl_masuk']); ?></label>
                    </li>
                    <li class="list-group-item">
                        <div class="row">
                        <div class="col-md-3">
                            <b>Agenda</b>
                        </div>
                        <div class="col-md-9">
                            <label class="pull-right label-wrap label-success"><?php echo $agd['nama']; ?></label>
                        </div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <b>Tahun</b> <label class="pull-right label label-primary"><?php echo $agd['tahun'];?></label>
                    </li>
                    <li class="list-group-item">
                        <b>Periode</b> <label class="pull-right label label-warning"><?php echo 'Periode '.$agd['nama_periode']; ?></label>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#info" data-toggle="tab">Informasi Umum</a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="info">
                    <div class="row">
                        <div class="col-md-12">
                            <table class='table table-bordered table-striped table-hover'>
                                <tr>
                                    <th>Nama Lengkap</th>
                                    <td><?php echo ucwords($profile['nama']);?></td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td><?php 
                                    if ($profile['email'] == "") {
                                        echo '<label class="label label-danger">Email Tidak Ada</label>';
                                    }else{
                                        echo $profile['email'];
                                    }
                                    ?></td>
                                </tr>
                                <tr>
                                    <th>Username</th>
                                    <td><?php echo $profile['nik'];?></td>
                                </tr>
                                <tr>
                                    <th>Jenis Kelamin</th>
                                    <td><?php 
                                    if($profile['kelamin'] == 'l'){
                                        echo '<i class="fa fa-male" style="color:blue"></i> Laki-laki';
                                    }else{
                                        echo '<i class="fa fa-female" style="color:#ff00a5"></i> Perempuan';
                                    }?></td>
                                </tr>
                                <tr> 
                                    <th>Jabatan</th>
                                    <td><?php 
                                    if ($profile['nama_jabatan'] || $profile['nama_jabatan_baru']) {
                                        if ($profile['nama_jabatan'] != $profile['nama_jabatan_baru']) {
                                            echo $profile['nama_jabatan'].'<br><label class="label label-primary" data-toggle="tooltip" title="Jabatan Baru"><i class="fa fa-briefcase"></i> '.$profile['nama_jabatan_baru'].'</label>'; 
                                        }else{
                                            echo $profile['nama_jabatan_baru']; 
                                        }
                                    }else{
                                        echo '<label class="label label-danger text-center">Tidak Punya Jabatan</label>';
                                    }?></td>
                                </tr>   
                                <tr>
                                    <th>Unit Kerja</th>
                                    <td><?php 
                                     if ($profile['nama_loker'] || $profile['nama_loker_baru']) {
                                        if ($profile['nama_loker'] != $profile['nama_loker_baru']) {
                                            echo $profile['nama_loker'].'<br><label class="label label-primary" data-toggle="tooltip" title="Lokasi Kerja Baru"><i class="fa fa-map"></i> '.$profile['nama_loker_baru'].'</label>'; 
                                        }else{
                                            echo $profile['nama_loker_baru']; 
                                        }
                                    }else{
                                        echo '<label class="label label-danger text-center">Tidak Punya Lokasi Kerja</label>';
                                    }?></td>
                                </tr> 
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div> 
    </div>
</div>