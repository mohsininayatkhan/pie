<?php

class MessageArchiv extends Eloquent
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'ad_message_statuses';
    protected $fillable = array('conversation_id', 'user_id');
    
    
    public function getMessages($user_id, $type='inbox')
    {
        return self::where('ad_messages.reciever_id','=',$user_id)
            ->join('ads', 'ads.id', '=', 'ad_messages.ad_id')
            ->join('users', 'users.id', '=', 'ad_messages.sender_id')
            ->select('ads.id as adid', 'ads.title', 'users.slug as user_slug', 'ads.unique_id', 'ad_messages.subject', 'ad_messages.message', 'ad_messages.id as mid', 'ad_messages.conversation_id', 'users.fname', 'users.lname', 'ad_messages.created_at')
            ->groupBy('ad_messages.conversation_id')
            ->orderBy('ad_messages.id','DESC')
            ->paginate(20);
    }
}