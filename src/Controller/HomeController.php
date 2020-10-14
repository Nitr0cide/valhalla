<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use mikehaertl\wkhtmlto\Pdf;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {

 //       $content = $this->render('home/index.html.twig', [
        //           'controller_name' => "controller",
  //       ]);
        //
        //       $pdf = new Pdf();
        //      $pdf->addPage((string)$content);
        //
        //     if (!$pdf->send()) {
        //        dd($pdf->getError());
        //     }

        return $this->render('home/index.html.twig', []);
    }
}
