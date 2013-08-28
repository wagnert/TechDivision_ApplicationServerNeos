/appserver/TechDivision_ApplicationServerNeos/instance-src/Packages/Framework/TYPO3.Flow/Classes/TYPO3/Flow/Http/RequestHandler.php:92

    /**
     * This request handler can handle any web request.
     *
     * @return boolean If the request is a web request, TRUE otherwise FALSE
     * @api
     */
    public function canHandleRequest() {
        return (FLOW_SAPITYPE !== 'CLI');
    }

/appserver/TechDivision_ApplicationServerNeos/instance-src/Packages/Framework/TYPO3.Flow/Classes/TYPO3/Flow/Cli/CommandRequestHandler.php:66

	/**
	 * This request handler can handle CLI requests.
	 *
	 * @return boolean If the request is a CLI request, TRUE otherwise FALSE
	 */
	public function canHandleRequest() {
		return (FLOW_SAPITYPE === 'CLI');
	}

/appserver/TechDivision_ApplicationServerNeos/instance-src/Packages/Framework/TYPO3.Flow/Classes/TYPO3/Flow/Core/Bootstrap.php:461

    if (array_key_exists('FLOW_SAPITYPE', $_SERVER) === false) {
        define('FLOW_SAPITYPE', (PHP_SAPI === 'cli' ? 'CLI' : 'Web'));
    } else {
        define('FLOW_SAPITYPE', $_SERVER['FLOW_SAPITYPE']);
    }

/appserver/TechDivision_ApplicationServerNeos/instance-src/Packages/Application/TYPO3.Setup/Classes/TYPO3/Setup/Core/RequestHandler.php:37

	/**
	 * This request handler can handle any web request.
	 *
	 * @return boolean If the request is a web request, TRUE otherwise FALSE
	 */
	public function canHandleRequest() {
		return (FLOW_SAPITYPE !== 'CLI' && ((strlen($_SERVER['REQUEST_URI']) === 6 && $_SERVER['REQUEST_URI'] === '/setup') || in_array(substr($_SERVER['REQUEST_URI'], 0, 7), array('/setup/', '/setup?'))));
	}

/appserver/TechDivision_ApplicationServerNeos/instance-src/Packages/Application/TYPO3.Setup/Classes/TYPO3/Setup/Core/BasicRequirements.php:116

    /*
    if (version_compare(PHP_VERSION, '6.0.0', '<') && !extension_loaded('mbstring')) {
        return new Error('Flow requires the PHP extension "mbstring" to be available for PHP versions below 6.0.0', 1207148809);
    }
    */

/appserver/TechDivision_ApplicationServerNeos/instance-src/Packages/Framework/TYPO3.Flow/Classes/TYPO3/Flow/Core/Bootstrap.php:539

    /*
    if (version_compare(PHP_VERSION, '6.0.0', '<') && !extension_loaded('mbstring')) {
        echo('Flow requires the PHP extension "mbstring" for PHP versions below 6.0.0 (Error #1207148809)' . PHP_EOL);
        exit(1);
    }
    */

/appserver/TechDivision_ApplicationServerNeos/instance-src/Packages/Framework/TYPO3.Flow/Classes/TYPO3/Flow/Error/AbstractExceptionHandler.php:76

    switch (FLOW_SAPITYPE) {
        case 'CLI' :
            $this->echoExceptionCli($exception);
            break;
        default :
            $this->echoExceptionWeb($exception);
    }

/appserver/TechDivision_ApplicationServerNeos/instance-src/Packages/Framework/TYPO3.Flow/Classes/TYPO3/Flow/Cli/SlaveRequestHandler.php:46

	/**
	 * This request handler can handle CLI requests.
	 *
	 * @return boolean If the request is a CLI request, TRUE otherwise FALSE
	 */
	public function canHandleRequest() {
		return (FLOW_SAPITYPE === 'CLI' && isset($_SERVER['argv'][1]) && $_SERVER['argv'][1] === '--start-slave');
	}

/appserver/TechDivision_ApplicationServerNeos/instance-src/Packages/Framework/TYPO3.Flow/Classes/TYPO3/Flow/Http/Response.php:558

    /*
    if (headers_sent() === TRUE) {
        return;
    }

    foreach ($this->renderHeaders() as $header) {
        header($header);
    }
    */

/appserver/TechDivision_ApplicationServerNeos/instance-src/Packages/Framework/TYPO3.Flow/Classes/TYPO3/Flow/Error/ProductionExceptionHandler.php:38

    // if (!headers_sent()) {
        header(sprintf('HTTP/1.1 %s %s', $statusCode, $statusMessage));
    // }

/appserver/TechDivision_ApplicationServerNeos/instance-src/Packages/Framework/TYPO3.Flow/Classes/TYPO3/Flow/Error/DebugExceptionHandler.php:39

    // if (!headers_sent()) {
        header(sprintf('HTTP/1.1 %s %s', $statusCode, $statusMessage));
    // }