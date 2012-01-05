<?php
/**
 * @copyright Copyright (C) 2010 Zynga Game Network Inc.
 * DataStore.class.php
 *
 * PHP Class to access membase
 *
 */
 
 
class DataStore {
	private static $m_memcacaheObj;
	
	private static function initFunction() {
		self::connect();
	}
	
	private static function connect() {
		self::$m_memcacaheObj = new Memcache;
		self::$m_memcacaheObj->connect('localhost', 11211) or die ("Could not connect");
	}
	
	
	//returns bool
	public static function set($key, $value, $expire=0, $flag=MEMCACHE_COMPRESSED) {
		self::connect();
		return memcache_set(self::$m_memcacaheObj, $key, $value, $flag, $expire);
	}
	
	//returns data or null
	public static function get($key) {
		self::connect();
		return memcache_get(self::$m_memcacaheObj, $key);
	}

}
 