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
}