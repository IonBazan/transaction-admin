<?php

declare(strict_types=1);

namespace App\Controller;

use App\Document\Company;
use App\Form\CompanyType;
use App\Repository\CompanyRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/companies', name: 'companies_')]
class CompanyController extends Controller
{
    #[Route('/list')]
    #[Route('', name: 'list')]
    public function list(CompanyRepository $companyRepository): Response
    {
        return $this->render('company/index.html.twig', [
            'companies' => $companyRepository->findAll(),
        ]);
    }

    #[Route('/create', name: 'create')]
    public function create(Request $request, CompanyRepository $companyRepository): Response
    {
        $form = $this->createForm(CompanyType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $companyRepository->save($form->getData());

            return $this->redirectToRoute('companies_list');
        }

        return $this->render('company/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/edit/{id}', name: 'edit')]
    #[ParamConverter('company', class: Company::class)]
    public function edit(Request $request, Company $company, CompanyRepository $companyRepository): Response
    {
        $form = $this->createForm(CompanyType::class, $company);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $companyRepository->save($form->getData());

            return $this->redirectToRoute('companies_list');
        }

        return $this->render('company/create.html.twig', [
            'form' => $form->createView(),
            'company' => $company,
        ]);
    }

    #[Route('/{id}', name: 'view')]
    #[ParamConverter('company', class: Company::class)]
    public function view(Company $company): Response
    {
        return $this->render('company/view.html.twig', [
            'company' => $company,
        ]);
    }
}
