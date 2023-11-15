<?php
$link = 'pages/'.$this->uri->segment(2);
$seg = $this->uri->segment(2);
?>
  <aside class="main-sidebar">
    <section class="sidebar">
      <div class="user-panel">
        <div class="pull-left image">
          <img src="<?php echo base_url($adm['foto']);?>" class="img-circle view_photo" alt="User Image">
        </div>
        <div class="pull-left info">
          <p><?php echo $adm['nama'];?></p>
          <a href="#"><i class="fa fa-circle" style="color: #15ff00;"></i> Online</a>
          <!-- <p><?php //echo $adm['id_admin'];?></p> -->
        </div>
      </div>
      <ul class="sidebar-menu"  data-widget="tree">
        <li class="header">MENU UTAMA</li>
            <?php
              echo $this->otherfunctions->getDrawMenu($adm['your_menu'],$adm['menu'],0,$seg);
              // print_r($adm['menu']);
            ?>
      </ul>
    </section>
  </aside>