<?php

namespace WebAwardsBundle\Tests\Util;
use WebAwardsBundle\Util\WebAwards;


class WebAwardsTest extends \PHPUnit_Framework_TestCase {

    public function testSlugify() {

        if (function_exists("iconv")) {
                                //valeur attendue           Valeur à tester
            $this->assertEquals(false, WebAwards::checkVote('valeur'));
            $this->assertEquals(false, WebAwards::checkVote('8dfff'));
            $this->assertEquals('empty', WebAwards::checkVote(''));
            $this->assertEquals('empty', WebAwards::checkVote(' '));
            $this->assertEquals('space', WebAwards::checkVote('12 34'));
            $this->assertEquals('négatif', WebAwards::checkVote(-1));
            $this->assertEquals('big', WebAwards::checkVote(11));
            $this->assertEquals(true, WebAwards::checkVote(8));
            $this->assertEquals(true, WebAwards::checkVote(8.5));
            $this->assertEquals(true, WebAwards::checkVote(8.55555221100));
        
        }

    }
}