<?php
/**
 * PHP 7 MAR
 * Scanner Class
 *
 * @author     Alexia E. Smith <washuu@gmail.com>
 * @copyright  2015 Alexia E. Smith
 * @link       https://github.com/Alexia/php7mar
 */

namespace alexia\mar;

class scanner {
	/**
	 * Project File or Path
	 *
	 * @var		string
	 */
	private $projectPath = null;

	/**
	 * List of files.
	 *
	 * @var		array
	 */
	private $files = [];

	/**
	 * List of file extension(s) to process.
	 *
	 * @var   array
	 */
	private $extensions = ['php'];
	
	/*
	 * Current file being examined
	*/
	private $currentFile = null;
	
	/**
	 * Main Constructor
	 *
	 * @access	public
	 * @param	string	Project file or folder
	 * @param	array	[Optional] Array of allowed file extensions.
	 * @return	void
	 */
	public function __construct($projectPath, $extensions = null) {
		if (empty($projectPath)) {
			throw new \Exception(__METHOD__.": Project path given was empty.");
		}
		$this->projectPath = $projectPath;

		if (is_array($extensions)) {
			$this->setFileExtensions($extensions);
		}
		
		if( is_file( $projectPath ) ){
			$this->files = [ $projectPath ];
		}else{
			$Directory 	 = new \RecursiveDirectoryIterator($projectPath.'/');			
			$Iterator 	 = new \RecursiveIteratorIterator($Directory, \RecursiveIteratorIterator::SELF_FIRST);
			$this->files = new \RegexIterator($Iterator, '/^.+\.('.implode('|',$this->extensions).')$/i');
			$this->files->next();
			//$this->files = iterator_to_array( $Files );
		}
	}

	/**
	 * Scan the next file in the array and provide back an array of lines.
	 *
	 * @access	public
	 * @return	mixed	Array of lines from the file or false for no more files.
	 */
	public function scanNextFile() {
		if(is_a( $this->files, 'RegexIterator' )){
			$_file = $this->files->current();
			$this->files->next();
		}else{
			list( $file, $_file ) = each($this->files);	
		}
		if( is_a( $_file, 'SplFileInfo' )){
			$file = $_file->getRealPath();
		}else{
			$file = $_file;
		}
		
		if(!$file) {
			return false;
		}
		
		$this->currentFile = $file;
		
		$fp = fopen( $file, 'r' );
		if( $fp ){
			return $fp;
		}else{
			return true;
		}
	}

	/**
	 * Return the file path of the current array pointer.
	 *
	 * @access	public
	 * @return	string	File Path
	 */
	public function getCurrentFilePath() {
		return $this->currentFile;
	}

	/**
	 * Sets the file extensions to be considered as PHP file. Ex:
	 *
	 *  array('php', 'inc')
	 *
	 * Do NOT include the dot before the extension
	 *
	 * @access	public
	 * @param 	array	Allowed file extensions
	 */
	public function setFileExtensions(array $extensions) {
		$this->extensions = $extensions;
	}

	/**
	 * Gets the list of extensions to be considered PHP files when scanning
	 *
	 * @access	public
	 * @return	array	File extensions to be considered as PHP files.
	 */
	public function getFileExtensions() {
		return (array) $this->extensions;
	}
}
?>