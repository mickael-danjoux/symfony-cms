<?php

namespace App\Controller\API\Editor\Image;

use App\Entity\Media\ImagePage;
use App\Entity\Page\Page;
use App\Repository\Page\PageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/editor')]
class EditorImageApiController extends AbstractController
{
	public function __construct(private readonly EntityManagerInterface $em, private readonly LoggerInterface $logger)
	{}

	#[Route('/image', name: 'api_editor_image_post', methods: [Request::METHOD_POST])]
	public function store(string $relativePathUploadsImagesDir, PageRepository $pageRepository): JsonResponse
	{
		$uploadedFiles = Request::createFromGlobals()->files->get('files');
		$pageId = Request::createFromGlobals()->request->getInt('pageId');

		/** @var Page $page */
		$page = $pageRepository->findOneById($pageId);

		$uploadedFilesResponse = [];

		/** @var UploadedFile $file */
		foreach ($uploadedFiles as $file) {

			$image = (new ImagePage())
				->setFile($file)
				->setPage($page);

			$this->em->persist($image);

			if (is_file($image->getFile()->getPathname())) {
				// récupérer la largeur + hauteur de l'image
				// car cette info est nécessaire dans la réponse JSON
				[$imageWidth, $imageHeight] = getimagesize($image->getFile()->getPathname());

				$uploadedFilesResponse[] = [
					"src" => $relativePathUploadsImagesDir . '/' . $image->getUrl(),
					"type" => 'image',
					"height" => $imageHeight,
					"width" => $imageWidth,
					"url" => $image->getUrl()
				];
			}
		}

		try {
			$this->em->flush();
			// la réponse API doit obligatoirement avoir la clé "data"
			// cf: https://grapesjs.com/docs/modules/Assets.html#uploading-assets
			return $this->json(["data" => $uploadedFilesResponse], Response::HTTP_OK);

		} catch (\Exception $e) {
			$this->logger->critical('Erreur upload fichier depuis editor :' . $e->getMessage(), ['erreur' => $e]);
			return $this->json(['erreur' => 'Une erreur est survenue', Response::HTTP_INTERNAL_SERVER_ERROR]);
		}
	}

	#[Route('/image/{url}', name: 'api_editor_image_remove', methods: Request::METHOD_DELETE)]
	public function remove(?ImagePage $image): JsonResponse
	{
		if ($image instanceof ImagePage) {
			try {
				$this->em->remove($image);
				$this->em->flush();
				return $this->json([], Response::HTTP_OK);
			} catch (\Exception $e) {
				$this->logger->critical('Erreur suppression image depuis editor :' . $e->getMessage(), ['erreur' => $e]);
				return $this->json(['erreur' => 'Une erreur est survenue', Response::HTTP_INTERNAL_SERVER_ERROR]);
			}
		}
		return $this->json('Cette image n\'existe pas.', Response::HTTP_NOT_FOUND);
	}
}
