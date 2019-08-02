<?php
define('BASE_URL', 'https://chatapi.viber.com/pa/');
define('HTTPHEADER', array(
    "Cache-control: no-cache",
    "Content-Type: application/JSON",
    "X-Viber-Auth-Token: 4a11e5b83c27d53a-f55357eaca14220c-400e6bedd901fe9"));
define('COMMAND_OFFSET', 990);
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$viber = json_decode(file_get_contents('php://input'));
if($viber->event == 'conversation_started'):
    $message = array(
        'receiver'=>$viber->user->id,
        'type' => 'text',
        'text' => 'Hello'
    );
    exec_json('send_message', $message);
endif;    
if($viber->event == 'message'):
    $labels = array();
    for ($i = 0; $i < 20; $i++):
        array_push($labels, $i);
    endfor;
    $keyboard = new Keyboard;
    //$keyboard->setParams($labels, 14);
    $message['receiver'] = $viber->sender->id;
    $message['type'] = "text";
    $message['text'] = "Choose an option please";
    $message['keyboard'] = $keyboard->getKeyboard();
    exec_json('send_message', $message);
endif;



function exec_json($command, $param=NULL){
$curl = curl_init();
curl_setopt_array($curl,array(
    CURLOPT_URL => BASE_URL.$command,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => json_encode($param),
    CURLOPT_HTTPHEADER => HTTPHEADER
));
$response = curl_exec($curl);
$err = curl_error($curl); 
curl_close($curl); 
if ($err):
    file_put_contents('CURLErrors.txt', $err,FILE_APPEND);
endif;
return $response;
}

class Button{
    private $columns = 2;
    private $rows = 1;
    private $actionType = 'reply';
    private $bgColor = '#a8aaba';
    public function setColumns($colunms){
        $this->columns = $colunms;
    }
    public function setRows($rows){
        $this->rows = $rows;
    }
    public function setActionType($type){
        $this->actionType = $type;
    }
    public function setBgColor($color){
        $this->bgColor = $color;
    }
    public function getButton($action,$text){
        return array(
            'Columns' => $this->columns,
            'Rows' => $this->rows,
            'Text' => $text,
            'ActionType' => $this->actionType,
            'ActionBody' => $action,
            'BgColor' => $this->bgColor
        );
    }
}

class Keyboard{
    private $offset;
    private $rest;
    private $buttons = array();
    
    //public function __construct($offset) {
    //    $this->offset = $offset;
    //}

    public function getKeyboard(){
        $button = new Button;
        array_push($this->buttons,$button->getButton(9999,'new label'));
        return array("Type" => "keyboard",
            "Buttons" => $this->buttons
			);
        /*
        $length = count($labels);
        if($length > ($offset + 7)):
            $this->rest = 9;
        else:
            $this->rest = $length - $offset + 1;
        endif;
        $j = $offset;
        while(intdiv($this->rest,3)> 0):
            for($i = 0; $i < 3; $i++):
                $sign = $this->offset + $j + 1;
                $actionBody = $sign.'-'.$action;
                $button = new Button;
                array_push($this->buttons,$button->getButton($actionBody,$labels[$j]));
                $j = $j + 1;
            endfor; 
            $this->rest -= 3;
        endwhile;
        //self::lastString($j, $offset, $labels, $action);
        return array('Type' => 'keyboard','Buttons' => $this->buttons);*/
    }
    
    private function lastString($j, $offset, $labels, $action =''){
        $prevscreen = $offset/7 + $this->offset + COMMAND_OFFSET;
        $nextscreen = $prevscreen +1;
        switch($this->rest):
            case 1:
                self::oneButton($prevscreen, $action);
                break;
            case 2:
                self::twoButtons($labels[$j], $prevscreen, $j, $action);
                break;
            default:
                self::threeButtons($labels[$j], $prevscreen, $nextscreen, $j, $action);
        endswitch;
    }

    private function oneButton($prevscreen, $action){
        $button = new Button;
        $button->setColumns(6);
        array_push($this->buttons, $button->getButton($prevscreen.$action, '<PREV'));
    }
    
    private function twoButtons($label, $prevscreen, $j, $action){
        $button = new Button;
        $offset = $this->offset + $j;        
        array_push($this->buttons, $button->getButton($offset.$action, $label));
        $button1 = new Button;
        $button1->setColumns(4);
        array_push($this->buttons, $button1->getButton($prevscreen.$action, '<PREV'));
    }
    
    private function threeButtons($label, $prevscreen, $nextscreen, $j, $action) {
        $offset = $this->offset + $j; 
        $button = new Button;
        array_push($this->buttons, $button->getButton($prevscreen.$action, '<PREV'));
        $button1 = new Button;        
        array_push($this->buttons, $button1->getButton($offset.$action, $label));
        $button2 = new Button;
        array_push($this->buttons, $button2->getButton($nextscreen.$action, 'NEXT>'));
        
    }
}