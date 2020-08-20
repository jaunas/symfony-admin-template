<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{

    /**
     * @Route("/")
     * @Template
     */
    public function dashboard(): array
    {
        return [];
    }
}