<?php
/* vim: set ts=4 sw=4 sts=0: */

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
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * XtraUpload Form Validation Class
 *
 * @package		XtraUpload
 * @subpackage	Libraries
 * @category	Validation
 * @author		Matthew Glinski
 * @link		http://xtrafile.com/docs/pages/files
 */
class XU_Form_validation extends CI_Form_validation {

	private $_types = array();

	/**
	 * Initialize Form_Validation class
	 *
	 * @param	array	$rules
	 * @return	void
	 */
	public function __construct($rules = array())
	{

		$this->_types = array(
			'required' => lang('The %s field is required.'),
			'isset' => lang('The %s field must have a value.'),
			'valid_email' => lang('The %s field must contain a valid email address.'),
			'valid_emails' => lang('The %s field must contain all valid email addresses.'),
			'valid_url' => lang('The %s field must contain a valid URL.'),
			'valid_ip' => lang('The %s field must contain a valid IP.'),
			'min_length' => lang('The %s field must be at least %s characters in length.'),
			'max_length' => lang('The %s field can not exceed %s characters in length.'),
			'exact_length' => lang('The %s field must be exactly %s characters in length.'),
			'alpha' => lang('The %s field may only contain alphabetical characters.'),
			'alpha_numeric' => lang('The %s field may only contain alpha-numeric characters.'),
			'alpha_dash' => lang('The %s field may only contain alpha-numeric characters, underscores, and dashes.'),
			'numeric' => lang('The %s field must contain only numbers.'),
			'is_numeric' => lang('The %s field must contain only numeric characters.'),
			'integer' => lang('The %s field must contain an integer.'),
			'regex_match' => lang('The %s field is not in the correct format.'),
			'matches' => lang('The %s field does not match the %s field.'),
			'is_natural' => lang('The %s field must contain only positive numbers.'),
			'is_natural_no_zero' => lang('The %s field must contain a number greater than zero.'),
			'decimal' => lang('The %s field must contain a decimal number.'),
			'less_than' => lang('The %s field must contain a number less than %s.'),
			'greater_than' => lang('The %s field must contain a number greater than %s.'),
		);

		parent::__construct();
		log_message('debug', 'XU Form Validation Class Initialized');
	}

