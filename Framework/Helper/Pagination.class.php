<?php

/**
* Pagination class
*/

class Pagination
{
	/*-----------------------------------------------------------------------------------------------------------*/
	/**
	* The page in the URL
	*/
	private $Page;
	/*-----------------------------------------------------------------------------------------------------------*/
	/**
	* Total number of records
	*/
	private $RecordCount;
	/*-----------------------------------------------------------------------------------------------------------*/
	/**
	* Result per page
	*/
	private $ResultPerPage;
	/*-----------------------------------------------------------------------------------------------------------*/
	/**
	* Maximum page index
	*/
	private $MaxPageIndex;
	/*-----------------------------------------------------------------------------------------------------------*/
	/**
	* Current page index, served with $_GET["n"]
	*/
	private $CurrentPageIndex;
	/*-----------------------------------------------------------------------------------------------------------*/
	/**
	* Previous page index
	*/
	private $PrevPageIndex;
	/*-----------------------------------------------------------------------------------------------------------*/
	/**
	* Next page index
	*/
	private $NextPageIndex;
	/*-----------------------------------------------------------------------------------------------------------*/
	/**
	* Limit offset of database query
	*/
	private $LimitOffset;
	/*-----------------------------------------------------------------------------------------------------------*/
	/**
	* Limit of database query
	*/
	private $Limit;
	/*-----------------------------------------------------------------------------------------------------------*/
	
	
	public function __construct($recordCount, $resultPerPage, $page = NULL){
		$this->RecordCount = $recordCount;
		$this->ResultPerPage = $resultPerPage;
		if(!empty($page)){
			$this->Page = $page;
		}
		$this->Init();
	}
	/*-----------------------------------------------------------------------------------------------------------*/
	/**
	* Calculate and initialize properties
	*/
	private function Init(){
		$maxPageIndex = ($this->RecordCount / $this->ResultPerPage);
		$this->MaxPageIndex = ($maxPageIndex < 1)? 0 : ceil($maxPageIndex - 1);
		if(isset($_GET["n"])){
			$index = filter_var($_GET["n"], FILTER_SANITIZE_NUMBER_INT);
			if(empty($index) || $index === 0 || $index < 0 || ($index > 0 && $index < 1)){
				$currentPageIndex = 0;
			}
			elseif($index > $maxPageIndex){
				$currentPageIndex = $maxPageIndex;
			}
			else{
				$currentPageIndex = $index;
			}
		}
		else{
			$currentPageIndex = 0;
		}
		$this->CurrentPageIndex = $currentPageIndex;
		$this->PrevPageIndex = ($currentPageIndex - 1);
		$this->NextPageIndex = ($currentPageIndex + 1);
		$this->LimitOffset = $currentPageIndex * $this->ResultPerPage;
		$this->Limit = "{$this->LimitOffset}, {$this->ResultPerPage}";
	}
	/*-----------------------------------------------------------------------------------------------------------*/
	/**
	* Get record count
	*/
	public function GetRecordCount(){
		return $this->RecordCount;
	}
	/*-----------------------------------------------------------------------------------------------------------*/
	/**
	* Get result_count per page
	*/
	public function GetResultPerPage(){
		return $this->ResultPerPage;
	}
	/*-----------------------------------------------------------------------------------------------------------*/
	/**
	* Get maximum page index
	*/
	public function GetMaxPageIndex(){
		return $this->MaxPageIndex;
	}
	/*-----------------------------------------------------------------------------------------------------------*/
	/**
	* Get current page index
	*/
	public function GetCurrentPageIndex(){
		return $this->CurrentPageIndex;
	}
	/*-----------------------------------------------------------------------------------------------------------*/
	/**
	* Get previous page index
	*/
	public function GetPrevPageIndex(){
		return $this->PrevPageIndex;
	}
	/*-----------------------------------------------------------------------------------------------------------*/
	/**
	* Get next page index
	*/
	public function GetNextPageIndex(){
		return $this->NextPageIndex;
	}
	/*-----------------------------------------------------------------------------------------------------------*/
	/**
	* Get limit offset
	*/
	public function GetLimitOffset(){
		return $this->LimitOffset;
	}
	/*-----------------------------------------------------------------------------------------------------------*/
	/**
	* Get limit
	*/
	public function GetLimit(){
		return $this->Limit;
	}
	/*-----------------------------------------------------------------------------------------------------------*/
	/**
	* Create pagination HTML
	*/
	public function Markup(){
		$markup = "";
		if($this->getRecordCount() > $this->getResultPerPage()){
			if($this->getCurrentPageIndex() == 0){
				$markup .= HTML::Hyperlink("Next", "/{$this->Page}?n=".$this->GetNextPageIndex(), array("class"=>"projectFeedNavBtn"));
			}
			elseif($this->GetCurrentPageIndex() > 0 && $this->GetCurrentPageIndex() < $this->GetMaxPageIndex()){
				$markup .= HTML::Hyperlink("Previous", "/{$this->Page}?n=".$this->GetPrevPageIndex(), array("class"=>"projectFeedNavBtn"));
				$markup .= HTML::Hyperlink("Next", "/{$this->Page}?n=".$this->GetNextPageIndex(), array("class"=>"projectFeedNavBtn"));
			}
			elseif($this->GetCurrentPageIndex() == $this->GetMaxPageIndex()){
				$markup .= HTML::Hyperlink("Previous", "/{$this->Page}?n=".$this->GetPrevPageIndex(), array("class"=>"projectFeedNavBtn"));
			}
		}
		return $markup;
	}
}
?>