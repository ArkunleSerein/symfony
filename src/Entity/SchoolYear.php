<?php

namespace App\Entity;

use App\Repository\SchoolYearRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SchoolYearRepository::class)]
#[UniqueEntity('name')] // contrainte d'unicité.
class SchoolYear
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank]
    #[Assert\Length(
        min: 3,
        max: 191,
    )]
    #[ORM\Column(length: 191)]
    private ?string $name = null;

    #[Assert\Length(max: 1000)]
    #[ORM\Column(
        type: Types::TEXT,
        nullable: true
    )]
    private ?string $description = null;

    #[ORM\Column(
        type: Types::DATE_MUTABLE,
        nullable: true
    )]
    private ?\DateTimeInterface $startDate = null;

    #[Assert\GreaterThan(propertyPath: 'startDate')]
    #[ORM\Column(
        type: Types::DATE_MUTABLE,
        nullable: true
    )]
    private ?\DateTimeInterface $endDate = null;

    #[ORM\OneToMany(
        mappedBy: 'schoolYear',
        targetEntity: Student::class
    )]
    private Collection $students;

    public function __construct()
    {
        $this->students = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(?\DateTimeInterface $startDate): static
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(?\DateTimeInterface $endDate): static
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * @return Collection<int, Student>
     */
    public function getStudents(): Collection
    {
        return $this->students;
    }

    public function addStudent(Student $student): static
    {
        if (!$this->students->contains($student)) {
            $this->students->add($student);
            $student->setSchoolYear($this);
        }

        return $this;
    }

    public function removeStudent(Student $student): static
    {
        if ($this->students->removeElement($student)) {
            // set the owning side to null (unless already changed)
            if ($student->getSchoolYear() === $this) {
                $student->setSchoolYear(null);
            }
        }

        return $this;
    }
    public function __toString()
    {
        return "{$this->getName()} (id {$this->getId()})";
    }
}
