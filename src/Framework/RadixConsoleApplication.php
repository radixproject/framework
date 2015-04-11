<?php

namespace Radix\Framework;

use Radix\Framework\Interfaces\RadixInterface;
use Symfony\Component\Console\Application as SymfonyConsoleApplication;

class RadixConsoleApplication extends SymfonyConsoleApplication
{
    public function setRadix(RadixInterface $radix)
    {
        $this->radix = $radix;
    }
    
    public function getRadix()
    {
        return $this->radix;
    }
}
