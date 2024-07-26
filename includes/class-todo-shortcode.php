<?php

use Psr\Log\LoggerInterface;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class Todo_Shortcode {
    private $db_handler;
    private $logger;

    public function __construct($db_handler) {
        $this->db_handler = $db_handler;
        $this->logger = new Logger('todo_shortcode');
        $this->logger->pushHandler(new StreamHandler(__DIR__ . '/logs/todo_shortcode.log', Logger::WARNING));
        add_shortcode('random_todos', [$this, 'random_todos_shortcode']);
    }

    public function random_todos_shortcode($atts) {
        $atts = shortcode_atts(array(
            'limit' => 5,
        ), $atts, 'random_todos');
        try {
            $todos = $this->get_random_incomplete_todos($atts['limit']);
    
            if (empty($todos)) {
                $this->logger->info('No incomplete todos found.');
                return '<p>No incomplete todos found.</p>';
            }

            $output = '<ul class="random-todos">';
            foreach ($todos as $todo) {
                $output .= '<li>' . esc_html($todo->title) . '</li>';
            }
            $output .= '</ul>';

            return $output;
        } catch (Exception $e) {
            $this->logger->error('Error in random_todos_shortcode: ' . $e->getMessage());
            return '<p>An error occurred while fetching todos.</p>';
        }
    }
    private function get_random_incomplete_todos($limit) {
        global $wpdb;
        $table_name = $this->db_handler->get_table_name();

        try {
            $query = $wpdb->prepare(
                "SELECT * FROM $table_name WHERE completed = 0 ORDER BY RAND() LIMIT %d",
                $limit
            );

            $results = $wpdb->get_results($query);

            if ($wpdb->last_error) {
                throw new \Exception('Database query error: ' . $wpdb->last_error);
            }

            return $results;
        } catch (\Exception $e) {
            $this->logger->error('Error in get_random_incomplete_todos: ' . $e->getMessage());
            throw $e;
        }
    }

}