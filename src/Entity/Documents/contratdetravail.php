<?php


namespace App\Entity\Documents;
use Symfony\Component\Validator\Constraints as Assert;

class contratdetravail
{
    /**
     * @Assert\NotBlank()
     * @Assert\Length(min="10", max="100")
     *
     */
    public $title;

    /**
     * @Assert\NotBlank()
     *
     */
    public $content;

    /**
     * @Assert\DateTime()
     * @var \DateTime
     */
    public $publishDate;

    public function __construct()
    {
        $this->publishDate = new \DateTime();
    }


    public function getPublishDate(): ?\DateTime
    {
        return $this->publishDate;
    }


    public function setPublishDate(\DateTime $publishDate): self
    {
        $this->publishDate = new \DateTime();

        return $this;
    }


    public function getContent(): string
    {
        return $this->content;
    }


    public function setContent(string $content): string
    {
        return $this->content = $content;

    }


    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): string
    {
        return $this->title = $title;

    }

}