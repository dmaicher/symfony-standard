<?php

namespace Acme\ApiBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/api")
 */
class ApiController extends Controller
{
    /**
     * @Route("/tokens")
     * @Method("POST")
     */
    public function tokensAction()
    {
        return new JsonResponse(['token' => uniqid()]);
    }

    /**
     * @Route("/hello")
     */
    public function helloAction()
    {
        return new JsonResponse(['hello' => 'world']);
    }
}
