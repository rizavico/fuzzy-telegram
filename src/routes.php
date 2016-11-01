<?php
// Routes
$app->get('/health', function ($request, $response, $args) {
    return $response->withJson(array("status"=>"I am alive!"));
});

$app->get('/[{category}]', function ($request, $response, $args) {
    try{
        // Fetch Quote
        $quoteClient = new \Quote($args['category'], $this->db);
        $quote = $quoteClient->fetchQuote();
    }catch(\Exception $e){
        $errorMessage = $e->getMessage();
    }

    // Any flash messages?
    if (session_status() !== PHP_SESSION_NONE) {
        $messages = $this->flash->getMessages();
    }

    // Render index view
    return $this->renderer->render($response, 'index.phtml', array("quote" => $quote, "messages"=>$messages, "error"=>$errorMessage) );
})->setName('welcome');

$app->post('/like/{id}', function ($request, $response, $args) {
    $this->db->exec("UPDATE `my_quotes` SET `likes` = `likes`+1 WHERE `quote_id`='".$args['id']."' LIMIT 1");
    $this->flash->addMessage('liked', true);
    return $response->withRedirect($this->router->pathFor('welcome'));
});
