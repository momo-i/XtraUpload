<?php
$registered = $this->db->get_where('refrence', array('user' => '0'))->num_rows();
$anonym = $this->db->get_where('refrence', array('user !=' => '0'))->num_rows();
?>

