<?php
// It may take a whils to crawl a site ...
set_time_limit(10000);
// Inculde the phpcrawl-mainclass
// require_once 'PHPUnit/Framework.php';

print_r("\n\n***************\n");
print_r("***************\n");
print_r("There will be a series of warnings about PHPCrawler* classes not loading\n");
print_r("You can safely ignore these warnings, as they are loaded, but frontend-test-suite cannot find them.\n");
print_r("***************\n");
print_r("***************\n\n");

require_once('frontend-test-suite/suite/TheCodeTrainEasyFrontendTestSuite.php' );
include("PHPCrawl_070/classes/phpcrawler.class.php");
// Extend the class and override the handlePageData()-method
class MyCrawler extends PHPCrawler 
{
  public $pages = array();
  public $error_pages = array();
  public $notfound_pages = array();
	
  function handlePageData(&$page_data) 
  {
	if (strstr($page_data["header"], "200 OK"))
    	array_push($this->pages, $page_data["url"]);

	if (strstr($page_data["header"], "Internal Server Error"))
		array_push($this->error_pages, $page_data["url"]." - referrer - ".$page_data["referer_url"]);
	
	if (strstr($page_data["header"], "Not Found"))
		array_push($this->notfound_pages, $page_data["url"]." - referrer - ".$page_data["referer_url"]);

    echo str_pad(".", 1); // "Force flush", workaround
    flush();
  }
}

class ExampleTestSuite extends TheCodeTrainEasyFrontendTestSuite {
    public static function suite() {

		$url="@SITE_URL@";
		$crawler = &new MyCrawler();
		// URL to crawl
		$crawler->setURL($url);
		// Only receive content of files with content-type "text/html"
		// (regular expression, preg)
		$crawler->addReceiveContentType("/text\/html/");
		// Ignore links to pictures, dont even request pictures
		// (preg_match)
		$crawler->addNonFollowMatch("/.(jpg|gif|png|css|js|swf)$/ i");
		// Store and send cookie-data like a browser does
		$crawler->setCookieHandling(true);
		// Set the traffic-limit to 10 MB (in bytes,
		// for testing we dont want to "suck" the whole site)
		$crawler->setTrafficLimit(10000 * 1024);
		// Thats enough, now here we go
		$crawler->go();
		// At the end, after the process is finished, we print a short
		// report (see method getReport() for more information)
		$report = $crawler->getReport();
		echo "Summary:\n";
		if ($report["traffic_limit_reached"]==true)
		  echo "Traffic-limit reached \n";
		print_r($report); 
		echo "\npages\n";
		print_r(array_unique($crawler->pages));
		echo "\nerrors\n\n";
		print_r(array_unique($crawler->error_pages));
		echo "\nnot found\n\n";
		print_r(array_unique($crawler->notfound_pages));
      return parent::suite( array(
        'html'  =>  array(
          'validator' => 'http://validator.w3.org/check',
          'tests'     => $crawler->pages
        )
      ) );
    }
  }


?>