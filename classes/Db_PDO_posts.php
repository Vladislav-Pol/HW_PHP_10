<?php


class Db_PDO_posts extends Db_PDO
{
    protected $config;
    protected $dbh;

    public function getCategories()
    {
        return $this->get('categories', ['code', 'name'], null, ['name' => 'ASC']);
    }

    public function getPreviewData($categoryCode = '', $offset = 0)
    {
        $query = 'SELECT title, date, P.code AS post_code, C.code AS cat_code FROM posts P JOIN categories C ON p.category_id = C.id WHERE P.active = 1 ';

        if($categoryCode != ''){
            $query .= "AND C.code = :categoryCode ";
        }
        $query .= "ORDER BY P.date DESC LIMIT :offset, :prevLimit";

        $stmt = $this->dbh->prepare($query);

        if($categoryCode != ''){
            $stmt->bindParam(':categoryCode', $categoryCode, PDO::PARAM_STR, 32);
        }
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindParam(':prevLimit', $this->config['prevLimit'], PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCountPosts($categoryCode = '')
    {
        $query = "SELECT COUNT(title) FROM posts P JOIN categories C ON p.category_id = C.id WHERE P.active = 1 ";

        if($categoryCode != ''){
            $query .= "AND C.code = :categoryCode ";
        }

        $stmt = $this->dbh->prepare($query);

        if($categoryCode != ''){
            $stmt->bindParam(':categoryCode', $categoryCode, PDO::PARAM_STR, 32);
        }

        $stmt->execute();

        return $stmt->fetch()[0];
    }

    public function getPrevLimit()
    {
        return $this->config['prevLimit'];
    }

    public function getPost($data)
    {
        $query = "SELECT * FROM posts JOIN authors ON posts.author_id = authors.id WHERE posts.code = :postCode";

        $stmt = $this->dbh->prepare($query);

        $stmt->bindParam(':postCode', $data, PDO::PARAM_STR, 32);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC)[0];
    }

}