<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SortiesRepository")
 */
class Sorties
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=30)
     * @Assert\Length(min="2" , max="30",
     *     minMessage="2 caractères minimum SVP!!",
     *     maxMessage="30 caractères maximun SVP!!")
     */
    private $nom_sortie;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\DateTime
     * @var string A "d-m-Y H:i:s" formatted value
     * @Assert\GreaterThanOrEqual("today", message ="Veuillez indiquer une date supérieure ou égale à aujourd'hui.")
     *
     */
    private $datedebut;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $duree;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\DateTime
     * @var string A "d-m-Y H:i:s" formatted value
     * @Assert\LessThan(propertyPath="datedebut" ,message =" Veuillez indiquer une date antérieure à .")
     */
    private $datecloture;

    /**
     * @ORM\Column(type="integer")
     * @Assert\GreaterThan(value = 2, message = " La sortie doit avoir au moins 2 participants.")
     */
    private $nbinscriptionsmax;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     * @Assert\Length(max="500", maxMessage=" La description comporte trop de caractères, veuillez réduire.")
     */
    private $descriptionsinfos;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\Choice({"Créée", "Ouverte", "Clôturée", "Activité en cours", "Passée", "Annulée"})
     */
    private $etatsortie;

     /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Sites")
     */
    private $site;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Etat")
     */
    private $etat;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Lieux")
     */
    private $lieu;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Participants", mappedBy="sorties")
     */
    private $participants;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Participants")
     */
    private $organisateur;

    /**
     * @return mixed
     */
    public function getOrganisateur()
    {
        return $this->organisateur;
    }

    /**
     * @param mixed $organisateur
     * @return Sorties
     */
    public function setOrganisateur($organisateur)
    {
        $this->organisateur = $organisateur;
        return $this;
    }

    public function __construct()
    {
        $this->participants = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getLieu()
    {
        return $this->lieu;
    }

    /**
     * @param mixed $lieu
     */
    public function setLieu($lieu)
    {
        $this->lieu = $lieu;
    }

    /**
     * @return mixed
     */
    public function getEtat()
    {
        return $this->etat;
    }

    /**
     * @param mixed $etat
     */
    public function setEtat($etat)
    {
        $this->etat = $etat;
    }

    /**
     * @return mixed
     */
    public function getSite()
    {
        return $this->site;
    }

    /**
     * @param mixed $site
     */
    public function setSite($site)
    {
        $this->site = $site;
    }

    public function getId(): ?int
    {
        return $this->id;
    }



    public function getNomSortie(): ?string
    {
        return $this->nom_sortie;
    }

    public function setNomSortie(string $nom_sortie): self
    {
        $this->nom_sortie = $nom_sortie;

        return $this;
    }

    public function getDatedebut(): ?\DateTimeInterface
    {
        return $this->datedebut;
    }

    public function setDatedebut(\DateTimeInterface $datedebut): self
    {
        $this->datedebut = $datedebut;

        return $this;
    }

    public function getDuree(): ?int
    {
        return $this->duree;
    }

    public function setDuree(?int $duree): self
    {
        $this->duree = $duree;

        return $this;
    }

    public function getDatecloture(): ?\DateTimeInterface
    {
        return $this->datecloture;
    }

    public function setDatecloture(\DateTimeInterface $datecloture): self
    {
        $this->datecloture = $datecloture;

        return $this;
    }

    public function getNbinscriptionsmax(): ?int
    {
        return $this->nbinscriptionsmax;
    }

    public function setNbinscriptionsmax(int $nbinscriptionsmax): self
    {
        $this->nbinscriptionsmax = $nbinscriptionsmax;

        return $this;
    }

    public function getDescriptionsinfos(): ?string
    {
        return $this->descriptionsinfos;
    }

    public function setDescriptionsinfos(?string $descriptionsinfos): self
    {
        $this->descriptionsinfos = $descriptionsinfos;

        return $this;
    }

    public function getEtatsortie(): ?int
    {
        return $this->etatsortie;
    }

    public function setEtatsortie(?int $etatsortie): self
    {
        $this->etatsortie = $etatsortie;

        return $this;
    }

    /**
     * @return Collection|Participants[]
     */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    public function addParticipant(Participants $participant): self
    {
        if (!$this->participants->contains($participant)) {
            $this->participants[] = $participant;
            $participant->addSorty($this);
        }

        return $this;
    }

    public function removeParticipant(Participants $participant): self
    {
        if ($this->participants->contains($participant)) {
            $this->participants->removeElement($participant);
            $participant->removeSorty($this);
        }

        return $this;
    }
}
