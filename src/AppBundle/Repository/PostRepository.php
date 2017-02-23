<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Post;
use Doctrine\ORM\EntityRepository;

class PostRepository extends EntityRepository
{
    public function upvotePost($postId)
    {
        $postToUpvote = $this->findOneBy(array('id' => $postId));
        $postToUpvote->upvote();

        return $postToUpvote;
    }

    public function downvotePost($postId)
    {
        $postToUpvote = $this->findOneBy(array('id' => $postId));
        $postToUpvote->downvote();

        return $postToUpvote;
    }
}
