<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\Encoder\EncoderAwareInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface, EncoderAwareInterface
{

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="boolean", options={"default": 1})
     */
    private $is_new;

    /**
     * @ORM\OneToMany(targetEntity="Mark", mappedBy="user")
     */
    private $marks;


    /**
     * @ORM\Column(name="role", nullable=false, type="string", options={"default": "ROLE_USER"})
     */
    private $role = 'ROLE_USER';

    public function __construct(?string $username = null)
    {
        $this->username = $username;
        $this->marks = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @param Mark $mark
     * @return User
     */
    public function addMarks(Mark $mark): self
    {
        $this->marks->add($mark);

        return $this;
    }

    /**
     * @param Mark $mark
     * @return User
     */
    public function removeMovie(Mark $mark): self
    {
        $this->marks->removeElement($mark);

        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection|Mark[]
     */
    public function getMarks()
    {
        return $this->marks;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $login): self
    {
        $this->username = $login;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getIsNew(): ?bool
    {
        return $this->is_new;
    }

    public function setIsNew(bool $is_new): self
    {
        $this->is_new = $is_new;

        return $this;
    }

    /**
     * Returns the roles granted to the user.
     *
     *     public function getRoles()
     *     {
     *         return ['ROLE_USER'];
     *     }
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return (Role|string)[] The user roles
     */
    public function getRoles()
    {
        return [$this->role];
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    /**
     * Gets the name of the encoder used to encode the password.
     *
     * If the method returns null, the standard way to retrieve the encoder
     * will be used instead.
     *
     * @return string
     */
    public function getEncoderName()
    {
        // TODO: Implement getEncoderName() method.
    }
}
