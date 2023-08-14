<?php

namespace App\Class;

use DateTime;
use ErrorException;

class Post
{
    /**
     * @var int
     */
    private $id;
    private $title;
    private $image;
    private $content;
    private $createdAt;
    private $updatedAt;
    private $createdBy;
    private $slug;
    private $leadSentence;

    public function __construct(array $data = [])
    {
        $this->hydrate($data);
    }

    public function __get($property)
    {
        if (property_exists($this, $property)) {
            return $this->$property;
        } else {
            throw new ErrorException('Undefined property.');
        }
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function getTitle()
    {
        return $this->title;
    }


    public function setTitle($title)
    {
        if (mb_strlen($title) <= 255) {
            $this->title = $title;
        } else {
            $this->title = substr($title, 0, 255);
        }
    }

    public function getImage()
    {
        return $this->image;
    }


    public function setImage($image)
    {
        $this->image = $image;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setContent($content)
    {
        $allowedTags = ['p', 'a', 'i', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6'];
        $this->content = strip_tags($content, '<' . implode('><', $allowedTags) . '>');
    }

    public function getLead_Sentence()
    {
        return $this->leadSentence;
    }

    public function setLead_Sentence($leadSentence)
    {
        $allowedTags = ['p', 'a', 'i', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6'];
        $this->leadSentence = strip_tags($leadSentence, '<' . implode('><', $allowedTags) . '>');
    }

    public function getCreated_At()
    {
        return $this->createdAt;
    }

    public function setCreated_At($createdAt)
    {
        $format = 'Y-m-d H:i:s';
        $d = DateTime::createFromFormat($format, $createdAt);
        if ($createdAt == $d->format($format)) {
            $this->createdAt = $d->format($format);
        } else {
            $dd = new DateTime();
            $this->createdAt = $dd->format($format);
        }
    }

    public function getUpdated_At()
    {

        return $this->updatedAt;
    }

    public function setUpdated_At($updatedAt)
    {
        if (!empty($updatedAt)) {
            $format = 'Y-m-d H:i:s';
            $d = DateTime::createFromFormat($format, $updatedAt);

            if ($updatedAt == $d->format($format)) {
                $this->updatedAt = $d->format($format);
            } else {
                $dd = new DateTime();
                $this->updatedAt = $dd->format($format);
            }
        }
    }

    public function getCreated_By()
    {
        return $this->createdBy;
    }

    public function setCreated_By($userId)
    {
        $this->createdBy = $userId;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function setSlug($slug)
    {
        $this->slug = $slug;
    }


    private function hydrate($data)
    {
        foreach ($data as $key => $value) {
            $methodName = 'set' . ucfirst($key);

            if (method_exists($this, $methodName)) {
                $this->$methodName($value);
            }
        }
    }
}
