<?php
if (!defined('APPPATH'))
    exit('No direct script access allowed');
/**
 * helpers/common_helper.php
 *
 * Some sample helper functions
 */
/**
 * Extract cells from an array into corresponding properties in an object
 * @param <type> $source An associative array state representation of an object
 * @param <type> $target The corresponding object
 * @param <type> $fields The names of the object properties
 */
function fieldExtract($source, $target, $fields)
{
    foreach ($fields as $prop)
    {
	if (isset($source[$prop]))
	{
	    $target->$prop = html_entity_decode($source[$prop]);
	}
    }
}
/**
 * Inject cells into an array from corresponding properties in an object
 * @param <type> $source The original object
 * @param <type> $target An associative array state representation of that object
 * @param <type> $fields The names of the object properties
 */
function fieldInject($source, $target, $fields)
{
    foreach ($fields as $prop)
    {
	if (isset($source->$prop))
	{
	    $target[$prop] = $source->html_entity_decode($prop);
	}
    }
}
/**
 * Does a string end with a specific substring
 * 
 * @param (string) $string  The target of our search
 * @param (string) $ending  The ending substring we are looking for
 * @return (boolean) True if the first string ends with the second
 */
function endsWith($string, $ending)
{
    $piece = strlen($ending); // Get the length of the ending string
    // Look at the end of the original string, just the right amount
    $ourEnd = substr($string, strlen($string) - $piece);
    return $ourEnd == $ending;
}
/**
 * Build an unordered list of linked items, such as used for a menu bar.
 * Assumption: that the URL helper has been loaded.
 * @param type $choices Array of name=>link pairs
 */
function build_menu_bar($choices)
{
    $result = '<ul>';
    foreach ($choices as $name => $link)
	$result .= '<li>' . anchor($link, $name) . '</li>';
    $result .= '</ul>';
    return $result;
}
/* End of file common_helper.php */
/* Location: application/helpers/common_helper.php */