	/**
	 * Executes the Validation routines
	 *
	 * @access	protected
	 * @param	array	$row
	 * @param	array	$rules
	 * @param	mixed	$postdata
	 * @param	int		$cycles
	 * @return	mixed
	 */
	protected function _execute($row, $rules, $postdata = NULL, $cycles = 0)
	{
		// If the $_POST data is an array we will run a recursive call
		if (is_array($postdata))
		{
			foreach ($postdata as $key => $val)
			{
				$this->_execute($row, $rules, $val, $key);
			}

			return;
		}

		// If the field is blank, but NOT required, no further tests are necessary
		$callback = FALSE;
		if ( ! in_array('required', $rules) && ($postdata === NULL OR $postdata === ''))
		{
			// Before we bail out, does the rule contain a callback?
			foreach ($rules as &$rule)
			{
				if (is_string($rule))
				{
					if (strncmp($rule, 'callback_', 9) === 0)
					{
						$callback = TRUE;
						$rules = array(1 => $rule);
						break;
					}
				}
				elseif (is_callable($rule))
				{
					$callback = TRUE;
					$rules = array(1 => $rule);
					break;
				}
			}

			if ( ! $callback)
			{
				return;
			}
		}

		// Isset Test. Typically this rule will only apply to checkboxes.
		if (($postdata === NULL OR $postdata === '') && ! $callback)
		{
			if (in_array('isset', $rules, TRUE) OR in_array('required', $rules))
			{
				// Set the message type
				$type = in_array('required', $rules) ? 'required' : 'isset';

				// Check if a custom message is defined
				if (isset($this->_field_data[$row['field']]['errors'][$type]))
				{
					$line = $this->_field_data[$row['field']]['errors'][$type];
				}
				elseif (isset($this->_error_messages[$type]))
				{
					$line = $this->_error_messages[$type];
				}
				elseif (FALSE === ($line = lang($type)))
				{
					// DEPRECATED support for non-prefixed keys
					$line = lang('The field was not set');
				}

				// Build the error message
				$message = sprintf($this->_types[$line], $this->_translate_fieldname($row['label']));

				// Save the error message
				$this->_field_data[$row['field']]['error'] = $message;

				if ( ! isset($this->_error_array[$row['field']]))
				{
					$this->_error_array[$row['field']] = $message;
				}
			}

			return;
		}

		// --------------------------------------------------------------------

		// Cycle through each rule and run it
		foreach ($rules as $rule)
		{
			$_in_array = FALSE;

			// We set the $postdata variable with the current data in our master array so that
			// each cycle of the loop is dealing with the processed data from the last cycle
			if ($row['is_array'] === TRUE && is_array($this->_field_data[$row['field']]['postdata']))
			{
				// We shouldn't need this safety, but just in case there isn't an array index
				// associated with this cycle we'll bail out
				if ( ! isset($this->_field_data[$row['field']]['postdata'][$cycles]))
				{
					continue;
				}

				$postdata = $this->_field_data[$row['field']]['postdata'][$cycles];
				$_in_array = TRUE;
			}
			else
			{
				// If we get an array field, but it's not expected - then it is most likely
				// somebody messing with the form on the client side, so we'll just consider
				// it an empty field
				$postdata = is_array($this->_field_data[$row['field']]['postdata'])
					? NULL
					: $this->_field_data[$row['field']]['postdata'];
			}

			// Is the rule a callback?
			$callback = $callable = FALSE;
			if (is_string($rule))
			{
				if (strpos($rule, 'callback_') === 0)
				{
					$rule = substr($rule, 9);
					$callback = TRUE;
				}
			}
			elseif (is_callable($rule))
			{
				$callable = TRUE;
			}
			elseif (is_array($rule) && isset($rule[0], $rule[1]) && is_callable($rule[1]))
			{
				// We have a "named" callable, so save the name
				$callable = $rule[0];
				$rule = $rule[1];
			}

			// Strip the parameter (if exists) from the rule
			// Rules can contain a parameter: max_length[5]
			$param = FALSE;
			if ( ! $callable && preg_match('/(.*?)\[(.*)\]/', $rule, $match))
			{
				$rule = $match[1];
				$param = $match[2];
			}

			// Call the function that corresponds to the rule
			if ($callback OR $callable !== FALSE)
			{
				if ($callback)
				{
					if ( ! method_exists($this->CI, $rule))
					{
						log_message('debug', 'Unable to find callback validation rule: '.$rule);
						$result = FALSE;
					}
					else
					{
						// Run the function and grab the result
						$result = $this->CI->$rule($postdata, $param);
					}
				}
				else
				{
					$result = is_array($rule)
						? $rule[0]->{$rule[1]}($postdata)
						: $rule($postdata);

					// Is $callable set to a rule name?
					if ($callable !== FALSE)
					{
						$rule = $callable;
					}
				}

				// Re-assign the result to the master data array
				if ($_in_array === TRUE)
				{
					$this->_field_data[$row['field']]['postdata'][$cycles] = is_bool($result) ? $postdata : $result;
				}
				else
				{
					$this->_field_data[$row['field']]['postdata'] = is_bool($result) ? $postdata : $result;
				}

				// If the field isn't required and we just processed a callback we'll move on...
				if ( ! in_array('required', $rules, TRUE) && $result !== FALSE)
				{
					continue;
				}
			}
			elseif ( ! method_exists($this, $rule))
			{
				// If our own wrapper function doesn't exist we see if a native PHP function does.
				// Users can use any native PHP function call that has one param.
				if (function_exists($rule))
				{
					// Native PHP functions issue warnings if you pass them more parameters than they use
					$result = ($param !== FALSE) ? $rule($postdata, $param) : $rule($postdata);

					if ($_in_array === TRUE)
					{
						$this->_field_data[$row['field']]['postdata'][$cycles] = is_bool($result) ? $postdata : $result;
					}
					else
					{
						$this->_field_data[$row['field']]['postdata'] = is_bool($result) ? $postdata : $result;
					}
				}
				else
				{
					log_message('debug', 'Unable to find validation rule: '.$rule);
					$result = FALSE;
				}
			}
			else
			{
				$result = $this->$rule($postdata, $param);

				if ($_in_array === TRUE)
				{
					$this->_field_data[$row['field']]['postdata'][$cycles] = is_bool($result) ? $postdata : $result;
				}
				else
				{
					$this->_field_data[$row['field']]['postdata'] = is_bool($result) ? $postdata : $result;
				}
			}

			// Did the rule test negatively? If so, grab the error.
			if ($result === FALSE)
			{
				// Callable rules might not have named error messages
				if ( ! is_string($rule))
				{
					return;
				}

				// Check if a custom message is defined
				if (isset($this->_field_data[$row['field']]['errors'][$rule]))
				{
					$line = $this->_field_data[$row['field']]['errors'][$rule];
				}
				elseif ( ! isset($this->_error_messages[$rule]))
				{
					if (FALSE === ($line = lang($rule)))
					{
						$line = lang('Unable to access an error message corresponding to your field name.');
					}
				}
				else
				{
					$line = $this->_error_messages[$rule];
				}

				// Is the parameter we are inserting into the error message the name
				// of another field? If so we need to grab its "field label"
				if (isset($this->_field_data[$param], $this->_field_data[$param]['label']))
				{
					$param = $this->_translate_fieldname($this->_field_data[$param]['label']);
				}

				// Build the error message
				if(isset($this->_types[$line]))
				{
					$message = sprintf($this->_types[$line], $this->_translate_fieldname($row['label']), $param);
				}
				else
				{
					$message = $line;
				}

				// Save the error message
				$this->_field_data[$row['field']]['error'] = $message;

				if ( ! isset($this->_error_array[$row['field']]))
				{
					$this->_error_array[$row['field']] = $message;
				}

				return;
			}
		}
	}

	/**
	 * Translate a field name
	 *
	 * @access	protected
	 * @param	string	$fieldname	the field name
	 * @return	string
	 */
	protected function _translate_fieldname($fieldname)
	{
		// Do we need to translate the field name?
		// We look for the prefix lang: to determine this
		if (sscanf($fieldname, 'lang:%s', $line) === 1)
		{
			// Were we able to translate the field name?  If not we use $line
			if (FALSE === ($fieldname = lang($line)))
			{
				return $line;
			}
		}

		return $fieldname;
	}

}

/* End of file Form_validation.php */
/* Location: ./application/libraries/Form_validation.php */
