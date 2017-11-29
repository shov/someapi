<?php declare(strict_types=1);

namespace App\Helpers;

use App\Exceptions\EntityNotFoundException;
use App\Exceptions\UserAuthException;
use App\Http\Middleware\AuthWithJWT;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

trait ControllerHelper
{
    use CommonHelper;

    /**
     * @param \Throwable $e
     * @param null|string $message
     * @param int $status
     * @return Response
     */
    protected function failWithException(\Throwable $e, ?string $message = null, int $status = Response::HTTP_FORBIDDEN)
    {
        if (is_null($message)) {
            if ($e instanceof ValidationException) {
                $message = $e->validator->getMessageBag();
            } else {
                $message = $e->getMessage();
            }
        }

        return $this->makeResponse($status, $message, $e);
    }

    /**
     * @param string $message
     * @param int $status
     * @return Response
     */
    protected function failWithAuth(string $message = "Authorization required!", int $status = Response::HTTP_UNAUTHORIZED)
    {
        return $this->makeResponse($status, $message);
    }

    /**
     * @param string $message
     * @param int $status
     * @return Response
     */
    protected function failWithNotFound(string $message = "Not found", int $status = Response::HTTP_NOT_FOUND)
    {
        return $this->makeResponse($status, $message);
    }

    /**
     * @param array $response
     * @param int $status
     * @return Response
     */
    protected function success(array $response = [], int $status = Response::HTTP_OK)
    {
        $response = AuthWithJWT::mixRefreshedTokenToResponse($response);
        return response()->json($response, $status);
    }

    /**
     * Wrapping regular controller action into try...catch block, give the process as callback,
     * return success result from it as array or return just the Response
     *
     * @param callable $process
     * @return Response
     */
    protected function wrapController(callable $process): Response
    {
        try {
            $result = $process();

        } catch (ValidationException $e) {
            return $this->failWithException($e);

        } catch (UserAuthException $e) {
            return $this->failWithAuth();

        } catch (NotFoundResourceException|NotFoundHttpException|EntityNotFoundException $e) {
            return $this->failWithNotFound();

        } catch (\Throwable $e) {
            return $this->failWithException($e);
        }

        if ($result instanceof Response) {
            return $result;
        }

        return $this->success($result);
    }

    /**
     * Common method to build response
     * @param int $status
     * @param null|string $message
     * @return Response
     */
    protected function makeResponse(int $status, ?string $message, ?\Throwable $e = null): Response
    {
        if (App::environment('testing')) {

            $logData = [$message, $status];
            is_null($e) ?: $logData[] = $e->getTraceAsString();
            Log::info($logData);

            return response()->json([
                'message' => $message ?? '',
            ], $status);
        }
        return response()->setStatusCode($status);
    }
}