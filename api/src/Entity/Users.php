<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ApiResource(
 *      subresourceOperations={
 *          "api_stores_users_get_subresource"={
 *              "method"="GET",
 *              "normalization_context"={"groups"={"users_products:read"}}
 *          }
 *      },
 *      collectionOperations={
 *          "post"={"security"="user.hasRole('ADMIN')"},
 *          "get"={"security"="user.hasRole('STORE_MANAGER')"}
 *      },
*       itemOperations={
*           "get"={"security"="user.hasRole('STORE_MANAGER')"},
*           "delete"={"security"="user.hasRole('STORE_MANAGER')"}
*       }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\UsersRepository")
 * @UniqueEntity(fields={"email"})
 */
class Users implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"users_products:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\Email
     * @Groups({"users_products:read"})
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     * @Groups({"users_products:read"})
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Store", inversedBy="users")
     */
    private $store;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        return array_unique($this->roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }


    /**
     * Check if the user has the role
     *
     * @param string $role
     * @return boolean
     */
    public function hasRole($role)
    {
        return in_array(strtoupper($role), $this->getRoles(), true);
    }

    public function getStore(): ?Store
    {
        return $this->store;
    }

    public function setStore(?Store $store): self
    {
        $this->store = $store;

        return $this;
    }
}
