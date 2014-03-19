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

require_once('SagTestBaseClass.php');

class SagTest extends SagTestBaseClass {

  public function test_replication()
  {
    $newDB = ($GLOBALS['dbReplication']) ? $GLOBALS['dbReplication'] : 'sag_tests_replication';

    // Basic
    $this->assertFalse(in_array($newDB, $this->couch->getAllDatabases()->body));
    $this->assertTrue($this->couch->createDatabase($newDB)->body->ok);
    $this->assertTrue($this->couch->replicate($this->couchDBName, $newDB)->body->ok);
    $this->assertTrue($this->couch->deleteDatabase($newDB)->body->ok);

    // create_target
    $this->assertFalse(in_array($newDB, $this->couch->getAllDatabases()->body));
    $this->assertTrue($this->couch->replicate($this->couchDBName, $newDB, false, true)->body->ok);
    $this->assertTrue(in_array($newDB, $this->couch->getAllDatabases()->body));
    $this->assertTrue($this->couch->deleteDatabase($newDB)->body->ok);

    // filter
    try
    {
      //Provide a valid filter function that does not exist.
      $this->assertTrue($this->couch->replicate($this->couchDBName, $newDB, false, true, "test")->body->ok);
      $this->assertFalse(true); //should not get this far
    }
    catch(SagCouchException $e)
    {
      $this->assertTrue(true); //we want this to happen
    }

    try
    {
      $this->assertFalse($this->couch->replicate($this->couchDBName, $newDB, false, false, 123)->body->ok);
      $this->assertFalse(true); //should not get this far
    }
    catch(SagException $e)
    {
      $this->assertTrue(true); //we want this to happen
    }

    // filter query params
    try
    {
      //Provide a valid filter function that does not exist.
      $this->assertTrue($this->couch->replicate($this->couchDBName, $newDB, false, true, "test", 123)->body->ok);
      $this->assertFalse(true); //should not get this far
    }
    catch(SagException $e)
    {
      $this->assertTrue(true); //we want this to happen
    }
  }
}
