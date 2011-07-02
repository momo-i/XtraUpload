#!/usr/bin/env php
<?php

require_once 'File/Find.php';

define('BASEPATH', true);
define('BASEDIR', realpath(__DIR__.'/../'));
define('APPDIR', BASEDIR.'/application');
define('LANGDIR', APPDIR.'/language');
define('VIEWDIR', APPDIR.'/views');
define('CTLDIR', APPDIR.'/controllers');
define('LIBDIR', APPDIR.'/libraries');
define('MODELDIR', APPDIR.'/models');

$installs = array('english', 'japanese');

//$a = get_terms(CTLDIR);
//$b = get_terms(VIEWDIR);
//$c = array_merge($a, $b);

foreach ($installs as $install)
{
	$lang = get_language($install);
	$ctl = get_terms(CTLDIR);
	$view = get_terms(VIEWDIR);
	$lib = get_terms(LIBDIR);
	$model = get_terms(MODELDIR);
	$all = array_merge($ctl, $view);
	$all = array_merge($all, $lib);
	$all = array_merge($all, $model);
	merge($all, $lang, $install);
}

function merge($terms, $lang, $language = 'english')
{
	$ignore = array('html_lang', 'language_direction');
	$fp = fopen(LANGDIR."/$language/global_lang.php", 'w');
	$header = <<<EOF
<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * XtraUpload
 *
 * A turn-key open source web 2.0 PHP file uploading package requiring PHP v5
 *
 * @package     XtraUpload
 * @author      Matthew Glinski
 * @copyright   Copyright (c) 2006, XtraFile.com
 * @license     http://xtrafile.com/docs/license
 * @link        http://xtrafile.com
 * @since       Version 2.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * XtraUpload Global Language File
 *
 * @package     XtraUpload
 * @subpackage  Language
 * @category    Language
 * @author      Matthew Glinski
 * @link        http://xtrafile.com/docs/language
 */

// ------------------------------------------------------------------------


EOF;
	fwrite($fp, $header);
	foreach ($terms as $term=>$lines)
	{
		foreach ($ignore as $notuse)
		{
			if(strcmp($notuse, $term) == "0")
			{
				continue 2;
			}
		}
		foreach ($lines as $line)
		{
			fwrite($fp, "$line\n");
		}
		foreach($lang as $key=>$val)
		{
			if(strcmp($key, $term) == "0")
			{
				fwrite($fp, '$lang[\''.$key.'\'] = \''.$val.'\';'."\n\n");
				continue 2;
			}
		}
		fwrite($fp, '$lang[\''.$term.'\'] = \''.$term.'\';'."\n\n");
	}

	$footer = <<<EOF
/* End of file global_lang.php */
/* Location: ./application/language/{$language}/global_lang.php */

EOF;

	fwrite($fp, $footer);
	fclose($fp);
	return true;
}

function get_language($language = 'english')
{
	include_once(LANGDIR."/$language/global_lang.php");
	return $lang;
}

function get_terms($dir)
{

	$pattern = '#lang\(\'(.*?)\'\)#';

	list($dirs, $files) = File_Find::maptree($dir);
	foreach ($files as $file)
	{
		if(!preg_match('#\.php$#', $file))
		{
			continue;
		}
		$fp = fopen($file, 'r');
		$i=1;
		$appath = ltrim(str_replace(BASEDIR, '', $file), '/');
		while(!feof($fp))
		{
			$line = trim(fgets($fp));
			if(empty($line))
			{
				++$i;
				continue;
			}
			preg_match_all($pattern, $line, $out);
			if(!empty($out[1]))
			{
				foreach($out[1] as $arr)
				{
					$dev[$arr][] = "#: $appath:$i";
				}
			}
			++$i;
		}
	}
	return $dev;
}
