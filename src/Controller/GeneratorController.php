<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\GeneratorService;

class GeneratorController extends AbstractController
{
    #[Route('/', name: 'app_generator')]
    public function generateData(Request $request, GeneratorService $generatorService): Response
    {
        $count = $request->query->get('count');
        $region = $request->query->get('region');
        $errors = $request->query->get('errors');
        $seed = $request->query->get('seed');

        $newData = $generatorService->generateUserData($count, $region, $seed, $errors);

        return $this->render('generator/index.html.twig', [
            'dataGenerator' => $newData
        ]);
    }
}
