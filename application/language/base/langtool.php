<?php

if(strcmp(PHP_SAPI, 'cli') !== 0)
{
	exit("This script must be run from the command line\n");
}

define('BASEDIR', __DIR__);
define('VERSION', '3.0.0 Alpha2');
define('MAILADDR', 'translator at xtrafile.com');

update_core(BASEDIR, 'xtraupload');
merge_translations(BASEDIR);

function update_core($dir, $domain)
{
	$potfile = BASEDIR."/xtraupload.pot";
	$version = VERSION;
	$mailaddr = MAILADDR;
	$old = getcwd();

	chdir($dir);

	$application = realpath(BASEDIR."/../../../application");

	passthru(<<<END
xgettext \
	--from-code=UTF-8 \
	--default-domain=$domain \
	--output=$domain.pot \
	--language=PHP \
	--copyright-holder="XtraUpload" \
	--package-name="XtraUpload" \
	--package-version="$version" \
	--msgid-bugs-address="$mailaddr" \
	--add-comments=HINT: \
	--keyword="_m:1,1t" \
	--keyword="_m:1c,2,2t" \
	--keyword="_m:1,2,3t" \
	--keyword="_m:1c,2,3,4t" \
	--keyword="pgettext:1c,2" \
	--keyword="npgettext:1c,2,3" \
	--keyword="lang" \
	`find ../../../application -type f`
END
);
	chdir($old);
}

function merge_translations($dir)
{

	$langdir = realpath($dir.'/../');

	$potfile = BASEDIR."/xtraupload.pot";
	$merge_cmd = 'msgmerge -U --backup=off %s %s';
	$compile_cmd = 'msgfmt -v -c %s -o %s';
	$init_cmd = 'msginit -l %s.UTF-8 --no-translator -i %s -o %s';

	$opendir = scandir($langdir);
	foreach ( $opendir as $lang)
	{
		if (preg_match('#^(index\.html|Makefile|base|\.)#', $lang))
		{
			continue;
		}
		$pofile = "{$langdir}/{$lang}/xtraupload.po";
		$mofile = "{$langdir}/{$lang}/xtraupload.mo";
		if (strcmp($lang, 'en_US') === 0 OR ! file_exists($pofile) OR strcmp($lang, 'en_GB') === 0)
		{
			echo "Recompiling {$lang}\n";
			@unlink($pofile);
			@unlink($mofile);
			$init = sprintf($init_cmd, $lang, $potfile, $pofile);
			shell_exec($init);
		}
		else
		{
			echo "Updating {$lang}\n";
			$merge = sprintf($merge_cmd, $potfile, $pofile);
			shell_exec($merge);
		}
		echo "Compiling {$lang}\n";
		$compile = sprintf($compile_cmd, $pofile, $mofile);
		shell_exec($compile);
	}
}
