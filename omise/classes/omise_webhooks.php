<?php
if (! defined('_PS_VERSION_')) {
    exit();
}

class OmiseWebhooks
{
    /**
     * Return the request body that received from Omise server.
     *
     * @return mixed|null The null will be returned. For examples,
     *
     * - The request from Omise server is HTTP GET not HTTP POST.
     * - The request body is empty.
     * - The request body is invalid JSON format.
     */
    public function getRequestBody()
    {
        $return_value_as_array = true;

        $data = file_get_contents('php://input');

        return json_decode($data, $return_value_as_array);
    }

    public function sendRawHeaderAsBadRequest()
    {
        $replace_previous_similar_header = true;

        header('HTTP/1.1 400 Bad Request', $replace_previous_similar_header, 400);
    }
}
