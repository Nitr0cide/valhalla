<?php

namespace App\Controller;

use App\Entity\Documents\ventehabitation;
use App\Form\Documents\ventehabitationType;
use App\Entity\UserDocuments;
use App\Entity\Documents\contratdetravail;
use App\Form\Documents\contratdetravailType;
use App\Repository\CompaniesRepository;
use App\Repository\DocumentsRepository;
use App\Repository\UserDocumentsRepository;
use Couchbase\Document;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use mikehaertl\wkhtmlto\Pdf;
use App\Controller\DocumentsController;
use DateTimeZone;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class DocumentsController extends AbstractController
{
    private $modelDocPath = "/documents/";  // Chemin racine vers tous les modèles documents
    private $exampleDocPath = "pdf-examples/"; // Chemin racine vers tous les exemples de document
    private $pdfListPath = "/public/userdocuments/";
    private $currentDocPath = "";

    private $articleFacade;

    public function __construct()
    {

    }

    /**
     * @Route("/documents/tous-les-documents", name="all_documents")
     */
    public function showAllDocs(DocumentsRepository $docRepo) :Response
    {
        $docs = $docRepo->findAll();

        return $this->render("$this->modelDocPath"."allDocuments.html.twig", [
            "docs" => $docs,
        ]);
    }

    /**
     * @Route("/documents/{modelid}", name="show_preview", methods={"POST", "GET"})
     */
    public function showPreview(DocumentsRepository $docRepo, string $modelid)
    {
        // Chaque exemple de document dans /public/pdf-examples/ doit s'appeler comme dans le champ (doc_model_name) de la DB
        $doc = $docRepo->findBy(["id" => $modelid]);

    //    $htmlPdfPreview = $this->render($this->modelDocPath.$doc[0]->getDocModelName().".html.twig")->getContent();

        $pdfPreviewUrlPath = $this->exampleDocPath.$doc[0]->getDocModelName().".pdf";


        return $this->render('documents/preview.html.twig', [
            "document" => $doc,
            "pdf_url" => file_exists($pdfPreviewUrlPath) ? $pdfPreviewUrlPath : false,
        ]);
    }

    public function showDocsByCategory()
    {

    }

    public function showDocsByCompanyType()
    {

    }

    /**
     * @Route("/documents/pay/{id}", name="pay_document", methods={"POST"})
     */
    public function payDocument (DocumentsRepository $docRepo, CompaniesRepository $compRepo, int $id)
    {
        // On lie un document au compte utilisateur, il sera en attente de traitement (voir fillDocument)
        $userDoc = new UserDocuments();
        $docPurchased = $docRepo->findBy(["id" => $id]);

        $em = $this->getDoctrine()->getManager();

        $userDoc->setDocument($docPurchased[0]);
        $userDoc->setUser($this->getUser());
        $userDoc->setCreatedAt(new \DateTime('now', new DateTimeZone('Europe/Paris')));

        $em->persist($userDoc);
        $em->flush();

        return $this->redirectToRoute("users_index", ["userName" => $this->getUser()->getUserLogin()]);
    }

    /**
     * @Route("/documents/fill/{id}/", name="fill_document", methods={"GET", "POST"})
     */
    public function fillDocument(DocumentsRepository $docRepo, UserDocumentsRepository $userDocRepo, Request $request, UserPasswordEncoderInterface $encoder, int $id)
    {
            $currentDoc = $docRepo->findBy(["id" => $id]);

        // Si le document a bien été acheté par l'utilisateur et qu'il n'a pas encore modifié (generated_pdf = null) on lui affiche le formulaire
            $userDocRepo->findOneBy(["user" => $this->getUser()->getId(), "document" => $id, "generated_pdf" => null]);

        // Appelle l'entité et le formulaire dynamiquement en fonction du document en cours d'édition

            $this->currentDocPath = $this->pdfListPath.$this->getUser()->getUserLogin()."/".$currentDoc[0]->getDocModelName();

            $name = $currentDoc[0]->getDocModelName();
            $docEntity= str_replace("_", "", $name);
            $formEntity = "App\\Form\\Documents\\".$docEntity."Type";
            $docEntity = "App\\Entity\\Documents\\".$docEntity;

            $document = new $docEntity();

            // create a form but with a request object instead of entity
            $form = $this->createForm($formEntity, $document, [
                "method" => "POST",
            ], "");

            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid())
            {
                $date = new \DateTime('now', new DateTimeZone('Europe/Paris'));

                $htmlContent = $this->render($this->modelDocPath.$currentDoc[0]->getDocModelName().".html.twig", [
                    "docParams" => $form->getData(),
                ])->getContent();

                $pdf = new Pdf($htmlContent);

               // Cette requête sert à lui faire modifier la première ligne que l'on trouvera dans userDocuments
                // qui correspond au document qu'il veut modifier (dans le cas où l'utilisateur a acheté plusieurs fois le même document
                // en avoir rempli un seul
                $result = $userDocRepo->findFirstNonFilledDoc($id, $this->getUser()->getId());
               // On rajoute le chemin du pdf qu'on a généré à la ligne correspondante (id récupéré dans la requête précédente)
                if (!$this->addPdfToUser($result[0]->getId(), $userDocRepo, $date))
                {
                    return $this->redirectToRoute('users_index', ["userName" => $this->getUser()->getUserLogin()]);
                }


                // On enregistre le fichier avec le timestamp contenu en base de données
                if (!$pdf->saveAs("C:/symfony/artis_concillium".$this->currentDocPath.$date->format("YmdHis").".pdf")) {
                    $pdf->getError();
                    $this->redirectToRoute('fill_document', ["id" => $id]);
                }
            }

            return $this->render('documents/fillDocument.html.twig', [
                'form' => $form->createView(),
            ]);
    }

    /**
     * @param $id
     * @param $userLogin
     * @param UserDocumentsRepository $userDocRepo
     */
    private function addPdfToUser($id, UserDocumentsRepository $userDocRepo, $timestamp)
    {
        if(!$userDocRepo->addPdfToRow($id, $this->currentDocPath, $timestamp))
        {
            return false;
        }
        return true;
    }
}
