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
     * @ORM\Column(type="integer", unique=false)
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
     * @ORM\Column(type="integer", options={"default": 0})
     */
    private $users_marks_quantity;

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

    /**
     * @ORM\OneToMany(targetEntity="Mark", mappedBy="movie", cascade={"persist"})
     */
    private $marks;

    /**
     * @ORM\ManyToOne(targetEntity="Director", inversedBy="movies")
     * @ORM\JoinColumn(name="director_id", referencedColumnName="id")
     */
    private $director;

    public function __construct() {
        $this->actors = new \Doctrine\Common\Collections\ArrayCollection();
        $this->genres = new \Doctrine\Common\Collections\ArrayCollection();
        $this->countries = new \Doctrine\Common\Collections\ArrayCollection();
        $this->marks = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @return Director
     */
    public function getDirector(): ?Director
    {
        return $this->director;
    }

    /**
     * @param Director $director
     * @return Movie
     */
    public function setDirector(?Director $director): self
    {
        $this->director = $director;

        return $this;
    }

    /**
     * @return integer
     */
    public function getUsersMarksQuantity(): ?int
    {
        return $this->users_marks_quantity;
    }

    /**
     * @param integer $users_marks_quantity
     *
     * @return Movie
     */
    public function setUsersMarksQuantity($users_marks_quantity): self
    {
        $this->users_marks_quantity = $users_marks_quantity;

        return $this;
    }



    /**
     * @param Mark $mark
     * @return Movie
     */
    public function addMark(Mark $mark): self
    {
        $this->marks->add($mark);

        return $this;
    }

    /**
     * @param Mark $mark
     * @return Movie
     */
    public function removeUser(Mark $mark): self
    {
        $this->marks->removeElement($mark);

        return $this;
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

    /**
     * @return Collection|Actor[]
     */
    public function getActors(): Collection
    {
        return $this->actors;
    }

    /**
     * @return Collection|Genre[]
     */
    public function getGenres(): Collection
    {
        return $this->genres;
    }

    /**
     * @return Collection|Country[]
     */
    public function getCountries(): Collection
    {
        return $this->countries;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return Movie
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getDirectorId(): ?int
    {
        return $this->director_id;
    }

    /**
     * @param int $director_id
     * @return Movie
     */
    public function setDirectorId(int $director_id): self
    {
        $this->director_id = $director_id;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getFoto(): ?string
    {
        return $this->foto;
    }

    /**
     * @param string|null $foto
     * @return Movie
     */
    public function setFoto(?string $foto): self
    {
        $this->foto = $foto;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getMark(): ?float
    {
        return $this->mark;
    }

    /**
     * @param float $mark
     * @return Movie
     */
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

    /**
     * @return int|null
     */
    public function getMarksQuantity(): ?int
    {
        return $this->marks_quantity;
    }

    /**
     * @return Collection|Mark[]
     */
    public function getMarks(): Collection
    {
        return $this->marks;
    }

    /**
     * @param int $marks_quantity
     * @return Movie
     */
    public function setMarksQuantity(int $marks_quantity): self
    {
        $this->marks_quantity = $marks_quantity;

        return $this;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $ret = [
            'id' => $this->id,
            'title' => $this->title,
            'photo' => $this->foto,
            'mark' => $this->mark,
            'mark_quantity' => $this->marks_quantity,
            'year' => $this->year,
            'director_name' => $this->getDirector()->getName(),
            'director' => [
                'id' => $this->getDirector()->getId(),
                'name' => $this->getDirector()->getName()
            ]
        ];
        foreach ($this->getActors() as $actor) {
            $ret['actors'][] = [
                'id' => $actor->getId(),
                'name' => $actor->getName()
            ];
        }
        foreach ($this->getCountries() as $country) {
            $ret['countries'][] = [
                'id' => $country->getId(),
                'name' => $country->getName()
            ];
            $ret['countries_string'][] = $country->getName();
        }
        foreach ($this->getGenres() as $genre) {
            $ret['genres'][] = [
                'id' => $genre->getId(),
                'name' => $genre->getName()
            ];
            $ret['genres_string'][] = $genre->getName();
        }
        $ret['countries_string'] = implode(', ', $ret['countries_string']);
        $ret['genres_string'] = implode(', ', $ret['genres_string']);

        return $ret;
    }

    /**
     * @return string
     */
    public function toJson(): string
    {
        return (string) json_encode($this->toArray(), JSON_UNESCAPED_UNICODE );
    }
}
