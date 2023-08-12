<?php
namespace App\Controller;

use App\Entity\Enum\DossierStatusEnum;
use App\Entity\{Comment, Dossier, User};
use App\Form\CommentType;
use App\Repository\{CommentRepository, DossierRepository};
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Request, Response};
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class DossierController extends AbstractController
{
    public function __construct(private DossierRepository $dossierRepository)
    {
    }

    #[Route('/', name: 'dossierList', methods: [Request::METHOD_GET])]
    public function dossierList(Request $request, PaginatorInterface $paginator): Response
    {
        $status = $request->query->getEnum('status', DossierStatusEnum::class, DossierStatusEnum::ACTIVE);
        $query = $this->dossierRepository->queryByStatus($status);
        $dossierList = $paginator->paginate($query, $request->query->getInt('page', 1), 6);

        return $this->render('dossierList.html.twig', [
            'status' => $status,
            'dossierList' => $dossierList,
        ]);
    }

    #[Route('/dossier/{id}', name: 'dossierItem', methods: [Request::METHOD_GET, Request::METHOD_POST], requirements: ['id' => '\d+'])]
    public function dossierItem(
        Dossier $dossier,
        #[CurrentUser] ?User $user,
        Request $request,
        CommentRepository $commentRepository,
    ): Response {
        $formComment = null;
        if (!empty($user)) {
            $comment = (new Comment)->setAuthor($user)->setDossier($dossier);
            $formComment = $this->createForm(CommentType::class, $comment);

            $formComment->handleRequest($request);
            if ($formComment->isSubmitted() && $formComment->isValid()) {
                $comment = $formComment->getData();
                $commentRepository->save($comment, flush: true);

                $this->addFlash('success', 'Your comment has been added.');

                return $this->redirectToRoute('dossierItem', ['id' => $dossier->getId()]);
            }
        }

        return $this->render('dossierItem.html.twig', [
            'dossier' => $dossier,
            'formComment' => $formComment,
        ]);
    }
}
