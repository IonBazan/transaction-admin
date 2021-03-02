<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Document\Address;
use App\Document\Company;
use App\Document\Money;
use App\Document\Transaction;
use App\Transaction\Status;
use Doctrine\Bundle\MongoDBBundle\Fixture\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;
use Faker\Provider\en_SG\Address as AddressProvider;
use Faker\Provider\en_SG\PhoneNumber;

class DemoFixtures extends Fixture
{
    private Faker\Generator $faker;

    public function __construct()
    {
        $this->faker = Faker\Factory::create('en_US');
        $this->faker->addProvider(new AddressProvider($this->faker));
        $this->faker->addProvider(new PhoneNumber($this->faker));
    }

    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 25; ++$i) {
            $address = new Address();
            $address->setCity($this->faker->city());
            $address->setCountry($this->faker->countryCode());
            $address->setZip($this->faker->postcode());
            $address->setStreet($this->faker->streetAddress());
            $address->setPhoneNumber($this->faker->phoneNumber());

            $company = new Company();
            $company->setName($this->faker->company());
            $company->setAddress($address);
            $company->setTaxNumber($this->faker->ean13());
            $company->setCreatedAt($this->faker->dateTimeThisYear());
            $this->addReference('company_'.$i, $company);
            $manager->persist($company);
        }

        for ($i = 1; $i <= 200; ++$i) {
            $transaction = new Transaction();
            $transaction->setAmount(new Money($this->faker->randomFloat(2), $this->faker->randomElement(['SGD', 'USD', 'EUR'])));
            $transaction->setCompany($this->getReference('company_'.$this->faker->biasedNumberBetween(1, 20)));
            $transaction->setStatus(Status::from($this->faker->randomElement(Status::toArray())));
            $transaction->setCreatedAt($this->faker->dateTimeThisYear());
            $manager->persist($transaction);
        }

        $manager->flush();
    }
}
