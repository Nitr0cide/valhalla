<?php

namespace App\Controller;

use App\Entity\Factures;
use App\Form\FacturesType;
use App\Repository\FacturesRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;


class FacturesController extends AbstractController
{

    /**
     * @Route("/factures", name="factures_index", methods={"GET"})
     */
    public function index(FacturesRepository $facturesRepository): Response
    {
        return $this->render('factures/index.html.twig', [
            'factures' => $facturesRepository->findBy(["user" => $this->getUser()->getId()]),
        ]);
    }

    /**
     * @Route("/factures/new", name="factures_new", methods={"GET","POST"})
     */
    public function new(Request $request, SluggerInterface $slugger): Response
    {
        $facture = new Factures();
        $form = $this->createForm(FacturesType::class, $facture, [], "");
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            if ($form->get('prix_ht')->getData() === null){
                $prixht = (1 - ($form->get('tva')->getData() / 100)) * $form->get('prix_ttc')->getData();
                $facture->setPrixHt($prixht);
            }
            if ($form->get('prix_ttc')->getData() === null){
                $prixttc = $form->get('prix_ht')->getData() / (1 - ($form->get('tva')->getData() / 100 ));
                $facture->setPrixTtc($prixttc);
            }
            $facture->setUser($this->getUser());

            // Gestion de l'upload de fichier
            $factureFile = $form->get('file_path')->getData();

            $originalFilename = pathinfo($factureFile->getClientOriginalName(), PATHINFO_FILENAME);
            // this is needed to safely include the file name as part of the URL
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename.'-'.uniqid().'.'.$factureFile->guessExtension();

            $this->uploadFile($factureFile, $newFilename);

            $facture->setFilePath($newFilename);

            $entityManager->persist($facture);
            $entityManager->flush();

            return $this->redirectToRoute('factures_index');
        }

        return $this->render('factures/new.html.twig', [
            'facture' => $facture,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/factures/{id}", name="factures_show", methods={"GET"})
     */
    public function show(Factures $facture): Response
    {
        return $this->render('factures/show.html.twig', [
            'facture' => $facture,
        ]);
    }

    public function uploadFile($file, $fileName)
    {
        try {
            $file->move(
                "userdocuments/".$this->getUser()->getUserLogin()."/factures/",
                $fileName
            );
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }
    }

    /**
     * @Route("/{id}/edit", name="factures_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Factures $facture, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(FacturesType::class, $facture, [], "");
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $factureFile = $form['file_path']->getData();

            if ($factureFile) {
                $originalFilename = pathinfo($factureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$factureFile->guessExtension();

                $this->uploadFile($factureFile, $newFilename);

                $facture->setFilePath($newFilename);

                $this->getDoctrine()->getManager()->flush();

                return $this->redirectToRoute('factures_index');
            }
        }

        return $this->render('factures/edit.html.twig', [
            'facture' => $facture,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="factures_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Factures $facture): Response
    {
        if ($this->isCsrfTokenValid('delete'.$facture->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($facture);
            $entityManager->flush();
        }

        return $this->redirectToRoute('factures_index');
    }

    /**
     * @Route("/factures/ajaxrequest/", name="search_by_name", methods={"POST"})
     */
    public function search(FacturesRepository $fr, Request $request): Response
    {
        $request->get('date1') !== "" ? $date1 = new \DateTime($request->get('date1')) : $date1 = null;
        $request->get('date2') !== "" ? $date2 = new \DateTime($request->get('date2')) : $date2 = null;
        $request->get('clientName') !== "" ? $name = $request->get("clientName") : $name = null;

        if ($date1 && $date2 && $name) {
            $factures = $fr->searchByNameBetweenDates($this->getUser()->getId(), $name, $date1, $date2);
        } elseif ($date1 && $date2 && !$name){
            $factures = $fr->searchBetweenDates($this->getUser()->getId(), $date1, $date2);
        } elseif ($date1 && $name && !$date2) {
            $factures = $fr->searchByNameAndDate($this->getUser()->getId(), $name, $date1);
        } elseif (!$name && $date1){
            $factures = $fr->searchByDate($this->getUser()->getId(), $date1);
        } elseif ($name && (!$date1 && !$date2)) {
            $factures = $fr->searchByName($name, $this->getUser()->getId());
        }

        return $this->render("factures/ajaxresponse.html.twig", [
            'factures' => $factures
            ]
        );
    }


}
