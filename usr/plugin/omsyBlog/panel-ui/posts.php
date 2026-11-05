<?php
$getAllPosts = __OmsySql->sqlPagex('omsyblog_single', 'ORDER BY ID DESC', 8);

$buff = [];
foreach ($getAllPosts['items'] as $key => $value):
    $buff[] = [
        "title" => '<a class="username_avatar" href="/blog/'.$value['blog_group'].'/'.$value['blog_slug'].'" target="_blank">
            <span class="image"><img src="'.($value['blog_min'] ?? '/use/img/freinds/enn.webp').'" width="32px" height="32px" style="border-radius: 4px; object-fit: cover;"></span>
            <div class="text">
                <span class="text">'.(__OmsyMain->GFunction->htmlDecode($value['blog_title']) ?? 'Data is ot found').'</span>
                <span class="sub_text"></span>
            </div>
        </a>',
        "Group" => __OmsyMain->GFunction->htmlDecode($value['blog_group']),
        "Date" => __OmsyMain->GFunction->htmlDecode($value['date_time']),
        "omsyPanel.table.options" => [
            "edit" => [
                "value" => $value['ID'],
                "page" => "post"
            ],
            "remove" => [
                "value" => $value['ID'],
                "page" => "posts"
            ]
        ]
    ];
endforeach;
?>
<?= omsp_header() ?>
<section class="kit-head">
    <div class="kit-grid-contenaire">
        <div class="information">
            <h2>Liste des articles</h2>
        </div>
    </div>
    <div class="kit-grid-contenaire">
        <ul>
            <li>
                <a href="/panel/getter?plugin=omsyBlog&page=post">
                    <span class="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e3e3e3"><path d="M460-460H240v-40h220v-220h40v220h220v40H500v220h-40v-220Z"/></svg>
                    </span>
                    <span class="name">Créer un article</span>
                </a>
            </li>
        </ul>
    </div>
</section>
<section class="kit-contenaire">
<?php
SQLX_pagetab($getAllPosts);
SQLX_table([
    "head" => [
        "Name",
        "Group",
        "Date",
    ],
    "body" => $buff
]);
SQLX_pagetab($getAllPosts);
?>
</section>
<?php if(isset($_GET['remove'])): ?>
<script>
Omsy.request('/api/OmsyBlog/v1/delete_post', {
	id: <?= $_GET['remove'] ?>
})
.then((e) => {
	console.log(e)
})
</script>
<?php endif; ?>
<?= omsp_footer() ?>