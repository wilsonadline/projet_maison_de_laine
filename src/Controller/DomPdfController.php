<?php

namespace App\Controller;

use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;


class DomPdfController extends AbstractController
{
    #[Route('/dom/pdf/{order_id}', name: 'dom_pdf', methods: ["GET"])]
    public function domPdf($order_id, OrderRepository $orderRepository)
    {
        // De l'objet Options, j'intialise la police du PDF
        $options = new Options();
        $options->set('defaultFont', 'Arial');
        
        // création de l'objet PDJ avec en parametre l'option de police
        $dompdf = new Dompdf($options);
        // initialisation la taille et la sens de la page
        $dompdf->setPaper('A4', 'portrait');
        
        // création de la view à la page indiqué en récuperant l'id de l'order 
        $html = $this->renderView('dom_pdf/index.html.twig', [
            'facture'=> $orderRepository->findOneBy(['id' => $order_id])
        ]);
        
        // j'appelle la fonction loadHTML à qui j'injecte ma vue html
        $dompdf->loadHtml($html);
        
        // rendre le HTML en PDF
        $dompdf->render();
        
        // 
        // Exporter le PDF généré vers le navigateur
        $dompdf->stream("Facture - La Maison de Laine");
    }
}