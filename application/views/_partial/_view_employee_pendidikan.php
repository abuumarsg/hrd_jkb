<meta http-equiv="cache-control" content="no-cache" />
<meta http-equiv="pragma" content="no-cache" />
<script src="<?php echo base_url('asset/bower_components/jquery/dist/jquery.min.js');?>"></script>
<div class="nav-tabs-custom">
	<ul class="nav nav-tabs">
		<li class="active"><a onclick="data_formal()" href="#formal" id="btn_formal" data-toggle="tab">Formal</a></li>
		<li><a onclick="data_non_formal()" href="#n_formal" id="btn_nformal" data-toggle="tab">Non-Formal</a></li>
	</ul>
  <div class="tab-content">
		<div class="tab-pane active" id="formal">
      <?php $this->load->view('_partial/_view_employee_formal'); ?>
		</div>
		<div class="tab-pane" id="n_formal">
      <?php $this->load->view('_partial/_view_employee_n_formal'); ?>
		</div>
  </div>
</div>