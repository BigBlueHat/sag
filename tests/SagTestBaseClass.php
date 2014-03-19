<?php
/*
   Licensed under the Apache License, Version 2.0 (the "License");
   you may not use this file except in compliance with the License.
   You may obtain a copy of the License at

       http://www.apache.org/licenses/LICENSE-2.0

   Unless required by applicable law or agreed to in writing, software
   distributed under the License is distributed on an "AS IS" BASIS,
   WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
   See the License for the specific language governing permissions and
   limitations under the License.
*/

// See the README in tests/ for information on running and writing these tests.

require_once('Sag.php');
require_once('SagFileCache.php');
require_once('SagMemoryCache.php');

class SagTestBaseClass extends PHPUnit_Framework_TestCase {

  protected $couchIP;
  protected $couchDBName;
  protected $couchAdminName;
  protected $couchAdminPass;
  protected $couchHTTPAdapter;
  protected $couchSSL;

  protected $couch;
  protected $session_couch;
  protected $noCacheCouch;

  public function setUp()
  {
    $this->couchIP = ($GLOBALS['host']) ? $GLOBALS['host'] : '127.0.0.1';
    $this->couchPort = ($GLOBALS['port']) ? $GLOBALS['port'] : '5984';
    $this->couchDBName = ($GLOBALS['db']) ? $GLOBALS['db'] : 'sag_tests';
    $this->couchAdminName = ($GLOBALS['adminName']) ? $GLOBALS['adminName'] : 'admin';
    $this->couchAdminPass = ($GLOBALS['adminPass']) ? $GLOBALS['adminPass'] : 'passwd';
    $this->couchHTTPAdapter = $GLOBALS['httpAdapter'];
    $this->couchSSL = (isset($GLOBALS['ssl'])) ? $GLOBALS['ssl'] : false;

    $this->couch = new Sag($this->couchIP, $this->couchPort);
    $this->couch->setHTTPAdapter($this->couchHTTPAdapter);
    $this->couch->useSSL($this->couchSSL);
    $this->couch->login($this->couchAdminName, $this->couchAdminPass);
    $this->couch->setDatabase($this->couchDBName);
    $this->couch->setRWTimeout(5);

    $this->session_couch = new Sag($this->couchIP, $this->couchPort);
    $this->session_couch->setHTTPAdapter($this->couchHTTPAdapter);
    $this->session_couch->useSSL($this->couchSSL);
    $this->session_couch->setDatabase($this->couchDBName);
    $this->session_couch->login($this->couchAdminName, $this->couchAdminPass);

    $this->noCacheCouch = new Sag($this->couchIP, $this->couchPort);
    $this->noCacheCouch->setHTTPAdapter($this->couchHTTPAdapter);
    $this->noCacheCouch->useSSL($this->couchSSL);
    $this->noCacheCouch->setDatabase($this->couchDBName);
    $this->noCacheCouch->login($this->couchAdminName, $this->couchAdminPass);
  }
}
