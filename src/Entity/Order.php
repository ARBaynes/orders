<?php

namespace App\Entity;

use DateTimeInterface;
use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity(repositoryClass=OrderRepository::class)
 * @ORM\Table(name="`order`")
 */
class Order
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $email;

    /**
     * @ORM\Column(type="datetime")
     */
    private ?DateTimeInterface $checkoutAt;

    /**
     * @ORM\OneToMany(targetEntity=OrderItem::class, mappedBy="order", orphanRemoval=true)
     */
    private Collection $orderItems;

    /**
     * Order constructor.
     */
    public function __construct()
    {
        $this->orderItems = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return $this
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return DateTimeInterface
     */
    public function getCheckoutAt(): DateTimeInterface
    {
        return $this->checkoutAt;
    }

    /**
     * @param DateTimeInterface $checkoutAt
     * @return $this
     */
    public function setCheckoutAt(DateTimeInterface $checkoutAt): self
    {
        $this->checkoutAt = $checkoutAt;

        return $this;
    }


    /**
     * @return int
     *
     * @JMS\VirtualProperty
     * @JMS\SerializedName("order_total_item_quantities")
     */
    public function getOrderItemQuantities(): int {
        $total = 1;
        foreach ($this->getOrderItems() as $item) {
            $total += $item->getQuantity();
        }
        return $total;
    }

    /**
     * @return float
     *
     * @JMS\VirtualProperty
     * @JMS\SerializedName("order_total_price")
     */
    public function getTotalOrderPrice(): float {
        $total = 0.0;
        foreach ($this->getOrderItems() as $item) {
            $total += $item->getPriceForQuantity();
        }
        return $total;
    }

    /**
     * @return Collection|OrderItem[]
     */
    public function getOrderItems(): Collection
    {
        return $this->orderItems;
    }

    public function addOrderItem(OrderItem $orderItem): self
    {
        if (!$this->orderItems->contains($orderItem)) {
            $this->orderItems[] = $orderItem;
            $orderItem->setOrder($this);
        }

        return $this;
    }

    public function removeOrderItem(OrderItem $orderItem): self
    {
        if ($this->orderItems->contains($orderItem)) {
            $this->orderItems->removeElement($orderItem);
            // set the owning side to null (unless already changed)
            if ($orderItem->getOrder() === $this) {
                $orderItem->setOrder(null);
            }
        }

        return $this;
    }
}
