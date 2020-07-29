<?php
namespace App\Controller;

use App\Entity\Film;
use App\Form\FilmType;
use App\Manager\FilmManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\FilmRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class ApiFilmController
 * @package App\Controller
 */
Class ApiFilmController extends AbstractController
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
     * @var FilmManager
     */
    private $manager;

    public function __construct(FilmRepository $repository, SerializerInterface $serializer, FilmManager $manager)
    {
        $this->repository = $repository;
        $this->serializer = $serializer;
        $this->manager    = $manager;
    }

    /**
     * @Route("/", name="all_films", methods={"GET"})
     * @return Response
     */
    public function index(Request $request):Response
    {
        $film = new Film();
        $form = $this->createForm(FilmType::class, $film, array(
            'method' => 'post'
        ));
        $page  = $request->query->get('page', 1);
        $limit = 5;

        $films = $this->repository->findAllFilm($page, $limit);
        dump($films);die;
        return new JsonResponse($this->serializer->serialize($films, 'json'));
    }

    /**
     * @Route("/film/{id}", name="get_film", methods={"GET"})
     * @return Response
     */
    public function getFilm(int $id):Response
    {
        $film = $this->repository->find($id);
        if(!$film) {
            return new JsonResponse(["message" => "film not nound", "code" => 204]);
        }
        return new JsonResponse($this->serializer->serialize($film, 'json'));
    }

    /**
     * @param int $id
     * @Route("/film/{id}", name="edit_film", methods={"GET|POST"})
     * @return Response
     */
    public function editFilm(Request $request, int $id):Response
    {
        $process = $this->manager->process($request, $id, 'edit');
        if(!$process) {
            return new JsonResponse(["message" => "Error in modification of film", "code" => 500]);
        }
        return new JsonResponse(["message" => "Film modified !", "code" => 200]);
    }

    /**
     * @Route("/film/{id}", name="delete_film", methods={"DELETE"})
     * @param int $id
     * @return Response
     */
    public function deleteFilm(int $id):Response
    {
        $response = $this->manager->delete($id);

        return new JsonResponse($response);
    }

    /**
     * @Route("/new/film", name="new_film", methods={"GET|POST"})
     * @return Response
     */
    public function newFilm(Request $request):Response
    {
        $process = $this->manager->process($request, null, 'add');
        if(!$process) {
            return new JsonResponse(["message" => "Error in add action of film", "code" => 500]);
        }
        return new JsonResponse(["message" => "Film added !", "code" => 200]);
    }
}