<?php

namespace App\Controller;

use App\Entity\Mark;
use App\Entity\Movie;
use App\Entity\User;
use App\Library\Cookie;
use App\Library\Session;
use mysql_xdevapi\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class MainController extends AbstractController
{
    /**
     * @param int $movieId
     * @return bool
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    protected function isUserMarkedMovie(int $movieId)
    {
       $user = $this->getUser();
        foreach ($user->getMarks() as $mark) {
            if ($mark->getMovie()->getId() === $movieId) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return User|mixed|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    protected function getUser()
    {
        return $this->getDoctrine()->getRepository(User::class)->findOneByUsername(Cookie::getUsername());
    }

    protected function validateMark(int $mark)
    {
        if ($mark > 10 || $mark < -1 || $mark == 0) {
            return false;
        }

        return $mark;
    }

    /**
     * @param array $data
     * @return bool
     */
    protected function saveReview(array $data)
    {
        if (!Cookie::getUsername()) {
            $this->register();
        } else {
            Session::getInstance();
        }
        try {
            if ($this->isUserMarkedMovie((int) $data['id'])) {
                return false;
            }
            $markValue = $this->validateMark((int) $data['mark']);
            if (!$markValue) {
                return false;
            }
            $currentMovie = $this->getDoctrine()->getRepository(Movie::class)->find($data['id']);
            $currentMovie->setUsersMarksQuantity($currentMovie->getUsersMarksQuantity() + 1);
            $mark = new Mark();
            $mark
                ->setMovie($currentMovie)
                ->setUser($this->getUser())
                ->setMark($markValue);
            $currentMovie->addMark($mark);
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($currentMovie);
            $manager->flush();

            return true;
        } catch (\Throwable $e) {
            var_dump($e->getMessage());
            file_put_contents('errors.dat', '[' . date('Y-m-d H:i:s') . '] ' . $e->getMessage());
            return false;
        }
    }

    /**
     * @Route("/", name="main")
     */
    public function index()
    {
        if (!Cookie::getUsername()) {
            $this->register();
        } else {
            Session::getInstance();
        }
        return $this->redirectToRoute('play');
    }

    public function loadQuestions()
    {
        $user = $this->getDoctrine()->getRepository(User::class)->findOneByUsername(Cookie::getUsername());
        if ($user) {
            throw new Exception('no found user');
        }
        foreach ($user->getMarks() as $mark) {
            Session::addLoadedMovie($mark->getMovie()->getId());
        }
    }

    public function register()
    {
        Cookie::init();
        $user = new User();
        $user->setUsername(Cookie::getUsername());
        $user->setPassword(Cookie::getPassword());
        $user->setIsNew(true);
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();
    }

}
