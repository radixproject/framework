<?php

namespace Radix\App\Example;

use Radix\Framework\BaseApp;
use Radix\Framework\Interfaces\RadixInterface;
use Radix\Framework\Interfaces\AppInterface;
use Radix\Framework\Annotation\App;

/**
 * @App("example", title="Example app")
 */
class ExampleApp extends BaseApp implements AppInterface
{
    public function getName()
    {
        return "Example";
    }
    
    public function register(RadixInterface $radix)
    {
        $radix['cool'] = 'stuff';
    }
}
