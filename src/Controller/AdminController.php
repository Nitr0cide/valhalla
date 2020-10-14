<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\HttpClientInterface;


class AdminController extends AbstractController
{

    public function construct()
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
    }
    /**
     * @Route("/admin", name="admin_dashboard")
     */
    public function index()
    {

        return $this->render('admin/adminDashboard.html.twig', []);
    }

    /**
     * @Route("/admin/info-entreprise", name="entreprise_seeker")
     */
    public function gouvAPI(Request $request, HttpClientInterface $client, $decode = true)
    {
        if ($request->getMethod() === "POST") {
            $siren = $request->get('siren');
            $siren = str_replace(" ", "", $siren);

            $apiUris = ["entreprise_siret" => "https://entreprise.data.gouv.fr/api/sirene/v3/etablissements/",  // SIRET SEARCH
                "rncs_siren" => "https://entreprise.data.gouv.fr/api/rncs/v1/fiches_identite/", // SIREN search RNCS
                "entreprise_siren" => "https://entreprise.data.gouv.fr/api/sirene/v3/unites_legales/",  // SIREN search
                "asso_siret" => "https://entreprise.data.gouv.fr/api/rna/v1/siret/", // ASSOCIATION
                "asso_rna" => "https://entreprise.data.gouv.fr/api/rna/v1/id/", // association
            ];

            foreach ($apiUris as $key => $uri)
            {
                if ($client->request('GET', "$uri"."$siren")->getStatusCode() >= 400) {
                    continue;
                } else {
                    if ($decode) {
                        $response[] = json_decode($client->request('GET', "$uri"."$siren")->getContent(), true);
                    }
                }
            }


            $newresponse = call_user_func_array('array_merge', $response);
            $response = call_user_func_array('array_merge', $newresponse);
        }

        return $this->render('admin/entreprise-seeker.html.twig', [
            'controller_name' => 'AdminController',
            'entite' => isset($response) ? $response : null,
        ]);
    }
}
