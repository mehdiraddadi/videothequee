<?php

namespace App\Controller;

use App\Repository\FilmRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Annotation\Route;

class FilmSearch extends AbstractController
{
    /**
     * @var FilmRepository
     */
    private $repository;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * FilmSearch constructor.
     * @param FilmRepository $repository
     * @param SerializerInterface $serializer
     */
    public function __construct(FilmRepository $repository, SerializerInterface $serializer)
    {
        $this->repository = $repository;
        $this->serializer = $serializer;
    }

    /**
     * @Route("/search", name="search_films", methods={"POST"})
     * @param Request $request
     */
   public function search(Request $request)
   {
        $search = $this->repository->search($request);

       return new JsonResponse($this->serializer->serialize($search, 'json'));
   }
}
