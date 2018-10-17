<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Team
 * @package App\Entity
 *
 * @ORM\Entity(repositoryClass="App\Repository\TeamRepository")
 * @ORM\Table()
 * @UniqueEntity(fields={"id", "name"}, repositoryMethod="findByNameAndNotSame")
 */
class Team implements TeamInterface
{
    use EntityTrait;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     *
     * @ORM\Column(length=100, unique=true)
     */
    private $name;

    /**
     * @var string
     *
     * @Assert\Choice({"white", "black", "red", "yellow", "green", "blue"})
     *
     * @ORM\Column(length=20, nullable=true)
     */
    private $strip;

    /**
     * @var LeagueInterface
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\League", inversedBy="teams")
     * @ORM\JoinColumns(
     *     @ORM\JoinColumn(nullable=false)
     * )
     */
    private $league;

    public function __construct(?string $name = null, ?League $league = null, ?string $strip = null)
    {
        $this->name = $name;
        $this->league = $league;
        $this->strip = $strip;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return TeamInterface
     */
    public function setName(string $name): TeamInterface
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getStrip(): string
    {
        return $this->strip;
    }

    /**
     * @param string $strip
     *
     * @return TeamInterface
     */
    public function setStrip(string $strip): TeamInterface
    {
        $this->strip = $strip;
        return $this;
    }

    /**
     * @return LeagueInterface
     */
    public function getLeague(): LeagueInterface
    {
        return $this->league;
    }

    /**
     * @param LeagueInterface $league
     *
     * @return TeamInterface
     */
    public function setLeague(LeagueInterface $league): TeamInterface
    {
        $this->league = $league;
        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }
}