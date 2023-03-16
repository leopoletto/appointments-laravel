<?php

namespace App\Rules;

use Exception;
use GuzzleHttp\Client;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\Response;

class ZipCode implements Rule
{
    protected $client;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
       try {
            $response = $this->client->get('https://brasilapi.com.br/api/cep/v2/' . $value);
            return $response->getStatusCode() === Response::HTTP_OK;
       } catch (Exception $exception) {
           return false;
       }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The zip code is invalid.';
    }
}
