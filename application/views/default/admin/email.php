        <script type="text/javascript" src="<?php echo base_url(); ?>/js/charts.js"></script>
        <h2 style="vertical-align:middle">
          <img src="<?php echo base_url(); ?>img/icons/reports_32.png" class="nb" alt="">
          <?php echo lang('Mass Emailer'); ?> 
        </h2>
<?php
if( ! empty($flash_message))
{
?>
        <p><?php echo $flash_message; ?></p>
<?php
}
?>
        <form method="post" action="<?php echo site_url('admin/email/send'); ?>">
          <h3><?php echo lang('Send Mass EMail'); ?></h3>
          <p>
            <label><?php echo lang('Select user group to send email to'); ?></label>
            <select name="group">
<?php
$groups = $this->db->get('groups');
foreach ($groups->result() as $group)
{
?>
              <option value="<?php echo $group->id; ?>"><?php echo $group->name; ?></option>
<?php
}
?>
            </select><br>
            <label><?php echo lang('Subject'); ?></label>
            <input type="text" size="60" name="subject"><br>
            <label><?php echo lang('Message'); ?></label>
            <textarea name="msg" cols="60" rows="10"></textarea><br>
            <?php echo generate_submit_button('Send Emails', base_url().'img/icons/ok_16.png', 'green')?><br><br>
          </p>
        </form>
