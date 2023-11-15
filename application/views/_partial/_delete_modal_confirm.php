<div id="delete" class="modal fade" role="dialog">
  <div class="modal-dialog modal-sm modal-danger">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title text-center">Konfirmasi Hapus</h4>
      </div>
      <form id="form_delete">
        <div class="modal-body text-center">
          <input type="hidden" id="data_column_delete" name="column">
          <input type="hidden" id="data_id_delete" name="id">
          <input type="hidden" id="data_table_delete" name="table">
          <input type="hidden" id="data_form_table" value="#table_data">
          <input type="hidden" id="data_form_table_u" value="table_view">
          <input type="hidden" id="data_file" name="file">
          <input type="hidden" id="data_table_drop" name="table_drop">
          <input type="hidden" id="data_link_table" name="link_table">
          <input type="hidden" id="data_link_col" name="link_col">
          <input type="hidden" id="data_link_data_col" name="link_data_col">
          <p>Apakah anda yakin akan menghapus data dengan nama <b id="data_name_delete" class="header_data"></b> ?</p>
        </div>
      </form>
      <div class="modal-footer">
        <button type="button" onclick="do_delete()" class="btn btn-primary"><i class="fa fa-trash"></i> Hapus</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript" charset="utf-8" async defer>
  function do_delete(){
    var tabelx = $('#data_form_table_u').val();
    if(tabelx==''){
      var table = $('#data_form_table').val();
    }else{
      var tbl = $('#data_form_table_u').val();
      var table = '#'+tbl;
    }
    submitAjax("<?php echo base_url('global_control/delete')?>",'delete','form_delete',null,null);
    $(table).DataTable().ajax.reload(function (){
      Pace.restart();
    });
  }
</script>