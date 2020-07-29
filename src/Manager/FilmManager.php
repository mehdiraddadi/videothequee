<?php

namespace App\Manager;

use App\Entity\Film;
use App\Form\FilmType;
use App\Repository\FilmRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormFactoryInterface;

class FilmManager
{
    /**
     * @var FilmRepository
     */
    private $repository;

    /**
     * @var ObjectManager
     */
    private $em;

    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * FilmManager constructor
     * @param FilmRepository $repository
     */
    public function __construct(FilmRepository $repository, EntityManagerInterface $em, FormFactoryInterface $formFactory)
    {
        $this->repository  = $repository;
        $this->formFactory = $formFactory;
        $this->em          = $em;
    }

    /**
     * @param $data
     * @param $id
     * @return JsonResponse
     */
    public function processAddd(Request $request)
    {
        $process = false;
        $film    = new Film();
        if ($request->isMethod('POST')) {
            $form = $this->formFactory->create(FilmType::class, $film, []);
            $data = json_decode($request->getContent(), true);

            $form->submit($data);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->em->persist($film);
                $this->em->flush();
                $process = true;
            } else {
                throw new Exception(json_encode($this->getErrorMessages($form)));
            }
        }
        return $process;
    }

    /**
     * @param Request $request
     * @param int $id
     * @return bool
     */
    public function processEdit(Request $request, int $id)
    {
        $process = false;
        $film = $this->repository->find($id);
        if(!$film) {
            $process = false;
        }
        if ($request->isMethod('POST')) {
            $form = $this->formFactory->create(FormType::class, $film, []);
            $data = json_decode($request->getContent(), true);

            $form->submit($data);
            $form->handleRequest($request, $form->getData());

            if ($form->isSubmitted() && $form->isValid()) {
                $this->em->flush();
                $process = true;
            } else {
                throw new Exception(json_encode($this->getErrorMessages($form)));
            }
        }
        return $process;
    }

    /**
     * @param $id
     * @return array
     */
    public function processDelete(int $id)
    {
        $response = ["message" => "film deleted!", "code" => 204];
        $film = $this->repository->find($id);
        if(!$film) {
            $response = ["message" => "film not nound", "code" => 204];
        }
        $this->em->remove($film);
        $this->em->flush();

        return $response;
    }

    /**
     * @param \Symfony\Component\Form\Form $form
     * @return array
     */
    private function getErrorMessages(\Symfony\Component\Form\Form $form) {
        $errors = array();

        foreach ($form->getErrors() as $key => $error) {
            if ($form->isRoot()) {
                $errors['#'][] = $error->getMessage();
            } else {
                $errors[] = $error->getMessage();
            }
        }

        foreach ($form->all() as $child) {
            if (!$child->isValid()) {
                $errors[$child->getName()] = $this->getErrorMessages($child);
            }
        }

        return $errors;
    }
}