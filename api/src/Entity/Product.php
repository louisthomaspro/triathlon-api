<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ApiResource(
 *      subresourceOperations={
 *          "api_stores_products_get_subresource"={
 *              "method"="GET",
 *              "path"="/stores/{id}/products",
 *              "normalization_context"={"groups"={"stores_products:read"}},
 *              "security"="user.hasRole('ROLE_ADMIN') or (user.hasRole('ROLE_SELLER') and (id == user.getStore().getId()))"
 *          }
 *      },
 *      collectionOperations={
 *          "post"={
 *              "security_post_denormalize"="user.hasRole('ROLE_ADMIN') or (user.hasRole('ROLE_STORE_MANAGER') and (object.getStore() == user.getStore()))"
 *          },
 *          "get"={
 *              "security"="user.hasRole('ROLE_ADMIN')",
 *              "normalization_context"={"groups"={"products:read"}}
 *          }
 *      },
 *      itemOperations={
 *          "get"={"security"="user.hasRole('ROLE_ADMIN') or (user.hasRole('ROLE_SELLER') and (object.getStore() == user.getStore()))"},
 *          "put"={"security"="user.hasRole('ROLE_ADMIN') or (user.hasRole('ROLE_SELLER') and (object.getStore() == user.getStore()))"},
 *          "delete"={"security"="user.hasRole('ROLE_ADMIN') or (user.hasRole('ROLE_STORE_MANAGER') and (object.getStore() == user.getStore()))"}
 *      }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 */
class Product
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"stores_products:read", "products:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"stores_products:read", "products:read", "stores:read"})
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"stores_products:read", "products:read", "stores:read"})
     */
    private $quantity;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Store", inversedBy="products")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"products:read"})
     */
    private $store;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
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
