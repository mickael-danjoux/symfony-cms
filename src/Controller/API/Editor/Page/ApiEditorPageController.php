<?php

namespace App\Controller\API\Editor\Page;

use App\Entity\Page\Page;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/editor')]
class ApiEditorPageController extends AbstractController
{
	public function __construct(private EntityManagerInterface $em)
	{}

	#[Route('/page/{id}', methods: [Request::METHOD_POST])]
	public function post(?Page $page): JsonResponse
	{
		$request = Request::createFromGlobals();
		$data = json_decode($request->getContent(), true);

		$page->setEditorContent($data);
		$page->setHtmlCss($data['pageHtml']);
		$this->em->flush();

		return $this->json([], Response::HTTP_OK);
	}

	#[Route('/page/load/{id}', methods: [Request::METHOD_GET])]
	public function load(?Page $page): JsonResponse
	{
		return $this->json($page->getEditorContent(), Response::HTTP_OK);
	}

}
