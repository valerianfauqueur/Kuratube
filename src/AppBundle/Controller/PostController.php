<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Post;
use AppBundle\Entity\Comment;
use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Post controller.
 *
 * @Route("post")
 */
class PostController extends Controller
{
    /**
     * Lists all post entities.
     *
     * @Route("/", name="post_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $posts = $em->getRepository('AppBundle:Post')->findAll();

        return $this->render('post/index.html.twig', array(
            'posts' => $posts,
        ));
    }

    /**
     * Creates a new post entity.
     *
     * @Route("/new", name="post_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $post = new Post();
        $form = $this->createForm('AppBundle\Form\PostType', $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            // Add the current logged in user as the author of the post
            $user = $this->get('security.token_storage')->getToken()->getUser();
            $post->setAuthor($user);
            $em->persist($post);
            $em->flush($post);

            return $this->redirectToRoute('post_show', array('id' => $post->getId()));
        }

        return $this->render('post/new.html.twig', array(
            'post' => $post,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a post entity.
     *
     * @Route("/{id}", name="post_show")
     * @Method("GET")
     */
    public function showAction(Post $post)
    {
        $deleteForm = $this->createDeleteForm($post);
        $comment = new Comment();
        $comment_form = $this->createForm('AppBundle\Form\CommentType', $comment, array(
            'action' => $this->generateUrl('new_comment', array('postId' => $post->getId())),
        ));

        return $this->render('post/show.html.twig', array(
            'post' => $post,
            'comment_form' => $comment_form->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing post entity.
     *
     * @Route("/{id}/edit", name="post_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Post $post)
    {
        $this->denyAccessUnlessGranted('edit', $post);

        $deleteForm = $this->createDeleteForm($post);
        $editForm = $this->createForm('AppBundle\Form\PostType', $post);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('post_edit', array('id' => $post->getId()));
        }

        return $this->render('post/edit.html.twig', array(
            'post' => $post,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a post entity.
     *
     * @Route("/{id}", name="post_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Post $post)
    {
        $this->denyAccessUnlessGranted('delete', $post);

        $form = $this->createDeleteForm($post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($post);
            $em->flush($post);
        }

        return $this->redirectToRoute('post_index');
    }

    /**
     * Creates a form to delete a post entity.
     *
     * @param Post $post The post entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Post $post)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('post_delete', array('id' => $post->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /**
     * Upvote the post {id}
     *
     * @Route("/{postId}/upvote", name="post_upvote")
     * @Method("POST")
     */
    public function upvoteAction(Request $request, $postId)
    {
        $data = $request->request->all();
        $userToken = $data['token'];
        $em = $this->getDoctrine()->getManager();
        // get the user that upvoted by searching his token
        $userUpvoted = $em->getRepository('AppBundle\Entity\User')->findOneBy(array('token' => $userToken));
        if($userUpvoted instanceof User) {
            $upvotedPost = $em->getRepository('AppBundle\Entity\Post')->upvotePost($postId);
            $em->persist($upvotedPost);
            $em->flush();
            return new Response(json_encode(['success' => true]));
        }
        return new Response(json_encode(['success' => false]));
    }

    /**
     * Downvote the post {id}
     *
     * @Route("/{postId}/downvote", name="post_downvote")
     * @Method("POST")
     */
    public function downvoteAction(Request $request, $postId)
    {
        $data = $request->request->all();
        $userToken = $data['token'];
        $em = $this->getDoctrine()->getManager();
        // get the user that upvoted by searching his token
        $userDownvoted = $em->getRepository('AppBundle\Entity\User')->findOneBy(array('token' => $userToken));
        if($userDownvoted instanceof User) {
            $downvotedPost = $em->getRepository('AppBundle\Entity\Post')->downvotePost($postId);
            $em->persist($downvotedPost);
            $em->flush();
            return new Response(json_encode(['success' => true]));
        }
        return new Response(json_encode(['success' => false]));
    }
}
