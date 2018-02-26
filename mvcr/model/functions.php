<?php


function countLines($path, $extensions = array('php')) {
	$it = new RecursiveIteratorIterator(
		new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS)
	);
	$totalLines=0;
	$files = array();
	foreach ($it as $file) {
		if ($file->isDir()) {
			continue;
		}
		$parts = explode('.', $file->getFilename());
		$extension = end($parts);
		if (in_array($extension, $extensions)) {
			$lines = count( file($file->getPathname() ) );
			$totalLines += $lines;
			$files[$file->getPathname()] = $lines;
		}
	}
	return $files;
}



function simple_array_intersect($a, $b) {
	$a_assoc = $a != array_values($a);
	$b_assoc = $b != array_values($b);
	$ak = $a_assoc ? array_keys($a) : $a;
	$bk = $b_assoc ? array_keys($b) : $b;
	$out = array();
	for ($i=0;$i<sizeof($ak);$i++) {
		if (in_array($ak[$i],$bk)) {
			if ($a_assoc) {
				$out[$ak[$i]] = $a[$ak[$i]];
			} else {
				$out[] = $ak[$i];
			}
		}
	}
	return $out;
}

function pecho($text)
{
	if (!DEBUG) {return;}

	if ( is_array($text) || is_object($text)) {
		pprint($text);
		return;
	}
	if (gettype($text) == 'integer') {
		$text = 'int(' . strval($text) .')';
	}
	if (gettype($text) == 'boolean') {
		$text = 'bool(' . intval($text) .')';
	}

	$file = '';
	$line = '';

	$caller = debug_backtrace();
	if (!empty($caller)) {
		$file = (isset($caller[0]['file'])) ? basename($caller[0]['file']) : '';
		$line = (isset($caller[0]['line'])) ? $caller[0]['line'] : '';
	}

	echo '<br /><span style="color:red;">' . $file . ': '. $line. ': ' . $text . "</span><br />";
}


function pprint($data)
{
	if (!DEBUG) {return;}

	if (gettype($data) == 'boolean') {
		$data = ($data === true) ? '<span style="color:green">true</span>' : '<span style="color:red">false</span>';
	}
	$text = '';
	$caller = debug_backtrace();
	if (!empty($caller)) {
		$file = (isset($caller[0]['file'])) ? basename($caller[0]['file']) : '';
		$line = (isset($caller[0]['line'])) ? $caller[0]['line'] : '';
		$func = (isset($caller[1]) ) ? $caller[1]['function'].'()' : '';
		$text = $file . ': ' . $line . ' - ' . $func . ': "' . $text . '"';
		$text = $text . PHP_EOL;
	}

	echo '<pre style="background:#EDEAEA;padding:5px 0 10px 40px">';
	echo '<p style="color:#b4df5b;weight:bold;font-size:1.2em;padding:5px;background:gray">'.$text.'</p>';
	print_r($data);
	echo "</pre>";
}


function sqlPrint($sql, $keylist)
{

	if (!DEBUG) {return;}
	$text = '';
	$caller = debug_backtrace();
	if (!empty($caller)) {
		$file = (isset($caller[0]['file'])) ? basename($caller[0]['file']) : '';
		$line = (isset($caller[0]['line'])) ? $caller[0]['line'] : '';
		$func = (isset($caller[1]) ) ? $caller[1]['function'].'()' : '';
		$text = $file . ': ' . $line . ' - ' . $func . ': "' . $text . '"';
		$text = $text . PHP_EOL;
	}


	$keys = array_keys($keylist);
	# sort keys from longest to shortest
	usort($keys, function($a, $b) {
		if (strlen($a) === strlen($b)) {
			return 0;
		}
		return ( strlen($a) > strlen($b) ) ? -1 : 1;
	}); # return -1 moves $b down, 1 moves $b up, 0 keeps $b the same
	foreach($keys as $key) {
		if ($keylist[$key] == null ) {$keylist[$key] = 'null';}
		$sql = str_replace( ":".$key, "'".$keylist[$key]."'",  $sql);
		$sql = str_replace( "'null'", "null", $sql);
	}
	$span = '<span style="color:#B74242;weight:bold;">';
	$sql = preg_replace('/SELECT/', $span.'SELECT</span>', $sql);
	$sql = preg_replace('/INSERT/', $span.'INSERT</span>', $sql);
	$sql = preg_replace('/UPDATE/', $span.'UPDATE</span>', $sql);
	$sql = preg_replace('/DELETE/', $span.'DELETE</span>', $sql);
	$sql = preg_replace('/LEFT JOIN/', '<br /> '.$span.'&nbsp;&nbsp;&nbsp;&nbsp;LEFT JOIN</span>', $sql);
	$sql = preg_replace('/FROM/', '<br /> '.$span.'FROM</span>', $sql);
	$sql = preg_replace('/WHERE/', '<br /> '.$span.'WHERE</span>', $sql);
	$sql = preg_replace('/AND/', '<br /> '.$span.'&nbsp;&nbsp;&nbsp;&nbsp;AND</span>', $sql);
	$sql = preg_replace('/ORDER BY/', '<br /> '.$span.'ORDER BY</span>', $sql);

	echo '<p style="color:#ff7b0d;weight:bold;font-size:1.2em;padding:5px;background:gray">SQL - '.$text.'</p>';
	echo $sql;
	echo '<hr>';
}

