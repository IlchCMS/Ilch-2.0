<?php
/**
 * @author Meyer Dominik
 * @copyright Ilch CMS 2.0
 * @package ilch
 */

defined('ACCESS') or die('no direct access');

/**
 * Improves "var_dump" function with pre - tags.
 */
function dumpVar()
{
	echo '<pre>';

	foreach(func_get_args() as $arg)
	{
		var_dump($arg);
	}

	echo '</pre>';
}

/**
 * Gets contents of any webside by an URI.
 *
 * @param string $uri
 */
function file_get_contents_by_uri($uri)
{
	$uriElem = parse_url($uri);
	$fp = fsockopen($uriElem['host'], 80, $errno, $errstr, 10);

	$request = "GET ".$uriElem['path'].(isset($uriElem['query']) ? "?".$uriElem['query'] : "")." HTTP/1.1\r\n";
	$request .= "Host: ".$uriElem['host']."\r\n";
	$request .= "Connection: Close\r\n\r\n";

	fwrite($fp, $request);
	$response = "";

	while(!feof($fp))
	{
		$response .= fgets($fp, 128);
	}

	fclose($fp);
	$responseSplit = explode("\r\n\r\n", $response, 2);

	return $responseSplit[1];
}