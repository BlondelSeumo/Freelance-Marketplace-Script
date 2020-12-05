<?php
namespace MyApp;
require dirname(__DIR__) .'/vendor/autoload.php';
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Chat implements MessageComponentInterface {
    protected $clients;     

    public function __construct(){
        //echo "hi every body\n";       
        $this->clients = array();
        
    }

    public function onOpen(ConnectionInterface $conn){
        echo "New Connection! ({$conn->resourceId})\n";
        $querystring = $conn->httpRequest->getUri()->getQuery();
        parse_str($querystring, $queryArray);
        $this->clients[$conn->resourceId]['conn'] = $conn;
        $this->clients[$conn->resourceId]['params'] = $queryArray;
        // echo "New Connection! ({$conn->resourceId})"." -- {$queryArray['message_group_id']}"."\n";
    }

    public function onMessage(ConnectionInterface $from, $data) {
        $numRecv = count($this->clients) - 1;
        echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n", $from->resourceId, $data, $numRecv, $numRecv == 1 ? '' : 's');
        $data = json_decode($data);                
        $type = $data->type;
        $message_group_id = $data->message_group_id;
        $message = $data->message;
        $file = $data->file;
        $seller_user_name = $data->seller_user_name;
        $seller_image = $data->seller_image;
        $message_date = $data->message_date;
        $message_file = $data->message_file;
        switch($type){
            case 'chat':
                foreach ($this->clients as $client) {                       
                    if($client['params']['message_group_id'] == $message_group_id){
                        $data = json_encode(array(
                            'type' => $type,
                            'message_group_id' => $message_group_id,
                            'message' => $message,
                            'file' =>  $file,
                            'seller_user_name' =>  $seller_user_name,
                            'seller_image' => $seller_image,
                            'message_date' => $message_date,
                            'message_file' => $message_file,
                        ));
                        $client['conn']->send($data);
                    }
                }                
            break;
        }
    }

    public function onClose(ConnectionInterface $conn) {
        //$this->clients->detach($conn);
        unset($this->clients[$conn->resourceId]);        
        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }

}