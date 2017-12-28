<?php
/**
* HTML class to be used for rendering HTML snippets in necessary situations.
*/

class HTML
{
	private static $MonthNames = array("Jan"=>"January", "Feb"=>"February", "Mar"=>"March", "Apr"=>"April", "May"=>"May", "Jun"=>"June", "Jul"=>"July", "Aug"=>"August", "Sep"=>"September", "Oct"=>"October", "Nov"=>"November", "Dec"=>"December");
	private static $WeekDays = array("Sun"=>"Sunday", "Mon"=>"Monday", "Tue"=>"Tuesday", "Wed"=>"Wednesday", "Thu"=>"Thursday", "Fri"=>"Friday", "Sat"=>"Saturday");
	
	private function __construct(){}
	private function __clone(){}
	
	/**
	********************************
	* Special elements
	********************************
	*/
	
	/**
	* Create a hyperlink
	* @var string $content the content of element
	* @var string $href the link url
	* @var array $attribs the Attributes and their values
	* @return the element
	*/
	public static function Hyperlink($content, $href, $attribs = NULL){
		$hyperlink = "<a href=\"{$href}\"" . self::Attributes($attribs) . ">{$content}</a>";
		return $hyperlink;
	}
	/*--------------------------------------------------------------------------------------------------------------*/	
	/**
	* Insert an image
	* @var string $src the image url
	* @var array $attribs the Attributes and their values
	* @return the element
	*/
	public static function Image($src, $attribs = NULL){
		$image = "<img src=\"{$src}\"" . self::Attributes($attribs) . ">";
		return $image;
	}
	/*--------------------------------------------------------------------------------------------------------------*/	
	/**
	* Insert a dropdown
	* @var array $options the dropdown options list
	* @var array $attribs the Attributes and their values
	* @return the element
	*/
	public static function Dropdown($options = array(), $attribs = NULL){
		$dropdown = "<select" . self::Attributes($attribs) . ">";
		foreach ($options as $text=>$value){
			$dropdown .= "<option value=\"{$value}\">{$text}</option>";
		}
		$dropdown .= "</select>";
		return $dropdown;
	}
	/*--------------------------------------------------------------------------------------------------------------*/	
	/**
	* Insert a dropdown option
	* @var string $text the option text
	* @var string $value the option value
	* @var bool $selected the option default selection flag
	* @return the element
	*/
	public static function DropdownOption($text, $value = NULL, $selected = FALSE, $attribs = NULL){
		$selectAttrib = ($selected === TRUE)? " selected" : "";
		if(!empty($value)){
			$option = "<option value=\"{$value}\"{$selectAttrib}" . self::Attributes($attribs) . ">{$text}</option>";
		}
		else{
			$option = "<option value=\"{$text}\"{$selectAttrib}" . self::Attributes($attribs) . ">{$text}</option>";
		}
		return $option;
	}
	/*--------------------------------------------------------------------------------------------------------------*/	
	/**
	* Insert an unordered list
	* @var array $items the list items
	* @var array $attribs the Attributes and their values
	* @return the element
	*/
	public static function Ulist($items = array(), $attribs = NULL){
		$list = "<ul" . self::Attributes($attribs) . ">";
		foreach ($items as $item){
			$list .= "<li>{$item}</li>";
		}
		$list .= "</ul>";
		return $list;
	}
	/*--------------------------------------------------------------------------------------------------------------*/	
	/**
	* Insert an ordered list
	* @var array $items the list items
	* @var array $attribs the Attributes and their values
	* @return the element
	*/
	public static function Olist($items = array(), $attribs = NULL){
		$list = "<ol" . self::Attributes($attribs) . ">";
		foreach ($items as $item){
			$list .= "<li>{$item}</li>";
		}
		$list .= "</ol>";
		return $list;
	}
	/*--------------------------------------------------------------------------------------------------------------*/	
	/**
	* Insert a list item
	*/
	public static function ListItem($text, $attribs = NULL){
		$item = "<li" . self::Attributes($attribs) . ">{$text}</li>";
	}
	/*-----------------------------------------------------------------------------------------------------------*/
	/**
	* Insert a link-list item
	*/
	public static function LinkListItem($text, $attribs = NULL){
		$item = "<li" . self::Attributes($attribs) . ">{$text}</li>";
	}
	/*--------------------------------------------------------------------------------------------------------------*/	
	/**
	* Create a hidden input element
	* @var mixed $value the value of the input
	* @var array $attribs the Attributes and their values
	* @return the element
	*/
	public static function HiddenField($value, $attribs = NULL){
		$hidden = "<input type=\"hidden\" value=\"{$value}\"" . self::Attributes($attribs) . ">";
		return $hidden;
	}
	/*--------------------------------------------------------------------------------------------------------------*/	
	/**
	* Creates markup for validation report
	* @return string the report
	*/
	public static function ValidationSummary(){
		$summary = "";
		foreach (Validation::ValidationReport as $report){
			$summary .= "<p class=\"errorMsg\">{$report}</p>";
		}
		return $summary;
	}
	/*--------------------------------------------------------------------------------------------------------------*/
	
	/****************************
	Some useful markups
	****************************/
	
	/**
	* Create dropdown options of days of a month
	*/
	public static function Days(){
		$options = "";
		for ($i = 1; $i <= 31; $i++){
			if($i < 10){
				$options .= "<option value=\"0{$i}\">0{$i}</option>";
			}
			else{
				$options .= "<option value=\"{$i}\">{$i}</option>";
			}
		}
		return $options;
	}
	/*--------------------------------------------------------------------------------------------------------------*/
	/**
	* Create dropdown options of days of week
	*/
	public static function WeekDays(){
		$options = "";
		foreach (self::$WeekDays as $key=>$value){
			$options .= "<option value=\"{$key}\">{$value}</option>";
		}
		return $options;
	}
	/*--------------------------------------------------------------------------------------------------------------*/
	/**
	* Create dropdown options of month numbers
	*/
	public static function MonthNames(){
		$options = "";
		foreach (self::$MonthNames as $key=>$value){
			$options .= "<option value=\"{$key}\">{$value}</option>";
		}
		return $options;
	}
	/*--------------------------------------------------------------------------------------------------------------*/
	/**
	* Create dropdown options of years
	*/
	public static function Years($start = NULL){
		$options = "";
		if(empty($start)){
			for ($i = 1950; $i <= date(Y); $i++){
				$options .= "<option value=\"{$i}\">{$i}</option>";
			}
		}
		else{
			for ($i = $start; $i <= date(Y); $i++){
				$options .= "<option value=\"{$i}\">{$i}</option>";
			}
		}
		return $options;
	}
	/*--------------------------------------------------------------------------------------------------------------*/
	/**
	* Write Attributes of an element
	* @var array $attribs the Attributes
	* @return string the Attributes
	*/
	private static function Attributes($attribs = NULL){
		$attributes = "";
		if (!empty($attribs)){
			foreach ($attribs as $attrib=>$value){
				$attributes .= " " . $attrib . "=" . "\"{$value}\" ";
			}
			$attributes = rtrim($attributes);
		}
		return $attributes;
	}
}
?>