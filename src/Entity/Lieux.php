<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LieuxRepository")
 */
class Lieux
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
    private $nom_lieu;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     * @Assert\Length(min="2" , max="30",
     *     minMessage="2 caractères minimum SVP!!",
     *     maxMessage="30 caractères maximun SVP!!")
     */
    private $rue;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $latitude;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $longitude;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Villes",cascade={"remove"})
     */
    private $ville;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return Lieux
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNomLieu()
    {
        return $this->nom_lieu;
    }

    /**
     * @param mixed $nom_lieu
     * @return Lieux
     */
    public function setNomLieu($nom_lieu)
    {
        $this->nom_lieu = $nom_lieu;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRue()
    {
        return $this->rue;
    }

    /**
     * @param mixed $rue
     * @return Lieux
     */
    public function setRue($rue)
    {
        $this->rue = $rue;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @param mixed $latitude
     * @return Lieux
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @param mixed $longitude
     * @return Lieux
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getVille()
    {
        return $this->ville;
    }

    /**
     * @param mixed $ville
     * @return Lieux
     */
    public function setVille($ville)
    {
        $this->ville = $ville;
        return $this;
    }



}
