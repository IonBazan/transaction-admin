<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\Translation\TranslatorInterface;

abstract class Controller extends AbstractController
{
    public static function getSubscribedServices()
    {
        return parent::getSubscribedServices() + [
            'translator' => '?'.TranslatorInterface::class,
        ];
    }
}
