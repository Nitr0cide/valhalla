<?php


namespace App\Entity\Documents;


class autorisationdomiciliation
{
    private $physicOrLegal;

    private $nom;

    private $prenom;

    private $ville;

    private $departement;

    /**
     * @return mixed
     */
    public function getPhysicOrLegal()
    {
        return $this->physicOrLegal;
    }

    /**
     * @param mixed $physicOrLegal
     */
    public function setPhysicOrLegal($physicOrLegal): void
    {
        $this->physicOrLegal = $physicOrLegal;
    }

    /**
     * @return mixed
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * @param mixed $nom
     */
    public function setNom($nom): void
    {
        $this->nom = $nom;
    }

    /**
     * @return mixed
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * @param mixed $prenom
     */
    public function setPrenom($prenom): void
    {
        $this->prenom = $prenom;
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
     */
    public function setVille($ville): void
    {
        $this->ville = $ville;
    }

    /**
     * @return mixed
     */
    public function getDepartement()
    {
        return $this->departement;
    }

    /**
     * @param mixed $departement
     */
    public function setDepartement($departement): void
    {
        $this->departement = $departement;
    }
}