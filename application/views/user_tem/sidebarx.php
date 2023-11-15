<?php
$link = 'kpages/'.$this->uri->segment(2);
$seg = $this->uri->segment(2);
?>
  <aside class="main-sidebar">
    <section class="sidebar">
      <div class="user-panel">
        <div class="pull-left image">
          <img src="<?php echo base_url($adm['foto']);?>" id="fp_side" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p><?php echo $adm['nama'];?></p>
          <a href="#"><i class="fa fa-circle" style="color: #15ff00;"></i> Online</a>
        </div>
      </div>
      <ul class="sidebar-menu"  data-widget="tree">
        <li class="header">MENU UTAMA</li>
            <?php
              if($adm['id_group_user'] != null || $adm['id_group_user'] != ''){
                echo $this->otherfunctions->getDrawMenuUser($adm['your_menu'],$adm['menu'],0,$seg);
              }else{
                echo '<h4 class="text-muted bg-red" style="white-space: normal; overflow-wrap: break-word;">Anda Tidak Memperoleh Menu, Silahkan Hubungi Administrator</h4>';
              }
            ?>
      </ul>
    </section>
  </aside>