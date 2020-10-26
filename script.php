<?
require($_SERVER['DOCUMENT_ROOT'].'/wp-load.php');

$count = 100;
$page=1;

//url
$url_path = 'https://urpravovoen.ru/wp-json/wp/v2/posts?per_page='.$count.'&page='.$page;

//в get запросе параметров нет
$data = array();

//заголовки запроса
$options = array(
    'http' => array(
        'method' => 'GET',
        'content' => http_build_query($data))
);
$stream = stream_context_create($options);
$result = file_get_contents(
    $url_path, false, $stream);

//результат запроса
$result = json_decode($result, true);
//vardump($result);

//перебор постов с другово сайта
foreach($result as $post){

    //получаем мета данные
    $metas = get_post_meta( $post['id'] );

    //категория / не продолжаем
    if ($metas['category'][0] == '1')
        continue;

    if (!$post['content']['rendered'])
        continue;

    if (!$post['title']['rendered'])
        continue;

    if (!$post['categories'])
        $post['categories'] = [];

    $post['content']['rendered'] = html_entity_decode($post['content']['rendered']);

    // Создаем массив данных новой записи
    $post_data = array(
        'post_title'    => 'от 26.10.20: '.$post['title']['rendered'],
        'post_content'  => $post['content']['rendered'],
        'post_status'   => 'publish',
        'post_type'     => 'post',
        'post_author'   => $post['author'],
        'post_category' => $post['categories']
    );

    //добавление поста
    $post_id = wp_insert_post( $post_data );


    //предпросмотр
    vardump($post['id']);
    vardump($post['title']['rendered']);
    ?><hr><?php




}


function vardump($var) {
    echo '<pre>';
    var_dump($var);
    echo '</pre>';
}
?>
