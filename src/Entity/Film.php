<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FilmRepository")
 * @ORM\Table(name="film")(titre, description, catégorie, photo de
l’affiche du film).
 *
 */
class Film
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 10,
     *      max = 255,
     *      minMessage = "Your title must be at least {{ limit }} characters long",
     *      maxMessage = "Your title cannot be longer than {{ limit }} characters",
     *      allowEmptyString = false
     * )
     * @ORM\Column(type="string", length=255)
     */
    private $titre;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="text", nullable=false)
     */
    private $description;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="string", length=100, nullable=false)
     */
    private $categoryFilm;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="string", length=100, nullable=false)
     */
    private $release_date;

    /**
     * Film constructor.
     */
    public function __construct()
    {
        $this->createdAt    = new \DateTime();
        $this->release_date = (new \DateTime())->format('Y');
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * @param mixed $titre
     */
    public function setTitre($titre): void
    {
        $this->titre = $titre;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description): void
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getCategoryFilm()
    {
        return $this->categoryFilm;
    }

    /**
     * @param mixed $category
     */
    public function setCategoryFilm($categoryFilm): void
    {
        $this->categoryFilm = $categoryFilm;
    }

    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param mixed $image
     */
    public function setImage($image): void
    {
        $this->image = $image;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTimeInterface $created_at
     * @return $this
     */
    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return string
     */
    public function getReleaseDate(): string
    {
        return $this->release_date;
    }

    /**
     * @param string $release_date
     */
    public function setReleaseDate(string $release_date): void
    {
        $this->release_date = $release_date;
    }

}