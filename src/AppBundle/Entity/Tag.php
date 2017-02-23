<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * Tag
 *
 * @ORM\Table(name="tag")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TagRepository")
 */
class Tag
{
    /**
     * @Assert\Type(type="AppBundle\Entity\Tag")
     * @Assert\Valid()
     */
    private $post;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=20, unique=true)
     */
    private $name;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Tag
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @var datetime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $created_at;

    public function getCreatedAt()
    {
        return $this->created_at;
    }

    public function setCreatedAt($createdAt)
    {
        $this->created_at = $createdAt;

        return $this;
    }

    /**
     * @var datetime
     *
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updated_at;

    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    public function setUpdatedAt($updatedAt)
    {
        $this->updated_at = $updatedAt;

        return $this;
    }

    public function __construct() {
        $this->setCreatedAt(new \DateTime());
        if ($this->getUpdatedAt() == null) {
            $this->setUpdatedAt(new \DateTime());
        }
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function updateModifiedDatetime() {
        // update the modified time
        $this->setUpdatedAt(new \DateTime());
    }
    public function __toString() {
        return $this->name;
    }
}

