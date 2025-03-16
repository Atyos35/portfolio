<?php

namespace App\Entity;

use App\Repository\ExperienceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ExperienceRepository::class)]
class Experience
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 42)]
    private ?string $name = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $start_date = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $end_date = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    /**
     * @var Collection<int, OperatingSystem>
     */
    #[ORM\ManyToMany(targetEntity: OperatingSystem::class, inversedBy: 'experiences')]
    private Collection $operatingSystem;

    /**
     * @var Collection<int, Database>
     */
    #[ORM\ManyToMany(targetEntity: Database::class, inversedBy: 'experiences')]
    private Collection $database;

    /**
     * @var Collection<int, Library>
     */
    #[ORM\ManyToMany(targetEntity: Library::class, inversedBy: 'experiences')]
    private Collection $library;

    /**
     * @var Collection<int, Software>
     */
    #[ORM\ManyToMany(targetEntity: Software::class, inversedBy: 'experiences')]
    private Collection $software;

    /**
     * @var Collection<int, Framework>
     */
    #[ORM\ManyToMany(targetEntity: Framework::class, inversedBy: 'experiences')]
    private Collection $framework;

    #[ORM\Column]
    private ?\DateInterval $duration = null;

    #[ORM\Column(length: 15)]
    private ?string $city = null;

    #[ORM\Column]
    private ?int $zipCode = null;

    #[ORM\ManyToOne(inversedBy: 'experiences')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Company $company = null;

    #[ORM\ManyToOne(inversedBy: 'Experiences')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $_user = null;

    public function __construct()
    {
        $this->operatingSystem = new ArrayCollection();
        $this->database = new ArrayCollection();
        $this->library = new ArrayCollection();
        $this->software = new ArrayCollection();
        $this->framework = new ArrayCollection();
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

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->start_date;
    }

    public function setStartDate(\DateTimeInterface $start_date): static
    {
        $this->start_date = $start_date;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->end_date;
    }

    public function setEndDate(\DateTimeInterface $end_date): static
    {
        $this->end_date = $end_date;

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
     * @return Collection<int, OperatingSystem>
     */
    public function getOperatingSystem(): Collection
    {
        return $this->operatingSystem;
    }

    public function addOperatingSystem(OperatingSystem $operatingSystem): static
    {
        if (!$this->operatingSystem->contains($operatingSystem)) {
            $this->operatingSystem->add($operatingSystem);
        }

        return $this;
    }

    public function removeOperatingSystem(OperatingSystem $operatingSystem): static
    {
        $this->operatingSystem->removeElement($operatingSystem);

        return $this;
    }

    /**
     * @return Collection<int, Database>
     */
    public function getDatabase(): Collection
    {
        return $this->database;
    }

    public function addDatabase(Database $database): static
    {
        if (!$this->database->contains($database)) {
            $this->database->add($database);
        }

        return $this;
    }

    public function removeDatabase(Database $database): static
    {
        $this->database->removeElement($database);

        return $this;
    }

    /**
     * @return Collection<int, Library>
     */
    public function getLibrary(): Collection
    {
        return $this->library;
    }

    public function addLibrary(Library $library): static
    {
        if (!$this->library->contains($library)) {
            $this->library->add($library);
        }

        return $this;
    }

    public function removeLibrary(Library $library): static
    {
        $this->library->removeElement($library);

        return $this;
    }

    /**
     * @return Collection<int, Software>
     */
    public function getSoftware(): Collection
    {
        return $this->software;
    }

    public function addSoftware(Software $software): static
    {
        if (!$this->software->contains($software)) {
            $this->software->add($software);
        }

        return $this;
    }

    public function removeSoftware(Software $software): static
    {
        $this->software->removeElement($software);

        return $this;
    }

    /**
     * @return Collection<int, Framework>
     */
    public function getFramework(): Collection
    {
        return $this->framework;
    }

    public function addFramework(Framework $framework): static
    {
        if (!$this->framework->contains($framework)) {
            $this->framework->add($framework);
        }

        return $this;
    }

    public function removeFramework(Framework $framework): static
    {
        $this->framework->removeElement($framework);

        return $this;
    }

    public function getDuration(): ?\DateInterval
    {
        return $this->duration;
    }

    public function setDuration(\DateInterval $duration): static
    {
        $this->duration = $duration;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getZipCode(): ?int
    {
        return $this->zipCode;
    }

    public function setZipCode(int $zipCode): static
    {
        $this->zipCode = $zipCode;

        return $this;
    }

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(?Company $company): static
    {
        $this->company = $company;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->_user;
    }

    public function setUser(?User $_user): static
    {
        $this->_user = $_user;

        return $this;
    }
}
