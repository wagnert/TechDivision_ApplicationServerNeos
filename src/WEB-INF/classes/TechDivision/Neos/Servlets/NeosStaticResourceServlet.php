<?php

/**
 * TechDivision\Neos\Servlets\StaticResourceServlet
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
use TechDivision\ServletContainer\Http\PostRequest;

/**
 * @package     TechDivision\Neos
 * @copyright   Copyright (c) 2013 <info@techdivision.com> - TechDivision GmbH
 * @license     http://opensource.org/licenses/osl-3.0.php
 *              Open Software License (OSL 3.0)
 * @author      Johann Zelger <jz@techdivision.com>
 */
class StaticResourceServlet extends \TechDivision\ServletContainer\Servlets\StaticResourceServlet
{

    /**
     * Holds the request object
     *
     * @var Request
     */
    protected $request;

    /**
     * Holds the response object
     *
     * @var Response
     */
    protected $response;

    public function getWebappPath() {
        return $this->getServletConfig()->getApplication()->getWebappPath();
    }

    /**
     * @see \TechDivision\ServletContainer\Servlets\PhpServlet::doGet()
     */
    public function doPost(Request $req, Response $res) {
        $this->doGet($req, $res);
    }

    /**
     * Sets request object
     *
     * @param mixed $request
     */
    public function setRequest($request)
    {
        $this->request = $request;
    }

    /**
     * Returns request object
     *
     * @return mixed
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Sets response object
     *
     * @param mixed $response
     */
    public function setResponse($response)
    {
        $this->response = $response;
    }

    /**
     * Returns response object
     *
     * @return mixed
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Initialize globals
     *
     * @return void
     */
    public function initGlobals()
    {
        
        if (($xRequestedWith = $this->getRequest()->getHeader('X-Requested-With')) != null) {
            $this->getRequest()->setServerVar('HTTP_X_REQUESTED_WITH', $xRequestedWith);
        }
        
        $this->getRequest()->setServerVar(
            'DOCUMENT_ROOT', $this->getRequest()->getServerVar('DOCUMENT_ROOT') . DIRECTORY_SEPARATOR . 'Web'
        );
        
        $this->getRequest()->setServerVar(
            'SCRIPT_FILENAME', $this->getRequest()->getServerVar('DOCUMENT_ROOT') . DIRECTORY_SEPARATOR . 'index.php'
        );
        
        $this->getRequest()->setServerVar(
            'REQUEST_URI', str_replace('/index.php', '', $this->getRequest()->getServerVar('REQUEST_URI'))
        );
        
        $this->getRequest()->setServerVar('SCRIPT_NAME', DIRECTORY_SEPARATOR . 'index.php');
        $this->getRequest()->setServerVar('PHP_SELF', DIRECTORY_SEPARATOR . 'index.php');
        
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
        
        // get request uri for further rewrite processing
        $uri = $req->getUri();
        
        // Perform rewriting of persistent private resources
        // .htaccess RewriteRule ^(_Resources/Persistent/[a-z0-9]+/(.+/)?[a-f0-9]{40})/.+(\..+) $1$3 [L]
        if (preg_match('/^(\/_Resources\/Persistent\/[a-z0-9]+\/(.+\/)?[a-f0-9]{40})\/.+(\..+)/', $uri, $matches)) {
            $req->setUri($matches[1].$matches[3]);
            $req->initServerVars();
        }
        
        // Perform rewriting of persistent resource files
        // .htaccess RewriteRule ^(_Resources/Persistent/.{40})/.+(\..+) $1$2 [L]
        if (preg_match('/^(\/_Resources\/Persistent\/.{40})\/.+(\..+)/', $uri, $matches)) {
            $req->setUri($matches[1].$matches[2]);
            $req->initServerVars();
        }
        
        // register request and response objects
        $this->setRequest($req);
        $this->setResponse($res);
        
        $this->initGlobals();

        parent::doGet($req, $res);
    }
}