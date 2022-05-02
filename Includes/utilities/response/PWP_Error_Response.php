<?php

declare(strict_types=1);

namespace PWP\includes\utilities\response;

class PWP_Error_Response extends PWP_Response
{
    private string $message;

    private int $exCode;
    private string $exMessage;
    private string $exFile;
    private string $exLine;

    private PWP_I_Response $previous;

    public function __construct(string $message, \Exception $exception, array $additionalData = [])
    {
        parent::__construct($message, $additionalData);

        $this->exCode = $exception->getCode();
        $this->exMessage = $exception->getMessage();
        $this->file = $exception->getFile();
        $this->line = $exception->getLine();

        $this->previous = new PWP_Error_Response($this->exceptionMessage, $exception->getPrevious());
    }
    public function to_array(): array
    {
        return array(
            'message' => $this->message,
            'error logs' => array(
                'code' => $this->exCode,
                'message' => $this->exMessage,
                'file' => $this->exFile,
                'line' => $this->exLine,
                'data' => $this->get_data,
                'previous' => $this->previous->to_array()
            ),
        );
    }
}
