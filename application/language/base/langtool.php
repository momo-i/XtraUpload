<?php

if(strcmp(PHP_SAPI, 'cli') !== 0)
{
	exit("This script must be run from the command line\n");
}

define('BASEDIR', __DIR__);
define('VERSION', '3.0.0 Beta1');
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
	--keyword="nlang:1,1t" \
	--keyword="nlang:1c,2,2t" \
	--keyword="nlang:1,2,3t" \
	--keyword="nlang:1c,2,3,4t" \
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
	$init_cmd = 'LANG=%s.UTF-8 msginit -l %s.UTF-8 --no-translator -i %s -o %s';
	$init_cmd2 = 'LANG=%s.UTF-8 msginit -l %s.UTF-8 -i %s -o %s';

	$opendir = scandir($langdir);
	foreach ( $opendir as $lang)
	{
		if (preg_match('#^(index\.html|Makefile|base|\.)#', $lang))
		{
			print "ignoring $lang continue.\n";
			continue;
		}
		$pofile = "{$langdir}/{$lang}/xtraupload.po";
		$mofile = "{$langdir}/{$lang}/xtraupload.mo";
		if (strcmp($lang, 'en_US') === 0 OR ! file_exists($pofile) OR strcmp($lang, 'en_GB') === 0)
		{
			echo "Recompiling {$lang}\n";
			@unlink($pofile);
			@unlink($mofile);
			if (strcmp($lang, 'en_US') === 0 OR strcmp($lang, 'en_GB') === 0)
			{
				$init = sprintf($init_cmd, $lang, $lang, $potfile, $pofile);
			}
			else
			{
				$init = sprintf($init_cmd2, $lang, $lang, $potfile, $pofile);
			}
			shell_exec($init);
		}
		else
		{
			echo "Merging {$lang}\n";
			$merge = sprintf($merge_cmd, $pofile, $potfile);
			shell_exec($merge);
		}
		echo "Compiling {$lang}\n";
		$compile = sprintf($compile_cmd, $pofile, $mofile);
		shell_exec($compile);
	}
}
