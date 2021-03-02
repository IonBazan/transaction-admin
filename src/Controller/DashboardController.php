<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\CompanyRepository;
use App\Repository\TransactionRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/', name: 'dashboard_')]
class DashboardController extends AbstractController
{
    #[Route('', name: 'index')]
    public function __invoke(TransactionRepository $transactionRepository, CompanyRepository $companyRepository): Response
    {
        $transactionsCount = $transactionRepository->getTransactionsCount(
            new DateTime('first day of January this year'),
            new DateTime()
        );

        $companiesCount = $companyRepository->getCompaniesCount(
            new DateTime('first day of January this year'),
            new DateTime()
        );

        return $this->render('dashboard.html.twig', [
            'transactionsCount' => $transactionsCount,
            'companiesRegistered' => $companiesCount,
        ]);
    }
}
