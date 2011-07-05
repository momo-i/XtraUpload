<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * XtraUpload
 *
 * A turn-key open source web 2.0 PHP file uploading package requiring PHP v5
 *
 * @package		XtraUpload
 * @author		Matthew Glinski
 * @copyright	Copyright (c) 2006, XtraFile.com
 * @license		http://xtrafile.com/docs/license
 * @link		http://xtrafile.com
 * @since		Version 2.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * XtraUpload Users Model
 *
 * @package		XtraUpload
 * @subpackage	Model
 * @category	Model
 * @author		Matthew Glinski
 * @link		http://xtrafile.com/docs/pages/files
 */

class Users extends CI_Model {

    public $loggedin = false;

	// ------------------------------------------------------------------------
	
    public function __construct()
    {
        // Call the Model constructor
        parent::__construct();
		$this->check_user_auth();
    }
	
	// ------------------------------------------------------------------------
	
	/**
	 * Users->get_user_by_id()
	 *
	 * Load a user object by id
	 *
	 * @access	public
	 * @param	string
	 * @return	none
	 */
	public function get_user_by_id($id)
	{
		$query = $this->db->get_where('users', array('id' => $id));
		return $query->row();
	}
    
	// ------------------------------------------------------------------------
	
	/**
	 * Users->check_user_auth()
	 *
	 * Load a view variable to see if the user is logged in
	 *
	 * @access	public
	 * @param	string
	 * @return	none
	 */
    public function check_user_auth()
    {
		if($this->session->userdata('id'))
		{
			log_message('debug', 'check_user_auth TRUE');
			$this->load->vars(array('loggedin' => true));
			$this->loggedin = true;
		}
		else
		{
			log_message('debug', 'check_user_auth FALSE');
			$this->load->vars(array('loggedin' => false));
			if(!stristr(uri_string(),'user/login'))
			{
				// Force all users to login by uncommenting the following line
				//redirect('/user/login');
			}
		}
    }
	
	// ------------------------------------------------------------------------
	
