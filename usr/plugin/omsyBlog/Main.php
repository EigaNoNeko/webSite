<?php
class omsyBlog {
    private $app_id = null;
    private $monday = [
        ['num' => 1, 'name' => "janvier", 'day' => 31],
        ['num' => 2, 'name' => "février", 'day' => 31],
        ['num' => 3, 'name' => "mars", 'day' => 31],
        ['num' => 4, 'name' => "avril", 'day' => 31],
        ['num' => 5, 'name' => "mai", 'day' => 31],
        ['num' => 6, 'name' => "juin", 'day' => 31],
        ['num' => 7, 'name' => "juillet", 'day' => 31],
        ['num' => 8, 'name' => "août", 'day' => 31],
        ['num' => 9, 'name' => "septembre", 'day' => 31],
        ['num' => 10, 'name' => "octobre", 'day' => 31],
        ['num' => 11, 'name' => "novembre", 'day' => 31],
        ['num' => 12, 'name' => "décembre", 'day' => 31]
    ];

    function __construct() {
        $this->app_id = $_GET['app_id'] ?? 'sss';

        define('__OmsyBlog', $this);
    }

    public function getArticleByUser($uniqid) {
        $data = [];

        $blogs = __OmsySql->execute('SELECT * FROM omsyblog_single WHERE blog_author = ? ORDER BY ID DESC', [$uniqid])->fetchAll();
        foreach($blogs as $key => $value) {
            $data[] = $value;
        }

        return $data;
    }

    public function markdown_parse($string) {
        return nl2br($string);
    }

    public function getAllPost($int = 12) {
        $blogs = __OmsySql->execute('SELECT * FROM omsyblog_single ORDER BY ID DESC LIMIT '.$int)->fetchAll();
        $blogsGroup = __OmsySql->execute('SELECT * FROM omsyblog_group')->fetchAll();

        foreach ($blogs as $key => $value) {
            $blogs[$key]['tag'] = $value['blog_group'];
            $blogs[$key]['date_string'] = $value['date_time'];
            $blogs[$key]['link'] = '/blog/'.$blogs[$key]['tag'].'/'.$value['blog_slug'];
        }
        return $blogs;
    }

    public function convertirDateSQL($date_sql) {
        $dateN = new DateTime($date_sql);
        $date_sql = $dateN->format('Y-m-d');

        // Définition de la date actuelle et de hier
        $aujourdhui = date('Y-m-d');
        $hier = date('Y-m-d', strtotime('-1 day'));

        if ($date_sql == $aujourdhui) {
            return "Aujourd'hui";
        } elseif ($date_sql == $hier) {
            return "Hier";
        }

        // Création d'un objet DateTime
        $date = new DateTime($date_sql);

        // Création du formateur de date en français
        $formatter = new IntlDateFormatter(
            'fr_FR',
            IntlDateFormatter::FULL,
            IntlDateFormatter::NONE,
            'Europe/Paris',
            IntlDateFormatter::GREGORIAN,
            'dd MMMM yyyy' // Format : 02 février 2025
        );

        return ucfirst($formatter->format($date));
    }

    public function search($query) {
        $search = '%'.htmlentities($query).'%';
        $results = __OmsySql->execute(
            'SELECT * FROM omsyblog_single WHERE (blog_title LIKE ? OR blog_content LIKE ? OR blog_tags LIKE ? OR blog_group LIKE ?)',
            [$search, $search, $search, $search]
        )->fetchAll();
        return $results;
    }
}
