<?php

namespace App\Controller;

use App\Entity\Team;
use App\Entity\TeamInterface;
use App\Service\TeamService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Api\Annotations\Resource as ApiResource;

/**
 * Class TeamController
 * @package App\Controller
 *
 * @Route("/api/teams")
 */
class TeamController extends AbstractController
{
    /**
     * @var TeamService
     */
    private $teamService;

    public function __construct(TeamService $teamService)
    {
        $this->teamService = $teamService;
    }

    /**
     * Create a football team
     *
     * @Route(methods={"POST"}, requirements={"application/json"})
     *
     * @ApiResource(
     *     reference="data",
     *     type="model_data",
     *     class=Team::class
     * )
     *
     *
     * @param Request $request
     *
     * @return Team
     */
    public function create(Request $request)
    {
        $data = $request->request->get('data');
        if (!$data instanceof Team) {
            throw new NotFoundHttpException('Not a valid team');
        }

        return $this->teamService->create($data);
    }

    /**
     * Replace all attributes of a football team
     *
     * @Route("/{id}", methods={"PUT"})
     *
     * @ApiResource(
     *     reference="data",
     *     class=Team::class
     * )
     *
     * @param Request   $request
     *
     * @param Team|null $team
     *
     * @return Team
     */
    public function update(Request $request, Team $team = null): TeamInterface
    {
        if (!$team instanceof Team) {
            throw new NotFoundHttpException('Team not found.');
        }
        $data = $request->request->get('data');

        return $this->teamService->update($team, $data);
    }
}