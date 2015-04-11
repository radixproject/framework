<?php

namespace Radix\Framework\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Radix\Framework\Interfaces\RadixInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use RuntimeException;

class FrameworkController
{
    /**
     * @Route("/", name="framework_root")
     */
    public function indexAction(RadixInterface $radix, Request $request)
    {
        $data = array();
        
        $html =  $radix['twig']->render('@Framework/index.html.twig', $data);
        return $html;
    }
    
    /**
     * @Route("/radix/info", name="radix_info")
     */
    public function radixInfoAction(RadixInterface $radix, Request $request)
    {
        $data = array();
        
        $data['apps'] = $radix->getApps();
        $data['types'] = $radix->getTypes();
        
        $html =  $radix['twig']->render('@Framework/radix/info.html.twig', $data);
        return $html;
    }
}
