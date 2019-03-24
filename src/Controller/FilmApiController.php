<?php

namespace App\Controller;

use App\Entity\Actor;
use App\Entity\Country;
use App\Entity\Director;
use App\Entity\Genre;
use App\Entity\Movie;
use DOMDocument;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\VarDumper\Exception\ThrowingCasterException;
use Throwable;

/**
 * Oranie po serwisie FilmWeb i wyciąganie informacji o filmach (nieoptymalne, ale działa)
 * 
 * @package App\Controller
 */
class FilmApiController extends AbstractController
{
    private $genres;
    private $countries;
    private $directors;
    private $actors;

    /**
     * @Route("/film/api/themoviedb", name="film_api")
     */
    public function themoviedb()
    {
        die;
        error_reporting(E_ERROR | E_PARSE);
        ini_set('memory_limit', -1);
        ini_set('max_execution_time', -1);
        $manager = $this->getDoctrine()->getManager();
        $titles = [];
        $errors = [];
        for ($i = 1; $i < 101; $i++) {
            $url = 'https://www.filmweb.pl/films/search?endYear=2018&orderBy=popularity&descending=true&startYear=2000&page=' . $i;
            $doc = new DOMDocument();
            $doc->loadHTML(file_get_contents($url));
            $result = $doc->getElementById('searchResult');
            foreach ($result->firstChild->childNodes as $childNode) {
                try {
                    $film = $childNode->firstChild->childNodes->item(1);
                    $title = $film->firstChild->firstChild->textContent;
                    $details = $film->childNodes->item(3);
                } catch (Throwable $t) {
                    continue;
                }

                $titles[] = $title;
                try {
                    $mark = (float) str_replace(',', '.', $film->childNodes->item(1)->firstChild->childNodes->item(2)->textContent);
                } catch (Throwable $t) {
                    $errors[$title][] = 'no mark';
                    $mark = 0;
                }
                try {
                    $popularity = (int) str_replace(' ', '', $film->childNodes->item(1)->firstChild->childNodes->item(4)->textContent);
                } catch (Throwable $t) {
                    $errors[$title][] = 'no popularity';
                    $popularity = 0;
                }
                try {
                    $image = $childNode->firstChild->firstChild->childNodes->item(1)->childNodes->item(3)->firstChild->getAttribute('data-src');
                } catch (Throwable $t) {
                    $errors[$title][] = 'no image';
                    $image = '';
                }

                try {
                    foreach ($details->childNodes->item(2)->childNodes->item(1)->childNodes as $item) {
                        $director = $this->getDirector($item->textContent);
                        $manager->persist($director);
                        $manager->flush();
                        break;
                    }
                } catch (Throwable $t) {
                    $errors[$title][] = 'no directory';
                    continue;
                }
                preg_match('/([0-9]+)$/', $title, $matches);
                $movie = new Movie();
                $movie
                    ->setTitle($title)
                    ->setFoto($image)
                    ->setMark($mark)
                    ->setYear((int) @$matches[1])
                    ->setDirectorId($director->getId())
                    ->setMarksQuantity($popularity);
                try {
                    $actors = $this->extractMovieDesc($film->firstChild->firstChild->firstChild->getAttribute('href'));
                    $temp = [];
                    foreach ($actors as $actor) {
                        if (isset($temp[base64_encode($actor)])) {
                            continue;
                        }
                        $temp[base64_encode($actor)] = true;
                        $model = $this->getActor($actor);
                        $movie->addActor($model);
                    }
                } catch (Throwable $t) {
                    $errors[$title][] = 'no actors';
                    continue;
                }
                try {
                    $temp = [];
                    foreach ($details->firstChild->childNodes->item(1)->childNodes as $item) {
                        try {
                            if (isset($temp[base64_encode($item->textContent)])) {
                                continue;
                            }
                            $temp[base64_encode($item->textContent)] = true;
                            $model = $this->getGenre($item->textContent);
                            $movie->addGenre($model);
                        } catch (Throwable $t) {
                            $errors[$title][] = 'genre error';
                            continue;
                        }
                    }
                    $temp = [];
                    foreach ($details->childNodes->item(1)->childNodes->item(1)->childNodes as $item) {
                        try {
                            if (isset($temp[base64_encode($item->textContent)])) {
                                continue;
                            }
                            $temp[base64_encode($item->textContent)] = true;
                            $model = $this->getCountrie($item->textContent);
                            $movie->addCountry($model);
                        } catch (Throwable $t) {
                            $errors[$title][] = 'country error';
                            continue;
                        }
                    }
                } catch (Throwable $t) {
                    $errors[$title][] = 'no genre or country';
                    continue;
                }
                $manager->persist($movie);
                $manager->flush();
                $this->fillActors();
                $this->fillCountries();
                $this->fillDirectories();
                $this->fillGenres();
            }
        }

        return $this->render('film_api/themoviedb.html.twig', [
            'controller_name' => 'FilmApiController',
            'titles' => $titles
        ]);
    }

