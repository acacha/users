<?php

namespace Acacha\Users\Http\Controllers;

use Acacha\Users\Services\GoogleApps\GoogleAppsService;

use Acacha\Users\Traits\HasPaginator;
use Google;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Class GoogleAppsUsersController.
 *
 * @package Acacha\Users\Http\Controllers
 */
class GoogleAppsUsersController extends Controller
{

    use HasPaginator;

    /**
     * Google apps service.
     *
     * @var
     */
    public $service;

    /**
     * GoogleAppsUsersController constructor.
     *
     * @param $service
     */
    public function __construct(GoogleAppsService $service)
    {
        $this->service = $service;
    }


    /**
     * Show Google apps users.
     */
    public function index()
    {
        $this->authorize('see-google-apps-users');
        $data = [];

        return view('acacha_users::googleApps.google', $data);
    }

    /**
     * Sync local database from Google Apps/Suite data.
     */
    public function localSync(Request $request)
    {
        $this->authorize('sync-google-apps-users');

        return $this->service->localSync();
    }

    /**
     * List Google apps users.
     */
    public function all(Request $request)
    {
        $this->authorize('list-google-apps-users');

        $perPage = config('users.google_apps_users_per_page' , 15);
        if ($request->has('perPage')) {
            $perPage = $request->input('perPage');
        }

        $users = $this->service->getUsers($perPage);

        return $this->paginate($users, $perPage);
    }

    /**
     * Check google apps connection.
     *
     * @return array
     */
    public function check()
    {
        $this->authorize('check-google-apps-connection');

        return $this->service->getConnectionState();
    }

    public function google3()
    {
        $directory = Google::make('directory');
        dump($directory->users);
    }

    public function google5()
    {
        //List all users: https://developers.google.com/apps-script/advanced/admin-sdk-directory

//        page = AdminDirectory.Users.list({
//        domain: 'example.com',
//      orderBy: 'givenName',
//      maxResults: 100,
//      pageToken: pageToken
//    });
    }

    public function google4()
    {
        //Example list 100 users
        $directory = Google::make('directory');
        dump(get_class($directory->users));
        dump($directory);
        try {
            $r = $directory->users->listUsers([
                'domain' => 'iesebre.com',
                'maxResults' => 500
            ]);
            dump($r);
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function google7()
    {
        //Example list 500 users. max results maxim value is 500
        $directory = Google::make('directory');
        dump(get_class($directory->users));
        try {
            $r = $directory->users->listUsers([
                'domain' => 'iesebre.com',
                'maxResults' => 500
            ]);
            dump($r);
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function google8()
    {
        // Count total number of users
        $directory = Google::make('directory');
        $count = 0;
        $pageToken = null;
        do {
            try {
                $r = $directory->users->listUsers([
                    'domain' => 'iesebre.com',
                    'maxResults' => 500,
                    'pageToken' => $pageToken
                ]);
                $pageToken = $r->nextPageToken;
//                dump($pageToken);
//                dump($r);
//                dump($r->users);
                $count = $count + count($r->users);
                dump('count: ' . $count);
            } catch (\Exception $e) {
                dd($e);
            }
        } while ($pageToken);

        dd($count);
    }

    public function google10()
    {
        return $this->service->localSync();
    }

    public function esborrar()
    {
        $googleClient = Google::getClient();
//        dd($googleClient);
        $directory = Google::make('directory');
//        dd($directory);

        $email = "stur@iesebre.com";
        try {
            $r = $directory->users->get($email);
            if($r) {
                echo "Name: ".$r->name->fullName."<br/>";
                echo "Suspended?: ".(($r->suspended === true) ? 'Yes' : 'No')."<br/>";
                echo "Org/Unit/Path: ".$r->orgUnitPath."<br/>";
            } else {
                echo "User does not exist: $email<br/>";
            }
        } catch (\Exception $e) {
            dd('exception!!!');
        }

        // if the user doesn't exist, it's safe to create the new user

//        $user_to_impersonate = 'adminaccount@example.com';
//        $scopes = array('https://www.googleapis.com/auth/admin.directory.user');
//        $cred = new Google_Auth_AssertionCredentials(
//            $service_account_name,
//            $scopes,
//            $key,
//            'notasecret', // Default P12 password
//            'http://oauth.net/grant_type/jwt/1.0/bearer', // Default grant type
//            $user_to_impersonate
//        );

//$dir = new Google_Service_Directory($client);
//$email = "possiblities@example.com";
//$r = $dir->users->get($email);
//if($r) {
//    echo "Name: ".$r->name->fullName."<br/>";
//    echo "Suspended?: ".(($r->suspended === true) ? 'Yes' : 'No')."<br/>";
//    echo "Org/Unit/Path: ".$r->orgUnitPath."<br/>";
//} else {
//    echo "User does not exist: $email<br/>";
//    // if the user doesn't exist, it's safe to create the new user
//}
    }

}