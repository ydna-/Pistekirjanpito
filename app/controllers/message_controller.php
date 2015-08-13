<?php

class MessageController extends BaseController {

    public static function message_list() {
        self::check_is_teacher();
        $messages = Message::all();
        View::make('message/list.html', array('messages' => $messages));
    }

    public static function message_accept($id) {
        //self::check_is_teacher();
        $message = Message::find($id);
        if ($message->sender_is_teacher) {
            $attributes = array(
                'name' => $message->sender_name,
                'email' => $message->sender_email,
                'password' => $message->sender_password,
                'is_teacher' => 1
            );
        } else {
            $attributes = array(
                'name' => $message->sender_name,
                'email' => $message->sender_email,
                'password' => $message->sender_password,
                'is_teacher' => 0
            );
        }
        $user = new User($attributes);
        $user->save();
        $message->destroy();
        Redirect::to('/messages', array('message' => 'Pyyntö on hyväksytty!'));
    }

    public static function message_destroy($id) {
        self::check_is_teacher();
        $message = new Message(array('id' => $id));
        $message->destroy();
        Redirect::to('/messages', array('message' => 'Pyyntö on hylätty!'));
    }

}