    function extractMovieDesc($url)
    {
        $url = 'https://www.filmweb.pl' . $url . '/cast/actors';
        $dom = new DOMDocument();
        $dom->loadHTML(file_get_contents($url));
        $tables = $dom->getElementsByTagName('table');
        $actors = [];
        for ($i = 0; $tables->length; $i++) {
            $item = $tables->item($i);
            if (!$item) {
                continue;
            }
            if ($item->hasAttribute('class') && mb_stripos($item->getAttribute('class'), 'filmCast filmCastCast') !== false) {
                $tds = $item->getElementsByTagName('td');
                for ($j = 0; $j < $tds->length; $j++) {
                    foreach ($tds->item($j)->childNodes as $childNode) {
                        try {
                            if ($childNode->getAttribute('rel') == 'v:starring') {
                                $actors[] = $childNode->textContent;
                            }
                        } catch (Throwable $t) {

                        }
                    }
                }
                return $actors;
            }
        }
        return $actors;
    }

    private function getActor($name)
    {
        $encodedName = md5(str_replace(' ', '', $name));
        if ($this->actors && isset($this->actors[$encodedName])) {
            return $this->actors[$encodedName];
        }
        $actor = new Actor();
        $actor->setName($name);

        return $actor;
    }

    private function getGenre($name)
    {
        $encodedName = md5(str_replace(' ', '', $name));
        if ($this->genres && isset($this->genres[$encodedName])) {
            return $this->genres[$encodedName];
        }
        $genre = new Genre();
        $genre->setName($name);

        return $genre;
    }

    private function getDirector($name)
    {
        $encodedName = md5(str_replace(' ', '', $name));
        if ($this->directors && isset($this->directors[$encodedName])) {
            return $this->directors[$encodedName];
        }
        $director = new Director();
        $director->setName($name);

        return $director;
    }

    private function getCountrie($name)
    {
        $encodedName = md5(str_replace(' ', '', $name));
        if ($this->countries && isset($this->countries[$encodedName])) {
            return $this->countries[$encodedName];
        }
        $country = new Country();
        $country->setName($name);

        return $country;
    }

    private function fillActors()
    {
        $this->actors = [];
        foreach ($this->getDoctrine()->getRepository(Actor::class)->findAll() as $actor) {
            $this->actors[md5(str_replace(' ', '', $actor->getName()))] = $actor;
        }
    }

    private function fillGenres()
    {
        $this->genres = [];
        foreach ($this->getDoctrine()->getRepository(Genre::class)->findAll() as $genre) {
            $this->genres[md5(str_replace(' ', '', $genre->getName()))] = $genre;
        }
    }

    private function fillCountries()
    {
        $this->countries = [];
        foreach ($this->getDoctrine()->getRepository(Country::class)->findAll() as $country) {
            $this->countries[md5(str_replace(' ', '', $country->getName()))] = $country;
        }
    }

    private function fillDirectories()
    {
        $this->directors = [];
        foreach ($this->getDoctrine()->getRepository(Director::class)->findAll() as $director) {
            $this->directors[md5(str_replace(' ', '', $director->getName()))] = $director;
        }
    }
}
