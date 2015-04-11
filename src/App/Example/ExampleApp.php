<?php

namespace Radix\App\Example;

use Radix\Framework\BaseApp;
use Radix\Framework\Interfaces\RadixInterface;
use Radix\Framework\Interfaces\AppInterface;
use Radix\Framework\Annotation\App;

/**
 * @App(name="example",
 * title="Example app",
 * description="This is an example of various Radix functionalities in custom apps")
 */
class ExampleApp extends BaseApp implements AppInterface
{
    public function register(RadixInterface $radix)
    {
        $radix['cool'] = 'stuff';
    }
}
