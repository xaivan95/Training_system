<?php

/**
 * Array for build path for current_item.
 */
$paths = array();

/**
 * Callack for xml_set_element_handler.
 *
 * @param $parser string  reference to the XML parser calling the handler
 * @param $name string  contains the name of the element
 *  for which this handler is called
 * @param array $attrs contains an associative array with the element's attributes
 */
function startElement($parser, $name, $attrs)
{
  global $depth;
  global $counter;
  global $items;
  global $paths;

  $counter++;
  $depth++;

  $current_item = new xml_item();
  $current_item->depth = $depth;
  $current_item->name = $name;
  $current_item->data = "";
  $current_item->attrs = $attrs;

  $paths[$depth] = $name;

  $path = "";
  for ($i = 1; $i <= $depth; $i++) {
    if ($path != "") $path .= "/";
    $path .= $paths[$i];
  }

  $current_item->path = $path;
  $items[$path] = $current_item;
}

/**
 * Callack for xml_set_element_handler.
 *
 * @param $parser string reference to the XML parser calling the handler
 * @param string $name contains the name of the element
 *  for which this handler is called
 */
function endElement($parser, $name)
{
  global $depth;

  $depth--;
}

/**
 * Callback for xml_set_character_data_handler
 *
 * @param $parser string reference to the XML parser calling the handler
 * @param $data string characters data
 */
function characterData($parser, $data)
{
  global $depth;
//    global $counter;
  global $items;
  global $paths;


  $path = "";
  for ($i = 1; $i <= $depth; $i++) {
    if ($path != "") $path .= "/";
    $path .= $paths[$i];
  }

  $items[$path]->data .= $data;
}

/**
 * Get data from xml
 *
 * @param $url string xml file
 *
 * @return array|string
 */
function get_xml_data($url)
{
  global $depth;
  global $counter;
  global $items;

  $depth = 0;
  $counter = 0;
  $items = array();

  $xml_parser = xml_parser_create('UTF-8');
  xml_parser_set_option($xml_parser, XML_OPTION_CASE_FOLDING, FALSE);
  xml_set_element_handler($xml_parser, "startElement", "endElement");
  xml_set_character_data_handler($xml_parser, "characterData");

  $XML_file = fopen($url, "r");
  while (($body = fread($XML_file, 5242880))) {
    if (!xml_parse($xml_parser, $body, feof($XML_file))) {
      return xml_error_string(xml_get_error_code($xml_parser));
    }
  }
  fclose($XML_file);
  $data = array();
  foreach ($items as $key => $item) {
    array_key_exists('NUMBER', $item->attrs) && isset($item->attrs) ?
      $data[$key . '_' . $item->attrs['NUMBER']] = $item->data : $data[$key] = $item->data;
  }
  xml_parser_free($xml_parser);
  return $data;
}

/**
 * Convert a xml file or string to an associative array (including the tag attributes):
 * $domObj = new xmlToArrayParser($xml);
 * $elemVal = $domObj->array['element']
 * Or:  $domArr=$domObj->array;  $elemVal = $domArr['element'].
 *
 * @param string $xml file/string.
 * @version  2.0
 */
class xmlToArrayParser
{
  /** The array created by the parser can be assigned to any variable: $anyVarArr = $domObj->array.*/
  public $array = array();
  public $parse_error = FALSE;
  private $parser;
  private $pointer;

  /** Constructor: $domObj = new xmlToArrayParser($xml);
   * @param $xml
   */
  public function __construct($xml)
  {
    $this->pointer =& $this->array;
    $this->parser = xml_parser_create("UTF-8");
    xml_set_object($this->parser, $this);
    xml_parser_set_option($this->parser, XML_OPTION_CASE_FOLDING, FALSE);
    xml_set_element_handler($this->parser, "tag_open", "tag_close");
    xml_set_character_data_handler($this->parser, "cdata");
    $this->parse_error = !xml_parse($this->parser, ltrim($xml));
  }

  /** Free the parser. */
  public function __destruct()
  {
    xml_parser_free($this->parser);
  }

  /** Get the xml error if an error in the xml file occured during parsing. */
  public function get_xml_error()
  {
    if ($this->parse_error) {
      $errCode = xml_get_error_code($this->parser);
      $thisError = "Error Code [" . $errCode . "] \"<strong style='color:red;'>" . xml_error_string($errCode) . "</strong>\",
                            at char " . xml_get_current_column_number($this->parser) . "
                            on line " . xml_get_current_line_number($this->parser) . "";
    } else $thisError = $this->parse_error;
    return $thisError;
  }

  private function tag_open($parser, $tag, $attributes)
  {
    $this->convert_to_array($tag, 'attrib');
    $idx = $this->convert_to_array($tag, 'cdata');
    if (isset($idx)) {
      $this->pointer[$tag][$idx] = Array('@idx' => $idx, '@parent' => &$this->pointer);
      $this->pointer =& $this->pointer[$tag][$idx];
    } else {
      $this->pointer[$tag] = Array('@parent' => &$this->pointer);
      $this->pointer =& $this->pointer[$tag];
    }
    if (!empty($attributes)) {
      $this->pointer['attrib'] = $attributes;
    }
  }

  /** Adds the current elements content to the current pointer[cdata] array.
   * @param $parser
   * @param $cdata
   */
  private function cdata($parser, $cdata)
  {
    $this->pointer['cdata'] = trim($cdata);
  }

  private function tag_close($parser, $tag)
  {
    $current = &$this->pointer;
    if (isset($this->pointer['@idx'])) {
      unset($current['@idx']);
    }

    $this->pointer = &$this->pointer['@parent'];
    unset($current['@parent']);

    if (isset($current['cdata']) && count($current) == 1) {
      $current = $current['cdata'];
    } else if (empty($current['cdata'])) {
      unset($current['cdata']);
    }
  }

  /** Converts a single element item into array(element[0]) if a second element of the same name is encountered.
   * @param $tag
   * @param $item
   * @return int|null
   */
  private function convert_to_array($tag, $item)
  {
    if (isset($this->pointer[$tag][$item])) {
      $content = $this->pointer[$tag];
      $this->pointer[$tag] = array((0) => $content);
      $idx = 1;
    } else if (isset($this->pointer[$tag])) {
      $idx = count($this->pointer[$tag]);
      if (!isset($this->pointer[$tag][0])) {
        foreach ($this->pointer[$tag] as $key => $value) {
          unset($this->pointer[$tag][$key]);
          $this->pointer[$tag][0][$key] = $value;
        }
      }
    } else $idx = null;
    return $idx;
  }
}