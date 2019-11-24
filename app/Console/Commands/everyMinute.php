<?php

namespace App\Console\Commands;

use Webklex\IMAP\Facades\Client;
use Illuminate\Console\Command;

use App\Models\User;
use App\Models\Project;
use App\Models\Ticket;

use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

use App\Exceptions\ItemNotCreatedException;
use App\Exceptions\ItemNotUpdatedException;
use App\Exceptions\InvalidDataException;

use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\File;
use Illuminate\Http\UploadedFile;

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
        $aMessage = $aFolder->query()->unseen()->setFetchAttachment(false)->get();
        foreach($aMessage as $oMessage){
            $emailData = [];
            $emailData['email_id'] = $oMessage->getMessageId();
            $emailData['reference'] = $oMessage->getAttributes()['references'];
            $emailData['subject'] = $oMessage->getSubject();
            $emailData['mail'] = $oMessage->getFrom()[0]->mail;
            $emailData['personal'] = $oMessage->getFrom()[0]->personal;
            // echo 'Attachments: '.$oMessage->getAttachments()->count().'<br />';
            $emailData['body'] =  $oMessage->getHTMLBody(true);
            
            // attachments
            $emailData['attachmentPaths'] = [];
            foreach ($oMessage->getAttachments() as $oAttachment) {
                dd($oAttachment->getExtension());
                // validate extention
                if (in_array($oAttachment->getExtension(), ['png', 'jpg', 'jpeg', 'txt', 'csv', 'docx', 'doc', 'xlsx', 'xls']) ) {
                    throw new InvalidDataException([
                        'file extension' => $oAttachment->getExtension()
                        ],
                        'file extension not allowed'
                    );
                }
                // storage
                $attachmentPath = storage_path('app/public/attachments/' . $oMessage->getMessageId() . '/' . $oAttachment->name);
                $dirName = dirname($attachmentPath);
                if (!is_dir($dirName))
                    mkdir($dirName, 0755, true);
                

                $filepath = 'public/attachments/' . $oMessage->getMessageId() . '/' . $oAttachment->name;
                $pullfile = \Storage::put($filepath, $oAttachment->content, 'public');

                // validate file 
                $attached = $this->pathToUploadedFile($attachmentPath);
                $request = new Request([
                'file' => $attached
                ]);
                $validator = \Validator::make($request->all(), ['file' => 'clamav']);
                if ($validator->fails()){
                    \Storage::delete($filepath);
                    throw new InvalidDataException([
                        'mail' => $emailData
                        ],
                        'Attached file not secure'
                    );
                }

                $emailData['attachmentPaths'][] = $attachmentPath;
            }

            $emailData['project'] = $this->getProjectByClientEmail($emailData);

            if ($oMessage->getInReplyTo()) {
                $this->updateTicket($emailData);
            } else {
                $this->createNewTicket($emailData);
            }
        }
    }

    private function getProjectByClientEmail($emailData)
    {
        $client = $this->getClient($emailData);

        $project = Project::where('name', 'other')
                          ->where('owner_id', $client->id)
                          ->first();

        if (! $project) {
            $project = $this->createOtherProject($client);
        }

        return $project;
    }

    private function createOtherProject($client)
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
            throw new ItemNotCreatedException('Project', $ex->getMessage());
        }

        return $project;
    }

    private function getClient($emailData)
    {
        $client = User::where('email', $emailData['mail'])
                      ->where('type', 'client')
                      ->first();

        if (! $client) {
            $client = $this->createNewClient($emailData);
        }

        return $client;
    }

    private function createNewClient($emailData)
    {
        $user = new User();
        $user->name = $emailData['personal'];
        $user->email = $emailData['mail'];
        $user->password = Hash::make('123456'); // our default password
        $user->type = 'client';
        $user->created_by = 1;
        $user->created_at = Carbon::now();

        try {
            $user->save();
        } catch (Exception $ex) {
            throw new ItemNotCreatedException('User', $ex->getMessage());
        }

        return $user;
    }

    private function createNewTicket($emailData){
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
            throw new ItemNotCreatedException('Ticket', $ex->getMessage());
        }

        echo nl2br('email: '.$emailData['subject'].' is inserted as a ticket id = '.$ticket->id);
        echo "<br>";
    }

    private function updateTicket($emailData)
    {
        $subReference = explode(' ', $emailData['reference']);
        $subReference[0] = trim($subReference[0], '<');
        $subReference[0] = trim($subReference[0], '>');      
        $ticket = Ticket::where('email_id', 'like', '%' . getStrBefore('.', $subReference[0]) . '%')
                        ->first();
        if (! $ticket) {
            $this->createNewTicket($emailData);
        } else {
            $ticket->description .= ' </br> ****reply**** </br> '.$emailData['body'];
            try {
                $ticket->save();
            } catch (Exception $th) {
                throw new ItemNotUpdatedException('Ticket', $ex->getMessage());
            }

            echo nl2br('email: '.$emailData['subject'].' is updated in the ticket id = '.$ticket->id);
            echo "<br>";
        }
    }

    public function pathToUploadedFile( $path, $public = false )
    {
        $name = File::name( $path );

        $extension = File::extension( $path );

        $originalName = $name . '.' . $extension;

        $mimeType = File::mimeType( $path );

        $size = File::size( $path );

        $error = null;

        $test = $public;

        $object = new UploadedFile( $path, $originalName, $mimeType, $size, $error, $test );

        return $object;
  }
}
