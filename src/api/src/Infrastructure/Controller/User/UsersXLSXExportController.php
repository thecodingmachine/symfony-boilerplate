<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller\User;

use App\Domain\Enum\Filter\SortOrder;
use App\Domain\Enum\Filter\UsersSortBy;
use App\Domain\Enum\Role;
use App\Infrastructure\Controller\DownloadXLSXController;
use App\UseCase\User\CreateUsersXLSXExport;
use App\UseCase\User\GetUsers;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use TheCodingMachine\GraphQLite\Annotations\Security;

use function assert;
use function is_string;

final class UsersXLSXExportController extends DownloadXLSXController
{
    private GetUsers $getUsers;
    private CreateUsersXLSXExport $createUsersXLSXExport;

    public function __construct(GetUsers $getUsers, CreateUsersXLSXExport $createUsersXLSXExport)
    {
        $this->getUsers              = $getUsers;
        $this->createUsersXLSXExport = $createUsersXLSXExport;
    }

    #[Route(path: '/users/xlsx', methods: ['GET'])]
    #[Security("is_granted('ROLE_ADMINISTRATOR')")]
    public function downloadUsersXlsx(Request $request): Response
    {
        $locale = $request->query->get(key: 'locale', default: $request->getLocale());
        assert(is_string($locale));
        $search    = $request->query->get(key: 'search', default: null);
        $role      = $request->query->get(key: 'role', default: null);
        $sortBy    = $request->query->get(key: 'sortBy', default: null);
        $sortOrder = $request->query->get(key: 'sortOrder', default: null);

        $users = $this->getUsers->users(
            search   : $search ? (string) $search : null,
            role     : $role ? Role::$role() : null,
            sortBy   : $sortBy ? UsersSortBy::$sortBy() : null,
            sortOrder: $sortOrder ? SortOrder::$sortOrder() : null
        );

        $xlsx = $this->createUsersXLSXExport->createXLSX(
            locale: $locale,
            users : $users
        );

        return $this->createResponseWithXLSXAttachment(
            filename: 'users.xlsx',
            xlsx    : $xlsx
        );
    }
}
