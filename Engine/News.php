<?php

/**
 * News Class
 */
class News {

    /**
     * @var Database
     */
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    /**
     * Fetch news articles from the database
     * @return array|false
     */
    public function fetchNews() {
        $sql = "SELECT * FROM `news` ORDER BY date_posted DESC";
        $stmt = $this->db->query($sql);
        if ($stmt->execute()) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }

    public function getNewsById($newsId) {
        $sql = "SELECT * FROM news WHERE news_id = :news_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(":news_id", $newsId, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            return false;
        }
    }


    /**
     * Add a new news article
     * @param $news_title
     * @param $news_content
     * @param $news_image
     * @return true|false
     */
    public function addNews($news_title, $news_content, $news_image) {
        try {
            $sql = "INSERT INTO news (news_title, news_content, news_image) VALUES (:news_title, :news_content, :news_image)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(":news_title", $news_title);
            $stmt->bindParam(":news_content", $news_content);
            $stmt->bindParam(":news_image", $news_image);
            if ($stmt->execute()) {
                return true;
            }
        } catch (Exception $exception) {
            echo "Error: " . $exception->getMessage();
        }
        return false;
    }

    /**
     * Update an existing news article
     * @param $news_id
     * @param $news_title
     * @param $news_content
     * @param $news_image
     * @return true|false
     */
    public function editNews($news_id, $news_title, $news_content, $news_image) {
        try {
            $sql = "UPDATE news SET news_title = :news_title, news_content = :news_content, news_image = :news_image WHERE news_id = :news_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(":news_id", $news_id);
            $stmt->bindParam(":news_title", $news_title);
            $stmt->bindParam(":news_content", $news_content);
            $stmt->bindParam(":news_image", $news_image);
            if ($stmt->execute()) {
                return true;
            }
        } catch (Exception $exception) {
            echo "Error: " . $exception->getMessage();
        }
        return false;
    }

    /**
     * Delete a news article
     * @param $news_id
     * @return true|false
     */
    public function deleteNews($news_id) {
        try {
            $sql = "DELETE FROM news WHERE news_id = :news_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(":news_id", $news_id);
            if ($stmt->execute()) {
                return true;
            }
        } catch (Exception $exception) {
            echo "Error: " . $exception->getMessage();
        }
        return false;
    }

    public function fetchNewsArchive() {
        $sql = "SELECT YEAR(date_posted) as year, MONTH(date_posted) as month, COUNT(*) as count FROM news GROUP BY year, month ORDER BY year DESC, month DESC";
        $stmt = $this->db->query($sql);
        if ($stmt->execute()) {
            $archiveData = array();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $year = $row['year'];
                $month = date('F', mktime(0, 0, 0, $row['month'], 1));
                $count = $row['count'];
                if (!isset($archiveData[$year])) {
                    $archiveData[$year] = array();
                }
                $archiveData[$year][$month] = $count;
            }
            return $archiveData;
        }
        return array();
    }

}
