<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Comment;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class CommentController extends Controller
{
    /**
     * Creates a new comment entity.
     *
     * @Route("/post/{postId}/comment/new", name="new_comment")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, $postId)
    {
        $comment = new Comment();
        $comment_form = $this->createForm('AppBundle\Form\CommentType', $comment);
        $comment_form->handleRequest($request);

        if ($comment_form->isSubmitted() && $comment_form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            // Add the current logged in user as the author of the post
            $user = $this->get('security.token_storage')->getToken()->getUser();
            $comment->setAuthor($user);
            $comment->setPost($em->getRepository('AppBundle:Post')->find($postId));
            $em->persist($comment);
            $em->flush($comment);
        }

        return $this->redirectToRoute('post_show', array('id' => $postId));
    }

    /**
     * Deletes a comment entity.
     *
     * @Route("/post/{postId}/comment/{commentId}/delete", name="comment_delete")
     * @Method("GET")
     */
    public function deleteAction($commentId, $postId)
    {
        $em = $this->getDoctrine()->getManager();
        $commentToDelete = $em->getRepository('AppBundle:Comment')->findOneBy(array('id' => $commentId));

        $this->denyAccessUnlessGranted('delete', $commentToDelete);

        $em->remove($commentToDelete);
        $em->flush($commentToDelete);

        return $this->redirectToRoute('post_show', array('id' => $postId));
    }
}