function sqlLog($sql, $keylist)
{

	$keys = array_keys($keylist);
	# sort keys from longest to shortest
	usort($keys, function($a, $b) {
		if (strlen($a) === strlen($b)) {
			return 0;
		}
		return ( strlen($a) > strlen($b) ) ? -1 : 1;
	}); # return -1 moves $b down, 1 moves $b up, 0 keeps $b the same
	foreach($keys as $key) {
		if ($keylist[$key] == null ) {$keylist[$key] = 'NULL';}
		$sql = str_replace( ":".$key, "'".$keylist[$key]."'",  $sql);
	}
	logThis($sql);
}

function logThis($text, $mode = 'a')
{
	if (DEBUG === False) {return;}

	if ( is_array($text) || is_object($text)) {
		$text = print_r($text, true);
	}
	if (gettype($text) == 'integer') {
		$text = 'int(' . strval($text) .')';
	}
	if (gettype($text) == 'boolean') {
		$text = 'bool(' . intval($text) .')';
	}
	if ($text === 'clear') {
		$text = '';
		$mode = 'w';
	}
	$caller = debug_backtrace();
	$file = basename($caller[0]['file']);
	$line = $caller[0]['line'];
	$func = ( isset($caller[1]) ) ? $caller[1]['function'].'()' : '';

	$f = fopen(DIR_ROOT .'/logfile.txt', $mode);
	$text = REQUEST_ID . ' | '. $file . ': ' . $line . ' - ' . $func . ': "' . $text . '"';
	$text = $text. '¡' . PHP_EOL;
	fwrite($f, $text);
	fclose($f);
}



function error_handler($e, $errString, $errfile, $errlin) {

	logThis($errfile.' - '. $errlin.': '. $errString);
	// header('HTTP/1.1 500 Internal Server Error');
	$result = array();
	$caller = debug_backtrace();
	$text = '';
	// logThis('checking for caller');
	if (!empty($caller)) {
		$file = (isset($caller[0]['file'])) ? basename($caller[0]['file']) : '';
		$line = (isset($caller[0]['line'])) ? $caller[0]['line'] : '';
		$func = (isset($caller[1]) ) ? $caller[1]['function'].'()' : '';
		$text = $file . ': ' . $line . ' - ' . $func . ': "' . $text . '"';
		$text = $text. '¡' . PHP_EOL;
		logThis($text);
	}
	// logThis('here');
	// logThis($text);
	$message = ($e !== null && method_exists($e, 'getMessage')) ?  $e->getMessage() : '';
	logThis($message);
	$result['errormsg'] = $text;
	logThis($result);
	return json_encode($result);
}


function json_array($array)
{
	if ($array) {
		$json = array();
		$array = pg_parse($array);
		$keys  = array_keys($array);

		foreach ($keys as $key) {
			array_push($json, $array[$key]);
		}

		return $json;
	}
	return null;
}

