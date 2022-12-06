<?php

namespace App\Controller\API\Editor;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WebpackApiController extends AbstractController
{
	const ENTRYPOINT_KEY = 'build/style-app-main.css';

	public function __construct(private LoggerInterface $logger)
	{}

	#[Route('/webpack/entrypoint')]
	public function __invoke(): JsonResponse
	{
		$pathToFile = $_SERVER['DOCUMENT_ROOT'] . '/build/manifest.json';
		$fileContent = file_get_contents($pathToFile);
		$fileContentToArray = json_decode($fileContent, true);

		try {
			$entrypoint = $fileContentToArray[self::ENTRYPOINT_KEY];
			return $this->json(["entrypoint" => $entrypoint], Response::HTTP_OK);
		} catch (\Exception $e) {
			$this->logger->critical('API EDITOR: entrypoint introuvable : ' . $e->getMessage(), ['error' => $e]);
			return $this->json('Entrypoint introuvable.', Response::HTTP_NOT_FOUND);
		}

	}

}