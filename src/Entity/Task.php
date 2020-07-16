<?php

namespace App\Entity;

use App\Repository\TaskRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=TaskRepository::class)
 */
class Task
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $title;

    /**
     * @ORM\Column(type="date")
     */
    private $deadline;

    /**
     * @ORM\Column(type="integer")
     */
    private $user_id;

    /**
     * Метод загрузки свойств для проверки валидатором
     *
     * @param object $metadata - класс метаданных
     */
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('title', new Assert\NotBlank());
        $metadata->addPropertyConstraint('deadline', new Assert\NotBlank());
        $metadata->addPropertyConstraint('user_id', new Assert\Positive());
    }

    /**
     * Геттер id
     *
     * @return integer
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Геттер title
     *
     * @return string
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Сеттер title
     *
     * @param string $title - текст задачи
     * @return object
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Геттер deadline
     *
     * @return string
     */
    public function getDeadline(): ?\DateTimeInterface
    {
        return $this->deadline;
    }

    /**
     * Сеттер deadline
     *
     * @param string $deadline - дата
     * @return object
     */
    public function setDeadline(\DateTimeInterface $deadline): self
    {
        $this->deadline = $deadline;

        return $this;
    }

    /**
     * Геттер user_id
     *
     * @return integer
     */
    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    /**
     * Сеттер user_id
     *
     * @param integer $user_id - id пользователя
     * @return object
     */
    public function setUserId(int $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }
}
