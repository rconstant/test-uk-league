<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class League
 * @package App\Entity
 *
 * @ORM\Entity()
 * @ORM\Table(name="leagues")
 *
 * @UniqueEntity(fields={"id", "name"}, repositoryMethod="findByNameAndNotSame")
 */
class League implements LeagueInterface
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
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Team", mappedBy="league", cascade={"persist", "remove"})
     * @JMS\Exclude()
     *
     */
    private $teams;

    public function __construct(?string $name = null)
    {
        $this->name = $name;
        $this->teams = new ArrayCollection();
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
     * @return LeagueInterface
     */
    public function setName(string $name): LeagueInterface
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return Collection
     */
    public function getTeams(): Collection
    {
        return $this->teams;
    }

    /**
     * @param Collection $teams
     *
     * @return LeagueInterface
     */
    public function setTeams(Collection $teams): LeagueInterface
    {
        $this->teams = $teams;
        return $this;
    }

    /**
     * @param TeamInterface $team
     *
     * @return LeagueInterface
     */
    public function addTeam(TeamInterface $team): LeagueInterface
    {
        $this->teams->add($team);
        return $this;
    }

    /**
     * @param TeamInterface $team
     *
     * @return LeagueInterface
     */
    public function removeTeam(TeamInterface $team): LeagueInterface
    {
        foreach ($this->teams as $key => $object) {
            if ($object = $team) {
                $this->teams->remove($key);
            }
        }
        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }
}