<?php

namespace App\Controller;

use App\Entity\Factures;
use App\Form\FacturesType;
use App\Repository\FacturesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/factures")
 */
class FacturesController extends AbstractController
{

    /**
     * @Route("/", name="factures_index", methods={"GET"})
     */
    public function index(FacturesRepository $facturesRepository): Response
    {
        return $this->render('factures/index.html.twig', [
            'factures' => $facturesRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="factures_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
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
     * @Route("/{id}", name="factures_show", methods={"GET"})
     */
    public function show(Factures $facture): Response
    {
        return $this->render('factures/show.html.twig', [
            'facture' => $facture,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="factures_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Factures $facture): Response
    {
        $form = $this->createForm(FacturesType::class, $facture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('factures_index');
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



    public function calculatePrice()
    {
        if (empty($this->prix_ht))
        {
            $this->prix_ht = $this->prix_ttc * ($this->tva/100);
        }
        if (empty($this->prix_ttc))
        {
            $this->prix_ttc = $this->prix_ht / (1-($this->tva/100));
        }

    }

    public function calculateCoutTva(): int
    {
        $coutTva = $this->prix_ttc - $this->prix_ht;
        $this->setCoutTVA($coutTva);
    }
}
