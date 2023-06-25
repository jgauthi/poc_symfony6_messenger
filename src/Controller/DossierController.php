<?php
namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Dossier;
use App\Entity\Enum\DossierStatusEnum;
use App\Entity\User;
use App\Form\CommentType;
use App\Message\SendComment;
use App\Repository\CommentRepository;
use App\Repository\DossierRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Request, Response};
use Symfony\Component\Messenger\MessageBusInterface;
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
        $query = $this->dossierRepository->findByStatus(DossierStatusEnum::ACTIVE);
        $dossierList = $paginator->paginate($query, $request->query->getInt('page', 1), 6);

        return $this->render('dossierList.html.twig', [
            'dossierList' => $dossierList,
        ]);
    }

    #[Route('/dossier/{id}', name: 'dossierItem', methods: [Request::METHOD_GET, Request::METHOD_POST], requirements: ['id' => '\d+'])]
    public function dossierItem(
        Dossier $dossier,
        #[CurrentUser] ?User $user,
        Request $request,
        CommentRepository $commentRepository,
        MessageBusInterface $bus,
    ): Response {
        $formComment = null;
        if (!empty($user)) {
            $comment = (new Comment)->setAuthor($user)->setDossier($dossier);
            $formComment = $this->createForm(CommentType::class, $comment);

            $formComment->handleRequest($request);
            if ($formComment->isSubmitted() && $formComment->isValid()) {
                $comment = $formComment->getData();
                $commentRepository->save($comment, flush: true);
                $bus->dispatch(new SendComment($comment->getId()));

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
