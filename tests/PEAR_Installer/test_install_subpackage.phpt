--TEST--
PEAR_Installer->install() (subpackage that conflicts)
--SKIPIF--
<?php
if (!getenv('PHP_PEAR_RUNTESTS')) {
    echo 'skip';
}
?>
--FILE--
<?php
require_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'setup.php.inc';
require_once 'PEAR/PackageFile.php';
require_once 'PEAR/PackageFile/v1.php';

$pf = new PEAR_PackageFile($config);
$package = $pf->fromPackageFile(dirname(__FILE__) . DIRECTORY_SEPARATOR .
    'test_install_subpackage' . DIRECTORY_SEPARATOR . 'package.xml', PEAR_VALIDATE_INSTALLING);

$subpackage = $pf->fromPackageFile(dirname(__FILE__) . DIRECTORY_SEPARATOR .
    'test_install_subpackage' . DIRECTORY_SEPARATOR . 'subpackage.xml', PEAR_VALIDATE_INSTALLING);

$oldpackage = new PEAR_PackageFile_v1;
$oldpackage->setPackage('foo');
$oldpackage->setSummary('foo');
$oldpackage->setDescription('foo');
$oldpackage->setDate('2004-10-01');
$oldpackage->setLicense('PHP License');
$oldpackage->setVersion('1.0');
$oldpackage->setState('stable');
$oldpackage->setNotes('foo');
$oldpackage->addFile('/', 'foo.php', array('role' => 'php'));
$oldpackage->addFile('/', 'bar.php', array('role' => 'php'));
$oldpackage->addMaintainer('lead', 'cellog', 'Greg Beaver', 'cellog@php.net');
$oldpackage->addPackageDep('bar', '1.0', 'ge');
$reg = &$config->getRegistry();
$reg->addPackage2($oldpackage);

$phpunit->assertNoErrors('setup');

$params = array(&$package, &$subpackage);
$installer->setDownloadedPackages($params);
$installer->install($subpackage, array('upgrade' => true));
$phpunit->assertNoErrors('install');
echo 'tests done';
?>
--EXPECT--
tests done