<?php

namespace App\Controller;

use Api\Util\DefaultConstant;
use App\Entity\League;
use App\Service\LeagueService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class LeagueController
 * @package App\Controller
 *
 * @Route("/leagues")
 */
class LeagueController extends AbstractController
{
    /**
     * @var LeagueService
     */
    private $leagueService;

    public function __construct(LeagueService $leagueService)
    {
        $this->leagueService = $leagueService;
    }

    /**
     * Get a list of football teams in given league
     *
     * @Route("/{id}/teams", methods={"GET"})
     *
     * @param Request     $request
     * @param League|null $league
     *
     * @return array
     */
    public function teams(Request $request, League $league = null): array
    {
        if (!$league instanceof League) {
            throw new NotFoundHttpException('League not found.');
        }
        $offset = $request->query->has('offset') ? $request->query->get('offset') : DefaultConstant::DEFAULT_OFFSET;
        $limit = $request->query->has('limit') ? $request->query->get('limit') : DefaultConstant::DEFAULT_LIMIT;

        return $this->leagueService->teams($league, $offset, $limit);
    }

    /**
     * Delete a football league
     *
     * @Route("/{id}", methods={"DELETE"})
     *
     * @param League|null $league
     */
    public function delete(League $league = null)
    {
        if (!$league instanceof League) {
            throw new NotFoundHttpException('League not found.');
        }
        $this->leagueService->delete($league);
    }
}