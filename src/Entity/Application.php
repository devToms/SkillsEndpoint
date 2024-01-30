<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\GetCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Controller\NewApplicationsController;
use App\Controller\ReadApplicationsController;
use App\Repository\ApplicationRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;

#[ORM\Entity(repositoryClass: ApplicationRepository::class)]
#[Index(name: "id_idx", fields: ["id"])]
#[ApiResource(
    normalizationContext: ['groups' => ['read']],
    denormalizationContext: ['groups' => ['write']],
    operations: [
        new Post(
            uriTemplate: '/application/create'
        ),
        new Get(
            uriTemplate: '/application/{id}',
            requirements: ['id' => '\d+'],
            cacheHeaders: [
                'max_age' => 60,  // 1 minuta
                'shared_max_age' => 120  // 2 minuty
            ]
        ),
        new Put(),
        new Delete()
    ]
)]
#[ApiResource(
    operations: [
        new GetCollection(
            uriTemplate: '/application/new-aplications',
            controller: NewApplicationsController::class,
            cacheHeaders: [
                'max_age' => 3600,  // 1 godzina
                'shared_max_age' => 7200  // 2 godziny
            ]
        ),
    ]
)]
#[ApiResource(
    operations: [
        new GetCollection(
            uriTemplate: '/application/read-apliactions',
            controller: ReadApplicationsController::class,
            cacheHeaders: [
                'max_age' => 86400,  // 1 dzień
                'shared_max_age' => 604800  // 7 dni
            ]
        ),
    ]
)]
#[ApiFilter(OrderFilter::class, properties: [
  'id',
  'firstName',
  'lastName',
  'email',
  'phoneNumber',
  'expectedSalary',
  'position',
  'level',
  'isRead',
  ])]
class Application
{
    // use ApplicationValidationTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read', 'write'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Groups(['read', 'write'])]
    private ?string $firstName = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Groups(['read', 'write'])]
    private ?string $lastName = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Groups(['read', 'write'])]
    private ?string $email = null;

    #[ORM\Column(length: 15)]
    #[Assert\NotBlank]
    #[Assert\Regex('/^[0-9]+$/')]
    #[Groups(['read', 'write'])]
    private ?string $phoneNumber = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Groups(['read', 'write'])]
    private ?int $expectedSalary = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Groups(['read', 'write'])]
    private ?string $position = null;

    #[ORM\Column(length: 7)]
    #[Groups(['read'])]
    private ?string $level = null;

    #[Groups(['read'])]
    #[ORM\Column(type: 'boolean')]
    private ?bool $isRead = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): static
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getExpectedSalary(): ?int
    {
        return $this->expectedSalary;
    }

    public function setExpectedSalary(int $expectedSalary): static
    {
        $this->expectedSalary = $expectedSalary;

        // Automatyczne ustawienie poziomu na podstawie oczekiwanego wynagrodzenia
        if ($expectedSalary < 5000) {
            $this->level = 'junior';
        } elseif ($expectedSalary >= 5000 && $expectedSalary <= 9999) {
            $this->level = 'regular';
        } else {
            $this->level = 'senior';
        }

        return $this;
    }

    public function getPosition(): ?string
    {
        return $this->position;
    }

    public function setPosition(string $position): static
    {
        $this->position = $position;

        return $this;
    }

    public function getLevel(): ?string
    {
        return $this->level;
    }

    public function setLevel(string $level): static
    {
        $this->level = $level;

        return $this;
    }

    public function getIsRead(): ?bool
    {
        return $this->isRead;
    }

    public function setIsRead(?bool $isRead): static
    {
        $this->isRead = $isRead;

        return $this;
    }
}
