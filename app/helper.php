<?php
if(!function_exists('getStrBefore')) {
    function getStrBefore($sub, $str) {
        return substr($str, 0, strpos($str, $sub));
    }
}

if(!function_exists('arrayPaginator')) {
    function arrayPaginator($array, $request) {
    $page = Illuminate\Support\Facades\Input::get('page', 1);
    $perPage = 10;
    $offset = ($page * $perPage) - $perPage;

    return new Illuminate\Pagination\LengthAwarePaginator(array_slice($array, $offset, $perPage, true), count($array), $perPage, $page,
        ['path' => $request->url(), 'query' => $request->query()]);
  }
}

if(!function_exists('saveSysMailToSentFolder')) {
    function saveSysMailToSentFolder($to, $data) {

        $oClient = Webklex\IMAP\Facades\Client::account('default');
        $oClient->connect();
        $aFolder = $oClient->getFolder('[Gmail]/Sent Mail');
        $date = now()->format('d-M-Y H:i:s O');

        $subject = $data['subject'];

        $body = '';
        foreach ($data['introLines'] as $introLine) {
            $body .= $introLine."\r\n";
        }

        $body .= $data['actionText']."\r\n".$data['actionUrl']."\r\n";

        foreach ($data['outroLines'] as $outroLine) {
            $body .= $outroLine."\r\n";
        }
        

        /**
         * \\Seen" or null to be un-seen
         */
        $aFolder->appendMessage( "From: ".config('imap.accounts')['default']['username']."\r\n"
        . "To: ".$to."\r\n"
        . "Subject: ".$subject."\r\n"
        . "\r\n"
        . $body."\r\n", "\\Seen", $date
        );
  }
}
?>