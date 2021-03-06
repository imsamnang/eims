<?php

namespace App\Models;

use App\Helpers\Encryption;
use DomainException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use PHPHtmlParser\Dom;

class MailboxReply extends Model
{
    public static function getData($mailbox_id)
    {
        $get = MailboxReply::where('mailbox_id', $mailbox_id)->get()->toArray();
        if ($get) {
            $data = [];
            $dom = new Dom();
            foreach ($get as $row) {
                $id = Encryption::encode($row['id']);
                $dom->load($row['message']);
                $data[] = [
                    'id'        => $id,
                    'user'      => ($row['from'] == Auth::user()->id) ? Users::getData($row['recipient'])['data'][0] : Users::getData($row['from'])['data'][0],
                    'from'      => Users::getData($row['from'])['data'][0],
                    'recipient' => Users::getData($row['recipient'])['data'][0],
                    'message' => $row['message'],
                    'created_at' => $row['created_at'],
                    'attachment_images'  =>  Mailbox::getAttachmentImages($dom->find('img')),
                    'action'    => [
                        'view'  => url(Mailbox::path('url') . '/reply/view/' . $id),
                        'mark_read'  => url(Mailbox::path('url') . '/reply/' . MailboxRead::path('url') . '/' . $id),
                        'mark_important'  => url(Mailbox::path('url') . '/reply/mark-important/' . $id),
                        'move_trash'  => url(Mailbox::path('url') . '/reply/move-trash/' . $id),
                    ]
                ];
            }

            return $data;
        }
    }

    public static function addToTable()
    {
        $response           = array();
        $validate = self::validate(null,'.*');
        $validator          = Validator::make(request()->all(), $validate['rules'], $validate['messages'](), $validate['attributes']());

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
                        'mailbox_id'  => Encryption::decode(request('mailbox_id')),
                        'from'  => Auth::user()->id,
                        'recipient'  => $recipient,
                        'message'  => trim(request('message')),
                    ];
                }

                $add =  MailboxReply::insert($values);

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
           } catch (\Throwable $th) {
                        throw $th;
                    }
        }
        return $response;
    }
}
