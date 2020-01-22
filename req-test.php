<?php
namespace Google\Cloud\Samples\Dialogflow;
use Google\Cloud\Dialogflow\V2\SessionsClient;
use Google\Cloud\Dialogflow\V2\TextInput;
use Google\Cloud\Dialogflow\V2\QueryInput;
require './vendor/autoload.php';
/*
 * Returns the result of detect intent with texts as inputs.
 * Using the same `session_id` between requests allows continuation
 * of the conversation. This function is the boilerplate that Google gives you.
function detect_intent_texts($projectId, $texts, $sessionId, $languageCode = 'en-US')
{
    // new session
    $sessionsClient = new SessionsClient();
    $session = $sessionsClient->sessionName($projectId, $sessionId ?: uniqid());
    printf('Session path: %s' . PHP_EOL, $session);

    // query for each string in array
    foreach ($texts as $text) {

        // create text input
        $textInput = new TextInput();
        $textInput->setText($text);
        $textInput->setLanguageCode($languageCode);

        // create query input
        $queryInput = new QueryInput();
        $queryInput->setText($textInput);

        // get response and relevant info
        $response = $sessionsClient->detectIntent($session, $queryInput);
        $queryResult = $response->getQueryResult();
        $queryText = $queryResult->getQueryText();
        $intent = $queryResult->getIntent();
        $displayName = $intent->getDisplayName();
        $confidence = $queryResult->getIntentDetectionConfidence();
        $fulfilmentText = $queryResult->getFulfillmentText();

        // output relevant info
        print(str_repeat("=", 20) . PHP_EOL);
        printf('Query text: %s' . PHP_EOL, $queryText);
        printf('Detected intent: %s (confidence: %f)' . PHP_EOL, $displayName,
            $confidence);
        print(PHP_EOL);
        printf('Fulfilment text: %s' . PHP_EOL, $fulfilmentText);

    }
    $sessionsClient->close();
}
*/

/**
 * This function will take a project ID, session ID, and session client, and generate a session.
 */
function get_session($projectId, $sessionId, $sessionsClient) {
    $session = $sessionsClient->sessionName($projectId, $sessionId ?: uniqid());
    return $session;
}

/**
 * This function takes a query and a number of session parameters and returns a fulfillment text response.
 */
function get_response($projectId, $text, $sessionId, $languageCode = 'en-US', $session, $sessionsClient) {
    // create text input
    $textInput = new TextInput();
    $textInput->setText($text);
    $textInput->setLanguageCode($languageCode);

    // create query input
    $queryInput = new QueryInput();
    $queryInput->setText($textInput);

    // get response and relevant info
    $response = $sessionsClient->detectIntent($session, $queryInput);
    $fulfillment = $response->getQueryResult()->getFulfillmentText();
    return $fulfillment;
}

/**
 * This function initializes a chat session with a bot and allows one to chat with it until the message 'stop' is sent, or the session is otherwise terminated.
 */
function chat_it_up() {
    $agentId = 'eddie-hysvnj';
    $sessionsClient = new SessionsClient();
    $session = get_session($agentId, 'ABCD1234', $sessionsClient);
    $inputMessage = readline('Message: ') ? : 'help';
    if ($inputMessage == 'stop') {
        return null;
    }
    else {
        $result = get_response($agentId, $inputMessage, 'ABCD1234', 'en', $session, $sessionsClient);
        print($result . PHP_EOL);
        chat_it_up();
    }
}
chat_it_up();
?>
