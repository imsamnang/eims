<?php

namespace App\Models;

use App\Events\NewsFeed;
use Highlight\Highlighter;

use App\Helpers\ImageHelper;
use App\Helpers\MentionHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class ActivityFeed extends Model
{
    /**
     *  @param string $key
     *  @param string|array $key
     */
    public static function path($key = null)
    {
        $table = (new self)->getTable();
        $path = [
            'image'  => $table,
            'video'  => $table,
            'url'    => str_replace('_', '-', $table),
            'view'   => str_replace(' ', '', ucwords(str_replace('_', ' ', $table)))
        ];
        return $key ? @$path[$key] : $path;
    }
    public static function getData($id = null, $paginate = 10)
    {

        $pages = [];
        $data = array();
        $get = ActivityFeed::where('status', 'active')
            ->orderBy('id', 'DESC');
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

                $reaction = ActivityFeedReaction::getData($row['id']);

                $feed = array(
                    'id'            => $row['id'],
                    'user'          => Users::where('id', $row['user_id'])->get()->map(function ($row) {
                        $row['profile'] = ImageHelper::site(Users::path('image'), $row['profile']);
                        $row['role'] = Roles::where('id', $row->role_id)->pluck(app()->getLocale())->first();
                        $row['action']  = [
                            'edit'   => url(Users::role() . '/' . Users::path('url') . '/edit/' . $row['id']),
                            'view'   => url(Users::role() . '/' . Users::path('url') . '/view/' . $row['id']),
                            'delete' => url(Users::role() . '/' . Users::path('url') . '/delete/' . $row['id']),
                        ];

                        return $row;
                    })->first(),
                    'type'          => $row['type'],
                    'who_see'       => $row['who_see'],
                    'post_message'  => $row['post_message'], //$highlighter->highlight($language, $row['post_message'])->value,
                    'mention'       => MentionHelper::mention($row['post_message']),
                    'theme_color'   => $row['theme_color_id'] ? ThemesColor::getData($row['theme_color_id'])['data'][0] : null,
                    'reaction'      => $reaction['data'],
                    'media'         => ActivityFeedMedia::getData($row['id'])['data'],
                    'link'          => ActivityFeedLink::getData($row['id'])['data'],
                    'comment'       => ActivityFeedComment::getData($row['id'])['data'],
                    'share'         => ActivityFeedShare::getData($row['id'])['data'],
                    'created_at'    => $row['created_at'],
                    'updated_at'    => $row['updated_at'],
                    'action'        => [
                        'edit' => url(Users::role() . '/' . ActivityFeed::path('url') . '/edit/' . $row['id']),
                        'view' => url(Users::role() . '/' . ActivityFeed::path('url') . '/view/' . $row['id']),
                        'delete' => url(Users::role() . '/' . ActivityFeed::path('url') . '/delete/' . $row['id']),
                    ]
                );
                if ($row['who_see'] == 'only_me') {
                    if (Auth::user()) {
                        if ($row['user_id'] == Auth::user()->id) {
                            $data[] = $feed;
                        }
                    }
                } else {
                    $data[] = $feed;
                }
            }

            $response       = array(
                'success'   => true,
                'data'      => $data,
                'pages'     => $pages,
            );
        } else {
            $response = array(
                'success'   => false,
                'data'      => [],
                'pages'     => $pages,
                'message'   => __('No Data'),
            );
        }

        return $response;
    }
    public static function addToTable()
    {
        $response =  [
            'success'   => false,
            'message'   => __('Post Unsuccessful'),
            'data'      => []
        ];

        $values = [
            'post_message'   => trim(request('post_message')),
            'user_id'        => Auth::user()->id,
            'type'           => trim(request('type')),
        ];
        $add = ActivityFeed::insertGetId($values);
        if (request('who_see')) {
            ActivityFeed::updateWhoSeeToTable($add, request('who_see'));
        }
        if ($add) {

            if (trim(request('type')) == 'text') {
                ActivityFeed::updateThemeColorToTable($add, request('theme_color'));
            } elseif (request('type')  == 'media') {
                ActivityFeedMedia::addToTable($add);
            } elseif (trim(request('type')) == 'link') {
                ActivityFeedLink::addToTable($add);
            }
            $feed =  ActivityFeed::getData($add, false);
            if ($feed['data'][0]['who_see'] == 'public') {
                ActivityFeedNotifacation::addToTable('post', $add);
                event(new NewsFeed($feed, 'post-event'));
            }
            $response = [
                'success'   => true,
                'message'   => __('Post Successfully'),
                'data'      => $feed['data']
            ];
        }
        return $response;
    }

    public static function addShareToTable()
    {
        $response =  [
            'success'   => false,
            'message'   => __('Share Unsuccessful'),
            'data'      => []
        ];
        $values = [
            'post_message'   => trim(request('post_message')),
            'user_id'        => Auth::user()->id,
            'type'           => 'share',
        ];
        $add = ActivityFeed::insertGetId($values);
        if (request('who_see')) {
            ActivityFeed::updateWhoSeeToTable($add, request('who_see'));
        }

        if ($add && ActivityFeedShare::addToTable($add, request('node_id'))['success']) {

            $feed =  ActivityFeed::getData($add, false);
            if ($feed['data'][0]['who_see'] == 'public') {
                ActivityFeedNotifacation::addToTable('share', $add);
                event(new NewsFeed($feed, 'post-event'));
            }

            $response = [
                'success'   => true,
                'message'   => __('Share Successfully'),
                'data'      => $feed['data']
            ];
        }
        return $response;
    }

    public static function updateTypeToTable($id, $type)
    {
        if ($id && $type) {
            $update =  ActivityFeed::where('id', $id)->update([
                'type'  => $type
            ]);
            if ($update) {
                return [
                    'success'   => true,
                    'message'   => __('Update Successfully')
                ];
            }
        }
    }

    public static function updateThemeColorToTable($id, $theme_color_id)
    {
        if ($id && $theme_color_id) {
            $update =  ActivityFeed::where('id', $id)->update([
                'theme_color_id'  => $theme_color_id
            ]);
            if ($update) {
                return [
                    'success'   => true,
                    'message'   => __('Update Successfully')
                ];
            }
        }
    }
    public static function updateWhoSeeToTable($id, $who_see)
    {
        if ($id && $who_see) {
            $update =  ActivityFeed::where('id', $id)->update([
                'who_see'  => $who_see
            ]);
            if ($update) {
                return [
                    'success'   => true,
                    'message'   => __('Update Successfully')
                ];
            }
        }
    }
}
