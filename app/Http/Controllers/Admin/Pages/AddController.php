<?php
declare(strict_types = 1);

namespace App\Http\Controllers\Admin\Pages;

use App\DataTransferObjects\Admin\Pages\Add;
use App\Exceptions\Page\AlreadyExistException;
use App\Handlers\Admin\Pages\AddHandler;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Pages\AddEditRequest;
use App\Services\Auth\Permissions;
use App\Services\Notification\Notifications\Success;
use App\Services\Notification\Notifications\Warning;
use App\Services\Response\JsonResponse;
use App\Services\Response\Status;
use Illuminate\Http\Response;
use function App\permission_middleware;

class AddController extends Controller
{
    public function __construct()
    {
        $this->middleware(permission_middleware(Permissions::ADMIN_PAGES_CRUD_ACCESS));
    }

    public function add(AddEditRequest $request, AddHandler $handler): JsonResponse
    {
        $dto = (new Add())
            ->setTitle($request->get('title'))
            ->setContent($request->get('content'))
            ->setUrl($request->get('url'));

        try {
            $handler->handle($dto);

            return (new JsonResponse(Status::SUCCESS))
                ->addNotification(new Success(__('msg.admin.pages.add.success')));
        } catch (AlreadyExistException $e) {
            return (new JsonResponse('page_already_exists'))
                ->setHttpStatus(Response::HTTP_CONFLICT)
                ->addNotification(new Warning(__('msg.admin.pages.add.already_exists')));
        }
    }
}
