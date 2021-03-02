<?php

declare(strict_types=1);

namespace App\Form;

use App\Document\Money;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Extension\Core\Type\CurrencyType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType as SymfonyMoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MoneyType extends AbstractType implements DataMapperInterface
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('amount', SymfonyMoneyType::class, [
                'currency' => false,
            ])
            ->add('currency', CurrencyType::class)
            ->setDataMapper($this)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Money::class,
            'empty_data' => null,
            'compound' => true,
        ]);
    }

    public function getBlockPrefix(): string
    {
        return 'document_money';
    }

    public function mapDataToForms($viewData, iterable $forms): void
    {
        $forms = iterator_to_array($forms);
        $forms['amount']->setData($viewData ? $viewData->getAmount() : 0);
        $forms['currency']->setData($viewData ? $viewData->getCurrency() : 'SGD');
    }

    public function mapFormsToData(iterable $forms, &$viewData): void
    {
        $forms = iterator_to_array($forms);
        $viewData = new Money(
            $forms['amount']->getData(),
            $forms['currency']->getData()
        );
    }
}
