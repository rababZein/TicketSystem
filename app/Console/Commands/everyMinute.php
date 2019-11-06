<?php

namespace App\Console\Commands;

use Webklex\IMAP\Facades\Client;
use Illuminate\Console\Command;

use App\Models\User;
use App\Models\Project;
use App\Models\Ticket;

use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class everyMinute extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'minute:updateMailBox';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'To fetch new mails';

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
            $emailData['subject']= $oMessage->getSubject();
            $emailData['mail']= $oMessage->getFrom()[0]->mail;
            $emailData['personal']= $oMessage->getFrom()[0]->personal;
            // echo 'Attachments: '.$oMessage->getAttachments()->count().'<br />';
            $emailData['body']=  $oMessage->getHTMLBody(true);

            $emailData['project'] = $this->getProjectByClientEmail($emailData);

            $this->createTicket($emailData);
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

        $project->save();

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

        $user->save();

        return $user;
    }

    private function createTicket($emailData){
        $ticket = new Ticket();
        $ticket->project_id = $emailData['project']->id;
        $ticket->name = $emailData['subject'];
        $ticket->description = $emailData['body'];
        $ticket->created_by = 1;
        $ticket->created_at = Carbon::now();

        $ticket->save();

        echo nl2br('email: '.$emailData['subject'].' is inserted as a ticket id = '.$ticket->id);
        echo "<br>";
    }
}