	/**
	 * Users->get_username_by_id()
	 *
	 * Get a username from a user id
	 *
	 * @access	public
	 * @param	int
	 * @return	none
	 */
	public function get_username_by_id($id)
	{
		$query = $this->db->get_where('users', array('id' => $id));
		if($query->num_rows() != '1')
		{
			return 'Anonymous';
		}
		return $query->row()->username;
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Users->user_logout()
	 *
	 * Log the user out
	 *
	 * @access	public
	 * @return	none
	 */
	public function user_logout()
    {
		log_message('debug', 'User logouted.');
		$this->session->sess_destroy();
		return true;
    }
	
	// ------------------------------------------------------------------------
	
	/**
	 * Users->user_update()
	 *
	 * Update the user entry in the DB with new entries
	 *
	 * @access	public
	 * @param	array
	 * @return	none
	 */
	public function user_update($data)
	{
		$this->db->where('id', $this->session->userdata('id'));
		$this->db->update('users', $data); 
		return true;
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Users->user_update_forgot()
	 *
	 * Save a new password to the user account
	 *
	 * @access	public
	 * @param	string
	 * @param	string
	 * @return	none
	 */
	public function user_update_forgot($pass, $username)
	{
		$this->db->where('username', $username);
		$this->db->update('users', array('password' => $pass)); 
		return true;
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Users->process_login()
	 *
	 * Run a login attempt
	 *
	 * @access	public
	 * @param	string
	 * @param	string
	 * @return	none
	 */
	public function process_login($user, $pass)
	{
		// Check if user exists in DB 
		$query = $this->db->get_where('users', array('username' => $user, 'status' => 1, 'password' => md5($this->config->config['encryption_key'].$pass)));
		$num = $query->num_rows();
		
		// If there is a user
		if($num == 1)
		{
			// Get user data and setup session
			$user_data = $query->row();
			
			$newdata = array(
					   'username'  	=> $user,
					   'id'			=> $user_data->id,
					   'group'		=> $user_data->group,
					   'email'     	=> $user_data->email,
					   'loggedin'	=> TRUE,
					   'login'		=> TRUE,
					   'ip_logged'	=> FALSE
				   );
	
			$this->session->set_userdata($newdata);
			return true;
		}
		else
		{
			return false;
		}
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Users->gen_pass()
	 *
	 * Generate a password
	 * DEPERICIATED
	 *
	 * @access	public
	 * @param	string
	 * @param	bool
	 * @return	none
	 */
	public function gen_pass($length, $caps=true)
	{
		// Depriciated, use the refrenced function
		return $this->functions->gen_pass($length, $caps);
	}
	
	// ------------------------------------------------------------------------
	
	/**
	 * Users->new_user()
	 *
	 * Save the new user to the database and send them an email
	 *
	 * @access	public
	 * @param	array
	 * @param	bool
	 * @return	none
	 */
	public function new_user($data, $pay=false)
	{
		
		// save the new user to the DB
		$this->db->insert('users', $data);
		$id = $this->db->insert_id();
		
		if(!$pay)
		{
			$to = $data['email'];
			$user = $this->db->get_where('users', array('id' => $id))->row();
			$group = $this->db->get_where('groups', array('id' => $user->group))->row();
			$this->send_new_user_email($to, $user, $group);
		}
		else
		{
			$to = $data['email'];
			$user = $this->db->get_where('users', array('id' => $id))->row();
			$this->send_pay_link_email($to, $user, $id);
		}
		
		return $id;
	}
	
	public function send_new_user_email($to, $user, $group)
	{
		// Load the email library
		$this->load->library('email');
		
		// Setup the mail library
		$config['mailtype'] = 'html';
		$this->email->initialize($config);
		
		$rec = array(
			'd' => lang('Daily'),
			'w' => lang('Weekly'),
			'm' => lang('Monthly'),
			'y' => lang('Yearly'),
			'dy' => lang('Bi-Yearly'),
		);
		
		// Set email options
		$this->email->from($this->startup->site_config->site_email, $this->startup->site_config->sitename.' Support');
		$this->email->to($to);
		$subject = sprintf(lang('New user ad %s!'), $this->startup->site_config->sitename);
		$this->email->subject($subject);

		$msg  = lang('Hello %s,')."<br>\n";
		$msg .= lang('Welcome to %s!')."<br><br>\n\n";
		$msg .= lang('Here are your account details should you ever need them:')."<br><br>\n\n";
		$msg .= '--------------------------'."<br>\n";
		$msg .= lang('Username: %s')."<br>\n";
		$msg .= lang('Group: %s')."<br>\n";

		$msg = sprintf($msg, $user->username, $this->startup->site_config->sitename, $user->username, ucwords($group->name));

		if($group->price > 0.00)
		{
			$msg .= sprintf(lang('Ammount Paid: %s'), $group->price)."<br>\n";
			if($group->repeat_billing)
			{
				$msg .= sprintf(lang('Billing Period: %s'), $rec[$group->repeat_billing])."<br>\n";
			}
		}

		$msg .= '--------------------------'."<br><br>\n\n";
		$msg .= lang('Thanks for joining our community!')."<br>\n";
		$msg .= sprintf(lang('%s Administration'), $this->startup->site_config->sitename)."\n";

		$this->email->message($msg);
		
		// Send the email
		$this->email->send();
	}
	
	public function send_pay_link_email($to, $user, $id)
	{
		// Load the email library
		$this->load->library('email');
		
		// Setup the mail library
		$config['mailtype'] = 'html';
		$this->email->initialize($config);
		
		// Set email options
		$this->email->from($this->startup->site_config->site_email, $this->startup->site_config->sitename.' Support');
		$this->email->to($to);
		$subject = sprintf(lang('New user at %s!'), $this->startup->site_config->sitename);
		$this->email->subject($subject);

		$msg  = sprintf(lang('Hello %s,'), $user->username)."<br>\n";
		$msg .= sprintf(lang('Welcome to %s!'), $this->startup->site_config->sitename)."<br>\n";
		$msg .= lang('Before you account is activated you need to pay using the following link. If you have already completed the payment process, please wait while we authorize your payment. Once complete you will recive a new email containg your details.')."<br><br>\n\n";
		$msg .= anchor('user/pay_new/'.$id.'/'.$user->gateway, lang('Pay Here'))."<br><br>\n\n";
		$msg .= lang('Thanks for joining our community!')."<br>\n";
		$msg .= sprintf(lang('%s Administration'), $this->startup->site_config->sitename)."\n";

		$this->email->message($msg);
		
		// Send the email
		$this->email->send();
	}
}

/* End of file users.php */
/* Location: ./application/models/users.php */
