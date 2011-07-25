<?php defined('SYSPATH') OR die('No direct access allowed.');
class Kohana_Exception extends Kohana_Kohana_Exception {

    public static function handler(Exception $e)
    {
        if (Kohana::DEVELOPMENT === Kohana::$environment)
        {
            parent::handler($e);
        }
        else
        {
            try
            {
                Kohana::$log->add(Log::ERROR, parent::text($e));

                $attributes = array
                (
					'controller' => 'error',
                    'action'  => 500,
                    'message' => rawurlencode($e->getMessage())
                );

                if ($e instanceof HTTP_Exception)
                {
                    $attributes['action'] = $e->getCode();
                }

                // Error sub-request.
                echo Request::factory(Route::get('error')->uri($attributes))
                ->execute() 		// execute() returns a Response
                ->send_headers()  	// sends headers, returns the Response
                ->body(); 			// returns the rendered content

            }
            catch (Exception $e)
            {
                // Clean the output buffer if one exists
                ob_get_level() and ob_clean();

                // Display the exception text
                echo parent::text($e);

                // Exit with an error status
                exit(1);
            }
        }
    }
}