function pg_parse($arraystring, $reset=true)
{
	static $i = 0;
	if ($reset) $i = 0;
	$matches = array();
	$indexer = 1;   // by default sql arrays start at 1
	// handle [0,2]= cases
	if (preg_match('/^\[(?P<index_start>\d+):(?P<index_end>\d+)]=/', substr($arraystring, $i), $matches)) {
		$indexer = (int)$matches['index_start'];
		$i = strpos($arraystring, '{');
	}
	if ($arraystring[$i] != '{') {
		return NULL;
	}
	if (is_array($arraystring)) return $arraystring;
	// handles btyea and blob binary streams
	if (is_resource($arraystring)) return fread($arraystring, 4096);
	$i++;
	$work = array();
	$curr = '';
	$length = strlen($arraystring);
	$count = 0;
	$quoted = false;
	while ($i < $length) {
		// echo "\n [ $i ] ..... $arraystring[$i] .... $curr";
		switch ($arraystring[$i]) {
		case '{':
			$sub = pg_parse($arraystring, false);
			if(!empty($sub)) {
				$work[$indexer++] = $sub;
			}
			break;
		case '}':
			$i++;
			if (strlen($curr) > 0) $work[$indexer++] = $curr;
			return $work;
		case '\\':
			$i++;
			$curr .= $arraystring[$i];
			$i++;
			break;
		case '"':
			$quoted = true;
			$openq = $i;
			do {
				$closeq = strpos($arraystring, '"' , $i + 1);
				$escaped = $closeq > $openq &&
					preg_match('/(\\\\+)$/', substr($arraystring, $openq + 1, $closeq - ($openq + 1)), $matches) &&
					(strlen($matches[1])%2);
				if ($escaped) {
					$i = $closeq;
				} else {
					break;
				}
			} while(true);
			if ($closeq <= $openq) {
				throw new Exception('Unexpected condition');
			}
			$curr .= substr($arraystring, $openq + 1, $closeq - ($openq + 1));
			$i = $closeq + 1;
			break;
		case ',':
			if (strlen($curr) > 0){
				if (!$quoted && (strtoupper($curr) == 'NULL')) $curr = null;
				$work[$indexer++] = $curr;
			}
			$curr = '';
			$quoted = false;
			$i++;
			break;
		default:
			$curr .= $arraystring[$i];
			$i++;
		}
	}
	throw new Exception('Unexpected line end');
}


// function db_timezone($db, $tz)
// {
//  $sql = "SET Time Zone '" . $tz . "'";
//  $db->execute($sql, array());
// }

// function fixForSQL($sql) {
//  $sql = str_replace('\\', '\\\\', $sql);
//  $sql = str_replace('\'', '\\\'', $sql);
//  $sql = str_replace(';', '\;', $sql);
//  return $sql;
// }


// function generateUUID() {
//  $s = '';
//  for ($i=0; $i<32; $i++) {
//      if ($i === 12) {
//          $s .= '4';
//      }
//      elseif ($i === 16) {
//          $s .= dechex(floor(rand(8,11)));
//      }
//      else {
//          $s .= dechex(floor(rand(0,15)));
//      }
//      if ($i===7||$i===11||$i===15||$i===19) {
//          $s .= '-';
//      }
//  }
//  return strtolower($s);
// }




// function isUUID($uuid)
// {
//  return (preg_match("/^(\{)?[a-f\d]{8}-[a-f\d]{4}-4[a-f\d]{3}-[89AB][a-f\d]{3}-[a-f\d]{12}(?(1)\})$/i", $uuid) == 1
//          ? true : false);
// }

// function isSQLDate($date)
// {
//  return (preg_match("/\d{1,4}-\d{1,2}-\d{1,2}/i", $date) == 1
//          ? true : false);
// }



// function generateCallTrace()
// {
//     $e = new Exception();
//     $trace = explode("\n", $e->getTraceAsString());
//     // reverse array to make steps line up chronologically
//     $trace = array_reverse($trace);
//     array_shift($trace); // remove {main}
//     array_pop($trace); // remove call to this method
//     $length = count($trace);
//     $result = array();
//     logThis($trace);
//     for ($i = 0; $i < $length; $i++)
//     {
//         $result[] = PHP_EOL . ($i + 1)  . ')' . substr($trace[$i], strpos($trace[$i], ' ')); // replace '#someNum' with '$i)', set the right ordering
//     }

//     return "\t" . implode("\n\t", $result);
// }
