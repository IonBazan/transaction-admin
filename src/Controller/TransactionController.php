<?php

declare(strict_types=1);

namespace App\Controller;

use App\Document\Transaction;
use App\Filters\TransactionFilters;
use App\Form\Filters\TransactionFiltersType;
use App\Form\TransactionType;
use App\Repository\TransactionRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/transactions', name: 'transactions_')]
class TransactionController extends Controller
{
    #[Route('/list')]
    #[Route('', name: 'list')]
    public function list(Request $request, TransactionRepository $transactionRepository): Response
    {
        $filtersForm = $this->createForm(TransactionFiltersType::class, new TransactionFilters())->handleRequest($request);

        return $this->render('transaction/index.html.twig', [
            'filtersForm' => $filtersForm->createView(),
            'transactions' => $transactionRepository->getFiltered($filtersForm->getData()),
            'stats' => $transactionRepository->getStats($filtersForm->getData()),
        ]);
    }

    #[Route('/create', name: 'create')]
    public function create(Request $request, TransactionRepository $transactionRepository): Response
    {
        $form = $this->createForm(TransactionType::class)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $transaction = $form->getData();
            $transactionRepository->save($transaction);
            $this->addFlash('success', $this->get('translator')->trans('transaction.created', ['%id%' => $transaction->getId()]));

            return $this->redirectToRoute('transactions_list');
        }

        return $this->render('transaction/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/edit/{id}', name: 'edit')]
    #[ParamConverter('transaction', class: Transaction::class)]
    public function edit(Request $request, Transaction $transaction, TransactionRepository $transactionRepository): Response
    {
        $form = $this->createForm(TransactionType::class, $transaction)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $transaction = $form->getData();
            $transactionRepository->save($transaction);
            $this->addFlash('success', $this->get('translator')->trans('transaction.updated', ['%id%' => $transaction->getId()]));

            return $this->redirectToRoute('transactions_list');
        }

        return $this->render('transaction/create.html.twig', [
            'form' => $form->createView(),
            'transaction' => $transaction,
        ]);
    }

    #[Route('/delete/{id}', name: 'delete')]
    #[ParamConverter('transaction', class: Transaction::class)]
    public function delete(Request $request, Transaction $transaction, TransactionRepository $repository): Response
    {
        $form = $this->createFormBuilder()->setMethod('DELETE')->getForm()->handleRequest($request);

        if ($this->isCsrfTokenValid('delete-transaction', $request->request->get('_token'))) {
            $repository->remove($transaction);
            $this->addFlash('success', $this->get('translator')->trans('transaction.deleted', ['%id%' => $transaction->getId()]));

            return $this->redirectToRoute('transactions_list');
        }

        return $this->render('transaction/delete.html.twig', [
            'form' => $form->createView(),
            'transaction' => $transaction,
        ]);
    }

    #[Route('/{id}', name: 'view')]
    #[ParamConverter('transaction', class: Transaction::class)]
    public function view(Transaction $transaction): Response
    {
        return $this->render('transaction/view.html.twig', [
            'transaction' => $transaction,
        ]);
    }
}
