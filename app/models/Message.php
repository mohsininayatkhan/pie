<?php
class Message extends Eloquent
{
    protected $table = 'ad_messages';

    protected $fillable = array('ad_id', 
        'subject', 
        'message', 
        'conversation_id',
        'sender_id',
        'reciever_id', 
        'sender_status', 
        'reciever_status'
    );
    
    public static function getUnreadMessages($user_id)
    {
        $user = Auth::user();
        $unread = Message::where('reciever_id', '=', $user_id)
            ->where('reciever_status', '=', 'NEW')
            ->select('ad_messages.id as mid')
            ->groupBy('ad_messages.conversation_id')
            ->get(); 
        return count($unread);
    }
      
    public static function achiveMessages($user_id)
    {
        $user = Auth::user();
        $messages = Message::where('sender_id', '=', $user_id)->orWhere('reciever_id' , '=', $user->id)
            ->join('ads', 'ads.id', '=', 'ad_messages.ad_id')
            ->join('users', 'users.id', '=', 'ad_messages.sender_id')
            ->join('ad_message_statuses', 'ad_message_statuses.conversation_id', '=', 'ad_messages.conversation_id')
            ->where('ad_message_statuses.user_id','=',$user->id)
            ->select('ad_messages.conversation_id')
            ->groupBy('ad_messages.conversation_id')
            ->orderBy('ad_messages.id','DESC')
            ->get();
            
        $conv = '';
        foreach ($messages as $msg) {
            $conv .= $msg->conversation_id.',';
        }

        $conv = substr($conv,0,strlen($conv)-1);
        return $conv;
    }

    public static function getInboxMessages($user_id, $limit)
    {
        return self::where('ad_messages.reciever_id','=',$user_id)
            ->join('ads', 'ads.id', '=', 'ad_messages.ad_id')
            ->join('users', 'users.id', '=', 'ad_messages.sender_id')
            ->select('ads.id as adid', 'ads.title', 'users.slug as user_slug', 'ads.unique_id', 'ad_messages.subject', 'ad_messages.message', 'ad_messages.id as mid', 'ad_messages.conversation_id', 'users.fname', 'users.lname', 'ad_messages.created_at', 'ad_messages.reciever_status')
            ->groupBy('ad_messages.conversation_id')
            ->orderBy('ad_messages.id','DESC')
            ->paginate($limit);
    }
    
    public static function getSentMessages($user_id, $limit)
    {
        return self::where('ad_messages.sender_id','=',$user_id)
            ->join('ads', 'ads.id', '=', 'ad_messages.ad_id')
            ->join('users as sender', 'sender.id', '=', 'ad_messages.sender_id')
            ->join('users as reciever', 'reciever.id', '=', 'ad_messages.reciever_id')
            ->select('ads.id as adid', 'ads.title', 'reciever.slug as user_slug', 'ads.unique_id', 'ad_messages.subject', 'ad_messages.message', 'ad_messages.id as mid', 'ad_messages.conversation_id', 'reciever.fname', 'reciever.lname', 'ad_messages.created_at')
            ->groupBy('ad_messages.conversation_id')
            ->orderBy('ad_messages.id','DESC')
            ->paginate($limit);
    }

    public static function getConversation($id)
    {
        return self::where('conversation_id','=',$id)
            ->join('ads', 'ads.id', '=', 'ad_messages.ad_id')
            ->join('users', 'users.id', '=', 'ad_messages.sender_id')
            ->select('ads.id as adid', 'ads.title', 'ad_messages.subject', 'ad_messages.message', 'ad_messages.id as mid', 'ad_messages.created_at', 'ad_messages.sender_id', 'ad_messages.ad_id', 'ad_messages.conversation_id', 'users.fname', 'users.lname')
            ->orderBy('ad_messages.id','DESC')->get();
    }
    
    public static function getAdConversationCount($ad_id)
    {
        $res = self::select(DB::raw('COUNT(DISTINCT conversation_id) as msg_count'))->where('ad_id','=',$ad_id)->get();
        return $res[0]['msg_count'];
    }

    public static function getAdConversation($user_id, $ad_id)
    {
        $ad_messages = DB::table('ad_messages');
        $ad_messages->where('ad_messages.reciever_id','=',$user_id)
            ->where('ads.id','=',$ad_id)
            ->join('ads', 'ads.id', '=', 'ad_messages.ad_id')
            ->join('users', 'users.id', '=', 'ad_messages.sender_id')
            ->select('ads.id as adid', 'ads.title', 'users.slug as user_slug', 'ads.unique_id', 'ad_messages.subject', 'ad_messages.message', 'ad_messages.id as mid', 'ad_messages.conversation_id', 'users.fname', 'users.lname', 'ad_messages.created_at')
            ->groupBy('ad_messages.conversation_id')
            ->orderBy('ad_messages.id','DESC')
            ->get();
    }
        
}