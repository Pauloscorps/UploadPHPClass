<?php

/**
 * Upload
 * A simple class to upload files on your server
 * 
 * Copyright (C) 2014  Pauline Ghiazza
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * @author 		Pauline Ghiazza
 * @license     GNU General Public License
 * @version    	1.0 [April 2014]
 * @link       	http://paulineghiazza.fr
 * @since      	Class available since release 1.0
 */ 

class Upload {

	private $_error_messages = array(
		'upload' => 'Internal error when moving file.',
		'_check_size' => 'The file size is over limit',
		'_check_extension' => 'This file extension is not allowed.'
	);

	public $errors = array();
	public $final_name;

	private $_final_path;
	private $_file;
	private $_name;
	private $_size;
	private $_max_size_allowed;
	private $_path;
	private $_extension;
	private $_tmp_name;
	private $_allowed_extensions = array();


	/**
	 * __construct : Class constructor
	 *
	 * @param  string   $directory 	Upload directory path from site root
	 * @param  array  	$file  		$_FILES array
	 */
	public function __construct($directory, $file) {
		$this->_file = $file;
		$this->_name = $file['name'];
		$this->_tmp_name = $file['tmp_name'];
		$this->_size = $file['size'];
		$this->_path = $this->_set_directory($directory);
		$this->_extension = $this->_get_file_extension();
		$this->_set_final_path();
	}


	/**
	 * set_name : Setter for final name of the file
	 *
	 * @param  string   $string 	The final name of the file
	 * @param  boolean  $rewrite  	Set to true if you want to avoid special chars & spaces
	 */
	public function set_name($name, $rewrite = false) {
		if($rewrite === true)
			$name = $this->_rewrite($name);

		$this->_name = $name;
		$this->_set_final_path();
	}


	/**
	 * set_max_size_allowed : Setter for final name of the file
	 * If call, this method automatically check if file size is under limit
	 *
	 * @param 	bigint   	$max_size 			The max allowed size for this file (in bytes)
	 */
	public function set_max_size_allowed($max_size) {
		$this->_max_size_allowed = $max_size;
		$this->_check_size();
	}


	/**
	 * set_allowed_extensions : Setter for allowed extensions
	 * If call, this method automatically check if the file extension is allowed
	 *
	 * @param 	array   	$extensions 		An array containing all the allowed extensions (without dot)
	 */
	public function set_allowed_extensions($extensions) {
		$this->_allowed_extensions = $extensions;
		$this->_check_extension();
	}	


	/**
	 * upload : Upload
	 *
	 * @param 	boolean		$erase_if_exists	If a file with the same name already exists, define if the file must be rename or rename the previous
	 * @return  array   	$errors 			If there are errors, they are returned
	 * @return 	boolean							Return true or false if move_uploaded_file succeed
	 */
 	public function upload($erase_if_exists = false) {
 		if($this->errors !== array())
 			return $this->errors;

 		$file_exists = $this->_check_file_exists();
 		if($file_exists && !$erase_if_exists) {
 			$this->set_name($this->_name.rand(0, 10));
 			$this->upload($erase_if_exists);
 		}

 		if(move_uploaded_file($this->_tmp_name, $this->_final_path))
 			return true;
 		return false;
	}


	/**
	 * _set_final_path : Define final & complete path of the file
	 */
	private function _set_final_path() {
		$this->final_name = $this->_name.'.'.$this->_extension;
		$this->_final_path = $this->_path.$this->final_name;			
	}


	/**
	 * _set_directory : Set path to upload directory
	 *
	 * @param 	string  $directory 	The directory path from site root
	 * @return  string 	$path 		Return the upload directory complete path
	 */
	private function _set_directory($directory) {
		if(substr($directory, -1) !== "/")
			$directory = $directory.'/';

		$path = $_SERVER['DOCUMENT_ROOT'].$directory;
		return $path;
	}


	/**
	 * _set_error : Add error to the array
	 *
	 * @param  string 	The method (which returns the error) name
	 */
	private function _set_error($method) {
		$this->errors[] = $this->_error_messages[$method];
	}


	/**
	 * _check_size : Check is size is too big
	 *
	 * @return  boolean 	Return true (file size is ok) or false (file size is not)
	 */
	private function _check_size() {
		if($this->_size > $this->_max_size_allowed) {
			$this->_set_error('_check_size');
			return false;
		} else {
			return true;
		}
	}


	/**
	 * _check_file_exists : Check if a file with the same name already exists
	 *
	 * @return  boolean 	Return true (file name is unique) or false (file name is not)
	 */
	private function _check_file_exists() {
		return file_exists($this->_final_path);
	}


	/**
	 * _check_extension : Check if file extension is allowed
	 *
	 * @return  boolean 	Return true (file extension is ok) or false (file extension is not)
	 */
	private function _check_extension() {
		if($this->_allowed_extensions === array())
			return true;

		if(in_array($this->_extension, $this->_allowed_extensions)) {
			return true;
		} else {
			$this->_set_error('_check_extension');
			return false;
		}
	}


	/**
	 * _get_file_extension : Determine file extension
	 *
	 * @return  string 	File extension (without dot)
	 */
	private function _get_file_extension() {
		$parts = explode('.', $this->_name);
		return strtolower(end($parts));
	}


	/**
	 * _rewrite : Rewrite a string to avoid special chars & spaces
	 *
	 * @param 	string	$name 	The original name of the file
	 * @return  string 	$name 	The name formated
	 */
	private function _rewrite($name) {
		$name = preg_replace("`\[.*\]`U","", $name);
		$name = preg_replace('`&(amp;)?#?[a-z0-9]+;`i','-',$name);
		$name = htmlentities($name, ENT_NOQUOTES, 'utf-8');
		$name = preg_replace("`&([a-z])(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig);`i","\\1", $name);
		$name = preg_replace(array("`[^a-z0-9]`i","`[-]+`"), "-", $name);
		$name = ($name == "") ? $type : strtolower(trim($name, '-'));
		return $name;
	}

}