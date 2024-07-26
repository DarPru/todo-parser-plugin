<?php
use Psr\Log\LoggerInterface;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class Todo_Database_Handler {
    private $table_name;
    private $logger;


    public function __construct() {
        global $wpdb;
        $this->table_name = $wpdb->prefix . 'todo_items';
        $this->logger = new Logger('todo_database_handler');
        $this->logger->pushHandler(new StreamHandler(__DIR__ . '/../logs/todo_database_handler.log', Logger::WARNING));
   
    }

    public function create_table() {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $this->table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            user_id mediumint(9) NOT NULL,
            title text NOT NULL,
            completed boolean NOT NULL,
            PRIMARY KEY  (id)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
         if ($wpdb->last_error) {
            $this->logger->error('Error creating table: ' . $wpdb->last_error);
        } else {
            $this->logger->info('Table created successfully');
        }
    }

   public function insert_todos($todos) {
    global $wpdb;
    
    $wpdb->query("TRUNCATE TABLE $this->table_name");
    
    foreach ($todos as $todo) {
        $result = $wpdb->insert(
            $this->table_name,
            array(
                'user_id' => $todo['userId'],
                'title' => $todo['title'],
                'completed' => $todo['completed']
            ),
            array('%d', '%s', '%d')
        );
        
        if ($result === false) {
            $this->logger->error('Error inserting todo: ' . $wpdb->last_error);
        }
    }
    
    $this->logger->info('Todos inserted successfully');
}

    public function get_table_name() {
        return $this->table_name;
    }
}
