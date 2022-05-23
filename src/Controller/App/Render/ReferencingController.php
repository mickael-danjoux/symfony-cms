<?php

namespace App\Controller\App\Render;

use App\Entity\Referencing\Referencing;
use App\Repository\Referencing\ReferencingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class ReferencingController extends AbstractController
{

    public function __construct(private ReferencingRepository $referencingRepository)
    {
    }

    public function addReferencing(): Response
    {
        $url = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
        $referencing = $this->referencingRepository->findOneByUrl($url);

        if(!$referencing){
            $referencing = $this->referencingRepository->findOneByUrl('/');
            if(!$referencing){
                $referencing = new Referencing();
            }
        }

        return $this->render('app/_render/_referencing.html.twig', [
            'referencing' => $referencing
        ]);
    }

}