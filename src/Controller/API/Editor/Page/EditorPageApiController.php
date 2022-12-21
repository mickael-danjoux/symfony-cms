<?php

namespace App\Controller\API\Editor\Page;

use App\Entity\Page\Page;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/editor')]
class EditorPageApiController extends AbstractController
{
	public function __construct(private EntityManagerInterface $em, private LoggerInterface $logger)
	{}

	#[Route('/page/{id}', name: 'api_editor_page_post', methods: [Request::METHOD_POST])]
	public function post(Page $page): JsonResponse
	{

		try {
			$request = Request::createFromGlobals();
			$data = json_decode($request->getContent(), true);

			$page->setEditorContent($data);
			$page->setHtmlCss($data['pageHtml']);
			$this->em->flush();

			return $this->json([], Response::HTTP_OK);

		} catch (\Exception $e) {
			$this->logger->critical('Erreur API POST données éditeur : ' . $e->getMessage(), ['erreur' => $e]);
			return $this->json(['error' => 'Une erreur est survenue'], Response::HTTP_INTERNAL_SERVER_ERROR);
		}

	}

	#[Route('/page/load/{id}', name: 'api_editor_page_load', methods: [Request::METHOD_GET])]
	public function load(Page $page): JsonResponse
	{
		return $this->json($page->getEditorContent(), Response::HTTP_OK);
	}

}
