<?php

class FormHandler {
    private $db;
    private $name;
    private $email;
    private $phone;

    public function __construct(Database $db, $name, $email, $phone) {
        $this->db = $db;
        $this->name = trim($name);
        $this->email = trim($email);
        $this->phone = trim($phone);
    }

    public function validate() {
        if (empty($this->name) || !filter_var($this->email, FILTER_VALIDATE_EMAIL) || !preg_match('/^\+7\d{10}$/', $this->phone)) {
            return ['success' => false, 'message' => 'Некорректные данные'];
        }
        return ['success' => true];
    }

    public function isRecentSubmission() {
        $sql = "SELECT * FROM userdata WHERE name = :name AND email = :email AND phone = :phone ORDER BY timestamp DESC LIMIT 1";
        $stmt = $this->db->query($sql, ['name' => $this->name, 'email' => $this->email, 'phone' => $this->phone]);
        $lastEntry = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($lastEntry && (time() - strtotime($lastEntry['timestamp'])) < 300) {
            return ['success' => false, 'message' => 'Заявка уже отправлена недавно.'];
        }
        return ['success' => true];
    }

    public function submit() {
        $sql = "INSERT INTO userdata (name, email, phone) VALUES (:name, :email, :phone)";
        $this->db->query($sql, ['name' => $this->name, 'email' => $this->email, 'phone' => $this->phone]);
        return ['success' => true];
    }
}

?>