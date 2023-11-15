<meta http-equiv="cache-control" content="no-cache" />
<meta http-equiv="pragma" content="no-cache" />
<div class="nav-tabs-custom">
   <ul class="nav nav-tabs">
      <li class="active"><a onclick="pribadi()" href="#pribadi" data-toggle="tab">Data Pribadi</a></li>
      <li><a onclick="refresh_jabatan()" href="#jabatan" id="btn_jabatan" data-toggle="tab">Data Jabatan</a></li>
      <li><a onclick="data_ayah()" href="#keluarga" id="btn_keluarga" data-toggle="tab">Data Keluarga</a></li>
      <li><a onclick="data_formal()" href="#pendidikan" id="btn_pendidikan" data-toggle="tab">Data Pendidikan</a></li>
      <li><a onclick="data_organisasi()" href="#organisasi" id="btn_org" data-toggle="tab">Organisasi</a></li>
      <li><a onclick="data_penghargaan()" href="#penghargaan" data-toggle="tab">Penghargaan</a></li>
      <li><a onclick="data_bahasa()" href="#bahasa" data-toggle="tab">Bahasa</a></li>
   </ul>
   <div class="tab-content">
      <div class="tab-pane active" id="pribadi">
         <?php $this->load->view('_partial/_view_employee_pribadi'); ?>
      </div>
      <div class="tab-pane" id="jabatan">
         <?php $this->load->view('_partial/_view_employee_jabatan'); ?>
      </div>
      <div class="tab-pane" id="keluarga">
         <?php $this->load->view('_partial/_view_employee_keluarga'); ?>
      </div>
      <div class="tab-pane" id="pendidikan">
         <?php $this->load->view('_partial/_view_employee_pendidikan'); ?>
      </div>
      <div class="tab-pane" id="organisasi">
         <?php $this->load->view('_partial/_view_employee_organisasi'); ?>
      </div>
      <div class="tab-pane" id="penghargaan">
         <?php $this->load->view('_partial/_view_employee_penghargaan'); ?>
      </div>
      <div class="tab-pane" id="bahasa">
         <?php $this->load->view('_partial/_view_employee_bahasa'); ?>
      </div>
   </div>
</div>