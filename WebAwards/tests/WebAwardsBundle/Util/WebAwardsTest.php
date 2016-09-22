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



            $this->assertEquals("empty", WebAwards::checkLastMonthWinner([]));
            $this->assertEquals("invalidArray", WebAwards::checkLastMonthWinner("qsd"));
            $this->assertEquals("invalidArray", WebAwards::checkLastMonthWinner(1233));
            $this->assertEquals("invalid", WebAwards::checkLastMonthWinner(["1"]));
            $this->assertEquals("invalid", WebAwards::checkLastMonthWinner(["1","2","3"]));
            $this->assertEquals("invalidMonthKey", WebAwards::checkLastMonthWinner(["1", "2"]));
            $this->assertEquals("invalidMonthKey", WebAwards::checkLastMonthWinner(["dev"=>"1", "2"]));
            $this->assertEquals("invalidYearKey", WebAwards::checkLastMonthWinner(["month"=>"1", "2"]));
            $this->assertEquals("invalidYearKey", WebAwards::checkLastMonthWinner(["month"=>"1", "dev"=>"2"]));
            $this->assertEquals("invalidData", WebAwards::checkLastMonthWinner(["month" => "dev", "year" => "test"]));
            $this->assertEquals("invalidMonth", WebAwards::checkLastMonthWinner(["month" => "0", "year" => "11"]));
            $this->assertEquals("invalidMonth", WebAwards::checkLastMonthWinner(["month" => "13", "year" => "11"]));
            $this->assertEquals("invalidYear", WebAwards::checkLastMonthWinner(["month" => "12", "year" => "2015"]));
            $this->assertEquals("invalidYear", WebAwards::checkLastMonthWinner(["month" => "12", "year" => "-2015"]));
            $this->assertEquals("janvier", WebAwards::checkLastMonthWinner(["month" => "1", "year" => "2017"]));
            $this->assertEquals("dateOk", WebAwards::checkLastMonthWinner(["month" => "1", "year" => "2016"]));
            $this->assertEquals("dateLastOk", WebAwards::checkLastMonthWinner(["month" => "1", "year" => "2015"]));
            $this->assertEquals(true, WebAwards::checkLastMonthWinner(["month" => "8", "year" => "2016"]));
            $this->assertEquals(true, WebAwards::checkLastMonthWinner(["month" => "12", "year" => "2016"]));
            $this->assertEquals(true, WebAwards::checkLastMonthWinner(["month" => "2", "year" => "2016"]));



        }

    }
}