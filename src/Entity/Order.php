<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\OrderRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=OrderRepository::class)
 * @ORM\Table(name="`order`")
 * 
 */

#[ApiResource(
    itemOperations:['get'],
    normalizationContext: ['groups' => ['read']]
)]

class Order
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"read"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="orders")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"read"})
     */
    private $user;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"read"})
     */
    private $createdAt;

    /**
     * @ORM\Column(type="string", length=100)
     * @Groups({"read"})
     */
    private $shipment;

   
    /**
     * @ORM\Column(type="float")
     * @Groups({"read"})
     */
    private $shipmentPrice;

    /**
     * @ORM\Column(type="text")
     * @Groups({"read"})
     */
    private $delivery;

    /**
     * @ORM\OneToMany(targetEntity=OrderDetails::class, mappedBy="myOrder")
     * @Groups({"read"})
     */
    private $orderDetails;

    /**
     * @ORM\Column(type="string", length=100)
     * @Groups({"read"})
     */
    private $paymentChoice;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"read"})
     */
    private $isPaid;

    /**
     * @ORM\ManyToOne(targetEntity=Status::class, inversedBy="orders")
     * @ORM\JoinColumn(nullable=false)
     */
    private $status;

    public function __construct()
    {
        $this->orderDetails = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getShipment(): ?string
    {
        return $this->shipment;
    }

    public function setShipment(string $shipment): self
    {
        $this->shipment = $shipment;

        return $this;
    }

    public function getShipmentPrice(): ?float
    {
        return $this->shipmentPrice;
    }

    public function setShipmentPrice(float $shipmentPrice): self
    {
        $this->shipmentPrice = $shipmentPrice;

        return $this;
    }

    public function getDelivery(): ?string
    {
        return $this->delivery;
    }

    public function setDelivery(string $delivery): self
    {
        $this->delivery = $delivery;

        return $this;
    }

    /**
     * @return Collection|OrderDetails[]
     */
    public function getOrderDetails(): Collection
    {
        return $this->orderDetails;
    }

    public function addOrderDetail(OrderDetails $orderDetail): self
    {
        if (!$this->orderDetails->contains($orderDetail)) {
            $this->orderDetails[] = $orderDetail;
            $orderDetail->setMyOrder($this);
        }

        return $this;
    }

    public function removeOrderDetail(OrderDetails $orderDetail): self
    {
        if ($this->orderDetails->removeElement($orderDetail)) {
            // set the owning side to null (unless already changed)
            if ($orderDetail->getMyOrder() === $this) {
                $orderDetail->setMyOrder(null);
            }
        }

        return $this;
    }

    public function getPaymentChoice(): ?string
    {
        return $this->paymentChoice;
    }

    public function setPaymentChoice(string $paymentChoice): self
    {
        $this->paymentChoice = $paymentChoice;

        return $this;
    }

    public function getIsPaid(): ?bool
    {
        return $this->isPaid;
    }

    public function setIsPaid(bool $isPaid): self
    {
        $this->isPaid = $isPaid;

        return $this;
    }

    public function getStatus(): ?Status
    {
        return $this->status;
    }

    public function setStatus(?Status $status): self
    {
        $this->status = $status;

        return $this;
    }
    public function getTotalOrder(){
        $total=0;
        foreach ($this->getOrderDetails()->getValues() as $product) {
            $total = $total + ($product->getPrice()*$product->getQuantity()*$product->getTva());
        }
        return $total;
    }
}
