<?php

namespace App\Controller;

use App\Entity\Director;
use App\Entity\Mark;
use App\Entity\Movie;
use App\Entity\User;
use App\Library\Cookie;
use App\Library\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PlayController extends MainController
{
    /**
     * @return User|mixed|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    protected function getUser()
    {
        return parent::getUser();
    }

    /**
     * @Route("/play", name="play")
     */
    public function index()
    {
        if (!Cookie::getUsername()) {
            return $this->redirectToRoute('main');
        }
        return $this->render('play/index.html.twig', [
            'controller_name' => 'PlayController',
        ]);
    }

    /**
     * @Route("/review", name="review")
     */
    public function review(Request $request)
    {
        try {
            $this->saveReview(['id' => $request->get('movieId'), 'mark' => $request->get('mark')]);
            $movie = $this->getMovie();

            return $this->render('play/review.html.twig', [
                'id' => $movie['id'],
                'title' => $movie['title'],
                'director' => $movie['director_name'],
                'genre' => $movie['genres_string'],
                'country' => $movie['countries_string'],
                'photo' => $movie['photo']
            ]);
        } catch (\Throwable $t) {
            file_put_contents('errorlog.dat', '[' . date('Y-m-d H:i:s') . '] ' . $e->getMessage() . '<br />' . $e->getTraceAsString() . '<br />' . PHP_EOL, FILE_APPEND);
            return [];
        }
    }

    /**
     * @return array
     */
    public function getMovie()
    {
        $r = $this->getMoviesArray(1);

        return reset($r);
    }

    /**
     * @Route("/get-movies", name="get-movies")
     */
    public function getMovies()
    {
        return $this->json($this->getMoviesArray(12));
    }

    /**
     * @return array
     */
    private function getMoviesIdsMarkedByUser()
    {
        try {
            $user = $this->getDoctrine()->getRepository(User::class)->findOneByUsername(Cookie::getUsername());
            if (!$user) {
                return [];
            }
            $marks = $this->getDoctrine()->getRepository(Mark::class)->findByUserId(
                $user->getId()
            );
            $ids = [];
            foreach ($marks as $mark) {
                $ids[] = $mark->getMovie()->getId();
            }
            foreach (Session::getLoadedMovies() as $movie) {
                $ids[] = $movie;
            }
            return $ids;
        } catch (\Throwable $t) {
            file_put_contents('errorlog.dat', '[' . date('Y-m-d H:i:s') . '] ' . $t->getMessage() . '<br />' . $e->getTraceAsString() . '<br />' . PHP_EOL, FILE_APPEND);
            return [];
        }
    }

    /**
     * @param int $limit
     * @param bool $noSession
     * @return array
     */
    private function getMoviesArray(int $limit = 1, $noSession = false)
    {
        try {
            if ($noSession) {
                Session::clearLoadedMovies();
            }
            $movies = $this
                ->getDoctrine()
                ->getRepository(Movie::class)
                ->getRandMoviesForUser($this->getMoviesIdsMarkedByUser(), $limit);

            $ret = [];
            /** @var Movie $movie */
            foreach ($movies as $movie) {
                Session::addLoadedMovie($movie->getId());

                $ret[] = $movie->toArray();
            }
            if (empty($ret) && !$noSession) {
                //Czyścimy sesję i próbujemy jeszcze raz
                $this->getMoviesArray($limit, true);
            }
            return $ret;
        } catch (\Throwable $e) {
            file_put_contents('errorlog.dat', '[' . date('Y-m-d H:i:s') . '] ' . $e->getMessage() . '<br />' . $e->getTraceAsString() . '<br />' . PHP_EOL, FILE_APPEND);
            return [];
        }
    }
}
