# Setting up Validator

## Things you'll need

	* PHP
	* PHPUnit
	* Frontend Test Suite (git://github.com/NeilCrosby/frontend-test-suite.git)
	* Apache ANT
	
## How to run

To run Validator, edit the build.xml file, replacing the SITE_URL filter token with your desired endpoint.  Then run 'ant validate' from the terminal and the application will start at the page, discover all links collecting those.  It will then run against each of those links reporting pages which return 500 and 404 HTTP status code, and will run HTML validation against those which return successfully.

Ant will then fail if any of these assertions fail.

## Integration

You can integrate this as part of a website build process, or as part of a monitoring solution.  This may be particularly useful for websites with a content management part to protect against those pesky users who might copy and paste from Word.

# Further work

	* CSS Validation
	* Javascript Lint
	* Better reporting
