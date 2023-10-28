<?php

/**
 * Announcement Class
 *
 * @author Cyanne Justin Vega
 * @copyright  Ilocos Sur Polytechnic State College 2023
 */

class Announcements {

    /**
     * @var Database
     */
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    /**
     * Convert data to JSON
     * @param $data
     * @return false|string
     */
    public function convertToJson($data){
        return json_encode($data);
    }

    /**
     * fetch announcements from the database
     * @return array|false|void
     */
    public function fetchAnnouncements() {
        $sql = "SELECT * FROM `announcement` ORDER BY announcement_date DESC    ";
        $stmt = $this->db->query($sql);
        if ($stmt->execute()) {
            $announcements = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($announcements as &$announcement) {
                $announcement["announcement_id"] = intval($announcement["announcement_id"]);
            }

            return $announcements;
        }
    }

    /**
     * Add a new announcement
     * @param $announcement_content
     * @return true|void
     */
    public function addAnnouncement($announcement_content) {

        try {
            $sql = "INSERT INTO announcement (announcement_content) VALUES (:announcement_content)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(":announcement_content", $announcement_content);
            if ($stmt->execute()){
                return true;
            }

        } catch (Exception $exception) {
            echo "error: " . $exception->getMessage();
        }
    }

    public function editAnnouncement($announcement_id, $announcement_content) {
        try {
            $sql = "UPDATE announcement SET announcement_content = :ac WHERE announcement_id = :aid";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(":ac", $announcement_content);
            $stmt->bindParam(":aid",$announcement_id);
            if ($stmt->execute()){
                return true;
            }
        } catch (Exception $exception) {
            echo "error: " . $exception->getMessage();
        }
    }

    public function deleteAnnouncement($announcement_id) {
        try {
            $sql = "DELETE FROM announcement WHERE announcement_id = :aid";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(":aid",$announcement_id);
            if ($stmt->execute()){
                return true;
            }
        } catch (Exception $exception) {
        echo "error: " . $exception->getMessage();
        }
    }
}