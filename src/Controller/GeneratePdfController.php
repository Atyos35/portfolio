<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Spatie\Browsershot\Browsershot;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class GeneratePdfController extends AbstractController
{
    #[Route('/generate-cv-pdf', name: 'generate_cv_pdf', methods: ['POST'])]
    public function generate(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['html']) || empty($data['html'])) {
            return new Response('Contenu HTML manquant.', Response::HTTP_BAD_REQUEST);
        }

        $htmlContent = htmlspecialchars_decode($data['html'], ENT_QUOTES);
        
        $html = <<<HTML
        <!DOCTYPE html>
        <html lang="fr">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <link href="https://cdn.jsdelivr.net/npm/tailwindcss@3.4.1/dist/tailwind.min.css" rel="stylesheet">
                <style>
                    body { background: white; padding: 2rem; }
                </style>
            </head>
            <body>
                {$htmlContent}
            </body>
        </html>
        HTML;

        $pdfPath = sys_get_temp_dir() . '/cv_' . uniqid() . '.pdf';

        try {
            Browsershot::html($html)
                ->setRemoteInstance('http://puppeteer:3000?token=secret-token')
                ->showBackground()
                ->format('A4')
                ->margins(10, 10, 10, 10)
                ->waitUntilNetworkIdle()
                ->save($pdfPath);
        } catch (\Throwable $e) {
            return new Response('Erreur lors de la génération du PDF : ' . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new Response(
            file_get_contents($pdfPath),
            Response::HTTP_OK,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="cv.pdf"',
            ]
        );
    }
}