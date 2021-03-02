<?php

declare(strict_types=1);

namespace App\Form\Filters;

use App\Document\Company;
use App\Filters\TransactionFilters;
use Doctrine\Bundle\MongoDBBundle\Form\Type\DocumentType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TransactionFiltersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('dateFrom', DateTimeType::class, ['widget' => 'single_text', 'required' => false])
            ->add('dateTo', DateTimeType::class, ['widget' => 'single_text', 'required' => false])
            ->add('companies', DocumentType::class, [
                'multiple' => true,
                'class' => Company::class,
                'required' => false,
            ])
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TransactionFilters::class,
        ]);
    }
}
