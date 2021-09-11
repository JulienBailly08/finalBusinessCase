<?php

namespace App\Entity;

use App\Repository\CommandRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommandRepository::class)
 */
class Command
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $orderDate;

    /**
     * @ORM\OneToMany(targetEntity=QuantityOnCommand::class, mappedBy="command")
     */
    private $quantityOnCommands;

    /**
     * @ORM\ManyToOne(targetEntity=Status::class, inversedBy="commands")
     * @ORM\JoinColumn(nullable=false)
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="commands")
     * @ORM\JoinColumn(nullable=false)
     */
    private $client;

    /**
     * @ORM\Column(type="boolean")
     */
    private $ClickAndCollect;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $payment;

    public function __construct()
    {
        $this->quantityOnCommands = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrderDate(): ?\DateTimeInterface
    {
        return $this->orderDate;
    }

    public function setOrderDate(\DateTimeInterface $orderDate): self
    {
        $this->orderDate = $orderDate;

        return $this;
    }

    /**
     * @return Collection|QuantityOnCommand[]
     */
    public function getQuantityOnCommands(): Collection
    {
        return $this->quantityOnCommands;
    }

    public function addQuantityOnCommand(QuantityOnCommand $quantityOnCommand): self
    {
        if (!$this->quantityOnCommands->contains($quantityOnCommand)) {
            $this->quantityOnCommands[] = $quantityOnCommand;
            $quantityOnCommand->setCommand($this);
        }

        return $this;
    }

    public function removeQuantityOnCommand(QuantityOnCommand $quantityOnCommand): self
    {
        if ($this->quantityOnCommands->removeElement($quantityOnCommand)) {
            // set the owning side to null (unless already changed)
            if ($quantityOnCommand->getCommand() === $this) {
                $quantityOnCommand->setCommand(null);
            }
        }

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

    public function getClient(): ?User
    {
        return $this->client;
    }

    public function setClient(?User $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getClickAndCollect(): ?bool
    {
        return $this->ClickAndCollect;
    }

    public function setClickAndCollect(bool $ClickAndCollect): self
    {
        $this->ClickAndCollect = $ClickAndCollect;

        return $this;
    }

    public function getPayment(): ?string
    {
        return $this->payment;
    }

    public function setPayment(string $payment): self
    {
        $this->payment = $payment;

        return $this;
    }
}
