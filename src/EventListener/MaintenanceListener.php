<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Twig\Environment;

class MaintenanceListener
{
    public function __construct(private $maintenance, private Environment $twig)
    {}

    public function onKernelRequest(RequestEvent $onKernelRequestEvent)
    {
        if(!$this->maintenance)return;

        $onKernelRequestEvent->setResponse(
            new Response(
                $this->twig->render('maintenance/maintenance.html.twig'),
                Response::HTTP_SERVICE_UNAVAILABLE
            )
        );
        $onKernelRequestEvent->stopPropagation();
    }
}