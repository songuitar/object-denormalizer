<?php

namespace Songuitar\Denormalizer\Test;

use Songuitar\Denormalizer\Denormalizer;

class TestTest extends ContainerAwareTestCase
{
    public function test() {
        $this->container->get(Denormalizer::class);
        $this->assertEquals(1,1);
    }
}