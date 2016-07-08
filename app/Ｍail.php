<?php
namespace App;

class Mail{
    //非全局
    const TO_USER_TYPE = 'user';
    //全局
    const GLOBAL_TYPE = 'global';

    private $baseUrl;

    public function __construct()
    {
        $this->baseUrl = getenv('MESSAGE_SERVER_URI');
    }

    //获取消息列表
    public function getAllMessage($namespace='main', $page=1, $per_page=20){
        $url = $this->buildRequestBaseUrl('/messages',
            ['page' => $page, 'namespace' => $namespace, 'per_page' => $per_page]);
        return $this->sendGetRequest($url);
    }

    //获取未读消息
    public function getUnreadMessage($userId, $namespace='main', $page=1){
        $url = $this->buildRequestBaseUrl('/users/{user_id}/unread_messages',
            ['user_id' => $userId, 'namespace' => $namespace, 'page' => $page]
        );

        return $this->sendGetRequest($url);
    }

    //获取用户未读站内信数量
    public function getUnreadMessageCount($userId, $namespace='main'){
        $url = $this->buildRequestBaseUrl('/users/{user_id}/unread_messages_count',
            ['user_id' => $userId, 'namespace' => $namespace]
        );

        return $this->sendGetRequest($url);
    }

    //获取用户已读站内信
    public function getUserMessage($userId, $namespace='main', $page=1){
        $url = $this->buildRequestBaseUrl('/users/{user_id}/read_messages',
            ['user_id' => $userId, 'namespace' => $namespace, 'page' => $page]
        );

        return $this->sendGetRequest($url);
    }

    //读消息
    public function readMessage($userId, $messageId){
        $url = $this->buildRequestBaseUrl('/messages/read');
        return $this->sendPostRequest($url, ['user_id' => $userId, 'message_id' => $messageId]);
    }

    //获取Merge消息
    public function getMergedMessage($userId, $namespace='main', $page=1, $per_page=20){
        $url = $this->buildRequestBaseUrl('/users/{user_id}/merged_messages',
            ['user_id' => $userId, 'namespace' => $namespace, 'page' => $page, 'per_page' => $per_page]);
        return $this->sendGetRequest($url);
    }

    //获取消息内容
    public function getMessage($messageId){
        $url = $this->buildRequestBaseUrl('/messages/{message_id}', ['message_id' => $messageId]);
        return $this->sendGetRequest($url);
    }

    //删除消息
    public function deleteMessage($messageId){
        $url = $this->buildRequestBaseUrl('/messages/{message_id}', ['message_id' => $messageId]);
        return $this->sendDeleteRequest($url);
    }

    private function sendDeleteRequest($url){
        try{
            $response = \Requests::DELETE($url);
            $res = json_decode($response->body, true);
            return $res;
        }catch (\Exception $e){
            return false;
        }
    }

    private function sendPostRequest($url, $params){
        try{
            $response = \Requests::POST($url, [], $params);
            $res = json_decode($response->body, true);
            return $res;
        }catch (\Exception $e){
            return false;
        }
    }

    private function sendPatchRequest($url, $params){
        try{
            $response = \Requests::PATCH($url, [], $params);
            $res = json_decode($response->body, true);
            return $res;
        }catch (\Exception $e){
            return false;
        }
    }

    private function sendGetRequest($url){
        try{
            $response = \Requests::get($url);
            $res = json_decode($response->body, true);
            return $res;
        }catch (\Exception $e){
            return false;
        }
    }

    //创建站内信
    public function createMessage($params){
        $url = $this->buildRequestBaseUrl('/messages');
        return $this->sendPostRequest($url, $params);
    }

    //修改消息
    public function updateMessage($messageId, $params){
        $url = $this->buildRequestBaseUrl('/messages/{message_id}', ['message_id' => $messageId]);
        return $this->sendPatchRequest($url, $params);
    }

    //获取已读消息记录
    public function getReadMessageLogs($params){
        $url = $this->buildRequestBaseUrl('/messages/read_status', $params);
        return $this->sendGetRequest($url);
    }

    private function buildRequestBaseUrl($url, $params=[]){
        $url =  "{$this->baseUrl}{$url}";
        if(!!$params){
            $queryString = [];

            foreach ($params as $key => $value){
                $replace_key = "{".$key."}";
                if(!$value){
                    continue;
                }
                if(strpos($url, $replace_key) !== false){
                    $url = str_replace($replace_key, $value, $url);
                }else{
                    array_push($queryString, $key . '=' . $value);
                }
            }

            if(!!$queryString){
                $url .= '?' . implode('&', $queryString);
            }
        }
        return $url;
    }
}