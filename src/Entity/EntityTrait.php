<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Trait EntityTrait
 * @package App\Entity
 */
trait EntityTrait
{
    /**
     * @var integer
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
}