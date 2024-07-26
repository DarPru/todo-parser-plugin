<?php
require_once plugin_dir_path(__FILE__) . 'class-todo-base.php';

class Todo_Parser_Plugin extends Todo_Base {
    private $db_handler;
    private $admin;

    public function __construct($db_handler) {
        parent::__construct(); 
        $this->db_handler = $db_handler;
        $this->admin = new Todo_Admin($this->db_handler);
    }
    public function run() {
        $api_url = 'https://jsonplaceholder.typicode.com/todos'; 
        $api_data = $this->fetch_data_from_api($api_url);
        if (empty($api_data)) {
            $this->logger->warning('No data fetched from API');
            return;
        }

        $this->db_handler->create_table();
        $this->db_handler->insert_todos($api_data);
        $this->logger->info('Todos inserted successfully');
    }  
}
?>
