<?php

namespace Radix\App\Example\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Radix\Framework\Interfaces\RadixInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use RuntimeException;

class ExampleController
{
    /**
     * @Route("/example", name="example_root")
     */
    public function indexAction(RadixInterface $radix, Request $request)
    {
        $data = array();
        
        $html =  $radix['twig']->render('@ExampleApp/index.html.twig', $data);
        return $html;
    }
    
    /**
     * @Route("/example/{id}", name="example_id")
     */
    public function viewAction(RadixInterface $radix, Request $request, $id)
    {
        $data = array();
        exit($id);
    
    }
}
