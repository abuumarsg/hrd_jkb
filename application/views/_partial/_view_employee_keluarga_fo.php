<meta http-equiv="cache-control" content="no-cache" />
<meta http-equiv="pragma" content="no-cache" />
<div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
        <li class="active"><a onclick="data_ayah()" href="#ayah" data-toggle="tab">Data Ayah</a></li>
        <li><a onclick="data_ibu()" href="#ibu" data-toggle="tab">Data Ibu</a></li>
        <li><a onclick="data_anak()" href="#anak" id="btn_anak" data-toggle="tab">Data Anak</a></li>
        <li><a onclick="data_saudara()" href="#saudara" id="btn_saudara" data-toggle="tab">Data Saudara</a></li>
        <li><a onclick="data_pasangan()" href="#pasangan" data-toggle="tab">Data Pasangan</a></li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="ayah">
            <?php $this->load->view('_partial/_view_employee_ayah_fo'); ?>
        </div>
        <div class="tab-pane" id="ibu">
            <?php $this->load->view('_partial/_view_employee_ibu_fo'); ?>
        </div>
        <div class="tab-pane" id="anak">
            <?php $this->load->view('_partial/_view_employee_anak_fo'); ?>
        </div>
        <div class="tab-pane" id="saudara">
            <?php $this->load->view('_partial/_view_employee_saudara_fo'); ?>
        </div>
        <div class="tab-pane" id="pasangan">
            <?php $this->load->view('_partial/_view_employee_pasangan_fo'); ?>
        </div>
    </div>
</div>