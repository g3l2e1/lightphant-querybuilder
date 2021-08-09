<?php

require_once('LPQueryBuilderTest.php');

$test = new LPQueryBuilderTest();
print $test->selectAll(); print '<br><br>';
print $test->insert(); print '<br><br>';
print $test->update(); print '<br><br>';
print $test->delete(); print '<br>';