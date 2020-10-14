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

class DocumentsController extends AbstractController
{
    private $modelDocPath = "/documents/";  // Chemin racine vers tous les modèles documents
    private $exampleDocPath = "pdf-examples/"; // Chemin racine vers tous les exemples de document

    private $articleFacade;
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
        $userDoc->setCreatedAt(new \DateTime());

        $em->persist($userDoc);
        $em->flush();

        return $this->redirectToRoute("users_index", ["userName" => $this->getUser()->getUserLogin()]);
    }

    /**
     * @Route("/documents/fill/{id}/", name="fill_document", methods={"GET", "POST"})
     */
    public function fillDocument(DocumentsRepository $docRepo, UserDocumentsRepository $userDocRepo, Request $request, int $id)
    {
        // Appelle l'entité et le formulaire dynamiquement en fonction du document en cours d'édition
            $currentDoc = $docRepo->findBy(["id" => $id]);
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
                $htmlContent = $this->render($this->modelDocPath.$currentDoc[0]->getDocModelName().".html.twig", [
                    "docParams" => $form->getData(),
                ])->getContent();

                $pdf = new Pdf($htmlContent);

    //            if (!$pdf->saveAs("C:/symfony/artis_concillium/public/userdocuments/".$currentDoc[0]->getDocModelName().".pdf")) {
     //               $pdf->getError();
       //             $this->redirectToRoute('fill_document', ["id" => $id]);
         //       }

                dd($userDocRepo->findFirstNonFilledDoc());
                $this->addDocumentToUser();

            }

            return $this->render('documents/fillDocument.html.twig', [
                'form' => $form->createView(),
            ]);
    }

}
