<?php

namespace App\Controller\API\Editor\Image;

use App\Entity\Media\Image;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/editor')]
class ApiEditorImageController extends AbstractController
{
	public function __construct(private EntityManagerInterface $em, private LoggerInterface $logger)
	{}

	#[Route('/image', methods: [Request::METHOD_POST])]
	public function store(string $relativePathUploadsImagesDir): JsonResponse
	{
		$uploadedFiles = Request::createFromGlobals()->files->get('files');

		$uploadedFilesResponse = [];

		/** @var UploadedFile $file */
		foreach ($uploadedFiles as $file) {

			try {
				$image = (new Image())
					->setFile($file);

				$this->em->persist($image);
				$this->em->flush();

				if (is_file($image->getFile()->getPathname())) {
					// récupérer la largeur + hauteur de l'image
					// car cette info est nécessaire dans la réponse JSON
					[$imageWidth, $imageHeight] = getimagesize($image->getFile()->getPathname());

					$uploadedFilesResponse[] = [
						"src" => $relativePathUploadsImagesDir . '/' . $image->getUrl(),
						"type" => 'image',
						"height" => $imageHeight,
						"width" => $imageWidth,
						"id" => $image->getId()
					];
				}
			} catch (\Exception $e) {
				$this->logger->critical('Erreur upload fichier depuis editor :' . $e->getMessage(), ['erreur' => $e]);
			}
		}

		// la réponse API doit obligatoirement avoir la clé "data"
		// cf: https://grapesjs.com/docs/modules/Assets.html#uploading-assets
		return $this->json(["data" => $uploadedFilesResponse], Response::HTTP_OK);
	}

	#[Route('/image/{id}', methods: Request::METHOD_DELETE)]
	public function remove(?Image $image): JsonResponse
	{
		if ($image instanceof Image && $this->isGranted('ROLE_ADMIN')) {
			try {
				$this->em->remove($image);
				$this->em->flush();
				return $this->json([], Response::HTTP_OK);
			} catch (\Exception $e) {
				$this->logger->critical('Erreur suppression image depuis editor :' . $e->getMessage(), ['erreur' => $e]);
			}
		}

		return $this->json('Une erreur est survenue.', Response::HTTP_NOT_FOUND);
	}
}
