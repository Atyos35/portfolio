<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 8)]
    private ?string $firstname = null;

    #[ORM\Column(length: 7)]
    private ?string $lastname = null;

    #[ORM\Column(length: 30)]
    private ?string $job = null;

    #[ORM\Column(length: 47)]
    private ?string $linkedin = null;

    #[ORM\Column(length: 28)]
    private ?string $email = null;

    #[ORM\Column(length: 6)]
    private ?string $age = null;

    #[ORM\Column(length: 6)]
    private ?string $city = null;

    #[ORM\Column]
    private ?int $phone = null;

    /**
     * @var Collection<int, Experience>
     */
    #[ORM\OneToMany(targetEntity: Experience::class, mappedBy: '_user', orphanRemoval: true)]
    private Collection $Experiences;

    /**
     * @var Collection<int, Training>
     */
    #[ORM\OneToMany(targetEntity: Training::class, mappedBy: '_user', orphanRemoval: true)]
    private Collection $trainings;

    /**
     * @var Collection<int, Framework>
     */
    #[ORM\OneToMany(targetEntity: Framework::class, mappedBy: '_user', orphanRemoval: true)]
    private Collection $frameworks;

    /**
     * @var Collection<int, Software>
     */
    #[ORM\OneToMany(targetEntity: Software::class, mappedBy: '_user')]
    private Collection $softwares;

    /**
     * @var Collection<int, Library>
     */
    #[ORM\OneToMany(targetEntity: Library::class, mappedBy: '_user', orphanRemoval: true)]
    private Collection $libraries;

    /**
     * @var Collection<int, Database>
     */
    #[ORM\OneToMany(targetEntity: Database::class, mappedBy: '_user', orphanRemoval: true)]
    private Collection $databases;

    /**
     * @var Collection<int, OperatingSystem>
     */
    #[ORM\OneToMany(targetEntity: OperatingSystem::class, mappedBy: '_user', orphanRemoval: true)]
    private Collection $operatingSystems;

    /**
     * @var Collection<int, Language>
     */
    #[ORM\OneToMany(targetEntity: Language::class, mappedBy: '_user', orphanRemoval: true)]
    private Collection $languages;

    /**
     * @var Collection<int, Hobby>
     */
    #[ORM\OneToMany(targetEntity: Hobby::class, mappedBy: '_user', orphanRemoval: true)]
    private Collection $hobbies;

    public function __construct()
    {
        $this->Experiences = new ArrayCollection();
        $this->trainings = new ArrayCollection();
        $this->frameworks = new ArrayCollection();
        $this->softwares = new ArrayCollection();
        $this->libraries = new ArrayCollection();
        $this->databases = new ArrayCollection();
        $this->operatingSystems = new ArrayCollection();
        $this->languages = new ArrayCollection();
        $this->hobbies = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getJob(): ?string
    {
        return $this->job;
    }

    public function setJob(string $job): static
    {
        $this->job = $job;

        return $this;
    }

    public function getLinkedin(): ?string
    {
        return $this->linkedin;
    }

    public function setLinkedin(string $linkedin): static
    {
        $this->linkedin = $linkedin;

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

    public function getAge(): ?string
    {
        return $this->age;
    }

    public function setAge(string $age): static
    {
        $this->age = $age;

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

    public function getPhone(): ?int
    {
        return $this->phone;
    }

    public function setPhone(int $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return Collection<int, Experience>
     */
    public function getExperiences(): Collection
    {
        return $this->Experiences;
    }

    public function addExperience(Experience $experience): static
    {
        if (!$this->Experiences->contains($experience)) {
            $this->Experiences->add($experience);
            $experience->setUser($this);
        }

        return $this;
    }

    public function removeExperience(Experience $experience): static
    {
        if ($this->Experiences->removeElement($experience)) {
            // set the owning side to null (unless already changed)
            if ($experience->getUser() === $this) {
                $experience->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Training>
     */
    public function getTrainings(): Collection
    {
        return $this->trainings;
    }

    public function addTraining(Training $training): static
    {
        if (!$this->trainings->contains($training)) {
            $this->trainings->add($training);
            $training->setUser($this);
        }

        return $this;
    }

    public function removeTraining(Training $training): static
    {
        if ($this->trainings->removeElement($training)) {
            // set the owning side to null (unless already changed)
            if ($training->getUser() === $this) {
                $training->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Framework>
     */
    public function getFrameworks(): Collection
    {
        return $this->frameworks;
    }

    public function addFramework(Framework $framework): static
    {
        if (!$this->frameworks->contains($framework)) {
            $this->frameworks->add($framework);
            $framework->setUser($this);
        }

        return $this;
    }

    public function removeFramework(Framework $framework): static
    {
        if ($this->frameworks->removeElement($framework)) {
            // set the owning side to null (unless already changed)
            if ($framework->getUser() === $this) {
                $framework->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Software>
     */
    public function getSoftwares(): Collection
    {
        return $this->softwares;
    }

    public function addSoftware(Software $software): static
    {
        if (!$this->softwares->contains($software)) {
            $this->softwares->add($software);
            $software->setUser($this);
        }

        return $this;
    }

    public function removeSoftware(Software $software): static
    {
        if ($this->softwares->removeElement($software)) {
            // set the owning side to null (unless already changed)
            if ($software->getUser() === $this) {
                $software->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Library>
     */
    public function getLibraries(): Collection
    {
        return $this->libraries;
    }

    public function addLibrary(Library $library): static
    {
        if (!$this->libraries->contains($library)) {
            $this->libraries->add($library);
            $library->setUser($this);
        }

        return $this;
    }

    public function removeLibrary(Library $library): static
    {
        if ($this->libraries->removeElement($library)) {
            // set the owning side to null (unless already changed)
            if ($library->getUser() === $this) {
                $library->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Database>
     */
    public function getDatabases(): Collection
    {
        return $this->databases;
    }

    public function addDatabase(Database $database): static
    {
        if (!$this->databases->contains($database)) {
            $this->databases->add($database);
            $database->setUser($this);
        }

        return $this;
    }

    public function removeDatabase(Database $database): static
    {
        if ($this->databases->removeElement($database)) {
            // set the owning side to null (unless already changed)
            if ($database->getUser() === $this) {
                $database->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, OperatingSystem>
     */
    public function getOperatingSystems(): Collection
    {
        return $this->operatingSystems;
    }

    public function addOperatingSystem(OperatingSystem $operatingSystem): static
    {
        if (!$this->operatingSystems->contains($operatingSystem)) {
            $this->operatingSystems->add($operatingSystem);
            $operatingSystem->setUser($this);
        }

        return $this;
    }

    public function removeOperatingSystem(OperatingSystem $operatingSystem): static
    {
        if ($this->operatingSystems->removeElement($operatingSystem)) {
            // set the owning side to null (unless already changed)
            if ($operatingSystem->getUser() === $this) {
                $operatingSystem->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Language>
     */
    public function getLanguages(): Collection
    {
        return $this->languages;
    }

    public function addLanguage(Language $language): static
    {
        if (!$this->languages->contains($language)) {
            $this->languages->add($language);
            $language->setUser($this);
        }

        return $this;
    }

    public function removeLanguage(Language $language): static
    {
        if ($this->languages->removeElement($language)) {
            // set the owning side to null (unless already changed)
            if ($language->getUser() === $this) {
                $language->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Hobby>
     */
    public function getHobbies(): Collection
    {
        return $this->hobbies;
    }

    public function addHobby(Hobby $hobby): static
    {
        if (!$this->hobbies->contains($hobby)) {
            $this->hobbies->add($hobby);
            $hobby->setUser($this);
        }

        return $this;
    }

    public function removeHobby(Hobby $hobby): static
    {
        if ($this->hobbies->removeElement($hobby)) {
            // set the owning side to null (unless already changed)
            if ($hobby->getUser() === $this) {
                $hobby->setUser(null);
            }
        }

        return $this;
    }
}
