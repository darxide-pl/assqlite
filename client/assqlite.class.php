<?php 

class assqlite 
{
	private $db;
	private $host;
	private $options = array();
	private $busyTimeout = false;
	private $queryString;
	private $result;
	private $array;
	private $point = false;
	private $i = 0;

	function __construct($host , $db)
	{
		$this->db = $db;
		$host = str_replace("http://","",$host);
		$host = str_replace("https://","",$host);
		$this->host = $host;
	}

	/**
	 *	@param $ms [integer]
	 */
	public function busyTimeout($ms)
	{
		try 
		{
			$this->isInt($ms);
			$this->busyTimeout = $ms;
		} catch (Exception $e) 
		{
			echo 'assqlite::busyTimeout() error - '.$e->getMessage() . "\r\n";
			exit();
		}
	}	

	/**
	 *	@param $stmt [string]
	 */
	public function query($stmt)
	{
		try
		{
			$this->isString($stmt);
			$this->queryString = $stmt;
			$this->execute('query');
		} catch (Exception $e)
		{
			echo 'assqlite::query() error - '.$e->getMessage() . "\r\n";
			exit();
		}
		return $this;
	}

	/**
	 *	@param $stmt [string]
	 */
	public function exec($stmt)
	{
		try
		{
			$this->isString($stmt);
			$this->queryString = $stmt;
			$this->execute('exec');
		} catch(Exception $e)
		{
			echo 'assqlite::exec() error - '.$e->getMessage() ."\r\n";
			exit();
		}
	}

	/**
	 *	@param $var [mixed]
	 */
	private function isInt($var)
	{
		if(!is_int($var))
		{
			throw new Exception("must be an integer");
		}
	}

	/**
	 *	@param $var [mixed]
	 */
	private function isString($var)
	{
		if(!is_string($var))
		{
			throw new Exception("must be a string");
		}

		return $var;
	}

	/**
	 *	@param $method [string[query|exec]]
	 */
	private function setOpt($method)
	{
		$this->options['db'] = $this->db;
		if($this->busyTimeout !== false)
		{
			$this->options['busyTimeout'] = $this->busyTimeout;
		}
		$this->options['queryString'] = $this->queryString;
		$this->options['method'] = $method;
	}

	public function fetchArray()
	{
		if($this->point === true)
		{
			next($this->array);	
		}

		$this->point = true;

		return current($this->array);
	}

	public function fetchObject()
	{
		$this->i++;
		if($this->point === true)
		{
			next($this->array);	
		}

		$this->point = true;

		if($this->i <= count($this->array))
		{
			return json_decode(json_encode(current($this->array)) ,false);	
		}
	}

	/**
	 *	@param $offset [int] >= 0
	 */
	public function data_seek($offset) {
		try {
			$this->isValidOffset($offset);
			reset($this->array);
			$this->i = $offset;
			$this->point = false;	
			if($offset > 0) {
				$this->point = true;
				$this->i = --$offset;
				while (key($this->array) < $offset) next($this->array);
			}
		} catch(Exception $e) {
			echo "assqlite::data_seek() error - ".$e->getMessage();;
		}
	}

	/**
	 *	check if var is greather or equal zero
	 */
	private function isValidOffset($var) {
		if(!is_int($var)) {
			throw new Exception('offset must be integer');
		}
		if($var < 0) {
			throw new Exception('offset must be greather or equal zero');
		}
		if($var > count($this->array)) {
			throw new Exception("offset can't be greather than result count");			
		}
	}

	/**
	 *	@param $method[string[query|exec]]
	 */
	private function execute($method)
	{
		$this->setOpt($method);
		$postdata = http_build_query($this->options);

		$opts = array('http' =>
		    array(
		        'method'  => 'POST',
		        'header'  => 'Content-type: application/x-www-form-urlencoded',
		        'content' => $postdata
		    )
		);
		$context = stream_context_create($opts);
		$result = file_get_contents('http://'.$this->host."/assqlite.php", false, $context);

		if($method == 'query')
		{
			$this->array = json_decode($result ,true);
			$this->point = false;
		}
	}

	/**
	 * 	@return result count
	 */
	public function num_rows() {
		return count($this->array);
	}

	private function xprint($var)
	{
		echo "<pre>";
		print_r($var);
		echo "</pre>";
	}

	function __destruct()
	{
		
	}
}