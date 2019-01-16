<?php
/**
 * @package    xero-php-sample-app
 * @author     Michael Calcinai <michael@calcin.ai>
 */

namespace App\Helper;

use League\Plates\Engine;
use League\Route\Http\Exception\NotFoundException;
use League\Route\Strategy\ApplicationStrategy;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use XeroPHP\Exception;
use Zend\Diactoros\Response\JsonResponse;

class CustomExceptionStrategy extends ApplicationStrategy
{

    public function getNotFoundDecorator(NotFoundException $exception)
    {
        return $this->getExceptionResponse($exception, 404);
    }

    public function getExceptionDecorator(\Exception $exception)
    {
        if ($exception instanceof Exception && $exception->getCode() >= 100 && $exception->getCode() <= 500) {
            $code = $exception->getCode();
        } else {
            $code = 500;
        }

        return $this->getExceptionResponse($exception, $code);
    }


    private function getExceptionResponse(\Exception $e, $code)
    {
        // Unfortunately need to do this because the standard callable doesn't
        // allow accessing via container so the exception message can't be displayed
        // nicely. Not ideal code duplication here, not not a lot of options
        return function (RequestInterface $request, ResponseInterface $response) use ($e, $code) {

            //Handle JSON responses
            if (in_array('application/json', $request->getHeader('accept'))) {
                return new JsonResponse([
                    'status_code' => $code,
                    'message' => $e->getMessage()
                ], $code);
            }


            $plates = new Engine(APP_ROOT . DS . 'src' . DS . 'templates', 'phtml');

            $response->getBody()->write(
                $plates->render('exception', ['exception' => $e, 'code' => $code])
            );

            return $response->withStatus($code);
        };
    }
}
