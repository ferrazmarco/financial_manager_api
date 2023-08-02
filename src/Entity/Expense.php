<?php

namespace App\Entity;

use App\Repository\ExpenseRepository;
use Symfony\Component\Serializer\Annotation\Ignore;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

// TODO: Adicionar atributo sharedWith para determinar quais usuários do grupo vao compartilhar as despesas
#[ORM\Entity(repositoryClass: ExpenseRepository::class)]
class Expense
{
    #[Groups(['main'])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(['main'])]
    #[ORM\Column]
    private ?int $value = null;

    #[Groups(['main'])]
    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[Groups(['main'])]
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $createdBy = null;

    #[Gedmo\Timestampable]
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $created = null;

    #[Ignore]
    #[ORM\ManyToOne(inversedBy: 'expenses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Group $group_ = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValue(): ?float
    {
        return ($this->value / 100);
    }

    // salvando o valor em centavos pra evitar 
    // problemas de arrendondamento
    public function setValue(float $value): static
    {
        $this->value = $value * 100;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getCreatedBy(): User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?User $createdBy): static
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(\DateTimeInterface $created): static
    {
        $this->created = $created;

        return $this;
    }

    public function getGroup(): ?Group
    {
        return $this->group_;
    }

    public function setGroup(?Group $group_): static
    {
        $this->group_ = $group_;

        return $this;
    }
}
