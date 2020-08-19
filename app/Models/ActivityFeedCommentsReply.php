<?php

namespace App\Models;

use App\Models\Users;
use App\Events\NewsFeed;

use App\Helpers\MentionHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class ActivityFeedCommentsReply extends Model
{

    public static function getData($activity_feed_comment_id, $id = null, $paginate = null)
    {
        $pages = [];
        $get = ActivityFeedCommentsReply::orderBy('id', 'ASC')->orderBy('created_at', 'DESC');
        if ($activity_feed_comment_id) {
            $get = $get->where('activity_feed_comment_id', $activity_feed_comment_id);
        }
        if ($id) {
            $get = $get->where('id', $id);
        }
        if ($paginate) {
            $get = $get->paginate($paginate)->toArray();
            foreach ($get as $key => $value) {
                if ($key == 'data') {
                } else {
                    $pages[$key] = $value;
                }
            }
            $get = $get['data'];
        } else {
            $get = $get->get()->toArray();
        }
        if ($get) {
            foreach ($get as $key => $row) {
                $data[$key]         = array(
                    'comment_id'    => $row['activity_feed_comment_id'],
                    'id'            => $row['id'],
                    'user'          => Users::where('id', $row['user_id'])->get()->map(function ($row) {
                        $row['profile'] = ImageHelper::site(Users::$path['image'], $row['profile']);
                        $row['role'] = Roles::where('id', $row->role_id)->pluck(app()->getLocale())->first();
                        $row['action']  = [
                            'edit'   => url(Users::role() . '/' . Users::$path['url'] . '/edit/' . $row['id']),
                            'view'   => url(Users::role() . '/' . Users::$path['url'] . '/view/' . $row['id']),
                            'delete' => url(Users::role() . '/' . Users::$path['url'] . '/delete/' . $row['id']),
                        ];

                        return $row;
                    })->first(),
                    'comment'       => $row['comment'],
                    'type'          => $row['type'],
                    'mention'       => MentionHelper::mention($row['comment']),
                    'created_at'    => $row['created_at'],
                    'updated_at'    => $row['updated_at'],
                );
            }

            $response       = array(
                'success'   => true,
                'data'      => $data,
                'pages'     => $pages
            );
        } else {
            $response = array(
                'success'   => false,
                'data'      => [],
                'message'   => __('No Data'),
            );
        }
        return $response;
    }
    public static function addToTable()
    {
        $activity_feed_comment_id = request('comment_id');
        if ($activity_feed_comment_id && request('comment')) {
            $add = ActivityFeedCommentsReply::insertGetId([
                'activity_feed_comment_id'  => $activity_feed_comment_id,
                'user_id'           => Auth::user()->id,
                'comment'           => trim(request('comment')),
                'type'              => request('type')  ? trim(request('type')) : 'text',
            ]);
            if ($add) {
                $replied = ActivityFeedCommentsReply::getData($activity_feed_comment_id, $add);
                ActivityFeedNotifacation::addToTable('replied', $add);
                event(new NewsFeed($replied, 'replied-event'));
                return [
                    'success'   => true,
                    'data'      => $replied['data'],
                    'message'   => __('Replied Successfully'),
                ];
            }
        }
    }
}
