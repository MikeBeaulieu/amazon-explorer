<?php

require ('./libs/application.php');

// useful for getting all XML data if wanted
const FULL = 0;

//if (isset($_POST['submit']))   {
require('./libs/amazon-rank.php');
require('./vendor/aws_signed_request.php');
require('./vendor/amazon_api_class.php');

$region = $_POST['region'];

//$amazon = new AmazonProductAPI($public, $private, $site, $affiliate_id);
$amazon = new AmazonProductAPI();
// get product info for a category - top 10
//$category = 'Books';
$category = $_POST['category'];
// limit to 25 chars per amazon
$keyword_limit = 25;
$keyword = $_POST['keyword'];

if ($category == 'Books') {
    $keyword = $_POST['keyword'] . ' -kindle';
}

$single = array(
		'Operation' => 'ItemSearch',
		'SearchIndex' => $category,
// 'BrowseNode' => '283155',
		'Keywords' => $keyword,
		'MinimumPrice' => '0.01',
		'ResponseGroup' => 'Large,OfferFull'
		);

try
{
  $result = $amazon->queryAmazon($region,$single);
}
catch(Exception $e)
{
  echo $e->getMessage();
  echo "<p />";
  die();
}

// useful for getting all XML data if wanted
//const FULL = 0;

if (FULL==1){
  echo "<pre>Result";
  print_r($result);
  echo "</pre>";
//  die();
}

$rankcount = 0;
$ranktotal = 0;
$pricecount = 0;
$totalprice = 0;
$pagecount = 0;
$totalpages = 0;
$maxpagecnt = 0;
$minpagecnt = 10000000;
$pages = "";

$resultsColor = getListingResults($result->Items->TotalResults);
$totalResults = $result->Items->TotalResults;

$products = $result->Items->Item;

setlocale(LC_MONETARY, 'en_US.UTF-8');

//$xml = xml2array($result->Items->Item);
//$xml = (array)$result->Items->Item;
//$xml = json_decode(json_encode((array) simplexml_load_string($result->Items->Item)), 1);
//print_r($xml);

$cnt = 0;
$test = array();
foreach($products as $si){
    // lets get them all!
    $rank = $si->SalesRank;
    $test[$cnt]['salesrank'] = $si->SalesRank;
    $test[$cnt]['node'] = $si->NodeID;
    $test[$cnt]['reviews'] = $si->CustomerReviews->IFrameURL;
    $test[$cnt]['detail'] = $si->DetailPageURL;
    $test[$cnt]['image'] = $si->SmallImage->URL;
    $test[$cnt]['large_image'] = $si->LargeImage->URL;

//    $reviewURL = $si->CustomerReviews->IFrameURL;
//    $smallImage = $si->SmallImage;

//    $title =  $si->ItemAttributes->Title;
    $test[$cnt]['title'] = $si->ItemAttributes->Title;
    $test[$cnt]['pages'] = $si->ItemAttributes->NumberOfPages;
    $pages = $si->ItemAttributes->NumberOfPages;

    $test[$cnt]['adult_p'] = $si->ItemAttributes->IsAdultProduct;
    $test[$cnt]['ISBN'] = $si->ItemAttributes->ISBN;
//    $test[$cnt]['node'] = $si->ItemAttributes->Department;

// check into keywords
    $price = $si->ItemAttributes->ListPrice->Amount;
    $test[$cnt]['amount'] = number_format(trim($si->ItemAttributes->ListPrice->Amount)/100,2,',',',');

    $test[$cnt]['author'] = $si->ItemAttributes->Author;
  
//    $test[$cnt]['sales30'] = round((100000/$rank)*30);
    if ($rank > 0) {
        $test[$cnt]['sales30'] = round((100000/$rank)*30);
    } else {
      $test[$cnt]['sales30'] = 0;
    } 
    
    if ($pages != "") {
      $totalpages += $si->ItemAttributes->NumberOfPages;
      $pagecount++;
    }

    if (intval($pages) < intval($minpagecnt) ) {
        $minpagecnt = $pages;
    }

    if (intval($pages) > intval($maxpagecnt) ) {
        $maxpagecnt = $si->ItemAttributes->NumberOfPages ;
    }

    if ($rank != "") {
      $ranktotal += $rank;
      $rankcount++;
    }
    if ($price > 0) {
      $totalprice += $price/100;
      $pricecount++;
      //echo $pricecount . " " . $totalprice . " <br />";
    }
    $cnt++;
}

//print_r($xml);
$avg_rank = round($ranktotal/$rankcount);
//$avg_price = $totalprice/$pricecount;
$avg_price = round($totalprice/$pricecount);
$rank_help = getAvgRankResult ($avg_rank);
$avg_sales = round((100000/$avg_rank)*30);
$avg_pages = round($totalpages/$pagecount);
//$maxpagecnt = "broken sorry";
//$minpagecnt = "yeah broken too!";

$template = $twig->loadTemplate('data.html');
echo $template->display(array('totalResults' => $totalResults, 
   'resultsColor' => $resultsColor,
   'nicheName' => $keyword,
   'avgRank' => $avg_rank,
   'rankColor' => $rank_help,
   'avgPrice' => $avg_price,
   'test' => $test,
   'region' => $region,
   'avgPages' => $avg_pages,
   'minPages' => $minpagecnt,
   'maxPages' => $maxpagecnt,
   'avgSales' => $avg_sales));
// ($template);
