<?php
use Psr\Log\LoggerInterface;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
class Todo_Base {
     protected $logger;

    public function __construct() {
        $this->logger = new Logger('todo_base');
        $this->logger->pushHandler(new StreamHandler(__DIR__ . '/../logs/todo_base.log', Logger::WARNING));
    }

    protected function fetch_data_from_api($url) {
        $response = wp_remote_get($url);
       
        if (is_wp_error($response)) {
            $this->logger->error('API request failed: ' . $response->get_error_message());
            return [];
        }

        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->logger->error('JSON decoding error: ' . json_last_error_msg());
            return [];
        }

        return $data; 
    }
}