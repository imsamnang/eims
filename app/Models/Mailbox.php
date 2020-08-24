<?php

namespace App\Models;

use App\Helpers\Encryption;

use App\Http\Requests\FormMailboxes;
use DomainException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use PHPHtmlParser\Dom;

class Mailbox extends Model
{
    /**
     *  @param string $key
     *  @param string|array $key
     */
    public static function path($key = null)
    {
        $table = (new self)->getTable();
        $tableUcwords = str_replace(' ', '', ucwords(str_replace('_', ' ', $table)));

        $path = [
            'image'  => $table,
            'url'    => str_replace('_', '-', $table),
            'view'   => $tableUcwords,
            'requests'   => 'App\Http\Requests\Form'.$tableUcwords,
        ];
        return $key ? @$path[$key] : $path;
    }
    public static function getData($id = null, $type = 'list', $paginate = null, $search = null)
    {
        $pages = [];
        $get = Mailbox::where('recipient', Auth::user()->id)
            ->whereNotIn('id', MailboxTrash::select('mailbox_id')->where('user_id', Auth::user()->id)->get())
            ->whereNotIn('id', MailboxImportant::select('mailbox_id')->where('user_id', Auth::user()->id)->get());

        $orderBy = 'DESC';
        if ($id) {
            $id  =  gettype($id) == 'array' ? $id : explode(',', Encryption::decode($id));
            $sorted = array_values($id);
            sort($sorted);
            if ($id === $sorted) {
                $orderBy = 'ASC';
            } else {
                $orderBy = 'DESC';
            }
        }
        if ($id) {
            $get = $get->whereIn((new Mailbox)->getTable() . '.id', $id);
        } else {
            if ($search) {
                $get = $get->where(function ($q) use ($search) {
                    $q->where('subject', 'LIKE', '%' . $search . '%');
                    $q->orWhere('message', 'LIKE', '%' . $search . '%');
                });
            }
        }


        $get = $get->orderBy((new Mailbox)->getTable() . '.id', $orderBy);

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
            $data = [];
            $dom = new Dom();
            foreach ($get as $key => $row) {
                $id = Encryption::encode($row['id']);
                $read_or_unread = MailboxRead::existsFromTable($row['id'], Auth::user()->id);
                $data[$key] = [
                    'read_or_unread'    => $read_or_unread ? 'read' : 'unread',
                    'id'        => $id,
                    'user'      => ($row['from'] == Auth::user()->id) ? Users::getData($row['recipient'])['data'][0] : Users::getData($row['from'])['data'][0],
                    'from'      => Users::getData($row['from'])['data'][0],
                    'recipient' => Users::getData($row['recipient'])['data'][0],
                    'subject' => $row['subject'],
                    'created_at' => $row['created_at'],
                    'action'    => [
                        'view'  => url(Mailbox::path('url') . '/inbox/view/' . $id),
                        'mark_read'  => url(Mailbox::path('url') . '/inbox/mark-read/' . $id),
                        'mark_important'  => url(Mailbox::path('url') . '/inbox/mark-important/' . $id),
                        'move_trash'  => url(Mailbox::path('url') . '/inbox/move-trash/' . $id),
                    ]
                ];

                if ($type == 'view') {
                    $data[$key]['message'] = $row['message'];
                    $dom->load($row['message']);
                    $data[$key]['attachment_images']  =  Mailbox::getAttachmentImages($dom->find('img'));
                }
            }
            $response = [
                'success'   => true,
                'data'   => $data,
                'pages' => $pages
            ];
        } else {
            $response = [
                'success'   => false,
                'data'   => [],
                'message'   => __('No Data'),
                'pages' => $pages
            ];
        }

        return $response;
    }





    public static function getAttachmentImages($images)
    {
        if ($images) {
            $data = [];
            foreach ($images as $key => $image) {
                $data[] = $image->getTag()->getAttribute('src')['value'];
            }
            return $data;
        }
    }

    public static function countUnread()
    {
        return Mailbox::where('recipient', Auth::user()->id)
            ->whereNotIn('id', MailboxRead::select('mailbox_id')->where('user_id', Auth::user()->id))
            ->count();
    }

    public static function addToTable()
    {
        $response           = array();
        $validator          = Validator::make(request()->all(), FormMailboxes::rules('.*'), FormMailboxes::messages(), FormMailboxes::attributes());

        if ($validator->fails()) {
            $response       = array(
                'success'   => false,
                'errors'    => $validator->getMessageBag(),
            );
        } else {
            try {
                $values = [];
                foreach (request('recipient') as  $recipient) {
                    $values[] = [
                        'from'  => Auth::user()->id,
                        'recipient'  => $recipient,
                        'subject'  => trim(request('subject')),
                        'message'  => trim(request('message')),
                    ];
                }

                $add =  Mailbox::insert($values);

                if ($add) {
                    $response       = array(
                        'success'   => true,
                        'type'      => 'add',
                        'message'   => array(
                            'title' => __('Success'),
                            'text'  => __('Send message successfully'),
                            'button'   => array(
                                'confirm' => __('Ok'),
                                'cancel'  => __('Cancel'),
                            ),
                        ),
                    );
                }
            } catch (DomainException $e) {
                return $e;
            }
        }
        return $response;
    }


    public static function deleteFromTable($mailbox_encode_id)
    {
        if ($mailbox_encode_id) {
            $id  = gettype($mailbox_encode_id) == 'array' ? $mailbox_encode_id : explode(',', $mailbox_encode_id);
            $mailbox = [];
            foreach ($id as $value) {
                $mailbox[] = Encryption::decode($value);
            }
            if (request()->method() === 'POST') {
                $delete = Mailbox::whereIn('id', $mailbox)
                    ->delete();
                if ($delete) {
                    $response       =  array(
                        'success'   => true,
                        'message'   => array(
                            'title' => __('Delete!'),
                            'text'  => __('Delete Successfully'),
                            'button'   => array(
                                'confirm' => __('Ok'),
                                'cancel'  => __('Cancel'),
                            ),
                        ),
                    );
                }
            } else {
                $response = response(
                    array(
                        'success'   => true,
                        'message'   => array(
                            'title' => __('Are you sure?'),
                            'text'  => __('You wont be able to revert this!') . PHP_EOL .
                                'ID : (' . implode(',', $id) . ')',
                            'button'   => array(
                                'confirm' => __('Yes delete!'),
                                'cancel'  => __('Cancel'),
                            ),
                        ),
                    )
                );
            }
        }
        return $response;
    }
}
