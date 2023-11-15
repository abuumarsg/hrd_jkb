<meta http-equiv="cache-control" content="no-cache" />
<meta http-equiv="pragma" content="no-cache" />
<div class="nav-tabs-custom">
   <ul class="nav nav-tabs">
      <li class="active"><a onclick="pribadi()" href="#pribadi" data-toggle="tab"><i class="fa fa-user"></i> Data Pribadi</a></li>
      <li><a onclick="data_ayah()" href="#keluarga" id="btn_keluarga" data-toggle="tab"><i class="fa fa-users"></i> Data Keluarga</a></li>
      <li><a onclick="data_pendidikan()" href="#pendidikan" id="btn_pendidikan" data-toggle="tab"><i class="fa fa-mortar-board"></i> Data Pendidikan</a></li>
      <li><a onclick="data_organisasi()" href="#organisasi" id="btn_org" data-toggle="tab"><i class="fa fa-mortar-board"></i> Organisasi</a></li>
      <li><a onclick="data_no_penting()" href="#no_penting" data-toggle="tab"><i class="fa fa-credit-card"></i> Nomor Penting</a></li>
   </ul>
   <div class="tab-content">
      <div class="tab-pane active" id="pribadi">
         <?php $this->load->view('_partial/_view_employee_pribadi_fo'); ?>
      </div>
      <div class="tab-pane" id="keluarga">
         <?php $this->load->view('_partial/_view_employee_keluarga_fo'); ?>
      </div>
      <div class="tab-pane" id="pendidikan">
         <?php $this->load->view('_partial/_view_employee_pendidikan_fo'); ?>
      </div>
      <div class="tab-pane" id="organisasi">
         <?php $this->load->view('_partial/_view_employee_organisasi_fo'); ?>
      </div>
      <div class="tab-pane" id="no_penting">
         <?php $this->load->view('_partial/_view_employee_nomor_fo'); ?>
      </div>
   </div>
</div>