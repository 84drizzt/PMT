<?php
class My_ImageHelper
{
	
	private static $convert = '/usr/bin/convert';
	private static $composite = '/usr/bin/compose';
	
	private function __construct() {}
	
	public static function resize($origFile, $destFile, $size) {
		$convert = self::getConvert();
		$cmd = "\"{$convert}\" -strip -resize {$size} \"{$origFile}\" \"{$destFile}\"";
		exec($cmd, $output, $return);
		if ($return) {
			return $cmd;
		} else {
			return 0;
		}
	}
	
	public static function sample($origFile, $destFile, $size) {
		$convert = self::getConvert();
		$cmd = "\"{$convert}\" -strip -sample {$size} \"{$origFile}\" \"{$destFile}\"";
		exec($cmd, $output, $return);
		if ($return) {
			return $cmd;
		} else {
			return 0;
		}
	}
	
	public static function watermark($file, $watermarkFile, $pos='', $transparent='75') {
		$composite = self::getComposite();
		if (empty($pos)) {
			$pos = 'SouthEast';
		}
		$t1 = 100-$transparent;
		$t = "{$transparent}x{$t1}";
		$cmd = "\"{$composite}\" -strip -gravity {$pos} -blend {$t} \"{$watermarkFile}\" \"{$file}\" \"{$file}\"";
		exec($cmd, $output, $return);
		if ($return) {
			return $cmd;
		} else {
			return 0;
		}
	}
	
	public static function normalizePath($path)
	{
		return rtrim($path, '/\\').DIRECTORY_SEPARATOR;
	}
	
	private static function getConvert() {
		$config = Zend_Registry::get('config');
		$config = $config['imagemagick'];
		if (empty(self::$convert)) {
			self::$convert = $config['convert'];
		}
		return self::$convert;
	}
	
	private static function getComposite() {
		$config = Zend_Registry::get('config');
		$config = $config['imagemagick'];
		if (empty(self::$composite)) {
			self::$composite = $config['composite'];
		}
		return self::$composite;
	}
}
