<?php
$this->load->view($skin.'/header',  array('header_title' => $page_title));
?><h2 style="vertical-align:middle"><img src="<?php echo base_url(); ?>img/icons/spelling_32.png" class="nb" alt=""> <?php echo $page_title; ?></h2><?
$this->load->view($skin.'/'.$page_content);
$this->load->view($skin.'/footer');
?> 
