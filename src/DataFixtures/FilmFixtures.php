<?php

namespace App\DataFixtures;

use App\Entity\Film;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class FilmFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $category = ['action', 'comedie', 'drame', 'aventures', 'fantastique'];
        for ($i = 0; $i < 20; $i++) {
            $rand_keys = array_rand($category, 1);
            $film = new Film();
            $film->setTitre('Film '.$i);
            $film->setDescription('description film '.$i);
            $film->setCategoryFilm($category[$rand_keys]);
            $film->setCreatedAt(new \DateTime());
            $film->setReleaseDate((new \DateTime())->format('Y'));
            $manager->persist($film);
            $manager->flush();
        }
    }
}
