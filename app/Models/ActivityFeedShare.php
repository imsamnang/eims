<?php

namespace App\Models;


use App\Helpers\ImageHelper;
use App\Helpers\VideoHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ActivityFeedShare extends Model
{
    public static function getData($activity_feed_id, $id = null)
    {

        $get = ActivityFeedShare::orderBy('id', 'desc');
        if ($activity_feed_id) {
            $get = $get->where('activity_feed_id', $activity_feed_id);
        }

        if ($id) {
            $get = $get->where('id', $id);
        }

        $get = $get->get()->toArray();
        if ($get) {
            foreach ($get as $key => $row) {
                $node = ActivityFeed::where('id', $row['node_id'])->first()->toArray();
                $data  = array(
                    'feed_id' => $row['node_id'],
                    'node'    => [
                        'id'            => $node['id'],
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
                        'type'          => $node['type'],
                        'who_see'       => $node['who_see'],
                        'post_message'  => $node['post_message'],
                        'theme_color'   => $node['theme_color_id'] ? ThemesColor::getData($node['theme_color_id'])['data'][0] : null,
                        'media'         => ActivityFeedMedia::getData($node['id'])['data'],
                        'link'          => ActivityFeedLink::getData($node['id'])['data'],
                        'share'         => ActivityFeedShare::getData($node['id'])['data'],
                        'created_at'    => $node['created_at'],
                        'updated_at'    => $node['updated_at'],
                    ]
                );
            }

            $response       = array(
                'success'   => true,
                'data'      => $data,
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


    public static function addToTable($activity_feed_id, $node_id)
    {
        $response = [
            'success'   => false,
            'message'   => __('Share Unsuccessful'),
        ];
        if ($activity_feed_id && $node_id) {
            $add = ActivityFeedShare::insertGetId([
                'activity_feed_id'  => $activity_feed_id,
                'node_id'  => $node_id,
            ]);
            if ($add) {
                $response = array(
                    'success'   => true,
                    'data'      => ActivityFeedShare::getData($activity_feed_id)['data'],
                    'message'   => __('Share Successfully'),
                );
            }
        }
        return $response;
    }
}
