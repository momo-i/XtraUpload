<?php

if(strcmp(PHP_SAPI, 'cli') !== 0)
{
	exit("This script must be run from the command line\n");
}

define('BASEDIR', __DIR__);

update_core(BASEDIR, 'xtraupload');

function update_core($dir, $domain)
{
	$potfile = BASEDIR."/xtraupload.pot";
	$old = getcwd();

	chdir($dir);

	$application = realpath(BASEDIR."/../../../application");

	passthru(<<<END
xgettext \
	--from-code=UTF-8 \
	--default-domain=$domain \
	--output=$domain.pot \
	--language=PHP \
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


