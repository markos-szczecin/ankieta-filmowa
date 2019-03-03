<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MovieRepository")
 */
class Movie
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, options={"default": ""})
     */
    private $title;

    /**
     * @ORM\Column(type="integer")
     * @ORM\ManyToOne(targetEntity="Director", inversedBy="id")
     */
    private $director_id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, options={"default": ""})
     */
    private $foto;

    /**
     * @ORM\Column(type="float", options={"default": 0})
     */
    private $mark;

    /**
     * @ORM\Column(type="integer", options={"default": 0})
     */
    private $year;

    /**
     * @ORM\Column(type="integer", options={"default": 0})
     */
    private $marks_quantity;


    /**
     * @ORM\ManyToMany(targetEntity="Actor", inversedBy="movies", cascade={"persist"})
     * @ORM\JoinTable(name="movies_actors",
     *      joinColumns={@ORM\JoinColumn(name="movie_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="actor_id", referencedColumnName="id")}
     *      )
     **/
    private $actors;


    /**
     * @ORM\ManyToMany(targetEntity="Genre", inversedBy="movies", cascade={"persist"})
     * @ORM\JoinTable(name="movies_genres",
     *      joinColumns={@ORM\JoinColumn(name="movie_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="genre_id", referencedColumnName="id")}
     *      )
     */
    private $genres;


    /**
     * @ORM\ManyToMany(targetEntity="Country", inversedBy="movies", cascade={"persist"})
     * @ORM\JoinTable(name="movies_countries",
     *      joinColumns={@ORM\JoinColumn(name="movie_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="country_id", referencedColumnName="id")}
     *      )
     */
    private $countries;

    public function __construct() {
        $this->actors = new \Doctrine\Common\Collections\ArrayCollection();
        $this->genres = new \Doctrine\Common\Collections\ArrayCollection();
        $this->countries = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @param Actor $actor
     * @return Movie
     */
    public function addActor(Actor $actor): self
    {
        $this->actors->add($actor);

        return $this;
    }

    /**
     * @param Actor $actor
     * @return Movie
     */
    public function removeActor(Actor $actor): self
    {
        $this->actors->removeElement($actor);

        return $this;
    }

    /**
     * @param Genre $genre
     * @return Movie
     */
    public function addGenre(Genre $genre): self
    {
        $this->genres->add($genre);

        return $this;
    }

    /**
     * @param Genre $genre
     * @return Movie
     */
    public function removeGenre(Genre $genre): self
    {
        $this->genres->removeElement($genre);

        return $this;
    }

    /**
     * @param Country $country
     * @return Movie
     */
    public function addCountry(Country $country): self
    {
        $this->countries->add($country);

        return $this;
    }

    /**
     * @param Country $country
     * @return Movie
     */
    public function removeCountry(Country $country): self
    {
        $this->countries->removeElement($country);

        return $this;
    }

    public function getActors(): Collection
    {
        return $this->actors;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDirectorId(): ?int
    {
        return $this->director_id;
    }

    public function setDirectorId(int $director_id): self
    {
        $this->director_id = $director_id;

        return $this;
    }

    public function getFoto(): ?string
    {
        return $this->foto;
    }

    public function setFoto(?string $foto): self
    {
        $this->foto = $foto;

        return $this;
    }

    public function getMark(): ?float
    {
        return $this->mark;
    }

    public function setMark(float $mark): self
    {
        $this->mark = $mark;

        return $this;
    }

    /**
     * @return int
     */
    public function getYear(): self
    {
        return $this->year;
    }

    /**
     * @param integer $year
     * @return Movie
     */
    public function setYear($year): self
    {
        $this->year = $year;

        return $this;
    }


    public function getMarksQuantity(): ?int
    {
        return $this->marks_quantity;
    }

    public function setMarksQuantity(int $marks_quantity): self
    {
        $this->marks_quantity = $marks_quantity;

        return $this;
    }
}
