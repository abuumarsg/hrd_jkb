<div class="content-wrapper">
  <section class="content-header">
    <h1>
      <a class="back" onclick="window.history.back()"><i class="fa fa-chevron-circle-left "></i></a>
      <i class="fa fa-file-text"></i> Raport Sikap (360°)
      <small><?php echo $profile['nama']?></small>
    </h1>
    <ol class="breadcrumb">
       <li><a href="<?php echo base_url('pages/dashboard');?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
      <li><a href="<?php echo base_url('pages/data_hasil_sikap');?>"><i class="fas fa-calendar"></i> Daftar Agenda</a></li>
      <li><a href="<?php echo base_url('pages/view_employee_result_sikap/'.$this->uri->segment(3));?>"><i class="fa fa-users"></i> Daftar Karyawan</a></li>
      <li><a href="<?php echo base_url('pages/report_value_sikap/'.$this->uri->segment(3).'/'.$this->uri->segment(5));?>"><i class="fa fa-file-text"></i> Raport <?php echo $profile['nama'];?></a></li>
      <li class="active">Detail Raport</li>
    </ol>
  </section>
  <section class="content">
  <div class="row">
    <div class="col-md-12">
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-file-text text-red"></i> Rapor Nilai Sikap (360°) <?php echo $profile['nama'];?></h3>
          <div class="box-tools pull-right">
            <button class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse"><i class="fa fa-minus"></i></button>
            <button class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Tutup"><i class="fa fa-times"></i></button>
          </div>
        </div>
        <div class="box-body">
          <div class="callout callout-success"><label><i class="fa fa-tags"></i> Aspek Sikap <?php echo $aspek_sikap['nama']; ?></label></div>
          <div class="row"> 
            <div class="col-md-12">
              <div class="row">
                <div class="col-md-4"></div>
                <div class="col-md-4">
                  <table class="table" id="rata_rata">
                  </table>
                </div>
              </div>
              <div style="overflow: auto;">
                <table id="example1" class="table table-striped table-hover table-bordered">
                  <thead>
                    <tr class="bg-green" id="nama_part">
                    </tr>
                    <tr id="ket_part">
                    </tr>
                  </thead>
                  <tbody id="kuisi">
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
</div>

<script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
<script type="text/javascript">
    var url_select="<?php echo base_url('global_control/select2_global');?>";
    $(document).ready(function(){
      var callback=getAjaxData("<?php echo base_url('agenda/report_detail_sikap/view_all/'.$this->codegenerator->encryptChar($kode).'/'.$this->uri->segment(4).'/'.$this->uri->segment(5))?>");  
      $('#rata_rata').html(callback['rata_rata']);
      $('#nama_part').html(callback['nama_part']);
      $('#ket_part').html(callback['ket_part']);
      $('#kuisi').html(callback['kuisi']);
    })
</script>