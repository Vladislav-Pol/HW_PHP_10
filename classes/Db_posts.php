<?php


class Db_posts extends Db
{
    protected $config;
    protected $mySqlI;

    public function getCategories()
    {
        return $this->get('categories', ['code', 'name'], null, ['name' => 'ASC']);
    }

    public function getPreviewData($categoryCode = '', $offset = 0)
    {
        $query = "SELECT title, date, P.code AS post_code, C.code AS cat_code FROM posts P JOIN categories C ON p.category_id = C.id WHERE P.active = 1 ";
        if($categoryCode != ''){
            $categoryCode = $this->mySqlI->real_escape_string($categoryCode);
            $query .= "AND C.code = '$categoryCode' ";
        }
        $query .= "ORDER BY P.date DESC LIMIT {$this->mySqlI->real_escape_string($offset)}, {$this->config['prevLimit']}";

        $result = $this->mySqlI->query($query);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getCountPosts($categoryCode = '')
    {
        $query = "SELECT COUNT(title) FROM posts P JOIN categories C ON p.category_id = C.id WHERE P.active = 1 ";

        if($categoryCode != ''){
            $categoryCode = $this->mySqlI->real_escape_string($categoryCode);
            $query .= "AND C.code = '$categoryCode' ";
        }

        $result = $this->mySqlI->query($query);
        return $result->fetch_all()[0][0];
    }

    public function getPrevLimit()
    {
        return $this->config['prevLimit'];
    }

    public function getPost($data)
    {
        $query = "SELECT * FROM posts JOIN authors ON posts.author_id = authors.id WHERE posts.code = '{$this->mySqlI->real_escape_string($data)}'";
        $result = $this->mySqlI->query($query);
        return $result->fetch_all(MYSQLI_ASSOC)[0];
    }

}