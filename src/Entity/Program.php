<?php

namespace App\Entity;

use App\Repository\ProgramRepository;
use Doctrine\ORM\Mapping as ORM;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints\DateTime;

use Symfony\Component\HttpFoundation\File\File;
//Ici on importe le package Vich, que l’on utilisera sous l’alias “Vich”
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=ProgramRepository::class)
 * @UniqueEntity(
 *     fields={"title"},
 *     errorPath="title",
 *     message="ce titre existe déjà."
 * )
 * @Vich\Uploadable
 */
class Program
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="ne me laisse pas tout vide")
     * @Assert\Length(max="255", maxMessage="La catégorie saisie {{ value }} est trop longue, elle ne devrait pas dépasser {{ limit }} caractères")
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="ne me laisse pas tout vide")
     * @Assert\Regex(
     * pattern="/plus belle la vie/",
     * match=false,
     * message="on parle de vraies séries ici")
     */
    private $summary;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var string
     */
    private $poster;

    //On va créer un nouvel attribut à notre entité, qui ne sera pas lié à une colonne
    // Tu peux d’ailleurs voir que l’annotation ORM column n’est pas spécifiée car
    //On ne rajoute pas de données de type file en bdd
    
    /**
    * @Vich\UploadableField(mapping="poster_file", fileNameProperty="poster, nullable=true")
    * @var File
    */
    private $posterFile;


    /**
    * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="programs")
     * @ORM\JoinColumn(nullable=true)
     */
    private $category;

    /**
     * @ORM\OneToMany(targetEntity=Season::class, mappedBy="program", orphanRemoval=true)
     */
    private $season_id;

    /**
     * @ORM\ManyToMany(targetEntity=Actor::class, mappedBy="programs")
     */
    private $actors;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @var Datetime
     */
    private $updatedAt;

    public function __construct()
    {
        $this->season_id = new ArrayCollection();
        $this->actors = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getSummary(): ?string
    {
        return $this->summary;
    }

    public function setSummary(string $summary): self
    {
        $this->summary = $summary;

        return $this;
    }

    public function getPoster(): ?string
    {
        return $this->poster;
    }

    public function setPoster(?string $poster): self
    {
        $this->poster = $poster;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection|Season[]
     */
    public function getSeasonId(): Collection
    {
        return $this->season_id;
    }

    public function addSeasonId(Season $seasonId): self
    {
        if (!$this->season_id->contains($seasonId)) {
            $this->season_id[] = $seasonId;
            $seasonId->setProgram($this);
        }

        return $this;
    }

    public function removeSeasonId(Season $seasonId): self
    {
        if ($this->season_id->removeElement($seasonId)) {
            // set the owning side to null (unless already changed)
            if ($seasonId->getProgram() === $this) {
                $seasonId->setProgram(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Actor[]
     */
    public function getActors(): Collection
    {
        return $this->actors;
    }

    public function addActor(Actor $actor): self
    {
        if (!$this->actors->contains($actor)) {
            $this->actors[] = $actor;
            $actor->addProgram($this);
        }

        return $this;
    }

    public function removeActor(Actor $actor): self
    {
        if ($this->actors->removeElement($actor)) {
            $actor->removeProgram($this);
        }

        return $this;
    }

    public function getPosterFile(): ?File
    {
        return $this->posterFile;
    }

    public function setPosterFile(File $image = null)
    {
        $this->imageFile = $image;
        if ($image) {
        $this->updatedAt = new DateTime('now');
        }
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
