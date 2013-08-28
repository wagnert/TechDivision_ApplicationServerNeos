<?php

/**
 * TechDivision\Neos\Servlets\NeosServlet
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 */

namespace TechDivision\Neos\Servlets;

use TechDivision\ServletContainer\Interfaces\Request;
use TechDivision\ServletContainer\Interfaces\Response;
use TechDivision\ServletContainer\Servlets\PhpServlet;

/**
 * @package     TechDivision\Neos
 * @copyright   Copyright (c) 2013 <info@techdivision.com> - TechDivision GmbH
 * @license     http://opensource.org/licenses/osl-3.0.php
 *              Open Software License (OSL 3.0)
 * @author      Johann Zelger <jz@techdivision.com>
 */
class NeosServlet extends PhpServlet
{

    public function getWebappPath() {
        return $this->getServletConfig()->getApplication()->getWebappPath();
    }

    /**
     * Initialize globals
     *
     * @return void
     */
    public function initGlobals()
    {
        // if the application has not been called over a vhost configuration append application folder name
        if (!$this->getServletConfig()->getApplication()->isVhostOf($this->getRequest()->getServerName())) {
            $this->getRequest()->setServerVar(
                'SCRIPT_FILENAME',
                $this->getRequest()->getServerVar('DOCUMENT_ROOT') .
                DIRECTORY_SEPARATOR . $this->getServletConfig()->getApplication()->getName() .
                DIRECTORY_SEPARATOR . 'Web' . DIRECTORY_SEPARATOR . 'index.php'
            );
            $this->getRequest()->setServerVar(
                'SCRIPT_NAME',
                DIRECTORY_SEPARATOR . $this->getServletConfig()->getApplication()->getName() .
                DIRECTORY_SEPARATOR . 'index.php'
            );
            $this->getRequest()->setServerVar(
                'PHP_SELF',
                DIRECTORY_SEPARATOR . $this->getServletConfig()->getApplication()->getName() .
                DIRECTORY_SEPARATOR . 'index.php'
            );
        } else {
            $this->getRequest()->setServerVar(
                'SCRIPT_FILENAME', $this->getRequest()->getServerVar('DOCUMENT_ROOT') . DIRECTORY_SEPARATOR . 'Web' . DIRECTORY_SEPARATOR . 'index.php'
            );
            $this->getRequest()->setServerVar('SCRIPT_NAME', DIRECTORY_SEPARATOR . 'index.php');
            $this->getRequest()->setServerVar('PHP_SELF', DIRECTORY_SEPARATOR . 'index.php');
        }

        $_SERVER = $this->getRequest()->getServerVars();
        $_SERVER['SERVER_PORT'] = NULL;

        // check post type and set params to globals
        if ($this->getRequest() instanceof PostRequest) {
            $_POST = $this->getRequest()->getParameterMap();
            // check if there are get params send via uri
            parse_str($this->getRequest()->getQueryString(), $_GET);
        } else {
            $_GET = $this->getRequest()->getParameterMap();
        }

        $_REQUEST = $this->getRequest()->getParameterMap();

        foreach (explode('; ', $this->getRequest()->getHeader('Cookie')) as $cookieLine) {

            list($key, $value) = explode('=', $cookieLine);

            if (empty($key) === false) {
                $_COOKIE[$key] = $value;
            }
        }
    }

    /**
     * @param Request $req
     * @param Response $res
     * @throws \Exception
     */
    public function doGet(Request $req, Response $res) {

        // register request and response objects
        $this->setRequest($req);
        $this->setResponse($res);

        // start session
        $this->getRequest()->getSession()->start();

        $this->initGlobals();

        /*
        $rootPath = isset($_SERVER['FLOW_ROOTPATH']) ? $_SERVER['FLOW_ROOTPATH'] : FALSE;

        if ($rootPath === FALSE && isset($_SERVER['REDIRECT_FLOW_ROOTPATH'])) {
            $rootPath = $_SERVER['REDIRECT_FLOW_ROOTPATH'];
        }

        if ($rootPath === FALSE) {
            $rootPath = dirname(__FILE__) . '/../';
        } elseif (substr($rootPath, -1) !== '/') {
            $rootPath .= '/';
        }
        */

        $_SERVER['DOCUMENT_ROOT'] = $this->getWebappPath() . DIRECTORY_SEPARATOR . 'Web' . DIRECTORY_SEPARATOR;
        $_SERVER['FLOW_REWRITEURLS'] = 1;
        $_SERVER['FLOW_ROOTPATH'] = $this->getWebappPath();
        $_SERVER['FLOW_SAPITYPE'] = 'Web';

        $_SERVER['REDIRECT_FLOW_CONTEXT'] = 'Development';
        $_SERVER['REDIRECT_FLOW_SAPITYPE'] = 'Web';
        $_SERVER['REDIRECT_FLOW_ROOTPATH'] = '/opt/appserver/webapps/neos/';
        $_SERVER['REDIRECT_FLOW_REWRITEURLS'] = '1';
        $_SERVER['REDIRECT_STATUS'] = '200';

        // error_log(var_export($_SERVER, true));
        // error_log(var_export($_COOKIE, true));

        require($this->getWebappPath() . '/Packages/Framework/TYPO3.Flow/Classes/TYPO3/Flow/Core/Bootstrap.php');

        $context = getenv('FLOW_CONTEXT') ?: (getenv('REDIRECT_FLOW_CONTEXT') ?: 'Development');
        $bootstrap = new \TYPO3\Flow\Core\Bootstrap($context);


        ob_start();
        $bootstrap->run();
        $res->setContent(ob_get_clean());
    }
}