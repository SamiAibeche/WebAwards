<?php

namespace WebAwardsBundle\Tests\Util;
use WebAwardsBundle\Util\WebAwards;


class WebAwardsTest extends \PHPUnit_Framework_TestCase {

    public function testSlugify() {

        if (function_exists("iconv")) {
                                //valeur attendue           Valeur à tester
            $this->assertEquals('sensio', WebAwards::slugify('Sensio'));
            $this->assertEquals('sensio-labs', WebAwards::slugify('sensio labs'));
            $this->assertEquals('sensio-labs', WebAwards::slugify('sensio   labs'));
            $this->assertEquals('paris-france', WebAwards::slugify('paris,france'));
            $this->assertEquals('sensio', WebAwards::slugify('  sensio'));
            $this->assertEquals('sensio', WebAwards::slugify('sensio  '));
            $this->assertEquals('n-a', WebAwards::slugify(''));
            $this->assertEquals('n-a', WebAwards::slugify(' '));
            if (function_exists('iconv'))
            {
                $this->assertEquals('developpeur-web', WebAwards::slugify('Développeur Web'));
            }
        }

    }
}