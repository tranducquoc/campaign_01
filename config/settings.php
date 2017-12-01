<?php

return [
    'admin_system' => 'admin system',
    'comment_parent' => 0,
    'search_default' => 0,
    'paginate_default' => 15,
    'paginate_notification' => 5,
    'paginate_comment' => 2,
    'paginate_event' => 4,
    'flag_join' => 'join',
    'locale' => ['vi', 'en', 'ja'],
    'campaigns' => [
        'status' => 1, // set campaign public or private
        'limit' => 2, // limit user join campaign
        'timeout_campaign' => 3, // set timeout for campaign
        'start_day' => 9,
        'end_day' => 10,
        'not_approve' => 0,
        'approve' => 1,
    ],
    'events' => [
        'start_day' => 7,
        'end_day' => 8,
        'timeout_event' => 4, // set timeout for event
        'set_image' => 5, // set image for event
        'set_video' => 6, // set video for event
    ],
    'actions' => [
        'paginate_in_event' => 4,
    ],
    'value_of_settings' => [
        'status' => [ // key of setting
            'private' => 0,
            'public' => 1,
        ],
    ],
    'pagination' => [
        'follow' => 12,
        'timeline' => 5,
        'following_campaign' => 3,
        'like' => 20,
        'action' => 8,
        'expense_statistic' => 5,
        'friend' => 14,
        'photo' => 16,
        'homepage' => 6,
        'notification' => 4,
    ],
    'campaigns_involve' => 6,
    'campaigns_public' => 4,
    'friend_suggest' => 5,
    'group_chat' => 'groupChat',
    'single_chat' => 'singleChat',
    'head_room_name' => 'hashtag:',
    'notifications' => 'notifications',
    'folderQuill' => 'quill',
    'expense' => [
        'paginate_list' => 10,
    ],
    'request' => 0,
    'approval_request' => 1,
    'read_notification_friend' => 0,
    'read_notification_action' => 1,
    'take_friend' => 9,
    'is_manager' => 1,
    'not_manager' => 0,
    'image_event_default' => '/images/image_event_default.jpg',
    'default_avatar' => 'default_avatar.png',
    'type_notification' => [
        'event' => 'event',
        'campaign' => 'campaign',
        'comment' => 'comment',
    ],
    'email' => [
        'logo' => 'http://campaign.framgia.vn/images/logo.png',
    ],
];
