<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;

/**
 * Interface LeagueInterface
 * @package App\Entity
 */
interface LeagueInterface
{
    /**
     * @return int
     */
    public function getId(): int;

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @param string $name
     *
     * @return LeagueInterface
     */
    public function setName(string $name): LeagueInterface;

    /**
     * @return Collection
     */
    public function getTeams(): Collection;

    /**
     * @param Collection $teams
     *
     * @return LeagueInterface
     */
    public function setTeams(Collection $teams): LeagueInterface;

    /**
     * @param TeamInterface $team
     *
     * @return LeagueInterface
     */
    public function addTeam(TeamInterface $team): LeagueInterface;

    /**
     * @param TeamInterface $team
     *
     * @return LeagueInterface
     */
    public function removeTeam(TeamInterface $team): LeagueInterface;
}