<?php

namespace App\Manager;

use App\Entity\Film;
use App\Form\FilmType;
use App\Repository\FilmRepository;
use Doctrine\ORM\EntityManagerInterface;
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
    public function process(Request $request, $id = null, string $action)
    {
        $process = false;
        $film    = new Film();
        if ($request->isMethod('POST')) {
            if($action !== "add"){
                $film = $this->repository->find($id);
                $form = $this->formFactory->create(FormType::class, $film, []);
                $form->submit($request->request->get($form->getName()));
                if(!$film) {
                    $process = false;
                }
                $form->handleRequest($request);

                if ($form->isSubmitted() && $form->isValid()) {
                    $this->em->flush();
                    $process = true;
                }
            }

            if($action === "add"){
                $form = $this->formFactory->create(FilmType::class, $film, []);
                $form->handleRequest($request);
                var_dump($request->request, $request->query);die;
                if ($form->isSubmitted() && $form->isValid()) {
                    $this->em->persist($film);
                    $this->em->flush();
                    $process = true;
                }
            }
        }
        return $process;
    }

    /**
     * @param $id
     * @return array
     */
    public function delete($id)
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