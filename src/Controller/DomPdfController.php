<?php

namespace App\Controller;

use App\Repository\AdressesRepository;
use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Dompdf\Dompdf;
use Dompdf\Options;
// use Test\Test;


class DomPdfController extends AbstractController
{
    
    #[Route('/dom/pdf/{adresse_id}', name: 'dom_pdf',  methods: ["GET"])]
    public function domPdf( $adresse_id,  OrderRepository $orderRepository)
    {

        // $this->renderView('dom_pdf/index.html.twig', [
        //     'invoice' => $orderRepository->find($id)
        // ]);
        $options = new Options();
        $options->set('defaultFont', 'Arial');
        
        $dompdf = new Dompdf($options);
        
        $html =  $this->renderView('dom_pdf/index.html.twig', [
            'facture' => $orderRepository->findOneBy(['adresse' => $adresse_id])
        ]);
        
         $dompdf->loadHtml($html);
        
        // (Optional) Setup the paper size and orientation
        $dompdf->setPaper('A4', 'portrait');
        
        
        // Render the HTML as PDF
        $dompdf->render();
        
        // Output the generated PDF to Browser
        $dompdf->stream("Facture - La Maison de Laine", [
            "Attachment" => true
        ] );
        
        $this->render('dom_pdf/index.html.twig', [
            'facture' => $orderRepository->findOneBy(['adresse' => $adresse_id])
        ]);
        // 
       

    }
}
