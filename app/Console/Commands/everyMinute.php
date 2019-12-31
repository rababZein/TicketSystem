<?php

namespace App\Console\Commands;

use Webklex\IMAP\Facades\Client;
use Illuminate\Console\Command;

use App\Models\User;
use App\Models\Project;
use App\Models\Ticket;
use App\Models\Ticket_file;
use App\Models\Ticket_mail;

use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

use App\Exceptions\ItemNotCreatedException;
use App\Exceptions\ItemNotUpdatedException;
use App\Exceptions\InvalidDataException;

use App\Jobs\User\NewAccountJob;

use Validator;

class everyMinute extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'minute:refreshMailBoxToCreateTickets';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'To fetch new mails to create new tickets';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $oClient = Client::account('default');
        $oClient->connect();
        $aFolder = $oClient->getFolder('INBOX');

        $aMessage = $aFolder->query()->unseen()->OLD()->limit(10)->setFetchAttachment(true)->leaveUnread()->get();
        
        foreach($aMessage as $oMessage){
            $emailData = [];
            $emailData['email_id'] = $oMessage->getMessageId();
            $emailData['reference'] = $oMessage->getAttributes()['references'];
            $emailData['subject'] = $oMessage->getSubject();
            $emailData['email'] = $oMessage->getFrom()[0]->mail;
            $emailData['personal'] = $oMessage->getFrom()[0]->personal;
            $emailData['body'] =  $oMessage->getHTMLBody(true);
            
            // attachments
            $emailData['attachmentPaths'] = [];
            foreach ($oMessage->getAttachments() as $oAttachment) {
                // validate extention
                if (in_array($oAttachment->getExtension(), ['png', 'jpg', 'jpeg', 'txt', 'csv', 'docx', 'doc', 'xlsx', 'xls']) ) {
                // storage
                    $attachmentPath = storage_path('app/public/attachments/' . $oMessage->getMessageId() . '/' . $oAttachment->name);
                    $dirName = dirname($attachmentPath);
                    if (!is_dir($dirName))
                        mkdir($dirName, 0755, true);
                    
                    $fp = fopen($attachmentPath, "wb");
                    file_put_contents($attachmentPath, $oAttachment->content);
                    fclose($fp);

                    $emailData['attachmentPaths'][] = $attachmentPath;
                } else {
                    $oMessage->setFlag(['Seen']);
                    throw new ItemNotCreatedException('Ticket_file', 'unallaow file type');
                }
            }

            $emailData['project'] = $this->getProjectByClientEmail($emailData, $oMessage);

            if ($oMessage->getInReplyTo()) {
                $this->updateTicket($emailData, $oMessage);
            } else {
                $this->createNewTicket($emailData, $oMessage);
            }

            //Move the current Message to 'INBOX.read'
            $oMessage->setFlag(['Seen']);
            $oMessage->moveToFolder('TICKETS');
        }
    }

    private function getProjectByClientEmail($emailData, $oMessage)
    {
        $client = $this->getClient($emailData, $oMessage);

        /**
         * if client has one project with open status add new tickets to it
         */
        $count = Project::where('owner_id', $client->id)->count();
        if ($count == 1) {
            return Project::where('owner_id', $client->id)
                          ->first();
        }

        /**
         * else return other project
         */
        $project = Project::where('name', 'other')
                          ->where('owner_id', $client->id)
                          ->first();

        /**
         * else create new project with name other
         */
        if (! $project) {
            $project = $this->createOtherProject($client, $oMessage);
        }

        return $project;
    }

    private function createOtherProject($client, $oMessage)
    {
        $project = new Project();
        $project->name = 'other';
        $project->owner_id = $client->id;
        $project->description = 'This for a collection of tickets to be filtered';
        $project->created_by = 1;
        $project->created_at = Carbon::now();
        $project->task_rate = 0;
        $project->budget_hours = 0;

        try {
            $project->save();
        } catch (Exception $ex) {
            $oMessage->setFlag(['Seen']);
            throw new ItemNotCreatedException('Project', $ex->getMessage());
        }

        return $project;
    }

    private function getClient($emailData, $oMessage)
    {
        $client = User::where('email', $emailData['email'])
                      ->where('type', 'client')
                      ->first();

        if (! $client) {
            $this->validateClientData($emailData, $oMessage);
            $client = $this->createNewClient($emailData, $oMessage);
        }

        return $client;
    }

    private function createNewClient($emailData, $oMessage)
    {
        $user = new User();
        $user->name = $emailData['personal'];
        $user->email = $emailData['email'];
        $password = Hash::make(str_random(8));
        $user->password = $password;
        $user->type = 'client';
        $user->created_by = 1;
        $user->created_at = Carbon::now();

        try {
            $user->save();
        } catch (Exception $ex) {
            $oMessage->setFlag(['Seen']);
            throw new ItemNotCreatedException('User', $ex->getMessage());
        }

        // no-need to send mail now, user will click forget password
        //NewAccountJob::dispatch($user, $password);

        return $user;
    }

    private function validateClientData($emailData, $oMessage)
    {
        $validator = Validator::make($emailData, [
            'email' => 'string|email|unique:users'
        ]);

        if ($validator->fails()) {
            $oMessage->setFlag(['Seen']);
            throw new ItemNotCreatedException('User', $validator->errors());
        }

    }

    private function createNewTicket($emailData, $oMessage)
    {
        $ticket = new Ticket();
        $ticket->email_id = $emailData['email_id'];
        $ticket->project_id = $emailData['project']->id;
        $ticket->name = $emailData['subject'];
        $ticket->description = $emailData['body'];
        $ticket->created_by = 1;
        $ticket->created_at = Carbon::now();

        try {
            $ticket->save();
        } catch (Exception $ex) {
            $oMessage->setFlag(['Seen']);
            throw new ItemNotCreatedException('Ticket', $ex->getMessage());
        }
        
        // insert attachment
        $this->insertAttachmentFiles($ticket, $emailData, $oMessage);

        // insert cc 
        $this->insertCCMails($ticket, $oMessage);

        echo nl2br('email: '.$emailData['subject'].' is inserted as a ticket id = '.$ticket->id);
        echo "<br>";
    }

    private function insertAttachmentFiles($ticket, $emailData, $oMessage)
    {
        if (isset($emailData['attachmentPaths'])) {
            foreach ($emailData['attachmentPaths'] as $attachmentPath) {
                $file = new Ticket_file();
                $file->attachment_path = $attachmentPath;
                $file->ticket_id = $ticket->id;
                $file->created_by = 1;

                try {
                    $file->save();
                } catch (Exception $ex) {
                    $oMessage->setFlag(['Seen']);
                    throw new ItemNotCreatedException('Ticket_file', $ex->getMessage());
                }
            }
        }

    }

    private function insertCCMails($ticket, $oMessage)
    {
        if ($oMessage->getCc()) {
            foreach ($oMessage->getCc() as $ccMail) {
                $ticket_cc_mail = new Ticket_mail();
                $ticket_cc_mail->email = $ccMail->mail;
                $ticket_cc_mail->ticket_id = $ticket->id;
                $ticket_cc_mail->created_by = 1;
                try {
                    $ticket_cc_mail->save();
                } catch (Exception $ex) {
                    $oMessage->setFlag(['Seen']);
                    throw new ItemNotCreatedException('Ticket_mail', $ex->getMessage());
                }
            }
        }
    }

    private function updateTicket($emailData, $oMessage)
    {
        $subReference = explode(' ', $emailData['reference']);
        $subReference[0] = trim($subReference[0], '<');
        $subReference[0] = trim($subReference[0], '>');      
        $ticket = Ticket::where('email_id', 'like', '%' . getStrBefore('.', $subReference[0]) . '%')
                        ->first();
        if (! $ticket) {
            $this->createNewTicket($emailData, $oMessage);
        } else {
            $ticket->description .= ' </br> ****reply**** </br> '.$emailData['body'];
            try {
                $ticket->save();
            } catch (Exception $th) {
                $oMessage->setFlag(['Seen']);
                throw new ItemNotUpdatedException('Ticket', $ex->getMessage());
            }

            // insert attachment
            $this->insertAttachmentFiles($ticket, $emailData, $oMessage);

            // insert cc 
            $this->insertCCMails($ticket, $oMessage);

            echo nl2br('email: '.$emailData['subject'].' is updated in the ticket id = '.$ticket->id);
            echo "<br>";
        }
    }
}
