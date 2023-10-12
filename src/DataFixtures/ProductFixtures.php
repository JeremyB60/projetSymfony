<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Product;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $productsData = [
            [
                'nom' => 'abricot',
                'type' => 'fruit',
                'prix' => 2,
                'image_url' => '/images/abricot.jpg',
                'description' => 'Nos abricots sont oranges et généralement frais.',
            ],
            [
                'nom' => 'salade',
                'type' => 'legume',
                'prix' => 5,
                'image_url' => '/images/salade.webp',
                'description' => 'Mangez nos belles salades !',
            ],
            [
                'nom' => 'cerise',
                'type' => 'fruit',
                'prix' => 3,
                'image_url' => '/images/cerise.jpg',
                'description' => 'Dégustez nos griottes !',
            ],
            [
                'nom' => 'tomate',
                'type' => 'legume',
                'prix' => 4,
                'image_url' => '/images/tomate.png',
                'description' => null,
            ],
        ];

        foreach ($productsData as $data) {
            $product = new Product();
            $product->setNom($data['nom']);
            $product->setType($data['type']);
            $product->setPrix($data['prix']);
            $product->setImageUrl($data['image_url']);
            $product->setDescription($data['description']);

            $manager->persist($product);
        }

        $manager->flush();
    }
}
