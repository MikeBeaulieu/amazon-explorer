<?php


function getListingResults ($total_listings) {
  switch ($total_listings) {
    case ($total_listings < 20):
	return "red";
	break;
    case ($total_listings > 499):
	return "red";
	break;
    default: 
	return "green";
    }
}

function getAvgRankResult ($avg_rank) {
  switch ($avg_rank) {
  case ($avg_rank <= 100000):
    return "green";
    break;
  case ($avg_rank > 100000 && $avg_rank  <= 250000):
    return "orange";
    break;
  default:
    return "red"; 
  }
}
