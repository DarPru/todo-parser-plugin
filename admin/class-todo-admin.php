<?php
require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-todo-base.php';

class Todo_Admin extends Todo_Base {
    private $db_handler;
    private $search_performed = false;

    public function __construct($db_handler) {
    parent::__construct();
    $this->db_handler = $db_handler;
    add_action('admin_menu', [$this, 'add_admin_menu']);
}

    public function add_admin_menu() {
        add_menu_page(
            'Todo Parser Settings', 
            'Todo Parser', 
            'manage_options',
            'todo-parser', 
            [$this, 'display_admin_page'] 
        );
    }

    public function display_admin_page() {
        ?>
        <div class="wrap">
            <h1>Todo Parser</h1>
            <form method="post" action="">
                <input type="text" name="todo_search" placeholder="Search by title" />
                <input type="submit" name="search_todos" value="Search" class="button button-primary" />
                <input type="submit" name="update_todos" value="Update" class="button button-secondary" />
            </form>
            <?php
            if (isset($_POST['search_todos'])) {
                $this->search_todos();
            }
            if (isset($_POST['update_todos'])) {
                $this->update_todos();
            }
            if ($this->search_performed) {
                $this->display_todo_list();
            }
            ?>
        </div>
        <?php
    }

    private function search_todos() {
        global $wpdb;
        $search_title = sanitize_text_field($_POST['todo_search']);
        $results = $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}todo_items WHERE title LIKE %s",
            '%' . $wpdb->esc_like($search_title) . '%'
        ));
 if ($wpdb->last_error) {
            $this->logger->error('Error searching todos: ' . $wpdb->last_error);
        } else {
            $this->logger->info('Todo search performed');
        }

        echo '<h2>Search Results:</h2>';
        $this->display_table($results);
    }

   private function update_todos() {
        $api_url = 'https://jsonplaceholder.typicode.com/todos'; 
        $api_data = $this->fetch_data_from_api($api_url);

        if (!empty($api_data)) {
            $this->db_handler->insert_todos($api_data);
            echo '<div class="updated"><p>Todos have been updated successfully.</p></div>';
        } else {
            echo '<div class="error"><p>Failed to update todos. Please try again later.</p></div>';
        }
    }
    private function display_todo_list() {
        global $wpdb;
        $results = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}todo_items");

        $this->display_table($results);
    }

    private function display_table($results) {
        if (!empty($results)) {
            echo '<table class="wp-list-table widefat fixed striped">';
            echo '<thead><tr><th>ID</th><th>Title</th><th>Completed</th></tr></thead>';
            echo '<tbody>';
            foreach ($results as $todo) {
                echo '<tr>';
                echo '<td>' . esc_html($todo->id) . '</td>';
                echo '<td>' . esc_html($todo->title) . '</td>';
                echo '<td>' . ($todo->completed ? 'Yes' : 'No') . '</td>';
                echo '</tr>';
            }
            echo '</tbody>';
            echo '</table>';
        } else {
            echo '<p>No todos found.</p>';
        }
    }
}
